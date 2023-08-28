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
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <?php echo form_open_multipart(uri_string(), array('id' => 'form-add-facility', 'class' => 'validate-form')); ?>
                                <div class="form-group">
                                    <label for="businessType">Business Type *</label>
                                    <select name="business_type_id" id="business_type_id" class="form-control" required="">
                                        <option value="">Select Business Type</option>
                                        <?php foreach ($business_types as $key => $type) { ?>
                                            <option value="<?php echo $type->bt_id; ?>"><?php echo $type->business_name; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('business_type_id'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="facilityName">Facility Name *</label>
                                    <input type="text" name="facility_name" class="form-control" id="facility_name" maxlength="50" required="">
                                    <?php echo form_error('facility_name'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="facilityIcon">Facility Icon *</label>
                                    <input type="file" id="facility_icon" name="facility_icon" class="form-control" onchange="validateAndSetCrop(this);" data-id="1" data-ratioone="1" data-ratiotwo="1" data-maxsize="5" data-minwidth="30" data-minheight="30" data-maxheight="1024" data-maxwidth="1024" required="">
                                    <?php echo form_error('facility_icon'); ?>
                                </div>

                                
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
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
