<?php
defined('MVC') OR exit('No direct script access allowed');
/*
 *
//Comfy - Open Source PHP Framework
//Copyright (C) 2016, Emanuel Oprea
 *
//This program is free software: you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation, either version 3 of the License, or
//(at your option) any later version.
 *
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.
 *
//You should have received a copy of the GNU General Public License
//along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

abstract class Controller {

	public function loadModel($name)
	{
		if(file_exists('application/dbmodels/'. strtolower($name) .'.php'))
			require_once('application/dbmodels/'. strtolower($name) .'.php');
		elseif(file_exists('application/models/'. strtolower($name) .'.php'))
			require_once('application/models/'. strtolower($name) .'.php');
	    else
			return;
		$model = new $name($this);
		return $model;
	}

	public function loadView($name)
	{
		$view = new View($name);
		return $view;
	}

	public function loadPlugin($name)
	{
		require(APP_DIR .'plugins/'. strtolower($name) .'.php');
	}

	public function loadHelper($name)
	{
		if(file_exists('application/helpers/'. strtolower($name) .'.php'))
			require_once('application/helpers/'. strtolower($name) .'.php');
		else
			return null;
		$helper = new $name;
		return $helper;
	}

	public function redirect($loc)
	{
		header('Location: '. BASE_URL . $loc);
        die();
	}

}

?>