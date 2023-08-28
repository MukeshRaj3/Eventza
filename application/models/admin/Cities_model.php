<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cities_model extends CI_Model {

    var $order = array('cities.id' => 'desc'); // default order

    public function __construct() {
        parent::__construct();

        $this->table = 'cities';
        
        $this->column_order = array('name', 'full_name'); //set column field database for datatable orderable
        
        $this->column_search = array('name'); //set column field database for datatable searchable 
    }

    private function _get_datatables_query() {
        //add custom filter here
        $this->db->select($this->table . '.*');
        if ($this->input->post('Name')) {
            $this->db->like($this->table . '.name', $this->input->post('Name'));
        }
        if ($this->input->post('Fullname')) {
            $this->db->like($this->table . '.full_name', $this->input->post('Fullname'));
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
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