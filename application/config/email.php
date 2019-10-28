<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.gmail.com';
$config['smtp_port'] = '465';
$config['smtp_user'] = 'user@gmail.com';
$config['smtp_pass'] = 'password';
$config['mailtype'] = 'html';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n"; //use double quotes

$config['prefix'] = '[CMS Gallery v10] ';

$config['default_from_email'] = array('Info', 'info@cms_gallery.com');

$config['admin_email'] = array('cms_gallery.com');