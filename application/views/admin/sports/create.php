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
                            <?php echo form_open_multipart(uri_string(), array('id' => 'form-add-sport', 'class' => 'validate-form')); ?>
                            <div class="row">


                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">Title *</label>
                                        <input type="text" name="title" class="form-control" id="title" value="<?= set_value('title'); ?>" maxlength="100" required="">
                                        <?php echo form_error('title'); ?>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date *</label>
                                        <input type="text" name="start_date" class="form-control datepicker_sports" id="start_date" value="<?= set_value('start_date'); ?>" required="" autocomplete="off">
                                        <?php echo form_error('start_date'); ?>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date *</label>
                                        <input type="text" name="end_date" class="form-control datepicker_sports" id="end_date" value="<?= set_value('end_date'); ?>" required="" autocomplete="off">
                                        <?php echo form_error('end_date'); ?>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="text_content">Content *</label>
                                        <textarea class="form-control" id="text_content" name="text_content"><?= set_value('text_content'); ?></textarea>
                                        <?php echo form_error('text_content'); ?>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="sport_event_image">Image *</label>
                                        <input type="hidden" id="dataX_1" name="dataX_1" />
                                        <input type="hidden" id="dataY_1" name="dataY_1" />
                                        <input type="hidden" id="dataWidth_1" name="dataWidth_1" />
                                        <input type="hidden" id="dataHeight_1" name="dataHeight_1" />
                                        <input type="hidden" name="imgCropped_1" id="imgCropped_1" />
                                        <input type="file" name="sport_event_image" class="form-control" id="sport_event_image" onchange="validate_profile(this)" data-id="1" required="">
                                        <?php echo form_error('sport_event_image'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="hide_show_crop_div">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="sizeInfo" style="color:#8c222e;"><b>Please drag the cursor over the image and select the area you wish to crop.Unless default area will be cropped.</b></span>
                                        <img class="cropper" src="" id="Image_1"> 
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
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
