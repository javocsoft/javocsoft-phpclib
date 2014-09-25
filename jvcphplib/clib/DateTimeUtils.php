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
 * Date & Time utility class.
 * 
 * @author JavocSoft 2014
 * @version 1.0
 */
class DateTimeUtils {

	const TIME_FORMAT ="Y-m-d H:i:s";
	const TIME_LOCALZULU_UTC_FORMAT ="Y-m-dTH:i:s:SZ";
	const DEFAULT_TIMEZONE = "Europe/Madrid";

	const INTERVAL_UNIT_DAY = "D";
	const INTERVAL_UNIT_MONTH = "M";
	const INTERVAL_UNIT_YEAR = "Y";
	const INTERVAL_UNIT_HOUR = "H";
	const INTERVAL_UNIT_MINUTE = "I";
	const INTERVAL_UNIT_SECOND = "S";
	
	
	/**
	 * Returns the fullname of the month in 
	 * spanish.
	 * 
	 * @param string $month
	 * @return string
	 */
	public static function MONTHS_SPANISH($month){
	 	$MONTHS_SPANISH = array(
			"Jan" => "Enero",
			"Feb" => "Febrero",
			"Mar" => "Marzo",
			"Apr" => "Abril",
			"May" => "Mayo",
			"Jun" => "Junio",
			"Jul" => "Julio",
			"Aug" => "Agosto",
			"Sep" => "Septiembre",
			"Oct" => "Octubre",
			"Nov" => "Noviembre",
			"Dec" => "Diciembre"
			);
		return $MONTHS_SPANISH[$month];
	}
	
	/**
	 * Returns the short name of the month
	 * in spanish.
	 * 
	 * @param string $month
	 * @return string
	 */
	public static function MONTHS_SHORT_SPANISH($month) { 
		$MONTHS_SHORT_SPANISH = array(	
			"Jan" => "Ene",
			"Feb" => "Feb",
			"Mar" => "Mar",
			"Apr" => "Abr",
			"May" => "May",
			"Jun" => "Jun",
			"Jul" => "Jul",
			"Aug" => "Ago",
			"Sep" => "Sep",
			"Oct" => "Oct",
			"Nov" => "Nov",
			"Dec" => "Dic"
		);
		return $MONTHS_SHORT_SPANISH[$month];
	}
		
	/**
	 * Returns the fullname of the month in
	 * english.
	 * 
	 * @param string $month
	 * @return string
	 */
	public static function MONTHS_ENGLISH($month) { 
		$MONTHS_ENGLISH = array(	
			"Jan" => "January",
			"Feb" => "February",
			"Mar" => "March",
			"Apr" => "April",
			"May" => "May",
			"Jun" => "June",
			"Jul" => "July",
			"Aug" => "August",
			"Sep" => "September",
			"Oct" => "October",
			"Nov" => "November",
			"Dec" => "December"
		);
		return $MONTHS_ENGLISH[$month];
	}

	/**
	 * Return the fullname of the day 
	 * in spanish.
	 * 
	 * @param string $day
	 * @return string
	 */
	public static function DAYS_SPANISH($day) { 
		$DAYS_SPANISH = array(	
			"Mon" => "Lunes",
			"Tue" => "Martes",
			"Wed" => "Miércoles",
			"Thu" => "Jueves",
			"Fri" => "Viernes",
			"Sat" => "Sábado",
			"Sun" => "Domingo"
		);
		return $DAYS_SPANISH[$day];
	}
	
	/**
	 * Returns the short name of the day
	 * in spanish.
	 * 
	 * @param string $day
	 * @return string
	 */
	public static function DAYS_SHORT_SPANISH($day) { 
		$DAYS_SHORT_SPANISH = array(	
			"Mon" => "Lun",
			"Tue" => "Mar",
			"Wed" => "Mie",
			"Thu" => "Jue",
			"Fri" => "Vie",
			"Sat" => "Sab",
			"Sun" => "Dom"
		);
		return $DAYS_SHORT_SPANISH[$day];
	}
	
