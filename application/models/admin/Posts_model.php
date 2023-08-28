<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Posts_model extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->table = [
        	'posts' => 'posts',
        	'categories' => 'categories',
        	'posts_tags' => 'posts_tags',
            'posts_categories' => 'posts_categories'
        ];

        $this->column_order = ['title', NULL, NULL, NULL, NULL]; //set column field database for datatable orderable     
        $this->column_search = ['title']; //set column field database for datatable searchable 
    }

    public function get_categories() {
    	$query = $this->db->get_where($this->table['categories'], ['status' => 1]);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function create_post($data) {
    	$this->db->insert($this->table['posts'], $data);
        return $this->db->insert_id();
    }

    public function add_category($data) {
    	return $this->db->insert_batch($this->table['posts_categories'], $data);
    }

    public function fetch_post($id) {
    	return $this->db->get_where($this->table['posts'], ['id' => $id])->row();
    }

    public function edit_post($id, $data) {
    	return $this->db->update($this->table['posts'], $data, ['id' => $id]);
    }

    public function remove_port_all_categories($id) {
        return $this->db->delete($this->table['posts_categories'], ['post_id' => $id]);
    }

    public function selected_post_categories($post_id) {
        $this->db->simple_query('SET SESSION group_concat_max_len=1000000000');
        $this->db->select('GROUP_CONCAT(category_id) as category_ids');
        $this->db->from($this->table['posts_categories']);
        $this->db->where('post_id', $post_id);
        $query = $this->db->get()->row_array();
        $response = explode(",", $query['category_ids']);
        return $response;
    }

    private function _get_datatables_query() {
        $this->db->select('');
        $this->db->from($this->table['posts']);
        if ($this->input->post('title')) {
        	$this->db->like('title', $this->input->post('title'));
        }
        if ($this->input->post('SEOTitle')) {
        	$this->db->like('seo_title', $this->input->post('SEOTitle'));
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

    public function delete_post($id) {
        $this->db->where(['id' => $id])->delete($this->table['posts']);
        if ($this->db->affected_rows() == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}