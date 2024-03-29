<?php $this->load->view(ADMIN_URL.'/header');?>

<!-- BEGIN PAGE LEVEL STYLES -->

<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css" />

<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>

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

                        <?php echo $this->module_name ?> List

                    </h3>

                    <ul class="page-breadcrumb breadcrumb">

                        <li>

                            <i class="fa fa-home"></i>

                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">

                            Home </a>

                            <i class="fa fa-angle-right"></i>

                        </li>

                        <li>

                            <?php echo $this->module_name ?> Pages

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

                            <div class="caption"><?php echo $this->module_name ?> List</div>

                           <!--  <div class="actions">

                                <a class="btn danger-btn btn-sm" href="<?php //echo base_url().ADMIN_URL.'/'.$this->controller_name;?>/add"><i class="fa fa-plus"></i> Add</a>

                            </div> -->

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

                                            <th class="table-checkbox">#</th>

                                            <th>User Name</th>

                                            <th>Restaurant Name</th>

                                            <th>No of People</th>

                                            <th>Booking Date</th>

                                            <th>Amount</th>

                                            <th>Event Status</th>

                                            <th>Status</th>

                                            <th>Action</th>

                                        </tr>

                                        <tr role="row" class="filter">

                                            <td></td>     

                                            <td><input type="text" class="form-control form-filter input-sm" name="user_name"></td>                                  

                                           

                                            <td><input type="text" class="form-control form-filter input-sm" name="restaurant"></td>

                                            <td><input type="text" class="form-control form-filter input-sm" name="no_of_people"></td>

                                            <td><input type="text" class="form-control form-filter input-sm date" name="booking_date" id="booking_date"></td>

                                            

                                            <td><input type="text" class="form-control form-filter input-sm" name="amount"></td>                                  

                                            <td> 

                                                <select name="event_status" class="form-control form-filter input-sm">

                                                    <option value="pending">Pending</option>

                                                    <option value="onGoing">On Going</option>

                                                    <option value="completed">Delivered</option>

                                                    <option value="cancel">Cancel</option>                                          

                                                </select>

                                            </td>

                                            <td>

                                                <select name="Status" class="form-control form-filter input-sm">

                                                    <option value="">Select...</option>

                                                    <option value="1">Active</option>

                                                    <option value="0">Deactive</option>                                            

                                                </select>

                                            </td>

                                            <td><div class="margin-bottom-5">

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

                    <!-- END EXAMPLE TABLE PORTLET-->

                </div>

            </div>

            <!-- END PAGE CONTENT-->

        </div>

    </div>

    <!-- END CONTENT -->

</div>

<!-- Modal -->

<div id="add_amount" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Add Amount</h4>

      </div>

      <div class="modal-body">

        <form id="form_add_amount" name="form_add_amount" method="post" class="form-horizontal" enctype="multipart/form-data">

            <div class="row">

                <div class="col-sm-12">

                <div class="form-group">

                  <label class="control-label col-md-4">Amount<span class="required">*</span></label>

                  <div class="col-sm-8">

                    <input type="text" class="form-control format-val" name="subtotal" id="subtotal" value="" maxlength="10" onkeyup="calculation(this.value)" />

                  </div>

                </div>  

                <div class="form-group">

                    <label class="control-label col-md-4">Coupon Discount</label>

                    <div class="col-md-8">

                        <input type="text" data-value="" name="coupon_amount" id="coupon_amount" value="" maxlength="10" data-required="1" class="form-control" readonly=""/><label class="coupon-type"></label>

                       

                    </div>

                </div> 

                <div class="form-group">

                    <label class="control-label col-md-4">Restaurant tax Rate<span class="required">*</span></label>

                    <div class="col-md-8">

                        <input type="text" data-value="" name="tax_rate" id="tax_rate" value="" maxlength="10" data-required="1" class="form-control" readonly=""/><label class="amount-type"></label>

                    </div>

                </div>  

                <div class="form-group">

                    <input type="hidden" name="entity_id" id="entity_id" value="">

                    <label class="control-label col-md-4">Status<span class="required">*</span></label>

                    <div class="col-sm-8">

                        <select name="event_status" id="event_status" class="form-control form-filter input-sm">

                            <option value="">Select...</option>

                            <option value="pending">Pending</option>  

                            <option value="onGoing">On Going</option>

                            <option value="completed">Completed</option>

                            <option value="cancel">Cancel</option>                                                 

                        </select>

                    </div>

                </div>

                <div class="form-group">

                  <label class="control-label col-md-4">Total<span class="required">*</span></label>

                  <div class="col-sm-8">

                    <input type="text" class="form-control format-val" name="amount" id="amount" value="" maxlength="10" readonly="" />

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

