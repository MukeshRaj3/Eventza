<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class Home
 * Create class for app home screen handling
*/
class Chat extends REST_Controller {

    public function __construct() {
        parent::__construct();

        /* Load :: Helper */
        $this->lang->load('API/chat');
        /* Load :: Models */
        $this->load->model('v1/chat_model');

        $this->form_validation->set_error_delimiters(' | ', '');
    }

   
    public function add_chat_post() {
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
        $this->form_validation->set_rules('individual_user_id', '', 'required', array('required' => '%s'));
        //$this->form_validation->set_rules('message', '', 'required', array('required' => '%s'));
        if ($this->form_validation->run() == true) {
            $data = [
                'user_id'               => $authorized_user['account']->id,
                'user_id_from'       => $this->input->post('individual_user_id'),
                //'message'                 => $this->input->post('message'),
                //'message_type'           => $this->input->post('message_type')
                //'created_at'            => date('Y-m-d H:i:s')
            ];
            $res = $this->general_model->getOne('user_chat_list',$data);
             if(!empty($res))
             {
                 
                 $this->general_model->getOne('user_chat_list',$data);
                 $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => 'User alreay added'
                        ], REST_Controller::HTTP_OK);

             }else{
            
            
           // print_r($data); die;

            /*if (empty($_FILES['cover_photo']['name'])) {
                $this->form_validation->set_rules('cover_photo', 'cover_photo', 'required', array('required' => '%s'));
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => 'Empty request parameter(s) [ cover_photo ]',
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $file_name = 'chat_' . time() . rand(100, 999);
                $config = [
                    'upload_path' => './upload/chat/',
                    'file_name' => $file_name,
                    'allowed_types' => 'png|jpg|jpeg',
                    'max_size' => 50480,
                    'max_width' => 20480,
                    'max_height' => 20480,
                    'file_ext_tolower' => TRUE,
                    'remove_spaces' => TRUE,
                ];
                $this->load->library('upload/', $config);
                if ($this->upload->do_upload('cover_photo')) {
                    $uploadData = $this->upload->data();
                    $data['cover_photo'] = 'upload/chat/' . $uploadData['file_name'];
                } else {
                    $this->response([
                        $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                        $this->config->item('rest_message_field_name')  => $this->upload->display_errors()
                            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            }*/
            
             $data2 = [
                 'user_id'               => $authorized_user['account']->id,
                 'user_id_from'       => $this->input->post('individual_user_id'),
                 //'message'                 => $this->input->post('message'),
                 //'message_type'           => $this->input->post('message_type')
                 'created_at'            => date('Y-m-d H:i:s')
             ];
            $insert_data = $this->chat_model->insert_chat($data2);
            if($insert_data) {

                 $whare = [
                 'user_id_from'               => $authorized_user['account']->id,
                 'user_id'       => $this->input->post('individual_user_id')];
                $res2 = $this->general_model->getOne('user_chat_list',$whare);
             if(empty($res2))
             {
                
               $data3 = [
                 'user_id_from'               => $authorized_user['account']->id,
                 'user_id'       => $this->input->post('individual_user_id'),
                 //'message'                 => $this->input->post('message'),
                 //'message_type'           => $this->input->post('message_type')
                 'created_at'            => date('Y-m-d H:i:s')
             ];
                  $this->chat_model->insert_chat($data3);
             }

                 
                 /*-------------Create Notification------------*/
                  /*$noti_arr=array('notification_title'=>'chat Post','notification_message'=>'Congratulations! your chat is posted and its under review.','notification_type'=>2,'notification_type_name'=>'chat Post','user_id'=>$authorized_user['account']->id);
                  $this->general_model->insert('notifications',$noti_arr);
                /*==================push notification send=================*/
                 /* $message = 'Congratulations! your chat is posted and its under review.';
                  $data=array('body'=>$message,'title'=>'chat Post');
                  push_notification_android($authorized_user['account']->device_token,1,$data);
                /*-----------------------------------------------------*/
                /*--------------------------------------------*/
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('insert_success_chat_message')
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('insert_failed_chat_message')
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
          }
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
 
    public function get_chat_data_post(){
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
        $this->form_validation->set_rules('individual_user_id', '', 'required', array('required' => '%s'));
        if($this->form_validation->run() == true) 
        {
        
        $user_id=$authorized_user['account']->id;

        $res_send_chat_data = $this->chat_model->get_send_chat_data(array('sender_id'=>$user_id,'receiver_id'=>$this->input->post('individual_user_id')));
        
        $res_rec_chat_data = $this->chat_model->get_rec_chat_data(array('sender_id'=>$user_id,'receiver_id'=>$this->input->post('individual_user_id')));
        if(!empty($res_send_chat_data))
        {
         $res_chat_data=$res_send_chat_data;

        }else if(!empty($res_rec_chat_data))
        {
         $res_chat_data=$res_rec_chat_data;
          
        }else if(!empty($res_send_chat_data) && !empty($res_rec_chat_data))
        {
         $res_chat_data=array_merge($res_send_chat_data,$res_rec_chat_data);
        }

        if(!empty($res_chat_data))
            {
                  $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') =>'chat data found',
                    $this->config->item('rest_data_field_name')     => $res_chat_data
                   ], REST_Controller::HTTP_OK); 
            }else{
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => 'chat data not found'
                ], REST_Controller::HTTP_OK); 
            }

        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }    
    } 

     public function get_chat_user_list_data_post(){
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
        
        
        $user_id=$authorized_user['account']->id;

        $res_send_chat_data = $this->chat_model->get_send_chat_data(array('sender_id'=>$user_id));
        
        //$res_rec_chat_data = $this->chat_model->get_rec_chat_data(array('sender_id'=>$user_id,'receiver_id'=>$this->input->post('individual_user_id')));
        if(!empty($res_send_chat_data))
        {
         $res_chat_data=$res_send_chat_data;
        }else{
         $res_chat_data=array();
        }
        if(!empty($res_chat_data))
            {
                foreach ($res_chat_data as $key => $value) {
                $where = [
                'user_id_from'               => $authorized_user['account']->id,
                'user_id'       => $value->id,
               ];
               $data = [
                 'status'=>'0'
               ];
              $res = $this->general_model->update('user_chat_list',$where,$data);
              }
                  $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') =>'user data found',
                    $this->config->item('rest_data_field_name')     => $res_chat_data
                   ], REST_Controller::HTTP_OK); 
            }else{
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => 'user data not found'
                ], REST_Controller::HTTP_OK); 
            }

           
    } 

    public function delete_chat_post(){
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
        //$this->form_validation->set_rules('like_status','','required');
        $this->form_validation->set_rules('individual_user_id','','required');
        $this->form_validation->set_rules('message_id','','required');

        if ($this->form_validation->run() == true) {
            
            $res= $this->general_model->delete('chat_message', ['receiver_id' =>$this->input->post('individual_user_id'),'sender_id' => $authorized_user['account']->id,'id'=>$this->input->post('message_id')]);
             if(!empty($res))
             {
                    $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name') =>'chat deleted successfully',
                ], REST_Controller::HTTP_OK); 
            }else{
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => 'chat not deleted'
                ], REST_Controller::HTTP_OK); 
            }
         
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function update_chat_message_post() {
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
        $this->form_validation->set_rules('individual_user_id', '', 'required', array('required' => '%s'));
        $this->form_validation->set_rules('message', '', 'required', array('required' => '%s'));

        //$this->form_validation->set_rules('message', '', 'required', array('required' => '%s'));
        if ($this->form_validation->run() == true) {
            $where = [
                'user_id'               => $authorized_user['account']->id,
                'user_id_from'       => $this->input->post('individual_user_id'),
            ];
              $data = [
                 'message'       => $this->input->post('message'),
                 'update_date'            => date('Y-m-d H:i:s'),
                 'status'=>1
             ];
            $res = $this->general_model->update('user_chat_list',$where,$data);
             if(!empty($res))
             {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  => 'Last message update successfully'
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('insert_failed_chat_message')
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
