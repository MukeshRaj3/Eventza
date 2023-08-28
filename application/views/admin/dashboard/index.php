============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->

<!--<div class="content-page">
    <div class="content">
      
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                        </div>
                        <?php echo $pagetitle; ?>
                    </div>
                </div>
            </div>  

            <?php if ($this->session->flashdata('message') !== NULL) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('message')['0'] == 1 ? 'success' : 'danger'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php print_r($this->session->flashdata('message')['1']); ?>
                </div>
            <?php } ?>
        <h3 class="smallBoxcs">
                            <a href="<?php echo base_url('organisation') ?>" style="color: black">
                              Individual  
                        </h3>
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 class=""><?php echo $total_users ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Total Users</span>
                </div>
            </div>
               <div class="col-lg-3 col-6">
                <div class="small-box" style="background-color:#1e7337">
                    <div class="inner">
                        <h3 class=""><?php echo $total_active_users ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Total Active Users</span>
                </div>
            </div>
             <div class="col-lg-3 col-6">
                <div class="small-box" style="background-color:#1e7337">
                    <div class="inner">
                        <h3 class=""><?php echo $total_inactive_users ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Total Inactive Users</span>
                </div>
            </div>
             <div class="col-lg-3 col-6">
                <div class="small-box" style="background-color:#1e7337">
                    <div class="inner">
                        <h3 class=""><?php echo $total_verified_users ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Verified Users</span>
                </div>
            </div>
           
             <div class="col-lg-3 col-6">
                <div class="small-box" style="background-color:#1e7337">
                    <div class="inner">
                        <h3 class=""><?php echo $total_unverified_users ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Unverified  Users</span>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 class=""><?php echo $total_party ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Total Party</span>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 class=""><?php echo $today_party ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/food.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Party (Today's) </span>
                </div>
            </div>
            <div class="col-lg-3 col-6" >
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 class="">0</h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/earning.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Total Revenue (Today's)</span>
                </div>
            </div>            
        </div>
         <h3 class="smallBoxcs">
                            <a href="<?php echo base_url('organisation') ?>" style="color: black">
                                Organisation
                        </h3>
         <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 class=""><?php echo $total_organisations; ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Total Organisations</span>
                </div>
            </div>
               <div class="col-lg-3 col-6">
                <div class="small-box" style="background-color:#1e7337">
                    <div class="inner">
                        <h3 class=""><?php echo $verified_organisations ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Verified Organisations</span>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 class=""><?php echo $unverified_organisations ?></h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion" src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg" alt="">
                    </div>
                    <span href="#" class="small-box-footer">Unverified Organisations</span>
                </div>
            </div>
                      
        </div>
        </div>
    </div> 
</div>-->

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->
<style>
    <?php include 'assets/frameworks/admin/css/bootstrap.min.css' ?>
    <?php include 'assets/frameworks/admin/css/custom.css' ?>

</style>



<div class="content-page">

    <div class="container">
        <div class="row mt-5">
             <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 class="smallBoxcs">
                            <a href="<?php echo base_url('indivisual') ?>" style="color: white">
                                Individual
                        </h3>
                        <p>&nbsp;</p>
                    </div>
                    <!-- <div class="icon">
                        <img class="ion"
                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                            alt="">
                    </div> -->
                    <!-- <span href="#" class="small-box-footer">Total Users</span> -->
                </div>
                <div>
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/users/index') ?>" style="color: white">

                                <div class="small-box bg-info">
                                    <div class="inner">

                                        <h3 class="">
                                            <?php echo $total_users ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Total Users</span>
                                </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/users/index/1') ?>" style="color: white">

                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3 class="">
                                            <?php echo $total_active_users ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Approved Users</span>
                                </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/users/index/2') ?>" style="color: white">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3 class="">
                                            <?php echo $total_inactive_users ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/food.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer"> 
                                    Not Approved Users </span>
                                </div>
                        </div>


                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/party') ?>" style="color: white">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3 class=""><?php echo $total_party ?></h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/earning.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Total Party</span>
                                </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/party/index/3') ?>" style="color: white">

                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3 class="">
                                            <?php echo $today_party ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Today Party</span>
                                </div>
                        </div>
                         <!--  <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/party/index/2') ?>" style="color: white">

                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3 class="">
                                            <?php echo $today_party ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Today Party</span>
                                </div>
                        </div> -->
                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/party/index/1') ?>" style="color: white">

                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3 class="">
                                            <?php echo $active_party ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer"> Approved Party</span>
                                </div>
                        </div>
                          <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/party/index/2') ?>" style="color: white">

                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3 class="">
                                            <?php echo $inactive_party ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Not Approved Party</span>
                                </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('organisation') ?>" style="color: white">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3 class="">
                                        0
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/food.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Total Revenue (Today's) </span>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 class="smallBoxcs">
                            <a href="<?php echo base_url('admin/organization') ?>" style="color: white">
                                Organisation
                        </h3>
                        <p>&nbsp;</p>
                    </div>
                    <div class="icon">
                        <img class="ion"
                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                            alt="">
                    </div>
                    <!-- <span href="#" class="small-box-footer">Total Users</span> -->
                </div>
                <div>
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/organization/index') ?>" style="color: white">

                                <div class="small-box bg-info">
                                    <div class="inner">

                                        <h3 class="">
                                            <?php echo $total_organisations ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Total Organisations</span>
                                </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/organization/index/1') ?>" style="color: white">

                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3 class="">
                                            <?php echo $verified_organisations ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/current_order.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Approved Organisations</span>
                                </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="<?php echo base_url('admin/organization/index/2') ?>" style="color: white">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3 class="">
                                            <?php echo $unverified_organisations ?>
                                        </h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/food.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Not Approved Organisations </span>
                                </div>
                        </div>


                       <!--  <div class="col-lg-3 col-6">
                            <a href="<?php echo base_url('organisation') ?>" style="color: white">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3 class="">0</h3>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="icon">
                                        <img class="ion"
                                            src="http://localhost/uk-eats-admin/assets/frameworks/admin/images/earning.svg"
                                            alt="">
                                    </div>
                                    <span href="#" class="small-box-footer">Revenue (Today's)</span>
                                </div>
                        </div> -->
                    </div>
                </div>
            </div>
           
        </div>
    </div>

</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ==============================================================