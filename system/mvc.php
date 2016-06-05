<?php
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

//foreach (glob('application/controllers/*.php') as $file)
//    require_once($file);
//foreach (glob('application/models/*.php') as $file)
//    require_once($file);
//foreach (glob('application/dbmodels/*.php') as $file)
//    require_once($file);
//foreach (glob('application/helpers/*.php') as $file)
//    require_once($file);

function mvc()
{
	global $config;

	$controller = $config['default_controller'];
	$error_controller = $config['error_controller'];
	$action = 'index';
    $url = preg_replace('/'. str_replace('/', '\/', BASE_URL) . '/', '', $_SERVER['REQUEST_URI'], 1);
    $url = explode('?', $url)[0];
    $segments = explode('/', trim($url, '/'));

	if(isset($segments[0]) && $segments[0] != '') $controller = $segments[0];
	if(isset($segments[1]) && $segments[1] != '') $action = $segments[1];

	if(file_exists('application/controllers/'. strtolower($controller) .'.php'))
		require_once('application/controllers/'. strtolower($controller) .'.php');
	if(file_exists('application/controllers/'. strtolower($error_controller) .'.php'))
		require_once('application/controllers/'. strtolower($error_controller) .'.php');

	if(!class_exists($controller))
		return call_user_func_array(array(new $error_controller, 'index'), $segments);

	if(!method_exists($controller, $action)) {
		if(!method_exists($controller, 'index'))
			return call_user_func_array(array(new $error_controller, 'index'), $segments);
        return call_user_func_array(array(new $controller, 'index'), array_slice($segments, 1));
	}

	return call_user_func_array(array(new $controller, $action), array_slice($segments, 2));

}

?>
