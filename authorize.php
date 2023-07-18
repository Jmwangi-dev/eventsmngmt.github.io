*** Authorization Request in PHP ***|
 
$ch = curl_init('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: Basic ' . base64_encode('YOUR_APP_CONSUMER_KEY:YOUR_APP_CONSUMER_SECRET')
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
echo json_decode($response);