<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Driver_account_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'drivers'                   => 'drivers',
            'driver_ratings'            => 'driver_ratings',
            'driver_daily_earnings'     => 'driver_daily_earnings',
            'driver_device_token'       => 'driver_device_token',
            'orders'                    => 'orders',
            'user_addresses'            => 'user_addresses',
            'restaurants'               => 'restaurants',
            'order_delivery_groups'     => 'order_delivery_groups',
            'restaurant_cuisine_items'  => 'restaurant_cuisine_items',
            'extra_item_group_items'    => 'extra_item_group_items',
            'users' => 'users'
        ];
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
            }
            return 0;
        }
        return 0;
    }

    public function get_current_month_earing($driver_id) {
        $this->db->select('SUM(earning_amount) AS earning_amount');
        $this->db->where('driver_id', $driver_id);
        $this->db->where('earning_date BETWEEN "'. date('Y-m-01'). '" and "'. date('Y-m-t').'"');
        $earning = $this->db->get($this->tables['driver_daily_earnings'])->row();

        $this->db->select('SUM(total_delivered) AS total_delivered');
        $this->db->where('driver_id', $driver_id);
        $this->db->where('earning_date BETWEEN "'. date('Y-m-01'). '" and "'. date('Y-m-t').'"');
        $delivered = $this->db->get($this->tables['driver_daily_earnings'])->row();

        if (empty($earning->earning_amount)) {
            $earning->earning_amount = 0;
        }

        if (empty($delivered->total_delivered)) {
            $delivered->total_delivered = 0;
        }
        
        return ['earning' => $earning->earning_amount, 'delivered' => $delivered->total_delivered];
    }

    public function update_driver_token($data) {
        return $this->db->update($this->tables['drivers'], ['device_token' => $data['device_token']], ['id' => $data['driver_id']]);  
    }

    public function get_all_orders($driver_id, $offset, $limit) {
        $this->db->select('*');
        $this->db->where(['driver_id' => $driver_id, 'order_status !=' => 0]);
        $this->db->order_by('order_id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->tables['orders']);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_user_address($address_id) {
        return $this->db->get_where($this->tables['user_addresses'], ['address_id' => $address_id])->row();
    }

    public function get_restaurant_address($restaurant_id) {
        return $this->db->get_where($this->tables['restaurants'], ['id' => $restaurant_id])->row();   
    }

    public function fetch_order_delivery_group($order_delivery_group_id) {
        return $this->db->get_where($this->tables['order_delivery_groups'], ['order_delivery_group_id' => $order_delivery_group_id])->row();
    }

    public function get_restaurant_item($restaurant_cuisine_item_id) {
        return $this->db->get_where($this->tables['restaurant_cuisine_items'], ['restaurant_cuisine_item_id' => $restaurant_cuisine_item_id])->row();   
    }

    public function get_driver_earning_chart($driver_id) {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        $dates = [];
        /*Earning Query */
        $query = $this->db->get_where($this->tables['driver_daily_earnings'], ['driver_id' => $driver_id, 'earning_date >=' => $start_date, 'earning_date <=' => $end_date]); 
        $max_amount = 0;
        if ($query->num_rows() > 0) {
            $results = $query->result();
            for ($i=0; $i < date('t'); $i++) {
                $date =  date('Y-m-d', strtotime($start_date . ' +'.$i.' day'));
                $earning_amount = 0;
                foreach ($results as $key => $data) {
                    if ($max_amount < $data->earning_amount) {
                        $max_amount = $data->earning_amount;
                    }
                    if ($data->earning_date == $date) {
                        $earning_amount = $data->earning_amount;
                    }
                }
                $response[] = [
                    'earning_date' => $date,
                    'earning_amount' => (float) $earning_amount
                ];
            }
            return ['earning' => $response, 'max_amount' => (float) $max_amount];
        }
        return ['earning' => [], 'max_amount' => 0];
    }

    public function get_driver_order_deliver_chart($driver_id) {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        $dates = [];
        /*Earning Query */
        $query = $this->db->get_where($this->tables['driver_daily_earnings'], ['driver_id' => $driver_id, 'earning_date >=' => $start_date, 'earning_date <=' => $end_date]);
        $max_delivered = 0;
        if ($query->num_rows() > 0) {
            $results = $query->result();
            for ($i = 0; $i < date('t'); $i++) {
                $date =  date('Y-m-d', strtotime($start_date . ' +'.$i.' day'));
                $total_delivered = 0;
                foreach ($results as $key => $data) {
                    if ($max_delivered < $data->total_delivered) {
                        $max_delivered = $data->total_delivered;
                    }

                    if ($data->earning_date == $date) {
                        $total_delivered = $data->total_delivered;
                    }
                }

                $response[] = [
                    'delivered_date' => $date,
                    'total_delivered' => (int) $total_delivered
                ];
            }
            return ['delivered' => $response, 'max_delivered' => (int)$max_delivered];
        }
        return ['delivered' => [], 'max_delivered' => 0];
    }

    public function get_group_item($addon_item_id) {
        return $this->db->get_where($this->tables['extra_item_group_items'], ['addon_item_id' => $addon_item_id])->row();
    }

    public function get_delivery_group($driver_id) {
        $delivery_group = $this->db->get_where($this->tables['order_delivery_groups'], ['is_active_group' => 1, 'next_available' => 0, 'driver_id' => $driver_id])->row();
        if (!is_null($delivery_group)) {
            $order_data = $this->db->get_where($this->tables['orders'], ['order_delivery_group_id' => $delivery_group->order_delivery_group_id])->result();

            if (!empty($order_data)) {
                $delivery_pending = 0;
                foreach ($order_data as $key => $order) {
                    /* Order status 7 - order delivered */
                    if ($order->order_status != 7) {
                        $delivery_pending = $delivery_pending + 1; 
                    }
                }

                if ($delivery_pending > 0) {
                    /* delivery_group 1 - current group , 2 - future group */
                    return ['group_type' => 1, 'data' => $delivery_group];
                } else {
                    $is_updated = $this->db->update($this->tables['order_delivery_groups'], ['is_active_group' => 0] ,['order_delivery_group_id' => $delivery_group->order_delivery_group_id]);

                    if ($is_updated) {
                        $delivery_group = $this->db->get_where($this->tables['order_delivery_groups'], ['is_active_group' => 1, 'next_available' => 1, 'driver_id' => $driver_id])->row();

                        if (!empty($delivery_group)) {
                            $is_updated = $this->db->update($this->tables['order_delivery_groups'], ['next_available' => 0] ,['order_delivery_group_id' => $delivery_group->order_delivery_group_id]);

                            if ($is_updated) {
                                /* delivery_group 1 - current group , 2 - future group */
                                return ['group_type' => 2, 'data' => $delivery_group];
                            } else {
                                return FALSE;
                            }
                        } else {
                            $delivery_group = $this->db->get_where($this->tables['order_delivery_groups'], ['is_active_group' => 1, 'next_available' => 0, 'driver_id' => $driver_id])->row();

                            if (!empty($delivery_group)) {
                                return ['group_type' => 1, 'data' => $delivery_group];
                            } else {
                                return FALSE;
                            }
                        }
                    } else {
                         return FALSE;
                    }
                }
            } else {
                return FALSE;
            }
        } else {
            $delivery_group = $this->db->get_where($this->tables['order_delivery_groups'], ['is_active_group' => 1, 'next_available' => 1, 'driver_id' => $driver_id])->row();

            if (!empty($delivery_group)) {
                $is_updated = $this->db->update($this->tables['order_delivery_groups'], ['next_available' => 0] ,['order_delivery_group_id' => $delivery_group->order_delivery_group_id]);

                if ($is_updated) {
                    /* group_type 1 - current group , 2 - future group */
                    return ['group_type' => 2, 'data' => $delivery_group];
                } else {
                    return FALSE;
                }
            }
        }
    }

    public function get_all_delivery_group_orders($data) {
        $this->db->select($this->tables['orders'].'.*,' .$this->tables['order_delivery_groups'].'.order_delivery_group_id, total_delivery_order, mark_as_pickup, next_available, is_active_group, delivery_start_on,' .$this->tables['order_delivery_groups'].'.driver_id,' . $this->tables['users'].'.country_code, phone, first_name as full_name,' .$this->tables['user_addresses'].'.address_latitude, address_longitude, address, house_number, landmark, postcode, save_as');
        $this->db->join($this->tables['user_addresses'], $this->tables['user_addresses'].'.address_id =' .$this->tables['orders'].'.address_id');
        $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['orders'].'.user_id');
        $this->db->join($this->tables['order_delivery_groups'], $this->tables['order_delivery_groups'].'.order_delivery_group_id =' .$this->tables['orders'].'.order_delivery_group_id');
        $this->db->where($this->tables['orders'].'.driver_id', $data['driver_id']);
        $this->db->where($this->tables['orders'].'.order_delivery_group_id', $data['order_delivery_group_id']);
        $this->db->order_by($this->tables['orders'].'.delivery_number', 'ASC');
        $query = $this->db->get($this->tables['orders']);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_future_group($driver_id) {
        return $this->db->get_where($this->tables['order_delivery_groups'], ['next_available' => 1, 'is_active_group' => 1, 'driver_id' => $driver_id])->row();
    }

    public function fetch_driver_all_order($driver_id) {
        $this->db->select($this->tables['orders'].'.*,' .$this->tables['order_delivery_groups'].'.order_delivery_group_id, total_delivery_order, mark_as_pickup, next_available, is_active_group, delivery_start_on,' .$this->tables['order_delivery_groups'].'.driver_id, total_distance, total_time, end_distance, end_time, delivery_start_on,' . $this->tables['users'].'.country_code, phone, first_name as full_name,' .$this->tables['user_addresses'].'.address_latitude, address_longitude, address, house_number, landmark, postcode, save_as');
        $this->db->join($this->tables['user_addresses'], $this->tables['user_addresses'].'.address_id =' .$this->tables['orders'].'.address_id');
        $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['orders'].'.user_id');
        $this->db->join($this->tables['order_delivery_groups'], $this->tables['order_delivery_groups'].'.order_delivery_group_id =' .$this->tables['orders'].'.order_delivery_group_id');
        $this->db->where('(orders.order_status = 2 OR orders.order_status = 4 OR orders.order_status = 5 OR orders.order_status = 6)');
        $this->db->where($this->tables['orders'].'.driver_id', $driver_id);
        $this->db->order_by($this->tables['orders'].'.order_id', 'ASC');
        $query = $this->db->get($this->tables['orders']);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;   
    }
}