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
                        <h4 class="page-title">Offer</h4>
                    </div>
                </div>
            </div>     

            <?php if ($this->session->flashdata('message') !== NULL) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('message')['0'] == 1 ? 'success' : 'danger'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php print_r($this->session->flashdata('message')['1']); ?>
                </div>
            <?php } ?>
                <div class="card">
                  <div class="card-body">
                    <h2 class="card-title offer_btn font-weight-bold">Add Offer</h2>
                    <div class="row offer_form">
                        <div class="col-6">
                            <div class="container">
                                <?php echo form_open(base_url('admin/business/offer/'.$business->id), array('id' => 'add_new_offer', 'class' => 'validate-form')); ?>
                                    <div class="border border-radius p-5">
                                        <p class="card-title cancel_btn" style="display:none">
                                            <a href="#" class="btn btn-primary waves-effect waves-light" onclick="cancel_offer()">Close Form</a>
                                        </p>
                                        <div class="form-group">
                                            <label for="title" class="form-label">Offer Name*</label>
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Offer Name" maxlength="100" autocomplete="off" value="" >
                                        </div>
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <textarea name="description" id="description" cols="1" rows="3" class="form-control" placeholder="Offer Detail" maxlength="200"></textarea>
                                        </div>
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-5 px-5">Submit</button>
                                        </div>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="" class="table dt-responsive nowrap" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Offer Title</th>
                                            <th>Offer Description</th>
                                            <th>ON/OFF</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($business_offers as $offer) { ?>
                                        <tr>
                                            <td><?php echo $offer->title ?></td>
                                            <td><?php echo $offer->description ?></td>
                                            <td> 
                                            <?php if ($offer->active == 1) { ?>
                                              <input type="checkbox" class="custom-control-input" id="cuisine_item_<?php echo $offer->id ?>" data-itemid="<?php echo $offer->id ?>" onchange="cuisineItemUnavailable(this);" checked>
                                              <label class="custom-control-label cuisine_item_<?php echo $offer->id ?>" for="cuisine_item_<?php echo $offer->id ?>">on</label>
                                              <?php } else  { ?>
                                              <input type="checkbox" class="custom-control-input" id="cuisine_item_<?php echo $offer->id ?>" data-itemid="<?php echo $offer->id ?>" onchange="cuisineItemUnavailable(this);">
                                              <label class="custom-control-label cuisine_item_<?php echo $offer->id ?>" for="cuisine_item_<?php echo $offer->id ?>">off</label>
                                          <?php }?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger waves-effect waves-light offer_delete_confirmation" data-toggle="modal" data-target="#offer_deleted" data-id="<?php echo $offer->id ?>" ><i class="mdi mdi-delete"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
            </div>
            
        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- business deleted model -->
<div id="offer_deleted" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this offer ? Once you click on YES all data will be delete permanently.</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="offer_id" name="offer_id">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">NO</button>
                <button type="button" id="offer-delete-save" class="btn btn-primary waves-effect waves-light">YES</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

            