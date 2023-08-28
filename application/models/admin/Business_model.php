<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Business_model extends CI_Model {

    var $order = array('business.id' => 'desc'); // default order

    public function __construct() {
        parent::__construct();

        $this->table = 'business';
        $this->table_business_type = 'business_types';
        $this->table_facilities = 'facilities';
        $this->table_venues = 'enet_venues';
        $this->table_business_venue = 'business_venue';
        $this->table_business_images = 'business_images';
        $this->table_business_timing = 'business_timing';
        $this->table_business_offers = 'business_offers';
        $this->table_business_ratings = 'business_ratings';

        
        $this->board = [
            'business_signup' => 1428264517 
        ];

        $this->auth_token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjExNTE4NTY4NiwidWlkIjoyMjczNzI2NywiaWFkIjoiMjAyMS0wNi0yOFQwODo0MzoxNy42MjhaIiwicGVyIjoibWU6d3JpdGUiLCJhY3RpZCI6OTI0OTg1OCwicmduIjoidXNlMSJ9.t6jCsKSoh5QjJQAhZbpYJPHZ-FWeSXXX7l6zHCCsnHg';
        
        $this->column_order = array('full_name', 'extra_name', 'address', 'email', 'phone','website','registered_on', NULL); //set column field database for datatable orderable
        
        $this->column_search = array('full_name', 'extra_name' , 'address', 'email', 'phone','website','registered_on'); //set column field database for datatable searchable 
    }

    private function _get_datatables_query() {
        //add custom filter here
        $this->db->select($this->table . '.*');
        if ($this->input->post('BusinessName')) {
            $this->db->like($this->table . '.full_name', $this->input->post('BusinessName'));
        }
        if ($this->input->post('Firstlineofaddress')) {
            $this->db->like($this->table . '.extra_name', $this->input->post('Firstlineofaddress'));
        }
        if ($this->input->post('BusinessEmail')) {
            $this->db->like($this->table . '.email', $this->input->post('BusinessEmail'));
        }
        if ($this->input->post('Address')) {
            $this->db->like($this->table . '.extra_name', $this->input->post('Address'));
            $this->db->or_like($this->table . '.address', $this->input->post('Address'));
        }
        if ($this->input->post('ContactNumber')) {
            $this->db->like($this->table . '.phone', $this->input->post('ContactNumber'));
        }
        if ($this->input->post('Website')) {
            $this->db->like($this->table . '.website', $this->input->post('Website'));
        }
        if ($this->input->post('RegisteredOn')) {
            $registered_on = date('Y-m-d H:i:s',strtotime($this->input->post('RegisteredOn')));
            $this->db->where($this->table . '.registered_on', $registered_on);
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

    public function business_offer($id) {
        return $this->db->get_where($this->table_business_offers, ['business_id' => $id, 'is_deleted' => 0]);
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

    public function offer_delete($offer_id) {
        return $this->db->update($this->table_business_offers, ['is_deleted' => 1], ['id' => $offer_id]);
    }

    public function change_offer_status($offer_id, $status) {
        $this->db->update($this->table_business_offers, ['active' => $status], ['id' => $offer_id]);
        echo json_encode($status);
    }

    public function business_approve_reject($id, $status)
    {
        if ($status == 1) {
            return $this->db->update($this->table, ['business_status' => $status], ['id' => $id]);
        } else {
            $this->db->select($this->table_business_images.'.*');
            $this->db->where($this->table_business_images.'.business_id', $id);
            $query = $this->db->get($this->table_business_images);

            if ($query->num_rows() > 0 ) {
                $images = $query->result();

                foreach ($images as $key => $row) {
                    if (!empty($row->image)) {
                        unlink($row->image);
                    }
                }
                /* Delete all business images */
                $this->db->delete($this->table_business_images, ['business_id' => $id]);
            }

            /* Delete all business timing */
            $this->db->delete($this->table_business_timing, ['business_id' => $id]);
            /* Delete all business offer */
            $this->db->delete($this->table_business_offers, ['business_id' => $id]);
            /* Delete all business offer */
            $this->db->delete($this->table_business_ratings, ['business_id' => $id]);


            $this->db->select($this->table.'.*');
            $this->db->where($this->table.'.id', $id);
            $query1 = $this->db->get($this->table);

            if ($query1->num_rows() > 0) {
                $business = $query1->row();
                if (!empty($business->menu_image)) {
                    unlink($business->menu_image);
                }

                return $this->db->delete($this->table, ['id' => $id]);
            } else {
                return false;
            }
        }
    }

    public function business_deleted($id)
    {
        if (!empty($id)) {
            $this->db->select($this->table_business_images.'.*');
            $this->db->where($this->table_business_images.'.business_id', $id);
            $query = $this->db->get($this->table_business_images);

            if ($query->num_rows() > 0 ) {
                $images = $query->result();

                foreach ($images as $key => $row) {
                    if (!empty($row->image)) {
                        unlink($row->image);
                    }
                }
                /* Delete all business images */
                $this->db->delete($this->table_business_images, ['business_id' => $id]);
            }

            /* Delete all business timing */
            $this->db->delete($this->table_business_timing, ['business_id' => $id]);
            /* Delete all business offer */
            $this->db->delete($this->table_business_offers, ['business_id' => $id]);
            /* Delete all business offer */
            $this->db->delete($this->table_business_ratings, ['business_id' => $id]);


            $this->db->select($this->table.'.*');
            $this->db->where($this->table.'.id', $id);
            $query1 = $this->db->get($this->table);

            if ($query1->num_rows() > 0) {
                $business = $query1->row();
                if (!empty($business->menu_image)) {
                    unlink($business->menu_image);
                }
            return $this->db->delete($this->table, ['id' => $id]);
            } else {
                return false;
            }
        }else {
            return FALSE;
        }
    }

    public function update_venue($venues, $business_id) {
        $business = $this->db->get_where($this->table_business_venue, ['business_id' => $business_id])->row();
        if (!empty($business)) {
            $this->db->delete($this->table_business_venue, ['business_id' => $business_id]);
        }
        $data = [];
        if (!empty($venues)) {
            foreach ($venues as $venue) {
                $data[] = [
                    'business_id' => $business_id,
                    'venue_id'    => $venue 
                ];

            }
            $query = $this->db->insert_batch($this->table_business_venue, $data);
            if($query) {
                return $query;
            }
        }
        return FALSE;
    }
    public function fetch_business($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function fetch_business_type($type) {
        return $this->db->get_where($this->table_business_type, ['bt_id' => $type])->row();   
    }

    public function fetch_venue($id) {
        $this->db->select('enet_venues.*, business_venue.*');
        $this->db->from($this->table_business_venue);
        $this->db->join($this->table_venues,$this->table_business_venue.".venue_id = ".$this->table_venues.".venue_id");
        $this->db->where('business_id', $id);
        $query = $this->db->get();
        if ($query) {
            return $query->result();
        }
        return FALSE;
    }
    public function fetch_stadium($venues) {
        $row = [];
        if (!empty($venues)) {
            foreach ( $venues as $venue ) {
                $query = $this->db->get_where($this->table_venues, ['venue_id' => $venue->venue_id])->row();
                if ($query) {
                    $row[] = $query->venue_id; 
                }
            }
        }
        if ($row) {
            return implode(', ', $row);
        }
        return implode(', ', $row);
    }

    public function business_types() {
        $query = $this->db->get_where($this->table_business_type, ['business_type_active' => 1]);
        if ($query->num_rows()) {
            return $query->result();
        }
        return FALSE;
    }

    public function fetch_facility($facility_ids) {
        $facility_ids = explode(',', $facility_ids);
        $this->db->simple_query('SET SESSION group_concat_max_len=1000000000');
        $this->db->select('GROUP_CONCAT(facility SEPARATOR ", ") as facilities');
        $this->db->from($this->table_facilities);
        $this->db->where_in('id', $facility_ids);
        return $this->db->get()->row_array();
    }

    public function update_business($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function insert_venue($venues, $business_id) {
        $data = [];
        if(!empty($venues)) {
            foreach ($venues as $venue) {
                $data[] = [
                    'business_id' => $business_id,
                    'venue_id'    => $venue 
                ];
            }
            $query = $this->db->insert_batch($this->table_business_venue, $data);
            if($query) {
                return $query;
            }
            return FALSE;
        }
        return FALSE;
    }

    public function fetch_business_type_name($type) {
        $query = $this->db->get_where($this->table_business_type, ['bt_id' => $type])->row();
        if (!is_null($query)) {
            return $query->business_name;
        }
    }

    public function insert_monday_data($post_data) {
        $item_name = $post_data['business_name'];  

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.monday.com/v2/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"query":"mutation {\\r\\n    create_item (board_id: '.$this->board['business_signup'].', item_name: \\"'.$item_name.'\\") {\\r\\n        id\\r\\n    }\\r\\n}","variables":{}}',
            CURLOPT_HTTPHEADER => array(
                'Authorization:'.$this->auth_token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (!empty($response)) {
            $data = json_decode($response);

            if (!empty($data->data->create_item->id)) {
                $item_id = $data->data->create_item->id;

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.monday.com/v2/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{"query":"mutation {\\r\\n    a1 : change_simple_column_value (board_id: '.$this->board['business_signup'].', item_id: '.$item_id.', column_id: \\"text\\", value: \\"'.$post_data['email'].'\\") {\\r\\n        id\\r\\n    }\\r\\n    a2 : change_simple_column_value (board_id: '.$this->board['business_signup'].', item_id: '.$item_id.', column_id: \\"text1\\", value: \\"'.$post_data['business_name'].'\\") {\\r\\n        id\\r\\n    }\\r\\n    a3 : change_simple_column_value (board_id: '.$this->board['business_signup'].', item_id: '.$item_id.', column_id: \\"text6\\", value: \\"'.$post_data['phone'].'\\") {\\r\\n        id\\r\\n    }\\r\\n    a4 : change_simple_column_value (board_id: '.$this->board['business_signup'].', item_id: '.$item_id.', column_id: \\"text9\\", value: \\"'.$post_data['address'].'\\") {\\r\\n        id\\r\\n    }\\r\\n    a5 : change_simple_column_value (board_id: '.$this->board['business_signup'].', item_id: '.$item_id.', column_id: \\"text4\\", value: \\"'.$post_data['stadium'].'\\") {\\r\\n        id\\r\\n    }\\r\\n    a6 : change_simple_column_value (board_id: '.$this->board['business_signup'].', item_id: '.$item_id.', column_id: \\"text3\\", value: \\"'.$post_data['business_type'].'\\") {\\r\\n        id\\r\\n    }\\r\\n    a7 : change_simple_column_value (board_id: '.$this->board['business_signup'].', item_id: '.$item_id.', column_id: \\"text0\\", value: \\"'.$post_data['facilities'].'\\") {\\r\\n        id\\r\\n    }\\r\\n    a8 : change_simple_column_value (board_id: '.$this->board['business_signup'].', item_id: '.$item_id.', column_id: \\"text97\\", value: \\"'.$post_data['website'].'\\") {\\r\\n        id\\r\\n    }\\r\\n    a9 : change_simple_column_value (board_id: '.$this->board['business_signup'].', item_id: '.$item_id.', column_id: \\"location\\", value: \\"'.$post_data['location'].'\\") {\\r\\n        id\\r\\n    }\\r\\n}","variables":{}}',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: '.$this->auth_token,
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);

                return TRUE;
            }
        }
    }
}