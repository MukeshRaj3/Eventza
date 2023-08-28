<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Organization_Category extends Admin_Controller {

  public function __construct() {
      parent::__construct();

      /* Load :: Common */
      $this->load->model('admin/organization_category_model');
  }

  public function index() {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
    
    /* Load Template */
    $this->template->admin_render('admin/individual_org_category/index', $this->data);
  }

  public function ajax_list() {
    
    $list = $this->organization_category_model->get_datatables();
    $data = array();
    //$no = $_POST['start'];
    foreach ($list as $key => $user) {
        //$user->created_at = date('d-m-Y H:i', $user->created_at);
       
        $view_detail = '<a href="Organization_Category/edit/'.$user->id.'" class="btn btn-dark btn-sm waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="mdi mdi-square-edit-outline"></i></a>';
        $view_detail .= '<button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light Organization_Category_delete_confirmation" data-toggle="modal" data-target="#Organization_Category_deleted" data-id='.$user->id.'><i class="mdi mdi-delete"></i></button>';
        //$no++;
        $row = array();
        $row[] = $key+1;
        $row[] = $user->name;
        $row[] = $view_detail;
        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->organization_category_model->count_all(),
        "recordsFiltered" => $this->organization_category_model->count_filtered(),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function create() {
    /* Title Page */
    $this->page_title->push(lang('menu_add_organization_category'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('name', 'name', 'trim|required');
    if ($this->form_validation->run() == TRUE) {
      $check = $this->organization_category_model->check_Organization_Category($this->input->post('name'));
      if ($check) {
        $this->session->set_flashdata('message', ['0', 'This category is already added']);
        redirect('admin/organization_category/create', 'refresh');
      }
    
      $insert = [
        'name' => $this->input->post('name'),
      ];
     
      $query = $this->organization_category_model->create($insert);
      if ($query) {
        $this->session->set_flashdata('message', ['1', 'Organization category has been create successfully']);
        redirect('admin/Organization_Category/create', 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to create Organization category']);
        redirect('admin/Organization_Category/create', 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      /* Load Template */
      $this->template->admin_render('admin/individual_org_category/add', $this->data);
    }
  }

  public function edit($id = NULL) {
      if (is_null($id)) {
        $this->session->set_flashdata('message', ['0', 'organization category not found']);
        redirect('admin/Organization_Category', 'refresh');	
      }
      
    /* Title Page */
    $this->page_title->push(lang('menu_edit_Organization_Category'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('name', 'name', 'trim|required');
    $get_data = $this->organization_category_model->organization_category_details($id);
    if ($this->form_validation->run() == TRUE) {
      $check = $this->organization_category_model->check_organization_category($this->input->post('name'));
      if (!empty($check)) {
        if ($check->id != $id) {
          $this->session->set_flashdata('message', ['0', 'This organization category is already added']);
          redirect('admin/Organization_Category/edit/'.$id, 'refresh');
        }
      }
      $insert = [
        'name' => $this->input->post('name')
      ];
    
      // print_r($this->input->post());exit;
      $update = $this->organization_category_model->update($id, $insert);
      if ($update) {
        $this->session->set_flashdata('message', ['1', 'Organization category has been updated successfully']);
        redirect('admin/Organization_Category/edit/'.$id, 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to update organization category']);
        redirect('admin/Organization_Category/edit/'.$id, 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $this->data['organization_category'] = $this->organization_category_model->organization_category_details($id);
      /* Load Template */
      $this->template->admin_render('admin/individual_org_category/edit', $this->data);
    }
  }

  public function delete() {
      $delete = $this->organization_category_model->delete($this->input->post('organization_category_id'));
      if ($delete) {
        $response = [
          'is_deleted' => 1,
          'message' => 'Organization category deleted successfully'
        ];
      } else {
        $response = [
          'status' => 0,
          'message' => 'unable to delete organization category'
        ];
      }
      echo json_encode($response);
  }
}