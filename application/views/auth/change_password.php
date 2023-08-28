<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content-page">
      <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">            
                  <!-- start page title -->
                  <div class="row">
                        <div class="col-12">
                              <div class="page-title-box">
                                    <h4 class="page-title">Change Password</h4>
                              </div>
                        </div>
                  </div>     
                  <!-- end page title -->
                  <div class="row">
                        <div class="col-xl-5 col-md-7">
                              <div class="card">
                                    <div class="card-body">
                                          <?php if ($this->session->flashdata('message') !== NULL) { ?>
                                                <div class="alert alert-<?php echo $this->session->flashdata('message')['0'] == 1 ? 'success' : 'danger'; ?> alert-dismissible">
                                                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                      <?php print_r($this->session->flashdata('message')['1']); ?>
                                                </div>
                                          <?php } ?>
                                          <?php echo form_open(uri_string(), array('id' => 'formChangePassword', 'class' => 'needs-validation', 'novalidate' => 'novalidate'));?>
                                          <div class="row">                                 
                                                <div class="col-md-12">
                                                      <div class="form-group">
                                                            <?php echo lang('change_password_old_password_label', 'old_password'); ?> 
                                                            <?php echo form_input($old_password); ?>
                                                            <?php echo form_error('old'); ?>
                                                      </div>
                                                </div>
                                                <div class="col-md-12">
                                                      <div class="form-group">
                                                            <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label>
                                                            <?php echo form_input($new_password); ?>
                                                            <?php echo form_error('new'); ?>
                                                      </div>
                                                </div>
                                                <div class="col-md-12">
                                                      <div class="form-group">
                                                            <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm'); ?> 
                                                            <?php echo form_input($new_password_confirm); ?>
                                                            <?php echo form_error('new_confirm'); ?>
                                                      </div>
                                                </div>

                                                <?php echo form_input($user_id);?>
                                          </div>
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <div class="form-group">
                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                                                      </div>
                                                </div>
                                          </div>
                                          <?php echo form_close(); ?>

                                    </div> <!-- end card-body-->
                              </div> <!-- end card-->
                        </div> <!-- end col-->
                  </div>
            </div>
      </div>
</div>

