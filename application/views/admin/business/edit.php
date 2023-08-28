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
                        <div class="page-title-right">

                        </div>
                        <h4 class="page-title"><?php echo $pagetitle; ?></h4>
                    </div>
                </div>
            </div>     
            <!-- end page title --> 

            <?php if ($this->session->flashdata('message') !== NULL) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('message')['0'] == 1 ? 'success' : 'danger'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php print_r($this->session->flashdata('message')['1']); ?>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <?php echo form_open(base_url('admin/business/edit/'.$business->id), array('id' => 'update_business', 'class' => 'validate-form', 'onsubmit' => 'submitForm(event)')); ?>
                            <div class="row">
                                <input type="hidden" name="business_id" id="business_id" value="<?php echo $business->id; ?>">
                                <input type="hidden" name="business_facilities" id="business_facilities" value="<?php echo $business->facilities; ?>">
                                <input type="hidden" name="address_latitude" id="address_latitude" value="<?php echo $business->latitude; ?>">
                                <input type="hidden" name="address_longitude" id="address_longitude" value="<?php echo $business->longitude; ?>">
                                <input type="hidden" name="postcode" id="hide_postcode" maxlength="12" value="<?php echo $business->pincode; ?>">
                                <input type="hidden" name="venue_id" id="stadium_venue_id" value="<?php echo $stadium; ?>">
                                <div class="form-group col-md-4 col-6">
                                    <label for="title" class="form-label">Title*</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title" maxlength="100" autocomplete="off" value="<?php echo $business->title; ?>" >
                                    <div class="error"><?php echo form_error('title'); ?></div>
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label for="first_name" class="form-label">First Name*</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="first name" onkeydown="checkCharacterOnly(event);" maxlength="100" autocomplete="off" value="<?php echo $business->first_name; ?>" >
                                    <div class="error"><?php echo form_error('first_name'); ?></div>
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label for="last_name" class="form-label">Last Name*</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="last name" onkeydown="checkCharacterOnly(event);" maxlength="100" autocomplete="off" value="<?php echo $business->last_name; ?>" >
                                    <div class="error"><?php echo form_error('last_name'); ?></div>
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label for="contact_number" class="form-label">Contact Tel Number</label>
                                    <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="Tel Number" maxlength="12" onkeydown="checkNumberOnly(event);" autocomplete="off"  value="<?php echo $business->contact_number; ?>" >
                                    <div class="error"><?php echo form_error('contact_number'); ?></div>
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label for="businessName" class="form-label">Business Name*</label>
                                    <input type="text" name="business_name" id="business_name" class="form-control" placeholder="Business name" maxlength="100" autocomplete="off" value="<?php echo $business->full_name; ?>" >
                                    <div class="error"><?php echo form_error('business_name'); ?></div>
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label for="emailaddress" class="form-label">Business Tel Number</label>
                                    <input type="text" name="business_phone" id="business_phone" class="form-control" placeholder="Business Tel Number" maxlength="12" onkeydown="checkNumberOnly(event);" autocomplete="off"  value="<?php echo $business->phone; ?>" >
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label for="businessEmail" class="form-label">Business Email</label>
                                    <input type="email" name="business_email" value="<?php echo $business->business_email; ?>" id="business_email" class="form-control" placeholder="Email" autocomplete="off" role="presentation" >
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label for="emailaddress" class="form-label">Business Website</label>
                                    <input type="url" name="business_website" id="business_website" class="form-control" placeholder="Business website" autocomplete="off" value="<?php echo $business->website; ?>">
                                    <?php echo form_error('business_website'); ?>
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label for="emailaddress" class="form-label">Business Type*</label>
                                    <select name="business_type" id="business_type" class="form-control" >
                                        <option value="<?php echo $business_type_row->bt_id; ?>"><?php echo $business_type_row->business_name; ?></option>
                                        <?php foreach ($business_types as $type):?>
                                            <?php if($type->bt_id != $business_type_row->bt_id) { ?>
                                                <option value="<?php echo $type->bt_id ?>"><?php echo $type->business_name ?></option>
                                            <?php }  endforeach;?>
                                    </select>
                                    <div class="error"><?php echo form_error('business_type'); ?></div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label for="">Business Address</label>
                                        <textarea name="business_address" id="pac-input" cols="1" rows="1" class="form-control business_enquiries_address" placeholder="Business Address" maxlength="150"><?php echo $business->address; ?></textarea>
                                        <?php echo form_error('business_address'); ?>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group mb-3 load-near-by-stadium-business"> 
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <div id="map" class="col-md-12" style="height: 400px;"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 ml-3">
                                    <div class="col-md-8 mt-3">
                                        <p class="animated fadeInUp mb-3">Select Facilities</p>
                                        <div class="facilityContent row load-business-facilities  text-center">
                                        </div>
                                        <div id="loaderror" style="color:red"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div>
                <!-- end col -->
            </div>
            <!-- end row-->
        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
