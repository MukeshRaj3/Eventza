<?php defined('BASEPATH') OR exit('No direct script access allowed');

class organization_Amenitie extends Admin_Controller {

  public function __construct() {
      parent::__construct();

      /* Load :: Common */
      $this->load->model('admin/organization_Amenitie_model');
  }

  public function index() {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
    
    /* Load Template */
    $this->template->admin_render('admin/organization_amenities/index', $this->data);
  }

  public function ajax_list() {
    
    $list = $this->organization_Amenitie_model->get_datatables();
    $data = array();
    //$no = $_POST['start'];
    foreach ($list as $key => $user) {
        //$user->created_at = date('d-m-Y H:i', $user->created_at);
       
        $view_detail = '<a href="organization_Amenitie/edit/'.$user->id.'" class="btn btn-dark btn-sm waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="mdi mdi-square-edit-outline"></i></a>';
        $view_detail .= '<button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light organization_amenitie_delete_confirmation" data-toggle="modal" data-target="#organization_amenitie_deleted" data-id='.$user->id.'><i class="mdi mdi-delete"></i></button>';
        //$no++;
        $row = array();
        $row[] = $key+1;
        $row[] = $user->name;
        $row[] = $view_detail;
        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->organization_Amenitie_model->count_all(),
        "recordsFiltered" => $this->organization_Amenitie_model->count_filtered(),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function create() {
    /* Title Page */
    $this->page_title->push(lang('menu_add_organization_amenitie'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('name', 'name', 'trim|required');
  
    if ($this->form_validation->run() == TRUE) {
      $check = $this->organization_Amenitie_model->check_organization_amenitie($this->input->post('name'));
      if ($check) {
        $this->session->set_flashdata('message', ['0', 'This organization amenitie is already added']);
        redirect('admin/organization_Amenitie/create', 'refresh');
      }
    
      $insert = [
        'name' => $this->input->post('name')
      ];
     
      $query = $this->organization_Amenitie_model->create($insert);
      if ($query) {
        $this->session->set_flashdata('message', ['1', 'Organization amenitie has been create successfully']);
        redirect('admin/organization_Amenitie/create', 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to create organization amenitie']);
        redirect('admin/organization_Amenitie/create', 'refresh');
      }
    } else {
     
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      /* Load Template */
      $this->template->admin_render('admin/organization_amenities/add', $this->data);
    }
  }

  public function edit($id = NULL) {
      if (is_null($id)) {
        $this->session->set_flashdata('message', ['0', 'organization amenitie not found']);
        redirect('admin/organization_Amenitie', 'refresh');  
      }
      
    /* Title Page */
    $this->page_title->push(lang('menu_edit_organization_amenitie'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('name', 'name', 'trim|required');
    $get_data = $this->organization_Amenitie_model->organization_amenitie_details($id);
    if ($this->form_validation->run() == TRUE) {
      $check = $this->organization_Amenitie_model->check_organization_amenitie($this->input->post('name'));
      if (!empty($check)) {
        if ($check->id != $id) {
          $this->session->set_flashdata('message', ['0', 'This organization amenitie is already added']);
          redirect('admin/organization_Amenitie/edit/'.$id, 'refresh');
        }
      }
      $insert = [
        'name' => $this->input->post('name')
      ];
    
      // print_r($this->input->post());exit;
      $update = $this->organization_Amenitie_model->update($id, $insert);
      if ($update) {
        $this->session->set_flashdata('message', ['1', 'Organization amenitie has been updated successfully']);
        redirect('admin/organization_Amenitie/edit/'.$id, 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to update organization amenitie']);
        redirect('admin/organization_Amenitie/edit/'.$id, 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $this->data['organization_amenitie'] = $this->organization_Amenitie_model->organization_amenitie_details($id);
      /* Load Template */
      $this->template->admin_render('admin/organization_amenities/edit', $this->data);
    }
  }

  public function delete() {
      $delete = $this->organization_Amenitie_model->delete($this->input->post('organization_amenitie_id'));
      if ($delete) {
        $response = [
          'is_deleted' => 1,
          'message' => 'Organization amenitie deleted successfully'
        ];
      } else {
        $response = [
          'status' => 0,
          'message' => 'Unable to delete organization amenitie'
        ];
      }
      echo json_encode($response);
  }
}