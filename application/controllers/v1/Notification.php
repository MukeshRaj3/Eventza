<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class notification
 * Create class for notification handling
*/
class notification extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* Load :: Models */
         $this->lang->load('auth');
        $this->lang->load('API/notification');
        $this->lang->load('API/party');
        $this->load->model('v1/notification_model');
        $this->form_validation->set_error_delimiters(' | ', '');
    }

    public function get_all_notification_post() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }


             $previous_week = strtotime("-1 week +1 day");
             $start_week = strtotime("last sunday midnight",$previous_week);
             $end_week = strtotime("next saturday",$start_week);
             $start_week = date("Y-m-d",$start_week);

             $type = $this->input->post('type');
             $where=array('user_id'=>$authorized_user['account']->id,'is_read'=>'1','DATE(notification_created_on) >='=>$start_week,'DATE(notification_created_on) <='=>date('Y-m-d'));
            //print_r($where); die;
            $res = $this->notification_model->get_notifications($where);
            if ($res) {
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') =>$this->lang->line('notification_found_successful'),
                    $this->config->item('rest_data_field_name')     => $res
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') =>$this->lang->line('notification_found_failed')
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
     
    }


    public function get_single_notification_post() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }

            $type = $this->input->post('type');
            $where=array('user_id'=>$authorized_user['account']->id,'is_read'=>'0');
            //print_r($where); die;
            $res = $this->notification_model->get_single_notifications($where);
            if ($res) {
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') =>$this->lang->line('notification_found_successful'),
                    $this->config->item('rest_data_field_name')     => $res
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') =>$this->lang->line('notification_found_failed')
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
     
    }


    public function single_notification_read_status_update_post() { 
    /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);

        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        $notification_id = $this->input->post('notification_id');
        $data = $this->notification_model->notification_read_status_update(array('user_id'=>$authorized_user['account']->id));
        if($data) {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('notification_read_status_update_success')
               
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('notification_read_status_update_failed')
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }

  
    }
}