<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="<?php echo base_url('admin/dashboard'); ?>" class="<?php echo $this->router->fetch_class() == 'dashboard' ? 'active' : ''; ?>">
                        <i class="fe-airplay"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url('admin/users'); ?>" class="<?php echo $this->router->fetch_class() == 'users' ? 'active' : ''; ?>">
                        <i class="fe-user"></i>
                        <span> Users </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url('admin/cities'); ?>" class="<?php echo $this->router->fetch_class() == 'cities' ? 'active' : ''; ?>">
                    <i class="fe-home"></i>
                        <span> Cities </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url('admin/party'); ?>" class="<?php echo $this->router->fetch_class() == 'party' ? 'active' : ''; ?>">
                    <i class="fe-gift"></i>
                        <span> Party </span>
                    </a>
                </li>
<!--                 
                <li>
                    <a href="<?php echo base_url('admin/business'); ?>" class="<?php echo $this->router->fetch_class() == 'business' ? 'active' : ''; ?>">
                        <i class="fe-briefcase"></i>
                        <span> Businesses </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url('admin/facilities'); ?>" class="<?php echo $this->router->fetch_class() == 'facilities' ? 'active' : ''; ?>">
                        <i class="fe-package"></i>
                        <span> Facilities </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url('admin/articles'); ?>" class="<?php echo $this->router->fetch_class() == 'articles' ? 'active' : ''; ?>">
                        <i class="fe-grid"></i>
                        <span> Articles </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url('admin/sports'); ?>" class="<?php echo $this->router->fetch_class() == 'sports' ? 'active' : ''; ?>">
                        <i class="fe-grid"></i>
                        <span> Sports Event </span>
                    </a>
                </li> -->
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
