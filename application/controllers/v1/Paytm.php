<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
ob_start();
/**
 * Class Refund
 * Create class for refund handling
*/
class Paytm extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* Load :: Models */
        $this->load->model('v1/paytm_model');
         $this->lang->load('API/order_history');
        $this->form_validation->set_error_delimiters(' | ', '');
       

    }

    public function paytm_payment_get() {
        /* Check Authentications */
    
            $order_id=$this->input->get('order_id');
            $user_id=$this->input->get('user_id');
        $res = $this->paytm_model->get_order($order_id,$user_id); 
        //print_r($res); die;
       if(!empty($res))
       {
            $txn_id=$res[0]['id'];
            $PaymentId=$res[0]['order_number'];
            $checkSum = "";
            $paramList = array();
            $ORDER_ID = $res[0]['order_number'];
            $CUST_ID ='CUST00'.$user_id; //$_POST["deliver_address"];
            $INDUSTRY_TYPE_ID ='Retail'; //$_POST["INDUSTRY_TYPE_ID"];
            $CHANNEL_ID = 'WEB';//$_POST["CHANNEL_ID"];
            $TXN_AMOUNT =$res[0]['amount'];

            // Create an array having all required parameters for creating checksum.
            $paramList["MID"] = PAYTM_MERCHANT_MID;
            $paramList["ORDER_ID"] =$ORDER_ID;
            $paramList["CUST_ID"] = $CUST_ID;
            $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
            $paramList["CHANNEL_ID"] = $CHANNEL_ID;
            $paramList["TXN_AMOUNT"] =1; //$TXN_AMOUNT;
            $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
            $paramList["CALLBACK_URL"] =base_url('v1/paytm/pg_response'); //
            $checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
               $data["checkSum"]=$checkSum;
               $data["txn_id"]=$txn_id;
               $data["PaymentId"]=$PaymentId;
               $data['list']=$paramList;
               $this->load->view('paytm/pg_redirect',$data);
          }else{
               $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('order_data_not_found'),
        
            ], REST_Controller::HTTP_OK);
           }
          
    }



public function pg_response_post()
{
$PAYTM_MERCHANT_KEY=PAYTM_MERCHANT_KEY; 
$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";
$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; 
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
    if($_POST["STATUS"] == "TXN_SUCCESS"){
          //unset($_POST['CHECKSUMHASH']);
          $response = json_encode($_POST);
          $this->paytm_model->update_data(array('payment_status'=>'1','transaction_data'=>$response),$_POST["ORDERID"]);

          $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('transaction_success'),
              
            ], REST_Controller::HTTP_OK);
                   
                
    }else{
         $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('transaction_fail'),
        
            ], REST_Controller::HTTP_OK);
       
    }

   
}else{
      $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('checksum_mismatched'),
        
            ], REST_Controller::HTTP_OK);   
 }
}

}