	/**
	 * Returns the full english name of the day.
	 * 
	 * @param string $day
	 * @return string
	 */
	public static function DAYS_ENGLISH($day) { 
		$DAYS_ENGLISH =array(	
			"Mon" => "Monday",
			"Tue" => "Tuesday",
			"Wed" => "Wednesday",
			"Thu" => "Thursday",
			"Fri" => "Friday",
			"Sat" => "Saturday",
			"Sun" => "Sunday"
		);
		return $DAYS_ENGLISH[$day];
	}
	
	
	
	/**
	 * Returns the week start and end for the specified date.
	 *  
	 * @param Date $date
	 * @param boolean $weekStartSunday
	 * @return array With a "start" element and an "end" element.
	 * 				 The date format is YYYY-MM-DD.
	 */
	public static function getWeekForDate($date, $timeZone = null, $weekStartSunday = false){
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
		
		$timestamp = strtotime($date);
	
		// Week starts on Sunday
		if($weekStartSunday){
			$start = (date("D", $timestamp) == 'Sun') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Last Sunday', $timestamp));
			$end = (date("D", $timestamp) == 'Sat') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Next Saturday', $timestamp));
		} else { // Week starts on Monday
			$start = (date("D", $timestamp) == 'Mon') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Last Monday', $timestamp));
			$end = (date("D", $timestamp) == 'Sun') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Next Sunday', $timestamp));
		}
			
		return array('start' => $start, 'end' => $end);
	}
	
	/**
	 * Extracts the month from the given date or from
	 * the current date. 
	 * 
	 * @param string $date
	 * @param string $timeZone
	 * @param boolean $asString
	 * @param boolean $fullName
	 * @param boolean $inSpanish
	 * @return number|string
	 */
	public static function getMonthOfDate($date=null, $timeZone = null, $asString=false, $fullName=false, $inSpanish = false) {
		
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
		
		if(!empty($date)){
			$dateObj = new \DateTime($date);
			if($asString){
				if($fullName){
					if($inSpanish) {
						$month = self::MONTHS_SPANISH($dateObj->format("M"));
					}else{
						$month = self::MONTHS_ENGLISH($dateObj->format("M"));
					}
				}else{
					if($inSpanish) {
						$month = self::MONTHS_SHORT_SPANISH($dateObj->format("M"));
					}else{
						$month = $dateObj->format("M");
					}
				}
			}else{
				$month = $dateObj->format("m");
			}
		}else{
			if($asString){
				if($fullName){
					if($inSpanish) {
						$month = self::MONTHS_SPANISH(date("M"));
					}else{
						$month = self::MONTHS_ENGLISH(date("M"));
					}
				}else{
					if($inSpanish) {
						$month = self::MONTHS_SHORT_SPANISH(date("M"));
					}else{
						$month = date("M");
					}
				}
			}else{
				$month = date("m");
			}
		}
			
		return $month;
	}
	
	/**
	 * Extracts the year from the given date or from
	 * the current date.. 
	 * 
	 * @param string $date
	 * @param string $timeZone	 
	 * @return number
	 */
	public static function getYearOfDate($date=null, $timeZone = null) {
		
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
		
		if(!empty($date)){
			$dateObj = new \DateTime($date);
			$year = $dateObj->format("Y");
		}else{
			$year = date("Y");
		}
			
		return $year;
	}
	
	/**
	 * Gets the time part of the given date 
	 * or from the current date.
	 * 
	 * @param string $date
	 * @param string $timeZone
	 * @param string $timeFormat
	 * @return string
	 */
	public static function getTimeOfDate($date=null, $timeZone = null, $timeFormat = null) {
	
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
	
		if(empty($timeFormat)){
			$timeFormat = "H:i:s";
		}
		
		if(!empty($date)){
			$dateObj = new \DateTime($date);
			$time = $dateObj->format($timeFormat);
		}else{
			$time = date($timeFormat);
		}
			
		return $time;
	}
	
