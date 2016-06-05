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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Welcome to Comfy!</title>
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="<?=BASE_URL?>assets/images/favicon.ico" type="image/x-icon" />

    <!-- Bootstrap CSS -->
    <link href="<?=BASE_URL?>assets/css/bootstrap-3.3.4.min.css" rel="stylesheet" type="text/css" />

    <!-- Jasny Bootstrap CSS -->
    <link href="<?=BASE_URL?>assets/css/jasny-bootstrap-3.1.3.min.css" rel="stylesheet" type="text/css" />

    <!-- Font Awesome CSS -->
    <link href="<?=BASE_URL?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom UI CSS -->
    <link href="<?=BASE_URL?>assets/css/main.css" rel="stylesheet" type="text/css" />

    <!-- jQuery JavaScript -->
    <script src="<?=BASE_URL?>assets/js/jquery-2.2.4.js" type="text/javascript"></script>

    <!-- Boostrap JavaScript -->
    <script src="<?=BASE_URL?>assets/js/bootstrap-3.3.4.min.js" type="text/javascript"></script>

    <!-- Jasny Boostrap JavaScript -->
    <script src="<?=BASE_URL?>assets/js/jasny-bootstrap-3.1.3.min.js" type="text/javascript"></script>

    <!-- Custom UI JavaScript -->
    <script>var base_url = '<?=BASE_URL?>'; </script>

</head>
<body>

    <div id="container">
        <h1>
            <img src="<?=BASE_URL?>assets/images/clipboard.png" />
            Welcome to Comfy!
        </h1>
        <div id="body">
            <p>This is an example view page.</p>

            <p>By default the entry point controller is set in the <strong>~application/config/config.php</strong> file as shown below:</p>
            <code>$config['default_controller'] = 'main';</code>

            <p>If you would like to edit this view page you'll find it located at:</p>
            <code>~application/views/main_view.php</code>

            <p>
                Hang on, this is an extremely early release, documentation will come soon...
            </p>
            <p>
                Meanwhile, keep calm and enjoy Comfy!
            </p>
        </div>
        <p class="footer">
            <strong>Comfy</strong> -
            Copyright (&copy;) [year], [company]
        </p>
    </div>

</body>
</html>
