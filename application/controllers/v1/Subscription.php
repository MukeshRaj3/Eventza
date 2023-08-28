<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Subscription extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* LOAD :: Language */
        $this->lang->load('API/subscription');
        /* LOAD :: Model */
        $this->load->model('v1/subscription_model');
        $this->load->model('v1/restaurant_model');
        //this->load->model('opayo_model');
        /* LOAD :: Form Validation */
        $this->form_validation->set_error_delimiters(' | ', '');
    }

  public function subscription_plan_post() {
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
            $where = [
                'status'               =>'1'
            ];
              
            $res = $this->general_model->getAll('view_subscriptions',$where);
             if(!empty($res))
             {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  => 'Data Found',
                $this->config->item('rest_data_field_name')     => $res

                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => 'Data Not Found'
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
          
    }

    public function user_subscriptions_purchase_post() {
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
       //print_r($authorized_user['account']); die;
        $this->form_validation->set_rules('subscription_id', '', 'required', array('required' => '%s'));
        if ($this->form_validation->run() == true) {
            $res = $this->general_model->getOne('view_subscriptions',array('id'=>$this->input->post('subscription_id')));
            if(!empty($res))
            {
                $res_ab = $this->general_model->getOne('user_subscriptions_purchase',array('subscription_id'=>$this->input->post('subscription_id'),'user_id'=>$authorized_user['account']->id,'plan_end_date >'=>date('Y-m-d H:i:s'),'plan_active_status'=>'1','payment_status'=>'1'));
                //echo $this->db->last_query(); die;
              if(!empty($res_ab))
                {
                     $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => 'This subscription already purchaseed'
                        ], REST_Controller::HTTP_OK);
                }
                $plan_start_date=date('Y-m-d H:i:s');
                $plan_end_date=date('Y-m-d H:i:s', strtotime($plan_start_date . ' +'.$res->day.' day'));
            $data = [
                'user_id'             => $authorized_user['account']->id,
                'subscription_id'     => $this->input->post('subscription_id'),
                'name'                => $res->name,
                'description'         => $res->description,
                'day'                 => $res->day,
                'amount'              => $res->amount,
                'plan_start_date'     => $plan_start_date,
                'plan_end_date'       => $plan_end_date,
                'plan_active_status' =>'1'
            ];
            $insert_id = $this->subscription_model->insert($data);
            if($insert_id) {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('insert_succes_message'),
                    'subscription_purchase_id'=>$insert_id
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('insert_failed_message')
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
          
          }else{
              $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => 'Invailed subscription id'
                        ], REST_Controller::HTTP_OK);
          }
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

        public function user_subscription_plan_status_update_post() {
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
       //print_r($authorized_user['account']); die;
        $this->form_validation->set_rules('subscription_purchase_id', '', 'required', array('required' => '%s'));
        $this->form_validation->set_rules('payment_status', '', 'required', array('required' => '%s'));

        if ($this->form_validation->run() == true) {
            $res = $this->general_model->getOne('user_subscriptions_purchase',array('id'=>$this->input->post('subscription_purchase_id')));
            if(!empty($res))
            {
            $data = [
                'payment_status'     => $this->input->post('payment_status'),
                'payment_response'    => $this->input->post('payment_response'),
                'payment_id'          => $this->input->post('payment_id')
            ];
            $update = $this->general_model->update('user_subscriptions_purchase',array('id'=>$this->input->post('subscription_purchase_id'),'user_id'=>$authorized_user['account']->id),$data);
            if($update) {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  =>'Your transaction successfully.'
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => 'Your transaction failed'
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
          
          }else{
              $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => 'Invailed subscription purchase id'
                        ], REST_Controller::HTTP_OK);
          }
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
}
