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

abstract class Model {

	private $controller = null;

	public function __construct($controller = null)
	{
		$this->controller = $controller;
	}

    public function __get($name)
    {
        if(method_exists($this, ($method = 'get_'.$name)))
            return $this->$method();
        else
            trigger_error('Unknown property "'. $name .'" in class "'. get_class($this) .'" (function "'. get_class($this) .'->'. $method .'()" not found).', E_USER_WARNING);
    }

    public function __isset($name)
    {
        if(method_exists($this, ($method = 'isset_'.$name)))
            return $this->$method();
        else
            trigger_error('Unknown property "'. $name .'" in class "'. get_class($this) .'" (function "'. get_class($this) .'->'. $method .'()" not found).', E_USER_WARNING);
    }

    public function __set($name, $value)
    {
        if(method_exists($this, ($method = 'set_'.$name)))
            $this->$method($value);
        else
            trigger_error('Unknown property "'. $name .'" in class "'. get_class($this) .'" (function "'. get_class($this) .'->'. $method .'($value)" not found).', E_USER_WARNING);
    }

    public function __unset($name)
    {
        if(method_exists($this, ($method = 'unset_'.$name)))
            $this->$method();
        else
            trigger_error('Unknown property "'. $name .'" in class "'. get_class($this) .'" (function "'. get_class($this) .'->'. $method .'()" not found).', E_USER_WARNING);
    }

	public function loadModel($name)
	{
		if(file_exists('application/dbmodels/'. strtolower($name) .'.php'))
			require_once('application/dbmodels/'. strtolower($name) .'.php');
		elseif(file_exists('application/models/'. strtolower($name) .'.php'))
			require_once('application/models/'. strtolower($name) .'.php');
	    else
			return;
		$model = new $name($this->controller);
		return $model;
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

	public function escapeString($index, $string)
	{
        global $config;
        switch($config['db'][$index]['type'])
        {
            case 'mssql': return $string;
            case 'mysql':
                $connection = mysql_connect($config['db'][$index]['host'], $config['db'][$index]['username'], $config['db'][$index]['password']) or $this->sql_error($query, 'MySQL Connection Error', mysql_error());
                $return = mysql_real_escape_string($string, $connection);
                mysql_close($connection);
                return $return;
        }

	}

	public function escapeArray($index, $array)
	{
        foreach($array as $key => $value)
	        $array[$key] = $this->escapeString($value);
		return $array;
	}

	public function to_bool($val)
	{
	    return !!$val;
	}

    public function to_int($val)
    {
        if(!is_numeric($val))
            return 0;
        return intval($val);
    }

    public function to_float($val)
    {
        if(!is_numeric($val))
            return 0;
        return floatval($val);
    }

	public function to_date($val)
	{
	    return date('Y-m-d', $val);
	}

	public function to_time($val)
	{
	    return date('H:i:s', $val);
	}

	public function to_datetime($val)
	{
	    return date('Y-m-d H:i:s', $val);
	}

    private function sql_error($query, $message, $details)
    {
        echo "<h1>". $message ."</h1>".PHP_EOL;
        if(is_string($details))
            echo $details;
        else
            echo str_replace("\n", '<br />', str_replace(" ", '&nbsp;', print_r($details, true)));
        echo "<h1>&nbsp;</h1><h4>Query</h4><code>". $query ."</code>";
        die();
    }

	public function query($index, $query)
	{
        global $config;

        $resultObjects = array();

        switch($config['db'][$index]['type'])
        {
            case 'mssql':
                $connection = sqlsrv_connect($config['db'][$index]['host'], array('Database'=>$config['db'][$index]['name'], 'UID'=>$config['db'][$index]['username'], 'PWD'=>$config['db'][$index]['password'], 'CharacterSet' => 'UTF-8')) or $this->sql_error($query, 'MSSQL Connection Error', sqlsrv_errors());

		        $result = sqlsrv_query($connection, $query) or $this->sql_error($query, 'MSSQL Query Error', sqlsrv_errors());
		        $resultObjects = array();

		        while($row = sqlsrv_fetch_object($result)) $resultObjects[] = $row;

		        sqlsrv_close($connection);

                break;

            case 'mysql':
                $connection = mysql_connect($config['db'][$index]['host'], $config['db'][$index]['username'], $config['db'][$index]['password']) or $this->sql_error($query, 'MySQL Connection Error', mysql_error());
		        mysql_select_db($config['db'][$index]['name'], $connection) or $this->sql_error($query, 'MySQL Connection Error', mysql_error());

		        $result = mysql_query($query, $connection) or $this->sql_error($query, 'MySQL Query Error ', mysql_error());
		        $resultObjects = array();

		        while($row = mysql_fetch_object($result)) $resultObjects[] = $row;

		        mysql_close($connection);

                break;
        }
        return $resultObjects;
    }

	public function execute($index, $query)
	{
        global $config;
        
        $exec = null;

        switch($config['db'][$index]['type'])
        {
            case 'mssql':
                $connection = sqlsrv_connect($config['db'][$index]['host'], array('Database'=>$config['db'][$index]['name'], 'UID'=>$config['db'][$index]['username'], 'PWD'=>$config['db'][$index]['password'], 'CharacterSet' => 'UTF-8')) or $this->sql_error($query, 'MSSQL Connection Error', sqlsrv_errors());

		        $exec = sqlsrv_query($connection, $query) or $this->sql_error($query, 'MSSQL Execute Error', sqlsrv_errors());

		        sqlsrv_close($connection);

                break;

            case 'mysql':
                $connection = mysql_connect($config['db'][$index]['host'], $config['db'][$index]['username'], $config['db'][$index]['password']) or $this->sql_error($query, 'MySQL Connection Error ', mysql_error());
                mysql_select_db($config['db'][$index]['name'], $connection) or $this->sql_error($query, 'MySQL Connection Error', mysql_error());

                $exec = mysql_query($query, $connection) or $this->sql_error($query, 'MySQL Execute Error ', mysql_error());

                mysql_close($connection);

                break;
        }
        return $exec;
    }

}

?>
