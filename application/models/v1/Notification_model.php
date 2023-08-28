<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'notifications' => 'notifications'
        ];
    }

    public function get_notifications($where) {
         if(!empty($where)){
            $query = $this->db->select('*')
            ->where($where)
            ->order_by("notification_id", "desc")
            ->get($this->tables['notifications']);                              
        } else {
            $query = $this->db->select('*')
            ->order_by("notification_id", "desc")
            ->get($this->tables['notifications']);                              
        }   
         // print_r($where); die;
        return $query->result();
    }

    public function get_single_notifications($where) {
         if(!empty($where)){
            $query = $this->db->select('*')
            ->where($where)
            ->order_by("notification_id", "desc")
            ->limit(1)
            ->get($this->tables['notifications']);                              
        } else {
            $query = $this->db->select('*')
            ->order_by("notification_id", "desc")
            ->get($this->tables['notifications']);                              
        }   
         // print_r($where); die;
        return $query->result();
    }

public function notification_read_status_update($data) {
        return $this->db->update($this->tables['notifications'],array('is_read'=>'1'), ['user_id'=>$data['user_id']]);
    }
    
}