<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Party extends Admin_Controller {

  public function __construct() {
      parent::__construct();

      /* Load :: Common */
      $this->load->model('admin/party_model');
  }

  public function index() {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
    
    /* Load Template */
    $this->template->admin_render('admin/party/index', $this->data);
  }

  public function ajax_list() {
    
    $party = $this->party_model->get_datatables();
    $list= [];
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
    foreach ($list as $key => $user) {
        $image = '<img src="'.base_url($user->cover_photo).'" width="60" height="60" alt="">';
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
        $view_detail = '<button type="button" class="ml-1 btn btn-dark btn-sm waves-effect waves-light party_view" data-toggle="modal" data-target="#party_view" data-id='.$user->id.'><i class="mdi mdi-eye"></i></button>';
        // $view_detail .= '<button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light cities_delete_confirmation" data-toggle="modal" data-target="#cities_deleted" data-id='.$user->id.'><i class="mdi mdi-delete"></i></button>';
        //$no++;
        $row = array();
        $row[] = $user->title;
        $row[] = $user->description;
        $row[] = $user->start_date;
        $row[] = $user->end_date;
        $row[] = $user->gender;
        // $row[] = $status;
        $row[] = $image;
        $row[] = $view_detail;
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

  public function edit($id = NULL) {
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
          redirect('admin/cities/edit/'.$id, 'refresh');
        }
    }
      // print_r($this->input->post());exit;
      $update = $this->party_model->update($id, $insert);
      if ($update) {
        $this->session->set_flashdata('message', ['1', 'City has been updated successfully']);
        redirect('admin/cities/edit/'.$id, 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to update city']);
        redirect('admin/cities/edit/'.$id, 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $this->data['city'] = $this->party_model->city_details($id);
      /* Load Template */
      $this->template->admin_render('admin/cities/edit', $this->data);
    }
  }

  public function delete() {
      $delete = $this->party_model->delete($this->input->post('city_id'));
      if ($delete) {
        $response = [
          'status' => 1,
          'message' => 'city deleted successfully'
        ];
      } else {
        $response = [
          'status' => 0,
          'message' => 'unable to delete city'
        ];
      }
      echo json_encode($response);
  }


  public function party_details() {
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
            $party->gender = $gend;

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
                                <td colspan="2"> <span class=""><img src="'.base_url($party->cover_photo).' " width="100%" height="150px"></span> </td>
                            </tr>
                            <tr><td colspan="2"><br></td></tr>
                            <tr>
                                <td width="200px" class="pr-2"><b>Title</b></td>
                                <td width="200px"> <span class="">'.$party->title.'</span> </td>
                            </tr>';
                            if (!empty($party->organization)) {
                              $data .= '
                                <tr>
                                  <td width="200px" class="pr-2"><b>Organization</b></td>
                                  <td width="200px"> <span class="">'.$party->organization.'</span> </td>
                                </tr>';
                            }                
                        $data .= '<tr>
                                <td class="pr-2"><b>Description</b></td>
                                <td> <span class="">'.$party->description.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party Type</b></td>
                                <td> <span class="">'.$party->type.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party Gender</b></td>
                                <td> <span class="">'.$party->gender.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party Start Date</b></td>
                                <td> <span class="">'.$party->start_date.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party End Date</b></td>
                                <td> <span class="">'.$party->end_date.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party Start Time</b></td>
                                <td> <span class="">'.$party->start_time.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Party End Time</b></td>
                                <td> <span class="">'.$party->end_time.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Person Limit</b></td>
                                <td> <span class="">'.$party->person_limit.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Created at</b></td>
                                <td> <span class="">'.$party->created_at.'</span> </td>
                            </tr>
                            <tr>
                                <td class="pr-2"><b>Created by</b></td>
                                <td> <span class="">'.$party->full_name.'</span> </td>
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
                      <div class="col">'.$party->full_name.'</div>
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
}