<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Admin_Controller {

  public function __construct() {
      parent::__construct();

      /* Load :: Common */
      $this->load->model('admin/users_model');
  }

  public function index($status='') {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
     $this->data['status']=$status;
    /* Load Template */
    $this->template->admin_render('admin/users/index', $this->data);
  }

  public function ajax_list() {
    
    $list = $this->users_model->get_datatables();
    $data = array();
    //$no = $_POST['start'];
    foreach ($list as $key => $user) {
        $user->last_login = is_null($user->last_login) ? "-" : date('d-m-Y H:i', $user->last_login);
        $user->created_on = date('d-m-Y H:i', $user->created_on);
        //$no++;
        $status='<select class="form-control" id="user_status'.$user->id.'" onchange="user_status('.$user->id.')">';
                             $select1='';
                             $select2='';
                            if($user->active=='1')
                            {
                              $select1='selected';
                            }
                            if($user->active=='0')
                            {
                              $select2='selected';
                            }
                              
                              $status .= '<option value="1" '.$select1.'>Active</option>
                              <option value="0" '.$select2.'>Deactived</option>
                              </select>';
        $row = array();
        $row[] = $user->first_name;
        $row[] = $user->email;
        $row[] = $user->phone;
        $row[] = $user->last_login;
        $row[] = $user->created_on;
        $row[] = $status;
        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->users_model->count_all(),
        "recordsFiltered" => $this->users_model->count_filtered(),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function update_data()
  {
     $update_arr=$_POST;
     unset($update_arr['u_id']);
     $update = $this->users_model->update($_POST['u_id'], $update_arr);
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

  public function user_deactivation_request() {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
    
    /* Load Template */
    $this->template->admin_render('admin/users_deactivation_request/index', $this->data);
  }


  public function ajax_list_deactivation_request() {
    
    $list = $this->users_model->get_datatables_deactivation_request();
    $data = array();
    //$no = $_POST['start'];
    //print_r($list); die;
    foreach ($list as $key => $user) {
       
        $user->create_date = date('d-m-Y H:i:s',strtotime($user->create_date));
        $user->update_date = date('d-m-Y H:i:s',strtotime($user->update_date));
        //$no++;
        $status='<select class="form-control" id="status'.$user->id.'" onchange="status('.$user->id.')">';
                             $select1='';
                             $select2='';
                             $select3='';
                            if($user->status=='1')
                            {
                              $select1='selected';
                            }
                            if($user->status=='0')
                            {
                              $select2='selected';
                            }
                             if($user->status=='3')
                            {
                              $select3='selected';
                            }
                              
                              $status .= '<option value="1" '.$select1.'>Accepted</option>
                              <option value="0" '.$select2.'>Pending</option>
                              <option value="3" '.$select3.'>Rejected</option>
                              </select>';
        $row = array();
        $row[] = $user->first_name;
        $row[] = $user->email;
        $row[] = $user->phone;
        $row[] = $user->description;
        $row[] = $user->create_date;
        $row[] = $user->update_date;
        $row[] = $status;
        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->users_model->count_all_deactivation_request(),
        "recordsFiltered" => $this->users_model->count_filtered_deactivation_request(),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

public function update_deactivation_request()
  {
     $update_arr=$_POST;
     unset($update_arr['udr_id']);
     $update = $this->users_model->update_deactivation_request($_POST['udr_id'], $update_arr);
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

 public function user_block_reports() {
    /* Title Page */
    $this->page_title->push(lang('menu_users'));
    $this->data['pagetitle'] = $this->page_title->show();
    
    /* Load Template */
    $this->template->admin_render('admin/users_block_reports/index', $this->data);
  }


  public function ajax_list_block_report() {
    
    $list = $this->users_model->get_datatables_block_report();
    $data = array();
    //$no = $_POST['start'];
    foreach ($list as $key => $user) {
       
        $user->create_date = date('d-m-Y H:i:s',strtotime($user->create_date));
        $user->update_date = date('d-m-Y H:i:s',strtotime($user->update_date));
        //$no++;
        $status='<select class="form-control" id="status'.$user->id.'" onchange="status_block_report('.$user->id.')">';
                             $select1='';
                             $select2='';
                             $select3='';
                            if($user->status=='1')
                            {
                              $select1='selected';
                            }
                            if($user->status=='0')
                            {
                              $select2='selected';
                            }
                             if($user->status=='3')
                            {
                              $select3='selected';
                            }
                              
                              $status .= '<option value="1" '.$select1.'>Accepted</option>
                              <option value="0" '.$select2.'>Pending</option>
                              <option value="3" '.$select3.'>Rejected</option>
                              </select>';
        $row = array();
        $row[] = $user->first_name;
        $row[] = $user->email;
        $row[] = $user->phone;
        $row[] = $user->block_first_name;
        $row[] = $user->block_email;
        $row[] = $user->block_phone;
        $row[] = $user->description;
        $row[] = $user->create_date;
        // $row[] = $user->update_date;
        // $row[] = $status;
        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->users_model->count_all_block_report(),
        "recordsFiltered" => $this->users_model->count_filtered_block_report(),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

public function update_block_report()
  {
     $update_arr=$_POST;
     unset($update_arr['udr_id']);
     $update = $this->users_model->update_block_report($_POST['udr_id'], $update_arr);
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

}