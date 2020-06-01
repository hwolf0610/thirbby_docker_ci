<?php 
$this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/jquery.timepicker.css"/>
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
<!-- BEGIN sidebar -->
<?php $this->load->view(ADMIN_URL.'/sidebar');
 
if($this->input->post()){
  foreach ($this->input->post() as $key => $value) {
    $$key = @htmlspecialchars($this->input->post($key));
  } 
} else {
  $FieldsArray = array('entity_id','name','business_type','business_category','diet_category','phone_number','email','capacity','no_of_table','no_of_hall','hall_capacity','address','landmark','latitude','longitude','state','country','city','zipcode','amount_type','amount','enable_hours','timings','image','is_veg','driver_commission');
  foreach ($FieldsArray as $key) {
    $$key = @htmlspecialchars($edit_records->$key);
  }
}
if(isset($edit_records) && $edit_records !="")
{
    $add_label    = "Edit ".$this->module_name;        
    $form_action      = base_url().ADMIN_URL.'/'.$this->controller_name."/edit/".str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->entity_id));
}
else
{
    $add_label    = "Add ".$this->module_name;       
    $form_action      = base_url().ADMIN_URL.'/'.$this->controller_name."/add";
}
$usertypes = getUserTypeList();
?>

<div class="page-content-wrapper">
        <div class="page-content">            
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title"><?php echo $this->module_name ?></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">
                            Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name?>/view"><?php echo $this->module_name ?></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo $add_label;?> 
                        </li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN VALIDATION STATES-->
                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption"><?php echo $add_label;?></div>
                        </div>
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="<?php echo $form_action;?>" id="form_add<?php echo $this->prefix; ?>" name="form_add<?php echo $this->prefix; ?>" method="post" class="form-horizontal" enctype="multipart/form-data" >
                                <div id="iframeloading" style= "display: none; position: absolute; top: 0px; left: 0px; width: 100%; height: 100%;">
                                     <img src="<?php echo base_url();?>assets/admin/img/loading-spinner-grey.gif" alt="loading" style="top: 50%; position: relative; left: 50%;"  />
                                </div>
                                <div class="form-body"> 
                                    <?php if(!empty($Error)){?>
                                    <div class="alert alert-danger"><?php echo $Error;?></div>
                                    <?php } ?>                                  
                                    <?php if(validation_errors()){?>
                                    <div class="alert alert-danger">
                                        <?php echo validation_errors();?>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Business Name<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="hidden" id="entity_id" name="entity_id" value="<?php echo $entity_id;?>" />
                                            <input type="text" name="name" placeholder="Enter Business Name" id="name" value="<?php echo $name;?>" maxlength="249" data-required="1" class="form-control"/>
                                        </div>
                                    </div>    
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Business Type<span class="required">*</span></label>
                                        <div class="col-md-4">
                                                <select name="business_type" id="business_type" class="form-control select_country">
                                                    <?php $data = array( ['name'=>'Select...'],['name' =>'Drink'],['name' =>'Eat'],['name' =>'Play']);
                                                        $selval = $business_type;
                                                        foreach ($data as $key => $value) { ?>
                                                            <option value="<?php echo $value['name']; ?>"  <?php echo ($value['name'] == $selval)?'selected':''; ?>><?php echo $value['name']; ?></option>
                                                    <?php } ?>
                                                </select>  
                                        </div>
                                    </div>    
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Business Category<span class="required">*</span></label>
                                        <div class="col-md-4">
                                                <select style="height:120px" name="business_category[]" multiple id="business_category" class="form-control select_country">
                                                    <?php $data = array();
                                                        $selval = 'Eat';
                                                        foreach ($data as $key => $value) { ?>
                                                            <option value="<?php echo $value['name']; ?>"  <?php echo ($value['name'] == $selval)?'selected':''; ?>><?php echo $value['name']; ?></option>
                                                    <?php } ?>
                                                </select>  
                                        </div>
                                    </div>            
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Phone Number<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="phone_number" placeholder="Enter Phone Number" id="phone_number" value="<?php echo $phone_number;?>" maxlength="20" data-required="1" class="form-control"/>
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Email<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="email" name="email" id="email" placeholder="Enter Email" value="<?php echo $email;?>" maxlength="100" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Image</label>
                                        <div class="col-md-4">
                                            <input type="file" name="Image" id="Image" accept="image/*" data-msg-accept="Please upload a valid file type." onchange="readURL(this)"/>
                                            <p class="help-block">Only JPG, JPEG, PNG & GIF files are allowed.<br /> Maximum upload file size 5MB.</p>
                                            <span class="error" id="errormsg" style="display: none;">Sorry, Your file extention is invalid.</span>
                                            <div id="img_gallery"></div>
                                            <img id="preview" height='100' width='150' style="display: none;"/>
                                            <input type="hidden" name="uploaded_image" id="uploaded_image" value="<?php echo isset($image)?$image:''; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="old">
                                        <label class="control-label col-md-3"></label>
                                        <div class="col-md-4">
                                            <?php if(isset($image) && $image != '') {?>
                                                    <span class="block">You have previously selected:</span>
                                                            <img id='oldpic' class="img-responsive" src="<?php echo base_url().'uploads/'.$image;?>">
                                            <?php }  ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Capacity(People)<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="capacity" id="capacity" placeholder="Enter Capacity" value="<?php echo $capacity ?>" maxlength="20" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                        <label class="control-label col-md-3">No Of Table<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="no_of_table" id="no_of_table" placeholder="Enter No Of Table" value="<?php echo $no_of_table ?>" maxlength="20" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">No Of Hall</label>
                                        <div class="col-md-4">
                                            <input type="text" name="no_of_hall" id="no_of_hall" placeholder="Enter No Of Hall" value="<?php echo $no_of_hall ?>" maxlength="20" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    -->
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Hall Capacity</label>
                                        <div class="col-md-4">
                                            <input type="text" placeholder="Enter Hall Capacity" name="hall_capacity" id="hall_capacity" value="<?php echo $hall_capacity ?>" maxlength="20" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Address<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Enter Address Here.." name="address" id="address" value="<?php echo $address ?>" maxlength="255"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Neighborhood<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Enter Landmark Here" name="landmark" id="landmark" value="<?php echo $landmark ?>" maxlength="255"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Latitude<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Enter Latitude" name="latitude" id="latitude" value="<?php echo $latitude ?>" maxlength="50"/>
                                        </div>
                                        <a href="#basic" data-toggle="modal" class="btn red default"> Pick Latitude / Longitude </a>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Longitude<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Enter Longitude" name="longitude" id="longitude" value="<?php echo $longitude ?>" maxlength="50"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">ZipCode<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Enter ZipCode Here" name="zipcode" id="zipcode" value="<?php echo $zipcode ?>" maxlength="10"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Country<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="Enter Country" name="country" id="country" value="<?php echo $country; ?>" maxlength="50"/>
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label class="control-label col-md-3">State<span class="required">*</span></label>
                                        <div class="col-md-4">
                                             <input type="text" class="form-control" placeholder="Enter State" name="state" id="state" value="<?php echo $state ?>" maxlength="50"/>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label col-md-3">City<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="Enter City" name="city" id="city" value="<?php echo $city ?>" maxlength="50"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Routing Number<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="Enter Routing Number" name="routingNumber" id="routingNumber" value="<?php echo $city ?>" maxlength="50"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Account Number<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="Enter Account Number" name="accountNumber" id="accountNumber" value="<?php echo $city ?>" maxlength="50"/>
                                        </div>
                                    </div>
                                    <div class="form-group">  
                                        <label class="control-label col-md-3">Tax Amount Type<span class="required">*</span></label>                        
                                        <div class="col-sm-4">
                                                <p>
                                                  <input type="radio" name="amount_type" id="MPercentage"
                                                  <?php if (isset($amount_type) && $amount_type=="Percentage") echo "checked";?>
                                                  value="Percentage" checked="checked">&nbsp;&nbsp;Percentage
                                                </p>
                                                <p>
                                                  <input type="radio" name="amount_type" id="MAmount"
                                                  <?php if (isset($amount_type) && $amount_type=="Amount") echo "checked";?>
                                                  value="Amount">&nbsp;&nbsp;Amount
                                                </p>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group"> 
                                        <label class="control-label col-md-3">Tax<span class="required">*</span></label>
                                        <div class="col-sm-8 form-markup">
                                              <label id="Percentage">Percentage (%)</label>
                                              <label id="Amount" style="display:none">Amount ($)</label>
                                              <br>
                                              <input type="text" name="amount" id="amount" value="<?php echo $amount ?>" maxlength="10" data-required="1" class="form-control"/>  
                                        </div>  
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="control-label col-md-3">Driver Commission (%)<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="Enter Driver Commission" name="driver_commission" id="driver_commission" value="<?php echo $driver_commission ?>" maxlength="10"/>
                                        </div>
                                    </div> -->
                                    <!--<div class="form-group">
                                        <label class="control-label col-md-3">Food Type<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="radio" name="is_veg" id="is_veg" value="1" checked="" <?php echo ($is_veg)?($is_veg == '1')?'checked':'':'checked' ?>>Veg
                                            <input type="radio" name="is_veg" id="non-veg" value="0" <?php echo ($is_veg == '0')?'checked':'' ?>>Non veg
                                            <input type="radio" name="is_veg" id="non-veg" value="" <?php echo ($is_veg == '')?'checked':'' ?>>Both
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Diet Category<span class="required">*</span></label>
                                        <div class="col-md-4">
                                                <select style="height:140px" name="diet_category[]" multiple id="business_category" class="form-control select_country">
                                                    <?php $data = array( ['name'=>'vegetarian'],['name' =>'vegan'],['name' =>'organic'],['name' =>'gluten-free'],['name' =>'halal'],['name' =>'kosher']);
                                                        $datas = explode(",",$diet_category);
                                                        $hash = array();
                                                        foreach ($datas as $item)
                                                            $hash[$item] = "YES";
                                                        foreach ($data as $key => $value) { ?>
                                                            <option value="<?php echo $value['name']; ?>"  <?php echo ($hash[$value['name']])?'selected':''; ?>><?php echo $value['name']; ?></option>
                                                    <?php } ?>
                                                </select>  
                                        </div>
                                    </div>         
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Enable Restaurant Hours<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="radio" <?php echo ($enable_hours)?($enable_hours == '1')?'checked':'':'checked' ?>  name="enable_hours" id="radioTrue" value="1" class="company-hours"> <label for="radioTrue">Yes</label>
                                            <input type="radio" <?php echo ($enable_hours == '0')?'checked':'' ?>  name="enable_hours" id="radioFalse" value="0" class="company-hours"> <label for="radioFalse">No</label>
                                        </div>
                                    </div> 
                                    <div class="form-group company-timing" style="<?php echo ($enable_hours == '0')?'display:none':'display:block' ?>">
                                        <label class="control-label col-md-3">Restaurant Timings</label>
                                        <?php if(empty($_POST['timings'])){
                                            $business_timings = unserialize(html_entity_decode($timings));
                                        }else{
                                            $timingsArr = $_POST['timings'];
                                            $newTimingArr = array();
                                            foreach($timingsArr as $key=>$value) {
                                                if(isset($value['off'])) {
                                                    $newTimingArr[$key]['open'] = '';
                                                    $newTimingArr[$key]['close'] = '';
                                                    $newTimingArr[$key]['off'] = '0';
                                                } else {
                                                    if(!empty($value['open']) && !empty($value['close'])) {
                                                        $newTimingArr[$key]['open'] = $value['open'];
                                                        $newTimingArr[$key]['close'] = $value['close'];
                                                        $newTimingArr[$key]['off'] = '1';
                                                    } else {
                                                        $newTimingArr[$key]['open'] = '';
                                                        $newTimingArr[$key]['close'] = '';
                                                        $newTimingArr[$key]['off'] = '0';
                                                    }
                                                }
                                            }
                                            $business_timings = $newTimingArr;
                                        }  ?>
                                        <div class="col-md-12">
                                             <table class="timingstable" width="100%" cellpadding="2" cellspacing="2">
                                                <tr>
                                                    <td style="border:0;"><strong>&nbsp;</strong></td>
                                                    <td style="border:0;" colspan="2">
                                                        <label class="checkbox" style="font-weight:bold;width:329px;padding:0;">
                                                            <input type="checkbox" id="clickSameHours">
                                                            Assign Monday Timings for all days </label><br/>
                                                        <span id="alertSpan" style="color: rgb(255, 0, 0); padding: 4px; font-size: 12px;"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border:0;"><strong>Monday</strong></td>
                                                    <td style="border:0;">
                                                        <div class="td-wrap">
                                                            <input type="text" class="ophrs" lesserThan="#monday_close_hours" id="monday_open_hours" name="timings[monday][open]" <?php echo (intval(@$business_timings['monday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['monday']['open']; ?>" placeholder="Select Opening Hours">
                                                        </div>
                                                        <div class="td-wrap">
                                                            <input type="text" class="clhrs" greaterThan="#monday_open_hours" placeholder="Select Closing Hours" <?php echo (intval(@$business_timings['monday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['monday']['close']; ?>" name="timings[monday][close]" id="monday_close_hours">
                                                        </div>
                                                    </td>
                                                    <td style="border:0;">
                                                        <label class="checkbox" style="width:100%;"><input type="checkbox" <?php echo (intval(@$business_timings['monday']['off'])) ? '' : 'checked="checked"'; ?> value="monday" class="close_bar_check" id="monday_close" name="timings[monday][off]">Mark to show close on Monday</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border:0;"><strong>Tuesday</strong></td>
                                                    <td style="border:0;">
                                                        <div class="td-wrap">
                                                            <input type="text" class="ophrs" lesserThan="#tuesday_close_hours" placeholder="Select Opening Hours"  <?php echo (intval(@$business_timings['tuesday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['tuesday']['open']; ?>" name="timings[tuesday][open]" id="tuesday_open_hours">
                                                        </div>
                                                        <div class="td-wrap">
                                                            <input type="text" class="clhrs" greaterThan="#tuesday_open_hours" placeholder="Select Closing Hours" <?php echo (intval(@$business_timings['tuesday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['tuesday']['close']; ?>" name="timings[tuesday][close]" id="tuesday_close_hours">
                                                        </div>
                                                    </td>
                                                    <td style="border:0;">
                                                        <label class="checkbox" style="width:100%;"><input type="checkbox" <?php echo (intval(@$business_timings['tuesday']['off'])) ? '' : 'checked="checked"'; ?> value="tuesday" class="close_bar_check" id="tuesday_close" name="timings[tuesday][off]">Mark to show close on Tuesday</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border:0;"><strong>Wednesday</strong></td>
                                                    <td style="border:0;">
                                                        <div class="td-wrap">
                                                            <input type="text" class="ophrs" placeholder="Select Opening Hours" <?php echo (intval(@$business_timings['wednesday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['wednesday']['open']; ?>" name="timings[wednesday][open]" id="wednesday_open_hours" lesserThan="#wednesday_close_hours">
                                                        </div>
                                                        <div class="td-wrap">
                                                            <input type="text" class="clhrs" placeholder="Select Closing Hours" <?php echo (intval(@$business_timings['wednesday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['wednesday']['close']; ?>" name="timings[wednesday][close]" id="wednesday_close_hours" greaterThan="#wednesday_open_hours">
                                                        </div>
                                                    </td>
                                                    <td style="border:0;">
                                                        <label class="checkbox" style="width:100%;"><input type="checkbox" <?php echo (intval(@$business_timings['wednesday']['off'])) ? '' : 'checked="checked"'; ?> value="wednesday" class="close_bar_check" id="wednesday_close" name="timings[wednesday][off]">Mark to show close on Wednesday</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border:0;"><strong>Thursday</strong></td>
                                                    <td style="border:0;">
                                                        <div class="td-wrap">
                                                            <input type="text" class="ophrs" placeholder="Select Opening Hours" <?php echo (intval(@$business_timings['thursday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['thursday']['open']; ?>" name="timings[thursday][open]" id="thursday_open_hours" lesserThan="#thursday_open_hours">
                                                        </div>
                                                        <div class="td-wrap">
                                                            <input type="text" class="clhrs" placeholder="Select Closing Hours" <?php echo (intval(@$business_timings['thursday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['thursday']['close']; ?>" name="timings[thursday][close]" id="thursday_close_hours" greaterThan="#thursday_close_hours">
                                                        </div>
                                                    </td>
                                                    <td style="border:0;">
                                                        <label class="checkbox" style="width:100%;"><input type="checkbox" <?php echo (intval(@$business_timings['thursday']['off'])) ? '' : 'checked="checked"'; ?> value="thursday" class="close_bar_check" id="thursday_close" name="timings[thursday][off]">Mark to show close on Thursday</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border:0;"><strong>Friday</strong></td>
                                                    <td style="border:0;">
                                                        <div class="td-wrap">
                                                            <input type="text" class="ophrs" placeholder="Select Opening Hours" <?php echo (intval(@$business_timings['friday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['friday']['open']; ?>" name="timings[friday][open]" id="friday_open_hours" lesserThan="#friday_open_hours">
                                                        </div>
                                                        <div class="td-wrap">
                                                            <input type="text" class="clhrs" placeholder="Select Closing Hours" <?php echo (intval(@$business_timings['friday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['friday']['close']; ?>" name="timings[friday][close]" id="friday_close_hours" greaterThan="#friday_close_hours">
                                                        </div>
                                                    </td>
                                                    <td style="border:0;">
                                                        <label class="checkbox" style="width:100%;"><input type="checkbox" <?php echo (intval(@$business_timings['friday']['off'])) ? '' : 'checked="checked"'; ?> value="friday" class="close_bar_check" id="friday_close" name="timings[friday][off]">Mark to show close on Friday</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border:0;"><strong>Saturday</strong></td>
                                                    <td style="border:0;">
                                                        <div class="td-wrap">
                                                            <input type="text" class="ophrs" placeholder="Select Opening Hours" <?php echo (intval(@$business_timings['saturday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['saturday']['open']; ?>" name="timings[saturday][open]" id="saturday_open_hours" lesserThan="#saturday_open_hours">
                                                        </div>
                                                        <div class="td-wrap">
                                                            <input type="text" class="clhrs" placeholder="Select Closing Hours" <?php echo (intval(@$business_timings['saturday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['saturday']['close']; ?>" name="timings[saturday][close]" id="saturday_close_hours" greaterThan="#saturday_close_hours">
                                                        </div>
                                                    </td>
                                                    <td style="border:0;">
                                                        <label class="checkbox" style="width:100%;"><input type="checkbox" <?php echo (intval(@$business_timings['saturday']['off'])) ? '' : 'checked="checked"'; ?> value="saturday" class="close_bar_check" id="saturday_close" name="timings[saturday][off]">Mark to show close on Saturday</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border:0;"><strong>Sunday</strong></td>
                                                    <td style="border:0;">
                                                        <div class="td-wrap">
                                                            <input type="text" class="ophrs" placeholder="Select Opening Hours"  <?php echo (intval(@$business_timings['sunday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['sunday']['open']; ?>" name="timings[sunday][open]" id="sunday_open_hours" lesserThan="#sunday_open_hours">
                                                        </div>
                                                        <div class="td-wrap">
                                                            <input type="text" class="clhrs" placeholder="Select Closing Hours"  <?php echo (intval(@$business_timings['sunday']['off'])) ? '' : 'disabled="disabled"'; ?> value="<?php echo @$business_timings['sunday']['close']; ?>" name="timings[sunday][close]" id="sunday_close_hours" greaterThan="#sunday_close_hours">
                                                        </div>
                                                    </td>
                                                    <td style="border:0;">
                                                        <label class="checkbox" style="width:100%;"><input type="checkbox" <?php echo (intval(@$business_timings['sunday']['off'])) ? '' : 'checked="checked"'; ?> value="sunday" class="close_bar_check" id="sunday_close" name="timings[sunday][off]">Mark to show close on Sunday</label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div> 
                                </div>    
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <input type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-success danger-btn">
                                        <a class="btn btn-danger danger-btn" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name?>/view">Cancel</a>
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM-->
                        </div>
                    </div>
                    <!-- END VALIDATION STATES-->
                </div>
            </div>
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Pick latitude longitude map</h4>
            </div>
            <div class="modal-body">                                               
                <form class="form-inline margin-bottom-10" action="#">
                    <div class="input-group">
                        <input type="text" class="form-control" id="gmap_geocoding_address" placeholder="address...">
                        <span class="input-group-btn">
                            <button class="btn blue" id="gmap_geocoding_btn"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
                <div id="gmap_geocoding" class="gmaps">
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>            
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url();?>assets/admin/scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<script src="//maps.google.com/maps/api/js?key=AIzaSyCGh2j6KRaaSf96cTYekgAD-IuUG0GkMVA" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/gmaps/gmaps.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {       
    Layout.init(); // init current layout
    $("#business_type").change(function () {
        var val = $(this).val();
        changeMultiple(val);
    });
    changeMultiple('<?php echo $business_type ?>');
});
function changeMultiple( val)
{
    <?php 
         $datas = explode(",",$business_category);
         $seldata = array();
         foreach ($datas as $item)
             $seldata[$item] = "Yes";
        $data = array( ['name'=>'American'],['name' =>'Cultural'],['name' =>'Fine Dining'],['name' =>'Casual Bites'],['name' =>'Quick']);
        foreach ($data as $key => $value) 
        {
            $selected = (($seldata[$value['name']] == "Yes")?'selected':'');
            $var1 = $var1. "<option value='" . $value['name'] ."' ". $selected . " >" . $value['name'] . "</option>";
        }            
        $data = array( ['name'=>'Coffee'],['name' =>'Bars'],['name' =>'Wine'],['name' =>'Cocktails'],['name' =>'Social Spots']);
        foreach ($data as $key => $value)  
        {
            $selected = (($seldata[$value['name']] == "Yes")?'selected':'');
            $var2 = $var2. "<option value='" . $value['name'] ."' ". $selected . " >" . $value['name'] . "</option>";
        }
        $data = array( ['name'=>'Shows'],['name' =>'Events'],['name' =>'Family Friendly'],['name' =>'Unique Experiences']);
        foreach ($data as $key => $value) 
        {
            $selected = (($seldata[$value['name']] == "Yes")?'selected':'');
            $var3 = $var3. "<option value='" . $value['name'] ."' ". $selected . ">". $value['name'] . "</option>";
        }
        ?>
        var1 = "<?php echo $var1 ?>";
        var2 = "<?php echo $var2 ?>";
        var3 = "<?php echo $var3 ?>";
        if (val == "Select...") {
            $("#business_category").html('');
        } else if (val == "Drink") {
            $("#business_category").html(var1);
        } else if (val == "Eat") {
            $("#business_category").html(var2);
        } else if (val == "Play") {
            $("#business_category").html(var3);
        }
}
$("#basic").on("shown.bs.modal", function () {    
    mapGeocoding(); // init geocoding Maps
});
var mapGeocoding = function () {    
    var map = new GMaps({
        div: '#gmap_geocoding',
        lat: 23.0225,
        lng: 72.5714,
        click: function (e) {           
           placeMarker(e.latLng);
        }       
    }); 
    map.addMarker({
        lat: 21.3891,
        lng: 72.5714,
        title: 'Ahmedabad',
        draggable: true,
        dragend: function(event) {
            $("#latitude").val(event.latLng.lat());
            $("#longitude").val(event.latLng.lng());
        }
    });   
    function placeMarker(location) {                       
        map.removeMarkers();
        $("#latitude").val(location.lat());
        $("#longitude").val(location.lng());
        map.addMarker({
            lat: location.lat(),
            lng: location.lng(),
            draggable: true,
            dragend: function(event) {
                $("#latitude").val(event.latLng.lat());
                $("#longitude").val(event.latLng.lng());
            }    
        })
    }
    var handleAction = function () {
        var text = $.trim($('#gmap_geocoding_address').val());
        GMaps.geocode({
            address: text,
            callback: function (results, status) {
                if (status == 'OK') { 
                    map.removeMarkers();                   
                    var latlng = results[0].geometry.location;                    
                    map.setCenter(latlng.lat(), latlng.lng());
                    map.addMarker({
                        lat: latlng.lat(),
                        lng: latlng.lng(),         
                        draggable: true,
                        dragend: function(event) {
                            $("#latitude").val(event.latLng.lat());
                            $("#longitude").val(event.latLng.lng());
                        }
                    });
                    $("#latitude").val(latlng.lat());
                    $("#longitude").val(latlng.lng());
                }
            }
        });
    }
    $('#gmap_geocoding_btn').click(function (e) {
        e.preventDefault();
        handleAction();
    });
    $("#gmap_geocoding_address").keypress(function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            e.preventDefault();
            handleAction();
        }
    });
}
// Markup Radio Button Validation
function markup () {
  if($("input[name=amount_type]:checked").val() == "Percentage" ){
          $("#Amount").hide();
          $("#Percentage").show();     
  }else if($("input[name=amount_type]:checked").val() == "Amount" ){
          $("#Percentage").hide();
          $("#Amount").show();
  }
}
$(document).ready(function(){
   markup();
});
$("input[name=amount_type]:radio").click(function(){
  markup();
  if($("input[name=amount_type]:checked").val() == "Percentage" ){    
    $("#amount").val('');          
  }else if($("input[name=amount_type]:checked").val() == "Amount" ){
    $("#amount").val('');           
  }
});
//for company timing
$(function () {

    $('#monday_open_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#monday_close_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#tuesday_open_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#tuesday_close_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#wednesday_open_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#wednesday_close_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#thursday_open_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#thursday_close_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#friday_open_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#friday_close_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#saturday_open_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#saturday_close_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#sunday_open_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});
    $('#sunday_close_hours').timepicker({timeFormat: "HH:mm", controlType: 'select', ampm: true, stepMinute: 5,showButtonPanel:  false});

    $(".close_bar_check").change(function () {
        var dy = this.value;

        if (this.checked) {
            $("#" + dy + "_open_hours").val('');
            $("#" + dy + "_close_hours").val('');
            $("#" + dy + "_open_hours").attr('disabled', 'disabled');
            $("#" + dy + "_close_hours").attr('disabled', 'disabled');
        } else {
            $("#" + dy + "_open_hours").removeAttr('disabled');
            $("#" + dy + "_close_hours").removeAttr('disabled');
        }
        return false;
    });
    $("#clickSameHours").change(function () {
        $('#alertSpan').html('');
        if (this.checked) {
            var ophrs = $('#monday_open_hours').val();
            var clhrs = $('#monday_close_hours').val();
            if (ophrs != '' && clhrs != '') {
                $('#alertSpan').html('');
                $(".close_bar_check").each(function (i) {
                    this.checked = false;
                    var parent = $(this).closest('tr');
                    $(parent).find('input').eq(0).removeAttr('disabled');
                    $(parent).find('input').eq(1).removeAttr('disabled');
                    $(parent).find('input').eq(0).val(ophrs);
                    $(parent).find('input').eq(1).val(clhrs);
                });
            } else {
                $('#alertSpan').html("Please select the opening and closing hours for monday");
                $(this).removeAttr("checked");
            }
        } else {
            $('#alertSpan').html('');
        }
        return false;
    });
});
$('.company-hours').click(function(){
    if($(this).val() == '0'){
        $('.company-timing').hide();
        $('.hasDatepicker').each(function(){
            var id = $(this).attr('id');
            $('#'+id).val('');
        });
        $('#clickSameHours').prop('checked',false).attr('checked',false);
    }else{
        $('.company-timing').show();
    }
});
function readURL(input) {
   /* imgCounter = $('#img_gallery').find("img").size();
    var fileInput = document.getElementById('Image');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        for (var i = 0; i < input.files.length; i++) {
            var file_data = input.files[i];
            var form_data = new FormData();
            var name = file_data.name;
            var filetype = name.substr( (name.lastIndexOf('.') +1) ).toLowerCase();
            var file_size = file_data.size;
            if(file_size <= 5242880){ // 5 MB

                if(filetype == 'png' || filetype == 'jpg' || filetype == 'jpeg' || filetype == 'gif')
                {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var html = '<span class="span_image">';
                        html += '<img width="128px" src="' + e.target.result + '" alt="' + input.files[0].name + '"><br/>';
                        html += '<a href="javascript: void(0);" onclick="return deleteImage(this)">Delete</a>';
                        html += '<input type="hidden" name="image[' + imgCounter + '][name]" value="' + input.files[0].name + '" />';
                        html += '<input type="hidden" name="image[' + imgCounter + '][type]" value="' + input.files[0].type + '" />';
                        html += '<input type="hidden" name="image[' + imgCounter + '][content]" value="' + e.target.result + '" />';
                        html += '</span>';
                        $('#img_gallery').append(html);
                        imgCounter++;
                    }
                    reader.readAsDataURL(input.files[i]);
                }else{
                    $('#errormsg').show(); 
                }
            }else{
                $('#errormsg').show(); 
            }
        }
    }*/
    var fileInput = document.getElementById('Image');
    var filePath = fileInput.value;
    var extension = filePath.substr((filePath.lastIndexOf('.') + 1)).toLowerCase();
    var file_size = fileInput.size;
    if(input.files[0].size <= 5242880){ // 5 MB
        if(extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview').attr('src', e.target.result).attr('style','display: inline-block;');
                    $("#old").hide();
                    $('#errormsg').html('').hide();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        else{
            $('#preview').attr('src', '').attr('style','display: none;');
            $('#errormsg').html('Sorry, Your file extention is invalid.').show();
            $('#Slider_image').val('');
            $("#old").show();
        }
    }else{
        $('#preview').attr('src', '').attr('style','display: none;');
        $('#errormsg').html('File size are not allowed').show();
        $('#Slider_image').val('');
        $("#old").show();
    }
}
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>