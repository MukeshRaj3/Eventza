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
                            <?php echo form_open(base_url('admin/cities/create'), array('id' => 'update_business', 'class' => 'validate-form','enctype' => 'multipart/form-data', 'onsubmit' => 'submitForm(event)')); ?>
                            <div class="row">
                                <input type="hidden" name="latitude" id="address_latitude" value="">
                                <input type="hidden" name="longitude" id="address_longitude" value="">
                                <input type="hidden" name="name" id="name" value="">
                                <div class="filedContent col-12 animated zoomUp">
                                    <div class="form-group">
                                        <label for="Address" class="form-label">Update City Name*</label>
                                        <div class="map-search-box">
                                            <input id="pac-input" name="full_name" value="" onkeydown="return event.key != 'Enter';" class="form-control search_address_box fill_up_address" type="text" placeholder="Search Address Here">
                                            <div id="error" class="text-danger"><?php echo form_error('full_name'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="filedContent col-12 animated zoomUp">
                                    <div class="form-group">
                                        <label for="change_image">Change City Image</label>
                                        <input type="file" name="image" class="form-control-file" id="change_image">
                                        <div id="error" class="text-danger"><?php echo form_error('image'); ?></div>
                                    </div>
                                </div>
                                <div class="filedContent col-12 animated zoomUp ml-3 mt-2">
                                    <div class="form-group">
                                        <input name="is_popular" class="form-check-input" type="checkbox" value="1" id="is_popular">
                                        <label class="form-check-label" for="is_popular">
                                            Popular
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12 mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
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
