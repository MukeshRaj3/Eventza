<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class notification
 * Create class for notification handling
*/
class Settings extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* Load :: Models */
         $this->lang->load('auth');
        $this->lang->load('API/settings');
        $this->load->model('v1/settings_model');
        $this->form_validation->set_error_delimiters(' | ', '');
    }

    public function get_settings_post() {
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
            $where=array('user_id'=>$authorized_user['account']->id);
            //print_r($where); die;
            $res = $this->settings_model->get_settings($where);
            if ($res) {
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') =>$this->lang->line('settings_found_successful'),
                    $this->config->item('rest_data_field_name')     => $res
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') =>$this->lang->line('settings_found_failed')
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
     
    }

    public function update_settings_post() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);

        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        /* End Check Authentications */
        $this->form_validation->set_rules('like', '', 'required', array('required' => '%s'));
        $this->form_validation->set_rules('party_subscribed', '', 'required', array('required' => '%s'));
        
        if ($this->form_validation->run() == true) {
       
            $organization_id = $this->input->post('organization_id');
            $data = [
                'user_id'               => $authorized_user['account']->id,
                'like'                 => $this->input->post('like'),
            'party_subscribed'           => $this->input->post('party_subscribed')
            ];
            
            $update_data = $this->settings_model->update_settings($data);
            if($update_data) {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('update_success_settings_message')
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('update_failed_settings_message')
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
}