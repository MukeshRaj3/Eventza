<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class settings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'settings' => 'settings'
        ];
    }

    public function get_settings($where) {
         if(!empty($where)){
            $query = $this->db->select('*')
            ->where($where)
            ->get($this->tables['settings']);                              
        } else {
            $query = $this->db->select('*')
            ->get($this->tables['settings']);                              
        }   

        return $query->result();
    }

    public function update_settings($data) 
    {
            $user_id=$data['user_id'];
            $query = $this->db->select('*')
            ->where(array('user_id' =>$user_id))
            ->get($this->tables['settings']);                              
          if($query->num_rows() > 0) {
                        unset($data['user_id']);
                 return $this->db->update($this->tables['settings'],$data,['user_id' =>$user_id]);
                }else{
                 return $this->db->insert($this->tables['settings'],$data);
                }
    }
}