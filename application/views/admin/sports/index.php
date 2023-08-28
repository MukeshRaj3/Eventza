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
                    <div class="col-4">
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="<?php echo base_url('admin/sports/create'); ?>" class="btn btn-primary waves-effect waves-light float-right" style="margin-right: 5px;"><i class="fe-plus"></i> Add New </a>
                            <br><br>
                            <table id="datatable-sports-event" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Text Content</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Text Content</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>

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

<div id="sport_event_deleted" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this sport event ?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="sport_event_id" name="sport_event_id">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">No</button>
                <button type="button" id="sport-event-delete-save" class="btn btn-primary waves-effect waves-light">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>