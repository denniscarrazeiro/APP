<?php

	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
	defined('WWW_ROOT') ? null : define('WWW_ROOT', __DIR__);
	defined('MODEL') ? null : define('MODEL','Model');
	defined('CONTROLLER') ? null : define('CONTROLLER','Controller');
	defined('ROUTER') ? null : define('ROUTER','Router');
	defined('CORE') ? null : define('CORE','Core');
	defined('CONFIG') ? null : define("CONFIG",'Config');
	defined('DB') ? null : define('DB','Db');


	function __autoloader_APP($class = null){			
		// CLASS NAME
		$class = str_replace('\\',DS,$class).'.php';
		// DEFINE PATHS
		$path_config_db = WWW_ROOT.DS.CONFIG.DS;
		$path_model = WWW_ROOT.DS.MODEL.DS;
		$path_controller = WWW_ROOT.DS.CONTROLLER.DS;
		$path_router = WWW_ROOT.DS.CORE.DS.ROUTER.DS;
		$path_config_template = WWW_ROOT.DS.CONFIG.DS;

		// INCLUDE FILES OF CLASS
		if(is_file($path_model.$class)){
			include_once($path_model.$class);
		}else if(is_file($path_controller.$class)){
			include_once($path_controller.$class);
		}else if(is_file($path_router.$class)){
			include_once($path_router.$class);
		}else if(is_file($class)){
			include_once($class);
		}else{
			throw new Exception("Erro ao localizar arquivo de classe de nome:".$class." | ".$path_router.$class);		
		}

		// ADD CONFIG DATABASE 
		if(preg_match("/connectiondb/i",strtolower($class))){
			if(is_file($path_config_db."database.php")){
				global $db; 
				$db = include_once($path_config_db."database.php");	
			}else{
				throw new Exception("Erro ao localizar arquivo de configuração do banco de dados");			
			}		
		}
		
	}

	/* SMARTY ENGINE CONFIGURACAO */
	global $smarty;
	include_once(WWW_ROOT.DS.CORE.DS.'Smarty/Smarty.php');
	$smarty_config = include_once(WWW_ROOT.DS.CONFIG.DS.'template.php');
	$smarty = new Smarty();
	$smarty->compile_dir = $smarty_config['compile_dir'];
	$smarty->cache_dir = $smarty_config['cache_dir'];
	$smarty->cache_modified_check= $smarty_config['cache_modified_check'];
	
	// $smarty->cache_lifetime= $smarty_config['cache_lifetime'];
	// $smarty->error_reporting = $smarty_config['error_reporting'];
	// $smarty->debugging = $smarty_config['debugging'];

	spl_autoload_register('__autoloader_APP');