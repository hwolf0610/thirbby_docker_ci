<?php 
$this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css"/>
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
<!-- BEGIN sidebar -->
<?php $this->load->view(ADMIN_URL.'/sidebar');
 
if($this->input->post()){
  foreach ($this->input->post() as $key => $value) {
    $$key = @htmlspecialchars($this->input->post($key));
  } 
} else {
  $FieldsArray = array('entity_id','first_name','last_name','email','mobile_number','phone_number','user_type','phone_code');
  foreach ($FieldsArray as $key) {
    $$key = @htmlspecialchars($edit_records->$key);
  }
}
$module =  ($user_type != 'Driver' && $this->uri->segment(4) != 'driver')?$this->module_name:'Driver';

if(isset($edit_records) && $edit_records !="")
{
    $add_label    = "Edit ".$module;        
    $form_action      = base_url().ADMIN_URL.'/'.$this->controller_name."/edit/".str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->entity_id));
}
else
{
    $add_label    = "Add ".$module;       
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
                    <h3 class="page-title"><?php echo ($user_type != 'Driver' && $this->uri->segment(4) != 'driver')?$this->module_name:'Driver Management' ?></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">
                            Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo ($user_type != 'Driver' && $this->uri->segment(4) != 'driver')?'<a href='.base_url().ADMIN_URL.'/'.$this->controller_name.'/view>User</a>':'<a href='.base_url().ADMIN_URL.'/'.$this->controller_name.'/driver/>Driver</a>' ?>
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
                            <form action="<?php echo $form_action;?>" id="form_add<?php echo $this->prefix ?>" name="form_add<?php echo $this->prefix ?>" method="post" class="form-horizontal" enctype="multipart/form-data" >
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
                                    <input type="hidden" id="entity_id" name="entity_id" value="<?php echo $entity_id;?>" />
                                    <?php if($user_type != 'Driver' && $this->uri->segment(4) != 'driver'){ ?> 
                                        <div class="form-group">
                                            <label class="control-label col-md-3">First Name<span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="text" name="first_name" id="first_name" value="<?php echo $first_name;?>" maxlength="249" data-required="1" class="form-control"/>
                                            </div>
                                        </div>      
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Last Name<span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="text" name="last_name" id="last_name" value="<?php echo $last_name;?>" maxlength="249" data-required="1" class="form-control"/>
                                            </div>
                                        </div>  
                                    <?php }else{ ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Name<span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="text" name="first_name" id="first_name" value="<?php echo $first_name;?>" maxlength="249" data-required="1" class="form-control"/>
                                            </div>
                                        </div>     
                                    <?php  } ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Phone Number</label>
                                        <div class="col-md-4">
                                            <input type="text" name="phone_number" id="phone_number" value="<?php echo $phone_number;?>" maxlength="20" data-required="1" class="form-control"/>
                                        </div>
                                        
                                    </div>  
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Mobile Number<span class="required">*</span></label>
                                        <div class="col-md-1">
                                            <input type="text" readonly="" name="phone_code" id="phone_code" value="<?php echo ($phone_code)?$phone_code:$country->OptionValue;?>" data-required="1" class="form-control phone_code_wrap"/>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" onblur="checkExist(this.value)" name="mobile_number" id="mobile_number" value="<?php echo $mobile_number;?>" maxlength="12" data-required="1" class="form-control"/>
                                        </div>
                                        <div id="phoneExist"></div>
                                    </div> 
                                    <?php if($user_type != 'Driver' && $this->uri->segment(4) != 'driver'){ ?>  
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Email<span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="email" name="email" id="email" onblur="checkEmail(this.value,'<?php echo $entity_id ?>')" value="<?php echo $email;?>" maxlength="99" data-required="1" class="form-control"/>
                                            </div>
                                            <div id="EmailExist"></div>
                                        </div>
                                    <?php } ?>
                                    <?php if($user_type == 'Driver' || $this->uri->segment(4) == 'driver'){ ?> 
                                        <div class="form-group">
                                        <label class="control-label col-md-3">User Type <span class="required">*</span></label>
                                        <div class="col-md-4">
                                             <input type="text" name="user_type" id="user_type" value="Driver" readonly="" class="form-control">
                                        </div>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="form-group">   
                                            <label class="control-label col-md-3">User Type <span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <select class="form-control" name="user_type" id="user_type">
                                                    <option value="">Please Select</option>
                                                    <?php foreach ($usertypes as $key => $value) {?>                                  
                                                        <option value="<?php echo $key;?>" <?php echo ($user_type==$key)?"selected":""?>><?php echo $value;?></option>    
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div> 
                                    <?php } ?>
                                    <?php if($entity_id){ ?>
                                        <h3>Change Password</h3>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Password <?php echo ($entity_id)?'':'<span class="required">*</span>' ?></label>
                                        <div class="col-md-4">
                                            <input type="password" name="password" id="password" value="" maxlength="249" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Confirm Password<?php echo ($entity_id)?'':'<span class="required">*</span>' ?></label>
                                        <div class="col-md-4">
                                            <input type="password" name="confirm_password" id="confirm_password" value="" maxlength="249" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    </div>    
                                    <div class="form-actions fluid">
                                        <div class="col-md-offset-3 col-md-9">
                                            <input type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-success danger-btn">
                                            <?php if($user_type != '' && $user_type != 'Driver'){?>
                                                <a class="btn btn-danger danger-btn" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name;?>/view">Cancel</a>
                                            <?php }else{ ?>
                                                <a class="btn btn-danger danger-btn" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name;?>/driver">Cancel</a>
                                            <?php } ?>
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

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<script>
jQuery(document).ready(function() {       
    Layout.init(); // init current layout
});
//check phone number exist
function checkExist(mobile_number){
    var entity_id = $('#entity_id').val();
    $.ajax({
    type: "POST",
    url: BASEURL+"<?php echo ADMIN_URL ?>/users/checkExist",
    data: 'mobile_number=' + mobile_number +'&entity_id='+entity_id,
    cache: false,
    success: function(html) {
      if(html > 0){
        $('#phoneExist').show();
        $('#phoneExist').html("User is already exist with this phone number!");        
        $(':input[type="submit"]').prop("disabled",true);
      } else {
        $('#phoneExist').html("");
        $('#phoneExist').hide();        
        $(':input[type="submit"]').prop("disabled",false);
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {                 
      $('#phoneExist').show();
      $('#phoneExist').html(errorThrown);
    }
  });
}
// admin email exist check
function checkEmail(email,entity_id){
  $.ajax({
    type: "POST",
    url: BASEURL+"<?php echo ADMIN_URL ?>/users/checkEmailExist",
    data: 'email=' + email +'&entity_id='+entity_id,
    cache: false,
    success: function(html) {
      if(html > 0){
        $('#EmailExist').show();
        $('#EmailExist').html("User is already exist with this email id!");        
        $(':input[type="submit"]').prop("disabled",true);
      } else {
        $('#EmailExist').html("");
        $('#EmailExist').hide();        
        $(':input[type="submit"]').prop("disabled",false);
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {                 
      $('#EmailExist').show();
      $('#EmailExist').html(errorThrown);
    }
  });
}
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>