<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Refund_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'orders' => 'orders'
        ];
    }

    public function create_refund($order_id, $data) {
        return $this->db->update($this->tables['orders'], $data, ['order_id' => $order_id]);
    }
}