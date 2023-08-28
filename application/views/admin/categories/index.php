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
                            <form action="<?= base_url('admin/categories/create'); ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="category_name">Category Name *</label>
                                    <input type="text" name="category_name" class="form-control" id="category_name" placeholder="Category name" value="<?php echo set_value('category_name'); ?>" required>
                                    <?php echo form_error('category_name'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="title">Activate</label>
                                    <select name="active_status" class="form-control" id="" required="">
                                      <option value="1">Yes</option>
                                      <option value="0">No</option>
                                    </select>
                                    <?php echo form_error('active_status'); ?>
                                </div>
                                
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                            </form>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div>
                <!-- end col -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="col-4">
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable-categories" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('categories_name'); ?></th>
                                        <th><?php echo $this->lang->line('categories_status'); ?></th>
                                        <th><?php echo $this->lang->line('categories_action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th><?php echo $this->lang->line('categories_name'); ?></th>
                                        <th><?php echo $this->lang->line('categories_status'); ?></th>
                                        <th><?php echo $this->lang->line('categories_action'); ?></th>
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

<div id="category_deleted" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this category ?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="category_id" name="category_id">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">No</button>
                <button type="button" id="category-delete-save" class="btn btn-primary waves-effect waves-light">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
