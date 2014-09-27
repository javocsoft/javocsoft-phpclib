<?php
/**
 * JavocSoft PHP Commons Library.
 *
 *   Copyright (C) 2013 JavocSoft - Javier González Serrano.
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


ini_set('include_path', dirname(__FILE__) . PATH_SEPARATOR . ini_get('include_path'));

spl_autoload_register(function ($classname) {
	$file = preg_replace('/\\\/', DIRECTORY_SEPARATOR, $classname) . '.php';

	if (strpos($file,'clib') !== false) {
		//if(!class_exists($file)){
			include_once LIB_BASEDIR_PATH . $file;
		//}
	}
});
?>