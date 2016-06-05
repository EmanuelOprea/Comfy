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

abstract class Dbmodel extends Model {

    protected $protected_id = null;

	public function __construct($controller = null)
	{
        if(!isset($this->link))
            trigger_error('No property "link" in class "'. get_class($this) .'".', E_USER_WARNING);
        else
            parent::__construct($controller);
	}

    public function Create($id = null)
    {
        $link = explode('.', $this->link);
        $index = $link[0];
        $table = $link[1];
        $key = $link[2];
        if($id)
        {
            $this->protected_id = $id;
            $query = $this->query($index, 'SELECT '. $table .'.'. $key .' FROM '. $table .' WHERE '. $key .' = \''. $this->protected_id .'\'');
            if(!empty($query))
                return null;
        }
        else
            do {
                $this->protected_id = mt_rand(100000000, 1000000000);
                $query = $this->query($index, 'SELECT '. $table .'.'. $key .' FROM '. $table .' WHERE '. $key .' = \''. $this->protected_id .'\'');
            } while (!empty($query));
        $this->execute($index, 'INSERT INTO '. $table .' ('. $key .') VALUES (\''. $this->protected_id .'\')');
        $this->GetById($this->protected_id);
        return $this;
    }

    public function GetAll($querystring = '')
    {
        $link = explode('.', $this->link);
        $index = $link[0];
        $table = $link[1];
        $key = $link[2];
        $query = $this->query($index, 'SELECT '. $table .'.'. $key .' FROM '. $table .' '. $querystring);
        $result = array();
        foreach($query as $item)
            $result[] = $this->loadModel(get_class($this))->GetById($item->{$key});
        return $result;
    }

    public function GetAllArray($querystring = '')
    {
        $link = explode('.', $this->link);
        $index = $link[0];
        $table = $link[1];
        $key = $link[2];
        return $this->query($index, 'SELECT '. $table .'.* FROM '. $table .' '. $querystring);
    }

    public function GetAllDistinct($fields = '', $querystring = '')
    {
        $link = explode('.', $this->link);
        $index = $link[0];
        $table = $link[1];
        $key = $link[2];
        return $this->query($index, 'SELECT DISTINCT '. $fields .' FROM '. $table .' '. $querystring);
    }


    public function GetCount($querystring = '')
    {
        $link = explode('.', $this->link);
        $index = $link[0];
        $table = $link[1];
        $key = $link[2];
        $query = $this->query($index, 'SELECT COUNT('. $table .'.'. $key .') AS Count FROM '. $table .' '. $querystring);
        return $query[0]->Count;
    }

    public function GetById($id)
    {
        if(!$id)
            return null;
        $link = explode('.', $this->link);
        $index = $link[0];
        $table = $link[1];
        $key = $link[2];
        $query = $this->query($index, 'SELECT '. $table .'.'. $key .' FROM '. $table .' WHERE '. $key .' = \''. $id .'\'');
        if(empty($query))
            return null;
        $this->protected_id = $query[0]->{$key};
        return $this;
    }

    public function Delete()
    {
        $link = explode('.', $this->link);
        $index = $link[0];
        $table = $link[1];
        $key = $link[2];
        if(isset($this->Deleted))
            $this->execute($index, 'UPDATE '. $table .' SET Deleted = CURRENT_TIMESTAMP WHERE '. $key. ' = \''. $this->protected_id .'\' AND Deleted IS NOT NULL');
        else
            $this->execute($index, 'DELETE FROM '. $table .' WHERE '. $key .' = \''. $this->protected_id .'\'');
        $this->protected_id = null;
        unset($this);
        return null;
    }

    public function DeleteAll($querystring = '')
    {
        $link = explode('.', $this->link);
        $index = $link[0];
        $table = $link[1];
        $key = $link[2];
        $result = array();
        if(isset($this->Deleted))
            $this->execute($index, 'UPDATE '. $table .' SET Deleted = 1 '. $querystring);
        else
            $query = $this->query($index, 'DELETE FROM '. $table .' '. $querystring);
        return null;
    }

    public function __get($name)
    {
        if(method_exists($this, ($method = 'get_'.$name)))
            return $this->$method();
        else
        {
            $link = explode('.', $this->link);
            $index = $link[0];
            $table = $link[1];
            $key = $link[2];
            $query = $this->query($index, 'SELECT '. $table .'.'. $name .' FROM '. $table .' WHERE '. $key. ' = \''. $this->protected_id .'\'');
            if(!empty($query))
                return $query[0]->$name;
        }
    }

    public function __isset($name)
    {
        if(method_exists($this, ($method = 'isset_'.$name)))
            return $this->$method();
        else
        {
            $link = explode('.', $this->link);
            $index = $link[0];
            $table = $link[1];
            $key = $link[2];
            $query = $this->query($index, 'SELECT TOP 1 '. $table .'.* FROM '. $table);
            if(!empty($query))
                return isset($query[0]->$name);
            return false;
        }
    }

    public function __set($name, $value)
    {
        if(method_exists($this, ($method = 'set_'.$name)))
            $this->$method($value);
        else
        {
            $link = explode('.', $this->link);
            $index = $link[0];
            $table = $link[1];
            $key = $link[2];
            if($key == $name)
                return;
            if($value === null)
                $this->execute($index, 'UPDATE '. $table .' SET '. $name .' = NULL WHERE '. $key. ' = \''. $this->protected_id .'\'');
            else
                $this->execute($index, 'UPDATE '. $table .' SET '. $name .' = \''. $this->escapeString($index, $value) .'\' WHERE '. $key. ' = \''. $this->protected_id .'\'');
        }
    }

    public function __unset($name)
    {
        if(method_exists($this, ($method = 'unset_'.$name)))
            $this->$method();
        else
        {
            $link = explode('.', $this->link);
            $index = $link[0];
            $table = $link[1];
            $key = $link[2];
            if($name != $name)
                $query = $this->query($index, 'UPDATE '. $table .' SET '. $name .' = DEFAULT WHERE '. $key. ' = \''. $this->protected_id .'\'');
        }
    }

    public function get_Id()
    {
        return $this->protected_id;
    }

    public function get_Info()
    {
        $link = explode('.', $this->link);
        $index = $link[0];
        $table = $link[1];
        $key = $link[2];
        $query = $this->query($index, 'SELECT * FROM '. $table .' WHERE '. $key. ' = \''. $this->protected_id .'\'');
        if(!empty($query))
            return $query[0];
    }

}

?>
