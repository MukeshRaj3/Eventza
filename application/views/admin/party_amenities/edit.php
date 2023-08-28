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
                <?php echo $this->session->flashdata('message')['1']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <?php echo form_open(base_url('admin/Party_Amenitie/edit/'.$party_amenitie->id), array('id' => 'update_party_amenitie', 'class' => 'validate-form','enctype' => 'multipart/form-data', 'onsubmit' => 'submitForm(event)')); ?>
                            <div class="row">
                               
                               <!--  <input type="hidden" name="name" id="name" value="<?php echo $party_amenitie->name; ?>"> -->
                                <div class="filedContent col-12 animated zoomUp">
                                    <div class="form-group">
                                        <label for="Address" class="form-label"> Name*</label>
                                        <div class="map-search-box">
                                            <input id="pac-input" name="name" value="<?php echo $party_amenitie->name; ?>" onkeydown="return event.key != 'Enter';" class="form-control search_address_box fill_up_address" type="text" placeholder="Name">
                                            <div id="error" class="text-danger"><?php echo form_error('name'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                  <div class="filedContent col-12 animated zoomUp">
                                    <div class="form-group">
                                        <label for="Address" class="form-label">Party Category*</label>
                                        <div class="map-search-box">
                                            <select class="form-control" name="party_cat_id">
                                                <option></option>
                                        <?php if(!empty($res_category)){
                                                    foreach ($res_category as $key => $value) { ?>
                                                      <option value="<?php echo $value->id ?>" <?php if($party_amenitie->party_cat_id==$value->id){echo "selected=''";} ?>><?php echo $value->name ?></option>
                                                  <?php } } ?>
                                            </select>
                                           
                                            <div id="error" class="text-danger"><?php echo form_error('party_cat_id'); ?></div>
                                        </div>
                                    </div>
                                </div>   
                               <!--  <div class="col-12 ">
                                    <div class="form-group">
                                        <label for="Address" class="form-label">Type</label>
                                        <div class="map-search-box">
                                            <select class="form-control" name="type">
                                                <option></option>
                                             <option value="1" <?php if($party_amenitie->type==1){echo "selected=''";} ?>>Party</option>
                                            <option value="2" <?php if($party_amenitie->type==2){echo "selected=''";} ?>>Organization</option>
                                            </select>
                                           
                                            <div id="error" class="text-danger"><?php echo form_error('type'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                            -->       
                                <div class="col-lg-12 mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
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