<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>

<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/admin/scripts/datatable.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>

<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>

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

            "aaSorting": [[ 6, "desc" ]] // set first column as a default sort by asc

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

//add amount

function addAmount(entity_id,tax,coupon,tax_type,coupon_type){

    $('#add_amount #entity_id').val(entity_id);

    $('#add_amount #tax_rate').val(tax);

    coupon = (coupon == 0)?'':coupon;

    $('#add_amount #coupon_amount').val(coupon);

    coupon_type = (coupon_type == 'Percentage')?'%':'';

    $('#add_amount .coupon-type').html(coupon_type);

    tax_type = (tax_type == 'Percentage')?'%':'';

    $('#add_amount .amount-type').html(tax_type);

    $('#add_amount').modal('show');

}

//submit add amount form

$("#form_add_amount").submit(function(event) {

    $("#form_add_amount").validate();

    if (!$("#form_add_amount").valid()) return false;

    var url = BASEURL+"backoffice/event/addAmount";

    var form = $("#form_add_amount").serialize();

    $.ajax({

      type: "POST",

      url: url,

      data: form,

      dataType: 'json',

      beforeSend: function(){

        jQuery('#add_amount #loadingModal').show();

      },

      success: function(html) {

        jQuery('#add_amount #loadingModal').hide();

        grid.getDataTable().fnDraw(); 

        $('#add_amount').modal('hide');

      }

    });

    return false;

});

function calculation(sum){

    //tax

    var amount = $('#coupon_amount').val(); 

    var type = $('.coupon-type').html();

    var tax = $('#tax_rate').val();

    if($('.amount-type').html() == '' && !isNaN(tax) && tax != ''){

        sum = parseFloat(sum) + parseFloat(tax); 

    }else if(!isNaN(tax) && tax != ''){

        var taxs = (sum*tax)/100;

        sum = parseFloat(sum) + parseFloat(taxs);

    }

    //coupon

    if(type == 'Percentage' && amount != '' && amount != 0){

        var cpn = (sum*amount)/100;

        sum = sum - cpn;

    }else if(type == 'Amount' && amount != '' && amount != 0){

        sum = sum - amount;

    }

    if(!isNaN(sum)){

        $('#amount').val(sum);

    }else{

        $('#amount').val(0);

    }

}

$('#add_amount').on('hidden.bs.modal', function () {

    $(".modal-dialog .form-control").removeClass("error");

    $(".modal-dialog label.error").remove();

    $('#form_add_amount option').prop('selected', false);

    $('#form_add_amount input').val('');

});

$('#booking_date').datetimepicker({

    format: 'yyyy-mm-dd hh:ii',

    autoclose: true,

});

$('#end_date').datetimepicker({

    format: 'yyyy-mm-dd hh:ii',

    autoclose: true,

});

//get invoice

function getInvoice(entity_id){

 

      $.ajax({

      type: "POST",

      dataType : "html",

      url: BASEURL+"backoffice/event/getInvoice",

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

</script>

<?php $this->load->view(ADMIN_URL.'/footer');?>