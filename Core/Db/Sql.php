<?php

namespace Core\Db;

use Core\Db\Select;
use Core\Db\Insert;
use Core\Db\Update;
use Core\Db\Delete;

class Sql{

	private $connectionDB;

	public function __construct(ConnectionDB $connectionDB){
		$this->connectionDB=$connectionDB;
	}

	public function select($columns=null){
		return new Select($columns);
	}

	public function insert($table=null){
		return new Insert($this->table);
	}

	public function update($table=null){
		return new Update($this->table);
	}

	public function delete($table=null){
		return new Delete($this->table);
	}

}