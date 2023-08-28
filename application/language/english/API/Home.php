<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class Home
 * Create class for app home screen handling
*/
class Home extends REST_Controller {

    public function __construct() {
        parent::__construct();

        /* Load :: Helper */
        $this->lang->load('API/home');
        $this->lang->load('API/party');
        
        /* Load :: Models */
        $this->load->model('v1/home_model');
        $this->load->model('v1/party_model');
        $this->form_validation->set_error_delimiters(' | ', '');
    }

    /**
     * Filter Categories 
     * Method (POST)
     */
    public function filter_categories_post() {

        $latitude = !empty($this->input->post('latitude')) ? $this->input->post('latitude') : "";
        $longitude = !empty($this->input->post('longitude')) ? $this->input->post('longitude') : "";

        $data = $this->home_model->get_filter_categories($latitude, $longitude);

        if ($data) {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('percentage_off_found'),
                $this->config->item('rest_data_field_name')     => $data
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('percentage_off_not_found')
            ], REST_Controller::HTTP_OK);
        }
    }

    public function popular_cities_post() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);

        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        /* End Check Authentications */

        $this->form_validation->set_rules('offset', '', 'required', array('required' => '%s'));
        if ($this->form_validation->run() == true) {
            $data = $this->home_model->popular_cities($this->input->post('offset'));
            if ($data) {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('popular_cities_found_success'),
                    $this->config->item('rest_data_field_name')     => $data
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('popular_cities_found_failed')
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function near_by_users_post() {

        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        /* End Check Authentications */

        
        $this->form_validation->set_rules('city_id','', 'required', array('required' => '%s'));
        $this->form_validation->set_rules('state','', 'required', array('required' => '%s'));
        
        if ($this->form_validation->run() == true) {
        $latitude = !empty($this->input->post('latitude')) ? $this->input->post('latitude') : "";

        $longitude = !empty($this->input->post('longitude')) ? $this->input->post('longitude') : "";

        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : "";

        $state = !empty($this->input->post('state')) ? $this->input->post('state') : "";
        
        $user_id = $authorized_user['account']->id;
        $user_arr=array();
        $users = $this->home_model->near_by_users($latitude, $longitude, $user_id,$city_id,$state);
        if (!empty($users)) {

            foreach ($users as $key => $value) {
                 $res_user=$this->general_model->getOne('individual_block_user',array('individual_id'=>$value->id,'user_id'=>$user_id,'status'=>'Block'));
                 $res_user2=$this->general_model->getOne('individual_block_user',array('user_id'=>$value->id,'individual_id'=>$user_id,'status'=>'Block'));

                 if(empty($res_user))
                 {
                   $block_status=0;
                 }else if(empty($res_user2))
                 {
                   $block_status=0;
                 }else{
                 	$block_status=1;
                 } 
                if(empty($block_status))
                {
               
                 $res_org=$this->general_model->getOne('organization',array
                    ('user_id'=>$value->id));
 
                 $res_like=$this->general_model->getOne('individual_like',array
                    ('individual_id'=>$value->id,'like_unlike'=>'Yes'));
                 if(!empty($res_like))
                 {
                   $like_status='1';
                 }else{
                   $like_status='0';
                   
                 }

                
                
                //print_r($res_org); die;
                $user_arr[] = [
                    'id' => $value->id,
                    'first_name' => $value->first_name,
                    'full_name' => @$res_org->name,
                    'username' => $value->username,
                    'city'=>@$res_org->city,
                    'email' => $value->email,
                    'phone' => $value->phone,
                    'country_code' => $value->country_code,
                    'profile_picture' => @$res_org->profile_pic ? @$res_org->profile_pic : "",
                    //'latitude' => $value->latitude,
                    'gender' => $res_org->gender,
                    'online_status'=>$value->online_status,
                    'privacy_online'=>$value->privacy_online,
                    'like_status'=>$like_status
                /*$users[$key] = [
                    'id' => $value->id,
                    'first_name' => $value->first_name,
                    'username' => $value->username,
                    'email' => $value->email,
                    'phone' => $value->phone,
                    'country_code' => $value->country_code,
                    'profile_picture' => $value->profile_picture ? $value->profile_picture : "",
                    //'latitude' => $value->latitude,
                    //'longitude' => $value->longitude
                    'online_status'=>'0',
                    'like_status'=>'0'*/

                ];

               }
            }
        }
        if ($user_arr) {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('user_found_success'),
                $this->config->item('rest_data_field_name')     => $user_arr
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('user_found_failed')
            ], REST_Controller::HTTP_OK);
        }

    }else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function users_search_post() {

        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        /* End Check Authentications */
        $keyword=$this->input->post('keyword');
        if($this->form_validation->run() == true) {
        $users = $this->home_model->get_search_users($keyword);
        if (!empty($users)) {
            foreach ($users as $key => $value) {
                $users[$key] = [
                    'id' => $value->id,
                    'first_name' => $value->first_name,
                    'username' => $value->username,
                    'email' => $value->email,
                    'phone' => $value->phone,
                    //'country_code' => $value->country_code,
                    'profile_picture' => $value->profile_picture ? $value->profile_picture : "",
                    //'latitude' => $value->latitude,
                    //'longitude' => $value->longitude
                ];
            }
        }
        if ($users) {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('user_found_success'),
                $this->config->item('rest_data_field_name')     => $users
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('user_found_failed')
            ], REST_Controller::HTTP_OK);
        }

        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function get_today_party_get() {

        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        /* End Check Authentications */

        $latitude = !empty($this->input->post('latitude')) ? $this->input->post('latitude') : "";
        $longitude = !empty($this->input->post('longitude')) ? $this->input->post('longitude') : "";
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : "";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : 0;
        $organisation = !empty($this->input->get('organisation')) ? $this->input->get('organisation') : 0;
        // $user_id = $authorized_user['account']->id;
        $user_id = 1;

        $party = $this->home_model->get_today_party($latitude, $longitude, $user_id, $offset, $organisation,$city_id);
        $row= [];
        if ($party) {
            foreach ($party as $key => $value) {
                if ($value->end_date >= strtotime(date('d-m-Y'))) {
                    if ($value->end_date == strtotime(date('d-m-Y'))) {
                        if (strtotime($value->end_time) > strtotime(date('H:i'))) {
                            $party[$key]->end_date = date('d-m-Y', date($value->end_date));
                            $party[$key]->start_date = date('d-m-Y', date($value->start_date));
                            $row[] = $value;
                        } 
                    }else {
                        $party[$key]->end_date = date('d-m-Y', date($value->end_date));
                        $party[$key]->start_date = date('d-m-Y', date($value->start_date));
                        $row[] = $value;
                    }
                }
            }
        }
        if ($row) {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('party_found_success'),
                $this->config->item('rest_data_field_name')     => $row
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('party_found_failed'),
                $this->config->item('rest_data_field_name')     => $row
            ], REST_Controller::HTTP_OK);
        }
    }

    public function get_tomorrow_party_post() {

        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        /* End Check Authentications */
        $latitude = !empty($this->input->post('latitude')) ? $this->input->post('latitude') : "";
        $longitude = !empty($this->input->post('longitude')) ? $this->input->post('longitude') : "";
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : "";

        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : 0;
        $organisation = !empty($this->input->post('organisation')) ? $this->input->post('organisation') : 0;
        $user_id = $authorized_user['account']->id;

        $party = $this->home_model->get_tomorrow_party($latitude, $longitude, $user_id, $offset, $organisation,$city_id);
        $row = [];
        if ($party) {
            foreach ($party as $key => $value) {
                if ($value->end_date >= strtotime(date('d-m-Y'))) {
                    if ($value->end_date == strtotime(date('d-m-Y'))) {
                        if (strtotime($value->end_time) > strtotime(date('H:i'))) {
                            $party[$key]->end_date = date('d-m-Y', date($value->end_date));
                            $party[$key]->start_date = date('d-m-Y', date($value->start_date));
                             $row[] = $value;
                        } 
                    }else {
                        $party[$key]->end_date = date('d-m-Y', date($value->end_date));
                        $party[$key]->start_date = date('d-m-Y', date($value->start_date));
                        $row[] = $value;
                    }
                }
            }
        }
        if ($row) {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('party_found_success'),
                $this->config->item('rest_data_field_name')     => $row
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('party_found_failed'),
                $this->config->item('rest_data_field_name')     => $row
            ], REST_Controller::HTTP_OK);
        }
    }

    public function party_by_cities_post() {

        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        /* End Check Authentications */
        $this->form_validation->set_rules('city_id', '', 'required', array('required' => '%s'));
        $this->form_validation->set_rules('offset', '', 'required', array('required' => '%s'));
        if ($this->form_validation->run() == true) {
            $city = $this->home_model->get_cites_by_id($this->input->post('city_id'));
            if ($city) {
                $latitude = !empty($city->latitude) ? $city->latitude : "";
                $longitude = !empty($city->longitude) ? $city->longitude : "";
                $party = $this->home_model->party_by_cities($latitude, $longitude, $this->input->post('offset'));
                if ($party) {
                    $row = [];
                    foreach ($party as $key => $value) {
                        $party[$key]->end_date = date('d-m-Y', date($value->end_date));
                        $party[$key]->start_date = date('d-m-Y', date($value->start_date));
                        $type = [];
                        $gender_type = explode(',', $value->gender);
                        if ($gender_type) {
                            foreach ($gender_type as $gender) {
                                if ($gender == '1') {
                                    $type[] = 'male';
                                } else if ($gender == '2') {
                                    $type[] = 'female';
                                } else if ($gender == '3') {
                                    $type[] = 'couple';
                                } else if ($gender == '4') {
                                    $type[] = 'other';
                                } 
                            }
                        }
                        if (!empty($type)) {
                            $gend = implode(',', $type);
                        }
                        $party[$key]->gender = $gend;
                        $row[] = $value;
                    }
                    if ($row) {
                        $this->response([
                            $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                            $this->config->item('rest_message_field_name')  => $this->lang->line('party_found_success'),
                            $this->config->item('rest_data_field_name')     => $row
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                            $this->config->item('rest_message_field_name')  => $this->lang->line('party_found_failed'),
                        ], REST_Controller::HTTP_OK);
                    }
                } else {
                    $this->response([
                        $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                        $this->config->item('rest_message_field_name')  => $this->lang->line('party_found_failed'),
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('city_found_failed'),
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    
    public function party_details_post() {

        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        /* End Check Authentications */
        $this->form_validation->set_rules('party_id', '', 'required', array('required' => '%s'));
        if ($this->form_validation->run() == true) {
            $party = $this->home_model->party_details($this->input->post('party_id'));
            if ($party) {
                $party->end_date = date('d-m-Y', date($party->end_date));
                $party->start_date = date('d-m-Y', date($party->start_date));
                $type = [];
                $gender_type = explode(',', $party->gender);
                if ($gender_type) {
                    foreach ($gender_type as $gender) {
                        if ($gender == '1') {
                            $type[] = 'male';
                        } else if ($gender == '2') {
                            $type[] = 'female';
                        } else if ($gender == '3') {
                            $type[] = 'couple';
                        } else if ($gender == '4') {
                            $type[] = 'other';
                        } 
                    }
                }
                if (!empty($type)) {
                    $gend = implode(',', $type);
                }
                $party->gender = $gend;

                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('party_found_success'),
                    $this->config->item('rest_data_field_name')     => $party
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('party_found_failed'),
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function cities_get() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);

        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }

        $data = $this->party_model->cities();
        if($data) {
            
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('success_cities_message'),
                $this->config->item('rest_data_field_name')     => $data
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => $this->lang->line('failed_cities_message')
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

        public function states_post() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);

        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }

        $data = $this->general_model->getAll('states',array('country_id'=>'101'));
        if($data) {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => 'Data Found',
                $this->config->item('rest_data_field_name')     => $data
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => 'Not Data Found'
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function cities_post() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);

        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
          $this->form_validation->set_rules('state_id', '', 'required', array('required' => '%s'));
        if ($this->form_validation->run() == true) {
        $res = $this->general_model->getOne('states',array('name'=>$this->input->post('state_id')));
            //print_r($res); die;
        if(!empty($res))
          {  
           $data = $this->general_model->getAll('all_cities',array('state_id'=>$res->id));
           }else{
            $data=array();
           } 
        if($data) {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => 'Data Found',
                $this->config->item('rest_data_field_name')     => $data
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                $this->config->item('rest_message_field_name')  => 'Not Data Found'
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }

         } else {
            $this->response([
            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
            $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

}
