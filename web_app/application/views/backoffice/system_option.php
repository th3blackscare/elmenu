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
                                    
                                    <?php
                                    foreach ($SystemOptionList as $key => $OptionDet) 
                                    { ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo $OptionDet->OptionName; ?></label>
                                            <div class="col-md-4">
                                                <input type="hidden" name="SystemOptionID[]" value="<?php echo $OptionDet->SystemOptionID; ?>">
                                                <input type="text" name="OptionValue[]" value="<?php echo htmlentities($OptionDet->OptionValue); ?>" maxlength="250" class="form-control">
                                            </div>
                                        </div>

                                    <?php } ?>                              
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
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/datatable.js"></script>
<script>
jQuery(document).ready(function() {       
    Layout.init(); // init current layout
});
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>