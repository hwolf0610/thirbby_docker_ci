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
  $FieldsArray = array('UserID','Name','LastName','Email','MobileNumber','PhoneNumber','UserType',);
  foreach ($FieldsArray as $key) {
    $$key = @htmlspecialchars($edit_records->$key);
  }
}
if(isset($edit_records) && $edit_records !="")
{
    $addUserLabel    = "Edit Branch";        
    $userFormAction      = base_url().ADMIN_URL."/branch/edit/".str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->UserID));
}
else
{
    $addUserLabel    = "Add Branch";       
    $userFormAction      = base_url().ADMIN_URL."/branch/add";
}
$usertypes = getUserTypeList();
?>

<div class="page-content-wrapper">
        <div class="page-content">            
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">Branch Pages</h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL;?>/dashboard">
                            Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url().ADMIN_URL?>admin/branch/view">Branch</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo $addUserLabel;?> 
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
                            <div class="caption"><?php echo $addUserLabel;?></div>
                        </div>
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="<?php echo $userFormAction;?>" id="form_add_user" name="form_add_user" method="post" class="form-horizontal" enctype="multipart/form-data" >
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
                                        <label class="control-label col-md-3">Restaurant Name<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <select name="RestaurantID" id="RestaurantID" class="form-control">
                                                <option value="">Select Restaurant</option>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Review<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="Review" id="Review" class="form-control"/>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Rating<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="radio" name="rating[]" id="rating1" value="1">1 Star
                                            <input type="radio" name="rating[]" id="rating2" value="2">2 Star
                                            <input type="radio" name="rating[]" id="rating3" value="3">3 Star
                                            <input type="radio" name="rating[]" id="rating4" value="4">4 Star
                                            <input type="radio" name="rating[]" id="rating5" value="5">5 Star
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Review Description<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <textarea name="ReviewDescription" id="ReviewDescription" class="form-control ckeditor"></textarea>
                                        </div>
                                    </div> 
                                </div>    
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <input type="submit" name="submitUserPage" id="submitUserPage" value="Submit" class="btn btn-success danger-btn">
                                        <a class="btn btn-danger danger-btn" href="<?php echo base_url().ADMIN_URL;?>/review/view">Cancel</a>
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
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>