<?php $this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
    <!-- BEGIN sidebar -->
<?php $this->load->view(ADMIN_URL.'/sidebar');?>
    <!-- END sidebar -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE header-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">
                    System Options
                    </h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">
                            Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            System Options 
                        </li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>            
            <!-- END PAGE header-->            
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption">System Options</div>
                            <div class="actions">
                               
                            </div>
                        </div>
                            <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="<?php echo base_url().ADMIN_URL;?>/system_option/view" method="post" id="SystemOption" name="SystemOption" class="form-horizontal">
                                <div class="form-body">
                            <?php 
                            if($this->session->flashdata('SystemOptionMSG'))
                            {?>
                                <div class="alert alert-success">
                                    <strong>Success!</strong> <?php echo $this->session->flashdata('SystemOptionMSG');?>
                                </div>
                            <?php } ?>
                                    
                                    <?php $i = 0;
                                    foreach  ($SystemOptionList as $key => $OptionDet) 
                                    { ?>
                                        <?php if($OptionDet->OptionSlug != 'country' && $OptionDet->OptionSlug != 'phone_code') {?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo $OptionDet->OptionName; ?><span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="hidden" name="SystemOptionID[<?php echo $i ?>]" value="<?php echo $OptionDet->SystemOptionID; ?>">
                                                <input type="text" name="OptionValue[<?php echo $i ?>]" id="OptionValue<?php echo $i ?>" value="<?php echo htmlentities($OptionDet->OptionValue); ?>" maxlength="250"  class="form-control system_value" required>
                                            </div>
                                        </div>
                                        <?php } if($OptionDet->OptionSlug == 'country') {?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo $OptionDet->OptionName; ?><span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="hidden" name="SystemOptionID[<?php echo $i ?>]" value="<?php echo $OptionDet->SystemOptionID; ?>">
                                                <select name="OptionValue[<?php echo $i ?>]" id="OptionValue<?php echo $i ?>" class="form-control select_country">
                                                    <?php $countryArray = getCountry();
                                                    if(!empty($countryArray)){
                                                        foreach ($countryArray as $key => $value) { ?>
                                                            <option value="<?php echo $value['name']; ?>" data-id="<?php echo $value['code']; ?>" <?php echo ($value['name'] == $OptionDet->OptionValue)?'selected':''; ?>><?php echo $value['name']; ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php } if($OptionDet->OptionSlug == 'phone_code'){ ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo $OptionDet->OptionName; ?></label>
                                            <div class="col-md-4">
                                                <input type="hidden" name="SystemOptionID[<?php echo $i ?>]" value="<?php echo $OptionDet->SystemOptionID; ?>">
                                                <input type="text" name="OptionValue[<?php echo $i ?>]" id="OptionValue<?php echo $i ?>" readonly="" value="<?php echo htmlentities($OptionDet->OptionValue); ?>" maxlength="250" class="form-control phone_code">
                                            </div>
                                        </div>
                                    <?php } $i++; } ?>                              
                                </div>
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-2 col-md-9">
                                        <input type="submit" name="SubmitSystemSetting" id="SubmitSystemSetting" class="btn danger-btn" value="Submit">
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM-->
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- BEGIN PAGE LEVEL PLUGINS -->


<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/datatable.js"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<script>
jQuery(document).ready(function() {       
    Layout.init(); // init current layout
    $('.select_country').change(function(){
       var code =  $(this).find(':selected').attr('data-id');
       $('.phone_code').val(code);
    });
    $('#SystemOption').submit(function(e){
        $('.system_value').each(function(){
            var id = $(this).attr('id');
            if($('#'+id).val() == ''){
                $('#'+id).prop('required',true);
                $('#'+id).addClass('error');
                e.preventDefault();
            }else{
                $('#'+id).prop('required',false);
                $('#'+id).removeClass('error');
            }
        });
    });
});
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>