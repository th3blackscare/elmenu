<?php $this->load->view(ADMIN_URL.'/header');?>
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
<div class="page-container">
    <!-- BEGIN sidebar -->
<?php $this->load->view(ADMIN_URL.'/sidebar');?>
    <!-- END sidebar -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content admin-dashboard">          
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title"><?php echo $this->module_name ?> <small>statistics</small></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li><?php echo  $this->module_name ?> </li>                        
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div> 
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat red-intense">
                        <div class="visual">
                            <i class="fa fa-cutlery" aria-hidden="true"></i>
                       </div>
                        <div class="details">
                            <div class="number"><?php echo $restaurantCount ?></div>                           
                            <div class="desc">Total Restaurant</div>
                        </div>
                        <a class="more" href="<?php echo base_url().ADMIN_URL ?>/restaurant/view">
                            View more <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div> 
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat purple-plum">
                        <div class="visual">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="details">
                            <div class="number"> <?php echo $user ?></div>                           
                            <div class="desc">Total User</div>
                        </div>
                        <a class="more" href="<?php echo base_url().ADMIN_URL ?>/users/view">
                            View more <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div> 
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-stat blue-madison">
                        <div class="visual">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php echo $totalOrder ?></div>                           
                            <div class="desc">Total Order</div>
                        </div>
                        <a class="more" href="<?php echo base_url().ADMIN_URL ?>/order/view">
                            View more <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div> 
            </div> 
            <div class="row">
                <div class="col-md-6">
                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption">Restaurants</div>
                            <div class="actions">
                                <a href="<?php echo base_url().ADMIN_URL?>/restaurant/view" class="btn default btn-xs purple-stripe">View All</a>                                
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-container">
                                <table class="table table-hover">
                                    <thead>
                                    <tr> 
                                        <th>#</th>                                       
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>                                        
                                    </tr>                                    
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($restaurant)){
                                        $i = 1;
                                        foreach  ($restaurant as $key => $value) { ?>
                                             <tr>
                                                 <td><?php echo $i; ?></td>
                                                 <td><?php echo $value->name; ?></td>
                                                 <td><?php echo $value->phone_number; ?></td>
                                                 <td><?php echo $value->email; ?></td>
                                             </tr>
                                         
                                    <?php $i++; } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption">Orders</div>
                            <div class="actions">
                                <a href="<?php echo base_url().ADMIN_URL?>/order/view" class="btn default btn-xs purple-stripe">View All</a>                                
                            </div>                            
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Order Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($orders)){
                                $i = 1;
                                foreach  ($orders as $key => $val) { ?>
                                     <tr>
                                         <td><?php echo $i; ?></td>
                                         <td><?php echo $val->fname.' '.$val->lname ?></td>
                                         <td><?php echo $val->rate; ?></td>
                                         <td><?php echo $val->ostatus; ?></td>
                                         <td><?php echo ($val->order_date)?date('d-m-Y',strtotime($val->order_date)):''; ?></td>
                                     </tr>
                                 
                            <?php $i++; } } ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>  
            </div>                          
        </div>            
        <div class="clearfix">
        </div>
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/index.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init();
   Layout.init(); // init layout   
});
</script>
<!-- END JAVASCRIPTS -->
<?php $this->load->view(ADMIN_URL.'/footer');?>