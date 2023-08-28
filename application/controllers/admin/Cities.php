<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cities extends Admin_Controller {

  public function __construct() {
      parent::__construct();

      /* Load :: Common */
      $this->load->model('admin/cities_model');
  }

  public function index() {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
    
    /* Load Template */
    $this->template->admin_render('admin/cities/index', $this->data);
  }

  public function ajax_list() {
    
    $list = $this->cities_model->get_datatables();
    $data = array();
    //$no = $_POST['start'];
    foreach ($list as $key => $user) {
        $user->created_at = date('d-m-Y H:i', $user->created_at);
        $image = '<img src="'.base_url($user->image).'" width="60" height="60" alt="">';
        if ($user->is_popular == '1') {
          $popular = '<span class="badge badge-success">yes</span>';
        } else {
          $popular = '<span class="badge badge-warning">no</span>';
        }
        $image = '<img src="'.base_url($user->image).'" width="60" height="60" alt="">';
        $view_detail = '<a href="cities/edit/'.$user->id.'" class="btn btn-dark btn-sm waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="mdi mdi-square-edit-outline"></i></a>';
        $view_detail .= '<button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light cities_delete_confirmation" data-toggle="modal" data-target="#cities_deleted" data-id='.$user->id.'><i class="mdi mdi-delete"></i></button>';
        //$no++;
        $row = array();
        $row[] = $user->name;
        $row[] = $user->full_name;
        $row[] = $popular;
        $row[] = $image;
        $row[] = $view_detail;
        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->cities_model->count_all(),
        "recordsFiltered" => $this->cities_model->count_filtered(),
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
      $check = $this->cities_model->check_city($this->input->post('full_name'));
      if ($check) {
        $this->session->set_flashdata('message', ['0', 'This city is already added']);
        redirect('admin/cities/create', 'refresh');
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
      //print_r($insert); die;
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
          redirect('admin/cities/create', 'refresh');
        }
    }
      $query = $this->cities_model->create($insert);
      if ($query) {
        $this->session->set_flashdata('message', ['1', 'City has been create successfully']);
        redirect('admin/cities/create', 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to create city']);
        redirect('admin/cities/create', 'refresh');
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
    $get_data = $this->cities_model->city_details($id);
    if ($this->form_validation->run() == TRUE) {
      $check = $this->cities_model->check_city($this->input->post('full_name'));
      if (!empty($check)) {
        if ($check->id != $id) {
          $this->session->set_flashdata('message', ['0', 'This city is already added']);
          redirect('admin/cities/edit/'.$id, 'refresh');
        }
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
      $update = $this->cities_model->update($id, $insert);
      if ($update) {
        $this->session->set_flashdata('message', ['1', 'City has been updated successfully']);
        redirect('admin/cities/edit/'.$id, 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to update city']);
        redirect('admin/cities/edit/'.$id, 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $this->data['city'] = $this->cities_model->city_details($id);
      /* Load Template */
      $this->template->admin_render('admin/cities/edit', $this->data);
    }
  }

  public function delete() {
      $delete = $this->cities_model->delete($this->input->post('city_id'));
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
}