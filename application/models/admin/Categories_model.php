<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends CI_Model {

    
    public function __construct() {
        parent::__construct();
        $this->tables = [
            'categories' => 'categories'
        ];

        $order = array($this->tables['categories'].'.id' => 'desc'); // default order

        $this->column_order = array('name','status',null); //set column field database for datatable orderable
        
        $this->column_search = array('name','status',null); //set column field database for datatable searchable 
    }

    public function get_categories() {
        $query = $this->db->get($this->tables['categories']);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    public function add_category($data) {
        return $this->db->insert($this->tables['categories'], $data);
    }

    public function fetch_category($id) {
        $this->db->select('*');
        $this->db->from($this->tables['categories']);
        $this->db->where('id', $id);
        return $this->db->get();
    }

    public function update_category($id, $data) {
        return $this->db->update($this->tables['categories'], $data, array('id' => $id));
    }

    public function update_status($id, $data) {
        return $this->db->update($$this->tables['categories'], $data, array('id' => $id));
    }

    public function delete_category($id) {
        $this->db->delete($this->tables['categories'], array('id' => $id));
        if ($this->db->affected_rows() == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private function _get_datatables_query() {
        //add custom filter here
        $this->db->select($this->tables['categories'] . '.*');
        if ($this->input->post('category_name')) {
            $this->db->like($this->tables['categories'] . '.name', $this->input->post('category_name'));
        }
        
        $this->db->from($this->tables['categories']);
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
        $this->db->from($this->tables['categories']);
        return $this->db->count_all_results();
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
