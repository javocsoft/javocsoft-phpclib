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
 * Security related helper class.
 * 
 * @author JavocSoft 2014
 * @version 1.0
 */
class SecurityUtils {
	
	/**
	 * Creates a unique HMAC secret Key.
	 * 
	 * @param string $textData
	 * @return string
	 */
	public static function createHMACSecretKey ($textData) {
		
		$salt = uniqid(null,true);
		$hmac_key = strtoupper(hash("sha256", ($textData . $salt), false));
		
		return $hmac_key;
	}
	
	/**
	 * Creates an HMAC SHA-1 signature for the given data.
	 * 
	 * @param string $textData 	Data to sign.
	 * @param string $appToken 	Token
	 * @param string $hmacKey 	HMAC secret key.
	 * @param boolean $base64 	Set to true to get the signature in base64 format.
	 * @return string
	 */
	public static function generateHMACSignature ($textData, $appToken, $hmacKey, $base64 = false) {
		$res = hash_hmac('sha1', ($textData . $appToken), $hmacKey);
		if($base64) {
			$res = base64_encode($res);
		}	
		return $res;
	}
	
	/**
	 * Generates a signature for the data in an object excluding
	 * the signature field.
	 * 
	 * @param object $obj
	 * @param string $signatureField
	 * @param string $appToken
	 * @param string $hmacKey
	 * @param boolean $base64
	 * @return string
	 */
	public static function generateHMACSignatureFromObject($obj, $signatureField, $appToken, $hmacKey, $base64 = false) {
		
		//We iterate the object to get an array of its properties
		$objArray = array();
		foreach ($obj as $key => $value) {
			if($key!=$signatureField) {
				//We exclude the signature field.
				$objArray[$key] = $value;				
			}
		}		
		$objectString = json_encode($objArray);
		
		return self::generateHMACSignature($objectString, $appToken, $hmacKey, $base64);		
	}
	
