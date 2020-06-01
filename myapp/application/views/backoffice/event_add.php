<?php 
$this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
<!-- BEGIN sidebar -->
<?php $this->load->view(ADMIN_URL.'/sidebar');
 
if($this->input->post()){
  foreach ($this->input->post() as $key => $value) {
    $$key = @htmlspecialchars($this->input->post($key));
  } 
} else {
  $FieldsArray = array('entity_id','name','no_of_people','booking_date','restaurant_id','user_id','end_date');
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
$array  = array(); foreach ($booked_date as $key => $value) {
    $array[] = "'".date('Y-m-d:G',strtotime($value->booking_date))."'"; 
} $arrayDate = implode(',', $array);
?>

<div class="page-content-wrapper">
        <div class="page-content">            
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title"><?php echo $this->module_name ?> Pages</h3>
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
                                    <div class="form-group">
                                        <label class="control-label col-md-3">User<span class="required">*</span></label>
                                        <input type="hidden" name="entity_id" id="entity_id" value="<?php echo $entity_id ?>">
                                        <div class="col-md-4">
                                            <select name="user_id" class="form-control" id="user_id">
                                                <option value="">Select User</option> 
                                                <?php if(!empty($user)){
                                                    foreach ($user as $key => $value) { ?>
                                                        <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $user_id)?"selected":"" ?>><?php echo $value->first_name.' ' .$value->last_name ?></option>    
                                                <?php } } ?> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Event Name<span class="required">*</span></label>
                                        <div class="col-md-4">
                                             <input type="text" maxlength="249" class="form-control" name="name" id="name" value="<?php echo $name ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Restaurant<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <select name="restaurant_id" class="form-control" id="restaurant_id" onchange="getDetail(this.id)">
                                                <option value="">Select Restaurant</option>  
                                                <?php if(!empty($restaurant)){
                                                    foreach ($restaurant as $key => $value) { ?>
                                                        <option value="<?php echo $value->entity_id ?>" off="<?php echo $value->timings['off'] ?>" close = "<?php echo $value->timings['close'] ?>" open = "<?php echo $value->timings['open'] ?>" capacity="<?php echo $value->capacity ?>" <?php echo ($value->entity_id == $restaurant_id)?"selected":"" ?>><?php echo $value->name ?></option>    
                                                <?php } } ?>
                                            </select>
                                            <label class="capacity"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Date of Booking<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <div class='input-group date' id='datetimepicker' data-date-format="mm-dd-yyyy HH:ii P">
                                            <input size="16" type="text" name="booking_date" class="form-control" id="booking_date" value="<?php echo ($booking_date)?date('Y-m-d H:i',strtotime($booking_date)):'' ?>" readonly="">
                                            <span class="input-group-addon">
                                                  <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label class="control-label col-md-3">End Date<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <?php //$today = date("m/d/y H:i");?>
                                            <div class='input-group date' id='datetimepicker'>
                                            <input size="16" type="text" name="end_date" class="form-control" id="end_date" value="<?php echo ($booking_date)?date('Y-m-d H:i',strtotime($end_date)):'' ?>" readonly="">
                                            <span class="input-group-addon">
                                                  <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                            </div>
                                        </div>
                                    </div>         
                                    <div class="form-group">
                                        <label class="control-label col-md-3">No of People<span class="required">*</span></label>
                                        <div class="col-md-4">
                                           <input type="text" class="form-control" name="no_of_people" id="no_of_people" value="<?php echo $no_of_people ?>" maxlength="20"/>
                                        </div>
                                    </div>
                                 
                                </div>    
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <input type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-success danger-btn">
                                        <a class="btn btn-danger danger-btn" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name;?>/view">Cancel</a>
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

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<script>
jQuery(document).ready(function() {       
    Layout.init(); // init current layout
});
// for datepicker
$(function() {
    var disabledtimes_mapping = [<?php echo $arrayDate ?>];
    var dt = new Date();
    function formatDate(datestr)
    {
        var date = new Date(datestr);
        var day = date.getDate(); day = day>9?day:"0"+day;
        var month = date.getMonth()+1; month = month>9?month:"0"+month;
        return date.getFullYear()+"-"+month+"-"+day;
    }
    $("#booking_date").datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        autoclose: true,
        startDate: dt,
        onRenderHour:function(date){
            if(disabledtimes_mapping.indexOf(formatDate(date)+":"+date.getUTCHours())>-1)
            {
                return ['disabled'];
            }
        }
    });
    $('#end_date').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        autoclose: true,
        startDate: dt
    });
});
function getDetail(id){
    var element = $('#'+id).find('option:selected'); 
    var myTag = element.attr("capacity");
    $('.capacity').html('Total Capacity: '+myTag);
}
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>