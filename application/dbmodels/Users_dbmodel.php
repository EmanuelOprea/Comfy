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
class Users_dbmodel extends Dbmodel
{
    //
    // Unique link that defines the following properties
    // ConnectionGroup.TableName.PrimaryKey
    // First parameter is the $config['db'][number] second parameter "number" to identify to which database we are connecting to
    // Second parameter is the table name
    // Third parameter is the identification column (primary key)
    //
    // Example:
    //
    // protected $link = '0.Users.UserId';
    //
    protected $link = '0.users.id';
    
    // Connection Timeout
    const TIMEOUT = 86400;
    
    function Create($id = null)
    {
        if($id)
        {
            if(!(parent::Create($id)))
                return null;
            else
            {
                return $id;
            }
        }
        else
            return null;
    }
    
    // Login with LDAP and store in local DB
    function Login($username, $password)
    {
        // Separate $link declared above to be used within custom queries
        //$link = explode('.', $this->link);
        
        // DB Connection Index
        //$index = $link[0];

        // DB Table Name
        //$table = $link[1];

        // DB Primary Key
        //$key = $link[2];

        if($username == null)
            return null;
        $ldap = $this->loadHelper('Ldap_helper');
        if(!$ldap->Authenticate($username, $password))
            return null;
        $user = $ldap->GetPersonInfo($username);
        $this->GetById($user['uid'][0]);
        //
        // Capture user in our database if not exising, otherwise get the user from our db
        //
        //if (!$this->Create($user['uid'][0]))
        //    $this->GetById($user['uid'][0]);
        //
        // Set hypotetical LastLoginDate and login counter by running an SQL UPDATE query (using the settings at begining of method)
        //
        //$this->execute($index, 'UPDATE '. $table .' SET [LastLoginDate] = GETUTCDATE(), [LoginNr] = [LoginNr] + 1 WHERE '. $key .' = \''. $this->Id .'\'');
        //
        $session_helper = $this->loadHelper('Session_helper');
        $session_helper->set('user', $this->Id);
        $session_helper->set('logintime', time());
        return $this;
    }
    
    function GetCurrent()
    {
        $session_helper = $this->loadHelper('Session_helper');
        if($session_helper->get('logintime') + $this::TIMEOUT < time())
        {
            return null;
        }
        $session_helper->set('logintime', time());
        $this->GetById($session_helper->get('user'));
        return $this;
    }
    
    function Delete()
    {
        // Separate $link declared above to be used within custom queries
        //$link = explode('.', $this->link);

        // DB Connection Index
        //$index = $link[0];

        // DB Table Name
        //$table = $link[1];

        // DB Primary Key
        //$key = $link[2];

        // $this->execute($index, 'DELETE FROM [$table] WHERE $key = \''. $this->Id .'\'');
        parent::Delete();
    }
    
    /* 
     * Managing roles for users
     * For this, the assumption is that you have a Role column set in the users table, that is of type INTEGER
     * 
    function HasRole($role)
    {
        if($this->Role == null)
            return false;
        return $this->to_bool($this->Role & $role);
    }
    
    function GrantRole($role)
    {
        $this->Role = $this->Role | $role;
        $this->Save();
    }
    
    function RemoveRole($role)
    {
        $this->Role = $this->Role & ~$role;
        $this->Save();
    }
    */
    
    function get_Json()
    {
        $return = $this->Info;

        /* Assuming you have LastLoginDate and Role set up in our database as columns
        $return->LastLoginDate = $return->LastLoginDate->format('Y-m-d H:i:s');
        
        if($this->HasRole(self::ROLE_ADMIN))
        {
            $return->Role = self::ROLE_ADMIN;
        }
        elseif($this->HasRole(self::ROLE_USER))
        {
            $return->Role = self::ROLE_USER;
        }
        elseif($this->HasRole(self::ROLE_MANAGER))
        {
            $return->Role = self::ROLE_MANAGER;
        }
        elseif($this->HasRole(self::ROLE_IPBM))
        {
            $return->Role = self::ROLE_IPBM;
        }
        */
        return $return;
    }
    
    function get_LdapInfo()
    {
        return $this->loadHelper('Ldap_helper')->GetPersonInfo($this->Id);
    }
    
    function get_FirstName()
    {
        return $this->LdapInfo['givenname'][0];
    }
    
    function get_LastName()
    {
        return $this->LdapInfo['sn'][0];
    }
    
    function get_CompleteName()
    {
        return $this->LdapInfo['cn'][0];
    }
    
    function get_Country()
    {
        return $this->LdapInfo['Country'][0];
    }
}
