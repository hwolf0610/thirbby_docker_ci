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
  $FieldsArray = array('entity_id','user_entity_id','address','landmark','latitude','longitude','zipcode','country','state','city','saved_status');
  foreach ($FieldsArray as $key) {
    $$key = @htmlspecialchars($edit_records->$key);
  }
}
if(isset($edit_records) && $edit_records !="")
{
    $add_label    = "Edit User Addresses";        
    $form_action      = base_url().ADMIN_URL.'/'.$this->controller_name."/edit_address/".str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->entity_id));
}
else
{
    $add_label    = "Add User Addresses";       
    $form_action      = base_url().ADMIN_URL.'/'.$this->controller_name."/add_address";
}
?>

<div class="page-content-wrapper">
        <div class="page-content">            
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">User Addresses</h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">
                            Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name?>/view">User</a>
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
                            <form action="<?php echo $form_action;?>" id="form_add<?php echo $this->ad_prefix ?>" name="form_add<?php echo $this->ad_prefix ?>" method="post" class="form-horizontal" enctype="multipart/form-data" >
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
                                        <label class="control-label col-md-3">Users<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <input type="hidden" name="entity_id" value="<?php echo $entity_id;?>" />
                                        <select name="user_entity_id" class="form-control" id="user_entity_id">
                                            <option value="">Select Users</option> 
                                            <?php if(!empty($user_data)){
                                                foreach ($user_data as $key => $value) { ?>
                                                   <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $user_entity_id)?'selected':'' ?>><?php echo $value->first_name.' '.$value->last_name ?></option>
                                            <?php } } ?> 
                                        </select></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Address<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Enter Address Here.." name="address" id="address" value="<?php echo $address ?>" maxlength="255"/>
                                    	</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Landmark<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Enter Landmark Here.." name="landmark" id="landmark" value="<?php echo $landmark ?>" maxlength="255"/>
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
                                        <input type="text" class="form-control" placeholder="Enter  Longitude" name="longitude" id="longitude" value="<?php echo $longitude ?>" maxlength="50"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">ZipCode<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Enter ZipCode Here.." name="zipcode" id="zipcode" value="<?php echo $zipcode;?>" maxlength="10"/>
                                    	</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Country<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="Enter Country" name="country" id="country" value="<?php echo $country ?>" maxlength="50"/>
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
                                        <label class="control-label col-md-3">Is Saved</label>
                                        <div class="col-md-4">
                                            <input type="checkbox" name="saved_status" id="saved_status" value="1" <?php echo ($saved_status == 1)?'checked':'' ?>>
                                        </div>
                                    </div>
                                </div>   
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <input type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-success danger-btn">
                                        <a class="btn btn-danger danger-btn" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name;?>/view/user_address">Cancel</a>
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM-->
                        </div>
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
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<script src="http://maps.google.com/maps/api/js?key=AIzaSyCGh2j6KRaaSf96cTYekgAD-IuUG0GkMVA" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/gmaps/gmaps.min.js"></script>
<script type="text/javascript">
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
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>