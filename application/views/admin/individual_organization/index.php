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
             <input type="hidden" id="Status" value="<?php echo $status; ?>"> 
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- <a href="<?php echo base_url('admin/cities/create'); ?>" class="btn btn-primary waves-effect waves-light float-right"><i class="fe-plus"></i> Add New</a>
                            <br><br> -->
                            <div class="table-responsive ">
                                <table id="datatable-cities" class="table table-sm dt-responsive nowrap" style="width:1168px !important">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>City</th>
                                            <th>User Name</th>
                                            <!-- <th>Latitude</th> -->
                                            <!-- <th>Longitude</th> -->
                                            <th>Created Date</th>
                                            <th>Updated Date</th>
                                            <!-- <th>Update Likes</th> -->
                                            <!-- <th>Update  Views</th> -->
                                            <!-- <th>Update Ongoings</th> -->
                                            <!-- <th>Update Ratings</th> -->
                                            <!-- <th>Profile Pic Approval</th> -->
                                             <th>Approval Status</th>
                                            <th>Action</th>
                                            <!-- <th>Remark</th>  -->
                                            <th>Blue Tick</th> 
                                            <th>Add Display Name</th>
                                            <th>Display Name Status</th> 


                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>City</th>
                                            <th>User Name</th>
                                            <!-- <th>Latitude</th> -->
                                            <!-- <th>Longitude</th> -->
                                            <th>Created Date</th>
                                            <th>Updated Date</th>
                                            <!-- <th>Update Likes</th> -->
                                            <!-- <th>Update Views</th> -->
                                            <!-- <th>Update Ongoings</th> -->
                                            <!-- <th>Update Ratings</th> -->
                                            <!-- <th>Profile Pic Approval</th> -->
                                             <th>Approval Status</th>
                                            <th>Action</th>
                                             <!-- <th>Remark</th> -->
                                             <th>Blue Tick</th>  
                                             <th>Add Display Name</th>
                                            <th>Display Name Status</th> 


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

<div id="cities_deleted" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this city ?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="city_id" name="city_id">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">No</button>
                <button type="button" id="city-delete-save" class="btn btn-primary waves-effect waves-light">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div id="organization_view" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="show_data"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div id="organization_approval" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approval status change ?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="organization_id" name="organization_id">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">No</button>
                <button type="button" id="organization-approval-save" class="btn btn-primary waves-effect waves-light">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div id="organization_deleted" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this organization ?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="party_id" name="party_id">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">No</button>
                <button type="button" id="organization-delete-save" class="btn btn-primary waves-effect waves-light">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>