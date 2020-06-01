<?php 
$this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
<!-- BEGIN sidebar -->
<?php $this->load->view(ADMIN_URL.'/sidebar');
 
if($this->input->post()){
  foreach ($this->input->post() as $key => $value) {
    $$key = @htmlspecialchars($this->input->post($key));
  } 
} else {
  $FieldsArray = array('entity_id','restaurant_id','name','description','amount_type','amount','start_date','end_date','max_amount');
  foreach ($FieldsArray as $key) {
    $$key = @htmlspecialchars($edit_records->$key);
  }
}
if(isset($edit_records) && $edit_records !="")
{
    $add_label    = "Edit ".$this->module_name;        
    $user_action      = base_url().ADMIN_URL.'/'.$this->controller_name."/edit/".str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->entity_id));
}
else
{
    $add_label    = "Add ".$this->module_name;       
    $user_action      = base_url().ADMIN_URL.'/'.$this->controller_name."/add";
}
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
                            <form action="<?php echo $user_action;?>" id="form_add<?php echo $this->prefix; ?>" name="form_add<?php echo $this->prefix; ?>" method="post" class="form-horizontal" enctype="multipart/form-data" >
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
                                        <label class="control-label col-md-3">Coupon Name<span class="required">*</span></label>
                                        <input type="hidden" name="entity_id" id="entity_id" value="<?php echo $entity_id ?>">
                                        <div class="col-md-8">
                                             <input type="text" maxlength="249" onblur="checkExist(this.value)" class="form-control" name="name" id="name" value="<?php echo $name ?>"/>
                                              <div id="phoneExist"></div>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Restaurant</label>
                                        <div class="col-md-8">
                                            <select name="restaurant_id" class="form-control" id="restaurant_id">
                                                <option value="">Select Restaurant</option>  
                                                 <?php if(!empty($restaurant)){
                                                    foreach ($restaurant as $key => $value) { ?>
                                                        <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $restaurant_id)?'selected':'' ?>><?php echo $value->name ?></option>    
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Description</label>
                                        <div class="col-md-8">
                                           <textarea name="description" id="description" class="form-control ckeditor"><?php echo $description ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">  
                                        <label class="control-label col-md-3">Discount Type</label>                        
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
                                        <label class="control-label col-md-3">Amount</label>
                                        <div class="col-sm-8 form-markup">
                                              <label id="Percentage">Percentage (%)<span class="required">*</span></label>
                                              <label id="Amount" style="display:none">Amount ($) <span class="required">*</span></label>
                                              <br>
                                              <input type="text" name="amount" id="amount" value="<?php echo $amount ?>" maxlength="10" data-required="1" class="form-control"/>  
                                        </div>  
                                    </div> 
                                    <div class="form-group"> 
                                        <label class="control-label col-md-3">Max Amount<span class="required">*</span></label>
                                        <div class="col-sm-8 form-markup">
                                              <input type="text" name="max_amount" greater="#amount" id="max_amount" value="<?php echo $max_amount ?>" maxlength="10" data-required="1" class="form-control"/>  
                                        </div>  
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Start Date<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <?php //$today = date("m/d/y H:i");?>
                                            <div class='input-group date' id='datetimepicker' data-date-format="mm-dd-yyyy HH:ii P">
                                            <input size="16" type="text" name="start_date" class="form-control" id="start_date" value="<?php echo ($start_date)?date('Y-m-d H:i',strtotime($start_date)):"" ?>" readonly="">
                                            <span class="input-group-addon">
                                                  <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">End Date<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <?php //$today = date("m/d/y H:i");?>
                                            <div class='input-group date' id='datetimepicker' data-date-format="mm-dd-yyyy HH:ii P">
                                            <input size="16" type="text" name="end_date" class="form-control" id="end_date" value="<?php echo ($end_date)?date('Y-m-d H:i',strtotime($end_date)):"" ?>" readonly="">
                                            <span class="input-group-addon">
                                                  <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                            </div>
                                        </div>
                                    </div>       
                                </div>    
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <input type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-success danger-btn">
                                        <a class="btn btn-danger danger-btn" href="<?php echo base_url().ADMIN_URL?>/coupon/view">Cancel</a>
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
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<script>
jQuery(document).ready(function() {       
    Layout.init(); // init current layout
});
//check coupon exist
function checkExist(coupon){
    var entity_id = $('#entity_id').val();
    $.ajax({
    type: "POST",
    url: BASEURL+"backoffice/coupon/checkExist",
    data: 'coupon=' + coupon +'&entity_id='+entity_id,
    cache: false,
    success: function(html) {
      if(html > 0){
        $('#phoneExist').show();
        $('#phoneExist').html("Coupon is already exist!");        
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
// for datepicker
$(function() {
    $('#start_date').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        autoclose: true,
    });
     $('#end_date').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        autoclose: true,
    });
});
$("#amount,#max_amount").each(function(){
      $(this).keyup(function(){
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
});
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
    $("#max_amount").attr('greater','');    
  }else if($("input[name=amount_type]:checked").val() == "Amount" ){
    $("#amount").val(''); 
    $("#max_amount").attr('greater','#amount');              
  }
});
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>