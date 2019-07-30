<?php

/**
 * Created by Mr.L@mbert_13
 */

use core\CurlController;

include_once 'CurlController.php';

$configurations = require 'settings.php';

/**
 * Init config and make authorization
 */
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
$leadsWithoutTasks = [];
foreach ($response as $key => $item) {
  array_push($leadsWithoutTasks, $item['id']);
}

/**
 * Add tasks in finded leads (if last set)
 */
if (count($leadsWithoutTasks) > 0) {
  $app->createLink('tasks');
  $tasks['add'] = [];
  foreach ($leadsWithoutTasks as $item) {
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
  /**
   * Check count finded leads with count added tasks
   */
  $out = $app->request('POST', $tasks);
  $response = json_decode($out, true);
  $response = $response['_embedded']['items'];
  var_dump(
    count($response) === count($leadsWithoutTasks)
      ? 'Tasks added successful'
      : 'Tasks did not add'
  );
}
