<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Promocodes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
            'restaurants' => 'restaurants',
            'promocodes' => 'promocodes',
            'promocode_usage_history' => 'promocode_usage_history'
        ];
        $this->radius_value = getenv('RADIUS_VALUE'); /* For Miles 3959 , For KM 6371 */
        $this->radius_distance = getenv('RADIUS_RANGE');
    }

    public function get_nearby_promo_restaurants($latitude, $longitude, $user_id)
    {
        $this->db->select($this->tables['restaurants'].'.id, restaurant_name, delivery_radius, ( '.$this->radius_value.' * acos( cos( radians('.$latitude.') ) * cos( radians( '.$this->tables['restaurants'].'.restaurant_latitude ) ) * cos( radians( '.$this->tables['restaurants'].'.restaurant_longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( '.$this->tables['restaurants'].'.restaurant_latitude ) ) ) ) AS distance');
        $this->db->where($this->tables['restaurants'].'.active', 1);
        $this->db->having('distance <=', $this->radius_distance);
        $this->db->order_by('distance', 'ASC');
        $query = $this->db->get($this->tables['restaurants']);
        
        $restaurants = [];
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $restaurant) {
                if ($restaurant->distance <= $restaurant->delivery_radius) {
                    $this->db->select('promocode_id, promocode, promocode_title, promocode_description, number_of_time_usage, promocode_type, discount, expiry_date, per_user');
                    $this->db->where('expiry_date >=', date('Y-m-d'));
                    $this->db->where('is_expired', 0);
                    $this->db->where('restaurant_id', $restaurant->id);
                    $this->db->order_by('expiry_date','ASC');
                    $query2 = $this->db->get($this->tables['promocodes']);

                    if ($query2->num_rows() > 0) {
                        if (!empty($query2->result())) {
                            $promocodes = $query2->result();
                            $promocode_array = [];
                            foreach ($promocodes as $key => $promo) {
                                if (!empty($user_id)) {
                                    $this->db->select('COUNT(promocode_id) AS total_used');
                                    $this->db->where(['promocode_id' => $promo->promocode_id, 'user_id'=> $user_id, 'is_used' => 1]);
                                    $query3 = $this->db->get($this->tables['promocode_usage_history']);
                                    $use_status = $query3->row();

                                    if ($use_status->total_used <= 0) {
                                        $this->db->select('COUNT(promocode_id) AS total_used');
                                        $this->db->where(['promocode_id' => $promo->promocode_id, 'is_used' => 1]);
                                        $query4 = $this->db->get($this->tables['promocode_usage_history']);
                                        $use_status = $query4->row();
                                        
                                        if (!empty($use_status)) {
                                            if($use_status->total_used < $promo->number_of_time_usage) {
                                                $promocode_array[] = $promo;
                                            }
                                        } else {
                                            $promocode_array[] = $promo;
                                        }
                                    } else {
                                        if ($use_status->total_used < $promo->per_user) {
                                            $promocode_array[] = $promo;
                                        }
                                    }
                                } else {
                                    $this->db->select('COUNT(promocode_id) AS total_used');
                                    $this->db->where(['promocode_id' => $promo->promocode_id, 'is_used' => 1]);
                                    $query5 = $this->db->get($this->tables['promocode_usage_history']);
                                    $use_status = $query5->row();

                                    if ($use_status->total_used > 0) {
                                        if($use_status->total_used < $promo->number_of_time_usage) {
                                            $promocode_array[] = $promo;
                                        }
                                    } else {
                                        $promocode_array[] = $promo;
                                    }
                                }
                            }

                            if (!empty($promocode_array)) {
                                $restaurant->promocodes = $promocode_array;
                                $restaurants[] = $restaurant;
                            }
                        }
                    }
                }
            }

        }

        return $restaurants;
    }

    public function get_promo_restaurants($user_id)
    {
        $this->db->select($this->tables['restaurants'].'.id, restaurant_name, delivery_radius');
        $this->db->where($this->tables['restaurants'].'.active', 1);
        $query = $this->db->get($this->tables['restaurants']);

        $restaurants = [];
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $restaurant) {
                $this->db->select('promocode_id, promocode, promocode_title, promocode_description, number_of_time_usage, promocode_type, discount, expiry_date, per_user');
                $this->db->where('expiry_date >=', date('Y-m-d'));
                $this->db->where('is_expired', 0);
                $this->db->where('restaurant_id', $restaurant->id);
                $this->db->order_by('expiry_date','ASC');
                $query2 = $this->db->get($this->tables['promocodes']);

                if ($query2->num_rows() > 0) {
                    if (!empty($query2->result())) {
                        $promocodes = $query2->result();
                        $promocode_array = [];
                        foreach ($promocodes as $key => $promo) {
                            if (!empty($user_id)) {
                                $this->db->select('COUNT(promocode_id) AS total_used');
                                $this->db->where(['promocode_id' => $promo->promocode_id, 'user_id'=> $user_id, 'is_used' => 1]);
                                $query3 = $this->db->get($this->tables['promocode_usage_history']);
                                $use_status = $query3->row();

                                if ($use_status->total_used <= 0) {
                                    $this->db->select('COUNT(promocode_id) AS total_used');
                                    $this->db->where(['promocode_id' => $promo->promocode_id, 'is_used' => 1]);
                                    $query4 = $this->db->get($this->tables['promocode_usage_history']);
                                    $use_status = $query4->row();
                                    
                                    if ($use_status->total_used > 0) {
                                        if($use_status->total_used < $promo->number_of_time_usage) {
                                            $promocode_array[] = $promo;
                                        }
                                    } else {
                                        $promocode_array[] = $promo;
                                    }
                                } else {
                                    if ($use_status->total_used < $promo->per_user) {
                                        $promocode_array[] = $promo;
                                    }
                                }
                            } else {
                                $this->db->select('COUNT(promocode_id) AS total_used');
                                $this->db->where(['promocode_id' => $promo->promocode_id, 'is_used' => 1]);
                                $query5 = $this->db->get($this->tables['promocode_usage_history']);
                                $use_status = $query5->row();

                                if ($use_status->total_used > 0) {
                                    if($use_status->total_used < $promo->number_of_time_usage) {
                                        $promocode_array[] = $promo;
                                    }
                                } else {
                                    $promocode_array[] = $promo;
                                }
                            }
                        }

                        if (!empty($promocode_array)) {
                            $restaurant->promocodes = $promocode_array;
                            $restaurants[] = $restaurant;
                        }
                    }
                }
            }

        }

        return $restaurants;
    }

    public function restaurant_promocodes($restaurant_id, $user_id)
    {
        $this->db->select($this->tables['restaurants'].'.id, restaurant_name, delivery_radius');
        $this->db->where($this->tables['restaurants'].'.active', 1);
        $this->db->where($this->tables['restaurants'].'.id', $restaurant_id);
        $query = $this->db->get($this->tables['restaurants']);

        if ($query->num_rows() > 0) {
            $restaurant = $query->row();
            
            $this->db->select('promocode_id, promocode, promocode_title, promocode_description, number_of_time_usage, promocode_type, discount, expiry_date, per_user');
            $this->db->where('expiry_date >=', date('Y-m-d'));
            $this->db->where('is_expired', 0);
            $this->db->where('restaurant_id', $restaurant->id);
            $this->db->order_by('expiry_date','ASC');
            $query2 = $this->db->get($this->tables['promocodes']);

            if ($query2->num_rows() > 0) {
                if (!empty($query2->result())) {
                    $promocodes = $query2->result();
                    $promocode_array = [];
                    foreach ($promocodes as $key => $promo) {
                        if (!empty($user_id)) {
                            $this->db->select('COUNT(promocode_id) AS total_used');
                            $this->db->where(['promocode_id' => $promo->promocode_id, 'user_id'=> $user_id, 'is_used' => 1]);
                            $query3 = $this->db->get($this->tables['promocode_usage_history']);
                            $use_status = $query3->row();

                            if ($use_status->total_used <= 0) {
                                
                                $this->db->select('COUNT(promocode_id) AS total_used');
                                $this->db->where(['promocode_id' => $promo->promocode_id, 'is_used' => 1]);
                                $query4 = $this->db->get($this->tables['promocode_usage_history']);
                                $use_status = $query4->row();
                                
                                if ($use_status->total_used > 0) {
                                    if($use_status->total_used < $promo->number_of_time_usage) {
                                        $promocode_array[] = $promo;
                                    }
                                } else {
                                    $promocode_array[] = $promo;
                                }
                            } else {
                                if ($use_status->total_used < $promo->per_user) {
                                    $promocode_array[] = $promo;
                                }
                            }
                        } else {
                            $this->db->select('COUNT(promocode_id) AS total_used');
                            $this->db->where(['promocode_id' => $promo->promocode_id, 'is_used' => 1]);
                            $query5 = $this->db->get($this->tables['promocode_usage_history']);
                            $use_status = $query5->row();

                            if ($use_status->total_used > 0) {
                                if($use_status->total_used < $promo->number_of_time_usage) {
                                    $promocode_array[] = $promo;
                                }
                            } else {
                                $promocode_array[] = $promo;
                            }
                        }
                    }

                    if (!empty($promocode_array)) {
                        $restaurant->promocodes = $promocode_array;
                    }
                }
            }
            
            return $restaurant;
        }

        return false;
    }

    public function check_promocode($promocode, $restaurant_id, $user_id)
    {
        $this->db->select('*');
        $this->db->where('promocode', $promocode);
        $this->db->where('restaurant_id', $restaurant_id);
        $this->db->where('is_expired', 0);
        $this->db->where('expiry_date >= ', date('Y-m-d'));
        $query = $this->db->get($this->tables['promocodes']);
        
        if(!empty($query->row())){
            $result = $query->row();
            
            $this->db->select('COUNT(promocode_id) AS total_usage');
            $this->db->where('promocode_id', $result->promocode_id);
            $this->db->where('restaurant_id', $restaurant_id);
            $this->db->where('is_used', 1);
            $count_data = $this->db->get($this->tables['promocode_usage_history'])->row();
            
            if ($count_data->total_usage < $result->number_of_time_usage) {   
                return $result;
            } else {
                $this->db->update('promocodes', ['is_expired' => 1], ['promocode_id' => $result->promocode_id]);
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    public function get_promocode_usage_count( $number_of_time_usage, $promocode, $promocode_id, $user_id)
    {
        $this->db->select('COUNT(promocode_id) AS total_usage');
        $this->db->where('promocode_id', $promocode_id);
        $this->db->where('is_used', 1);
        $count_data = $this->db->get($this->tables['promocode_usage_history'])->row();
        
        if($count_data->total_usage < $number_of_time_usage){
            return array('status' => 1,'count' => $number_of_time_usage - $count_data->total_usage);
        }else{
            return array('status' => 0,'count' => 0);
        }
    }

    public function check_user_usage($promocode_id, $user_id)
    {
        $this->db->select('COUNT(promocode_id) AS total_usage');
        $this->db->where('promocode_id',$promocode_id);
        $this->db->where('user_id',$user_id);
        $this->db->where('is_used',1);
        $count_data = $this->db->get($this->tables['promocode_usage_history'])->row();
        
        return $count_data->total_usage;
    }

    public function apply_promocode($promocode,$promocode_id,$user_id, $restaurant_id)
    {
        $data = [
            'promocode'     => $promocode,
            'user_id'       => $user_id,
            'promocode_id'  => $promocode_id,
            'restaurant_id' => $restaurant_id
        ];
        $this->db->insert($this->tables['promocode_usage_history'], $data);
        return $this->db->insert_id();
    }
}