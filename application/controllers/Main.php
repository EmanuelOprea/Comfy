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

class Main extends Controller {

	public function index()
	{
        $template = $this->loadView('main_view');

        //
        // Running a Model function - example
        //
        //$user = $this->loadModel('Users_dbmodel')->GetCurrent();

        // Making a redirect to login if user was not successfully found
        //if($user == null)
        // $this->redirect('login/'. str_replace('/', '-', ltrim($_SERVER['REQUEST_URI'], '/')));

        // Sending user model to the view
        //$template->set('user', $user);

        $val = 'value';
        $template->set('name', $val);
        $template->render();
	}
}
