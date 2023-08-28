<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Party_model extends CI_Model {

    var $order = array('party.id' => 'desc'); // default order

    public function __construct() {
        parent::__construct();

        $this->table = 'party';
        $this->table_cities = 'cities';
        $this->table_users = 'users';
        $this->table_party_type = 'party_type';
        $this->table_organization = 'organization';
        
        $this->column_order = array('Title', 'Description', 'Start_date', 'End_date', 'Gender'); //set column field database for datatable orderable
        
        $this->column_search = array('Title', 'Description', 'Start_date', 'End_date'); //set column field database for datatable searchable 
    }

    private function _get_datatables_query() {
        //add custom filter here
        $this->db->select($this->table.'.*');
        $this->db->where($this->table.'.is_deleted', 0);
        $this->db->where($this->table.'.active', 1);
        
        if ($this->input->post('Title')) {
            $this->db->like($this->table . '.title', $this->input->post('Title'));
        }

        if ($this->input->post('Description')) {
            $this->db->like($this->table . '.description', $this->input->post('Description'));
        }

        if ($this->input->post('Start_date')) {
            $this->db->like($this->table . '.start_date', strtotime('d-m-Y', $this->input->post('Start_date')));
        }

        if ($this->input->post('End_date')) {
            $this->db->like($this->table . '.end_date', strtotime('d-m-Y', $this->input->post('End_date')));
        }
        $this->db->from($this->table);
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
        $this->db->where($this->table.'.is_deleted', 0);
        $this->db->where($this->table.'.active', 1);
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_today_party() {
        $this->db->select($this->table.'.*,' .$this->table_organization.'.name AS organization,'.$this->table_users.'.first_name AS full_name,' .$this->table_users.'.profile_picture');
        $this->db->join($this->table_organization, $this->table_organization.'.id =' .$this->table.'.organization_id', 'left');
        $this->db->join($this->table_users, $this->table_users.'.id =' .$this->table.'.user_id');
        $this->db->where($this->table.'.is_deleted', 0);
        $this->db->where($this->table.'.active', 1);
        $this->db->where($this->table.'.start_date <=', strtotime('today'));
        $this->db->where($this->table.'.end_date >=', strtotime('today'));
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function party_details($party_id) {
        /* If Required to filter near by user */
        $this->db->select($this->table.'.*,' .$this->table_organization.'.name AS organization,'.$this->table_users.'.first_name AS full_name,' .$this->table_users.'.profile_picture');
        $this->db->join($this->table_organization, $this->table_organization.'.id =' .$this->table.'.organization_id', 'left');
        $this->db->join($this->table_users, $this->table_users.'.id =' .$this->table.'.user_id');
        $this->db->where($this->table.'.id', $party_id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            $data =  $query->row();
        }
        if (!empty($data)) {
            $types = $data->type;
            $typeData = explode(',', $types);
            $row = [];
            foreach ($typeData as $key1 => $type) {
                $type = $this->db->get_where($this->table_party_type, ['id' => $type])->row();
                $row[] = $type->name;
            }
            if (!empty($row)) {
                $data->type = implode(',', $row);   
            }
            $genders = $data->gender;
            $gend = explode(',', $genders);
            return $data;
        }
        return false;
    }

    public function check_city($full_name) {
        return $this->db->get_where($this->table, ['full_name' => $full_name])->row();
    }

    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    public function city_details($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}