<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Individual_Organization extends Admin_Controller {

  public function __construct() {
      parent::__construct();

      /* Load :: Common */
      $this->load->model('admin/individual_organization_model');
  }

  public function index($status='') {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
     $this->data['status']=$status;
    /* Load Template */
    $this->template->admin_render('admin/individual_organization/index', $this->data);
  }

  public function ajax_list() {
    
    $organization = $this->individual_organization_model->get_datatables();
    $list= [];
    if ($organization) {
        foreach ($organization as $key => $value) {
            $list[] = $value;
        }
    }
    $data = array();
    //$no = $_POST['start'];
    foreach ($list as $key => $user) {
        //$image = '<img src="'.base_url($user->cover_photo).'" width="60" height="60" alt="">';
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
        $view_detail = '<button type="button" class="ml-1 btn btn-dark btn-sm waves-effect waves-light organization_view" data-toggle="modal" data-target="#organization_view" data-id='.$user->id.'><i class="mdi mdi-eye"></i></button>';
       $view_detail_likes= '';
        $view_detail_views ='';
        $view_detail_ongoings='';

$view_detail_ratings='';
$view_detail_remark='';
 if($user->approval_status==1)
          {
              $approval_status = '<button type="button" class="ml-1 btn btn-dark btn-sm waves-effect waves-light organization_approval_confirmation" data-toggle="modal" data-target="#organization_approval" data-id='.$user->id.'><i class="fa fa-check" aria-hidden="true"></i></button>';
          }else{
              $approval_status = '<button type="button" class="ml-1 btn btn-dark btn-sm waves-effect waves-light organization_approval_confirmation" data-toggle="modal" data-target="#organization_approval" data-id='.$user->id.'><i class="fa fa-times" aria-hidden="true"></i></button>';

               
          } 
// $approval_profile_pic_status='<select class="form-control" id="profile_pic_approval_status'.$user->id.'" onchange="changes_profile_pic_approval_status('.$user->id.')">';
//                              $select_img1='';
//                              $select_img2='';
//                             $select_img3='';
//                             if($user->profile_pic_approval_status=='1')
//                             {
//                               $select_img1='selected';
//                             }
//                             if($user->profile_pic_approval_status=='0')
//                             {
//                               $select_img2='selected';
//                             }

//                             if($user->profile_pic_approval_status=='2')
//                             {
//                               $select_img3='selected';
//                             }
                              
//                               $approval_profile_pic_status .= '<option value="1" '.$select_img1.'>Approved</option>
//                               <option value="0" '.$select_img2.' disabled>Pending</option>
//                                <option value="2" '.$select_img3.'>Rejected</option>
//                               </select>';


$blue_tick_status='<select class="form-control" id="bluetick_status'.$user->id.'" onchange="changes_bluetick_status('.$user->id.')">';
                             $select1='';
                             $select2='';
                            
                            if($user->bluetick_status=='1')
                            {
                              $select1='selected';
                            }
                            if($user->bluetick_status=='0')
                            {
                              $select2='selected';
                            }

                           
                              
                              $blue_tick_status .= '<option value="1" '.$select1.'>Yes</option>
                              <option value="0" '.$select2.' >No</option>
                             
                              </select>';

$display_status='<select class="form-control" id="display_status'.$user->id.'" onchange="changes_display_status('.$user->id.')">';
                             $selectd1='';
                             $selectd2='';
                            
                            if($user->display_status=='1')
                            {
                              $selectd1='selected';
                            }
                            if($user->display_status=='0')
                            {
                              $selectd2='selected';
                            }

                           
                              
                              $display_status .= '<option value="1" '.$selectd1.'>Yes</option>
                              <option value="0" '.$selectd2.' >No</option>
                             
                              </select>';


        $view_detail .= '<p><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light organization_delete_confirmation" data-toggle="modal" data-target="#organization_deleted" data-id='.$user->id.'><i class="mdi mdi-delete"></i></button></p>';
        
        $row = array();
        $row[] = $user->name;
        $row[] = $user->description;
        $row[] = $user->city;
        $row[] = $user->full_name;
        // $row[] = $user->latitude;
        // $row[] = $user->longitude;
        $row[] =  date('d-m-Y H:i:s',strtotime($user->created_at));
        $row[] = $user->updated_at;
        // $row[] = $view_detail_likes;
        // $row[] = $view_detail_views;
        // $row[] = $view_detail_ongoings;
        // $row[] = $view_detail_ratings;
        // $row[] = $approval_profile_pic_status;
        $row[] = $approval_status;
        $row[] = $view_detail;
        // $row[] = $view_detail_remark;
        $row[] = $blue_tick_status;
        $row[] = $user->display_name;
        $row[] = $display_status;
        $data[] = $row;
    }
    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->individual_organization_model->count_all(),
        "recordsFiltered" => $this->individual_organization_model->count_filtered(),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function create() {
    /* Title Page */
    $this->page_title->push(lang('menu_edit_business'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('full_name', 'full_name', 'trim|required');
    if (empty($_FILES['image']['name']))
		{
			$this->form_validation->set_rules('image', 'Image', 'required');
		}
    if ($this->form_validation->run() == TRUE) {
      $check = $this->individual_organization_model->check_city($this->input->post('full_name'));
      if ($check) {
        $this->session->set_flashdata('message', ['0', 'This city is already added']);
        redirect('admin/individual_organization/create', 'refresh');
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
      if (! empty($_FILES['image']['name'])) {

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
          redirect('admin/organization/create', 'refresh');
        }
    }
      $query = $this->individual_organization_model->create($insert);
      if ($query) {
        $this->session->set_flashdata('message', ['1', 'City has been create successfully']);
        redirect('admin/individual_organization/create', 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to create city']);
        redirect('admin/individual_organization/create', 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      /* Load Template */
      $this->template->admin_render('admin/cities/add', $this->data);
    }
  }

  public function edit($id = NULL) {
      if (is_null($id)) {
        $this->session->set_flashdata('message', ['0', 'Cities not found']);
        redirect('admin/cities', 'refresh');	
      }
      
    /* Title Page */
    $this->page_title->push(lang('menu_edit_business'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('full_name', 'full_name', 'trim|required');
    $get_data = $this->individual_organization_model->city_details($id);
    if ($this->form_validation->run() == TRUE) {
      $check = $this->individual_organization_model->check_city($this->input->post('full_name'));

      if ($check->id != $id) {
        $this->session->set_flashdata('message', ['0', 'This city is already added']);
        redirect('admin/cities/edit/'.$id, 'refresh');
      }
      $insert = [
        'name' => $this->input->post('name'),
        'full_name' => $this->input->post('full_name'),
        'latitude' => $this->input->post('latitude'),
        'longitude' => $this->input->post('longitude'),
        'is_popular' => $this->input->post('is_popular'),
        'updated_at' => time()
      ];
      if (! empty($_FILES['image']['name'])) {

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
          redirect('admin/individual_organization/edit/'.$id, 'refresh');
        }
    }
      // print_r($this->input->post());exit;
      $update = $this->individual_organization_model->update($id, $insert);
      if ($update) {
        $this->session->set_flashdata('message', ['1', 'City has been updated successfully']);
        redirect('admin/individual_organization/edit/'.$id, 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to update city']);
        redirect('admin/individual_organization/edit/'.$id, 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $this->data['city'] = $this->individual_organization_model->city_details($id);
      /* Load Template */
      $this->template->admin_render('admin/cities/edit', $this->data);
    }
  }

  public function delete() {
      $delete = $this->individual_organization_model->delete($this->input->post('organization_id'));
      if ($delete) {
        $response = [
          'status' => 1,
          'message' => 'Organization deleted successfully'
        ];
      } else {
        $response = [
          'status' => 0,
          'message' => 'unable to delete organization'
        ];
      }
      echo json_encode($response);
  }


  public function organization_details() {
    $this->form_validation->set_rules('organization_id', '', 'required', array('required' => '%s'));
    if ($this->form_validation->run() == true) {
        $organization = $this->individual_organization_model->organization_details($this->input->post('organization_id'));
        // $user_id = $organization->user_id;
        // $user_details = $this->users_model->user_details($user_id);
        if ($organization) {
       

            $data = '
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Organization Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
               <!-- <h5 class="card-title">Organization Details</h5>-->
                <div class="card-header text-secondary">
                    <table>
                        <tbody>
                            <tr>
                                <td width="200px" class="pr-2"><b>Profile Pic</b></td>

                                <td colspan=""><span class=""><img src="'.$organization->profile_pic.' " width="100%" height="150px"></span> </td>
                            </tr>
                            <tr>
                                <td width="200px" class="pr-2"><b>Time Line Pic</b></td>
                                <td colspan=""> <span class=""><img src="'.$organization->timeline_pic.' " width="100%" height="150px"></span> </td>
                            </tr>
                        
                            <tr>
                                <td width="200px" class="pr-2"><b>Name</b></td>
                                <td width="200px"> <span class="">'.$organization->name.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Display Name</b></td>
                                <td>'.$organization->display_name.'<span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#update_display_name'.$organization->id.'" data-id='.$organization->id.'><i class="fa fa-plus" aria-hidden="true"></i>
                                </button><div id="update_display_name'.$organization->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Update Display Name</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                   <div class="row">
                                            <div class="col-sm">
                                            <lable>Display Name</lable>
                                               <input type="text" id="display_name'.$organization->id.'" class="form-control" value="'.$organization->display_name.'">
                                            </div>
                                            </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                                <button type="button" onclick="update_display_name('.$organization->id.')" class="btn btn-primary waves-effect waves-light">Update</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div></span> </td>
                            </tr>
                            <tr>
                                <td width="200px" class="pr-2"><b>Description</b></td>
                                <td width="200px"> <span class="">'.$organization->description.'</span> </td>
                            </tr>';
                                  
                        $data .= '<tr>
                                <td class="pr-2"><b>City Name</b></td>
                                <td> <span class="">'.$organization->city_name.'</span> </td>
                            </tr>
                            
                            <tr>
                                <td class="pr-2"><b>Create Date</b></td>
                                <td> <span class="">'.$organization->created_at.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Update Date</b></td>
                                <td> <span class="">'.$organization->updated_at.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Likes</b></td>
                                <td> <span class="">'.$organization->like.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Views</b></td>
                                <td> <span class="">'.$organization->view.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Ongoings</b></td>
                                <td> <span class="">'.$organization->ongoing.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Ratings</b></td>
                                <td> <span class="">'.$organization->rating.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Amenities</b></td>
                                <td> <span class="">'.$organization->amenitie.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Update Likes</b></td>
                                <td> <span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#update_like'.$organization->id.'" data-id='.$organization->id.'><i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                </button><div id="update_like'.$organization->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                               <input type="text" id="like'.$organization->id.'" class="form-control" value="'.$organization->like.'">
                                            </div>
                                              
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                                <button type="button" onclick="update_like('.$organization->id.')" class="btn btn-primary waves-effect waves-light">Update</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div></span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Update Views</b></td>
                                <td> <span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#update_view'.$organization->id.'" data-id='.$organization->id.'><i class="fa fa-eye" aria-hidden="true"></i>
                                </button><div id="update_view'.$organization->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Update View</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                            <div class="row">
                                            <div class="col-sm">
                                            <lable>View</lable>
                                               <input type="text" id="view'.$organization->id.'" class="form-control" value="'.$organization->view.'">
                                            </div>
                                            </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                                <button type="button" onclick="update_view('.$organization->id.')" class="btn btn-primary waves-effect waves-light">Update</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div></span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Update Ratings</b></td>
                                <td> <span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#update_rating'.$organization->id.'" data-id='.$organization->id.'><i class="fa fa-star" aria-hidden="true"></i>
                                </button><div id="update_rating'.$organization->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Update Rating</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                   <div class="row">
                                            <div class="col-sm">
                                            <lable>Rating</lable>
                                               <input type="text" id="rating'.$organization->id.'" class="form-control" value="'.$organization->rating.'">
                                            </div>
                                            </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                                <button type="button" onclick="update_rating('.$organization->id.')" class="btn btn-primary waves-effect waves-light">Update</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div></span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Remark</b></td>
                                <td> <span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#send_remark'.$organization->id.'" data-id='.$organization->id.'><i class="fa fa-comment" aria-hidden="true"></i>

                                </button><div id="send_remark'.$organization->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                               <textarea id="remark_message'.$organization->id.'" class="form-control">
                                               </textarea>
                                            </div>
                                            </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                                <button type="button" onclick="send_remark('.$organization->id.')" class="btn btn-primary waves-effect waves-light">Submit</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div></span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Update Ongoings</b></td>
                                <td> <span class=""><button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#update_ongoing'.$organization->id.'" data-id='.$organization->id.'><i class="fa fa-users" aria-hidden="true"></i>
                                </button><div id="update_ongoing'.$organization->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Update Ongoing</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                   <div class="row">
                                            <div class="col-sm">
                                            <lable>View</lable>
                                               <input type="text" id="ongoing'.$organization->id.'" class="form-control" value="'.$organization->ongoing.'">
                                            </div>
                                            </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Cancel</button>
                                                <button type="button" onclick="update_ongoing('.$organization->id.')" class="btn btn-primary waves-effect waves-light">Update</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div></span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Profile Pic Approval</b></td>
                                <td> <span class=""><select class="form-control" id="profile_pic_approval_status'.$organization->id.'" onchange="changes_profile_pic_approval_status('.$organization->id.')">';
                                $select_img1='';
                                $select_img2='';
                               $select_img3='';
                               if($organization->profile_pic_approval_status=='1')
                               {
                                 $select_img1='selected';
                               }
                               if($organization->profile_pic_approval_status=='0')
                               {
                                 $select_img2='selected';
                               }
   
                               if($organization->profile_pic_approval_status=='2')
                               {
                                 $select_img3='selected';
                               }
                               $data .= '<option value="1" '.$select_img1.'>Approved</option>
                                 <option value="0" '.$select_img2.' disabled>Pending</option>
                                  <option value="2" '.$select_img3.'>Rejected</option>
                                 </select></span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Latitude</b></td>
                                <td> <span class="">'.$organization->latitude.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Longitude</b></td>
                                <td> <span class="">'.$organization->longitude.'</span> </td>
                            </tr>

                        </tbody>
                    </table>
                </div>';
            $response = [
              'status' => 1,
              'data' => $data,
              'message' => 'organization Found'
            ];
        } else {
          $response = [
            'status' => 0,
            'message' => 'organization not found'
          ];
        }
    } else {
      $response = [
        'status' => 0,
        'message' => 'organization id not found'
      ];
    }
    echo json_encode($response);
  }

  public function update_data()
  {
     $update_arr=$_POST;
     unset($update_arr['org_id']);
     $update = $this->individual_organization_model->update($_POST['org_id'], $update_arr);
    if($update){
      $response = [
              'status' => 1,
              'message' => 'Data update successfully.'
            ];
        }else{
          $response = [
            'status' => 0,
            'message' => 'Data not update'
          ];
        }
    echo json_encode($response);
  }

    public function org_pdf_add() {
    /* Title Page */
    $this->page_title->push(lang('menu_organiztion_pdf'));
    $this->data['pagetitle'] = $this->page_title->show();
    $this->form_validation->set_rules('name', 'name', 'trim|required');
    if(empty($_FILES['file_a']['name']))
    {
      $this->form_validation->set_rules('file_a', 'pdf a', 'trim|required');
    }
    if(empty($_FILES['file_b']['name']))
    {
      $this->form_validation->set_rules('file_b', 'pdf b', 'trim|required');
    }
      //echo "<pre>";
    //print_r($this->form_validation->run()); die;
    if($this->form_validation->run()==TRUE) {
          
      $insert =array();
      if (!empty($_FILES['file_a']['name'])) {
        $pdf_a = 'pdf_a_' . time() . rand(100, 999);
        $config = [
            'upload_path' => './upload/organization/',
            'file_name' => $pdf_a,
            'allowed_types' => 'pdf',
            'max_size' => 50480,
            'remove_spaces' => TRUE,
        ];
        $this->load->library('upload/', $config);
        if ($this->upload->do_upload('file_a')) {
            $uploadData = $this->upload->data();
            $insert['pdf_a'] = 'upload/organization/' . $uploadData['file_name'];
        } else {
          $this->session->set_flashdata('message', ['0', 'File A Not Support']);
          redirect('admin/organization/org_pdf_add', 'refresh');
        }
    }

       if (!empty($_FILES['file_b']['name'])) {

        $pdf_b = 'pdf_b_' . time() . rand(100, 999);
        $config = [
            'upload_path' => './upload/organization/',
            'file_name' => $pdf_b,
            'allowed_types' => 'pdf',
            'remove_spaces' => TRUE,
        ];
        $this->load->library('upload/', $config);
        if ($this->upload->do_upload('file_b')) {
            $uploadData = $this->upload->data();
            $insert['pdf_b'] = 'upload/organization/' . $uploadData['file_name'];
        } else {
          $this->session->set_flashdata('message', ['0', 'File B Not Support']);
          redirect('admin/organization/org_pdf_add', 'refresh');
        }
    }
      $insert['created_at']=date('Y-m-d H:i:s');
      $query = $this->individual_organization_model->add_pdf($insert);
      if ($query) {
        $this->session->set_flashdata('message', ['1', 'Organization PDF has been add successfully']);
        redirect('admin/organization/org_pdf_add', 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to add organization PDF']);
        redirect('admin/organization/org_pdf_add', 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      /* Load Template */
      $this->template->admin_render('admin/organization/add_pdf', $this->data);
    }
  }

    public function edit_pdf($id = NULL) {
      if (is_null($id)) {
        $this->session->set_flashdata('message', ['0', 'Cities not found']);
        redirect('admin/cities', 'refresh');  
      }
      
    /* Title Page */
    $this->page_title->push(lang('menu_edit_pdf'));
    $this->data['pagetitle'] = $this->page_title->show();

   $this->form_validation->set_rules('name', 'name', 'trim|required');
    if(empty($_FILES['file_a']['name']))
    {
      $this->form_validation->set_rules('file_a', 'pdf a', 'trim|required');
    }
    if(empty($_FILES['file_b']['name']))
    {
      $this->form_validation->set_rules('file_b', 'pdf b', 'trim|required');
    }
    
    if ($this->form_validation->run() == TRUE) {
      $insert['created_at']=strtotime(date('d-m-Y'));
        $pdf_a = 'pdf_a_' . time() . rand(100, 999);
        $config = [
            'upload_path' => './upload/organization/',
            'file_name' => $pdf_a,
            'allowed_types' => 'pdf',
            'max_size' => 50480,
            'remove_spaces' => TRUE,
        ];
        $this->load->library('upload/', $config);
        if ($this->upload->do_upload('file_a')) {
            $uploadData = $this->upload->data();
            $insert['pdf_a'] = 'upload/organization/' . $uploadData['file_name'];
        } else {
          $this->session->set_flashdata('message', ['0', 'File A Not Support']);
          redirect('admin/organization/edit_pdf', 'refresh');
        }
   

       if (!empty($_FILES['file_b']['name'])) {

        $pdf_b = 'pdf_b_' . time() . rand(100, 999);
        $config = [
            'upload_path' => './upload/organization/',
            'file_name' => $pdf_b,
            'allowed_types' => 'pdf',
            'remove_spaces' => TRUE,
        ];
        $this->load->library('upload/', $config);
        if ($this->upload->do_upload('file_b')) {
            $uploadData = $this->upload->data();
            $insert['pdf_b'] = 'upload/organization/' . $uploadData['file_name'];
        } else {
          $this->session->set_flashdata('message', ['0', 'File B Not Support']);
          redirect('admin/organization/org_pdf_add', 'refresh');
        }
    }
      $insert['updated_at']=date('Y-m-d H:i:s');
      // print_r($this->input->post());exit;
      $update = $this->individual_organization_model->update($id, $insert);
      if ($update) {
        $this->session->set_flashdata('message', ['1', 'Organization PDF has been updated successfully']);
        redirect('admin/organization/edit_pdf/'.$id, 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to update organization pdf']);
        redirect('admin/organization/edit_pdf/'.$id, 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $this->data['pdf'] = $this->individual_organization_model->pdf_details($id);
      /* Load Template */
      $this->template->admin_render('admin/organization/edit_pdf', $this->data);
    }
  }

  

    public function changes_approval_image_status()
  {
     $update_arr=$_POST;
     unset($update_arr['org_id']);
     $res = $this->individual_organization_model->get_status($this->input->post('org_id'));
     $update = $this->individual_organization_model->update($_POST['org_id'],$update_arr);
    if($update){
        if($update_arr['profile_pic_approval_status']==1)
      {
         /*-------------Create Notification------------*/
                  $noti_arr=array('notification_title'=>'Organization Photo Approval','notification_message'=>'Congratulations! Your organization photo is approved.','notification_type'=>11,'notification_type_name'=>'Organization Photo Approval','user_id'=>$res->user_id);
                  $this->general_model->insert('notifications',$noti_arr);
                /*--------------------------------------------*/
      }else if($update_arr['profile_pic_approval_status']==2){
         /*-------------Create Notification------------*/
                  $noti_arr=array('notification_title'=>'Organization Photo Approval Rejected','notification_message'=>'Oops! Your organization photo is rejected upload another one.','notification_type'=>12,'notification_type_name'=>'Organization Photo Approval Rejected','user_id'=>$res->user_id);
                  $this->general_model->insert('notifications',$noti_arr);
                /*--------------------------------------------*/
      }
      $response = [
              'status' => 1,
              'message' => 'Approval status change successfully.'
            ];
        }else{
          $response = [
            'status' => 0,
            'message' => 'Approval status not change'
          ];
        }
    echo json_encode($response);
  } 

  public function changes_approval_status()
  {
     $update_arr=$_POST;
     unset($update_arr['organization_id']);
    
     $res = $this->individual_organization_model->get_status($this->input->post('organization_id'));
  
     if($res->approval_status=='1')
     {
       $update_arr['approval_status']='0';
     }else{
       $update_arr['approval_status']='1';
       $update_arr['approval_date']=date('Y-m-d H:i:s');


     }
     
     $update = $this->individual_organization_model->update($_POST['organization_id'],$update_arr);
    if($update){
      if($update_arr['approval_status']==1)
      {
         /*-------------Create Notification------------*/
                  $noti_arr=array('notification_title'=>'Organization Approval','notification_message'=>'Congratulations! your organization is approved.','notification_type'=>13,'notification_type_name'=>'Organization Approval','user_id'=>$res->user_id);
                  $this->general_model->insert('notifications',$noti_arr);
                /*--------------------------------------------*/
      }
      $response = [
              'status' => 1,
              'message' => 'Approval status change successfully.'
            ];
        }else{
          $response = [
            'status' => 0,
            'message' => 'Approval status not change'
          ];
        }
    echo json_encode($response);
  } 

  public function send_remark()
  {
     $update_arr=$_POST;
     $res = $this->general_model->getOne('organization',array('id'=>$this->input->post('org_id')));
    if($res){
      
         /*-------------Create Notification------------*/
          $noti_arr=array('notification_title'=>'Organization - '.$res->name,'notification_message'=>$_POST['description'],'notification_type'=>16,'notification_type_name'=>'Organization Remark','user_id'=>$res->user_id);
                  $this->general_model->insert('notifications',$noti_arr);
          /*--------------------------------------------*/
          $response = [
              'status' => 1,
              'message' => 'Organization remark send successfully.'
            ];
        }else{
          $response = [
            'status' => 0,
            'message' => 'Organization remark not sent'
          ];
        }
    echo json_encode($response);
  }

public function changes_bluetick_status()
  {
     $update_arr=$_POST;
     unset($update_arr['org_id']);
     $update = $this->individual_organization_model->update($_POST['org_id'],$update_arr);
    if($update){
      $response = [
              'status' => 1,
              'message' => 'Blue tick status change successfully.'
            ];
        }else{
          $response = [
            'status' => 0,
            'message' => 'Blue tick status not change'
          ];
        }
    echo json_encode($response);
  } 

  public function changes_display_status()
  {
     $update_arr=$_POST;
     unset($update_arr['org_id']);
     $update = $this->individual_organization_model->update($_POST['org_id'],$update_arr);
    if($update){
      $response = [
              'status' => 1,
              'message' => 'Display status status change successfully.'
            ];
        }else{
          $response = [
            'status' => 0,
            'message' => 'Display status status not change'
          ];
        }
    echo json_encode($response);
  } 

}