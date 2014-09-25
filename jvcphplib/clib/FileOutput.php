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
 * File utility class. 
 *
 * @author JavocSoft 2014
 * @version 1.0
 */
class FileOutput {
	
	/** The size in bytes of 1 MB */
	const MEGABYTE_BYTES = 1048576;
	
	
	/**
	 * Adds a line in the specified file.
	 * 
	 * @param string $file
	 * @param string $line
	 */
	public static function addLineToFile($file, $line){
		date_default_timezone_set(\jvcphplib\clib\DateTimeUtils::DEFAULT_TIMEZONE);
		if($fh = @fopen( $file, "a+" )){
			$line = (date(\jvcphplib\clib\DateTimeUtils::TIME_FORMAT) . " " . $line . "\n");
			fputs( $fh, $line, strlen($line) );
			fclose( $fh );
		}
	}
		
	/**
	 * Adds lines in the specified file.
	 * 
	 * @param string $file
	 * @param array $lines
	 */
	public static function addLinesToFile($file, $lines) {
		$timestamp = \jvcphplib\clib\DateTimeUtils::getCurrentTime();
		foreach ($lines as $line)
		{	
			addLogLine($file, $line);		    
		}				
	}
	
	/**
	 * Updates the file modification time or if not exists,
	 * creates an empty file setting the modification date.
	 * 
	 * @param string $file
	 */
	public static function updateFileModifiedTime($file){		
		touch($file);			
	}
	
	
	/**
	 * Returns TRUE if the safe time has passed, otherwise FALSE.
	 * 
	 * @param String $mSpamCtrlFile
	 * @param int $mSpamCtrlInterval
	 */
	public static function isMailIntervalPassed($mSpamCtrlFile, $mSpamCtrlInterval){
		
		if(!file_exists($mSpamCtrlFile)){
			touch($mSpamCtrlFile);
		}		
		
		date_default_timezone_set(\jvcphplib\clib\DateTimeUtils::DEFAULT_TIMEZONE);
		$now =  date (\jvcphplib\clib\DateTimeUtils::TIME_FORMAT);
		$dateLastMail = self::getFileModifiedTime($mSpamCtrlFile);		
		
		if((strtotime($now) - $mSpamCtrlInterval) < strtotime($dateLastMail)){
			return false;
		}else{
			touch($mSpamCtrlFile);
			return true;
		}
	}
	
	
	//AUXILIAR
	
	private static function getFileModifiedTime($file){
		date_default_timezone_set(\jvcphplib\clib\DateTimeUtils::DEFAULT_TIMEZONE);
		if(!file_exists($file)){			
			$now =  date (\jvcphplib\clib\DateTimeUtils::TIME_FORMAT);
			touch($file);
		}
		
		return date(\jvcphplib\clib\DateTimeUtils::TIME_FORMAT, self::getCorrectMTime($file));		
	}
	
	/*
	 * FIX for PHP.
	 * 
	 * The detection of DST on the time of the file is confused by 
	 * whether the CURRENT time of the current system is currently 
	 * under DST. 
	 * 
	 * @param unknown_type $filePath
	 */
	private static function getCorrectMTime($filePath)
	{
	    $time = filemtime($filePath);
	
	    $isDST = (date('I', $time) == 1);
	    $systemDST = (date('I') == 1);
	
	    $adjustment = 0;
	
	    if($isDST == false && $systemDST == true){
	        $adjustment = 3600;	   
	    }else if($isDST == true && $systemDST == false){
	        $adjustment = -3600;
	    }else{
	        $adjustment = 0;
	    }
	        
	    return ($time + $adjustment);
	} 	
	
}

?>