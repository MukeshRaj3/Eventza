<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'users' => 'users',
            'user_points' => 'user_points',
            'orders' => 'orders',
            'point_system' => 'point_system',
            'user_device_token' => 'user_device_token',
            'notifications' => 'notifications',
            'user_chat' => 'user_chat',
            'individual_block_user'=>'individual_block_user',
            'individual_like'=>'individual_like',
            'individual_user_profile_view'=>'individual_user_profile_view'
        ];
    }

    public function check_user_email_exist($email)
    {
    	$this->db->select($this->tables['users'].'.*');
        $this->db->where($this->tables['users'].'.email', $email);
    	$query = $this->db->get($this->tables['users']);

    	if ($query->num_rows() > 0) {
            return $query->row();
        }

        return FALSE;
    }

    public function check_user_phone_exist($phone)
    {
        $this->db->select($this->tables['users'].'.*');
        $this->db->where($this->tables['users'].'.phone', $phone);
        $query = $this->db->get($this->tables['users']);

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return FALSE;
    }

    public function update_user_data($where, $data)
    {
        return $this->db->update($this->tables['users'], $data, $where);
    }

    public function check_referral_exist($referral_code)
    {
        $this->db->select($this->tables['users'].'.referral_code');
        $this->db->where($this->tables['users'].'.referral_code', $referral_code);
        $query = $this->db->get($this->tables['users']);

        if ($query->num_rows() > 0) {
            return ['status' => 0, 'data' => $query->row()];
        }

        return ['status' => 1, 'data' => ''];
    }

    public function insert_referral_point($data, $user_id)
    {
        $this->db->select($this->tables['point_system'].'.*');
        $this->db->limit(1);
        $query = $this->db->get($this->tables['point_system']);

        if ($query->num_rows() > 0) {
            $point_system = $query->row();
            $is_insert = $this->db->insert($this->tables['user_points'], ['user_id' => $data->id, 'from_user_id' => $user_id, 'point' => $point_system->referral_point, 'is_earn_point' => 1, 'is_active' => 1, 'is_referral_point' => 1, 'created_on' => time()]);

            if ($is_insert) {
                $data->points += $point_system->referral_point;
                $earning_amount = 0;
                if ($data->points >= $point_system->reach_x_point_reword) {
                    $set = floor($data->points / $point_system->reach_x_point_reword);
                    
                    $earning_amount = $set * $point_system->reach_x_point_reword_amount;
                }

                return $this->db->update($this->tables['users'], ['points' => $data->points, 'earning_amount' => $earning_amount], ['id' => $data->id]);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get_earning_points($user_id)
    {
        $this->db->select($this->tables['user_points'].'.*,'.$this->tables['orders'].'.order_unique_id,'.$this->tables['users'].'.first_name');
        $this->db->join($this->tables['orders'], $this->tables['user_points'].'.order_id = '.$this->tables['orders'].'.order_id', 'LEFT');
        $this->db->join($this->tables['users'], $this->tables['user_points'].'.from_user_id = '.$this->tables['users'].'.id', 'LEFT');
        $this->db->where($this->tables['user_points'].'.user_id', $user_id);
        $this->db->where($this->tables['user_points'].'.is_active', 1);
        $query = $this->db->get($this->tables['user_points']);

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

    public function check_referral_code($referral_code)
    {
        $this->db->select($this->tables['users'].'.referral_code');
        $this->db->where($this->tables['users'].'.referral_code', $referral_code);
        $query = $this->db->get($this->tables['users']);

        if ($query->num_rows() > 0) {
            return TRUE;
        }

        return FALSE;
    }

    public function update_user_token($data) {
        $token = $this->db->get_where($this->tables['user_device_token'], ['device_id' => $data['device_id']])->row();
        if (is_null($token)) {
            return $this->db->insert($this->tables['user_device_token'], $data);
        } else {
            return $this->db->update($this->tables['user_device_token'], $data, ['id' => $token->id]);
        }
    }

    public function delete_user_account($user_id) {
        return $this->db->update($this->tables['users'], ['is_deleted' => 1], ['id' => $user_id]);
    }

    public function notification_list($user_id, $offset, $limit) {
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('notification_id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->tables['notifications']);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function insert_user_chat($data) {
        return $this->db->insert($this->tables['user_chat'], $data);
    }
    public function insert_chat_tick($id,$data) {
        $chat_id = $this->db->get_where($this->tables['user_chat'], ['id' => $id])->row();
        if (is_null($chat_id)) {
            return FALSE;
        } else {
            return $this->db->update($this->tables['user_chat'], $data, ['id' => $chat_id->id]);
        }
    }

    public function get_individual_blok_list($user_id)
    {
        $this->db->select($this->tables['individual_block_user'].'.*,'.$this->tables['users'].'.username,profile_picture');
        $this->db->join($this->tables['users'], $this->tables['individual_block_user'].'.individual_id = '.$this->tables['users'].'.id', 'LEFT');
        $this->db->where($this->tables['individual_block_user'].'.user_id', $user_id);
        $this->db->where($this->tables['individual_block_user'].'.status','Block');
         $this->db->order_by($this->tables['individual_block_user'].'.date', 'DESC');
        $query = $this->db->get($this->tables['individual_block_user']);

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

        public function get_individual_visitor_list($user_id)
    {
        $this->db->select($this->tables['individual_user_profile_view'].'.*,'.$this->tables['users'].'.username,profile_picture');
        $this->db->join($this->tables['users'], $this->tables['individual_user_profile_view'].'.user_id = '.$this->tables['users'].'.id', 'LEFT');
        $this->db->where($this->tables['individual_user_profile_view'].'.individual_id', $user_id);
        $this->db->order_by($this->tables['individual_user_profile_view'].'.date','DESC');
        $query = $this->db->get($this->tables['individual_user_profile_view']);

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

        public function get_individual_view_list($user_id)
    {
        $this->db->select($this->tables['individual_user_profile_view'].'.*,'.$this->tables['users'].'.username,profile_picture');
        $this->db->join($this->tables['users'], $this->tables['individual_user_profile_view'].'.individual_id = '.$this->tables['users'].'.id', 'LEFT');
        $this->db->where($this->tables['individual_user_profile_view'].'.user_id', $user_id);
        $this->db->order_by($this->tables['individual_user_profile_view'].'.date','DESC');

        $query = $this->db->get($this->tables['individual_user_profile_view']);

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

        public function get_individual_like_list($user_id)
    {
        $this->db->select($this->tables['individual_like'].'.*,'.$this->tables['users'].'.username,profile_picture');
        $this->db->join($this->tables['users'], $this->tables['individual_like'].'.user_id = '.$this->tables['users'].'.id', 'LEFT');
        $this->db->where($this->tables['individual_like'].'.individual_id', $user_id);
        $this->db->order_by($this->tables['individual_like'].'.date', 'DESC');
        
        $query = $this->db->get($this->tables['individual_like']);

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }
}