<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            
                        </div>
                        <h4 class="page-title"><?php echo $pagetitle; ?></h4>
                    </div>
                </div>
            </div>     
            <!-- end page title --> 

            <?php if ($this->session->flashdata('message') !== NULL) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('message')['0'] == 1 ? 'success' : 'danger'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php print_r($this->session->flashdata('message')['1']); ?>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="<?php echo base_url('admin/business/create'); ?>" class="btn btn-primary waves-effect waves-light float-right"><i class="fe-plus"></i> Add New</a>
                            <br><br>
                            <div class="table-responsive">
                            <table id="datatable-businesses" class="table dt-responsive nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th>Business Name</th>
                                        <th>First line of address</th>
                                        <th>Address</th>
                                        <th>Business Email</th>
                                        <th>Contact Number</th>
                                        <th>Website</th>
                                        <th>Registered On</th>
                                        <th>Offers</th>
                                        <th>Action</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Business Name</th>
                                        <th>First line of address</th>
                                        <th>Address</th>
                                        <th>Business Email</th>
                                        <th>Contact Number</th>
                                        <th>Website</th>
                                        <th>Registered On</th>
                                        <th>Offers</th>
                                        <th>Action</th>
                                        <th>View</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->
        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
<div id="business_rejected" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject this business ? Once you click on YES all data will be discard permanently.</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="business_id" name="business_id">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">NO</button>
                <button type="button" id="business-reject-save" class="btn btn-primary waves-effect waves-light">YES</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<!-- business deleted model -->
<div id="business_deleted" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this business ? Once you click on YES all data will be delete permanently.</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="business_id" name="business_id">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">NO</button>
                <button type="button" id="business-delete-save" class="btn btn-primary waves-effect waves-light">YES</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>