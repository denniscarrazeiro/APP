<?php

namespace Model;

use \Core\Db\Model;
use \Core\Db\ConnectionDb;
use \Core\Db\Select;

class Usuario extends Model{

	protected $table = 'adb_usuario';
	protected $primaryKey = 'id_adb_usuario';

}