	/**
	 * Checks an HMAC signature for the given data using
	 * the spcified token and HMAC key.
	 * 
	 * Returns TRUE if the signature is valid.
	 * 
	 * @param string|object $data
	 * @param string $signature
	 * @param string $signatureField
	 * @param string $hmacKey
	 * @param string $appToken
	 * @param boolean $base64
	 * @return boolean
	 */
	public static function checkHMACSignature($data, $signature, $signatureField, $hmacKey, $appToken, $base64 = false){
		if(is_object($data)) {
			$generatedSignature = self::generateHMACSignatureFromObject($data, $signatureField, $appToken, $hmacKey, $base64);
		}else{
			$generatedSignature = self::generateHMACSignature($data, $appToken, $hmacKey, $base64);
		}
		
		if($signature==$generatedSignature) {
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Converts and object to an array.
	 * 
	 * @param object $obj
	 * @return array
	 */
	public static function objectAsArray($obj) {
		return json_decode(json_encode($obj), true);		
	}
	
	/**
	 * Generates an unique SHA-1 token.
	 * 
	 * @return string
	 */
	public static function createUniqueToken(){
		$prefixData = self::makeRandomString();
		$uid = uniqid($prefixData, true);
		return sha1($uid);
	}
	
	/**
	 * Generates a reference string of the desired length.
	 * 
	 * This method uses also the time to get a unique reference.
	 *  
	 * @param int $length
	 */
	public static function generateReferenceString_v2 ($length) {
		$random = substr(number_format(time() * rand(),0,'',''),0,$length);
		return $random;
	}
	
	/**
	 * Generates a reference string of the desired length.
	 * 
	 * @param unknown $length Minimum of 5.
	 * @return string
	 */
	public static function generateReferenceString_v1 ($length) {
	
		while (strlen($value) < $length) {
			$value .= str_pad(mt_rand(00000, 99999), 5, "0", STR_PAD_LEFT);
		}
		// as pairs of five were added before, eventually remove some again
		return substr($value, 0, $length);
	}
		
	/**
	 * Gets a random chunk of byte data.
	 * 
	 * @return string
	 */
	public static function getSaltDataBytes($size) {
		$fd = fopen('/dev/random', 'r');
		$bytes = fread($fd, $size);
		return $bytes;
	}
	
	/**
	 * Converts a String to Hex string.
	 * 
	 * @param unknown $string
	 * @return string
	 */
	public static function strToHex($string){
		$hex = '';
		for ($i=0; $i<strlen($string); $i++){
			$ord = ord($string[$i]);
			$hexCode = dechex($ord);
			$hex .= substr('0'.$hexCode, -2);
		}
		return strToUpper($hex);
	}
	
	/**
	 * Converts an hex string to a String.
	 * 
	 * Note:
	 * 	Use ISO8859-1 to print the string in 
	 * the screen.
	 * 
	 * @param unknown $hex
	 * @return string
	 */
	public static function hexToStr($hex){
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2){
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}
	
	/**
	 * Generates a SHA-1 hash for the given text.
	 * 
	 * @param string $data
	 * @return string
	 */
	public static function generateHashFromString($data) {
		return sha1($data);
	}
	
	/**
	 * Creates a unique reference for a given string.
	 * 
	 * @param string $text 			The text.
	 * @param number $textRefSize 	It is the mas number of letters to take from the text
	 * 								for the reference string.
	 * @param string $prefix 		Optional.
	 * @param string $suffix 		Optional.
	 * @return string
	 */
	public static function generateReferenceForString ($text, $textRefSize, $prefix = null, $suffix = null) {
		$ref = self::getRandomCharactersFromString(strtoupper($text), $textRefSize);
		$ref .= self::generateReferenceString_v2(5); //makes it unique
		
		if(!empty($prefix)) {
			$ref = $prefix . $ref;
		}
		
		if(!empty($suffix)) {
			$ref .= $suffix;
		}
		
		return $ref;
	}	
	
	/**
	 * Gets a random characters from the given text.
	 * 
	 * @param unknown $text 	The text
	 * @param unknown $length 	The maximum number of characters to get.
	 * @return string
	 */
	public static function getRandomCharactersFromString($text, $length) {
		$res = "";
		$text = preg_replace("/[^a-zA-Z0-9]+/", "", $text);
		for($i=0;$i<$length;$i++) {
			$pos = rand(0,(strlen($text)-1));			
			$res .= substr($text, $pos, 1);
		}		
		return $res;		
	}
	
	/**
	 * Returns an encrypted & utf8-encoded
	 * 
	 * Install the module:
	 * 	yum install php-mcrypt
	 * 
	 * @param string $pure_string
	 * @param string $encryption_key
	 * @return string Encrypted data string.
	 */
	public static function sim_encrypt($pure_string, $encryption_key) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
		return $encrypted_string;
	}
	
	/**
	 * Returns decrypted original string
	 * 
	 * Install the module:
	 * 	yum install php-mcrypt
	 * 
	 * @param string $encrypted_string
	 * @param string $encryption_key
	 * @return string
	 */
	public static function sim_decrypt($encrypted_string, $encryption_key) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypted_string = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
		//We remove non-utf8 characters from string
		return preg_replace('/[^(\x20-\x7F)]*/','', $decrypted_string);
	}
	
	/**
	 * Nonce is used in combination with a timestamp to
	 * avoid malicious replies in the API.
	 * 
	 * A request with a ts and a nonce should only
	 * be used once.
	 * 
	 * @return string
	 */
	public static function generateNonce() {
		$nonce = hash('sha512', self::makeRandomString());		
		return $nonce;
	}
		
	
	/**
	 * This function is a reasonable secure function that
	 * generates a random string with the specified size.
	 * 
	 * @param number $bits
	 * @return string
	 */
	public static function makeRandomString($bits = 256) {
		$bytes = ceil($bits / 8);
		$return = '';
		for ($i = 0; $i < $bytes; $i++) {
			$return .= chr(mt_rand(0, 255));
		}
		return $return;
	}
}

?>