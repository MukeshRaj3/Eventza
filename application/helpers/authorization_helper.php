<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . '/third_party/jwt/vendor/autoload.php';
use \Firebase\JWT\JWT;

class AUTHORIZATION
{
    public static function check_authorization($token)
    {
        $CI =& get_instance();
        $token = self::check_authorization($token);
        if ($token != false && (now() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }
    
    public static function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }

}



if (!function_exists('check_authorization')) {

    function check_authorization($token)
    {
        $CI =& get_instance();

        try {
            return JWT::decode($token, $CI->config->item('jwt_key'),array('HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
}