<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sports_model extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->table = [
            'major_sports_event' => 'major_sports_event'
        ];

        $this->column_order = ['title', NULL, NULL, NULL, NULL]; //set column field database for datatable orderable     
        $this->column_search = ['title']; //set column field database for datatable searchable 
    }

    public function create_sports_event($data) {
    	$this->db->insert($this->table['major_sports_event'], $data);
        return $this->db->insert_id();
    }

    public function fetch_sport_event($id) {
    	return $this->db->get_where($this->table['major_sports_event'], ['id' => $id])->row();
    }

    public function edit_sports_event($id, $data) {
    	return $this->db->update($this->table['major_sports_event'], $data, ['id' => $id]);
    }

    private function _get_datatables_query() {
        $this->db->select('*');
        $this->db->from($this->table['major_sports_event']);
        if ($this->input->post('title')) {
        	$this->db->like('title', $this->input->post('title'));
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
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function delete_sport_event($id) {
        $this->db->where(['id' => $id])->delete($this->table['major_sports_event']);
        if ($this->db->affected_rows() == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}