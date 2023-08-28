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
                <?php echo $this->session->flashdata('message')['1']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <?php echo form_open(base_url('admin/Organization_Amenitie/edit/'.$organization_amenitie->id), array('id' => 'update_organization_amenitie', 'class' => 'validate-form','enctype' => 'multipart/form-data', 'onsubmit' => 'submitForm(event)')); ?>
                            <div class="row">
                               
                               <!--  <input type="hidden" name="name" id="name" value="<?php echo $organization_amenitie->name; ?>"> -->
                                <div class="filedContent col-6 animated zoomUp">
                                    <div class="form-group">
                                        <label for="Address" class="form-label"> Name*</label>
                                        <div class="map-search-box">
                                            <input id="pac-input" class="form-control" name="name" value="<?php echo $organization_amenitie->name; ?>"  type="text" placeholder="Name">
                                            <div id="error" class="text-danger"><?php echo form_error('name'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                 
                           
                                <div class="col-lg-12 mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div>
                <!-- end col -->
            </div>
            <!-- end row-->
        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
