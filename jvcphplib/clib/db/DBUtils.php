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
 * Database utility class.
 * 
 * @author JavocSoft 2014
 * @version 1.0
 */
class DBUtils{

	/**
	 * Removes any character usable for a SQL Injection attack.
	 *
	 * @param string $param
	 */
	public static function safeParam($param){
		$param = addslashes($param);
		$param = strip_tags($param);
		$param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
		 
		return $param;
	}

	/**
	 * Escapes the text but not the HTML tags.
	 * 
	 * Use it for accets and other valid characters.
	 * 
	 * @param string $text
	 * @param string $charset Optional. Default is UTF-8.
	 * @return string The escaped text.
	 */
	public static function escapeText($text, $charset = 'UTF-8'){		
		return htmlspecialchars_decode(htmlentities($temp, ENT_NOQUOTES, $charset), ENT_NOQUOTES);
	}
	
	
}

?>