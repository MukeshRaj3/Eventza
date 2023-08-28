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
                    <a href="<?php echo base_url('admin/users'); ?>" class="<?php echo $this->router->fetch_method() == 'users' ? 'active' : ''; ?>">
                        <i class="fe-user"></i>
                        <span> Users </span>
                    </a>
                </li>
                 <li>
                    <a href="<?php echo base_url('admin/users/user_deactivation_request'); ?>" class="<?php echo $this->router->fetch_method() == 'user_deactivation_request' ? 'active' : ''; ?>">
                        <i class="fe-user"></i>
                        <span>User Deactivation Request </span>
                    </a>
                </li>
                    <li>
                    <a href="<?php echo base_url('admin/users/user_block_reports'); ?>" class="<?php echo $this->router->fetch_class() == 'user_block_reports' ? 'active' : ''; ?>">
                        <i class="fe-user"></i>
                        <span>User Block Reports</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/cities'); ?>" class="<?php echo $this->router->fetch_class() == 'cities' ? 'active' : ''; ?>">
                    <i class="fe-home"></i>
                        <span> Cities </span>
                    </a>
                </li>

                  <li>
                    <a href="<?php echo base_url('admin/Party_Category'); ?>" class="<?php echo $this->router->fetch_class() == 'party_category' ? 'active' : ''; ?>">
                    <i class="fe-gift"></i>
                        <span> Party Category </span>
                    </a>
                </li>

                  <li>
                    <a href="<?php echo base_url('admin/Party_Amenitie'); ?>" class="<?php echo $this->router->fetch_class() == 'party_amenitie' ? 'active' : ''; ?>">
                    <i class="fe-gift"></i>
                        <span>Party Amenitie</span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url('admin/party'); ?>" class="<?php echo $this->router->fetch_method() == 'party' ? 'active' : ''; ?>">
                    <i class="fe-gift"></i>
                        <span>All Party </span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/party/index/2'); ?>" class="<?php echo $this->router->fetch_method() == '2' ? 'active' : ''; ?>">
                    <i class="fe-gift"></i>
                        <span>Under Review Party</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/party/index/1'); ?>" class="<?php echo $this->router->fetch_method() == '1' ? 'active' : ''; ?>">
                    <i class="fe-gift"></i>
                        <span>Approved Party</span>
                    </a>
                </li>
                 
                <li>
                    <a href="<?php echo base_url('admin/organization'); ?>" class="<?php echo $this->router->fetch_class() == 'organization' ? 'active' : ''; ?>">
                        <i class="fe-briefcase"></i>
                        <span> Organization </span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/individual_organization'); ?>" class="<?php echo $this->router->fetch_class() == 'individual_organization' ? 'active' : ''; ?>">
                        <i class="fe-briefcase"></i>
                        <span> Individual Organization </span>
                    </a>
                </li>
                    <li>
                    <a href="<?php echo base_url('admin/Organization_Category'); ?>" class="<?php echo $this->router->fetch_class() == 'Organization_Category' ? 'active' : ''; ?>">
                    <i class="fe-gift"></i>
                        <span> Individaul Organization Category</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/Individual_Organization_Amenitie'); ?>" class="<?php echo $this->router->fetch_class() == 'Individual_Organization_Amenitie' ? 'active' : ''; ?>">
                    <i class="fe-gift"></i>
                        <span> Individaul Organization Amenitie</span>
                    </a>
                </li>
                 <li>
                    <a href="<?php echo base_url('admin/Organization_Amenitie'); ?>" class="<?php echo $this->router->fetch_class() == 'Organization_Amenitie' ? 'active' : ''; ?>">
                    <i class="fe-gift"></i>
                        <span>Organization Amenitie</span>
                    </a>
                </li>
                 <li>
                    <a href="<?php echo base_url('admin/organization/org_pdf_list'); ?>" class="<?php echo $this->router->fetch_class() == 'org_pdf_list' ? 'active' : ''; ?>">
                      <i class="fa fa-file-pdf"></i>
                        <span>Organization PDF Upload</span>
                    </a>
                </li>
                  <li>
                    <a href="<?php echo base_url('admin/organization/org_pdf_verification_list'); ?>" class="<?php echo $this->router->fetch_class() == 'org_pdf_verification_list' ? 'active' : ''; ?>">
                      <i class="fa fa-file-pdf"></i>
                        <span>Organization PDF Verification</span>
                    </a>
                </li>
               <!-- <li>
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
