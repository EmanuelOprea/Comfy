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

class Email_helper {
    
    public function __construct()
	{
	}

    function send_email($subject, $message, $to = null, $from = null, $cc = null, $path = null, $subject_filename = null, $message_filename = null) {
        if(!$to)
        {
            trigger_error('Error. Must set $to in send_email() !');
            die('Error. Must set $to in send_email() !');
        }
        if(!$from)
        {
            trigger_error('Error. Must set $from in send_email() !');
            die('Error. Must set $from in send_email() !');
        }
        if($path)
        {
            if($message_filename)
            {
                $message_file = @fopen($path.DIRECTORY_SEPARATOR.$message_filename, 'r');
                if(!$message_file)
                {
                    trigger_error('Message file not found');
                    die('Message file not found');
                }
                else 
                {
                    $message = @fread($message_file,filesize($path.DIRECTORY_SEPARATOR.$message_filename));
                    @fclose($message_file);
                }
            }
            if($subject_filename)
            {
                $subject_file = @fopen($path.DIRECTORY_SEPARATOR.$subject_filename, 'r');
                if(!$subject_file)
                {
                    trigger_error('Subject file not found');
                    die('Subject file not found');
                }
                else 
                {
                    $subject = @fread($subject_file,filesize($path.DIRECTORY_SEPARATOR.$subject_filename));
                    @fclose($subject_file);
                }
            }
        }

        $subject = "=?UTF-8?B?" . base64_encode(rawurldecode($subject)) . "?=";
        $message = htmlspecialchars($message, ENT_QUOTES);
        $message = mb_convert_encoding($message, 'HTML-ENTITIES', 'utf-8');
        $message = '<html><body>'. $message;
        $message = str_replace("\r\n", '<br />', $message);
        $message = str_replace("\r", '<br />', $message);
        $message = str_replace("\n", '<br />', $message);
        $message = $message . '</body></html>';

        $headers = '';
        if($cc)
        {
            $headers = 'From: '. $from .PHP_EOL.
                       'CC: '. $cc .PHP_EOL.
                       'MIME-Version: 1.0' .PHP_EOL.
                       'Content-Type: text/html; charset=UTF8' .PHP_EOL;
        }
        else
        {
            $headers = 'From: '. $from .PHP_EOL.
                       'CC: '. $from .PHP_EOL.
                       'MIME-Version: 1.0' .PHP_EOL.
                       'Content-Type: text/html; charset=UTF8' .PHP_EOL;
        }
        ini_set('SMTP', 'smtp.domain.extension');
        ini_set('smtp_port', 25);
        ini_set('sendmail_from', $from);

        mail($to, $subject, $message, $headers);
    }
    
}

?>