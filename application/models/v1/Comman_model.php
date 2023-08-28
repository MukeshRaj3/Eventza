<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comman_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'introductions' => 'introductions'
        ];
    }

    public function get_introduction()
    {
    	$this->db->select($this->tables['introductions'].'.*');
    	$this->db->order_by($this->tables['introductions'].'.order_number','ASC');
    	$query = $this->db->get($this->tables['introductions']);

    	if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }
}