	/**
	 * Gets the date part of the given date
	 * or from the current date.
	 *
	 * @param string $date
	 * @param string $timeZone
	 * @param string $dateFormat
	 * @return string
	 */
	public static function getDateOfDate($date=null, $timeZone = null, $dateFormat = null) {
	
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
		
		if(empty($dateFormat)){
			$dateFormat = "Y-m-d";
		}
	
		if(!empty($date)){
			$dateObj = new \DateTime($date);
			$datePart = $dateObj->format($dateFormat);
		}else{
			$datePart = date($dateFormat);
		}
			
		return $datePart;
	}
	
	/**
	 * Extracts the day from the given date or from
	 * the current date.. 
	 * 
	 * @param string $date
	 * @param string $timeZone
	 * @param boolean $asString
	 * @param boolean $fullName
	 * @param boolean $inSpanish
	 * @return number|string
	 */
	function getDayOfDate($date=null, $timeZone = null, $asString=false, $fullName=false, $inSpanish = false) {

		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
		
		if(!empty($date)){
			$dateObj = new \DateTime($date);
			if($asString){
				if($fullName){
					if($inSpanish) {
						$day = self::DAYS_SPANISH($dateObj->format("D"));;
					}else{
						$day = self::DAYS_ENGLISH($dateObj->format("D"));
					}
				}else{
					if($inSpanish) {
						$day = self::DAYS_SHORT_SPANISH($dateObj->format("D"));
					}else{
						$day = $dateObj->format("D");
					}
				}
			}else{
				$day = $dateObj->format("d");
			}
		}else{
			if($asString){
				if($fullName){
					if($inSpanish) {
						$day = self::DAYS_SPANISH(date("D"));
					}else{
						$day = self::DAYS_ENGLISH(date("D"));
					}
				}else{
					if($inSpanish) {
						$day = self::DAYS_SHORT_SPANISH(date("D"));
					}else{
						$day = date("D");
					}
				}
			}else{
				$day = date("d");
			}
		}
			
		return $day;
	}
	
	
	/**
	 * Adds an interval to a date. If no date is specified, 
	 * current date is used.
	 * 
	 * @param object $date
	 * @param string $timezone
	 * @param number $interval
	 * @param string $intervalUnit Use INTERVAL_UNIT_DAY, 
	 * 								INTERVAL_UNIT_MONTH or 
	 * 								INTERVAL_UNIT_YEAR
	 * @return DateTime
	 */
	public static function addIntervalDateToDate($date = null, $timezone = null, $interval, $intervalUnit) {
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
		
		if(!empty($date)) {
			$datetime = $date;
		}else{
			$datetime = new \DateTime(null, new \DateTimeZone($timeZone));
		}
		
		if($intervalUnit == self::INTERVAL_UNIT_DAY ||
			$intervalUnit == self::INTERVAL_UNIT_MONTH ||
			$intervalUnit == self::INTERVAL_UNIT_YEAR) {
			$datetime = $datetime->add(new \DateInterval('P' . $interval . $intervalUnit));
		}
		
		return $datetime;
	}
	
	/**
	 * Substracts an interval to a date. If no date is specified, 
	 * current date is used.
	 * 
	 * @param object $date
	 * @param string $timezone
	 * @param number $interval
	 * @param string $intervalUnit Use INTERVAL_UNIT_DAY, 
	 * 								INTERVAL_UNIT_MONTH or 
	 * 								INTERVAL_UNIT_YEAR
	 * @return DateTime
	 */
	public static function substractIntervalDateToDate($date = null, $timezone = null, $interval, $intervalUnit) {
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
	
		if(!empty($date)) {
			$datetime = $date;
		}else{
			$datetime = new \DateTime(null, new \DateTimeZone($timeZone));
		}
		
		if($intervalUnit == self::INTERVAL_UNIT_DAY ||
			$intervalUnit == self::INTERVAL_UNIT_MONTH ||
			$intervalUnit == self::INTERVAL_UNIT_YEAR) {
			$datetime = $datetime->sub(new \DateInterval('P' . $interval . $intervalUnit));
		}
	
		return $datetime;
	}
	
