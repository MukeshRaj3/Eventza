<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class Promocodes
 * Create class for Promo-codes handling
*/
class Promocodes extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* Load :: Helper */
        $this->lang->load('auth');
        $this->lang->load('API/promocode');
        /* Load :: Models */
        $this->load->model('v1/promocodes_model');

        $this->form_validation->set_error_delimiters(' | ', '');
    }

    /**
     * Available Promocode list
     * Method (GET)
     */
    public function promocode_list_post() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_user_authentication_if_login($headers);
        
        if ($authorized_user['status'] != 1) {
            $user_id = 0;
        }else{
            $user_id = $authorized_user['account']->id;
        }
        /* End Check Authentications */

        $latitude = !empty($this->input->post('latitude')) ? $this->input->post('latitude') : "";
        $longitude = !empty($this->input->post('longitude')) ? $this->input->post('longitude') : "";

        if (!empty($latitude) && !empty($longitude)) {
            $promocodes = $this->promocodes_model->get_nearby_promo_restaurants($latitude, $longitude, $user_id);
        } else {
            $promocodes = $this->promocodes_model->get_promo_restaurants($user_id);
        }

        if (!empty($promocodes)) {
            
            $this->response([
                $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name') => $this->lang->line('promocode_list_found'),
                $this->config->item('rest_data_field_name')    => $promocodes
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
            $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => $this->lang->line('promocode_list_empty'),
            ], REST_Controller::HTTP_OK);
        }
    }

    /**
     * Available Promocode list
     * Method (GET)
     */
    public function restaurant_promocodes_post() {
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

        $this->form_validation->set_rules('restaurant_id', '', 'required', array('required' => '%s'));

        if ($this->form_validation->run() == true) {
            $user_id        = $authorized_user['account']->id;
            $restaurant_id  = $this->input->post('restaurant_id');

            $promocodes = $this->promocodes_model->restaurant_promocodes($restaurant_id, $user_id);

            if (!empty($promocodes)) {
                if (!empty($promocodes->promocodes)) {
                    $this->response([
                        $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                        $this->config->item('rest_message_field_name')  => $this->lang->line('promocode_list_found'),
                        $this->config->item('rest_data_field_name')     => $promocodes
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_zero'),
                        $this->config->item('rest_message_field_name') => $this->lang->line('promocode_list_empty'),
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('promocode_list_empty'),
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    /**
     * Validate Promocode
     * Method (POST)
     */
    public function validate_promocode_post() {
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

        $this->form_validation->set_rules('promocode', '', 'required');
        $this->form_validation->set_rules('restaurant_id', '', 'required');

        if ($this->form_validation->run() == true) {
            $promocode = $this->input->post('promocode');
            $restaurant_id = $this->input->post('restaurant_id');

            $response = $this->promocodes_model->check_promocode($promocode, $restaurant_id, $authorized_user['account']->id);

            if (!empty($response)) {
                if ($response->number_of_time_usage > 0) {
                    $get_usage_count = $this->promocodes_model->get_promocode_usage_count($response->number_of_time_usage, $promocode, $response->promocode_id, $authorized_user['account']->id);
                    if ($get_usage_count['status'] == 1) {
                        $user_usage_count = $this->promocodes_model->check_user_usage($response->promocode_id,$authorized_user['account']->id);
                        if($user_usage_count >= $response->per_user){
                            $this->response([
                            $this->config->item('rest_status_field_name')  => $this->config->item('rest_status_code_zero'),
                            $this->config->item('rest_message_field_name') => $this->lang->line('promocode_already_used_by_user'),
                                    ], REST_Controller::HTTP_OK);
                        } else {
                            $promo_usage_id = $this->promocodes_model->apply_promocode($promocode,$response->promocode_id,$authorized_user['account']->id, $restaurant_id);

                            $this->response([
                            $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                            $this->config->item('rest_message_field_name')  => $this->lang->line('promocode_applied_success'),
                            $this->config->item('rest_data_field_name')     => [
                                'promo_usage_id' => $promo_usage_id,
                                'promocode' => $promocode,
                                'promocode_id' => (int) $response->promocode_id,
                                'promocode_type' => $response->promocode_type,
                                'discount' => $response->discount,
                            ],
                            ], REST_Controller::HTTP_OK);
                        }
                    } else {
                        $this->response([
                            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                            $this->config->item('rest_message_field_name') => $this->lang->line('promocode_limit_exceed'),
                        ], REST_Controller::HTTP_OK);
                    }
                } else {
                    $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('promocode_invalid'),
                            ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name') => $this->lang->line('promocode_invalid'),
                        ], REST_Controller::HTTP_OK);
            }
        } else {
            // Set the response and exit
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ promocode | restaurant_id ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
}