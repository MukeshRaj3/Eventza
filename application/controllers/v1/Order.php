<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
ob_start();
/**
 * Class Refund
 * Create class for refund handling
*/
class Order extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* Load :: Models */
        $this->load->model('v1/order_model');
         $this->lang->load('API/order_history');
        $this->form_validation->set_error_delimiters(' | ', '');
       

    }

    public function create_order_post() {
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

        $this->form_validation->set_rules('party_id', '', 'required', array('required' => '%s'));
        $this->form_validation->set_rules('amount', '', 'required', array('required' => '%s'));
        $this->form_validation->set_rules('papular_status', '', 'required', array('required' => '%s'));

        if ($this->form_validation->run() == true) {
             $order_num=$this->order_model->get_order_data();
             
            $data = [
                'user_id'              => $authorized_user['account']->id,
                'order_number'        =>$order_num,
                'party_id'             => $this->input->post('party_id'),
                'organization_id'      => @$this->input->post('organization_id'),
                'user_name'            => @$authorized_user['account']->first_name,
                'email_id'             => @$authorized_user['account']->email,
                'phone_number'         => @$authorized_user['account']->phone,
                 'amount'    => $this->input->post('amount'),
                'created_date'            => date('Y-m-d H:i:s')
            ];
           
               // print_r($data); die;
            $insert_data =1; // $this->order_model->insert($data); die;
            if($insert_data) {
                  $this->order_model->update_party(array('papular_status'=>$this->input->post('papular_status'),'pr_start_date'=>date('Y-m-d',strtotime($this->input->post('pr_start_date'))),'pr_end_date'=>date('Y-m-d',strtotime($this->input->post('pr_end_date')))),$this->input->post('party_id'));    

                $res_pr = $this->order_model->get_party_data($this->input->post('party_id')); 
                //print_r($res_pr); die;
                 if($res_pr==1)
                 {
                  $this->order_model->update_party(array('papular_status'=>1),$this->input->post('party_id'));    

                    /*-------------Create Notification------------*/
                  $noti_arr=array('notification_title'=>'Popular Post','notification_message'=>'Congratulations ! Your event post has been successfully boosted now.','notification_type'=>17,'notification_type_name'=>'Popular Post','user_id'=>$authorized_user['account']->id);
                  $this->general_model->insert('notifications',$noti_arr);
                   /*==================push notification send=================*/
                  $message = 'Congratulations ! Your event post has been successfully boosted now.';
                  $data_mess=array('body'=>$message,'title'=>'Popular Post');
                  push_notification_android($authorized_user['account']->device_token,17,$data_mess);
                /*-----------------------------------------------------*/

                /*-------------Create Notification------------*/
                 /* $noti_arr=array('notification_title'=>'Popular Post','notification_message'=>'You have gained extra visibility for your event.','notification_type'=>18,'notification_type_name'=>'Popular Post','user_id'=>$authorized_user['account']->id);
                  $this->general_model->insert('notifications',$noti_arr);
                  
                  $message = 'You have gained extra visibility for your event.';
                  $data_mess=array('body'=>$message,'title'=>'Popular Post');
                  push_notification_android($authorized_user['account']->device_token,18,$data_mess);*/
                /*-----------------------------------------------------*/
                
                 }else{
                         /*-------------Create Notification------------*/
                  $noti_arr1=array('notification_title'=>'Popular Post','notification_message'=>'Your party has been successfully popular and it will be live on '.$this->input->post('pr_start_date').' and will end on '.$this->input->post('pr_end_date'),'notification_type'=>17,'notification_type_name'=>'Popular Post','user_id'=>$authorized_user['account']->id);
                  $this->general_model->insert('notifications',$noti_arr1);
                   /*==================push notification send=================*/
                  $message1 = 'Your party has been successfully popular and it will be live on '.$this->input->post('pr_start_date').' and will end on '.$this->input->post('pr_end_date');
                  //print_r($message); die;
                  $data_mess1=array('body'=>$message1,'title'=>'Popular Post');
                  push_notification_android($authorized_user['account']->device_token,17,$data_mess1);
                /*-----------------------------------------------------*/

                /*-------------Create Notification------------*/
                 /* $noti_arr=array('notification_title'=>'Popular Post','notification_message'=>'You have gained extra visibility for your event.','notification_type'=>18,'notification_type_name'=>'Popular Post','user_id'=>$authorized_user['account']->id);
                  $this->general_model->insert('notifications',$noti_arr);
                   
                  $message = 'You have gained extra visibility for your event.';
                  $data_mess=array('body'=>$message,'title'=>'Popular Post');
                  push_notification_android($authorized_user['account']->device_token,18,$data_mess);*/
                /*-----------------------------------------------------*/
                 }
                
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('order_create_success'),
                       'order_id'    => $insert_data
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('order_create_fail')
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
         
    }


public function update_ragular_papular_status_post() {
        /* Check Authentications */
            $res = $this->order_model->get_party_data();
            if($res) {
                //print_r($res); die;
                  
                   foreach ($res as $key => $value) {
                      if($value->pr_start_date==date('Y-m-d'))
                      {
                        $this->order_model->update_party(array('papular_status'=>1),$value->id); 
                        
                      }
                     if(date('Y-m-d') > $value->pr_end_date)
                      {
                        $this->order_model->update_party(array('papular_status'=>2),$value->id);  
                      }
                    }
                  }
        }


         
    


}