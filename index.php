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


//
//MVC CONSTANTS
//
define('MVC', true);
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'application/');
define('BASE_URL', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

//
//ENVIRONMENT URLs
//
define('LOC_URL', 'http://localhost:8080/');
define('ITG_URL', 'http://test.domain.com/');
define('PRD_URL', 'http://prod.domain.com/');

//
//ENVIRONMENT
//
define('ENVIRONMENT', isset($_SERVER['CMFY_ENV']) ? $_SERVER['CMFY_ENV'] : 'local');

//
//ERROR REPORTING
//
switch (ENVIRONMENT)
{
	case 'local':
    case 'integration':
        //
        //DEBUG MODE
        //
        define('DEBUG', true);
        define('DEBUG_PRINT', true);

		error_reporting(-1);
        ini_set('display_errors', DEBUG_PRINT ? 1 : 0);
        ini_set('display_startup_errors', DEBUG_PRINT ? 1 : 0);
        ini_set('log_errors', DEBUG_PRINT ? 0 : 1);
        ini_set('error_log', 'error.log');
        break;

	case 'production':
        //
        //DEBUG MODE
        //
        define('DEBUG', false);
        define('DEBUG_PRINT', false);

        ini_set('display_errors', DEBUG_PRINT ? 1 : 0);
        ini_set('display_startup_errors', DEBUG_PRINT ? 1 : 0);
        ini_set('log_errors', DEBUG_PRINT ? 0 : 1);
        ini_set('error_log', 'error.log');

		if (version_compare(PHP_VERSION, '5.5', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
        break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

//
//Setup the framework
//
require_once(APP_DIR .'config/config.php');
require_once(ROOT_DIR .'system/model.php');
require_once(ROOT_DIR .'system/dbmodel.php');
require_once(ROOT_DIR .'system/view.php');
require_once(ROOT_DIR .'system/controller.php');
require_once(ROOT_DIR .'system/mvc.php');

//
//Start framework
//
echo mvc();

?>
