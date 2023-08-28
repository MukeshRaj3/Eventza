<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function party_end_send_notification()
	{

     $res=$this->general_model->getAll('party',array('active'=>1,'approval_status'=>1));
   
      if (!empty($res)) {
          foreach ($res as $key => $value) {
                //echo strtotime(date('Y-m-d H:i')); die;
                 //$start_date_time=date('Y-m-d',$value->start_date).' '.$value->start_time; 
                 $end_date_time=date('Y-m-d',$value->end_date).' '.$value->end_time;
              
              
                 if($end_date_time==date('Y-m-d H:i'))
                 {
                  /*-------------Create Notification------------*/
                  $noti_arr=array('notification_title'=>'Party End','notification_message'=>'Oop! Your plan has expired.','notification_type'=>5,'notification_type_name'=>'Party End','user_id'=>$value->user_id);
                  $this->general_model->insert('notifications',$noti_arr);
                /*--------------------------------------------*/ 
                 }

               }
           
          }
           
        }


 public function party_plan_expiring_send_notification()
	{

     $res=$this->general_model->getAll('party',array('active'=>1,'approval_status'=>1));
   
      if (!empty($res)) {
          foreach ($res as $key => $value) {
                //echo strtotime(date('Y-m-d H:i')); die;
                 //$start_date_time=date('Y-m-d',$value->start_date).' '.$value->start_time; 
                 $end_date_time=date('Y-m-d',$value->end_date).' '.$value->end_time;
              
                 /*---------------5 Day Before ---------------------*/
                   $start_date_time= date("Y-m-d H:i", strtotime("-5 days", strtotime($end_date_time))); 
                  if($start_date_time <= date('Y-m-d H:i') && $end_date_time >=date('Y-m-d H:i'))
                 {
                  /*-------------Create Notification------------*/
                  $noti_arr=array('notification_title'=>'Plan Expiring','notification_message'=>'Your plan for a popular party is expiring soon! renew now.','notification_type'=>8,'notification_type_name'=>'Plan Expiring','user_id'=>$value->user_id);
                  $this->general_model->insert('notifications',$noti_arr);
                /*--------------------------------------------*/ 
                 }


               }
           
          }
           
        }

public function update_offline_user_status()
      {

     $res=$this->general_model->getAll('users',array('active'=>1,'type'=>'Individual'));
      if (!empty($res)) {
          foreach ($res as $key => $value) {
                  if($value->online_time_expiry  < date('Y-m-d H:i:s'))
                 {
                  $where=array('id'=>$value->id);
                  $this->general_model->update('users',$where,array('online_status'=>'off'));
                 }
               }
          }
           
        }

public function update_subscription_plan_expairy_status()
      {

     $res=$this->general_model->getAll('user_subscriptions_purchase
',array('payment_status'=>'1','plan_expired_status'=>'No','plan_end_date <'=>date('Y-m-d H:i:s')));
      if (!empty($res)) {
          foreach ($res as $key => $value) {
                  $where=array('payment_status'=>'1','plan_expired_status'=>'No','plan_end_date <'=>date('Y-m-d H:i:s'));
                  $this->general_model->update('user_subscriptions_purchase',$where,array('plan_expired_status'=>'Yes'));
               }
          }
           
        }  
 
}
