<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class Order History
 * Create class for User order history handling
*/
class Order_history extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* Load :: Helper */
        $this->lang->load('auth');
        $this->lang->load('API/order_history');
        /* Load :: Models */
        $this->load->model('v1/order_history_model');

        $this->form_validation->set_error_delimiters(' | ', '');
    }

    /* User Order history */
    public function history_get() {
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
        $offset = $this->input->get('offset') * getenv('LIMIT_DRIVER_OREDER_HISTORY');
        $orders = $this->order_history_model->get_all_orders($authorized_user['account']->id, $offset, getenv('LIMIT_DRIVER_OREDER_HISTORY'));
        
        $order_array = [];
        if ($orders) {
            foreach ($orders as $key => $fetch_order) {
                $fetch_order_detail =  [];
                $fetch_order_detail['order_id']      = (int) $fetch_order->order_id;
                $fetch_order_detail['user_id']       = (int) $fetch_order->user_id;
                $fetch_order_detail['restaurant_id'] = (int) $fetch_order->restaurant_id;
                $fetch_order_detail['driver_id']     = (int) $fetch_order->driver_id;
                $fetch_order_detail['order_delivery_group_id'] = !empty($fetch_order->order_delivery_group_id) ? (int) $fetch_order->order_delivery_group_id : 0;
                $fetch_order_detail['order_unique_id']  = $fetch_order->order_unique_id;
                $fetch_order_detail['address_id']       = (int) $fetch_order->address_id;
                $fetch_order_detail['order_place_datetime'] = (int) $fetch_order->order_place_datetime;
                $fetch_order_detail['dropoff_latitude']  = $fetch_order->dropoff_latitude;
                $fetch_order_detail['dropoff_longitude'] = $fetch_order->dropoff_longitude;
                $fetch_order_detail['delivery_type']     = (int) $fetch_order->delivery_type;
                $fetch_order_detail['payment_type']      = (int) $fetch_order->payment_type;
                $fetch_order_detail['additional_note']   = $fetch_order->additional_note;
                $fetch_order_detail['total_amount']      = (float) $fetch_order->total_amount;
                $fetch_order_detail['delivery_charge']   = (float) $fetch_order->delivery_charge;
                $fetch_order_detail['discount_amount']   = (float) $fetch_order->discount_amount;
                $fetch_order_detail['discount_percentage']   = (int) $fetch_order->discount_percentage;
                $fetch_order_detail['payment_status']    = (int) $fetch_order->payment_status;
                $fetch_order_detail['order_status']      = (int) $fetch_order->order_status;
                $fetch_order_detail['payment_id']        = (int) $fetch_order->payment_id;
                $fetch_order_detail['promo_usage_id']    = (int) $fetch_order->promo_usage_id;
                $fetch_order_detail['estimated_delivery_time'] = $fetch_order->estimated_delivery_time;
                $fetch_order_detail['additional_note']   = !empty($fetch_order->additional_note) ? $fetch_order->additional_note : "";
                $fetch_order_detail['delivery_date']     = !empty($fetch_order->delivery_date) ? $fetch_order->delivery_date : "";
                $fetch_order_detail['delivery_number']   = (int) $fetch_order->delivery_number;

                $fetch_order_detail['restaurant_food_is_rated'] = $this->order_history_model->is_order_rated(['order_id' => $fetch_order->order_id,'user_id' => $fetch_order->user_id]);
                $fetch_order_detail['delivery_food_is_rated'] = $this->order_history_model->is_delivery_order_rated(['order_id' => $fetch_order->order_id, 'driver_id' => $fetch_order->driver_id]);

                if (!empty($fetch_order->order_delivery_group_id)) {
                    $fetch_order_delivery_group = $this->order_history_model->fetch_order_delivery_group($fetch_order->order_delivery_group_id);
                    if (!empty($fetch_order_delivery_group)) {
                        $fetch_order_detail['delivery_out_of'] = (int) $fetch_order_delivery_group->total_delivery_order;
                        $fetch_order_detail['order_delivery_group'] = [
                            'order_delivery_group_id' => (int) $fetch_order_delivery_group->order_delivery_group_id,
                            'total_delivery_order'    => (int) $fetch_order_delivery_group->total_delivery_order,
                            'mark_as_pickup'          => (int) $fetch_order_delivery_group->mark_as_pickup,
                            'next_available'          => (int) $fetch_order_delivery_group->next_available,  
                        ];
                    } else {
                        $fetch_order_detail['delivery_out_of'] = 0;
                        $fetch_order_detail['order_delivery_group'] = (object) [];
                    }
                }

                $restaurant_details = $this->order_history_model->get_restaurant_details($fetch_order->restaurant_id);
                if (!is_null($restaurant_details)) {
                    $fetch_order_detail['restaurant_details'] = [
                        'restaurant_address' => $restaurant_details->restaurant_address,
                        'restaurant_name'    => $restaurant_details->restaurant_name,
                        'restaurant_image'    => base_url($restaurant_details->restaurant_image),
                    ];
                } else {
                    $fetch_order_detail['restaurant_details'] = [];
                }

                /* Get Order Delivery Person Detail */
                $fetch_order_detail['user_details'] = [
                    'full_name' => $authorized_user['account']->first_name,
                    'email' => $authorized_user['account']->email,
                    'phone' => $authorized_user['account']->phone,
                    'profile_picture' => !empty($authorized_user['account']->profile_picture) ? base_url($authorized_user['account']->profile_picture) : ""
                ];

                /* Get Order Order Person Detail */
                $unserialize_item_array = $this->general_model->unserialize_order($fetch_order->order_item_array);
                
                $fetch_order_detail['order_item_array'] = $unserialize_item_array;
                
                foreach ($unserialize_item_array as $item_key => $item) {
                    $fetch_restaurant_item = $this->order_history_model->fetch_restaurant_item($item['restaurant_cuisine_item_id']);
                    
                    $fetch_order_detail['order_item_array'][$item_key]['price']       = (float) $fetch_restaurant_item->price;
                    $fetch_order_detail['order_item_array'][$item_key]['order_price'] = $item['price'];
                    $fetch_order_detail['order_item_array'][$item_key]['item_image']  = base_url($fetch_restaurant_item->item_image);
                    $fetch_order_detail['order_item_array'][$item_key]['is_alcohol_item'] = (int) $fetch_restaurant_item->is_alcohol_item;

                    if (!empty($fetch_order_detail['order_item_array'][$item_key]['addon_groups'])) {
                        foreach ($item['addon_groups'] as $addon_key => $addon) {

                            if (!empty($fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'])) {
                                foreach ($fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'] as $grp_key => $group_item) {
                                    $fetch_group_item = $this->order_history_model->fetch_group_item($group_item['addon_item_id']);

                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['addon_item_id'] = (int) $fetch_group_item->addon_item_id;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['cuisine_addon_id'] = (int) $fetch_group_item->cuisine_addon_id;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['group_item_name'] = $fetch_group_item->group_item_name;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['restaurant_id'] = $fetch_group_item->restaurant_id;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['veg_only'] = (int) $fetch_group_item->veg_only;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['price'] = (float) $fetch_group_item->price;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['order_group_item_price'] = (float) $group_item['price'];
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['is_addon_item_deleted'] = (int) $fetch_group_item->is_addon_item_deleted;
                                }
                            } else {
                                $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'] = [];
                            }
                        }
                    } else {
                        $fetch_order_detail['order_item_array'][$item_key]['addon_groups'] = [];
                    }
                }

                /* Get Order Address */
                $address_details = $this->order_history_model->get_address_details($fetch_order->address_id);
                if (!empty($address_details)) {
                    $fetch_order_detail['address_details'] = $address_details;
                } else {
                    $fetch_order_detail['address_details'] = (object) [];
                }

                /* Get Order Delivery Person Detail */
                if (!empty($fetch_order->driver_id)) {
                    $fetch_driver = $this->order_history_model->fetch_driver_details($fetch_order->driver_id);
                    if (!empty($fetch_driver)) {
                        $fetch_order_detail['driver_details'] = [
                            'first_name' => $fetch_driver->first_name,
                            'email' => $fetch_driver->email,
                            'phone' => $fetch_driver->phone,
                            'profile_picture' => !empty($fetch_driver->profile_picture) ? base_url($fetch_driver->profile_picture) : "",
                            'average_rating' => $this->order_history_model->get_driver_ratings($fetch_order->driver_id)
                        ];
                    } else {
                        $fetch_order_detail['driver_id'] = !empty($fetch_order->driver_id) ? $fetch_order->driver_id : "";
                        $fetch_order_detail['driver_details'] = (object) [];
                    }
                } else {
                    $fetch_order_detail['driver_id'] = !empty($fetch_order->driver_id) ? $fetch_order->driver_id : "";
                    $fetch_order_detail['driver_details'] = (object) [];
                }
                
                $order_array[$key] = $fetch_order_detail;
            }

            $this->response([
                $this->config->item('rest_status_field_name')  => 1,
                $this->config->item('rest_message_field_name') => $this->lang->line('order_history_data_found'),
                'offset' => $offset + 1,
                $this->config->item('rest_data_field_name')    => $order_array
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                $this->config->item('rest_status_field_name') => 0,
                $this->config->item('rest_message_field_name') => $this->lang->line('order_history_data_not_found')
            ], REST_Controller::HTTP_OK);
        }
    }

    /**
     * Get Order Details 
     * Method (POST)
     */
    public function order_details_post() {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        $authorized_user = $this->general_model->check_authorization($headers);
        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name') => $authorized_user['status'],
                $this->config->item('rest_message_field_name') => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }

        $this->form_validation->set_rules('order_id', '', 'required', array('required' => '%s'));

        if ($this->form_validation->run() == true) {
            $order_id = $this->input->post('order_id');

            $fetch_order = $this->order_history_model->fetch_order_detail($order_id);

            if (!is_null($fetch_order)) {
                $fetch_order_detail =  [];
                $fetch_order_detail['order_id'] = (int) $fetch_order->order_id;
                $fetch_order_detail['user_id'] = (int) $fetch_order->user_id;
                $fetch_order_detail['restaurant_id'] = (int) $fetch_order->restaurant_id;
                $fetch_order_detail['driver_id'] = (int) $fetch_order->driver_id;
                $fetch_order_detail['order_delivery_group_id'] = !empty($fetch_order->order_delivery_group_id) ? (int) $fetch_order->order_delivery_group_id : 0;
                $fetch_order_detail['order_unique_id'] = $fetch_order->order_unique_id;
                $fetch_order_detail['address_id'] = (int) $fetch_order->address_id;
                $fetch_order_detail['order_place_datetime'] = (int) $fetch_order->order_place_datetime;
                $fetch_order_detail['dropoff_latitude'] = $fetch_order->dropoff_latitude;
                $fetch_order_detail['dropoff_longitude'] = $fetch_order->dropoff_longitude;
                $fetch_order_detail['delivery_type'] = (int) $fetch_order->delivery_type;
                $fetch_order_detail['payment_type'] = (int) $fetch_order->payment_type;
                $fetch_order_detail['additional_note'] = $fetch_order->additional_note;
                $fetch_order_detail['total_amount'] = (float) $fetch_order->total_amount;
                $fetch_order_detail['delivery_charge'] = (float) $fetch_order->delivery_charge;
                $fetch_order_detail['discount_amount'] = (float) $fetch_order->discount_amount;
                $fetch_order_detail['discount_percentage']   = (int) $fetch_order->discount_percentage;
                $fetch_order_detail['payment_status'] = (int) $fetch_order->payment_status;
                $fetch_order_detail['order_status'] = (int) $fetch_order->order_status;
                $fetch_order_detail['payment_id'] = (int) $fetch_order->payment_id;
                $fetch_order_detail['promo_usage_id'] = (int) $fetch_order->promo_usage_id;
                $fetch_order_detail['estimated_delivery_time'] = $fetch_order->estimated_delivery_time;
                $fetch_order_detail['current_timestamp'] = time();
                $fetch_order_detail['order_placed_on'] = (int) $fetch_order->order_placed_on;
                $fetch_order_detail['order_cancel_end_timestamp'] = !empty($fetch_order->order_cancel_end_timestamp) ? (int) $fetch_order->order_cancel_end_timestamp : 0;
                $fetch_order_detail['additional_note'] = !empty($fetch_order->additional_note) ? $fetch_order->additional_note : "";
                $fetch_order_detail['delivery_date'] = !empty($fetch_order->delivery_date) ? $fetch_order->delivery_date : "";
                $fetch_order_detail['delivery_number'] = (int) $fetch_order->delivery_number;
                $fetch_order_detail['is_auto_rejected'] = (int) $fetch_order->is_auto_rejected;

                $fetch_order_detail['restaurant_food_is_rated'] = $this->order_history_model->is_order_rated(['order_id' => $fetch_order->order_id,'user_id' => $fetch_order->user_id]);
                $fetch_order_detail['delivery_food_is_rated'] = $this->order_history_model->is_delivery_order_rated(['order_id' => $fetch_order->order_id, 'driver_id' => $fetch_order->driver_id]);

                if (!empty($fetch_order->order_delivery_group_id)) {
                    $fetch_order_delivery_group = $this->order_history_model->fetch_order_delivery_group($fetch_order->order_delivery_group_id);
                    if (!empty($fetch_order_delivery_group)) {
                        $fetch_order_detail['delivery_out_of'] = (int) $fetch_order_delivery_group->total_delivery_order;
                        $fetch_order_detail['order_delivery_group'] = [
                            'order_delivery_group_id' => (int) $fetch_order_delivery_group->order_delivery_group_id,
                            'total_delivery_order' => (int) $fetch_order_delivery_group->total_delivery_order,
                            'mark_as_pickup' => (int) $fetch_order_delivery_group->mark_as_pickup,
                            'next_available' => (int) $fetch_order_delivery_group->next_available,  
                        ];
                    } else {
                        $fetch_order_detail['delivery_out_of'] = 0;
                        $fetch_order_detail['order_delivery_group'] = (object) [];
                    }
                }

                $restaurant_details = $this->order_history_model->get_restaurant_details($fetch_order->restaurant_id);
                if (!is_null($restaurant_details)) {
                    $fetch_order_detail['restaurant_details'] = [
                        'restaurant_address' => $restaurant_details->restaurant_address,
                        'restaurant_name'    => $restaurant_details->restaurant_name,
                        'restaurant_latitude'    => $restaurant_details->restaurant_latitude,
                        'restaurant_longitude'    => $restaurant_details->restaurant_longitude,
                        'restaurant_image'    => base_url($restaurant_details->restaurant_image),
                        'restaurant_phone' => $restaurant_details->phone
                    ];
                } else {
                    $fetch_order_detail['restaurant_details'] = [];
                }

                /* Get Order Delivery Person Detail */
                $fetch_order_detail['user_details'] = [
                    'full_name' => $authorized_user['account']->first_name,
                    'email' => $authorized_user['account']->email,
                    'phone' => $authorized_user['account']->phone,
                    'profile_picture' => !empty($authorized_user['account']->profile_picture) ? base_url($authorized_user['account']->profile_picture) : ""
                ];

                /* Get Order Order Person Detail */
                $unserialize_item_array = $this->general_model->unserialize_order($fetch_order->order_item_array);
                
                $fetch_order_detail['order_item_array'] = $unserialize_item_array;
                
                foreach ($unserialize_item_array as $item_key => $item) {
                    $fetch_restaurant_item = $this->order_history_model->fetch_restaurant_item($item['restaurant_cuisine_item_id']);
                    
                    $fetch_order_detail['order_item_array'][$item_key]['price']       = (float) $fetch_restaurant_item->price;
                    $fetch_order_detail['order_item_array'][$item_key]['order_price'] = $item['price'];
                    $fetch_order_detail['order_item_array'][$item_key]['item_image']  = base_url($fetch_restaurant_item->item_image);
                    $fetch_order_detail['order_item_array'][$item_key]['is_alcohol_item'] = (int) $fetch_restaurant_item->is_alcohol_item;

                    if (!empty($fetch_order_detail['order_item_array'][$item_key]['addon_groups'])) {
                        foreach ($item['addon_groups'] as $addon_key => $addon) {

                            if (!empty($fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'])) {
                                foreach ($fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'] as $grp_key => $group_item) {
                                    $fetch_group_item = $this->order_history_model->fetch_group_item($group_item['addon_item_id']);

                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['addon_item_id'] = (int) $fetch_group_item->addon_item_id;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['cuisine_addon_id'] = (int) $fetch_group_item->cuisine_addon_id;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['group_item_name'] = $fetch_group_item->group_item_name;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['veg_only'] = (int) $fetch_group_item->veg_only;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['price'] = (float) $fetch_group_item->price;
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['order_group_item_price'] = (float) $group_item['price'];
                                    $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'][$grp_key]['is_addon_item_deleted'] = (int) $fetch_group_item->is_addon_item_deleted;
                                }
                            } else {
                                $fetch_order_detail['order_item_array'][$item_key]['addon_groups'][$addon_key]['group_items'] = [];
                            }
                        }
                    } else {
                        $fetch_order_detail['order_item_array'][$item_key]['addon_groups'] = [];
                    }
                }

                /* Get Order Address */
                $address_details = $this->order_history_model->get_address_details($fetch_order->address_id);
                if (!empty($address_details)) {
                    $fetch_order_detail['address_details'] = $address_details;
                } else {
                    $fetch_order_detail['address_details'] = (object) [];
                }

                /* Get Order Delivery Person Detail */
                if (!empty($fetch_order->driver_id)) {
                    $fetch_driver = $this->order_history_model->fetch_driver_details($fetch_order->driver_id);
                    if (!empty($fetch_driver)) {
                        $fetch_order_detail['driver_details'] = [
                            'first_name' => $fetch_driver->first_name,
                            'email' => $fetch_driver->email,
                            'phone' => $fetch_driver->phone,
                            'profile_picture' => !empty($fetch_driver->profile_picture) ? base_url($fetch_driver->profile_picture) : "",
                            'average_rating' => $this->order_history_model->get_driver_ratings($fetch_order->driver_id)
                        ];
                    } else {
                        $fetch_order_detail['driver_id'] = !empty($fetch_order->driver_id) ? $fetch_order->driver_id : "";
                        $fetch_order_detail['driver_details'] = (object) [];
                    }
                } else {
                    $fetch_order_detail['driver_id'] = !empty($fetch_order->driver_id) ? $fetch_order->driver_id : "";
                    $fetch_order_detail['driver_details'] = (object) [];
                }

                $this->response([
                $this->config->item('rest_status_field_name') => 1,
                $this->config->item('rest_message_field_name') => 'Order Found',
                'data' => $fetch_order_detail,
                    ], REST_Controller::HTTP_OK);
            }else{
                $this->response([
                $this->config->item('rest_status_field_name') => 0,
                $this->config->item('rest_message_field_name') => 'No order detail found.'
                    ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                $this->config->item('rest_status_field_name') => 0,
                $this->config->item('rest_message_field_name') => 'Empty request parameter(s). [ ' . ltrim(str_replace("\n", '', validation_errors()), ' |') . ' ]'
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
}