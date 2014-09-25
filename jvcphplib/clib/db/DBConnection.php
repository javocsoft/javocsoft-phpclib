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
namespace jvcphplib\clib\db;

/**
 * Database connection utility class.
 *
 * @author JavocSoft 2014
 * @version 1.0
 */
class DBConnection{


	/**
	 * Gets a connection with a DB using mysqli.
	 *
	 * @param String $host
	 * @param String $usr
	 * @param String $pwd
	 * @param String $db
	 * @param integer $port
	 */
	public static function dbConnect($host,$usr,$pwd,$db,$port){

		if(empty($port)){
			$port = 3306;
		}
		
		$mysqli = new \mysqli($host, $usr, $pwd, $db, (double)$port);
		if (mysqli_connect_errno()) {
			$msg = "Error connecting with MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			\jvcphplib\clib\LoggerUtils::writeLog($msg, LIB_BASEDIR_PATH . APP_LOG_DB_PATH);			
		}else{
			//IMPORTANT! use in conjuction with <meta charset="utf-8"> in the HTML
			$mysqli->query('SET NAMES utf8');
			//$mysqli->set_charset("utf8");			
		}

		return $mysqli;
	}
	
	/**
	 * Closes completely a database connection.
	 * 
	 * @param MySQLi_Conn $mysqli
	 */
	public static function dbClose($mysqli){
		$thread = $mysqli->thread_id;
		$mysqli->close();
		try{
			$mysqli->kill($thread);	
		}catch(\Exception $e){}
	}
}

?>