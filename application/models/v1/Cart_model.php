<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cart_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->tables = [
			'introductions' => 'introductions',
			'orders' => 'orders',
			'payment_history' => 'payment_history',
			'point_system' => 'point_system',
			'user_points' => 'user_points',
			'restaurant_off_dates' => 'restaurant_off_dates',
			'working_hours' => 'working_hours',
			'order_counts' => 'order_counts' 
		];
	}

	public function get_introduction() {
		$this->db->select($this->tables['introductions'].'.*');
		$this->db->order_by($this->tables['introductions'].'.order_number','ASC');
		$query = $this->db->get($this->tables['introductions']);

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return FALSE;
	}

	public function check_distance($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}

	public function place_order($data) {
		$this->db->insert($this->tables['orders'], $data);
		return $this->db->insert_id();
	}

	public function save_transcation($data) {
		$this->db->insert($this->tables['payment_history'], $data);
		return $this->db->insert_id();
	}

	public function get_point_system_data() {
		$this->db->select($this->tables['point_system'].'.*');
		$this->db->limit(1);
		$query = $this->db->get($this->tables['point_system']);

		if ($query->num_rows() > 0) {
			return $query->row();
		}

		return FALSE;
	}

	public function get_order($order_id) {
		$this->db->select($this->tables['orders'].'.*');
		$this->db->where($this->tables['orders'].'.order_id', $order_id);
		$query = $this->db->get($this->tables['orders']);

		if ($query->num_rows() > 0) {
			return $query->row();
		}

		return FALSE;
	}

	public function get_order_earning_point($order_id, $user_id) {
		$this->db->select($this->tables['user_points'].'.*');
		$this->db->where($this->tables['user_points'].'.order_id', $order_id);
		$this->db->where($this->tables['user_points'].'.user_id', $user_id);
		$query = $this->db->get($this->tables['user_points']);

		if ($query->num_rows() > 0) {
			return $query->row();
		}

		return FALSE;
	}


	public function check_restaurant_holiday_working_hour($restaurant_id) {
		$query = $this->db->get_where($this->tables['restaurant_off_dates'], ['off_date' => date('Y-m-d'), 'restaurant_id' => $restaurant_id]);
		if ($query->num_rows() > 0) {
			return FALSE;
		} else {
			$query1 = $this->db->get_where($this->tables['working_hours'], ['day_name' => date('l'), 'open_time <=' => date('H:i'), 'close_time >=' => date('H:i'), 'restaurant_id' => $restaurant_id, 'close_day' => 0]);
			if ($query1->num_rows() > 0) {
				return TRUE;
			} 
			return FALSE;
		}
	}

	public function insert_report_details($data) {
		return $this->db->insert_batch($this->tables['order_counts'], $data);
	}
}