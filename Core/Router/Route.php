<?php

class Route{

	private $uri;
	private $params;
	private $type;
	private $data;
	private $controller;
	private $action;
	private $self_obj;

	public function __construct(){
		try{

			$this->uri = $_SERVER['REQUEST_URI'];
	    	$this->params = explode('/',$this->uri);	

		}catch(Exception $e){
			
			throw new Exception("Error to retrive url info!");

		}
	}

	public function setupVariablesByParams($params=Array()){

	}

	public function setSelfObj(){

	}

	public function setController($controller){
		$this->controller= $controller;
	}

	public function setAction($action){
		$this->action = $action;
	}

	public function getController(){
		return $this->controller;
	}

	public function getAction(){
		return $this->action;
	}

	public function getParams(){
		return $this->params;
	}


	static public function get($uri,$callback){
		
		if(gettype($callback) == 'object'){
		
			self::closureTraitments($uri,$callback);
		
		}elseif(gettype($callback) == "string"){
			
			$self_obj = new Route();

			if(gettype(strpos($callback,'@')) == 'integer'){
				$piecesCallback = explode('@', $callback);
				$self_obj->setController($piecesCallback[0]);
				$self_obj->setAction($piecesCallback[1]);
				call_user_func_array(array(sprintf('Controller\%s',$self_obj->getController()),$self_obj->getAction()),array());
				exit;
			}else{
				throw new Exception("Malformed callback string!");
			}

		}

		exit;

	}

	static public function closureTraitments($uri,$callback){
		
		$self_obj = new Route();

		$params = explode('/',$uri);	
		$paramsChecked = [];
		$argsFunction = [];
		foreach ($params as $key => $value) {
			if($value){
				$paramsChecked[] = $params[$key];
			}
			if( gettype(strpos($value,'{')) =='integer' && gettype(strpos($value,'}')) =='integer'){
				if(isset($self_obj->params[$key])){
					$argsFunction[] = $self_obj->params[$key];	
				}				
			}
		}	

		$info = new ReflectionFunction($callback);
		$numberParams = $info->getNumberOfParameters();	

		if(count($argsFunction) != $numberParams){
			throw new Exception("Missing params in url!");
		}

		$return = call_user_func_array($callback,$argsFunction);
		echo $return;
		return;	

	}

	static public function post($uri,$data){

	}

	static public function put($uri){

	}

	static public function patch($uri){

	}

	static public function delete($uri){

	}

	static public function options($uri){

	}	

	static public function view($uri=null){

	}
	
}