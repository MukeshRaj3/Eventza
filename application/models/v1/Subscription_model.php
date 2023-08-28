<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Subscription_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tables = [
            'chat_message'         => 'chat_message',
            'users'         => 'users',
            'user_chat_list'=>'user_chat_list',
            'organization'=>'organization',
            'user_chat_list'=>'user_chat_list',
            'individual_block_user'=>'individual_block_user',
            'user_subscriptions_purchase'=>'user_subscriptions_purchase'
            
        ];
    }

    public function insert($data)
    {
      $this->db->insert($this->tables['user_subscriptions_purchase'], $data);
      return $this->db->insert_id();
    }

public function get_send_chat_data($where)
{
    $this->db->select($this->tables['users'].'.id,username,online_status,'.$this->tables['user_chat_list'].'.created_at,message,update_date,user_id_from,'.$this->tables['organization'].'.profile_pic,display_name,display_status');
        $this->db->join($this->tables['users'], $this->tables['user_chat_list'].'.user_id_from='.$this->tables['users'].'.id','left');
        $this->db->join($this->tables['organization'], $this->tables['user_chat_list'].'.user_id_from='.$this->tables['organization'].'.user_id','left');

        //$this->db->where([$this->tables['chat_message'].'.receiver_id' =>$where['receiver_id']]);
        $this->db->where([$this->tables['user_chat_list'].'.user_id' =>$where['sender_id']]);
        $this->db->order_by($this->tables['user_chat_list'].'.update_date','DESC');
        $query = $this->db->get($this->tables['user_chat_list']);
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0) {
            $res= $query->result();
            foreach ($res as $key => $value) {
                  $res[$key]->type='send';
                  $b_res=$this->db->get_where($this->tables['individual_block_user'], ['user_id' =>$where['sender_id'],'individual_id' => $value->user_id_from])->row();
                  if(!empty($b_res))
                  {
                    $res[$key]->block_status='Block';
                   }else{
                    $res[$key]->block_status='Unblock';
                     
                   }
            }
            return $res;
        }else{
            return false;
        }
}

public function get_rec_chat_data($where)
{
    $this->db->select($this->tables['users'].'.id,username,profile_picture,'.$this->tables['chat_message'].'.message,message_type,is_read,created_date');
        $this->db->join($this->tables['users'], $this->tables['chat_message'].'.receiver_id='.$this->tables['users'].'.id','left');
        $this->db->where([$this->tables['chat_message'].'.receiver_id' =>$where['sender_id']]);
        $this->db->where([$this->tables['chat_message'].'.sender_id' =>$where['receiver_id']]);
        $this->db->order_by($this->tables['chat_message'].'.created_date','ASC');
        $query = $this->db->get($this->tables['chat_message']);
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0) {
            $res= $query->result();
            foreach ($res as $key => $value) {
                  $res[$key]->type='receive';
            }
            return $res;
        }else{
            return false;
        }
}

}
