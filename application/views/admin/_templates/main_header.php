<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Topbar Start -->
<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-right mb-0">

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="<?php echo base_url($frameworks_dir.'/admin'); ?>/images/default.png" alt="user-image" class="rounded-circle">
                <span class="pro-user-name ml-1">
                    <?php echo @$logged_in->first_name; ?> <i class="mdi mdi-chevron-down"></i> 
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome !</h6>
                </div>

                <!-- item-->
                <a href="<?php echo base_url('auth/change_password'); ?>" class="dropdown-item notify-item">
                    <i class="ti-key m-r-10 text-custom"></i>
                    <span>Change Password</span>
                </a>

                <div class="dropdown-divider"></div>

                <!-- item-->
                <a href="<?php echo base_url('auth/logout'); ?>" class="dropdown-item notify-item">
                    <i class="fe-log-out"></i>
                    <span>Logout</span>
                </a>

            </div>
        </li>
    </ul>

    <!-- LOGO -->
    <div class="logo-box">
        <a href="index-2.html" class="logo text-center">
            <span class="logo-lg">
                <img src="<?php echo base_url($frameworks_dir.'/admin'); ?>/images/logo.png" alt="" height="60" style="border: 1px solid #FFF;">
            </span>
            <span class="logo-sm">
                <img src="<?php echo base_url($frameworks_dir.'/admin'); ?>/images/logo.png" alt="" height="24" style="border: 1px solid #FFF;">
            </span>
        </a>
    </div>

    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button class="button-menu-mobile waves-effect waves-light">
                <i class="fe-menu"></i>
            </button>
        </li>
    </ul>
</div>
<!-- end Topbar -->