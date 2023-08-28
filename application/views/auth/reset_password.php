
<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">

                    <div class="card-body p-4">
                        
                        <div class="text-center w-75 m-auto">
                            <span><img src="<?php echo base_url($frameworks_dir.'/admin/images/logo.png'); ?>" alt="" height="120"></span>
                            <p class="text-muted mb-4 mt-3">Set/Reset Password</p>
                        </div>

                        <?php if ($this->session->flashdata('message') !== NULL) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('message')['0'] == 1 ? 'success' : 'danger'; ?> alert-dismissable"><i class="zmdi zmdi-check-all"></i> <div class="activity"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo $this->session->flashdata('message')['1']; ?></div></div>
                        <?php } ?>

                        <?php echo form_open('auth/reset_password/' . $code);?>

                            <div class="form-group mb-3">
                                <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
                                <?php echo form_input($new_password);?>
                                <?php echo form_error('new'); ?>
                            </div>

                            <div class="form-group mb-3">
                                <label for="confirm_password"><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?></label>
                                <?php echo form_input($new_password_confirm);?>
                                <?php echo form_error('new_confirm'); ?>
                            </div>
							<?php echo form_input($user_id);?>
							<?php echo form_hidden($csrf); ?>
                            
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block" style="background-color:#e3732c;" type="submit"> <?php echo lang('reset_password_submit_btn'); ?> </button>
                            </div>
    
                        <?php echo form_close();?>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<style type="text/css">
    .error{
        color:red;
    }
</style>