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
                            <?php echo form_open_multipart(uri_string(), array('id' => 'form-add-post', 'class' => 'validate-form')); ?>
                            <div class="row">


                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">Title *</label>
                                        <input type="text" name="title" class="form-control" id="title" value="<?= set_value('title'); ?>" maxlength="100" required="">
                                        <?php echo form_error('title'); ?>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="body">Body *</label>
                                        <textarea name="body" class="form-control" id="body" required=""><?= set_value('body'); ?></textarea>
                                        <?php echo form_error('body'); ?>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="published_at">Published At *</label>
                                        <input type="text" name="published_at" class="form-control datepicker" id="published_at" value="<?= set_value('published_at'); ?>" required="">
                                        <?php echo form_error('published_at'); ?>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="category_ids">Select Category *</label>
                                        <select class="form-control stt multi-select show-tick" multiple="multiple" id="category_ids" name="category_ids[]" data-live-search="true" data-size="5" data-all="true" required>
                                            <?php if ($post_categories) { foreach ($post_categories as $category) { ?>
                                            <option value="<?php echo $category->id; ?>"><?= $category->name; ?></option>
                                            <?php } } ?>    
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="category_ids">Reading Time (Minute) *</label>
                                        <input type="text" name="reading_time" class="form-control" id="reading_time" value="<?= set_value('reading_time'); ?>" onkeydown="checkNumberOnly(event);" mexlength="3" required="">
                                    </div>
                                </div>

                                <input type="hidden" name="is_favourited" value="1">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="category_ids">Posted By *</label>
                                        <input type="text" name="posted_by" class="form-control" id="posted_by" value="<?= set_value('posted_by'); ?>" onkeydown="checkCharacterOnly(event);" maxlength="100" required="">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="featured_image">Post Image *</label>
                                        <input type="hidden" id="dataX_1" name="dataX_1" />
                                        <input type="hidden" id="dataY_1" name="dataY_1" />
                                        <input type="hidden" id="dataWidth_1" name="dataWidth_1" />
                                        <input type="hidden" id="dataHeight_1" name="dataHeight_1" />
                                        <input type="hidden" name="imgCropped_1" id="imgCropped_1" />
                                        <input type="file" name="featured_image" class="form-control" id="featured_image" onchange="validate_profile(this)" data-id="1" required="">
                                        <?php echo form_error('featured_image'); ?>
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
