<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Party_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tables = [
            'party'         => 'party',
            'organization'  => 'organization',
            'party_type'    => 'party_type',
            'users'         => 'users',
            'join_users'    => 'join_users',
            'cities'        => 'cities',
            'host_subscriptions' => 'host_subscriptions',
            'view_subscriptions' => 'view_subscriptions',
            'organization_amenities' => 'organization_amenities',
            'party_amenities' => 'party_amenities',
            'organization_verified_pdf' => 'organization_verified_pdf',
            'organization_pdf' => 'organization_pdf',
            'banners' => 'banners',
            'party_category' => 'party_category',
            'notifications' => 'notifications',
            'individual'=>'individual',
            'individual_like'=>'individual_like',
            'organization_category' => 'organization_category',
            'party_wish_list'=>'party_wish_list'
            
            
        ];
    }

    public function insert_party($data)
    {
        return $this->db->insert($this->tables['party'], $data);
    }

    public function get_party_by_id($party_id)
    {
        $res = $this->db->get_where($this->tables['party'], ['id' => $party_id])->row();
        if (!empty($res)) {
            if (!empty($res->party_amenitie_id)) {
                $sql = $this->db->query('SELECT * FROM party_amenities WHERE id IN(' . $res->party_amenitie_id . ')');
                $res_pimm = $sql->result_array();
                if (!empty($res_pimm)) {
                    $res->party_amenities = $res_pimm;
                } else {
                    $res->party_amenities = $res_pimm;
                }
            } else {
                $res->party_amenities = '';
            }

            return $res;
        } else {
            return false;
        }
    }

    public function get_party_by_id_09_02_2023($party_id)
    {
        return $this->db->get_where($this->tables['party'], ['id' => $party_id])->row();
    }

    public function get_user_details($user_id)
    {
        $this->db->select($this->tables['users'] . '.*, (DATEDIFF(CURRENT_DATE, STR_TO_DATE(dob, "%d-%m-%Y"))/365.25) as age');
        $this->db->where([$this->tables['users'] . '.id' => $user_id]);
        return $this->db->get($this->tables['users'])->row();
    }

    public function join_party($user_id, $party_id)
    {
        return $this->db->insert($this->tables['join_users'], ['user_id' => $user_id, 'party_id' => $party_id]);
    }

    public function get_joined_details($user_id, $party_id)
    {
        return $this->db->get_where($this->tables['join_users'], ['user_id' => $user_id, 'party_id' => $party_id])->row();
    }

    public function get_join_users($party_id)
    {
        return $this->db->get_where($this->tables['join_users'], ['party_id' => $party_id])->num_rows();
    }

    public function update_party($data, $id)
    {
        return $this->db->update($this->tables['party'], $data, ['id' => $id]);
    }


    public function insert_organization($data)
    {

        $res = $this->db->get_where($this->tables['organization'], ['user_id' => $data['user_id']])->result();
        if (empty($res)) {
            return $this->db->insert($this->tables['organization'], $data);
        } else {
            return FALSE;
        }
    }

    public function get_organization_by_id_09_02_2023($organization_id)
    {
        return $this->db->get_where($this->tables['organization'], ['id' => $organization_id])->row();
    }

    public function get_organization_by_id($user_id)
    {
        $res = $this->db->get_where($this->tables['organization'], ['user_id' => $user_id, 'is_deleted' => '0'])->result();
        if (!empty($res)) {
            foreach ($res as $key => $value) {
                $sql = $this->db->query('SELECT * FROM organization_amenities WHERE id IN(' . $value->org_amenitie_id . ')');
                $res_oimm = $sql->result_array();
                if (!empty($res_oimm)) {
                    $res[$key]->organization_amenities = $res_oimm;
                } else {
                    $res[$key]->organization_amenities = $res_oimm;
                }
            }
            return $res;
        }
        return false;
    }



    public function update_organization($data, $id)
    {
        return $this->db->update($this->tables['organization'], $data, ['id' => $id]);
    }

    public function get_user_all_individual_party($user_id,$user_type=null)
    {
        $this->db->select($this->tables['party'] . '.*,' . $this->tables['users'] . '.username AS full_name,' . $this->tables['users'] . '.profile_picture,'.$this->tables['organization'] .'.rating,like,view,ongoing');

        $this->db->join($this->tables['users'], $this->tables['users'] . '.id =' . $this->tables['party'] . '.user_id');
        $this->db->join($this->tables['organization'], $this->tables['organization'] . '.id =' . $this->tables['party'] . '.organization_id');

        $this->db->where([$this->tables['party'] . '.user_id' => $user_id, $this->tables['party'] . '.organization_id' => 0]);
        //$this->db->where([$this->tables['party'] . '.active' => 1]);
        $this->db->where([$this->tables['party'] . '.start_time <=' => date('H:i:s')]);

        $this->db->where([$this->tables['party'] . '.end_time >=' => date('H:i:s')]);
        if($user_type){
            $this->db->where([$this->tables['party'] . '.user_type' => $user_type ]);
        }
        $this->db->order_by($this->tables['party'] . '.id', 'DESC');
        $query = $this->db->get($this->tables['party']);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_user_organization_party_by_id_24_02_2023($user_id, $organization_id)
    {
        $this->db->select($this->tables['party'] . '.*,' . $this->tables['organization'] . '.name AS organization,' . $this->tables['users'] . '.first_name AS full_name,' . $this->tables['users'] . '.profile_picture');
        $this->db->join($this->tables['organization'], $this->tables['organization'] . '.id =' . $this->tables['party'] . '.organization_id');
        $this->db->join($this->tables['users'], $this->tables['users'] . '.id =' . $this->tables['party'] . '.user_id');
        $this->db->where([$this->tables['party'] . '.user_id' => $user_id, $this->tables['party'] . '.organization_id' => $organization_id]);

        $this->db->order_by($this->tables['party'] . '.id', 'DESC');
        $query = $this->db->get($this->tables['party']);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    // public function get_user_organization_party_by_id($user_id, $organization_id,$status) {
    //   $obj_res=array();  
    //   $current_date= strtotime(date('Y-m-d'));
    //   $tommorow_date = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
    //   $day_after_tommorow = date('Y-m-d', strtotime(date('Y-m-d') . ' +2 day'));
    // //   print_r($tommorow_date);exit;
    // //   print_r($day_after_tommorow);exit;
    //   $this->db->select($this->tables['party'].'.*,'.$this->tables['organization'].'.name AS organization,'.$this->tables['users'].'.first_name AS full_name,' .$this->tables['users'].'.profile_picture');
    //   $this->db->join($this->tables['organization'], $this->tables['organization'].'.id =' .$this->tables['party'].'.organization_id');
    //   $this->db->join($this->tables['users'], $this->tables['users'].'.id =' .$this->tables['party'].'.user_id');
    //   $this->db->where([$this->tables['party'].'.user_id' => $user_id, $this->tables['party'].'.organization_id' => $organization_id]);
    //   $this->db->where([$this->tables['party'].'.active' =>1]);

    //   if($status == 1) {
    //     $this->db->where([$this->tables['party'].'.start_date' =>$current_date]);
    //     //$this->db->where([$this->tables['party'].'.end_date >=' => $current_date]);



    //   } 
    //   if($status == 2) {
    //         $this->db->where([$this->tables['party'].'.start_date' =>strtotime($tommorow_date)]);
    //        //$this->db->where([$this->tables['party'].'.end_date >=' => strtotime($tommorow_date)]);



    //   } 
    //   if($status == 3) {
    //         $this->db->where([$this->tables['party'].'.start_date >=' => strtotime($day_after_tommorow)]);
    //       // $this->db->where([$this->tables['party'].'.end_date >=' => strtotime($day_after_tommorow)]);


    //   }
    //   $this->db->order_by($this->tables['party'].'.start_time', 'ASC');
    // //   $this->db->order_by($this->tables['party'].'.id', 'DESC');
    //   $query = $this->db->get($this->tables['party']);

    //   if ($query->num_rows() > 0) {
    //       $res=$query->result();
    //       foreach ($res as $key => $value) {
    //              $query_party_amenitie= $this->db->query("SELECT id,name FROM party_amenities WHERE id IN ($value->party_amenitie_id)")->result_array();
    //             if($query_party_amenitie) {
    //                   $res[$key]->party_amenitie= $query_party_amenitie;
    //               }else{
    //                  $res[$key]->party_amenitie=array();
    //               }
    //       }

    //         return $res;
    //     }
    //     return FALSE;
    // }
    public function get_user_organization_party_by_id($user_id, $organization_id, $status)
    {
        //date_default_timezoe_set("Asia/Kolkata"); 
        $obj_res = array();
        $current_date = strtotime(date('Y-m-d'));
        $tommorow_date = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        $day_after_tommorow = date('Y-m-d', strtotime(date('Y-m-d') . ' +2 day'));
        //   print_r($tommorow_date);exit;
        //   print_r($day_after_tommorow);exit;
        $this->db->select($this->tables['party'] . '.*,' . $this->tables['organization'] . '.name AS organization,' . $this->tables['users'] . '.first_name AS full_name,' . $this->tables['users'] . '.profile_picture');
        $this->db->join($this->tables['organization'], $this->tables['organization'] . '.id =' . $this->tables['party'] . '.organization_id');
        $this->db->join($this->tables['users'], $this->tables['users'] . '.id =' . $this->tables['party'] . '.user_id');
        $this->db->where([$this->tables['party'] . '.user_id' => $user_id, $this->tables['party'] . '.organization_id' => $organization_id]);
       // $this->db->where([$this->tables['party'] . '.active' => 1]);
        
        /*if ($status != 1 && $status != 2 && $status != 3 && $status != 5) {
            return false;
        }*/

        if ($status == 1) {
            //$this->db->where([$this->tables['party'] . '.start_date' => $current_date]); //27-04-2023
            $this->db->where(['DATE('.$this->tables['party'] . '.created_at)<=' =>date('Y-m-d',$current_date)]); //27-04-2023 New Changes
            $this->db->where([$this->tables['party'] . '.start_date >=' =>$current_date]); //27-04-2023 New Changes

            //$this->db->where([$this->tables['party'].'.end_date >=' => $current_date]);
            $this->db->where([$this->tables['party'] . '.papular_status' => 2]);
        }
        if ($status == 2) {
            //$this->db->where([$this->tables['party'] . '.start_date' => strtotime($tommorow_date)]); //27-04-2023

            $this->db->where(['DATE('.$this->tables['party'] . '.created_at)<=' => $tommorow_date]); //27-04-2023 New Changes

            $this->db->where([$this->tables['party'] . '.start_date >=' => strtotime($tommorow_date)]);  //27-04-2023 New Changes
            //$this->db->where([$this->tables['party'].'.end_date >=' => strtotime($tommorow_date)]);
            $this->db->where([$this->tables['party'] . '.papular_status' => 2]);
        }
        if ($status == 3) {
            $this->db->where([$this->tables['party'] . '.start_date >=' => strtotime($day_after_tommorow)]);

            $this->db->where(['DATE('.$this->tables['party'] . '.created_at)<=' => $day_after_tommorow]); //27-04-2023 New Changes

            // $this->db->where([$this->tables['party'].'.end_date >=' => strtotime($day_after_tommorow)]);
            $this->db->where([$this->tables['party'] . '.papular_status' => 2]);
        }

        if ($status == 5) {
            $this->db->where([$this->tables['party'] . '.start_date >=' => $current_date]);
            $this->db->where(['DATE('.$this->tables['party'] . '.created_at)<=' =>date('Y-m-d',$current_date)]); //27-04-2023 New Changes
            
            $this->db->where([$this->tables['party'] . '.papular_status' => 1]);
            // $this->db->where([$this->tables['party'].'.end_date >=' => strtotime($day_after_tommorow)]);

        }
        $this->db->order_by($this->tables['party'] . '.start_time', 'ASC');
        //   $this->db->order_by($this->tables['party'].'.id', 'DESC');
        $query = $this->db->get($this->tables['party']);
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            $res = $query->result();
            foreach ($res as $key => $value) {
                $query_party_amenitie = $this->db->query("SELECT id,name FROM party_amenities WHERE id IN ($value->party_amenitie_id)")->result_array();
                if ($query_party_amenitie) {
                    $res[$key]->party_amenitie = $query_party_amenitie;
                } else {
                    $res[$key]->party_amenitie = array();
                }
            }

            return $res;
        }
        return FALSE;
    }
    public function party_type()
    {
        return $this->db->get($this->tables['party_type'])->result();
    }

    public function host_subscriptions()
    {
        return $this->db->get($this->tables['host_subscriptions'])->result();
    }

    public function view_subscriptions()
    {
        return $this->db->get($this->tables['view_subscriptions'])->result();
    }

    public function cities()
    {
        return $this->db->get($this->tables['cities'])->result();
    }

    public function party()
    {
        $current_date = strtotime(date('Y-m-d'));
        $this->db->select($this->tables['party'] . '.title,description,cover_photo,start_date,start_time,id');
        $this->db->where([$this->tables['party'] . '.start_date >=' => $current_date]);
        //$this->db->where([$this->tables['party'] . '.active' => 1]);

        $this->db->order_by($this->tables['party'] . '.id', 'DESC');
        $query = $this->db->get($this->tables['party']);
        if ($query->num_rows() > 0) {
            $res = $query->result();
            foreach ($res as $key => $value) {
                //$res[$key]->cover_photo=base_url().$value->cover_photo;
                /*$cover_photo = base_url($value->cover_photo);
        if(file_exists($cover_photo))
           {
            $image = base_url($value->cover_photo);
          }else{
             $image =base_url('assets/frameworks/admin/images/party_image.jpg');
          }
            $res[$key]->cover_photo=$image;*/
                $res[$key]->start_date = date('Y-m-d', $value->start_date);
            }
            return $res;
        }
        return FALSE;
    }

    public function organization_amenities()
    {
        //return $this->db->get($this->tables['organization_amenities'])->result();
        return $this->db->get_where($this->tables['organization_amenities'], ['org_cat_id' =>'0'])->result();

    }


    public function party_amenities()
    {
        $res = $this->db->get($this->tables['party_category'])->result();
        if (!empty($res)) {
            foreach ($res as $key => $value) {
                $this->db->select('id,name,type');
                $this->db->where([$this->tables['party_amenities'] . '.party_cat_id' => $value->id]);
                $query = $this->db->get($this->tables['party_amenities']);
                if ($query->num_rows() > 0) {
                    $amenities = $query->result();
                    $res[$key]->amenities = $amenities;
                } else {
                    $res[$key]->amenities = array();
                }
            }
        }

        return $res;
    }

    public function party_amenities_old()
    {
        $res = $this->db->get($this->tables['party_amenities'])->result();
        if (!empty($res)) {
            foreach ($res as $key => $value) {
                $this->db->select('name');
                $this->db->where([$this->tables['party_category'] . '.id' => $value->party_cat_id]);
                $query = $this->db->get($this->tables['party_category']);
                if ($query->num_rows() > 0) {
                    $res_cat = $query->result();
                    $res[$key]->category_name = $res_cat[0]->name;
                } else {
                    $res[$key]->category_name = '';
                }
            }
        }

        return $res;
    }

    public function web_regular_party()
    {
        $current_date = strtotime(date('Y-m-d'));
        $this->db->select($this->tables['party'] . '.title,description,cover_photo,start_date,start_time,id');
        $this->db->where([$this->tables['party'] . '.start_date >=' => $current_date]);
        $this->db->where([$this->tables['party'] . '.papular_status=' => 2]);
        //$this->db->where([$this->tables['party'] . '.active' => 1]);

        $this->db->order_by($this->tables['party'] . '.id', 'DESC');
        $query = $this->db->get($this->tables['party']);
        if ($query->num_rows() > 0) {
            $res = $query->result();
            foreach ($res as $key => $value) {
                //$res[$key]->cover_photo=base_url().$value->cover_photo;
                /*$cover_photo = base_url($value->cover_photo);
        if(file_exists($cover_photo))
           {
            $image = base_url($value->cover_photo);
          }else{
             $image =base_url('assets/frameworks/admin/images/party_image.jpg');
          }
            $res[$key]->cover_photo=$image;*/
                $res[$key]->start_date = date('Y-m-d', $value->start_date);
            }
            return $res;
        }
        return FALSE;
    }

    public function get_organisation($name)
    {
        return $this->db->get_where($this->tables['organization'], ['name' => $name, 'is_deleted' => '0'])->num_rows();
    }

    public function regular_papular_party_get($type)
    {
        $current_date = strtotime(date('Y-m-d'));
        $this->db->select($this->tables['party'] . '.title,description,cover_photo,start_date,start_time,id,like,view,ongoing');
        if ($type == 2) {
            $this->db->where([$this->tables['party'] . '.start_date >=' => $current_date]);
        }
        $this->db->where([$this->tables['party'] . '.papular_status=' => $type]);
        //$this->db->where([$this->tables['party'] . '.active' => 1]);
        $this->db->order_by($this->tables['party'] . '.id', 'DESC');
        $query = $this->db->get($this->tables['party']);
        if ($query->num_rows() > 0) {
            $res = $query->result();
            foreach ($res as $key => $value) {
                //$res[$key]->cover_photo=base_url().$value->cover_photo;
                /* $cover_photo = base_url($value->cover_photo);
        if(file_exists($cover_photo))
           {
            $image = base_url($value->cover_photo);
          }else{
             $image =base_url('assets/frameworks/admin/images/party_image.jpg');
          }
            $res[$key]->cover_photo=$image;*/
                $res[$key]->start_date = date('Y-m-d', $value->start_date);
            }
            return $res;
        }
        return FALSE;
    }

    public function organization_pdf_get()
    {

        $this->db->select($this->tables['organization_pdf'] . '.id,pdf_a,pdf_b');
        $this->db->where([$this->tables['organization_pdf'] . '.status' => 1]);
        $query = $this->db->get($this->tables['organization_pdf']);
        if ($query->num_rows() > 0) {
            $res = $query->result();
            return $res;
        }
        return FALSE;
    }

    public function get_organization_pdf_by_id($organization_id, $user_id)
    {
        $res = $this->db->get_where($this->tables['organization_verified_pdf'], ['organization_id' => $organization_id, 'user_id' => $user_id])->result();
        if (!empty($res)) {
            return $res;
        }
        return false;
    }

    public function update_organization_pdf($data, $user_id, $organization_id)
    {
        return $this->db->update($this->tables['organization_verified_pdf'], $data, ['user_id' => $user_id, 'organization_id' => $organization_id]);
    }

    public function insert_organization_pdf($data)
    {
        return $this->db->insert($this->tables['organization_verified_pdf'], $data);
    }

    public function get_all_banners()
    {
        $this->db->select($this->tables['banners'] . '.*');
        $this->db->where($this->tables['banners'] . '.is_active_banner', 1);
        $this->db->order_by($this->tables['banners'] . '.order_number', 'ASC');
        $query = $this->db->get($this->tables['banners']);

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    public function get_notification_count($user_id)
    {
        $res = $this->db->get_where($this->tables['notifications'], ['user_id' => $user_id, 'is_read' => '0'])->num_rows();
        return $res;
    }

    public function notification_read_status_update($user_id)
    {
        return $this->db->update($this->tables['notifications'], array('is_read' => '1'), ['user_id' => $user_id]);
    }

    public function delete_organization($data, $id)
    {
        return $this->db->update($this->tables['organization'], $data, ['id' => $id]);
    }

    /***Maruti***/
    public function insert_individual($data)
    {

        $res = $this->db->get_where($this->tables['individual'], ['user_id' => $data['user_id']])->num_rows();
        if (empty($res)) {
            return $this->db->insert($this->tables['individual'], $data);
        } else {
            return FALSE;
        }
    }

    /***Maruti***/
    public function get_individual_by_id($user_id)
    {
        $res = $this->db->get_where($this->tables['individual'], ['user_id' => $user_id, 'is_deleted' => '0'])->result();
        if (!empty($res)) {
            foreach ($res as $key => $value) {
                if(@$value && @$value->org_amenitie_id){
                    $sql = $this->db->query('SELECT * FROM individual_amenities WHERE id IN(' . $value->org_amenitie_id . ')');
                    $res_oimm = $sql->result_array();
                    if (!empty($res_oimm)) {
                        $res[$key]->individual_amenities = $res_oimm;
                    } else {
                        $res[$key]->individual_amenities = $res_oimm;
                    }
                }
            }
            return $res;
        }
        return false;
    }

    /***Maruti***/
    public function update_individual($data, $id)
    {
        return $this->db->update($this->tables['individual'], $data, ['id' => $id]);
    }

    public function get_user_individual_party_by_id($user_id, $individual_id, $status)
    {

        $obj_res = array();
        $current_date = strtotime(date('Y-m-d'));
        $tommorow_date = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        $day_after_tommorow = date('Y-m-d', strtotime(date('Y-m-d') . ' +2 day'));
        //   print_r($tommorow_date);exit;
        //   print_r($day_after_tommorow);exit;
        $this->db->select($this->tables['party'] . '.*,' . $this->tables['individual'] . '.name AS individual,' . $this->tables['users'] . '.first_name AS full_name,' . $this->tables['users'] . '.profile_picture');
        $this->db->join($this->tables['individual'], $this->tables['individual'] . '.id =' . $this->tables['party'] . '.individual_id');
        $this->db->join($this->tables['users'], $this->tables['users'] . '.id =' . $this->tables['party'] . '.user_id');
        $this->db->where([$this->tables['party'] . '.user_id' => $user_id, $this->tables['party'] . '.individual_id' => $individual_id]);
       // $this->db->where([$this->tables['party'] . '.active' => 1]);
        
        if ($status != 1 && $status != 2 && $status != 3 && $status != 5) {
            return false;
        }

        if ($status == 1) {
            $this->db->where([$this->tables['party'] . '.start_date' => $current_date]);
            //$this->db->where([$this->tables['party'].'.end_date >=' => $current_date]);
            $this->db->where([$this->tables['party'] . '.papular_status' => 2]);
        }
        if ($status == 2) {
            $this->db->where([$this->tables['party'] . '.start_date' => strtotime($tommorow_date)]);
            //$this->db->where([$this->tables['party'].'.end_date >=' => strtotime($tommorow_date)]);
            $this->db->where([$this->tables['party'] . '.papular_status' => 2]);
        }
        if ($status == 3) {
            $this->db->where([$this->tables['party'] . '.start_date >=' => strtotime($day_after_tommorow)]);
            // $this->db->where([$this->tables['party'].'.end_date >=' => strtotime($day_after_tommorow)]);
            $this->db->where([$this->tables['party'] . '.papular_status' => 2]);
        }

        if ($status == 5) {
            $this->db->where([$this->tables['party'] . '.start_date >=' => $current_date]);
            $this->db->where([$this->tables['party'] . '.papular_status' => 1]);
            // $this->db->where([$this->tables['party'].'.end_date >=' => strtotime($day_after_tommorow)]);

        }
        
        $this->db->where([$this->tables['party'] . '.active' =>'1']);

        $this->db->where([$this->tables['party'] . '.approval_status' =>'1']);

        $this->db->order_by($this->tables['party'] . '.start_time', 'ASC');
        //   $this->db->order_by($this->tables['party'].'.id', 'DESC');
        $query = $this->db->get($this->tables['party']);

        if ($query->num_rows() > 0) {
            $res = $query->result();
            foreach ($res as $key => $value) {
                $query_party_amenitie = $this->db->query("SELECT id,name FROM party_amenities WHERE id IN ($value->party_amenitie_id)")->result_array();
                if ($query_party_amenitie) {
                    $res[$key]->party_amenitie = $query_party_amenitie;
                } else {
                    $res[$key]->party_amenitie = array();
                }
            }

            return $res;
        }
        return FALSE;
    }
    public function update_profile_like($data)
    {
        $res = $this->db->get_where($this->tables['individual_like'], ['user_id' => $data['user_id'],'individual_id' => $data['individual_id']])->row();
        if (empty($res)) {
            return $this->db->insert($this->tables['individual_like'], $data);
        } else {
            return $this->db->update($this->tables['individual_like'], $data, ['id' => $res->id]);
        }
    }
    public function get_all_individual($user_id)
    {
        $obj_res = array();
        $this->db->select($this->tables['individual'] . '.*,' . $this->tables['users'] . '.first_name AS full_name,' . $this->tables['users'] . '.profile_picture,'.$this->tables['individual_like'].'.like_unlike');
        $this->db->join($this->tables['individual_like'], $this->tables['individual_like'] . '.individual_id =' . $this->tables['individual'] . '.id');
        $this->db->join($this->tables['users'], $this->tables['users'] . '.id =' . $this->tables['individual'] . '.user_id');
        $this->db->where([$this->tables['individual'] . '.user_id !=' => $user_id]);
        $this->db->order_by($this->tables['individual'].'.id', 'DESC');
        $query = $this->db->get($this->tables['individual']);

        if ($query->num_rows() > 0) {
            $res = $query->result();
            return $res;
        }
        return FALSE;
    }
    public function update_online_status($id,$data)
    {
        return $this->db->update($this->tables['individual'], $data, ['user_id' => $id]);
    }

    public function individual_organization_amenities()
    {
        $res = $this->db->get($this->tables['organization_category'])->result();
        if (!empty($res)) {
            foreach ($res as $key => $value) {
                $this->db->select('id,name,type');
                $this->db->where([$this->tables['organization_amenities'] . '.org_cat_id' => $value->id]);
                $query = $this->db->get($this->tables['organization_amenities']);
                if ($query->num_rows() > 0) {
                    $amenities = $query->result();
                    $res[$key]->amenities = $amenities;
                } else {
                    $res[$key]->amenities = array();
                }
            }
        }

        return $res;
    }

    public function get_all_individual_party($city,$status,$filter_type)
    {
        //date_default_timezoe_set("Asia/Kolkata"); 
        $obj_res = array();
        $current_date = strtotime(date('Y-m-d'));
        $tommorow_date = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        $day_after_tommorow = date('Y-m-d', strtotime(date('Y-m-d') . ' +2 day'));
        //   print_r($tommorow_date);exit;
        //   print_r($day_after_tommorow);exit;
        $this->db->select($this->tables['party'] . '.*,'. $this->tables['users'] . '.username AS full_name,' . $this->tables['users'] . '.profile_picture');
        //$this->db->join($this->tables['organization'], $this->tables['organization'] . '.id =' . $this->tables['party'] . '.organization_id');
        $this->db->join($this->tables['users'], $this->tables['users'] . '.id =' . $this->tables['party'] . '.user_id');
        //$this->db->where([$this->tables['party'] . '.active' => 1]);
        
       if(!empty($city))
        {
        $where=$this->tables['party'].'.city IN("'.$city.'")';
        $this->db->where($where);

        //$this->db->like($this->tables['party'].'.city IN',$city);

       
        /*if ($status != 1 && $status != 2 && $status != 3 && $status != 5) {
            return false;
        }*/

        if ($status == 1) {
            //$this->db->where([$this->tables['party'] . '.start_date' => $current_date]); //27-04-2023
            $this->db->where(['DATE('.$this->tables['party'] . '.created_at)<=' =>date('Y-m-d',$current_date)]); //27-04-2023 New Changes
            $this->db->where([$this->tables['party'] . '.start_date=' =>$current_date]); //27-04-2023 New Changes

            //$this->db->where([$this->tables['party'].'.end_date >=' => $current_date]);
            //$this->db->where([$this->tables['party'] . '.papular_status' => $filter_type]);
        }
        if ($status == 2) {
            //$this->db->where([$this->tables['party'] . '.start_date' => strtotime($tommorow_date)]); //27-04-2023

            $this->db->where(['DATE('.$this->tables['party'] . '.created_at)<=' => $tommorow_date]); //27-04-2023 New Changes

            $this->db->where([$this->tables['party'] . '.start_date=' => strtotime($tommorow_date)]);  //27-04-2023 New Changes
            //$this->db->where([$this->tables['party'].'.end_date >=' => strtotime($tommorow_date)]);
            //$this->db->where([$this->tables['party'] . '.papular_status' => $filter_type]);
        }
        if ($status == 3) {
            $this->db->where([$this->tables['party'] . '.start_date >=' => strtotime($day_after_tommorow)]);

            $this->db->where(['DATE('.$this->tables['party'] . '.created_at)<=' => $day_after_tommorow]); //27-04-2023 New Changes

            // $this->db->where([$this->tables['party'].'.end_date >=' => strtotime($day_after_tommorow)]);
            //$this->db->where([$this->tables['party'] . '.papular_status' => $filter_type]);
        }

        if ($status == 5) {
            $this->db->where([$this->tables['party'] . '.start_date >=' => $current_date]);
            $this->db->where(['DATE('.$this->tables['party'] . '.created_at)<=' =>date('Y-m-d',$current_date)]); //27-04-2023 New Changes
            
            $this->db->where([$this->tables['party'] . '.papular_status' =>'1']);
            // $this->db->where([$this->tables['party'].'.end_date >=' => strtotime($day_after_tommorow)]);

        }
    
        $this->db->where([$this->tables['party'] . '.approval_status' =>'1']);
        $this->db->where([$this->tables['party'] . '.active' =>'1']);
        
        $this->db->order_by($this->tables['party'] . '.start_time', 'ASC');
        //   $this->db->order_by($this->tables['party'].'.id', 'DESC');
        $query = $this->db->get($this->tables['party']);
       // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            $res = $query->result();
            foreach ($res as $key => $value) {
                $query_party_amenitie = $this->db->query("SELECT id,name FROM party_amenities WHERE id IN ($value->party_amenitie_id)")->result_array();
                if ($query_party_amenitie) {
                    $res[$key]->party_amenitie = $query_party_amenitie;
                } else {
                    $res[$key]->party_amenitie = array();
                }
            }

            return $res;
          }
        return FALSE;
           
         }
        return FALSE;
    }

    public function get_wish_list_party($user_id)
    {
        $obj_res = array();
        $this->db->select($this->tables['party_wish_list'] . '.*,' . $this->tables['users'] . '.username AS full_name,' . $this->tables['users'] . '.profile_picture,'.$this->tables['party'].'.title,pr_start_date,pr_end_date,gender,like,view,ongoing,cover_photo,phone_number,papular_status,papular_time,approval_status,description,others,person_limit,start_date,end_date,start_time,end_time');
        $this->db->join($this->tables['party'], $this->tables['party'] . '.id =' . $this->tables['party_wish_list'] . '.party_id');
        $this->db->join($this->tables['users'], $this->tables['users'] . '.id =' . $this->tables['party_wish_list'] . '.user_id');
        $this->db->where([$this->tables['party_wish_list'] . '.user_id' => $user_id]);
        $this->db->order_by($this->tables['party_wish_list'].'.id', 'DESC');
        $query = $this->db->get($this->tables['party_wish_list']);
      // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            $res = $query->result();
            return $res;
        }
        return FALSE;
    }

     public function get_org_view_like_ongoing_party_count($org_id)
    {
        $obj_res = array();
        $this->db->select('SUM('.$this->tables['party'] .'.like) AS total_like,SUM('.$this->tables['party'] . '.view) AS total_view,SUM('.$this->tables['party'] .'.ongoing) AS total_ongoing');
        $this->db->where([$this->tables['party'] . '.organization_id' => $org_id]);
        $query = $this->db->get($this->tables['party']);
      // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            $res = $query->result();
            return $res;
        }
        return FALSE;
    }


}
