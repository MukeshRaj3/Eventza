<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
                            <?php echo form_open_multipart(uri_string(), array('id' => 'form-edit-category', 'class' => 'validate-form')); ?>
                                <div class="form-group">
                                    <label for="categoryName">Category Name *</label>
                                    <input type="text" name="category_name" class="form-control" id="category_name" maxlength="50" value="<?php echo $category_data->name; ?>" required="">
                                    <?php echo form_error('category_name'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="title">Activate</label>
                                    <select name="active_status" class="form-control" id="" required="">
                                      <option value="1" <?php if($category_data->status == 1) { echo 'selected';} ?>>Yes</option>
                                      <option value="0" <?php if($category_data->status == 0) { echo 'selected';} ?>>No</option>
                                    </select>
                                    <?php echo form_error('active_status'); ?>
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


