<?php
phpinfo();
composer require safaricom/daraja-php
require_once 'vendor/autoload.php';
use safaricom\Mpesa\Mpesa;

$consumerkey='your consumer key;'
$consumerSecret ='your consumer secret';
//create an instance of the mpesa class 
$mpesa = Mpesa($consumerKey,$consumerSecret);
//Generate an access token 
$response =$Mpesa->generatedAcessToken();
if ($response['status']=='success') {
	$accessToken =  $response['data']->access_token;

	//perform lipa na MpesaOnline(
	$accessToken,
	$businessShortCode,
	$passkey,
	$amount,
	$phoneNumber,
	$callbackURL,
	accountReference,
	$transactionDescription
	);
	//process the response 
	if ($repsonse['status'] == 'success') {
	//payment request was successful,handle the response data
	$transactionId = $response['data']->CheckoutRequestID;
	//...
	} else {
	//payment request failed,handle the error 
	$errorMessage = $response['data'];
	//...

	} else {
	//Access token generation failed, handle the error 
	$errorMessage = $response['data'];
	//...
	}
