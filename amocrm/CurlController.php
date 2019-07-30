<?php

namespace core;

class CurlController
{
  private $_config;

  public function init(array $configuration){
    $this->_config = $configuration;
    if (!count($this->_config) > 0){
      throw new Exception("Error load configuration);
    }
  }

  private function createLink($apiType, $params = null)
  {
    $result = 'https://' . $subdomain;
    if (isset($params)) {
      $result .= $params;
    }
    return $result;
  }
}
