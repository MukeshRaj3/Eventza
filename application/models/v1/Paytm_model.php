<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Paytm_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'orders' => 'orders'
        ];
    }

    public function create_refund($order_id, $data) {
        return $this->db->update($this->tables['orders'], $data, ['order_id' => $order_id]);
    }

     public function get_order($order_id) {
        $res= $this->db->get_where($this->tables['orders'], ['id' => $order_id])->result_array();
        return $res;
     }   

      public function update_data($data, $order_number) {
        return $this->db->update($this->tables['orders'], $data, ['order_number' => $order_number]);
    }	
}