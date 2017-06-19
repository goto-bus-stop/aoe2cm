<?php
class EpiCache_Apc extends EpiCache
{
  private $expiry   = null;
  public function __construct($params = array())
  {
    $this->expiry   = !empty($params[0]) ? $params[0] : 3600;
  }

  public function delete($key)
  {
    if(empty($key)){
      return null;
    }
	if(function_exists('apc_delete')) {
		return apc_delete($key);
	} else {
		return null;
	}
  }  

  public function get($key)
  {
    if(empty($key)){
      return null;
    }else if($getEpiCache = $this->getEpiCache($key)){
      return $getEpiCache;
    }else if(function_exists('apc_fetch')){
      $value = apc_fetch($key);
      $this->setEpiCache($key, $value);
      return $value;
    } else {
		return null;
	}
  }

  public function set($key = null, $value = null, $expiry = null)
  {
    if(empty($expiry)) {
      $expiry = $this->expiry;
    }
    if(empty($key) || $value === null)
      return false;

	if(function_exists('apc_store')) {
		apc_store($key, $value, $expiry);
	}
    $this->setEpiCache($key, $value);
    return true;
  }
}
?>
