<?php 
$this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css"/>
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
  $FieldsArray = array('entity_id','title','subject','message');
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
}?>
    <div class="page-content-wrapper">
        <div class="page-content">            
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title"><?php echo $this->module_name; ?></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>">
                            Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name?>/view"><?php echo $this->module_name; ?></a>
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
                            <div class="caption">
                                <?php echo $add_label;?>
                            </div>
                            <!-- <div class="actions">
                                <a class="btn danger-btn btn-sm" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name?>/templateVariables" data-target="#ajax" title="Email Template Variables List" data-toggle="modal">Email Templates Variables</a>
                            </div> -->
                        </div>                        
                        <div class="portlet-body form">                        
                            <!-- BEGIN FORM-->
                            <form action="<?php echo $form_action;?>" id="form_add<?php echo $this->prefix ?>" name="form_add<?php echo $this->prefix ?>" method="post" class="form-horizontal" enctype="multipart/form-data" >
                                <div class="form-body">
                                    <?php if(!empty($Error)){?>
                                    <div class="alert alert-danger">
                                        <?php echo $Error;?>
                                    </div>
                                    <?php }?>
                                    <?php if(validation_errors()){?>
                                    <div class="alert alert-danger">
                                        <?php echo validation_errors();?>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Title <span class="required">* </span></label>
                                        <div class="col-md-4">                                            
                                            <input type="hidden" name="entity_id" id="entity_id" value="<?php echo $entity_id;?>" />
                                            <input type="text" name="title" id="title" value="<?php echo htmlentities($title);?>" maxlength="249" data-required="1" class="form-control"/>
                                        </div>
                                    </div>                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Subject <span class="required">* </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="subject" data-required="1" maxlength="250" value="<?php echo htmlentities($subject);?>" id="subject" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Message Body<span class="required">* </span></label>
                                        <div class="col-md-9">
                                            <textarea class="ckeditor form-control" name="message" id="message" rows="6" data-required="1" ><?php echo $message;?></textarea> 
                                            <div style="display:none;" class="error" id="message_error"></div>           
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
<!-- BEGIN PAGE LEVEL PLUGINS -->
<div class="modal fade" id="ajax" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
    <img src="<?php echo base_url()?>assets/admin/img/loading-spinner-grey.gif" alt="loading image" class="loading">
    <span>&nbsp;&nbsp;Loading... </span>
    </div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<script>
$( "#form_add_email" ).submit(function( event ) {
    var message = $("textarea#Message").val();
    if (message == "") {
      $("#message_error").show();
      $('#message_error').html("This field is required.");
      return false;
    }    
});

</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>