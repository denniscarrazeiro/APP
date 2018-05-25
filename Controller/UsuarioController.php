<?php

namespace Controller;

use Core\Viewer\View;

class UsuarioController{

	public function __construct(){

	}

	public function index($id=null){
		return new View('usuario|index');
	}

	public function edit($id){

	}

	public function delete($id){

	}


}