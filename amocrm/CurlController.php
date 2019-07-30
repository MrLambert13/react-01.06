<?php

namespace core;

use Exception;

class CurlController
{
  private $_config;
  private $_link;

  const ERRORS = [
    301 => 'Moved permanently',
    400 => 'Bad request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not found',
    500 => 'Internal server error',
    502 => 'Bad gateway',
    503 => 'Service unavailable',
  ];

  /**
   * Initialization configuration
   *
   * @param array $configuration
   *
   * @throws Exception
   */
  public function init($configuration)
  {
    $this->_config = $configuration;
    if (!isset($this->_config)) {
      throw new Exception("Error load configuration");
    }
  }

  public function createLink($apiType, $params = null)
  {
    $this->_link = 'https://'
      . $this->_config['subdomain']
      . $this->_config['domain']
      . $this->_config['apiTypes'][$apiType];

    if (isset($params)) {
      $this->_link .= $params;
    }
    var_dump($this->_link);
  }

  public function request($method = 'GET', $request = null)
  {
    $curl = curl_init($this->_link);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
    curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    if (isset($request)) {
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
    }

    $result = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $code = (int) $code;
    try {
      if ($code != 200 && $code != 204) {
        throw new Exception(isset(self::ERRORS[$code]) ? self::ERRORS[$code] : 'Undescribed error', $code);
      }
    } catch (Exception $E) {
      die('Error: ' . $E->getMessage() . PHP_EOL . 'Error code: ' . $E->getCode());
    }

    return $result;
  }

  public function authorization()
  {
    $this->createLink('auth', '?type=json');
    $out = $this->request('POST', $this->_config['user']);
    $response = json_decode($out, true);
    $response = $response['response'];
    var_dump(
      isset($response['auth'])
        ? 'Authorization successful'
        : 'Authorization failed'
    );
  }
}
