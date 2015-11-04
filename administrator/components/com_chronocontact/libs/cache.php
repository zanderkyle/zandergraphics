<?php
namespace GCore\Libs;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Cache{
	protected static $instances;
	
	public static function getInstance($domain = 'gcore', $params = array(), $engine = 'file'){
		$domain = !empty($domain) ? $domain : 'gcore';
		$engine = !empty($engine) ? $engine : Base::getConfig('cache_engine');
		if(empty(self::$instances[$engine][$domain])){
			$class = '\GCore\Libs\CacheEngines\\'.Str::camilize($engine);
			self::$instances[$engine][$domain] = new $class($domain, $params);
		}
		return self::$instances[$engine][$domain];
	}

	public function get($key){
		
	}

	public function set($key, $data){
		
	}

	public function clear($key){
		
	}
	
	public function destroy(){
		
	}
}
?>