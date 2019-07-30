<?php
require_once 'settings.php';

$link = 'https://' . $subdomain . $API['leads'] . '?filter[tasks]=1';

$curl = curl_init($link);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

$code = (int) $code;

try {
  if ($code != 200 && $code != 204) {
    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
  }
} catch (Exception $E) {
  die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
}


$response = json_decode($out, true);
$response = $response['_embedded']['items'];
$leads_without_tasks = [];
foreach ($response as $key => $item) {
  array_push($leads_without_tasks, $item['id']);
}

return count($leads_without_tasks) > 0 ? $leads_without_tasks : false;
