<?php

use core\CurlController;

include_once 'CurlController.php';

$configurations = require 'settings.php';

$app = new CurlController();
$app->init($configurations);
$app->authorization();

/*
* Get leads without tasks
*/
$app->createLink('leads', '?filter[tasks]=1');
$out = $app->request('GET');
$response = json_decode($out, true);
$response = $response['_embedded']['items'];
$leads_without_tasks = [];
foreach ($response as $key => $item) {
  array_push($leads_without_tasks, $item['id']);
}

/**
 * Add tasks in finded leads (if last set)
 */
if (count($leads_without_tasks) > 0) {
  $app->createLink('tasks');
  $tasks['add'] = [];
  foreach ($leads_without_tasks as $item) {
    array_push(
      $tasks['add'],
      [
        'element_id' => $item,
        'element_type' => 2,
        'text' => 'Сделка без задачи',
        'complete_till_at' => strtotime("+1 day")
      ],
    );
  }
  // var_dump($tasks);

  $out = $app->request('POST', $tasks);
  $response = json_decode($out, true);
  $response = $response['_embedded']['items'];
  var_dump(
    count($response) === count($leads_without_tasks)
      ? 'Tasks added successful'
      : 'Tasks did not add'
  );
}
