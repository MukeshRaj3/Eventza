<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->


<style type="text/css">

.show-file-preview img {
	width: 200px;
	height: 200px;
}
.nav-tabs .nav-item {
    padding-left: 10rem!important;
    padding-right: 10rem!important;
}
</style>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="page-title-box">
                        <?php echo form_open(base_url('admin/business/create'), array('id' => 'add_business', 'class' => 'validate-form', 'enctype' => "multipart/form-data")); ?>
                    	<nav>
						  <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
						    <a class="nav-item nav-link active" id="nav-business-tab" data-toggle="tab" href="#nav_business" role="tab" aria-controls="nav-home" aria-selected="true">Add Business</a>
						    <a class="nav-item nav-link disabled" id="nav-profile-tab" data-toggle="tab" href="#nav_profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a>
						  </div>
						</nav>
						<div class=" card tab-content" id="nav-tabContent">
				            <?php if ($this->session->flashdata('message') !== NULL) { ?>
				                <div class="alert alert-<?php echo $this->session->flashdata('message')['0'] == 1 ? 'success' : 'danger'; ?> alert-dismissible">
				                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				                    <?php print_r($this->session->flashdata('message')['1']); ?>
				                </div>
				            <?php } ?>
						  <div class="tab-pane fade show active" id="nav_business" role="tabpanel" aria-labelledby="nav-home-tab">
							<div class="row">
				                <div class="col-lg-12">
				                    <div class="card pt-5">
				                        <div class="card-body">
				                            <div class="row">
				                                <input type="hidden" name="business_id" id="business_id" value="">
				                                <input type="hidden" name="business_facilities" id="business_facilities" value="">
				                                <input type="hidden" name="address_latitude" id="address_latitude" value="">
				                                <input type="hidden" name="address_longitude" id="address_longitude" value="">
				                                <input type="hidden" name="postcode" id="hide_postcode" maxlength="12" value="">
				                                <input type="hidden" name="venue_id" id="stadium_venue_id" value="">
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="title" class="form-label">Title*</label>
				                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title" maxlength="100" autocomplete="off" value="" >
				                                    <div class="error"><?php echo form_error('title'); ?></div>
				                                </div>
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="first_name" class="form-label">First Name*</label>
				                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="first name" onkeydown="checkCharacterOnly(event);" maxlength="100" autocomplete="off" value="" >
				                                    <div class="error"><?php echo form_error('first_name'); ?></div>
				                                </div>
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="last_name" class="form-label">Last Name*</label>
				                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="last name" onkeydown="checkCharacterOnly(event);" maxlength="100" autocomplete="off" value="" >
				                                    <div class="error"><?php echo form_error('last_name'); ?></div>
				                                </div>
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="Email" class="form-label">Contact Email*</label>
				                                    <input type="email" name="email" value="" id="email" class="form-control" placeholder="Email" autocomplete="off" role="presentation" onchange="check_email_exists(this);">
				                                    <div class="email-validation" style="color:red"></div>
				                                </div>
												<div class="form-group col-md-4 col-6">
													<label for="password" class="form-label">Password*</label>
													<input type="password" name="password" value="" id="password" class="form-control" role="presentation" autocomplete="off" placeholder="Password" autocomplete="off" >
												</div>
												<div class="form-group col-md-4 col-6">
													<label for="password_confirm" class="form-label">Confirm Password*</label>
													<input type="password" name="password_confirm" value="" id="password_confirm" class="form-control" placeholder="Confirm Password" autocomplete="off" >
												</div>
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="contact_number" class="form-label">Contact Tel Number</label>
				                                    <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="Tel Number" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" autocomplete="off"  value="" >
				                                    <div class="error"><?php echo form_error('contact_number'); ?></div>
				                                </div>
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="businessName" class="form-label">Business Name*</label>
				                                    <input type="text" name="business_name" id="business_name" class="form-control" placeholder="Business name" maxlength="100" autocomplete="off" value="" >
				                                    <div class="error"><?php echo form_error('business_name'); ?></div>
				                                </div>
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="emailaddress" class="form-label">Business Tel Number</label>
				                                    <input type="text" name="business_phone" id="business_phone" class="form-control" placeholder="Business Tel Number" maxlength="12"  oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" autocomplete="off"  value="" >
				                                </div>
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="businessEmail" class="form-label">Business Email</label>
				                                    <input type="email" name="business_email" value="" id="business_email" class="form-control" placeholder="Email" autocomplete="off" role="presentation" >
				                                </div>
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="emailaddress" class="form-label">Business Website</label>
				                                    <input type="url" name="business_website" id="business_website" class="form-control" placeholder="Business website" autocomplete="off" value="">
				                                    <?php echo form_error('business_website'); ?>
				                                </div>
				                                <div class="form-group col-md-4 col-6">
				                                    <label for="emailaddress" class="form-label">Business Type*</label>
				                                    <select name="business_type" id="business_type" class="form-control" >
				                                    	<option value="">Select Business Type</option>
				                                        <?php foreach ($business_types as $type):?>
				                                                <option value="<?php echo $type->bt_id ?>"><?php echo $type->business_name ?></option>
				                                            <?php endforeach;?>
				                                    </select>
				                                    <div class="error"><?php echo form_error('business_type'); ?></div>
				                                </div>
				                                <div class="col-lg-12">
				                                    <div class="form-group mb-3">
				                                        <label for="">Business Address*</label>
				                                        <textarea name="business_address" id="pac-input" cols="1" rows="1" class="form-control business_enquiries_address" placeholder="Business Address" maxlength="150" autocomplete="off"></textarea>
				                                        <?php echo form_error('business_address'); ?>
				                                    </div>
				                                </div>
				                                
				                                <div class="col-lg-12">
				                                    <div class="form-group mb-3">
				                                        <div id="map" class="col-md-12" style="height: 400px;"></div>
				                                    </div>
				                                </div>

				                                <div class="col-lg-12">
				                                    <div class="form-group mb-3 load-near-by-stadium-business"> 
				                                    </div>
				                                </div>
				                                
				                                <div class="col-lg-12 ml-3">
				                                    <div class="col-md-8 mt-3">
				                                        <p class="animated fadeInUp ml-n3">Select Facilities</p>
				                                        <div class="facilityContent row load-business-facilities  text-center">
				                                        </div>
				                                        <div id="loaderror" style="color:red"></div>
				                                    </div>
				                                </div>
				                            </div>
				                        </div> <!-- end card-body-->
				                    </div> <!-- end card-->
				                </div>
				                <!-- end col -->
				            </div>
				            <!-- end row-->
						  </div>
						  <div class="tab-pane fade show mb-5" id="nav_profile" role="tabpanel" aria-labelledby="nav-profile-tab">
						  	<div class="container my-5">
								<input type="hidden" id="file_increment" value="0">
								<div class="add_more_images">
									<div class="show-image-input">
			                            <div id="coba" class="row"></div>
			                            <div class="hide_show_crop_div">
			                                <span class="sizeInfo" style="color:#163357;font-size: 9px;"></span>
			                            </div>
			                        </div>
								</div>
						  	</div>
						  </div>
						</div>
						<br>

						<br>
                        <div class="col-lg-12 mt-3">
                        	<div class="row float-right">
	                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-5 px-5">Next</button>
                        	</div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>     
            <!-- end page title --> 
        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
