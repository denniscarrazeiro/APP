<?php


namespace Core\Db;

use PDO;
use Core\Db\ConnectionDb;
use Core\Db\Sql;
use Core\Db\Select;
use Core\Db\Insert;
use Core\Db\Update;
use Core\Db\Delete;
use Exception;

class Model {

	private $childTable;
	private $childPrimaryKey;
	private $columns = ['*'];
	private $whereArray = Array();
	private $wherefield;
	private $whereoperator='=';
	private $wherevalue;
	private $limit;
	private $offset;
	private $orderbyfield;
	private $orderbyside;
	private $output = 'array';
	private $outputAllowed = ['array','object','json'];
	
	public function __construct(){
		
	}

	public function columns($columns){
		if(is_array($columns)){
			$this->columns = $columns;
			return $this;
		}else{
			throw new Exception("Columns variable needs be array!");
		}
	}

	public function where($field,$value,$extra=null){
		
		$i = count($this->whereArray);
		$i++;

		if($i%2 == 0){
			$index_before = ($i-1);
			if(!isset($this->whereArray[$index_before]['concat'])){
				throw new Exception("Missing Concatenator In Where Clause!");
			}
		}

		$this->whereArray[$i]['field'] = $field;
		if($extra){
			$this->whereArray[$i]['operator'] = $value; // CHECAR SE VALUE FAZER PARTE DOS OPERATOR
			$this->whereArray[$i]['value'] = $extra;
		}else{
			$this->whereArray[$i]['operator'] = $this->whereoperator;
			$this->whereArray[$i]['value'] = $value;
		}

		return $this;
	}

	public function whereconcat($concat){
		$i = count($this->whereArray);
		$i++;
		$this->whereArray[$i]['concat'] = $concat; // CHECAR SE VALUE FAZER PARTE DOS CONCATENADORES
		return $this;
	}

	public function orderBy($order_by_field,$order_by_side=null){
		$this->orderbyfield = $order_by_field;
		if($order_by_side){
			$this->orderbyside = $order_by_side;
		}
		return $this;
	}

	public function limit($limit){
		if(is_int($limit)){
			$this->limit = $limit;
			return $this;	
		}else{
			throw new Exception("Limit variable neeeds be int value!");			
		}
	}

	public function offset($offset){
		if(is_int($offset)){
			$this->offset = $offset;
			return $this;	
		}else{
			throw new Exception("Offset variable neeeds be int value!");			
		}
	}

	public function output($output){
		$this->output = $output;
		return $this;
	}

	public function get($id=null){
		
		self::checkChildVariables();

		if($id){
			$db = new ConnectionDb();
			$select = new Select();
			$stmt = $db->connectionDB
					   ->prepare($select->from(self::getChildTable())
									    ->where([self::getChildPrimaryKey()=>$id])
									    ->get());	
			$ret = $stmt->execute();
			$ret = $stmt->fetch(PDO::FETCH_ASSOC);
			return $ret ? $ret : false;			

		}else{
			throw new Exception("Missing id variable!");
		}

	}

	public function getAll($limit=null,$offset=null){

		self::checkChildVariables();

		$db = new ConnectionDb();
		$select = new Select();
		$select->from(self::getChildTable());

		if($this->columns){
			$select->columns($this->columns);
		}

		if($this->whereArray){
			$select->setWhereArray($this->whereArray);
		}

		if($this->orderbyfield){
			$select->orderBy($this->orderbyfield,$this->orderbyside);
		}

		if(is_int($limit)){
			$select->limit($limit);
		}

		if(is_int($offset)){
			$select->offset($offset);
		}

		if(is_int($this->limit)){
			$select->limit($this->limit);
		}

		if(is_int($this->offset)){
			$select->offset($this->offset);
		}

		$stmt = $db->connectionDB->prepare($select->get());	
		$ret = $stmt->execute();

		if(gettype(array_search(strtolower($this->output),$this->outputAllowed)) != 'integer'){
			throw new Exception("This output is not recognize!");
		}

		if($this->output == "array"){
			$ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ret ? $ret : false;			
		}
		if($this->output == "json"){
			$ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ret ? json_encode($ret) : false;				
		}

		if($this->output == 'object'){
			$ret = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $ret ? $ret : false;					
		}
		
		return false;

	}

	public function count(){
	
		self::checkChildVariables();	

		$db = new ConnectionDb();
		$sql = new Sql($db);
		$stmt = $db->connectionDB
				   ->prepare($sql->select(Array('COUNT(*) as total'))
				   				->from(self::getChildTable())
				   				->get());
		$ret = $stmt->execute();
		$ret = $stmt->fetch(PDO::FETCH_ASSOC);
		return $ret ? intval($ret['total']) : false;
		
	}

	public function checkChildVariables(){
		try{
			if(!isset($this->table) || !isset($this->primaryKey)){
				throw new Exception("Child class missing table or primary key variable!");	
			}else{
				self::setChildTable($this->table);
				self::setChildPrimaryKey($this->primaryKey);
			}
		}catch(Exception $e){
			throw new Exception("Child class missing table or primary key variable!");
		}		
	}

	public function setChildTable($child_table){
		$this->childTable = $child_table;
	}

	public function setChildPrimaryKey($child_primary_key){
		$this->childPrimaryKey = $child_primary_key;
	}

	public function getChildTable(){
		return $this->childTable;
	}

	public function getChildPrimaryKey(){
		return $this->childPrimaryKey;
	}

}
