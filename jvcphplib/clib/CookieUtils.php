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
 * Cookie tools.
 * 
 * @author JavocSoft 2014
 * @version 1.0
 */
class CookieUtils {
	
	const EXPIRATION_TIME_SECONDS = 1; //SECONDS
	const EXPIRATION_TIME_MINUTES = 60;//SECONDS
	const EXPIRATION_TIME_HOURS = 3600;//SECONDS
	const EXPIRATION_TIME_DAYS = 86400;//SECONDS
	
	
	/**
	 * Creates a cookie.
	 * 
	 * @param string $name
	 * @param string|number $value
	 * @param number $expirationTime  
	 * @param number $timeUnit See time units.
	 * 
	 * @see vclub_core\clib\CookieUtils#EXPIRATION_TIME_SECONDS
	 * @see vclub_core\clib\CookieUtils#EXPIRATION_TIME_MINUTES
	 * @see vclub_core\clib\CookieUtils#EXPIRATION_TIME_HOURS
	 * @see vclub_core\clib\CookieUtils#EXPIRATION_TIME_DAYS
	 */
	public static function createCookie($name, $value, $expirationTime, $timeUnit) {
		$date_of_expiry = time() + ($expirationTime * $timeUnit) ; //seconds
		setcookie( $name, $value, $date_of_expiry );
	}
	
	
	/**
	 * Returns the value of a cookie.
	 * 
	 * @param string $name The cookie name.
	 * @return 	The value of the cookie or 
	 * 			null if does not exists.
	 */
	public static function getCookieValue($name) {
		if(self::cookieExists($name)) {
			return $_COOKIE[$name];
		}else{
			return null;
		}		
	}
	
	
	/**
	 * Deletes a cookie.
	 * 
	 * @param string $name
	 */
	public static function deleteCookie($name) {
		if(isset($_COOKIE[$name])) {
			unset($_COOKIE[$name]);
			//Empty value and set an old timestamp.
			//Next time the page loads cookie will be deleted.
			setcookie($name, "", time() - 3600);
		}
	}
	
	
	/**
	 * "Updates" a cookie. A cookie can not be updated, it must
	 * be overwrited.
	 * 
	 * @param string $name
	 * @param string|number $value
	 * @param number $expirationTime  
	 * @param number $timeUnit See time units.
	 * 
	 * @see vclub_core\clib\CookieUtils#EXPIRATION_TIME_SECONDS
	 * @see vclub_core\clib\CookieUtils#EXPIRATION_TIME_MINUTES
	 * @see vclub_core\clib\CookieUtils#EXPIRATION_TIME_HOURS
	 * @see vclub_core\clib\CookieUtils#EXPIRATION_TIME_DAYS
	 */
	public static function updateCookie($name, $value, $expirationTime, $timeUnit){
		self::createCookie($name, $value, $expirationTime, $timeUnit);		
	}
	
	/**
	 * Checks if a cookie exists or not.
	 * 
	 * @param string $name
	 * @return boolean
	 */
	public static function cookieExists($name) {
		if(isset($_COOKIE[$name])) {
			return true;
		}else{			
			return false;
		}
	}
	
}

?>