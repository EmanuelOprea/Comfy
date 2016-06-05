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

class Session_helper {
	
	public $id = null;
	
	public function __construct()
	{
        if(session_status() == PHP_SESSION_NONE)
        {
            session_start();
		    //session_regenerate_id(true);
        }
		$this->id = session_id();
	}
	
	public function set($key, $val)
	{
		$_SESSION["$key"] = $val;
		//setcookie($key, $val);
		//$_COOKIE[$key] = $val;
	}
	
	public function get($key)
	{
        if(isset($_SESSION["$key"]))
    		return $_SESSION["$key"];
        //if(array_key_exists($key, $_COOKIE))
    	//	return $_COOKIE[$key];
        return null;
	}
	
	function destroy()
	{
		session_destroy();
	}
	
}

?>
