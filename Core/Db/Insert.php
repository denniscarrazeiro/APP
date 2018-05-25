<?php

namespace Core\Db;

class Insert{

	private $table = "";
	private $columns= array();
	private $values=array();
	private $sql ="";


	public function __construct($table=null,$data=Array()){
		if($table){
			$this->table = $table;	
		}

		if(is_array($data) && !empty($data)){
			$values = array_values($data);
			$columns = array_keys($data);
			$this->values = self::createValuesFromArray($values);
			$this->columns = $columns;
		}	

		if(is_array($this->values) && !empty($values) && is_array($columns) && !empty($columns)){
			self::builderSqlInsert();
			return $this->sql;
		}

	}

	public function into($table=null){
		$this->table= $table;
		return $this;
	}

	public function columns($columns=Array()){
		if(is_array($columns) && !empty($columns)){
			$this->columns = implode(',', $columns);
		}		
		return $this;
	}

	public function values($values=Array()){
		if(is_array($values) && !empty($values)){
			$this->values = self::createValuesFromArray($values);
		}
		return $this;
	}

	public function createValuesFromArray($values=array()){
		if(is_array($values) && !empty($values)){
			$valuesStringPieces = '';
			foreach ($values as $value) {
				if(gettype($value) == 'string'){
					$valuesStringPieces[] = "'{$value}'";
				}elseif(gettype($value) == 'integer'){
					$valuesStringPieces[] ="{$value}";	
				}
			}
			if($valuesStringPieces){
				$valuesStringPieces = implode(',', $valuesStringPieces);
			}
			return $valuesStringPieces;
		}
		return false;
	}

	public function builderSqlInsert(){
		$this->sql = "INSERT INTO ".$this->table."( ".$this->columns.") VALUES (".$this->values.") ";
		return $this;
	}


}


