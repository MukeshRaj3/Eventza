<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    var $order = array('users.id' => 'desc'); // default order
    var $order_udr = array('users_deactivation_request.id' => 'desc'); // default order
    public function __construct() {
        parent::__construct();

        $this->table = 'users';
        $this->table_users_deactivation_request = 'users_deactivation_request';
        $this->table_users_block_reports = 'users_block_reports';
        $this->column_order = array('first_name', 'email', 'phone', 'last_login','created_on'); //set column field database for datatable orderable
        
        $this->column_search = array('first_name', 'email', 'phone', 'last_login','created_on'); //set column field database for datatable searchable 
    }

    private function _get_datatables_query() {
        //add custom filter here
        $this->db->select($this->table . '.*');
        if ($this->input->post('Name')) {
            $this->db->like($this->table . '.first_name', $this->input->post('Name'));
        }
        if ($this->input->post('Email')) {
            $this->db->like($this->table . '.email', $this->input->post('Email'));
        }
        if ($this->input->post('Phone')) {
            $this->db->like($this->table . '.phone', $this->input->post('Phone'));
        }

        $this->db->from($this->table);
        $this->db->where($this->table . '.role_id', 2);
          if(!empty($this->input->post('Status')) && $this->input->post('Status')==1) {
            $this->db->where($this->table . '.active',1);
        }

         if(!empty($this->input->post('Status')) && $this->input->post('Status')==2) {
            $this->db->where($this->table . '.active',0);
        }

         if(!empty($this->input->post('Status')) && $this->input->post('Status')==3) {
            $this->db->where($this->table . '.is_verified_phone',1);
        }

         if(!empty($this->input->post('Status')) && $this->input->post('Status')==4) {
            $this->db->where($this->table . '.is_verified_phone',0);
        }

        $i = 0;
        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables() {

        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all() {
        $this->db->from('users');
        $this->db->where($this->table . '.role_id', 2);
        return $this->db->count_all_results();
    }

    public function count_active_all() {
        $this->db->from('users');
        $this->db->where($this->table . '.role_id', 2);
        $this->db->where($this->table . '.active',1);

        return $this->db->count_all_results();
    }

     public function count_inactive_all() {
        $this->db->from('users');
        $this->db->where($this->table . '.role_id', 2);
        $this->db->where($this->table . '.active',0);

        return $this->db->count_all_results();
    }

     public function count_verified_all() {
        $this->db->from('users');
        $this->db->where($this->table . '.role_id', 2);
        $this->db->where($this->table . '.active',1);

        return $this->db->count_all_results();
    }

     public function count_unverified_all() {
        $this->db->from('users');
        $this->db->where($this->table . '.role_id', 2);
        $this->db->where($this->table . '.active',0);

        return $this->db->count_all_results();
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function update_deactivation_request($id, $data) {
        return $this->db->update($this->table_users_deactivation_request, $data, ['id' => $id]);
    }
    
 public function get_datatables_deactivation_request() {

        $this->_get_datatables_query_deactivation_request();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

private function _get_datatables_query_deactivation_request() {
        //add custom filter here
        $this->db->select($this->table_users_deactivation_request . '.*,'.$this->table . '.email,username,phone,first_name');
        $this->db->from($this->table_users_deactivation_request);
        $this->db->join($this->table, $this->table.'.id =' .$this->table_users_deactivation_request.'.user_id', 'left');
        $i = 0;
        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        if (isset($_POST['order'])) { // here order processing
            //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order_udr)) {
           // $order_udr = $this->order_udr;
           // $this->db->order_by(key($order_udr), $order[key($order_udr)]);
        }
    }

   

     public function count_all_deactivation_request() {
        $this->db->from($this->table_users_deactivation_request);
        return $this->db->count_all_results();
    }

    public function count_filtered_deactivation_request() {
        $this->_get_datatables_query_deactivation_request();
        $query = $this->db->get();
        return $query->num_rows();
    }

     public function update_block_report($id, $data) {
        return $this->db->update($this->table_users_block_reports, $data, ['id' => $id]);
    }
    
 public function get_datatables_block_report() {

        $this->_get_datatables_query_block_report();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
         $result=$query->result();
      
        if(!empty($result))
        {
            foreach ($result as $key => $value) {
            $this->db->select($this->table. '.email,username,phone,first_name');
            $this->db->from($this->table);
            $this->db->where($this->table.'.id',$value->block_user_id);
            $query2 = $this->db->get();
            
            if($query2->num_rows()>0)
            {
                  $result[$key]->block_first_name=$query2->result()[0]->first_name;
                  $result[$key]->block_email=$query2->result()[0]->email;
                  $result[$key]->block_phone=$query2->result()[0]->phone;
            }else{
                  $result[$key]->block_first_name='';
                  $result[$key]->block_email='';
                  $result[$key]->block_phone='';
                 }
            }
        }
        return $result;
    }

private function _get_datatables_query_block_report() {
        //add custom filter here
        $this->db->select($this->table_users_block_reports . '.*,'.$this->table . '.email,username,phone,first_name');
        $this->db->from($this->table_users_block_reports);
        $this->db->join($this->table, $this->table.'.id =' .$this->table_users_block_reports.'.user_id', 'left');
        $i = 0;
        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        if (isset($_POST['order'])) { // here order processing
            //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
           // $order = $this->order;
           // $this->db->order_by(key($order), $order[key($order)]);
        }
    }

   

     public function count_all_block_report() {
        $this->db->from($this->table_users_block_reports);
        return $this->db->count_all_results();
    }

    public function count_filtered_block_report() {
        $this->_get_datatables_query_block_report();
        $query = $this->db->get();
        return $query->num_rows();
    }    
}