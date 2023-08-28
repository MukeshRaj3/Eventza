<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
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
        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
