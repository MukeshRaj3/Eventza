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
                            <table id="datatable-users-deactivation-request" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Description</th>
                                        <th>Created Date</th>
                                        <th>Updated Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Description</th>
                                        <th>Created Date</th>
                                        <th>Updated Date</th>
                                        <th>Status</th>
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