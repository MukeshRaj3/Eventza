<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'cities' => 'cities',
        	'party' => 'party',
        	'party_type' => 'party_type',
        	'organization' => 'organization',
        	'users' => 'users',
        	'user_view_plans' => 'user_view_plans',
        	'view_subscriptions' => 'view_subscriptions'
        ];
        $this->limit = 20;
        $this->radius_value = getenv('RADIUS_VALUE'); /* For Miles 3959 , For KM 6371 */
        $this->radius_distance = getenv('RADIUS_RANGE');
    }

    public function get_user_view_plan_details($user_id) {
        return $this->db->get_where($this->tables['user_view_plans'], ['user_id' => $user_id, 'status' => 1])->row();
    }

    public function get_view_subscriptions() {
        $this->db->select('*');
    	$query = $this->db->get($this->tables['view_subscriptions']);
    	if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_all_banners() {
    	$this->db->select($this->tables['banners'].'.*');
        $this->db->where($this->tables['banners'].'.is_active_banner', 1);
        $this->db->order_by($this->tables['banners'].'.order_number', 'ASC');
    	$query = $this->db->get($this->tables['banners']);

    	if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    public function get_percentage_off($latitude, $longitude) {
        if (!empty($latitude) && !empty($longitude)) {
            $this->db->select($this->tables['restaurants'].'.offer_percentage,'.$this->tables['restaurants'].'.delivery_radius, ( '.$this->radius_value.' * acos( cos( radians('.$latitude.') ) * cos( radians( '.$this->tables['restaurants'].'.restaurant_latitude ) ) * cos( radians( '.$this->tables['restaurants'].'.restaurant_longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( '.$this->tables['restaurants'].'.restaurant_latitude ) ) ) ) AS distance');
            $this->db->where($this->tables['restaurants'].'.active', 1);
            $this->db->where($this->tables['restaurants'].'.offer_percentage >', 0);
            $this->db->having('distance <=', $this->radius_distance);

            $query = $this->db->get($this->tables['restaurants']);

            if ($query->num_rows() > 0) {
                $data = $query->result();

                $discount_array = [];
                foreach ($data as $key => $row) {
                    if ($row->distance <= $row->delivery_radius) {
                        $discount_array[] = $row->offer_percentage;
                    }
                }

                return ['minimum' => min($discount_array), 'maximum' => max($discount_array)];
            }
        } else {
            $this->db->select('MIN('.$this->tables['restaurants'].'.offer_percentage'.') AS minimum, MAX('.$this->tables['restaurants'].'.offer_percentage'.') AS maximum');
            $this->db->where($this->tables['restaurants'].'.active', 1);
            $this->db->where($this->tables['restaurants'].'.offer_percentage >', 0);
            $query = $this->db->get($this->tables['restaurants']);

            if ($query->num_rows() > 0) {
                $data = $query->row();

                if (!empty($data->minimum)) {
                    return $data;
                } else {
                    return false;
                }
            }
        }
        
        return false;
    }

    public function get_filter_categories($latitude, $longitude) {
        $this->db->select($this->tables['main_cuisines'].'.name, id');
        $this->db->where($this->tables['main_cuisines'].'.is_active', 1);
        $query = $this->db->get($this->tables['main_cuisines']);

        if ($query->num_rows() > 0) {
            $categories = $query->result();
            $data = [];
            foreach ($categories as $key => $category) {
                /* If Required to filter near by resturant */
                if (!empty($latitude) && !empty($longitude)) {
                    $this->db->select($this->tables['restaurants'].'.delivery_radius,'.$this->tables['restaurant_cuisine'].'.restaurant_id, ( '.$this->radius_value.' * acos( cos( radians('.$latitude.') ) * cos( radians( '.$this->tables['restaurants'].'.restaurant_latitude ) ) * cos( radians( '.$this->tables['restaurants'].'.restaurant_longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( '.$this->tables['restaurants'].'.restaurant_latitude ) ) ) ) AS distance');
                    $this->db->join($this->tables['restaurants'], $this->tables['restaurants'].'.id = '.$this->tables['restaurant_cuisine'].'.restaurant_id');
                    $this->db->where($this->tables['restaurant_cuisine'].'.main_cuisine_id', $category->id);
                    $this->db->where($this->tables['restaurant_cuisine'].'.is_cuisine_deleted', 0);
                    $this->db->where($this->tables['restaurant_cuisine'].'.cuisine_is_active', 1);
                    $this->db->where($this->tables['restaurants'].'.active', 1);
                    $this->db->group_by($this->tables['restaurant_cuisine'].'.restaurant_id');
                    $this->db->having('distance <=', $this->radius_distance);
                    $query = $this->db->get($this->tables['restaurant_cuisine']);
                    
                    if ($query->num_rows() > 0) {
                        $data[] = [
                            'total_restaurant' => $query->num_rows(),
                            'name' => $category->name,
                            'id' => $category->id
                        ];
                    }
                } else {
                    $this->db->select($this->tables['restaurant_cuisine'].'.restaurant_id');
                    $this->db->join($this->tables['restaurants'], $this->tables['restaurants'].'.id = '.$this->tables['restaurant_cuisine'].'.restaurant_id');
                    $this->db->where($this->tables['restaurant_cuisine'].'.main_cuisine_id', $category->id);
                    $this->db->where($this->tables['restaurant_cuisine'].'.is_cuisine_deleted', 0);
                    $this->db->where($this->tables['restaurant_cuisine'].'.cuisine_is_active', 1);
                    $this->db->where($this->tables['restaurants'].'.active', 1);
                    $this->db->group_by($this->tables['restaurant_cuisine'].'.restaurant_id');
                    $query = $this->db->get($this->tables['restaurant_cuisine']);
                }
            }
            
            if (!empty($data)) {
                return $data;
            }

            return false;
        }
        
        return false;
    }

    public function popular_cities($offset) {
        $this->db->select($this->tables['cities'].'.*');
        $this->db->where($this->tables['cities'].'.is_popular', 1);
        $this->db->order_by($this->tables['cities'].'.id', 'ASC');
        $this->db->limit($this->limit, $offset);
    	$query = $this->db->get($this->tables['cities']);
    	if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }
    
    
    
    public function near_by_users($latitude, $longitude, $user_id,$city_id,$state) {
        /* If Required to filter near by user */
          $city_rre=strtolower($city_id);
          //  $city_arr=array("noida","greater noida","gurgaon","ghaziabad","faridabad","alipur","bawana","deoli","karol bagh","najafgarh","narela","nangloi jat","pitampura","rohini","dehli");
          // if(in_array($city_rre,$city_arr))
          // {
          //   $state='Delhi';
          //   $state_status=1;
          // }else{
          //   $state_status=2;
          // }

         
        $city_arr=array("noida","greater noida","gurgaon","ghaziabad","faridabad","alipur","bawana","deoli","karol bagh","najafgarh","narela","nangloi jat","pitampura","rohini","delhi","central delhi","east delhi","new delhi","north delhi","north east delhi","north west delhi","south delhi","south west delhi","west delhi");
          if(!in_array($city_rre,$city_arr))
           {
            $city=$city_rre;
           }else{
            $city=implode('","',$city_arr);
          }

         // echo $state_status; die;
        if (!empty($latitude) && !empty($longitude)) {
            $this->db->select($this->tables['users'].'.*, ( '.$this->radius_value.' * acos( cos( radians('.$latitude.') ) * cos( radians( '.$this->tables['users'].'.latitude ) ) * cos( radians( '.$this->tables['users'].'.longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( '.$this->tables['users'].'.latitude ) ) ) ) AS distance');
            if (!empty($filter)) {
            $this->db->or_where($this->tables['users'].'.phone', $filter);

            $this->db->or_where($this->tables['users'].'.username !=', $filter);

            }
            $this->db->where($this->tables['users'].'.is_deleted', 0);
            $this->db->where($this->tables['users'].'.is_suspended', 0);
            $this->db->where($this->tables['users'].'.active', 1);
            $this->db->where($this->tables['users'].'.id !=', $user_id);
            $this->db->having('distance <=', $this->radius_distance);
            $this->db->order_by($this->tables['users'].'.id', 'RANDOM');
            $query = $this->db->get($this->tables['users']);
            
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
            if (empty($data)) {
                $this->db->select($this->tables['users'].'.*');
                $this->db->where($this->tables['users'].'.is_deleted', 0);
                $this->db->where($this->tables['users'].'.is_suspended', 0);
                $this->db->where($this->tables['users'].'.active', 1);
                $this->db->where($this->tables['users'].'.type','Individual');
                $this->db->where($this->tables['users'].'.id !=', $user_id);
                $this->db->order_by($this->tables['users'].'.id', 'RANDOM');
                $query = $this->db->get($this->tables['users']);
                
                if ($query->num_rows() > 0) {
                    $data = $query->result();
                }
            }
        } else if (!empty($city_id)) { 
            $this->db->select($this->tables['users'].'.*');
            $this->db->join($this->tables['organization'], $this->tables['organization'].'.user_id =' .$this->tables['users'].'.id');
            $this->db->where($this->tables['users'].'.is_deleted', 0);
            $this->db->where($this->tables['users'].'.is_suspended', 0);
            $this->db->where($this->tables['users'].'.active', 1);
            $this->db->where($this->tables['users'].'.id !=', $user_id);
            $this->db->where($this->tables['users'].'.type','Individual');
            //if($state_status=='2')
            //{
            //$this->db->where($this->tables['organization'].'.IN(city',$city_id,'both');

            $where=$this->tables['organization'].'.city IN("'.$city.'")';
            $this->db->where($where);


           // }else{
           // $this->db->like($this->tables['organization'].'.state',$state);
            // }
            //$where=$this->tables['organization'].'.city IN("'.$city.'")';
            //$this->db->where($where);
            
            //$this->db->order_by($this->tables['users'].'.id', 'RANDOM');
            $this->db->order_by($this->tables['users'].'.online_status','on');
            $this->db->order_by($this->tables['users'].'.privacy_online ','Yes');

            $query = $this->db->get($this->tables['users']);
            //echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }else{
            $this->db->select($this->tables['users'].'.*');
            $this->db->join($this->tables['organization'], $this->tables['organization'].'.user_id =' .$this->tables['users'].'.id');
            $this->db->where($this->tables['users'].'.is_deleted', 0);
            $this->db->where($this->tables['users'].'.is_suspended', 0);
            $this->db->where($this->tables['users'].'.active', 1);
            $this->db->where($this->tables['users'].'.id !=', $user_id);
            //$this->db->where($this->tables['organization'].'.city', $city_id);
            $this->db->where($this->tables['users'].'.type','Individual');
            //$this->db->like($this->tables['organization'].'.city', $city_id);
              if($state_status=='2')
            {
            $this->db->like($this->tables['organization'].'.city',$city_id,'both');
                             
            }
            $this->db->like($this->tables['organization'].'.state',$state);

            $this->db->order_by($this->tables['users'].'.online_status','on');
            $this->db->order_by($this->tables['users'].'.privacy_online ','Yes');
            $query = $this->db->get($this->tables['users']);
            
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }
        
        if (!empty($data)) {
            return $data;
        }
        return false;
    }


    public function get_search_users($keyword,$user_id,$gender)
    {
        $this->db->select($this->tables['users'].'.*,'.$this->tables['organization'].'.profile_pic,gender');
            $this->db->join($this->tables['organization'], $this->tables['organization'].'.user_id =' .$this->tables['users'].'.id');
            $this->db->where($this->tables['users'].'.is_deleted', 0);
            $this->db->where($this->tables['users'].'.is_suspended', 0);
            $this->db->where($this->tables['users'].'.active', 1);
            $this->db->where($this->tables['users'].'.id !=', $user_id);
            $this->db->where($this->tables['users'].'.type','Individual');
            if(!empty($gender))
            {
            $this->db->where($this->tables['organization'].'.gender',$gender);    
            }
            $this->db->like($this->tables['users'].'.username',$keyword,'both');
            $this->db->order_by($this->tables['users'].'.online_status','on');
            $this->db->order_by($this->tables['users'].'.privacy_online ','Yes');

            $query = $this->db->get($this->tables['users']);
            //echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {
                $data = $query->result();
                return $data;
            }else{
                return FALSE;
            }
    }
    
    
    public function get_today_party($latitude, $longitude, $user_id, $offset, $organisation,$city_id) {

        //echo strtotime('today'); die;
        /* If Required to filter near by user */
        if (!empty($latitude) && !empty($longitude)) {
            $this->db->select($this->tables['party'].'.*, ( '.$this->radius_value.' * acos( cos( radians('.$latitude.') ) * cos( radians( '.$this->tables['party'].'.latitude ) ) * cos( radians( '.$this->tables['party'].'.longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( '.$this->tables['party'].'.latitude ) ) ) ) AS distance,' .$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
            $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id', 'left');
            $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
            $this->db->where($this->tables['party'].'.is_deleted', 0);
            $this->db->where($this->tables['party'].'.active', 1);
            $this->db->where($this->tables['party'].'.organization_id', $organisation);
            $this->db->where($this->tables['party'].'.start_date <=', strtotime('today'));
            $this->db->where($this->tables['party'].'.end_date >=', strtotime('today'));
            
            $this->db->having('distance <=', $this->radius_distance);
            $this->db->order_by($this->tables['party'].'.end_date', 'ASC');
            $this->db->limit($this->limit, $offset);
            $query = $this->db->get($this->tables['party']);
            
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
            if (empty($data)) {
                $this->db->select($this->tables['party'].'.*,' .$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
                $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id', 'left');
                $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
                $this->db->where($this->tables['party'].'.is_deleted', 0);
                $this->db->where($this->tables['party'].'.active', 1);
                $this->db->where($this->tables['party'].'.organization_id', $organisation);
                $this->db->where($this->tables['party'].'.start_date <=', strtotime('today'));
                $this->db->where($this->tables['party'].'.end_date >=', strtotime('today'));
                $this->db->order_by($this->tables['party'].'.end_date', 'ASC');
                $this->db->limit($this->limit, $offset);
                $query = $this->db->get($this->tables['party']);
                
                if ($query->num_rows() > 0) {
                    $data = $query->result();
                }
            }
        } else if(!empty($city_id)){

             $this->db->select($this->tables['party'].'.*,' .$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
            $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id', 'left');
            $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
            $this->db->where($this->tables['party'].'.is_deleted', 0);
            $this->db->where($this->tables['party'].'.active', 1);
            $this->db->where($this->tables['party'].'.organization_id', $organisation);
            $this->db->where($this->tables['party'].'.start_date <=', strtotime('today'));
            $this->db->where($this->tables['users'].'.city_id',$city_id);
            $this->db->order_by($this->tables['party'].'.end_date', 'ASC');
            $this->db->limit($this->limit, $offset);
            $query = $this->db->get($this->tables['party']);
            //echo $this->db->last_query(); die;
            
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }else{
            $this->db->select($this->tables['party'].'.*,' .$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
            $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id', 'left');
            $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
            $this->db->where($this->tables['party'].'.is_deleted', 0);
            $this->db->where($this->tables['party'].'.active', 1);
            $this->db->where($this->tables['party'].'.organization_id', $organisation);
            $this->db->where($this->tables['party'].'.start_date <=', strtotime('today'));
            $this->db->where($this->tables['party'].'.end_date >=', strtotime('today'));
            $this->db->order_by($this->tables['party'].'.end_date', 'ASC');
            $this->db->limit($this->limit, $offset);
            $query = $this->db->get($this->tables['party']);
            
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }
        
        if (!empty($data)) {
            foreach ($data as $key => $value) {
              
                if($value->image_status=='1')
                {
                  $data[$key]->cover_photo=$value->cover_photo;
                }else{
                  $data[$key]->cover_photo=''; 
                }
                $types = $value->type;
                $typeData = explode(',', $types);
                $row = [];
                foreach ($typeData as $key1 => $type) {
                    $type = $this->db->get_where($this->tables['party_type'], ['id' => $type])->row();
                    $row[] = @$type->name;
                }
                if (!empty($row)) {
                    $value->type = implode(',', $row);
                    
                }
                
                // $data[$key][]
            }
            return $data;
        }
        return false;
    }
    
    public function get_tomorrow_party($latitude, $longitude, $user_id, $offset, $organisation,$city_id) {

        /* If Required to filter near by user */
        if (!empty($latitude) && !empty($longitude)) {
            $this->db->select($this->tables['party'].'.*, ( '.$this->radius_value.' * acos( cos( radians('.$latitude.') ) * cos( radians( '.$this->tables['party'].'.latitude ) ) * cos( radians( '.$this->tables['party'].'.longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( '.$this->tables['party'].'.latitude ) ) ) ) AS distance,' .$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
            $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id', 'left');
            $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
            $this->db->where($this->tables['party'].'.is_deleted', 0);
            $this->db->where($this->tables['party'].'.active', 1);
            $this->db->where($this->tables['party'].'.organization_id', $organisation);
            $this->db->where($this->tables['party'].'.start_date <=', strtotime('tomorrow'));
            $this->db->where($this->tables['party'].'.end_date >=', strtotime('tomorrow'));
            // $this->db->where($this->tables['party'].'.end_date >=', date('d-m-Y', strtotime("+1 day")));
            $this->db->having('distance <=', $this->radius_distance);
            $this->db->order_by($this->tables['party'].'.end_date', 'ASC');
            $this->db->limit($this->limit, $offset);
            $query = $this->db->get($this->tables['party']);
            
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
            if (empty($data)) {
                $this->db->select($this->tables['party'].'.*,' .$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
                $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id', 'left');
                $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
                $this->db->where($this->tables['party'].'.is_deleted', 0);
                $this->db->where($this->tables['party'].'.active', 1);
                $this->db->where($this->tables['party'].'.organization_id', $organisation);
                $this->db->where($this->tables['party'].'.start_date <=', strtotime('tomorrow'));
                $this->db->where($this->tables['party'].'.end_date >=', strtotime('tomorrow'));
                // $this->db->where($this->tables['party'].'.end_date >=', date('d-m-Y', strtotime("+1 day")));
                $this->db->order_by($this->tables['party'].'.end_date', 'ASC');
                $this->db->limit($this->limit, $offset);
                $query = $this->db->get($this->tables['party']);
               
                if ($query->num_rows() > 0) {
                    $data = $query->result();
                }
            }
        }else if(!empty($city_id)){

           $this->db->select($this->tables['party'].'.*,' .$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
            $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id', 'left');
            $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
            $this->db->where($this->tables['party'].'.is_deleted', 0);
            $this->db->where($this->tables['party'].'.active', 1);
            $this->db->where($this->tables['party'].'.organization_id', $organisation);
            $this->db->where($this->tables['party'].'.start_date <=', strtotime('tomorrow'));
            $this->db->where($this->tables['party'].'.end_date >=', strtotime('tomorrow'));
            $this->db->where($this->tables['users'].'.city_id',$city_id);
            $this->db->order_by($this->tables['party'].'.end_date', 'ASC');
            $this->db->limit($this->limit, $offset);
            $query = $this->db->get($this->tables['party']);
            // echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }else{
            $this->db->select($this->tables['party'].'.*,' .$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
            $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id', 'left');
            $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
            $this->db->where($this->tables['party'].'.is_deleted', 0);
            $this->db->where($this->tables['party'].'.active', 1);
            $this->db->where($this->tables['party'].'.organization_id', $organisation);
            $this->db->where($this->tables['party'].'.start_date <=', strtotime('tomorrow'));
            $this->db->where($this->tables['party'].'.end_date >=', strtotime('tomorrow'));
            $this->db->order_by($this->tables['party'].'.end_date', 'ASC');
            $this->db->limit($this->limit, $offset);
            $query = $this->db->get($this->tables['party']);
            
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }
        
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                 if($value->image_status=='1')
                {
                  $data[$key]->cover_photo=$value->cover_photo;
                }else{
                  $data[$key]->cover_photo=''; 
                }
                $types = $value->type;
                $typeData = explode(',', $types);
                $row = [];
                foreach ($typeData as $key1 => $type) {
                    $type = $this->db->get_where($this->tables['party_type'], ['id' => $type])->row();
                    $row[] = $type->name;
                }
                if (!empty($row)) {
                    $value->type = implode(',', $row);   
                }
                $genders = $value->gender;
                $gend = explode(',', $genders);
                
            }
            return $data;
        }
        return false;
    }

    public function party_by_cities($latitude, $longitude, $offset) {
        /* If Required to filter near by user */
        if (!empty($latitude) && !empty($longitude)) {
            $this->db->select($this->tables['party'].'.*, ( '.$this->radius_value.' * acos( cos( radians('.$latitude.') ) * cos( radians( '.$this->tables['party'].'.latitude ) ) * cos( radians( '.$this->tables['party'].'.longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( '.$this->tables['party'].'.latitude ) ) ) ) AS distance');
            $this->db->where($this->tables['party'].'.is_deleted', 0);
            $this->db->where($this->tables['party'].'.active', 1);
            $this->db->where($this->tables['party'].'.end_date >=', strtotime('today'));
            $this->db->having('distance <=', $this->radius_distance);
            $this->db->order_by($this->tables['party'].'.end_date', 'ASC');
            $this->db->limit($this->limit, $offset);
            $query = $this->db->get($this->tables['party']);
            if ($query->num_rows() > 0) {
                $data = $query->result();
            }
        }
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                 if($value->image_status=='1')
                {
                  $data[$key]->cover_photo=$value->cover_photo;
                }else{
                  $data[$key]->cover_photo=''; 
                }
                $types = $value->type;
                $typeData = explode(',', $types);
                $row = [];
                foreach ($typeData as $key1 => $type) {
                    $type = $this->db->get_where($this->tables['party_type'], ['id' => $type])->row();
                    $row[] = $type->name;
                }
                if (!empty($row)) {
                    $value->type = implode(',', $row);   
                }
                $genders = $value->gender;
                $gend = explode(',', $genders);
                
            }
            return $data;
        }
        return false;
    }

    public function party_details($party_id) {
        /* If Required to filter near by user */
        $this->db->select($this->tables['party'].'.*,' .$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
        $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id', 'left');
        $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
        $this->db->where($this->tables['party'].'.id', $party_id);
        $query = $this->db->get($this->tables['party']);
        if ($query->num_rows() > 0) {
            $data =  $query->row();
        }
        if (!empty($data)) {
            $types = $data->type;
            $typeData = explode(',', $types);
            $row = [];
            foreach ($typeData as $key1 => $type) {
                $type = $this->db->get_where($this->tables['party_type'], ['id' => $type])->row();
                $row[] = $type->name;
            }
            if (!empty($row)) {
                $data->type = implode(',', $row);   
            }
            $genders = $data->gender;
            $gend = explode(',', $genders);
            return $data;
        }
        return false;
    }

    public function get_city_details($city_id) {
        $this->db->select($this->tables['cities'].'.*');
        $this->db->where($this->tables['cities'].'.id', $city_id);
        $query = $this->db->get($this->tables['cities']);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }
}