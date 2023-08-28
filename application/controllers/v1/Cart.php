<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Cart extends REST_Controller {

    public function __construct() {
        parent::__construct();
        /* LOAD :: Language */
        $this->lang->load('API/cart');
        /* LOAD :: Model */
        $this->load->model('v1/cart_model');
        $this->load->model('v1/restaurant_model');
        $this->load->model('opayo_model');
        /* LOAD :: Form Validation */
        $this->form_validation->set_error_delimiters(' | ', '');
    }

    /* Checkout order API */
    public function checkout_post() {
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
        $_POST = json_decode( file_get_contents( 'php://input' ), true);
        
        $this->form_validation->set_rules('restaurant_id', '', 'required');
        $this->form_validation->set_rules('item_total_amount', '', 'required');
        $this->form_validation->set_rules('delivery_type', '', 'required');
        $this->form_validation->set_rules('payment_type', '', 'required');
        $this->form_validation->set_rules('address_id', '', 'required');
        $this->form_validation->set_rules('items[]', '', 'required');
        $this->form_validation->set_rules('point_discount_applicable', '', 'required');

        if ($this->form_validation->run() == true) {

            $restaurant_id          = $this->input->post('restaurant_id');
            $address_id             = $this->input->post('address_id');
            $delivery_type          = $this->input->post('delivery_type');
            $point_discount_amount  = !empty($this->input->post('point_discount_amount')) ? $this->input->post('point_discount_amount') : 0;

            $check_restaurant_working_hours = $this->cart_model->check_restaurant_holiday_working_hour($restaurant_id);
            if ($check_restaurant_working_hours == FALSE) {
                $this->response([
                    $this->config->item('rest_status_field_name') => 3,
                    $this->config->item('rest_message_field_name') => 'Restaurant is closed.'
                ], REST_Controller::HTTP_OK);
            }

            if ($delivery_type == 1) {
                
                $this->form_validation->set_rules('address_id', '', 'required');

                if ($this->form_validation->run() == FALSE) {
                    $this->response([
                        $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                        $this->config->item('rest_message_field_name')  => $this->lang->line('order_empty_address')
                    ], REST_Controller::HTTP_OK);
                }

                $fetch_address = $this->general_model->getOne('user_addresses', ['address_id' => $this->input->post('address_id')]);

                if (!empty($fetch_address)) {
                    $dropoff_latitude = $fetch_address->address_latitude;
                    $dropoff_longitude = $fetch_address->address_longitude;

                    /* Restaurant Data Fetch */
                    $fetch_restaurant = $this->restaurant_model->get_restaurant_with_distance($restaurant_id, $dropoff_latitude, $dropoff_longitude);

                    if (empty($fetch_restaurant)) {
                        $this->response([
                            $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                            $this->config->item('rest_message_field_name') => $this->lang->line('order_restaurant_not_found')
                        ], REST_Controller::HTTP_OK);
                    }

                    if ($fetch_restaurant->delivery_radius < $fetch_restaurant->distance) {
                        if (!empty($fetch_address->postcode)) {
                            $postcode = explode(' ', $fetch_address->postcode);
                            $is_allowed_postcode = $this->restaurant_model->check_delivery_postcode($postcode[0], $restaurant_id); 
                            
                            if (empty($is_allowed_postcode)) {
                                $this->response([
                                    $this->config->item('rest_status_field_name') => 2,
                                    $this->config->item('rest_message_field_name') => $fetch_restaurant->takeaway == 1 ? $this->lang->line('order_delivery_not_allowed_choose_collection') : $this->lang->line('order_delivery_not_allowed')
                                ], REST_Controller::HTTP_OK);
                            }
                        } else {
                            $this->response([
                                $this->config->item('rest_status_field_name') => 2,
                                $this->config->item('rest_message_field_name') => $fetch_restaurant->takeaway == 1 ? $this->lang->line('order_delivery_not_allowed_choose_collection') : $this->lang->line('order_delivery_not_allowed')
                            ], REST_Controller::HTTP_OK);
                        }
                    }
                } else {
                    $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('order_address_not_found'),
                        ], REST_Controller::HTTP_OK);
                }
            } else {

                /* Restaurant Data Fetch */
                $fetch_restaurant = $this->restaurant_model->get_restaurant($restaurant_id);

                if (empty($fetch_restaurant)) {
                    $this->response([
                        $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                        $this->config->item('rest_message_field_name') => $this->lang->line('order_restaurant_not_found')
                    ], REST_Controller::HTTP_OK);
                }
                
                $dropoff_latitude = "";
                $dropoff_longitude = "";
            }

            $discount_amount = 0;
            $point_spent = 0;
            $promocode_type = "";
            $discount_value = 0;
            $item_count = 0;
            $details_for_report_purpose = array();

            $promo_usage_id     = $this->input->post('promo_usage_id');
            $delivery_charge    = $this->input->post('delivery_charge');
            $additional_note    = $this->input->post('additional_note');
            $item_total_amount  = $this->input->post('item_total_amount');
            $order_items        = $this->input->post('items');
            $delivery_charge_id = $this->input->post('delivery_charge_id');
            $payment_type       = $this->input->post('payment_type');
            
            $all_item_fianl_amount = 0;
            foreach ($order_items as $key => $item) {
                $is_customized = 0;
                $item_all_addon_total = 0;
                if ($item['customization_available'] == 1) {
                    if (!empty($item['addon_groups'])) {
                        $is_customized = 1;
                        foreach ($item['addon_groups'] as $key1 => $addon_group) {
                            $addon_total = 0;
                            foreach ($addon_group['group_items'] as $key2 => $group_item) {
                                // print_r($group_item);exit;
                                $fetch_group_item = $this->restaurant_model->get_addon_item($group_item['addon_item_id']);

                                if (!empty($fetch_group_item)) {
                                    $item_count = $item_count + $item['quantity'];
                                    $per_group_item_total = $fetch_group_item->price * $item['quantity'];
                                    /* Received Addon Price multiply By Quantity */
                                    $group_item['price'] = $group_item['price'] * $item['quantity'];

                                    if ($per_group_item_total != $group_item['price']) {
                                        $this->response([
                                        $this->config->item('rest_status_field_name') => 3,
                                        $this->config->item('rest_message_field_name') => 'Your order item '.$item['item_name'].' addon price has been changed. Please remove from the cart.'
                                            ], REST_Controller::HTTP_OK);
                                    }else{
                                        $addon_total += $per_group_item_total;

                                        // Add extra items detail for report purpose
                                        array_push( $details_for_report_purpose, [
                                            'type'          => 'extra_item',
                                            'restaurant_id' => $restaurant_id,
                                            'dish_or_drink' => 'Drink',
                                            'order_id'      =>  0,
                                            'user_id'       => $authorized_user['account']->id,
                                            'item_id'       => $group_item['addon_item_id'],
                                            'item_count'    => $item['quantity'],
                                            'earning'       => $group_item['price'],
                                            'is_customized' => '0',
                                            'order_date'    => now()
                                        ]);
                                    }
                                }
                            }

                            $item_all_addon_total += $addon_total;
                        }
                    }
                }
                $item_count = $item_count + $item['quantity'];
                // Add cuisine detail for report purpose
                array_push( $details_for_report_purpose, [
                    'type'          => 'cuisine',
                    'dish_or_drink' => 'Dish',
                    'restaurant_id' => $restaurant_id,
                    'order_id'      =>  0,
                    'user_id'       => $authorized_user['account']->id,
                    'item_id'       => $item['restaurant_cuisine_item_id'],
                    'item_count'    => $item['quantity'],
                    'is_customized' => $is_customized,
                    'earning'       => $item["price"] * $item['quantity'],
                    'order_date'    => now()
                ]);
                
                /* Check Item Price Validation */
                $fetch_item = $this->restaurant_model->get_restaurant_cuisine_item($item['restaurant_cuisine_item_id'], $restaurant_id);
                
                if (!empty($fetch_item)) {

                    if ($fetch_item->is_cuisine_item_deleted == 1) {
                        $this->response([
                            $this->config->item('rest_status_field_name') => 3,
                            $this->config->item('rest_message_field_name') => 'Your order item '.$item['item_name'].' not available. Please remove from the cart.'
                        ], REST_Controller::HTTP_OK);
                    }

                    if ($fetch_item->unavailable == 0) {
                        $this->response([
                            $this->config->item('rest_status_field_name') => 3,
                            $this->config->item('rest_message_field_name') => 'Your order item '.$item['item_name'].' not available. Please remove from the cart.'
                        ], REST_Controller::HTTP_OK);
                    }
                    
                    $per_item_total = $fetch_item->price * $item['quantity'];
                    $per_item_add_total = $item_all_addon_total;
                    
                    $item_final_amount = $per_item_total + $per_item_add_total;

                    /* Received Addon Price multiply By Quantity */
                    $item['price'] = $item['price'] * $item['quantity'];
                    if (number_format($per_item_total, 2) != number_format($item['price'], 2)) {
                        $this->response([
                            $this->config->item('rest_status_field_name') => 3,
                            $this->config->item('rest_message_field_name') => 'Your order item '.$item['item_name'].' price has been changed. Please remove from the cart.'
                        ], REST_Controller::HTTP_OK);
                    }else{
                        $all_item_fianl_amount += $item_final_amount;
                    }
                }else{
                    $this->response([
                        $this->config->item('rest_status_field_name') => 3,
                        $this->config->item('rest_message_field_name') => 'Your order item '.$item['item_name'].' not available. Please remove from the cart.'
                    ], REST_Controller::HTTP_OK);
                }
            }
            

            if (!empty($fetch_restaurant->offer_percentage)) {
                $discount_amount = number_format((($all_item_fianl_amount * $fetch_restaurant->offer_percentage) / 100), 2);
                $all_item_fianl_amount = number_format($all_item_fianl_amount - $discount_amount, 2);
            } else {
                if (!empty($promo_usage_id)) {
                    $promo_detail = $this->restaurant_model->get_promocode_usage_details($promo_usage_id);

                    if (empty($promo_detail)) {
                        $this->response([
                            $this->config->item('rest_status_field_name')   => 0,
                            $this->config->item('rest_message_field_name')  => $this->lang->line('order_promo_not_found')
                        ], REST_Controller::HTTP_OK);
                    }

                    if ($promo_detail->promocode_type == 1) {
                        $promocode_type = 1;
                        $discount_value = $promo_detail->discount;
                        $discount_amount = number_format((($all_item_fianl_amount * $promo_detail->discount) / 100), 2);
                        $all_item_fianl_amount = number_format($all_item_fianl_amount - $discount_amount, 2);
                    } else {
                        $promocode_type = 2;
                        $discount_value = $promo_detail->discount;
                        $discount_amount = $promo_detail->discount;
                        $all_item_fianl_amount = $all_item_fianl_amount - $discount_amount;
                    }
                }
            }

            /* Point Discount Check */
            if ($this->input->post('point_discount_applicable') == 1) {
                $points = $authorized_user['account']->points;
                /* Get Point system Data */
                $point_system = $this->cart_model->get_point_system_data();

                if (!empty($point_system)) {
                    if ($authorized_user['account']->points >= $point_system->per_order_max_point_spent && $authorized_user['account']->earning_amount > 0) {
                        $discount_amount = ($point_system->per_order_max_point_spent * $point_system->reach_x_point_reword_amount) / $point_system->reach_x_point_reword;

                        if (number_format($discount_amount, 2) <= number_format($authorized_user['account']->earning_amount, 2)) {
                            if (number_format($discount_amount, 2) != $point_discount_amount) {
                                $this->response([
                                    $this->config->item('rest_status_field_name')   => 4,
                                    $this->config->item('rest_message_field_name')  => $this->lang->line('point_discount_amount_not_match')
                                ], REST_Controller::HTTP_OK);
                            } else {
                                $point_spent = $point_system->per_order_max_point_spent;
                                $all_item_fianl_amount = $all_item_fianl_amount - $point_discount_amount;
                                $discount_amount += $point_discount_amount;
                            }
                        } else {
                            $this->response([
                                $this->config->item('rest_status_field_name')   => 4,
                                $this->config->item('rest_message_field_name')  => $this->lang->line('point_discount_not_enough_amount')
                            ], REST_Controller::HTTP_OK);
                        }
                    } else {
                        $this->response([
                            $this->config->item('rest_status_field_name')   => 4,
                            $this->config->item('rest_message_field_name')  => $this->lang->line('point_discount_not_enough_points')
                        ], REST_Controller::HTTP_OK);
                    }
                } else {
                    $this->response([
                        $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                        $this->config->item('rest_message_field_name')  => $this->lang->line('order_process_stuck')
                    ], REST_Controller::HTTP_OK);
                }
            }

            /* Delivery Charge Add */
            $all_item_fianl_amount = $all_item_fianl_amount + $delivery_charge;
            $differentiated_amount = number_format($all_item_fianl_amount - $item_total_amount, 2);

            if ($differentiated_amount > 0.10) {
                $this->response([
                    $this->config->item('rest_status_field_name') => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name') => $this->lang->line('order_final_amount_not_match')
                ], REST_Controller::HTTP_OK);
            }

            $order_data = [
                'user_id'                   => $authorized_user['account']->id,
                'restaurant_id'             => $restaurant_id,
                'order_unique_id'           => rand(10000,100000000),
                'order_place_datetime'      => now(),
                'restaurant_latitude'       => $fetch_restaurant->restaurant_latitude,
                'restaurant_longitude'      => $fetch_restaurant->restaurant_longitude,
                'dropoff_latitude'          => $dropoff_latitude,
                'dropoff_longitude'         => $dropoff_longitude,
                'delivery_type'             => $this->input->post('delivery_type'),
                'total_amount'              => $all_item_fianl_amount,
                'delivery_charge'           => !empty($delivery_charge) ? $delivery_charge : 0,
                'address_id'                => !empty($address_id) ? $address_id : 0,
                'discount_amount'           => !empty($discount_amount) ? $discount_amount : 0,
                'discount_percentage'       => $fetch_restaurant->offer_percentage,
                'promo_usage_id'            => !empty($promo_usage_id) ? $promo_usage_id : 0,
                'additional_note'           => !empty($additional_note) ? $additional_note : "",
                'order_item_array'          => serialize($order_items),
                'delivery_date'             => date('Y-m-d'),
                'estimated_delivery_time'   => 0,
                'point_discount_amount'     => $point_discount_amount,
                'point_spent'               => $point_spent
            ];

            if ($payment_type != 1) { /* 1 - Card, 2- COD */
                $order_data['payment_status']   = 0;
                $order_data['payment_type']     = 2;
                $order_data['order_status']     = 0;
                $order_data['order_placed_on']  = time();
            } else {
                $order_data['payment_type']     = 1;
            }

            $order_id = $this->cart_model->place_order($order_data);
            
            if (!empty($order_id)) {

                foreach ($details_for_report_purpose as $key => $value) {
                    if (!empty($promocode_type)) {
                        if ($promocode_type == 1) {
                            $discount_money = ($details_for_report_purpose[$key]["earning"] * $discount_value) / 100;
                            $details_for_report_purpose[$key]["discount"] = $details_for_report_purpose[$key]["earning"] - $discount_money;
                        } else {
                            $discount_money = $discount_value / $item_count;
                            $details_for_report_purpose[$key]["discount"] = $discount_money;
                        }
                    }
                    $details_for_report_purpose[$key]['order_id'] = $order_id;
                }
                $this->cart_model->insert_report_details($details_for_report_purpose);

                $encry_order_id = id_crypt($order_id);

                $payment_data = [
                    'user_id'               => $authorized_user['account']->id,
                    'order_id'              => $order_id,
                    'restaurant_id'         => $restaurant_id,
                    'charge_id'             => '',
                    'discount'              => !empty($discount_amount) ? $discount_amount : 0,
                    'promo_usage_id'        => !empty($promo_usage_id) ? $promo_usage_id : 0,
                    'payment_amount'        => $all_item_fianl_amount,
                    'payment_status'        => 'unpaid',
                    'currency'              => 'GBP',
                    'payment_on'            => time(),
                    'payment_on_date'       => date('Y-m-d')
                ];

                if ($payment_type == 1) { /* 1 - Card, 2- COD */
                    $payment_data['payment_type'] = 'CARD';
                } else {
                    $payment_data['payment_type'] = 'COD';

                    $order = $this->cart_model->get_order($order_id);
                    $order_point = $this->opayo_model->get_order_earning_point($order->order_id, $order->user_id);
                    
                    if (!empty($order->promo_usage_id)) {
                        $this->general_model->update('promocode_usage_history', ['promo_usage_id' => $order->promo_usage_id], ['is_used' => 1]);
                    }

                    if (!$order_point) {
                        $this->opayo_model->insert_order_earning_points($order);
                    }

                    /* Deduct Disount Points */
                    $this->opayo_model->insert_order_discount_points($order);
                }
                
                $payment_id = $this->cart_model->save_transcation($payment_data);

                if (!empty($payment_id)) {
                    $encry_payment_id = id_crypt($payment_id);
                    
                    $payment_url = base_url('opayo/payment/' . $encry_payment_id); 
                    
                    $data = [ 
                        'order_id' => $encry_order_id, 
                        'payment_url' => $payment_url, 
                        'payment_type' => $payment_type,
                        'payment_id' => $encry_payment_id,
                        'checkout_order_id' => id_crypt($encry_order_id, 'd')
                    ];
                    $this->response([
                        $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                        $this->config->item('rest_message_field_name')  => $this->lang->line('order_placed_success'),
                        $this->config->item('rest_data_field_name')     => (object) $data,
                    ], REST_Controller::HTTP_OK);
                }

            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('order_process_stuck')
                ], REST_Controller::HTTP_OK);
            }
        } else {
            // Set the response and exit
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name')  => 'Empty request parameter(s). [ restaurant_id | item_total_amount | item_total_amount | delivery_type | items[] | payment_type | address_id ]'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function point_discount_get()
    {
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

        $user_id = $authorized_user['account']->id;
        $points = $authorized_user['account']->points;

        if (!empty($points)) {
            /* Get Point system Data */
            $point_system = $this->cart_model->get_point_system_data();

            if ($points >= $point_system->per_order_max_point_spent && $authorized_user['account']->earning_amount > 0) {
                $discount_amount = ($point_system->per_order_max_point_spent * $point_system->reach_x_point_reword_amount) / $point_system->reach_x_point_reword;

                if (number_format($discount_amount, 2) <= number_format($authorized_user['account']->earning_amount, 2)) {
                    $data = [
                        'discount_amount'   => number_format($discount_amount, 2),
                        'earning_amount'    => $authorized_user['account']->earning_amount,
                        'points'            => $points
                    ];

                } else {
                    $data = [
                        'discount_amount'   => "0",
                        'earning_amount'    => $authorized_user['account']->earning_amount,
                        'points'            => $points
                    ];
                }
            } else {
                $data = [
                    'discount_amount'   => "0",
                    'earning_amount'    => $authorized_user['account']->earning_amount,
                    'points'            => $points
                ];
            }
        } else {
            $data = [
                'discount_amount'   => "0",
                'earning_amount'    => $authorized_user['account']->earning_amount,
                'points'            => $points
            ];
        }

        $this->response([
            $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
            $this->config->item('rest_message_field_name')  => $this->lang->line('point_discount_success'),
            $this->config->item('rest_data_field_name')     => $data
        ], REST_Controller::HTTP_OK);
    }

    public function order_earning_post()
    {
        /* Check Authentications */
        $headers = $this->input->request_headers();
        
        $authorized_user = $this->general_model->check_authorization($headers);

        if ($authorized_user['status'] != 1) {
            $this->response([
                $this->config->item('rest_status_field_name')   => $authorized_user['status'],
                $this->config->item('rest_message_field_name')  => $authorized_user['message']
            ], REST_Controller::HTTP_OK);
        }
        /* End Check Authentications */

        $this->form_validation->set_rules('order_id', '', 'required');

        if ($this->form_validation->run() == true) {
            $user_id    = $authorized_user['account']->id;
            $order_id   = id_crypt($this->input->post('order_id'), 'd');

            $order_detail = $this->cart_model->get_order($order_id);

            if ($order_detail) {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_one'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('point_discount_success'),
                    $this->config->item('rest_data_field_name')     => ['point' => $order_detail->point_earning]
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                    $this->config->item('rest_message_field_name')  => $this->lang->line('order_detail_not_found')
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                $this->config->item('rest_status_field_name')   => $this->config->item('rest_status_code_zero'),
                $this->config->item('rest_message_field_name')  => 'Empty request parameter(s). [ order_id ]'
            ], REST_Controller::HTTP_OK);
        }
    }
}
