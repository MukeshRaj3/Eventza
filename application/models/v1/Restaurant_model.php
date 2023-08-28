<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Restaurant_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'restaurants' => 'restaurants',
            'takeaway_postcodes' => 'takeaway_postcodes',
            'extra_item_group_items' => 'extra_item_group_items',
            'restaurant_cuisine_items' => 'restaurant_cuisine_items',
            'promocode_usage_history' => 'promocode_usage_history',
            'promocodes' => 'promocodes'
        ];
        $this->radius_value = getenv('RADIUS_VALUE'); /* For Miles 3959 , For KM 6371 */
        $this->radius_distance = getenv('RADIUS_RANGE');
    }

    public function get_restaurant($restaurant_id)
    {
    	$this->db->select($this->tables['restaurants'].'.*');
        $this->db->where($this->tables['restaurants'].'.id', $restaurant_id);
    	$query = $this->db->get($this->tables['restaurants']);

    	if ($query->num_rows() > 0) {
            return $query->row();
        }

        return FALSE;
    }

    public function get_restaurant_with_distance($restaurant_id, $latitude, $longitude)
    {
        $this->db->select($this->tables['restaurants'].'.*,( '.$this->radius_value.' * acos( cos( radians('.$latitude.') ) * cos( radians( '.$this->tables['restaurants'].'.restaurant_latitude ) ) * cos( radians( '.$this->tables['restaurants'].'.restaurant_longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( '.$this->tables['restaurants'].'.restaurant_latitude ) ) ) ) AS distance');
        $this->db->where($this->tables['restaurants'].'.id', $restaurant_id);
        $query = $this->db->get($this->tables['restaurants']);

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return FALSE;
    }

    public function check_delivery_postcode($postcode, $restaurant_id) {
        $this->db->select('*');
        $this->db->where('restaurant_id', $restaurant_id);
        $this->db->like('postcode', $postcode, 'none');
        $query = $this->db->get($this->tables['takeaway_postcodes']);

        return $query->row();
    }

    public function get_addon_item($addon_item_id) {
        $this->db->select('*');
        $this->db->where('addon_item_id', $addon_item_id);
        $query = $this->db->get($this->tables['extra_item_group_items']);
        
        return $query->row();
    }

    public function get_restaurant_cuisine_item($item_id, $restaurant_id) {
        $this->db->select('*');
        $this->db->where('restaurant_cuisine_item_id', $item_id);
        $this->db->where('restaurant_id', $restaurant_id);
        $query = $this->db->get($this->tables['restaurant_cuisine_items']);
        
        return $query->row();
    }

    public function get_promocode_usage_details($promo_usage_id)
    {
        $this->db->select($this->tables['promocode_usage_history'].'.promo_usage_id,'.$this->tables['promocodes'].'.*');
        $this->db->join($this->tables['promocodes'], $this->tables['promocode_usage_history'].'.promocode_id = '.$this->tables['promocodes'].'.promocode_id');
        $this->db->where($this->tables['promocode_usage_history'].'.promo_usage_id', $promo_usage_id);
        $query = $this->db->get($this->tables['promocode_usage_history']);

        return $query->row();
    }
}