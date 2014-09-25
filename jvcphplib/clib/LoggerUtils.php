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
namespace jvcphplib\clib;


/**
 * A logger utility class.
 * 
 * @author JavocSoft 2014
 * @version 1.0
 */
class LoggerUtils {

	const INFO = "INFO";
	const WARNING = "WARNING";
	const ERROR = "ERROR";
	const CRITICAL = "CRITICAL";
	
	
	
	/**
	 * Logs a message.
	 * 
	 * @param string $message
	 * @param string $logPath location of the log file. It will be set
	 * 						  from the base project folder.
	 */
	public static function writeLog($message, $logPath = null) {		
		self::writeLogTask(self::INFO . " >> " . $message, $logPath);
	}
	
	
	/**
	 * Logs a message with a given log level.
	 * 
	 * @param string $message
	 * @param string $logPath location of the log file. It will be set
	 * 						  from the base project folder.
	 * @param string $logLevel
	 */
	public static function writeLogWithLevel($message, $logPath = null, $logLevel = null) {
		self::writeLogTask($logLevel . " >> " . $message, $logPath);
	}
	
	
	
	//AUXILIAR
	
	private static function writeLogTask($message, $logPath = null) {
		if(!is_null($logPath)){
			$logPath = LIB_BASEDIR_PATH . $logPath;
		}else{
			$logPath = LIB_BASEDIR_PATH . APP_LOG_PATH;
		}
		//http://us3.php.net/manual/es/function.error-log.php
		$now = \jvcphplib\clib\DateTimeUtils::getCurrentTime();
		\error_log('[' . $now . '] ' . $message . PHP_EOL, 3, $logPath);
	}
	
}
