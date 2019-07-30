<?php
// POST parameters
$user = array(
  'USER_LOGIN' => 'lam_13@mail.ru',
  'USER_HASH' => '1e5f912661b6949e0fa66fb3c3d30550db62d7a2',
);
$subdomain = 'lam13';
$domain = '.amocrm.ru';
$API = [
  'auth' => "{$domain}/private/api/auth.php?type=json",
  'leads' => "{$domain}/api/v2/leads",
  'tasks' => "{$domain}/api/v2/tasks"
];
