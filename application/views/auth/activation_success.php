<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">

                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            <span><img src="<?php echo base_url($frameworks_dir.'/admin/images/logo.png'); ?>" alt="" height="120"></span>
                        </div>

                        <div class="text-center w-75 m-auto login-head">                         
                            <p class="text-muted mb-4 mt-3"><h3>Your account has been activated successfully</h3></p>
                        </div>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<script type="text/javascript">
    var link = '<?php echo $this->config->item('user_app_deep_link'); ?>';
    if (link != "") {
        setTimeout(function () {  
            window.location = link;    
        }, 5000);
    }
</script>