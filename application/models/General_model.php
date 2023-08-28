<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . '/third_party/jwt/vendor/autoload.php';
include APPPATH . '/third_party/PHPMailer/PHPMailerAutoload.php';
use \Firebase\JWT\JWT;

class General_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getOne($table,$where)
    {
        $query = $this->db->select()
        ->where($where)
        ->get($table);
        
        return $query->row();
    }

    public function getAll($table,$where='')
    {
        if(!empty($where)){
            $query = $this->db->select()
            ->where($where)
            ->get($table);                              
        } else {
            $query = $this->db->select()
            ->get($table);                              
        }   

        return $query->result();
    }

    public function getAllOrderBy($table,$where='',$order_by = '',$order = '')
    {
        if(!empty($where)){
            $this->db->select();
            $this->db->where($where);
            if(!empty($order_by)){
                if(!empty($order)){
                    $this->db->order_by($order_by,$order);
                }else{
                    $this->db->order_by($order_by,'ASC');
                }
            }
            $query = $this->db->get($table);                             
        }
        else{
            $this->db->select();
            if(!empty($order_by)){
                if(!empty($order)){
                    $this->db->order_by($order_by,$order);
                }else{
                    $this->db->order_by($order_by,'ASC');
                }
            }
            $query = $this->db->get($table);                      
        }   
        return $query->result();
    }

    public function insert($table, $data)
    {
        return $this->db->insert($table,$data);
    }

    public function delete($table,$where)
    {
        return $this->db->where($where)->delete($table);
    }

    public function deleteAll($table)
    {
        return $this->db->empty_table($table);
    }

    public function update($table, $where, $data)
    {
        return $this->db->update($table, $data, $where);
    }

    public function insertAndGetId($table, $data)
    {
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }
    
    public function check_authorization($headers) {   
        if (!empty($headers['x-access-token']) && $authorization = check_authorization($headers['x-access-token'])) {
            if (!empty($authorization->id)) {
                $account = $this->ion_auth->user($authorization->id)->row();
                if (is_null($account)) {
                    return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                } else {
                    if ($account->is_deleted == 1) {
                        return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                    } else {
                        return ['status' => $this->config->item('rest_status_code_one'), 'message' => 'Account found', 'account' => $account];
                    }
                }
            } else {
                return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
            }
        } else {
            if (!empty($headers['X-access-token']) && $authorization = check_authorization($headers['X-access-token']))
            {
                if (!empty($authorization->id)) {
                    $account = $this->ion_auth->user($authorization->id)->row();
                    if (is_null($account)) {
                        return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                    } else {
                        if ($account->is_deleted == 1) {
                            return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                        } else {
                            return ['status' => $this->config->item('rest_status_code_one'), 'message' => 'Account found', 'account' => $account];
                        }
                    }
                } else {
                    return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
                }
            } else {
                return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
            }
        }
    }

    public function check_user_authentication_if_login($headers)
    {   
        if (!empty($headers['x-access-token']) && $authorization = check_authorization($headers['x-access-token']))
        {
            if(!empty($authorization)){
                $account = $this->ion_auth->user($authorization->id)->row();

                if (is_null($account)) {
                    return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                } else {
                    if ($account->is_deleted == 1) {
                        return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                    } else {
                        return ['status' => $this->config->item('rest_status_code_one'), 'message' => 'Account found', 'account' => $account];
                    }
                }
            }else{
                return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
            }
        }else{
            if (!empty($headers['X-access-token']) && $authorization = check_authorization($headers['X-access-token']))
            {
                if (!empty($authorization->id)) {
                    $account = $this->ion_auth->user($authorization->id)->row();
                    if (is_null($account)) {
                        return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                    } else {
                        if ($account->is_deleted == 1) {
                            return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                        } else {
                            return ['status' => $this->config->item('rest_status_code_one'), 'message' => 'Account found', 'account' => $account];
                        }
                    }
                } else {
                    return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
                }
            } else {
                return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
            }
        }
    }

    public function check_driver_authentication($headers) {   
        if (!empty($headers['x-access-token']) && $authorization = check_authorization($headers['x-access-token'])) {
            if (!empty($authorization->id)) {
                $account = $this->getOne('drivers', ['id' => $authorization->id]);
                if (is_null($account)) {
                    return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                } else {
                    return ['status' => $this->config->item('rest_status_code_one'), 'message' => 'Account found', 'account' => $account];
                }
            } else {
                return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
            }
        } else {
            if (!empty($headers['X-access-token']) && $authorization = check_authorization($headers['X-access-token']))
            {
                if (!empty($authorization->id)) {
                    $account = $this->getOne('drivers', ['id' => $authorization->id]);
                    if (is_null($account)) {
                        return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                    } else {
                        return ['status' => $this->config->item('rest_status_code_one'), 'message' => 'Account found', 'account' => $account];
                    }
                } else {
                    return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
                }
            } else {
                return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
            }
        }
    }

    public function check_restaurant_authorization($headers) {   
        if (!empty($headers['x-access-token']) && $authorization = check_authorization($headers['x-access-token'])) {
            if (!empty($authorization->id)) {
                $account = $this->getOne('restaurants', ['id' => $authorization->id]);
                if (is_null($account)) {
                    return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                } else {
                    return ['status' => $this->config->item('rest_status_code_one'), 'message' => 'Account found', 'account' => $account];
                }
            } else {
                return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
            }
        } else {
            if (!empty($headers['X-access-token']) && $authorization = check_authorization($headers['X-access-token']))
            {
                if (!empty($authorization->id)) {
                    $account = $this->getOne('restaurants', ['id' => $authorization->id]);
                    if (is_null($account)) {
                        return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Account not found', 'account' => new stdClass()];
                    } else {
                        return ['status' => $this->config->item('rest_status_code_one'), 'message' => 'Account found', 'account' => $account];
                    }
                } else {
                    return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
                }
            } else {
                return ['status' => $this->config->item('rest_status_unauthentication_code'), 'message' => 'Authentication failed', 'account' => new stdClass()];
            }
        }
    }

    public function send_email_notification($data) {
        $message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $data['email_template'], $data, true);
        
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->config->item('aws_ses_host');
        $mail->SMTPAuth = true;
        $mail->Username = $this->config->item('aws_ses_username');
        $mail->Password = $this->config->item('aws_ses_password');
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
        $mail->addAddress($data['user']->email, $this->config->item('site_title', 'ion_auth'));
        $mail->isHTML(true);
        $mail->Subject = $this->config->item('site_title', 'ion_auth') . ' - ' . $data['email_subject'];
        $mail->Body = $message;
        $mail->send();
    }

    public function unserialize_order($serialized) {
        $data = preg_replace_callback(
        '!s:(\d+):"(.*?)";!', 
        function($m) { 
            return 's:'.strlen($m[2]).':"'.$m[2].'";'; 
        }, 
        $serialized);

        return unserialize($data);
    }
}