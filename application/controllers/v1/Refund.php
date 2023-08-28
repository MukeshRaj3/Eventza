<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class Refund
 * Create class for refund handling
*/
class Refund extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* Load :: Models */
        $this->load->model('v1/refund_model');

        $this->form_validation->set_error_delimiters(' | ', '');
    }

    public function order_refund_post() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }

        $this->form_validation->set_rules('order_id', '', 'required', ['required' => '%s']);
        $this->form_validation->set_rules('message', '', 'required', ['required' => '%s']);
        if ($this->form_validation->run() == true) {
            $order_id = $this->input->post('order_id');
            $data = [
                'refund_message' => $this->input->post('message'),
                'is_refund_request' => 1,
                'refund_request_time' => time(),
            ];

            $create = $this->refund_model->create_refund($order_id, $data);
            if ($create) {
                $this->response([
                    $this->config->item('rest_status_field_name') => 1,
                    $this->config->item('rest_message_field_name') => 'Refund request has been sent successfully'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name') => 0,
                    $this->config->item('rest_message_field_name') => 'Unable to sent refund request'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        } else {
            $this->response([
                $this->config->item('rest_status_field_name') => 0,
                $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
}