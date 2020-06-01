<?php $this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL STYLES -->
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
<!-- BEGIN sidebar -->
<?php $this->load->view(ADMIN_URL.'/sidebar');?>
<!-- END sidebar -->
<?php
if($this->input->post()){
  foreach ($this->input->post() as $key => $value) {
    $$key = @htmlspecialchars($this->input->post($key));
  } 
} else {    
  $FieldsArray = array('UserID','UserName','Email');
  foreach ($FieldsArray as $key) {
    $$key = @htmlspecialchars($editUserDetail->$key);
  }
}?>   
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">My Profile</h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL;?>/dashboard">Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>                        
                        <li>My Profile</li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE header-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row">
                <div class="col-md-12">
                    <!-- <ul id="myTab" class="nav nav-tabs">
                        <li <?php echo ($selected_tab == "" || $selected_tab == "UserInfo")?"class='active'":"";?>><a href="#UserInfo" data-toggle="tab">User Information</a></li>
                        <li <?php echo ($selected_tab == "ChangePassword")?"class='active'":"";?>><a href="#ChangePass" data-toggle="tab">Change Password</a></li>
                    </ul> -->
                    <div id="myTabContent" class="tab-content">
                    <!-- BEGIN VALIDATION STATES-->
                        <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption">My Profile</div>
                        </div>
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="<?php echo base_url().ADMIN_URL."/myprofile/getUserProfile";?>" id="form_edit_editor" name="form_edit_editor" method="post" class="form-horizontal" enctype="multipart/form-data">
                                <div class="form-body">
                                	<?php if($this->session->flashdata('myProfileMSG')){?>
	                                <div class="alert alert-success">
	                                    <strong>Success!</strong> <?php echo $this->session->flashdata('myProfileMSG');?>
	                                </div>
                            		<?php } ?>
                                    <?php if(validation_errors()){?>
                                        <div class="alert alert-danger"><?php echo validation_errors();?></div>
                                    <?php } ?>                    
                                    <div class="form-group">                                        
                                        <label class="control-label col-md-3">Email <span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="Email" id="Email" value="<?php echo htmlentities($Email);?>" onblur="checkEmailExist(this.value,<?php echo $this->session->userdata("adminID");?>);" maxlength="240" data-required="1" class="form-control"/>
                                            <div style="display:none;" class="error" id="EmailExist"></div>
                                        </div>                                        
                                    </div>                                                                                                         
                                    <div class="form-group">                                        
                                        <label class="control-label col-md-3">Password </label>
                                        <div class="col-md-4">
                                            <input type="password" name="password" id="password" value=""  data-required="1" class="form-control"/>
                                        </div>                                        
                                    </div>
                                    <div class="form-group">                                        
                                        <label class="control-label col-md-3">UserName </label>
                                        <div class="col-md-4">
                                             <input type="text" name="UserName" id="UserName" value="<?php echo $UserName ?>"  readonly="" data-required="1" class="form-control"/>
                                        </div>                                        
                                    </div>                                    
                                </div>
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <input type="submit" class="btn danger-btn btn-sm" name="submitEditUser" id="submitEditUser" value="Submit">
                                        <a href="<?php echo base_url().ADMIN_URL?>/dashboard" class="btn danger-btn btn-sm">Cancel</a>
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM-->
                        </div>
                        </div>
                    <!-- END VALIDATION STATES-->
                </div>
                </div>
                </div>
            </div>
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/pages/scripts/pwstrength.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>
jQuery(document).ready(function() {           
    Layout.init(); // init current layout
    var options = {
        onLoad: function () {
            $('#messages').text('Start typing password');
        }
    };
    $('#Newpass').pwstrength(options);    
});
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>