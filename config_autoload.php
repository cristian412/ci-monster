<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$autoload['packages'] = array();
$autoload['libraries'] = array('session','googleplus'); // edit library/Googleplus.php and unzip third_party folder
$autoload['drivers'] = array();
$autoload['helper'] = array('url','showhtml_helper');
$autoload['config'] = array();
$autoload['language'] = array();
$autoload['model'] = array('Request_model','Tables_model');

