<?php

namespace Core\Application;

use Core\Router\Route;

class Environment{

	public function __construct(){

	}

	public function run(){
		
		// ADD ROUTES
		$path_config_router = WWW_ROOT.DS.CONFIG.DS;
		if(is_file($path_config_router.'routes.php')){
			include_once($path_config_router.'routes.php');
		}else{
			throw new Exception("Route Not Found!", 1);	
		}		

				
	}

}