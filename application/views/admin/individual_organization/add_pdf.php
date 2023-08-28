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
                            <?php echo form_open(base_url('admin/organization/org_pdf_add'), array('id' => 'update_business', 'class' => 'validate-form','enctype' => 'multipart/form-data', 'onsubmit' => 'submitForm(event)')); ?>
                            <div class="row">
                            
                              
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="file_a">PDF A</label>
                                         <input type="hidden" name="name" class="form-control" value="1">
                                        <input type="file" name="file_a" class="form-control" id="file_a" accept="application/pdf,application/vnd.ms-excel">
                                        <div id="error" class="text-danger"><?php echo form_error('file_a'); ?></div>
                                    </div>
                                </div>
                               

                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="file_b">PDF B</label>
                                        <input type="file" name="file_b" class="form-control" id="pdf_b" accept="application/pdf,application/vnd.ms-excel">
                                        <div id="error" class="text-danger"><?php echo form_error('file_b'); ?></div>
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
