<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_model extends CI_Model {

    var $order = array('facilities.id' => 'desc'); // default order

    public function __construct() {
        parent::__construct();

        $this->table = 'facilities';
        
        $this->column_order = array('business_type_id','facility',null,null); //set column field database for datatable orderable
        
        $this->column_search = array('facility'); //set column field database for datatable searchable 
    }

    private function _get_datatables_query() {
        //add custom filter here
        $this->db->select($this->table . '.*,business_types.*');
        $this->db->join('business_types',$this->table .'.business_type_id = business_types.bt_id');
        if ($this->input->post('Facility')) {
            $this->db->like($this->table . '.facility', $this->input->post('Facility'));
        }
        if ($this->input->post('BusinessType')) {
            $this->db->like('business_types.business_name', $this->input->post('BusinessType'));
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
}