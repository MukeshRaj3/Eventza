<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class Comman
 * Create class for Comman handling
*/
class Comman extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* Load Langauge File */
        $this->lang->load('API/comman');
        /* Load Model File */
        $this->load->model('v1/comman_model');
        
        $this->form_validation->set_error_delimiters(' | ', '');
    }

    /**
     * App Introduction API
     * Method (GET)
     */
    public function introduction_get() {

        $introductions = $this->comman_model->get_introduction();

        if ($introductions) {
            foreach ($introductions as $key => $introduction) {
                $introduction->introduction_file = !empty($introduction->introduction_file) ? base_url($introduction->introduction_file) : "";
            }

            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('app_introduction_success'),
                $this->config->item('rest_data_field_name')     => $introductions
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => $this->lang->line('app_introduction_empty')
                ], REST_Controller::HTTP_OK);
        }
    }

    public function send_feedback_post()
    {
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

        $this->form_validation->set_rules('message', '', 'required', array('required' => '%s'));

        if ($this->form_validation->run() == true) {
            $insert_data = [
                'user_id' => $authorized_user['account']->id,
                'contact_person_name' => $authorized_user['account']->first_name,
                'message' => $this->input->post('message'),
                'user_type' => 1,
                'contactus_on' => now()
            ];

            $is_insert = $this->general_model->insert('contact_us', $insert_data);

            if ($is_insert) {
                $this->response([
                    $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('feedback_send_success'),
                ], REST_Controller::HTTP_OK);
            }else{
                $this->response([
                    $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('feedback_send_failed'),
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function contact_details_get()
    {
        $data = [
            'contact_number' => '+44 9000000000',
            'contact_email'  => 'helpdesk@ukeats.co.uk' 
        ];

        $this->response([
            $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_one'),
            $this->config->item('rest_message_field_name') => $this->lang->line('contact_detail_success'),
            $this->config->item('rest_data_field_name')    => $data
        ], REST_Controller::HTTP_OK);
    }

    public function send_query_to_support_team_post()
    {
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

        $this->form_validation->set_rules('message', '', 'required', array('required' => '%s'));

        if ($this->form_validation->run() == true) {
            $insert_data = [
                'user_id' => $authorized_user['account']->id,
                'contact_person_name' => $authorized_user['account']->first_name,
                'message' => $this->input->post('message'),
                'user_type' => 1,
                'contactus_on' => now()
            ];

            $is_insert = $this->general_model->insert('contact_us', $insert_data);

            if ($is_insert) {
                $this->response([
                    $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('send_query_to_support_success'),
                ], REST_Controller::HTTP_OK);
            }else{
                $this->response([
                    $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('send_query_to_support_failed'),
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function app_version_get()
    {
        $os_type = $this->input->get('os_type');

        if (!empty($os_type)) {
            $app_version = $this->general_model->getOne('app_version', ['os_type' => $os_type]);

            if (!empty($app_version)) {
                $this->response([
                    $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('app_version_detail_found'),
                    $this->config->item('rest_data_field_name')    => $app_version
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('app_version_detail_not_found')
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name') => 'Please provide OS Type.'
            ], REST_Controller::HTTP_OK);
        }
    }
}