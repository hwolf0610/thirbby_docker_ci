<?php $this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/datepicker.css" />
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
                    Delivery Commission
                    </h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">
                            Home </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo '<a href='.base_url().ADMIN_URL.'/'.$this->controller_name.'/driver/>Driver</a>' ?>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            Commission Pages
                        </li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>            
            <!-- END PAGE header-->            
            <div class="row">
                <div class="col-md-12">
                
                    <!-- BEGIN VALIDATION STATES-->
                    <div class="page-content-wrapper">
                        <!-- BEGIN PAGE header--> 
                        <div class="row">
                            <div class="col-md-12">    
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet box red">
                                    <div class="portlet-title">
                                        <div class="caption">Commission List</div>
                                        <div class="actions">
                                            <button class="btn danger-btn btn-sm" type="button" id="pay_commission" title="Pay Commission">Pay</button>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-container">
                                        <?php 
                                        if($this->session->flashdata('success_pay'))
                                        {?>
                                            <div class="alert alert-success">
                                                <strong>Success!</strong> <?php echo $this->session->flashdata('success_pay');?>
                                            </div>
                                        <?php } 
                                        if($this->session->flashdata('error_pay'))
                                        {?>
                                            <div class="alert alert-danger">
                                                <strong>Success!</strong> <?php echo $this->session->flashdata('error_pay');?>
                                            </div>
                                        <?php } ?>
                                        <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                            <thead>
                                            <tr role="row" class="heading">
                                                <th class="table-checkbox"><label><input type="checkbox" class="group-checkable"></label></th>
                                                <th>Name</th>
                                                <th>Restaurant</th>
                                                
                                                <th>Commission</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                            <tr role="row" class="filter">
                                                <td></td>                                       
                                                <td><input type="text" class="form-control form-filter input-sm" name="name"></td>
                                                <td><input type="text" class="form-control form-filter input-sm" name="restaurant"></td>
                                                
                                                <td><input type="text" class="form-control form-filter input-sm" name="commission"></td>
                                                <td><input type="text" class="form-control form-filter input-sm datepicker" name="date"></td>
                                                <td>
                                                    <div class="margin-bottom-5">
                                                        <button class="btn btn-sm  danger-btn filter-submit margin-bottom"><i class="fa fa-search"></i> Search</button>
                                                    </div>
                                                    <button class="btn btn-sm danger-btn filter-cancel"><i class="fa fa-times"></i> Reset</button>
                                                </td>
                                            </tr>
                                            </thead>                                        
                                            <tbody>
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/datatable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/scripts/bootstrap-datepicker.js"></script>
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
                { "bSortable": false }
            ],
            "sPaginationType": "bootstrap_full_number",
            "oLanguage": {  // language settings
                "sProcessing": '<img src="<?php echo base_url(); ?>assets/admin/img/loading-spinner-grey.gif"/><span>&nbsp;&nbsp;Loading...</span>',
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
            "sAjaxSource": "<?php echo base_url().ADMIN_URL.'/'.$this->controller_name ?>/ajaxcommission/<?php echo $entity_id ?>", // ajax source
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
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    });
    
});
//pay with multiple check box
$('#pay_commission').click(function(e){
    e.preventDefault();
    var records = grid.getSelectedRows();  
    if(!jQuery.isEmptyObject(records)){            
        var CommissionIds = Array();
        var amount = '0.00'
        for (var i in records) {  
            var val = records[i]["value"];
            var getnum = val.split('-'); 
            var num = parseInt(getnum[0]);            
            CommissionIds.push(num);                        
        }
        var CommissionIdComma = CommissionIds.join(",");
        jQuery.ajax({
          type : "POST",                      
          url : '<?php echo base_url().ADMIN_URL ?>/users/commission_pay',
          data : {'arrayData':CommissionIdComma},
          success: function(response) {   
             grid.getDataTable().fnDraw();                             
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
          }
        });
    }else{
        bootbox.alert('Please select at least one checkbox!');
    }        
});
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>