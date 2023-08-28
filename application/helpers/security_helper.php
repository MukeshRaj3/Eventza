<?php 
if (!function_exists('id_crypt')) {
	function id_crypt($string, $action = 'e') {
		$secret_key = 'ukeatsencsecretkey';
	    $secret_iv = 'ukeatsencsecretiv';

	    $output = false;
	    $encrypt_method = "AES-256-CBC";
	    $key = hash( 'sha256', $secret_key );
	    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

	    if( $action == 'e' ) {
	        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
	    } else if( $action == 'd' ) {
	        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
	    }

	    return $output;
	}
}
?>