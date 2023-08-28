<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Organization_model extends CI_Model {

    var $order = array('organization.id' => 'desc'); // default order
     var $order_org_pdf = array('organization_pdf.id' => 'desc');
    var $order_org_pdf_verification = array('organization_verified_pdf.id' => 'desc');

    public function __construct() {
        parent::__construct();

        $this->table = 'organization';
        $this->table_cities = 'cities';
        $this->table_users = 'users';
        $this->table_organization_amenities = 'organization_amenities';
        $this->table_organization_amenities = 'organization_amenities';
        $this->table_organization_pdf = 'organization_pdf';
        $this->table_organization_verified_pdf = 'organization_verified_pdf';
        $this->column_order = array('Name', 'Description'); //set column field database for datatable orderable
        
        $this->column_search = array('name', 'description'); //set column field database for datatable searchable 
    }

    private function _get_datatables_query() {
        //add custom filter here
        $this->db->select($this->table.'.*,'.$this->table_cities.'.name as city,'.$this->table_users.'.username AS full_name');
        $this->db->where($this->table.'.is_deleted', 0);
        //$this->db->where($this->table.'.active', 1);
        
        if ($this->input->post('Name')) {
            $this->db->like($this->table . '.name', $this->input->post('Name'));
        }

        if ($this->input->post('Description')) {
            $this->db->like($this->table . '.description', $this->input->post('Description'));
        }
       
        $this->db->from($this->table);
        $this->db->join($this->table_cities, $this->table.'.city_id ='.$this->table_cities.'.id', 'left');
         $this->db->join($this->table_users, $this->table.'.user_id ='.$this->table_users.'.id', 'left');
         
        if(!empty($this->input->post('Status')) && $this->input->post('Status')==1) {
            $this->db->where($this->table . '.status',1);
        }

         if(!empty($this->input->post('Status')) && $this->input->post('Status')==2) {
            $this->db->where($this->table . '.status',0);
        }
        $this->db->where($this->table . '.type','1');
        
        $i = 0;
        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($this->table.'.'.$item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($this->table.'.'.$item, $_POST['search']['value']);
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
         // echo $this->db->last_query(); die;
        return $query->result();
    }

    public function count_all() {
        $this->db->where($this->table.'.is_deleted', 0);
        //$this->db->where($this->table.'.active', 1);
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_today_organization() {
        $this->db->select($this->table.'.*,' .$this->table_organization.'.name AS organization,'.$this->table_users.'.username AS full_name,' .$this->table_users.'.profile_picture');
        $this->db->join($this->table_organization, $this->table_organization.'.id =' .$this->table.'.organization_id', 'left');
        $this->db->join($this->table_users, $this->table_users.'.id =' .$this->table.'.user_id');
        $this->db->where($this->table.'.is_deleted', 0);
        //$this->db->where($this->table.'.active', 1);
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

    public function organization_details($organization_id) {
        /* If Required to filter near by user */
        $this->db->select($this->table.'.*,'.$this->table_cities.'.name as city_name,'.$this->table_users.'.username AS full_name,' .$this->table_users.'.profile_picture');
        $this->db->join($this->table_users, $this->table_users.'.id =' .$this->table.'.user_id');
        $this->db->join($this->table_cities, $this->table.'.city_id ='.$this->table_cities.'.id', 'left');
        $this->db->where($this->table.'.id', $organization_id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            $org_amenitie=array();
            $data =  $query->row();
            $sql=$this->db->query('SELECT name FROM '.$this->table_organization_amenities.' WHERE id IN('.$data->org_amenitie_id.')');
            $res_org_am=$sql->result_array();
            if(!empty($res_org_am))
            {
              
              foreach ($res_org_am as $key => $value) {
                 $org_amenitie[]=$value['name'];
              }
                 $data->amenitie=implode(',',$org_amenitie);
            }else{
                   $data->amenitie='';
                 }

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

     public function add_pdf($data) {
        return $this->db->insert($this->table_organization_pdf, $data);
    }
   
    public function update_pdf($id, $data) {
        return $this->db->update($this->table_organization_pdf, $data, ['id' => $id]);
    }

    public function delete_pdf($id) {
        return $this->db->update($this->table, array('is_deleted'=>'1'), ['id' => $id]);
    }

     /*--------------------pdf listing code-------------------*/
     
     private function _get_datatables_org_paf_query() {
        //add custom filter here
        $this->db->select('*');
        $this->db->from($this->table_organization_pdf);
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
        } else if (isset($this->order_org_pdf)) {
            $order_org_pdf = $this->order_org_pdf;
            $this->db->order_by(key($order_org_pdf), $order_org_pdf[key($order_org_pdf)]);
        }
    }

    public function get_org_paf_datatables() {

        $this->_get_datatables_org_paf_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

     public function count_all_org_pdf() {
        $this->db->from($this->table_organization_pdf);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_filtered_org_pdf() {
        $this->_get_datatables_org_paf_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

   public function pdf_details($id) {
        return $this->db->get_where($this->table_organization_pdf, ['id' => $id])->row();
    }


       private function _get_datatables_org_paf_verification_query() {
        //add custom filter here
        $this->db->select($this->table_organization_verified_pdf.'.*,'.$this->table_users.'.first_name,last_name,'.$this->table.'.name as organization_name');
        $this->db->join($this->table, $this->table_organization_verified_pdf.'.organization_id ='.$this->table.'.id', 'left');

        $this->db->join($this->table_users, $this->table_organization_verified_pdf.'.user_id ='.$this->table_users.'.id', 'left');

        $this->db->from($this->table_organization_verified_pdf);
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
        } else if (isset($this->order_org_pdf_verification)) {
            $order_org_pdf_verification = $this->order_org_pdf_verification;
            $this->db->order_by(key($order_org_pdf_verification), $order_org_pdf_verification[key($order_org_pdf_verification)]);
        }
    }

    public function get_user_org_pdf_verification_datatables() {

        $this->_get_datatables_org_paf_verification_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

     public function count_all_user_org_pdf_verification() {
        $this->db->from($this->table_organization_verified_pdf);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_filtered_user_org_pdf_verification() {
        $this->_get_datatables_org_paf_verification_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

   public function pdf_verification_status_update_data($id, $data) {
        return $this->db->update($this->table_organization_verified_pdf, $data, ['id' => $id]);
    }  

     public function get_status($org_id) {
        return $this->db->get_where($this->table, ['id' => $org_id])->row();
    }

   

}