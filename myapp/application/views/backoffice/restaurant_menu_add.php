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
  $FieldsArray = array('entity_id','name','restaurant_id','category_id','price','menu_detail','receipe_detail','popular_item','availability','image','is_veg','receipe_time');
  foreach ($FieldsArray as $key) {
    $$key = @htmlspecialchars($edit_records->$key);
  }
}
if(isset($edit_records) && $edit_records !="")
{
    $add_label    = "Edit ".$this->menu_module;        
    $form_action      = base_url().ADMIN_URL.'/'.$this->controller_name."/edit_menu/".str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->entity_id));
}
else
{
    $add_label    = "Add ".$this->menu_module;       
    $form_action      = base_url().ADMIN_URL.'/'.$this->controller_name."/add_menu";
}
$usertypes = getUserTypeList();
?>

<div class="page-content-wrapper">
        <div class="page-content">            
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title"><?php echo $this->menu_module ?> Pages</h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">
                            Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url().ADMIN_URL?>/restaurant/view_menu"><?php echo $this->menu_module ?></a>
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
                            <form action="<?php echo $form_action;?>" id="form_add<?php echo $this->menu_prefix ?>" name="form_add<?php echo $this->menu_prefix ?>" method="post" class="form-horizontal" enctype="multipart/form-data" >
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
                                        <label class="control-label col-md-3">Restaurant<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <select name="restaurant_id" class="form-control" id="restaurant_id">
                                                <option value="">Select Restaurant</option>
                                                <?php if(!empty($restaurant)){
                                                    foreach ($restaurant as $key => $value) { ?>
                                                       <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $restaurant_id)?"selected":"" ?>><?php echo $value->name ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Category<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <select name="category_id" class="form-control" id="category_id">
                                                <option value="">Select Category</option>
                                                 <?php if(!empty($category)){
                                                    foreach ($category as $key => $value) { ?>
                                                       <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $category_id)?"selected":"" ?>><?php echo $value->name ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Name<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="hidden" id="entity_id" name="entity_id" value="<?php echo $entity_id;?>" />
                                            <input type="text" name="name" id="name" value="<?php echo $name;?>" maxlength="249" data-required="1" class="form-control"/>
                                        </div>
                                    </div>      
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Price<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="price" id="price" value="<?php echo $price ?>" maxlength="10" data-required="1" class="form-control"/>
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Detail<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="menu_detail" id="menu_detail" value="<?php echo $menu_detail;?>" maxlength="249" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Image</label>
                                        <div class="col-md-4">
                                            <input type="file" name="Image" id="Image" data-msg-accept="Please upload a valid file type." onchange="readURL(this)" />
                                            <p class="help-block">Only JPG, JPEG, PNG & GIF files are allowed.<br /> Maximum upload file size 10MB.</p>
                                            <span class="error" id="errormsg" style="display: none;"></span>
                                            <div id="img_gallery"></div>
                                            <img id="preview" height='100' width='150' style="display: none;"/>
                                            <video controls style="display: none;" id="v-control">
                                                <source id="source" src="" type="video/mp4">
                                            </video>
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
                                        <label class="control-label col-md-3">Receipe Details<span class="required">*</span></label>
                                        <div class="col-md-8">
                                           <textarea name="receipe_detail" id="receipe_detail" class="form-control ckeditor"><?php echo $receipe_detail ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Receipe Time(In Minutes)<span class="required">*</span></label>
                                        <div class="col-md-8">
                                           <input type="number" class="form-control" name="receipe_time" id="receipe_time" value="<?php echo $receipe_time ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Popular Item</label>
                                        <div class="col-md-1">
                                           <input type="checkbox" name="popular_item" id="popular_item" value="1" class="form-control" <?php echo (isset($popular_item) && $popular_item == 1)?'checked':'' ?>/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Food Type<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="radio" name="is_veg" id="is_veg" value="1" checked="" <?php echo ($is_veg)?($is_veg== '1')?'checked':'':'checked' ?>>Veg
                                            <input type="radio" name="is_veg" id="non-veg" value="0" <?php echo ($is_veg == '0')?'checked':'' ?>>Non veg
                                        </div>
                                    </div>    
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Availability<span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <?php $availability = explode(',', @$availability); ?>
                                            <select name="availability[]" class="form-control" id="availability" multiple="">
                                                <option value="">Select Availability</option>  
                                                <option value="Morning" <?php echo @in_array('Morning',$availability)?'selected':''; ?>>Morning</option>
                                                <option value="Lunch" <?php echo @in_array('Lunch',$availability)?'selected':''; ?>>Lunch</option>  
                                                <option value="Dinner" <?php echo @in_array('Dinner',$availability)?'selected':''; ?>>Dinner</option>  
                                            </select>
                                        </div>
                                    </div> 
                                </div>    
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <input type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-success danger-btn">
                                        <a class="btn btn-danger danger-btn" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name?>/view_menu">Cancel</a>
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
function readURL(input){
    var fileInput = document.getElementById('Image');
    var filePath = fileInput.value;
    var fileUrl = window.URL.createObjectURL(fileInput.files[0]);
    var extension = filePath.substr((filePath.lastIndexOf('.') + 1)).toLowerCase();
    if(input.files[0].size <= 10506316){ // 10 MB
        if(extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
            if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                if(extension == 'mp4'){
                    $('#source').attr('src', e.target.result);
                    $('#v-control').show();
                    $('#preview').attr('src','').hide();
                }else{
                    $('#preview').attr('src', e.target.result).attr('style','display: inline-block;');
                    $('#v-control').hide();
                    $('#source').attr('src', '');
                }
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
        $('#source').attr('src', '');
        $('#v-control').hide();
        $("#old").show();
    }
}
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>