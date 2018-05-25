<?php

namespace Core\Db;

class Select{

	const LIMIT_DEFAULT = 30;

	private $columns = '*';
	private $table;
	private $whereArray = Array();
	private $wherefield;
	private $whereoperator='=';
	private $wherevalue;
	private $limit;
	private $offset;
	private $sql;
	private $orderbyfield;
	private $orderbyside;


	public function __construct($columns=null){
		$columns = ($columns != array() ? implode(", ",$columns) : "*");
		$this->columns = $columns;
		return $this;
	}

	public function columns($columns=null){
		$columns = ($columns != array() ? implode(", ",$columns) : "*");
		$this->columns = $columns;
		return $this;
	}

	public function orderBy($order_by_field,$order_by_side=null){
		$this->orderbyfield = sprintf(" ORDER BY %s ",$order_by_field);
		if($order_by_field && $order_by_side){
			$this->orderbyside = $order_by_side;
		}		
		return $this;
	}

	public function from($table=null){
		$this->table = $table;
		return $this;
	}

	public function where($field,$value,$extra=null){
		
		$i = count($this->whereArray);
		$i++;

		$this->whereArray[$i]['field'] = $field;
		if($extra){
			$this->whereArray[$i]['operator'] = $value; // "CHECAR SE VALUE FAZER PARTE DOS OPERATOR"
			$this->whereArray[$i]['value'] = $extra;
		}else{
			$this->whereArray[$i]['operator'] = $this->whereoperator;
			$this->whereArray[$i]['value'] = $value;
		}

		return $this;
	}

	public function whereField($where_field){
		$this->wherefield = $where_field;
	}

	public function whereOperator($where_operator){
		$this->whereoperator = $where_operator;
	}

	public function whereValue($where_value){
		$this->wherevalue = $where_value;
	}

	public function whereConcat($where_concat){
		$this->whereconcat = $where_concat;
	}

	public function setWhereArray($where_array= Array()){
		if(is_array($where_array)){
			$this->whereArray = $where_array;
		}else{
			throw new Exception("Where array variable needs be array type!");
		}
	}

	public function limit($limit){
		if(is_int($limit)){
			$this->limit = sprintf(" LIMIT %s ",$limit);
			return $this;	
		}else{
			throw new Exception("Limit variable neeeds be int value!");			
		}
	}

	public function offset($offset){
		if(is_int($offset)){
			if(!$this->limit){
				$this->offset = sprintf("LIMIT %s,%s ",$offset,self::LIMIT_DEFAULT);
			}else{
				$this->offset = sprintf(" OFFSET %s ",$offset);	
			}			
			return $this;	
		}else{
			throw new Exception("Offset variable neeeds be int value!");			
		}
	}


	public function builderSqlSelect(){
		$where = self::whereBuilder();
		$this->sql = sprintf("SELECT %s FROM %s %s %s %s %s %s",
								$this->columns,
								$this->table,
								$where,
								$this->orderbyfield,
								$this->orderbyside,
								$this->limit,$this->offset);
		return $this;
	}

	public function whereBuilder(){
		if(!empty($this->whereArray)){
			$whereString = '';
			foreach ($this->whereArray as $where) {
				if(!isset($where['concat'])){
					$where['value'] = gettype($where['value']) == 'integer' ? $where['value'] : sprintf("'%s'",$where['value']);
					$whereString .= sprintf(" %s %s %s",$where['field'],$where['operator'],$where['value']);
				}else{
					$whereString .= sprintf(" %s ",$where['concat']);
				}
			}	
			return "WHERE ".$whereString;
		}
		return '';		
	}

	public function get(){
		self::builderSqlSelect();
		return $this->sql;
	}


}
