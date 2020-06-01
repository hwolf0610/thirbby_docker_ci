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
                        <?php  echo 'On Going ' .$this->module_name ?> List
                    </h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">
                            Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo 'On Going ' .$this->module_name ?> Pages
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
                            <div class="caption"><?php echo 'On Going ' .$this->module_name ?> List</div>
                            <div class="actions">
                                <a class="btn danger-btn btn-sm green-btn" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name;?>/add"><i class="fa fa-plus"></i> Add</a>
                                <button class="btn danger-btn btn-sm red-btn" id="delete_order"><i class="fa fa-times"></i> Delete</button>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-container">
                            <?php 
                            if($this->session->flashdata('page_MSG'))
                            {?>
                                <div class="alert alert-success">
                                    <strong>Success!</strong> <?php echo $this->session->flashdata('page_MSG');?>
                                </div>
                            <?php } ?>
                            <div id="delete-msg" class="alert alert-success hidden">
                                <strong>Success!</strong> <?php echo $this->lang->line('success_delete');?>
                            </div>
                                <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th class="table-checkbox"><input type="checkbox" class="group-checkable"></th>
                                            <th>Order#</th>
                                            <th>Restaurant</th>
                                            <th>User Name</th>
                                            <th>Order Total</th>
                                            <th>Order Assign To</th>
                                            <th>Order Status</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td></td>           
                                            <td><input type="text" class="form-control form-filter input-sm" name="order"></td>            
                                            <td><input type="text" class="form-control form-filter input-sm" name="restaurant"></td> 
                                            <td><input type="text" class="form-control form-filter input-sm" name="page_title"></td>
                                            <td><input type="text" class="form-control form-filter input-sm" name="order_total"></td>
                                            <td><input type="text" class="form-control form-filter input-sm" name="driver"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><div class="margin-bottom-5">
                                                    <button class="btn btn-sm  danger-btn filter-submit margin-bottom"><i class="fa fa-search"></i> Search</button>
                                                </div>
                                                <button class="btn btn-sm danger-btn filter-cancel red-btn"><i class="fa fa-times"></i> Reset</button>
                                            </td>
                                        </tr>
                                        </thead>                                        
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
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
<!-- Modal -->
<div id="add_status" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Status</h4>
      </div>
      <div class="modal-body">
        <form id="form_add_status" name="form_add_status" method="post" class="form-horizontal" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="hidden" name="entity_id" id="entity_id" value="">
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <label class="control-label col-md-4">Status<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select name="order_status" id="order_status" class="form-control form-filter input-sm">
                                <option value="">Select...</option>
                                <option value="placed">Placed</option>
                                <option value="preparing">Preparing</option>
                                <option value="delivered">Delivered</option>
                                <option value="onGoing">On Going</option>
                                <option value="cancel">Cancel</option>                                            
                            </select>                                               
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-12 text-center">
                         <div id="loadingModal" class="loader-c" style="display: none;"><img  src="<?php echo base_url() ?>assets/admin/img/loading-spinner-grey.gif" align="absmiddle"  ></div>
                         <button type="submit" class="btn btn-sm  danger-btn filter-submit margin-bottom" name="submit_page" id="submit_page" value="Save"><span>Save</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div id="view_status_history" class="modal fade" role="dialog">
