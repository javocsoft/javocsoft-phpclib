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
 * String utilities.
 * 
 * @author JavocSoft 2014
 * @version 1.0
 */
class StringUtils {

	public static function startsWith($data, $tosearch)
	{
	    return strpos($data, $tosearch) === 0;
	}
	
	
	public static function endsWith($data, $tosearch)
	{
		return substr($data, -strlen($tosearch)) == $tosearch;
	}
	
}

?>