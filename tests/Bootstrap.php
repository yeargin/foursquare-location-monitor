<?php

// Errors on full!
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

$dir = realpath(dirname(__FILE__));

// Path constants
define('PROJECT_BASE',	realpath($dir.'/../').'/');
define('BASEPATH',		PROJECT_BASE.'system/');
define('APPPATH',		PROJECT_BASE.'application/');
define('VIEWPATH',		PROJECT_BASE.'');

unset($dir);