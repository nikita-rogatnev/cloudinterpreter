<?php

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

$jpathbase15 = str_replace('/plugins/system/yoonique_zopim', '', dirname(__FILE__));
$jpathbase16 = str_replace('/plugins/system/yoonique_zopim/yoonique_zopim', '', dirname(__FILE__));

define('JPATH_BASE15', $jpathbase15 . '/administrator');
define('JPATH_BASE16', $jpathbase16 . '/administrator');

if (file_exists(JPATH_BASE15 . '/includes/defines.php') && file_exists(JPATH_BASE15 . '/includes/framework.php')) {
	define('JPATH_BASE', JPATH_BASE15);
} else {

	define('JPATH_BASE', JPATH_BASE16);
}

require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );


$mainframe = & JFactory::getApplication('administrator');

JRequest::checkToken() or die('{"error":"Invalid Token"}');


$mainframe = & JFactory::getApplication('site');
$mainframe->initialise();
$zopimPassword = JRequest::getVar('zopimPassword', "");
$zopimUsername = JRequest::getVar('zopimUsername', "");
$zopimSSL = JRequest::getVar('zopimSSL', "");

if ($zopimSSL == "NOSSL")
	define('ZOPIM_BASE_URL', "http://www.zopim.com/");
else
	define('ZOPIM_BASE_URL', "https://www.zopim.com/");
define('ZOPIM_GETACCOUNTDETAILS_URL', ZOPIM_BASE_URL . "plugins/getAccountDetails");
define('ZOPIM_LOGIN_URL', ZOPIM_BASE_URL . "plugins/login");

$logindata = array("email" => $zopimUsername, "password" => $zopimPassword);

if (!extension_loaded('curl')) {
	$loginresult->error = "Can not connect, because extension cURL is not enabled on your server. Please ask your hosting provider to enable cURL.";
	echo json_encode($loginresult);
	return;
}
$loginresult = json_decode(do_post_request(ZOPIM_LOGIN_URL, $logindata));


if (!isset($loginresult)) {
	$loginresult->error = "Connection Error";
	echo json_encode($loginresult);
	return;
}

if (isset($loginresult->error)) {
	echo json_encode($loginresult);
	return;
}

if (!isset($loginresult->salt)) {
	$loginresult->error = "Zopim account info not correct";
	echo json_encode($loginresult);
	return;
}
$account = json_decode(do_post_request(ZOPIM_GETACCOUNTDETAILS_URL, array("salt" => $loginresult->salt)));

if (!isset($account)) {
	$loginresult->error = "Zopim account retrieval failed";
	echo json_encode($loginresult);
	return;
}

if (!isset($account->account_key)) {
	$loginresult->error = "Could not retrieve widget id";
	echo json_encode($loginresult);
	return;
}
echo json_encode($account);

// Register the option settings we will be using

function do_post_request($url, $_data, $optional_headers = null) {

	$data = array();

	while (list($n, $v) = each($_data)) {
		$data[] = urlencode($n) . "=" . urlencode($v);
	}

	$data = implode('&', $data);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);

	if(curl_errno($ch)) {
		$response = '{"error":"Connection error: '.curl_error($ch).'"}';
	}

	if($response == "") {
		$response = '{"error":"Connection error: Zopim did not return any information"}';
	}

	curl_close($ch);
	return $response;
}