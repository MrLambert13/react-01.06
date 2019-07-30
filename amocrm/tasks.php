<?php
require_once 'settings.php';

$tasks['add'] = array(
  array(
    'element_id' => 989183,
    'element_type' => 2,
    'text' => 'Сделка без задачи',
    'complete_till_at' => strtotime("+1 day")
  )
);

$link = 'https://' . $subdomain . $API['tasks'];

$curl = curl_init($link);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($tasks));
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

$out = curl_exec($curl);

$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

$code = (int) $code;
$errors = array(
  301 => 'Moved permanently',
  400 => 'Bad request',
  401 => 'Unauthorized',
  403 => 'Forbidden',
  404 => 'Not found',
  500 => 'Internal server error',
  502 => 'Bad gateway',
  503 => 'Service unavailable',
);
try {
  if ($code != 200 && $code != 204) {
    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
  }
} catch (Exception $E) {
  die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
}

curl_close($curl); #Завершаем сеанс cURL

$response = json_decode($out, true);
var_dump($response);
