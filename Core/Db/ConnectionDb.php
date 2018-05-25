<?php

namespace Core\Db;

use PDO;

class ConnectionDb{

	private $connectionDb;
	private $db_host;
	private $db_name;
	private $db_user;
	private $db_pass;

	public function __construct($db_host=null,$db_name=null,$db_user=null,$db_pass=null){

		global $db;

		$global_host = (isset($db['config']['host']) ? $db['config']['host'] : false);
		$global_name = (isset($db['config']['name']) ? $db['config']['name'] : false);
		$global_user = (isset($db['config']['user']) ? $db['config']['user'] : false);
		$global_pass = (isset($db['config']['pass']) ? $db['config']['pass'] : false);

		if($global_host && $global_name && $global_user && $global_pass){
			$this->setHost($global_host);
			$this->setName($global_name);
			$this->setUser($global_user);
			$this->setPass($global_pass);
		}
			
		if($db_host && $db_name && $db_user && $db_pass){
			$this->setHost($db_host);
			$this->setName($db_name);
			$this->setUser($db_user);
			$this->setPass($db_pass);
		}

		try{


			$this->connectionDB = new PDO(
				sprintf("mysql:host=%s;dbname=%s",$this->getHost(),$this->getName()),
				sprintf($this->getUser()),
				sprintf($this->getPass()),
				array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
			);	
			
			return $this;

		}catch(Exception $e){
			throw new Exception("Can't connect with database!");	
		}	
		
	}

	public function setHost($host=null){
		$this->db_host = $host;
	}

	public function setName($name=null){
		$this->db_name = $name;
	}

	public function setUser($user=null){
		$this->db_user = $user;
	}

	public function setPass($pass=null){
		$this->db_pass= $pass;
	}

	public function getHost(){
		return $this->db_host;
	}

	public function getName(){
		return $this->db_name;
	}

	public function getUser(){
		return $this->db_user;
	}	

	public function  getPass(){
		return $this->db_pass;
	}


}

