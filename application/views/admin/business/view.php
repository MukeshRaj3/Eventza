<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                        <div class="page-title-right"></div>
                        <?php echo $pagetitle; ?>
                    </div>
                </div>
            </div>     
            <!-- end page title --> 

            <div class="row" id="view_business_profile">
                <div class="col-lg-12">
                    <p class="sub-header"></p>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="sub-header mb-3">Business Info</p>
                                        <div class="d-flex">
                                            <a href="<?php echo base_url('admin/business'); ?>" class="btn btn-dark waves-effect waves-light mb-3"><i class="mdi mdi-arrow-left-bold mr-1"></i> Back</a>
                                        </div>
                                    </div>
                                    <table class="table table-striped business_detail">
                                        <tr>
                                            <th>Business Name :</th>
                                            <td><?= $business->full_name; ?></td>
                                        </tr>
                                        <tr>
                                            <th>First line of address :</th>
                                            <td><?= $business->extra_name; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Business Address :</th>
                                            <td><?= $business->extra_name.' <br>'.$business->address; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Business Email :</th>
                                            <td><?= $business->email; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Business Contact Number :</th>
                                            <td><?= $business->phone; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Stadium :</th>
                                             <td><?= !empty($stadium) ? $stadium : ""; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Business Type :</th>
                                             <td><?= $business_type->business_name; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Facilities :</th>
                                             <td><?= $facility_data['facilities']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Business Website URL :</th>
                                             <td><?= $business->website ? $business->website : '-'; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" name="address_latitude" id="address_latitude" value="<?= $business->latitude; ?>">
                                    <input type="hidden" name="address_longitude" id="address_longitude" value="<?= $business->longitude; ?>">
                                    <div class="form-group mb-3">
                                        <div id="map" class="col-md-12 autocomplete_search" style="height: 455px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                <p class="sub-header">Subscription Details</p>
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Plan Name :</th>
                                            <td>Pending</td>
                                        </tr>
                                        <tr>
                                            <th>Start Date :</th>
                                             <td>Pending</td>
                                        </tr>
                                        <tr>
                                            <th>End Date :</th>
                                             <td>Pending</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                             <td>Pending</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col-->
            </div>
            <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->
</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
<style type="text/css">
    .business_detail th {
        min-width: 150px;
    }
    .business_detail td {
        word-break: break-word;
    }
</style>