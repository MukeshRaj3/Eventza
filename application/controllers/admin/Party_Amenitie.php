<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Party_Amenitie extends Admin_Controller {

  public function __construct() {
      parent::__construct();

      /* Load :: Common */
      $this->load->model('admin/party_Amenitie_model');
  }

  public function index() {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
    
    /* Load Template */
    $this->template->admin_render('admin/party_amenities/index', $this->data);
  }

  public function ajax_list() {
    
    $list = $this->party_Amenitie_model->get_datatables();
    $data = array();
    //$no = $_POST['start'];
    foreach ($list as $key => $user) {
        //$user->created_at = date('d-m-Y H:i', $user->created_at);
         $res_cat = $this->party_Amenitie_model->get_party_category($user->party_cat_id);
         if(!empty($res_cat))
         {
          $category_name=$res_cat->name;
         }else{
          $category_name='';
         }
         /*$type='';
          if($user->type==1)
         {
          $type='Party';
         }else{
          $type='Organization';
         }*/
        $view_detail = '<a href="Party_Amenitie/edit/'.$user->id.'" class="btn btn-dark btn-sm waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="mdi mdi-square-edit-outline"></i></a>';
        $view_detail .= '<button type="button" class="ml-1 btn btn-danger btn-sm waves-effect waves-light party_amenitie_delete_confirmation" data-toggle="modal" data-target="#party_amenitie_deleted" data-id='.$user->id.'><i class="mdi mdi-delete"></i></button>';
        //$no++;
        $row = array();
        $row[] = $key+1;
        $row[] = $user->name;
        //$row[] = $type;
        $row[] = $category_name;
        $row[] = $view_detail;
        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->party_Amenitie_model->count_all(),
        "recordsFiltered" => $this->party_Amenitie_model->count_filtered(),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function create() {
    /* Title Page */
    $this->page_title->push(lang('menu_add_party_amenitie'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('name', 'name', 'trim|required');
    $this->form_validation->set_rules('party_cat_id', 'category', 'trim|required');

    if ($this->form_validation->run() == TRUE) {
      $check = $this->party_Amenitie_model->check_party_amenitie($this->input->post('name'));
      if ($check) {
        $this->session->set_flashdata('message', ['0', 'This amenitie is already added']);
        redirect('admin/Party_Amenitie/create', 'refresh');
      }
    
      $insert = [
        'name' => $this->input->post('name'),
        //'type' => $this->input->post('type'),
        'party_cat_id' => $this->input->post('party_cat_id')
      ];
     
      $query = $this->party_Amenitie_model->create($insert);
      if ($query) {
        $this->session->set_flashdata('message', ['1', 'Party amenitie has been create successfully']);
        redirect('admin/Party_Amenitie/create', 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to create Party amenitie']);
        redirect('admin/Party_Amenitie/create', 'refresh');
      }
    } else {
     
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $res_cat = $this->party_Amenitie_model->get_all_party_category();
      $this->data['res_category'] =$res_cat;
      /* Load Template */
      $this->template->admin_render('admin/party_amenities/add', $this->data);
    }
  }

  public function edit($id = NULL) {
      if (is_null($id)) {
        $this->session->set_flashdata('message', ['0', 'Party amenitie not found']);
        redirect('admin/Party_Amenitie', 'refresh');  
      }
      
    /* Title Page */
    $this->page_title->push(lang('menu_edit_party_amenitie'));
    $this->data['pagetitle'] = $this->page_title->show();

    $this->form_validation->set_rules('name', 'name', 'trim|required');
    $get_data = $this->party_Amenitie_model->party_amenitie_details($id);
    if ($this->form_validation->run() == TRUE) {
      $check = $this->party_Amenitie_model->check_party_amenitie($this->input->post('name'));
      if (!empty($check)) {
        if ($check->id != $id) {
          $this->session->set_flashdata('message', ['0', 'This party amenitie is already added']);
          redirect('admin/Party_Amenitie/edit/'.$id, 'refresh');
        }
      }
      $insert = [
        'name' => $this->input->post('name'),
        //'type' => $this->input->post('type'),
        'party_cat_id' => $this->input->post('party_cat_id')
      ];
    
      // print_r($this->input->post());exit;
      $update = $this->party_Amenitie_model->update($id, $insert);
      if ($update) {
        $this->session->set_flashdata('message', ['1', 'Party amenitie has been updated successfully']);
        redirect('admin/Party_Amenitie/edit/'.$id, 'refresh');
      } else {
        $this->session->set_flashdata('message', ['0', 'unble to update party amenitie']);
        redirect('admin/Party_Amenitie/edit/'.$id, 'refresh');
      }
    } else {
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
      $this->data['party_amenitie'] = $this->party_Amenitie_model->party_amenitie_details($id);
      $res_cat = $this->party_Amenitie_model->get_all_party_category();
      $this->data['res_category'] =$res_cat;
      /* Load Template */
      $this->template->admin_render('admin/party_amenities/edit', $this->data);
    }
  }

  public function delete() {
      $delete = $this->party_Amenitie_model->delete($this->input->post('party_amenitie_id'));
      if ($delete) {
        $response = [
          'is_deleted' => 1,
          'message' => 'Party amenitie deleted successfully'
        ];
      } else {
        $response = [
          'status' => 0,
          'message' => 'unable to delete party amenitie'
        ];
      }
      echo json_encode($response);
  }
}