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

class Ldap_helper {
	
    //Datasource
    private $ds = null;

    //Hostname (use LDAP for unsecure or LDAPS if secure is required)
    private $host = 'ldaps://fullyqualifieddomainname';

    //Port
    private $port = '636'; // 339 for unsecure, 636 for secure
    
    public function __construct()
    {
        $this->ds = ldap_connect($this->host, $this->port);
        ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ds, LDAP_OPT_REFERRALS, 0);
    }
    
    function Sanitize($string)
    {
        $sanitized=array('\\' => '\5c',
                         '*' => '\2a',
                         '(' => '\28',
                         ')' => '\29',
                         "\x00" => '\00');
        return str_replace(array_keys($sanitized),array_values($sanitized),$string);
    }
    
    function Authenticate($username, $password)
    {
        $valid = false;
        set_error_handler(function() { });
        // SET BASE_DN like "OU= (organization unit), O= (organization)" from your AD values
        $valid = ldap_bind($this->ds, 'uid='. $this->Sanitize($username) .', ou=, o=', $password);
        echo(ldap_error($this->ds));
        restore_error_handler();
        return $valid;
    }
    
    function GetPersonInfo($username)
    {
        // SET BASE_DN like "OU= (organization unit), O= (organization)" from your AD values
        $search = ldap_search($this->ds, 'ou=, o=', 'uid='. $this->Sanitize($username));
        if(!$search)
            ldap_error($this->ds);
        $entries = ldap_get_entries($this->ds, $search);
        if($entries['count'] == 0)
            return null;
        return $entries[0];
    }

    function GetLdapInfo($base_dn, $query)
    {
        return ldap_get_entries($this->ds, ldap_search($this->ds, $base_dn, $query));
    }
}

?>
