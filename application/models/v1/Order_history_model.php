<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_history_model extends CI_Model {

	public function __construct() {
	    parent::__construct();
        $this->tables = [
        	'orders' => 'orders',
        	'restaurant_ratings' => 'restaurant_ratings',
        	'order_delivery_groups' => 'order_delivery_groups',
        	'restaurant_cuisine_items' => 'restaurant_cuisine_items',
        	'cuisine_addons' => 'cuisine_addons',
        	'extra_item_group_items' => 'extra_item_group_items',
        	'user_addresses'  => 'user_addresses',
        	'driver_ratings'  => 'driver_ratings',
        	'drivers' => 'drivers',
            'restaurants' => 'restaurants'
        ];
	}

	public function get_all_orders($user_id, $offset, $limit) {
		$this->db->select('*');
        $this->db->where(['user_id' => $user_id, 'order_status !=' => 0]);
        $this->db->order_by('order_id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->tables['orders']);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function is_order_rated($where) {
    	$query = $this->db->get_where($this->tables['restaurant_ratings'], $where)->row();
        if (!is_null($query)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function is_delivery_order_rated($where) {
        $query = $this->db->get_where($this->tables['driver_ratings'], $where)->row();
        if (!is_null($query)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function fetch_order_delivery_group($order_delivery_group_id) {
    	return $this->db->get_where($this->tables['order_delivery_groups'], ['order_delivery_group_id' => $order_delivery_group_id])->row();
    }

    public function fetch_restaurant_item($restaurant_cuisine_item_id) {
    	return $this->db->get_where($this->tables['restaurant_cuisine_items'], ['restaurant_cuisine_item_id' => $restaurant_cuisine_item_id])->row();
    }

    public function fetch_addon_item($extra_item_group_id) {
    	return $this->db->get_where($this->tables['cuisine_addons'], ['cuisine_addon_id' => $extra_item_group_id])->row();
    }

    public function fetch_group_item($addon_item_id) {
    	return $this->db->get_where($this->tables['extra_item_group_items'], ['addon_item_id' => $addon_item_id])->row();
    }

    public function get_address_details($address_id) {
    	return $this->db->get_where($this->tables['user_addresses'], ['address_id' => $address_id])->row();
    }

    public function fetch_driver_details($driver_id) {
    	return $this->db->get_where($this->tables['drivers'], ['id' => $driver_id])->row();
    }

    public function get_driver_ratings($driver_id) {
    	$this->db->select('SUM(rate_point) AS total_rate, COUNT(driver_rating_id) AS total_person');
        $this->db->where('driver_id', $driver_id);
        $this->db->limit(1);
        $result = $this->db->get($this->tables['driver_ratings'])->row();
        if (!empty($result)) {
            if ($result->total_person != 0 && ($result->total_rate != null OR $result->total_rate != 0 OR $result->total_rate != "")) {
                $calculated_rating = $result->total_rate / $result->total_person;
                return number_format($calculated_rating, 1);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function get_restaurant_details($restaurant_id) {
        return $this->db->get_where($this->tables['restaurants'], ['id' => $restaurant_id])->row();
    }

    public function fetch_order_detail($order_id) {
        return $this->db->get_where($this->tables['orders'], ['order_id' => $order_id])->row();
    }
}