</div>
<div class="wait-loader" id="quotes-main-loader" style="display: none;"><img  src="<?php echo base_url() ?>assets/admin/img/ajax-loader.gif" align="absmiddle"  ></div>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script type="text/javascript" src="<?php echo base_url() ?>/assets/admin/plugins/uniform/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>/assets/admin/plugins/uniform/css/uniform.default.min.css"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/datatable.js"></script>
<script>
var grid;
jQuery(document).ready(function() {           
    Layout.init(); // init current layout    
    grid = new Datatable();
    grid.init({
        src: $("#datatable_ajax"),
        onSuccess: function(grid) {
            // execute some code after table records loaded
        },
        onError: function(grid) {
            // execute some code on network or other general error  
        },
        dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options 
            "sDom" : "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", 
           "aoColumns": [
                { "bSortable": false },
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                { "bSortable": false }
              ],
            "sPaginationType": "bootstrap_full_number",
            "oLanguage": {  // language settings
                "sProcessing": '<img src="' + Metronic.getGlobalImgPath() + 'loading-spinner-grey.gif"/><span>&nbsp;&nbsp;Loading...</span>',
                "sLengthMenu": "_MENU_ records",
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
                "sInfoEmpty": "No records found to show",
                "sGroupActions": "_TOTAL_ records selected:  ",
                "sAjaxRequestGeneralError": "Could not complete request. Please check your internet connection",
                "sEmptyTable":  "No data available in table",
                "sZeroRecords": "No matching records found",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next",
                    "sPage": "Page",
                    "sPageOf": "of"
                }
            },
            "bServerSide": true, // server side processing
            "sAjaxSource": "ajaxview/onGoing", // ajax source
            "aaSorting": [[ 5, "desc" ]] // set first column as a default sort by asc
        }
    });            
    $('#datatable_ajax_filter').addClass('hide');
    $('input.form-filter, select.form-filter').keydown(function(e) 
    {
        if (e.keyCode == 13) 
        {
            grid.addAjaxParam($(this).attr("name"), $(this).val());
            grid.getDataTable().fnDraw(); 
        }
    });
});
// method for active/deactive 
function disableDetail(entity_id,status)
{
    var statusVar = (status==0)?'active':'deactive';
    bootbox.confirm("Are you sure you want to "+statusVar+" this?", function(disableConfirm) {    
        if (disableConfirm) {
            jQuery.ajax({
              type : "POST",
              dataType : "json",
              url : 'ajaxdisable',
              data : {'entity_id':entity_id,'status':status},
              success: function(response) {
                   grid.getDataTable().fnDraw(); 
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {           
                alert(errorThrown);
              }
           });
        }
    });
}
// method for deleting
function deleteDetail(entity_id)
{   
    bootbox.confirm("Are you sure wants to delete this?", function(disableConfirm) {    
        if (disableConfirm) {
            jQuery.ajax({
              type : "POST",
              dataType : "html",
              url : 'ajaxDelete',
              data : {'entity_id':entity_id},
              success: function(response) {
                grid.getDataTable().fnDraw(); 
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {           
                alert(errorThrown);
              }
           });
        }
    });
}
//get invoice
function getInvoice(entity_id){
    $.ajax({
      type: "POST",
      dataType : "html",
      url: BASEURL+"backoffice/order/getInvoice",
      data: {'entity_id': entity_id},
      cache: false, 
      beforeSend: function(){
        $('#quotes-main-loader').show();
      },   
      success: function(html) {
            $('#quotes-main-loader').hide();
            var WinPrint = window.open('<?php echo base_url() ?>'+html, '_blank', 'left=0,top=0,width=650,height=630,toolbar=0,status=0');
            /*deletefile(html);*/
      }
    });
}
//add status
function updateStatus(entity_id,status,user_id){
    $('#entity_id').val(entity_id);
    $('#user_id').val(user_id);
    if(status == 'preparing'){
        $('#order_status').empty().append(
            '<option value="">Please Select</option><option value="delivered">Delivered</option><option value="onGoing">OnGoing</option>'
        );
    }
    if(status == 'onGoing'){
        $('#order_status').empty().append(
            '<option value="">Please Select</option><option value="delivered">Delivered</option>'
        );
    }
    if(status == 'placed'){
        $('#order_status').empty().append(
            '<option value="">Please Select</option><option value="preparing">Preparing</option><option value="delivered">Delivered</option><option value="onGoing">OnGoing</option><option value="cancel">Cancel</option>'
        );
    }
    $('#add_status').modal('show');
}
$('#form_add_status').submit(function(){
    $.ajax({
      type: "POST",
      dataType : "html",
      url: BASEURL+"backoffice/order/updateOrderStatus",
      data: $('#form_add_status').serialize(),
      cache: false, 
      beforeSend: function(){
        $('#quotes-main-loader').show();
      },   
      success: function(html) {
        $('#quotes-main-loader').hide();
        $('#add_status').modal('hide');
        grid.getDataTable().fnDraw();
      }
    });
    return false;
});
//delete multiple
$('#delete_order').click(function(e){
    e.preventDefault();
    var records = grid.getSelectedRows();  
    if(!jQuery.isEmptyObject(records)){            
        var CommissionIds = Array();
        var amount = '0.00';
        for (var i in records) {  
            var val = records[i]["value"];            
            CommissionIds.push(val);                        
        }
        var CommissionIdComma = CommissionIds.join(",");
        bootbox.confirm("Are you sure wants to delete this?", function(disableConfirm) {    
            if (disableConfirm) {
                jQuery.ajax({
                  type : "POST",                      
                  url : 'deleteMultiOrder',
                  data : {'arrayData':CommissionIdComma},
                  success: function(response) {                        
                    grid.getDataTable().fnDraw(); 
                  },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                  }
                });
            }
        });
    }else{
        bootbox.alert("Please Select At least one checkbox!");
    }        
});
function statusHistory(order_id){
    jQuery.ajax({
      type : "POST",                      
      url : 'statusHistory',
      data : {'order_id':order_id},
      cache: false,
      success: function(response) {      
        $('#view_status_history').html(response);
        $('#view_status_history').modal('show');      
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert(errorThrown);
      }
    });
}
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>