	/**
	 * Adds an interval time to a date. If no date is specified, 
	 * current date is used.
	 * 
	 * @param object $date
	 * @param string $timezone
	 * @param number $interval
	 * @param string $intervalUnit Use INTERVAL_UNIT_HOUR, 
	 * 								INTERVAL_UNIT_MINUTE or 
	 * 								INTERVAL_UNIT_SECOND
	 * @return DateTime
	 */
	public static function addIntervalTimeToDate($date = null, $timezone = null, $interval, $intervalUnit) {
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
	
		if(!empty($date)) {
			$datetime = $date;
		}else{
			$datetime = new \DateTime(null, new \DateTimeZone($timeZone));
		}
		
		if($intervalUnit == self::INTERVAL_UNIT_HOUR || 
			$intervalUnit == self::INTERVAL_UNIT_MINUTE || 
			$intervalUnit == self::INTERVAL_UNIT_SECOND) {
			$datetime = $datetime->add(new \DateInterval('PT' . $interval . $intervalUnit));
		}
	
		return $datetime;
	}
	
	/**
	 * Substracts an interval time to a date. If no date is specified, 
	 * current date is used.
	 * 
	 * @param object $date
	 * @param string $timezone
	 * @param number $interval
	 * @param string $intervalUnit Use INTERVAL_UNIT_HOUR, 
	 * 								INTERVAL_UNIT_MINUTE or 
	 * 								INTERVAL_UNIT_SECOND
	 * @return DateTime
	 */
	public static function substractIntervalTimeToDate($date = null, $timezone = null, $interval, $intervalUnit) {
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
	
		if(!empty($date)) {
			$datetime = $date;
		}else{
			$datetime = new \DateTime(null, new \DateTimeZone($timeZone));
		}
	
		if($intervalUnit == self::INTERVAL_UNIT_HOUR ||
		$intervalUnit == self::INTERVAL_UNIT_MINUTE ||
		$intervalUnit == self::INTERVAL_UNIT_SECOND) {
			$datetime = $datetime->sub(new \DateInterval('PT' . $interval . $intervalUnit));
		}
	
		return $datetime;
	}
	
	/**
	 * Get the days of the specified 
	 * month.
	 * 
	 * @param number $month
	 * @param number $year
	 * @return number
	 */
	public static function days_in_month($month, $year) {
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}
	
	
	/**
	 * Returns the current time as YYYY-MM-DD HH-MM-SS
	 * string for the specified time zone.
	 *
	 * @param string $timeZone If null, default time zone
	 * 						   is set "Europe/Madrid".
	 * @param string $format If null, default time format.
	 */
	public static function getCurrentTime($timeZone = null, $format = null){
		if(empty($timeZone)){
			date_default_timezone_set(self::DEFAULT_TIMEZONE); 
		}else{
			date_default_timezone_set($timeZone);
		}
		
		$formatToApply = self::TIME_FORMAT;
		if(!empty($format)) {
			$formatToApply = $format;
		}
		
		$timestamp = date($formatToApply);		
		return $timestamp;
	}
	
	/**
	 * Converts a date object to String.
	 * 
	 * If no date is set or if date is not an object,
	 *  current time is returned.
	 * 
	 * @param Date $date An object of Date type.
	 * @param string $format If null, default time format.
	 */
	public static function date2String($date = NULL, $format = NULL){
		$formatToApply = self::TIME_FORMAT;
		if(!empty($format)) {
			$formatToApply = $format;
		}
	
		if(!is_null($date) && is_object($date)){
			return $date->format($formatToApply);
		}else{
			return self::getCurrentDateTime()->format($formatToApply);
		}
	}
	
