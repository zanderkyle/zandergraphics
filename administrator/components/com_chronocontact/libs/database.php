<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Libs;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Database extends \PDO {
	var $db_user = null;
	var $db_pass = null;
	var $db_name = null;
	var $db_host = null;
	var $db_type = null;
	var $db_prefix = null;
	var $log = array();
	var $connected = false;

	//Database/Platform dependent 
	public static function _setOptions($options = array()){
		if(empty($options)){
			$options['user'] = Base::getConfig('db_user');
			$options['pass'] = Base::getConfig('db_pass');
			$options['name'] = Base::getConfig('db_name');
			$options['host'] = Base::getConfig('db_host');
			$options['type'] = Base::getConfig('db_type');
			$options['prefix'] = Base::getConfig('db_prefix');
		}
		return $options;
	}
	
	function __construct($options, $driver_options = null){
		parent::__construct($options['type'].':dbname='.$options['name'].';host='.$options['host'], $options['user'], $options['pass'], $driver_options);
		$this->_initialize($options);
		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
		//$this->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
	}
	
	function getTablesList(){
		$tables = array();
		$sql = 'SHOW TABLES';
		$query = $this->query($sql);
		$query->execute();
		$this->_log($sql);
		$result = $query->fetchAll(\PDO::FETCH_ASSOC);
		foreach($result as $r){
			$clean = array_values($r);
			$tables[] = $clean[0];
		}
		return $tables;
	}
	
	function getTableInfo($tablename){
		$sql = $this->_prefixTable('DESCRIBE '.$tablename);
		$query = $this->query($sql);
		$query->execute();
		$this->_log($sql);
		return $result = $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	function getTableColumns($tablename){
		$columns = array();
		$sql = 'DESCRIBE '.$tablename;
		$query = $this->query($sql);
		$query->execute();
		$result = $query->fetchAll(\PDO::FETCH_ASSOC);
		foreach($result as $r){
			$columns[] = $r['Field'];
		}
		return $columns;
	}
		
	function getTablePrimary($tablename){
		$columns = array();
		$sql = 'DESCRIBE '.$this->quoteName($tablename);	
		$query = $this->query($sql);	
		$query->execute();
		$this->_log($sql);
		$result = $query->fetchAll(\PDO::FETCH_ASSOC);
		foreach($result as $r){
			if($r['Key'] == 'PRI'){
				return $r['Field'];
			}
		}
		return null;
	}
	
	function quoteName($string, $q = '`'){
		return $q.trim($string, $q).$q;
	}
	
	function get_reserved_words(){
		return array('LIKE', 'ASC', 'DESC', 'OR', 'AND');
	}
	//end dependent stuff
	
	function _initialize($options){
		$this->db_prefix = $options['prefix'];
		$this->db_user = $options['user'];
		$this->db_pass = $options['pass'];
		$this->db_name = $options['name'];
		$this->db_host = $options['host'];
		$this->db_type = $options['type'];
	}
	
	function prefix($tablename = ''){
		if(empty($tablename)){
			return $this->db_prefix;
		}else{
			return $this->db_prefix.$tablename;
		}
	}
	
	function _prefixTable($sql){
		return str_replace('#__', $this->db_prefix, $sql);
	}
	
	function _close($sql){
		return $sql.";";
	}
	
	function _log($sql, $params = array()){
		foreach($params as $k => $v){
			$sql = preg_replace('/:'.$k.'( |,|;|\))/', "'".$v."'$1", $sql);
		}
		$this->log[] = $sql;
	}
	
	function run($sql, $params = array(), $driver_options = array()){
		$sql = $this->_close($sql);
		$sql = $this->_prefixTable($sql);
		$query = $this->prepare($sql, $driver_options);
		$query->execute($params);
		$this->_log($sql, $params);
		return $query->rowCount();
	}
	
	function load($sql, $params = array(), $driver_options = array()){
		$sql = $this->_close($sql);
		$sql = $this->_prefixTable($sql);
		$query = $this->prepare($sql, $driver_options);
		$query->execute($params);
		$this->_log($sql, $params);
		return $query;
	}
	
	function loadObject($sql, $params = array()){
		$query = $this->load($sql, $params);
		return $data = $query->fetch(\PDO::FETCH_OBJ);
	}
	
	function loadObjectList($sql, $params = array()){
		$query = $this->load($sql, $params);
		return $data = $query->fetchAll(\PDO::FETCH_OBJ);
	}
	
	function loadAssoc($sql, $params = array()){
		$query = $this->load($sql, $params);
		return $data = $query->fetch(\PDO::FETCH_ASSOC);
	}
	
	function loadAssocList($sql, $params = array()){
		$query = $this->load($sql, $params);
		return $data = $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	public static function getInstance($options = array()){
		static $instances;
		if(!isset($instances)){
			$instances = array();
		}
		$options = self::_setOptions($options);
		
		ksort($options);
		$id = md5(serialize($options));
		if(empty($instances[$id])){
			try{
				$instances[$id] = new Database($options);
				$instances[$id]->connected = true;
			}catch(\PDOException $e){
				echo $e->getMessage();
				return false;
			}
			return $instances[$id];
		}else{
			return $instances[$id];
		}
	}
		
	function checkDriver($d){
		return in_array($d, \PDO::getAvailableDrivers());
	}	
	//override the query() function to terminate execution
	function query($statement){
		$pdo_state = parent::query($statement);
		if($pdo_state === false){
			echo 'Database Error:'."\n";
			pr($this->errorInfo());
			die();
		}
		return $pdo_state;
	}
	
}