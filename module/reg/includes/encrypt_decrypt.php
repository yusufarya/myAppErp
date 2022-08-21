<?php
$key = '5U7V9w19a21Tya15';
$GLOBALS['ENCRKEY'] = $key;

function encrypt($key, $payload)
{
	$IV_SIZE = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$iv = mcrypt_create_iv($IV_SIZE, MCRYPT_DEV_URANDOM);
	$crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $payload, MCRYPT_MODE_CBC, $iv);
	$combo = $iv . $crypt;
	$garble = base64_encode($iv . $crypt);
	return $garble;
}

function decrypt($key, $garble)
{
	$IV_SIZE = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$combo = base64_decode($garble);
	$iv = substr($combo, 0, $IV_SIZE);
	$crypt = substr($combo, $IV_SIZE, strlen($combo));
	$payload = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $crypt, MCRYPT_MODE_CBC, $iv);
	return $payload;
}
?>