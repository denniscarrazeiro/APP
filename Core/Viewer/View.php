<?php

namespace Core\Viewer;

class View{

	const VIEW_PATH = WWW_ROOT.DS.'View'.DS.'Templates'.DS;
	const EXT_FILE = '.tpl';


	private $template;
	private $template_folder;
	private $template_name;


	public function __construct($template=null){
		self::setTemplate($template);
		self::checkExistsTemplatesDirInEnvironment();
		self::checkIsValidTemplate();
		return $this;
		// self::displayTemplate();
	}	

	public function checkExistsTemplatesDirInEnvironment(){
		if(!is_dir(self::VIEW_PATH)){
			throw new Exception("Template directory not exists. Please create View/templates directory in root project.");
		}
	}
	public function checkIsValidTemplate(){
		$template = self::getTemplate();
		if($template){
			$template_pieces = explode('|',$template);
			$template_folder = ucfirst($template_pieces[0]);
			$template_name = $template_pieces[1];
			if(!is_file(self::VIEW_PATH.$template_folder.DS.$template_name.self::EXT_FILE)){
				throw new Exception("Template not found. Path try: ".self::VIEW_PATH.$template_folder.DS.$template_name.self::EXT_FILE);				
			}else{
				self::setTemplateFolder($template_folder);
				self::setTemplateName($template_name);
				return true;
			}			
		}else{
			throw new Exception("Missing template name variable.");
		}
	}

	public function displayTemplate(){

		global $smarty;
		$smarty->template_dir = self::VIEW_PATH.'Global';
		$smarty->assign('body_path',self::VIEW_PATH.self::getTemplateFolder().DS.self::getTemplateName().self::EXT_FILE);
		$smarty->display("body.tpl");
		exit;
	}

	public function setTemplate($template){
		$this->template = $template;
	}

	public function getTemplate(){
		return $this->template;
	}

	public function setTemplateFolder($template_folder){
		$this->template_folder = $template_folder;
	}

	public function setTemplateName($template_name){
		$this->template_name = $template_name;
	}

	public function getTemplateFolder(){
		return $this->template_folder;
	}

	public function getTemplateName(){
		return $this->template_name;
	}


}