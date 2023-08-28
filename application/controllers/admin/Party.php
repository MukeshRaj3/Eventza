<?php defined('BASEPATH') or exit('No direct script access allowed');

class Party extends Admin_Controller
{

  public function __construct()
  {
    parent::__construct();

    /* Load :: Common */
    $this->load->model('admin/party_model');
  }

  public function index($status = '')
  {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
    $this->data['status'] = $status;
    /* Load Template */
    $this->template->admin_render('admin/party/index', $this->data);
  }

  public function ajax_list()
  {

    $party = $this->party_model->get_datatables();
    $list = [];
    if ($party) {
      foreach ($party as $key => $value) {
        $type = [];
        $gender_type = explode(',', $value->gender);
        if ($gender_type) {
          foreach ($gender_type as $gender) {
            if ($gender == '1') {
              $type[] = 'male';
            } else if ($gender == '2') {
              $type[] = 'female';
            } else if ($gender == '3') {
              $type[] = 'couple';
            } else if ($gender == '4') {
              $type[] = 'other';
            }
          }
        }
        if (!empty($type)) {
          $gend = implode(',', $type);
          $party[$key]->gender = $gend;
        }
        $party[$key]->end_date = date('d-m-Y', intVal(date($value->end_date)));
        $party[$key]->start_date = date('d-m-Y', intVal(date($value->start_date)));
        $list[] = $value;
      }
    }
    $data = array();
    //$no = $_POST['start'];
    //echo "<pre>";
    //print_r($list); die;
    foreach ($list as $key => $user) {
      $image = '<img src="' . $user->cover_photo . '" width="60" height="60" alt="">';

      // if ($user->active == '1') {
      //   $active = '<span class="badge badge-success">active</span>';
      // } else {
      //   $active = '<span class="badge badge-warning">deactive</span>';
      // } 
      // if ($user->status == '1') {
      //   $status = '<span class="badge badge-warning">full</span>';
      // } else {
      //   $status = '<span class="badge badge-success">available</span>';
      // }
      $view_detail = '<button type="button" class="ml-1 btn btn-dark btn-sm waves-effect waves-light party_view" data-toggle="modal" data-target="#party_view" data-id=' . $user->id . '><i class="mdi mdi-eye"></i></button>';


      if ($user->approval_status == 1) {
        $approval_party = '<button type="button" class="ml-1 btn btn-dark btn-sm waves-effect waves-light party_approval_confirmation" data-toggle="modal" data-target="#party_approval" data-id=' . $user->id . '><i class="fa fa-check" aria-hidden="true"></i></button>';
      } else {
        $approval_party = '<button type="button" class="ml-1 btn btn-dark btn-sm waves-effect waves-light party_approval_confirmation" data-toggle="modal" data-target="#party_approval" data-id=' . $user->id . '><i class="fa fa-times" aria-hidden="true"></i></button>';
      }

      /* if($user->image_status==1)
          {
              $approval_image = '<button type="button" class="ml-1 btn btn-dark btn-sm waves-effect waves-light party_approval_image_confirmation" data-toggle="modal" data-target="#party_image_approval" data-id='.$user->id.'><i class="fa fa-check" aria-hidden="true"></i></button>';
          }else{
              $approval_image = '<button type="button" class="ml-1 btn btn-dark btn-sm waves-effect waves-light party_approval_image_confirmation" data-toggle="modal" data-target="#party_image_approval" data-id='.$user->id.'><i class="fa fa-times" aria-hidden="true"></i></button>';
          }*/


      $status = '<select class="form-control" id="active_status' . $user->id . '" onchange="active_status(' . $user->id . ')">';
      $select1 = '';
      $select2 = '';

      if ($user->active == '1') {
        $select1 = 'selected';
      }
      if ($user->active == '0') {
        $select2 = 'selected';
      }



      $status .= '<option value="1" ' . $select1 . '>Active</option>
                              <option value="0" ' . $select2 . '>Deactived</option>
                              </select>';

      $approval_image = '<select class="form-control" id="image_status' . $user->id . '" onchange="changes_approval_image_status(' . $user->id . ')">';
      $select_img1 = '';
      $select_img2 = '';
      $select_img3 = '';
      if ($user->image_status == '1') {
        $select_img1 = 'selected';
      }
      if ($user->image_status == '0') {
        $select_img2 = 'selected';
      }

      if ($user->image_status == '2') {
        $select_img3 = 'selected';
      }

      $approval_image .= '<option value="1" ' . $select_img1 . '>Approved</option>
                              <option value="0" ' . $select_img2 . '>Pending</option>
                               <option value="2" ' . $select_img3 . '>Rejected</option>
                              </select>';
      $view_detail_likes = '';
      $view_detail_views = '';
      $view_detail_ongoings = '';

      $view_detail_remark = '';


      //         $view_detail_ratings= $user->rating'<button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#update_rating'.$user->id.'" data-id='.$user->id.'><i class="fa fa-star" aria-hidden="true"></i>
      // </button>';
      //$no++;
      $row = array();
      $row[] = $user->title;

      $string = strip_tags($user->description);
      if (strlen($string) > 50) {

        // truncate string
        $stringCut = substr($string, 0, 50);
        $endPoint = strrpos($stringCut, ' ');

        //if the string doesn't contain any space then it will cut without word basis.
        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '... <a href="javascript:void(0);" data-toggle="modal" data-target="#description' . $user->id . '" data-id=' . $user->id . '>Read More</a><div id="description' . $user->id . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Description</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
            <div class="row">
             ' . $user->description . '
            </div>
            </div>
           
        </div>
    </div>
</div>';
      }
        $view_detail .= '<p><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light party_delete_confirmation" data-toggle="modal" data-target="#party_deleted" data-id='.$user->id.'><i class="mdi mdi-delete"></i></button></p>';

      $string;
      $row[] = $string;
      $row[] = $user->start_date;
      $row[] = $user->end_date;
      $row[] = $user->gender;
      // $row[] = $status;
      $row[] = $image;
      // $row[] = $view_detail_likes;
      // $row[] = $view_detail_views;
      // $row[] = $view_detail_ongoings;
      $row[] = $approval_image;
      $row[] = $approval_party;
      $row[] = $view_detail;
      $row[] = $status;
      // $row[] = $view_detail_remark;
      $data[] = $row;
    }
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->party_model->count_all(),
      "recordsFiltered" => $this->party_model->count_filtered(),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function create()
  {
    /* Title Page */
    $this->page_title->push(lang('menu_edit_business'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('full_name', 'full_name', 'trim|required');
    if (empty($_FILES['image']['name'])) {
      $this->form_validation->set_rules('image', 'Image', 'required');
    }
    if ($this->form_validation->run() == TRUE) {
      $check = $this->party_model->check_city($this->input->post('full_name'));
      if ($check) {
        $this->session->set_flashdata('message', ['0', 'This city is already added']);
        redirect('admin/party/create', 'refresh');
      }
      $popular = $this->input->post('is_popular');
      if (empty($popular)) {
        $popular = 0;
      }
      $insert = [
        'name' => $this->input->post('name'),
        'full_name' => $this->input->post('full_name'),
        'latitude' => $this->input->post('latitude'),
        'longitude' => $this->input->post('longitude'),
        'is_popular' => $popular,
        'updated_at' => time()
      ];
      if (!empty($_FILES['image']['name'])) {

        $file_name = 'city_' . time() . rand(100, 999);
        $config = [
          'upload_path' => './upload/city/',
          'file_name' => $file_name,
          'allowed_types' => 'png|jpg|jpeg',
          'max_size' => 50480,
          'max_width' => 20480,
          'max_height' => 20480,
          'file_ext_tolower' => TRUE,
          'remove_spaces' => TRUE,
        ];
        $this->load->library('upload/', $config);
        if ($this->upload->do_upload('image')) {
          $uploadData = $this->upload->data();
          $insert['image'] = 'upload/city/' . $uploadData['file_name'];
        } else {
          $this->session->set_flashdata('message', ['0', 'File Not Support']);
          redirect('admin/party/create', 'refresh');
        }
      }
      $query = $this->party_model->create($insert);
      if ($query) {
        $this->session->set_flashdata('message', ['1', 'City has been create successfully']);
        redirect('admin/party/create', 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to create city']);
        redirect('admin/party/create', 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      /* Load Template */
      $this->template->admin_render('admin/cities/add', $this->data);
    }
  }

  public function edit($id = NULL)
  {
    if (is_null($id)) {
      $this->session->set_flashdata('message', ['0', 'Cities not found']);
      redirect('admin/cities', 'refresh');
    }

    /* Title Page */
    $this->page_title->push(lang('menu_edit_business'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('full_name', 'full_name', 'trim|required');
    $get_data = $this->party_model->city_details($id);
    if ($this->form_validation->run() == TRUE) {
      $check = $this->party_model->check_city($this->input->post('full_name'));

      if ($check->id != $id) {
        $this->session->set_flashdata('message', ['0', 'This city is already added']);
        redirect('admin/cities/edit/' . $id, 'refresh');
      }
      $insert = [
        'name' => $this->input->post('name'),
        'full_name' => $this->input->post('full_name'),
        'latitude' => $this->input->post('latitude'),
        'longitude' => $this->input->post('longitude'),
        'is_popular' => $this->input->post('is_popular'),
        'updated_at' => time()
      ];
      if (!empty($_FILES['image']['name'])) {

        $file_name = 'city_' . time() . rand(100, 999);
        $config = [
          'upload_path' => './upload/city/',
          'file_name' => $file_name,
          'allowed_types' => 'png|jpg|jpeg',
          'max_size' => 50480,
          'max_width' => 20480,
          'max_height' => 20480,
          'file_ext_tolower' => TRUE,
          'remove_spaces' => TRUE,
        ];
        $this->load->library('upload/', $config);
        if ($this->upload->do_upload('image')) {
          $uploadData = $this->upload->data();
          $insert['image'] = 'upload/city/' . $uploadData['file_name'];
          if (!empty($get_data)) {
            if (is_file($get_data->image)) {
              unlink($get_data->image);
            }
          }
        } else {
          $this->session->set_flashdata('message', ['0', 'File Not Support']);
          redirect('admin/cities/edit/' . $id, 'refresh');
        }
      }
      // print_r($this->input->post());exit;
      $update = $this->party_model->update($id, $insert);
      if ($update) {
        $this->session->set_flashdata('message', ['1', 'City has been updated successfully']);
        redirect('admin/cities/edit/' . $id, 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to update city']);
        redirect('admin/cities/edit/' . $id, 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $this->data['city'] = $this->party_model->city_details($id);
      /* Load Template */
      $this->template->admin_render('admin/cities/edit', $this->data);
    }
  }

  public function delete()
  {

    $delete = $this->party_model->delete($this->input->post('party_id'));
    if($delete) {
      $response = [
        'status' => 1,
        'message' => 'Party deleted successfully'
      ];
    } else {
      $response = [
        'status' => 0,
        'message' => 'unable to delete city'
      ];
    }
    echo json_encode($response);
  }


  public function party_details()
  {
    $this->form_validation->set_rules('party_id', '', 'required', array('required' => '%s'));
    if ($this->form_validation->run() == true) {
      $party = $this->party_model->party_details($this->input->post('party_id'));
      if ($party) {
        $party->end_date = date('d-m-Y', date($party->end_date));
        $party->start_date = date('d-m-Y', date($party->start_date));
        $type = [];
        $gender_type = explode(',', $party->gender);
        if ($gender_type) {
          foreach ($gender_type as $gender) {
            if ($gender == '1') {
              $type[] = 'male';
            } else if ($gender == '2') {
              $type[] = 'female';
            } else if ($gender == '3') {
              $type[] = 'couple';
            } else if ($gender == '4') {
              $type[] = 'other';
            }
          }
        }
        if (!empty($type)) {
          $gend = implode(',', $type);
        }
        $party->gender = @$gend;

        $data = '
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Party Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5 class="card-title">Party Details</h5>
                <div class="card-header text-secondary">
                    <table>
                        <tbody>
                            <tr>
                                <td colspan="2"> <span class=""><img src="' . $party->cover_photo . '" width="100%" height="150px"></span> </td>
                            </tr>
                            <tr><td colspan="2"><br></td></tr>
                            <tr>
                                <td width="200px" class="pr-2"><b>Title</b></td>
                                <td width="200px"> <span class="">' . $party->title . '</span> </td>
                            </tr><tr>
                                <td class="pr-2"><b>Offer</b></td>
                                <td> <span class="">' . $party->offers . '</span> </td>
                            </tr>';
        if (!empty($party->organization)) {
          $data .= '
                                <tr>
                                  <td width="200px" class="pr-2"><b>Organization</b></td>
                                  <td width="200px"> <span class="">' . $party->organization . '</span> </td>
                                </tr>';
        }
        $data .= '<tr>
                                <td class="pr-2"><b>Description</b></td>
                                <td> <span class="">' . $party->description . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party Type</b></td>
                                <td> <span class="">' . $party->type . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party Gender</b></td>
                                <td> <span class="">' . $party->gender . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party Start Date</b></td>
                                <td> <span class="">' . $party->start_date . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party End Date</b></td>
                                <td> <span class="">' . $party->end_date . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party Start Time</b></td>
                                <td> <span class="">' . $party->start_time . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party End Time</b></td>
                                <td> <span class="">' . $party->end_time . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Person Limit</b></td>
                                <td> <span class="">' . $party->person_limit . '</span> </td>
                            </tr>
                             <tr>
                                <td class="pr-2"><b>Party Status</b></td>
                                <td> <span class="">
                             <select class="form-control" id="party_status_pr' . $party->id . '" onchange="party_status_pr(' . $party->id . ')">';
        $select1 = '';
        $select2 = '';
        if ($party->papular_status == 1) {
          $select1 = 'selected';
        }
        if ($party->papular_status == 2) {
          $select2 = 'selected';
        }

        $data .= '<option value="1" ' . $select1 . '>Popular</option>
                              <option value="2" ' . $select2 . '>Regular</option>
                              </select></span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Likes</b></td>
                                <td> <span class="">' . $party->like . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>View</b></td>
                                <td> <span class="">' . $party->view . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Ongoing</b></td>
                                <td> <span class="">' . $party->ongoing . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Ladies Rs.</b></td>
                                <td> <span class="">' . $party->ladies . '</span> </td>
                            </tr>
                             <tr>
                                <td class="pr-2"><b>Stag Rs.</b></td>
                                <td> <span class="">' . $party->stag . '</span> </td>
                            </tr>
                             <tr>
                                <td class="pr-2"><b>Couples Rs.</b></td>
                                <td> <span class="">' . $party->couples . '</span> </td>
                            </tr>
                             <tr>
                                <td class="pr-2"><b>Others Rs.</b></td>
                                <td> <span class="">' . $party->others . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Created at</b></td>
                                <td> <span class="">' . $party->created_at . '</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Created by</b></td>
                                <td> <span class="">' . $party->full_name . '</span> </td>
                            </tr>
                            <tr>
                                <td width="200px" class="pr-2"><b>Updated Likes</b></td>
                                <td width="200px"> <span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#update_like' . $party->id . '" data-id=' . $party->id . '><i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                </button><div id="update_like' . $party->id . '" class="modal fade" tabindex="-1" role="dialog"     aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Update Like</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                        <div class="row">
                                        <div class="col-sm">
                                        <lable>Like</lable>
                                           <input type="text" id="like' . $party->id . '" class="form-control" value="' . $party->like . '">
                                        </div>
                                          
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                            <button type="button" onclick="update_like(' . $party->id . ')" class="btn btn-primary waves-effect waves-light">Update</button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div>
                            </div></span> </td>
                            </tr>
                            <tr>
                                <td width="200px" class="pr-2"><b>Updated Views</b></td>
                                <td width="200px"> <span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#update_view' . $party->id . '" data-id=' . $party->id . '><i class="fa fa-eye" aria-hidden="true"></i>
                                </button><div id="update_view' . $party->id . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                        <div class="row">
                                        <div class="col-sm">
                                        <lable>View</lable>
                                           <input type="text" id="view' . $party->id . '" class="form-control" value="' . $party->view . '">
                                        </div>
                                        </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                            <button type="button" onclick="update_view(' . $party->id . ')" class="btn btn-primary waves-effect waves-light">Update</button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div>
                            </div></span> </td>
                            </tr>
                            <tr>
                                <td width="200px" class="pr-2"><b>Remark</b></td>
                                <td width="200px"> <span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#send_remark' . $party->id . '" data-id=' . $party->id . '><i class="fa fa-comment" aria-hidden="true"></i>

                            </button><div id="send_remark' . $party->id . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h4 class="modal-title" id="myModalLabel">Remark</h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                      </div>
                                      <div class="modal-body">
                                              <div class="row">
                                      <div class="col-sm">
                                      <lable>Description</lable>
                                          <textarea id="remark_message' . $party->id . '" class="form-control">
                                          </textarea>
                                      </div>
                                      </div>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                          <button type="button" onclick="send_remark(' . $party->id . ')" class="btn btn-primary waves-effect waves-light">Submit</button>
                                      </div>
                                  </div><!-- /.modal-content -->
                              </div>
                            </div></span> </td>
                            </tr>
                            <tr>
                                <td width="200px" class="pr-2"><b>Update Ongoings</b></td>
                                <td width="200px"> <span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#update_ongoing' . $party->id . '" data-id=' . $party->id . '><i class="fa fa-users" aria-hidden="true"></i>
                                </button><div id="update_ongoing' . $party->id . '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                   <div class="row">
                                            <div class="col-sm">
                                            <lable>View</lable>
                                               <input type="text" id="ongoing' . $party->id . '" class="form-control" value="' . $party->ongoing . '">
                                            </div>
                                            </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                                <button type="button" onclick="update_ongoing(' . $party->id . ')" class="btn btn-primary waves-effect waves-light">Update</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div></span> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </br>
                </br>
<!--                <div class="card-body">
                    <h5 class="card-title">Party Join User</h5>
                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" id="new_order_table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="mt-2">
                                <td>
                                    <span class="text-success">Focaccia Bread</span>
                                </td>
                                <td class="">
                                    1
                                </td>
                                <td class="">
                                    £8.00
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div> 
                <h5 class="card-title">Organizer</h5>
                <div class="card-header">
                    <div class="row">
                      <div class="col">' . $party->full_name . '</div>
                    </div>
                </div>

            </div> -->
            ';
        $response = [
          'status' => 1,
          'data' => $data,
          'message' => 'Party Found'
        ];
      } else {
        $response = [
          'status' => 0,
          'message' => 'party not found'
        ];
      }
    } else {
      $response = [
        'status' => 0,
        'message' => 'party id not found'
      ];
    }
    echo json_encode($response);
  }

  public function update_data()
  {
    date_default_timezone_set('Asia/Kolkata');
    $update_arr = $_POST;
    unset($update_arr['party_id']);
    $update = $this->party_model->update($_POST['party_id'], $update_arr);
    if ($update) {
      $res = $this->party_model->get_status($this->input->post('party_id'));
      if (isset($_POST['papular_status']) && $_POST['papular_status'] == 1) {
        /*-------------Create Notification------------*/
        $noti_arr = array('notification_title' => 'Party Popular', 'notification_message' => 'Congratulations your party is popular now.', 'notification_type' => 19, 'notification_type_name' => 'Party Popular', 'user_id' => $res->user_id,'notification_created_on'=>date('Y-m-d H:i:s'));
        $this->general_model->insert('notifications', $noti_arr);
        /*--------------------------------------------*/
      } else {
        /*-------------Create Notification------------*/
        $noti_arr = array('notification_title' => 'Party Regular', 'notification_message' => 'Congratulations your party is regular now.', 'notification_type' => 20, 'notification_type_name' => 'Party Regular', 'user_id' => $res->user_id,'notification_created_on'=>date('Y-m-d H:i:s'));
        $this->general_model->insert('notifications', $noti_arr);
        /*--------------------------------------------*/
      }
      $response = [
        'status' => 1,
        'message' => 'Data update successfully.'
      ];
    } else {
      $response = [
        'status' => 0,
        'message' => 'Data not update'
      ];
    }
    echo json_encode($response);
  }

  public function changes_approval_status_15_03_2023()
  {
    $update_arr = $_POST;
    unset($update_arr['party_id']);
    $res = $this->party_model->get_status($this->input->post('party_id'));
    //print_r($res); die;
    if ($res->active == 1) {
      $update_arr['active'] = '0';
    } else {
      $update_arr['active'] = '1';
    }
    $update = $this->party_model->update($_POST['party_id'], $update_arr);
    if ($update) {
      $response = [
        'status' => 1,
        'message' => 'Approval status change successfully.'
      ];
    } else {
      $response = [
        'status' => 0,
        'message' => 'Approval status not change'
      ];
    }
    echo json_encode($response);
  }

  public function changes_approval_image_status_15_03_2023()
  {
    $update_arr = $_POST;
    unset($update_arr['party_id']);
    $res = $this->party_model->get_status($this->input->post('party_id'));
    //print_r($res); die;
    if ($res->image_status == 1) {
      $update_arr['image_status'] = '0';
    } else {
      $update_arr['image_status'] = '1';
    }
    $update = $this->party_model->update($_POST['party_id'], $update_arr);
    if ($update) {
      $response = [
        'status' => 1,
        'message' => 'Approval status change successfully.'
      ];
    } else {
      $response = [
        'status' => 0,
        'message' => 'Approval status not change'
      ];
    }
    echo json_encode($response);
  }

  public function changes_approval_status()
  {
    $update_arr = $_POST;
    unset($update_arr['party_id']);

    $res = $this->party_model->get_status($this->input->post('party_id'));

    if ($res->approval_status == '1') {
      $update_arr['approval_status'] = '0';
    } else {
      $update_arr['approval_status'] = '1';
    }

    $update = $this->party_model->update($_POST['party_id'], $update_arr);
    if ($update) {
      if ($update_arr['approval_status'] == 1) {
        /*-------------Create Notification------------*/
         date_default_timezone_set('Asia/Kolkata');
         $noti_arr = array('notification_title' => 'Party Approval', 'notification_message' => 'Congratulations! your party is approved.', 'notification_type' => 3, 'notification_type_name' => 'Party Approval', 'user_id' => $res->user_id,'notification_created_on'=>date('Y-m-d H:i:s'));
        $this->general_model->insert('notifications', $noti_arr);
        /*--------------------------------------------*/
      }
      $response = [
        'status' => 1,
        'message' => 'Approval status change successfully.'
      ];
    } else {
      $response = [
        'status' => 0,
        'message' => 'Approval status not change'
      ];
    }
    echo json_encode($response);
  }

  public function changes_approval_image_status()
  {
    date_default_timezone_set('Asia/Kolkata');
    $update_arr = $_POST;
    unset($update_arr['party_id']);
    $res = $this->party_model->get_status($this->input->post('party_id'));
    //print_r($update_arr); die;

    //print_r($res); die;
    /*if($res->image_status==1)
     {
       $update_arr['image_status']='0';
     }else{
       $update_arr['image_status']='1';
     }*/
    $update = $this->party_model->update($_POST['party_id'], $update_arr);
    if ($update) {
      if ($update_arr['image_status'] == 1) {
        /*-------------Create Notification------------*/
        $noti_arr = array('notification_title' => 'Photo Approval', 'notification_message' => 'Congratulations! Your photo is approved..', 'notification_type' => 9, 'notification_type_name' => 'Photo Approval', 'user_id' => $res->user_id,'notification_created_on'=>date('Y-m-d H:i:s'));
        $this->general_model->insert('notifications', $noti_arr);
        /*--------------------------------------------*/
      } else if ($update_arr['image_status'] == 2) {
        /*-------------Create Notification------------*/
        $noti_arr = array('notification_title' => 'Photo Approval', 'notification_message' => 'Oops! Your photo is rejected upload another one.', 'notification_type' => 10, 'notification_type_name' => 'Photo Approval', 'user_id' => $res->user_id,'notification_created_on'=>date('Y-m-d H:i:s'));
        $this->general_model->insert('notifications', $noti_arr);
        /*--------------------------------------------*/
      }
      $response = [
        'status' => 1,
        'message' => 'Approval status change successfully.'
      ];
    } else {
      $response = [
        'status' => 0,
        'message' => 'Approval status not change'
      ];
    }
    echo json_encode($response);
  }

  public function add_party_category($id)
  {
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();

    /* Load Template */
    $this->template->admin_render('admin/party/add_amenities', $this->data);
  }

  public function send_remark()
  {
    $update_arr = $_POST;
    $res = $this->general_model->getOne('party', array('id' => $this->input->post('p_id')));
    if ($res) {

      /*-------------Create Notification------------*/
      date_default_timezone_set('Asia/Kolkata');
      $noti_arr = array('notification_title' => 'Party - ' . $res->title, 'notification_message' => $_POST['description'], 'notification_type' => 15, 'notification_type_name' => 'Party Remark', 'user_id' => $res->user_id,'notification_created_on'=>date('Y-m-d H:i:s'));
      $this->general_model->insert('notifications', $noti_arr);
      /*--------------------------------------------*/
      $response = [
        'status' => 1,
        'message' => 'Party remark send successfully.'
      ];
    } else {
      $response = [
        'status' => 0,
        'message' => 'Party remark not sent'
      ];
    }
    echo json_encode($response);
  }
}
