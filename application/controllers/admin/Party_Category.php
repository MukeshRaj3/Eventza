<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Party_Category extends Admin_Controller {

  public function __construct() {
      parent::__construct();

      /* Load :: Common */
      $this->load->model('admin/Party_Category_model');
  }

  public function index() {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
    
    /* Load Template */
    $this->template->admin_render('admin/party_category/index', $this->data);
  }

  public function ajax_list() {
    
    $list = $this->Party_Category_model->get_datatables();
    $data = array();
    //$no = $_POST['start'];
    foreach ($list as $key => $user) {
        //$user->created_at = date('d-m-Y H:i', $user->created_at);
       
        $view_detail = '<a href="Party_Category/edit/'.$user->id.'" class="btn btn-dark btn-sm waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="mdi mdi-square-edit-outline"></i></a>';
        $view_detail .= '<button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light party_category_delete_confirmation" data-toggle="modal" data-target="#party_category_deleted" data-id='.$user->id.'><i class="mdi mdi-delete"></i></button>';
        //$no++;
        $row = array();
        $row[] = $key+1;
        $row[] = $user->name;
        $row[] = $view_detail;
        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->Party_Category_model->count_all(),
        "recordsFiltered" => $this->Party_Category_model->count_filtered(),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function create() {
    /* Title Page */
    $this->page_title->push(lang('menu_add_party_category'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('name', 'name', 'trim|required');
    if ($this->form_validation->run() == TRUE) {
      $check = $this->Party_Category_model->check_party_category($this->input->post('name'));
      if ($check) {
        $this->session->set_flashdata('message', ['0', 'This category is already added']);
        redirect('admin/Party_Category/create', 'refresh');
      }
    
      $insert = [
        'name' => $this->input->post('name'),
      ];
     
      $query = $this->Party_Category_model->create($insert);
      if ($query) {
        $this->session->set_flashdata('message', ['1', 'Party category has been create successfully']);
        redirect('admin/Party_Category/create', 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to create Party category']);
        redirect('admin/Party_Category/create', 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      /* Load Template */
      $this->template->admin_render('admin/party_category/add', $this->data);
    }
  }

  public function edit($id = NULL) {
      if (is_null($id)) {
        $this->session->set_flashdata('message', ['0', 'Party category not found']);
        redirect('admin/Party_Category', 'refresh');	
      }
      
    /* Title Page */
    $this->page_title->push(lang('menu_edit_party_category'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('name', 'name', 'trim|required');
    $get_data = $this->Party_Category_model->party_category_details($id);
    if ($this->form_validation->run() == TRUE) {
      $check = $this->Party_Category_model->check_party_category($this->input->post('name'));
      if (!empty($check)) {
        if ($check->id != $id) {
          $this->session->set_flashdata('message', ['0', 'This party category is already added']);
          redirect('admin/Party_Category/edit/'.$id, 'refresh');
        }
      }
      $insert = [
        'name' => $this->input->post('name')
      ];
    
      // print_r($this->input->post());exit;
      $update = $this->Party_Category_model->update($id, $insert);
      if ($update) {
        $this->session->set_flashdata('message', ['1', 'Party category has been updated successfully']);
        redirect('admin/Party_Category/edit/'.$id, 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to update party category']);
        redirect('admin/Party_Category/edit/'.$id, 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $this->data['party_category'] = $this->Party_Category_model->party_category_details($id);
      /* Load Template */
      $this->template->admin_render('admin/party_category/edit', $this->data);
    }
  }

  public function delete() {
      $delete = $this->Party_Category_model->delete($this->input->post('party_category_id'));
      if ($delete) {
        $response = [
          'is_deleted' => 1,
          'message' => 'Party category deleted successfully'
        ];
      } else {
        $response = [
          'status' => 0,
          'message' => 'unable to delete party category'
        ];
      }
      echo json_encode($response);
  }
}