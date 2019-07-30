<?php
/*
* Parameters for apps. Change 'USER_LOGIN', 'USER_HASH', and 'subdomain' for using.
*
* Created by Mr.L@mbert_13
*/
return [
  'user' => [
    'USER_LOGIN' => 'lam_13@mail.ru',
    'USER_HASH' => '1e5f912661b6949e0fa66fb3c3d30550db62d7a2',
  ],
  'subdomain' => 'lam13',
  'domain' => '.amocrm.ru',
  'apiTypes' => [
    'auth' => '/private/api/auth.php',
    'leads' => '/api/v2/leads',
    'tasks' => '/api/v2/tasks'
  ],
];