	/**
	 * Returns the current time as YYYY-MM-DD HH-MM-SS
	 * DateTime object.
	 * 
	 * @param $timeZone
	 * @param string $format If null, default time format.
	 */
	public static function getCurrentDateTime($timeZone = null, $format = null){
		if(empty($timeZone)){
			$timeZone = self::DEFAULT_TIMEZONE;
		}
		
		$formatToApply = self::TIME_FORMAT;
		if(!empty($format)) {
			$formatToApply = $format;
		}
		
		$now = new \DateTime(null, new \DateTimeZone($timeZone));
		$now->format($formatToApply);
		
		return $now;	
	}
	 	
	/**
	 * Converts the UTC datetime string to a local datetime
	 * string.
	 * 
	 * Usage (example):
	 * 
	 *  utc_to_local('2014-04-14T11:00:00.000Z', 'Y-m-d H:i:s', 'Europe/Madrid')
	 * 
	 * @param string $utc_datetime 		The UTC datetime string.
	 * @param string $format_string 	Optional. Default: Y-m-d H:i:s. The desired output format.
	 * @param string $time_zone 		Optional. Default: Europe/Madrid. The local timezone to apply.
	 * @return string
	 */
	public static function utc_to_local($utc_datetime, $format_string = null, $time_zone = null){
		$date = new \DateTime($utc_datetime, new \DateTimeZone('UTC'));
		if(empty($format_string)) {
			$format_string = self::TIME_FORMAT;
		}
		if(empty($time_zone)) {
			$time_zone = self::DEFAULT_TIMEZONE;
		}
		$date->setTimeZone(new \DateTimeZone($time_zone));
		return $date->format($format_string);
	}
	
	/**
	 * Returns the time as YYYY-MM-DD HH-MM-SS
	 * string for the specified time zone and time.
	 *
	 * @param string $timeZone If null, default time zone
	 * 						   is set "Europe/Madrid".
	 * @param string Time
	 */
	public static function getTimeFromString($time, $timeZone = null, $format = null){
		if(empty($timeZone)){
			$timeZone = self::DEFAULT_TIMEZONE;
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
		
		$fFormat = self::TIME_FORMAT;
		if(!empty($format)){
			$fFormat = $format;
		}
				
		$date = new \DateTime($time);
		$date = $date->format($fFormat);
						
		return $date;
	}
	
	/**
	 * Gets a DateTime object for the specified 
	 * date string.
	 * 
	 * @param string $time
	 * @param string $timeZone
	 * @return \DateTime
	 */
	public static function getTimeObjectFromString($time, $timeZone = null){
		if(empty($timeZone)){
			$timeZone = self::DEFAULT_TIMEZONE;
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
	
		$date = new \DateTime($time);		
	
		return $date;
	}
	
	/**
	 * Checks if the given date in string format is 
	 * a valid date time.
	 * 
	 * @param string $time
	 * @param string $timeZone
	 * @return boolean
	 */
	public static function checkIfStringIsValidDateTime($time, $timeZone = null){
		if(empty($timeZone)){
			$timeZone = self::DEFAULT_TIMEZONE;
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
		
		$fFormat = self::TIME_FORMAT;
		if(!empty($format)){
			$fFormat = $format;
		}
		
		$date = new \DateTime($time);
		
		if($date === false) {
			return false;
		}else{
			return true;	
		}		
	}
		
	
	/**
	 * Returns the time as YYYY-MM-DD HH-MM-SS
	 * string for the specified time zone and time.
	 *
	 * @param string $timeZone If null, default time zone
	 * 						   is set "Europe/Madrid".
	 * @param string Time	(in Unix time format)
	 */
	public static function getTimeFromUnixTime($time, $timeZone = null, $format = null){
		if(empty($timeZone)){
			$timeZone = self::DEFAULT_TIMEZONE;
			date_default_timezone_set(self::DEFAULT_TIMEZONE);
		}else{
			date_default_timezone_set($timeZone);
		}
		
		$fFormat = self::TIME_FORMAT;
		if(!empty($format)){
			$fFormat = $format;
		}
		
		$date = gmdate($fFormat, $time);
								
		return $date;
	}

}


?>