<?php
/**
 * JavocSoft PHP Commons Library.
 *
 *   Copyright (C) 2014 JavocSoft - Javier González Serrano.
 *
 *   This file is part of JavocSoft PHP Commons Library.
 *
 *   This library is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   JavocSoft Commons Library is distributed in the hope that it will
 *   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 *   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with javocSoft Commons Library. If not, see <http://www.gnu.org/licenses/>.
 */
namespace jvcphplib;

/**
 * JavocSoft Commons Library Setup
 *
 * @author JavocSoft 2014
 * @version 1.0
 */

define ("LIB_ERRORS_ENV_MODE_DEV", "DEV");
define ("LIB_ERRORS_ENV_MODE_PROD", "PROD");


//This is where your webapp application is installed
define ("LIB_BASEDIR_PATH", INIT_BASEDIR_PATH);

//System tenporal folder
define ("LIB_TEMP_PATH", sys_get_temp_dir());

//The library home folder
define ("LIB_MODULE_PATH", "jvcphplib");

//Log path
	define ("APP_LOG_PATH", INIT_LOGS_PATH . INIT_APPNAME . ".log");
	define ("APP_LOG_EXCEPTIONS_PATH", INIT_LOGS_PATH . INIT_APPNAME . "_exceptions.log");
	define ("APP_LOG_DB_PATH", INIT_LOGS_PATH . INIT_APPNAME . "_db.log");

//API Segurity related
	define ("LIB_OPENSSL_PATH", INIT_OPENSSL_PATH);
	//Download 'cacert.pem' from 'http://curl.haxx.se/docs/caextract.html'
	define ("LIB_CACERTS_FILE_PATH", __DIR__ . "/clib/resources/certs/cacert.pem");

/**
 * Error handling.
 */
//Configure error reporting
// See http://php.net/error-reporting
// Common Values:
//  E_ALL & ~E_NOTICE  (Show all errors, except for notices and coding standards warnings.)
//  E_ALL & ~E_NOTICE | E_STRICT  (Show all errors, except for notices)
//  E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR  (Show only errors)
//  E_ALL | E_STRICT  (Show all errors, warnings and notices including coding standards.)
//Default Value: E_ALL & ~E_NOTICE
//Development Value: E_ALL | E_STRICT
//Production Value: E_ALL & ~E_DEPRECATED

if(INIT_ERRORS_ENV_MODE==LIB_ERRORS_ENV_MODE_DEV){
	error_reporting(E_ALL);
	ini_set('display_errors',1);
	ini_set('html_errors', 1);
}else if(INIT_ERRORS_ENV_MODE==LIB_ERRORS_ENV_MODE_PROD){
	error_reporting(E_ALL & ~E_DEPRECATED);
	ini_set('display_errors',0);
	ini_set('html_errors', 0);
}else{
	error_reporting(E_ALL);
	ini_set('display_errors',1);
	ini_set('html_errors', 1);
}


?>
