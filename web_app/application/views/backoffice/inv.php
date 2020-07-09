<?php $this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
    <!-- BEGIN sidebar -->

    <!-- END sidebar -->
                                                    <?php
                                                if(isset($_GET['invoice'])){
                                                    echo('<h4 class="modal-title">رقم الطلب: '.$_GET['invoice'].'.</h4>');
                                                    $id= $_GET['invoice'];
                                                    echo('<button onclick="getInvoice('.$id.')"  title="Click here for Download Invoice" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> انشاء الفاتورة</button>');
                                                    $u= $_GET['user'];
                                                    echo "  ";
                                                    echo('<button onclick="updateStatus('.$id.','.$u.')"  title="Click Here to Update order Status" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> تغيير الحالة</button>');
                                                }
                                                ?>
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
            "sAjaxSource": "ajaxview", // ajax source
            "aaSorting": [[ 3, "desc" ]] // set first column as a default sort by asc
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
<?php
    if(function_exists($_GET['F'])) {
   getInvoice(20);
    }
?>
//get invoice
function getInvoice(entity_id){
    $.ajax({
      type: "POST",
      dataType : "html",
      url: BASEURL+"backoffice/inv/getInvoice",
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
function updateStatus(entity_id,user_id){
    $('#entity_id').val(entity_id);
    $('#user_id').val(user_id);
    if(status == 'preparing'){
        $('#order_status').empty().append(
            '<option value="">Please Select</option><option value="delivered">تم التوصيل</option><option value="onGoing">في الطريق</option>'
        );
    }
    if(status == 'onGoing'){
        $('#order_status').empty().append(
            '<option value="">Please Select</option><option value="delivered">تم التوصيل</option>'
        );
    }
    if(status == 'placed'){
        $('#order_status').empty().append(
            '<option value="">Please Select</option><option value="preparing">جاري التحضير</option><option value="delivered">تم التوصيل</option><option value="onGoing">في الطريق</option><option value="cancel">الغاء</option>'
        );
    }
    $('#add_status').modal('show');
}
$('#form_add_status').submit(function(){
    $.ajax({
      type: "POST",
      dataType : "html",
      url: BASEURL+"backoffice/inv/updateOrderStatus",
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
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>