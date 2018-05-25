<?php
		
	Route::get('/user','UsuarioController@index');

	
	Route::get('/user/{id}',function($id){
		return $id;
	});	

	