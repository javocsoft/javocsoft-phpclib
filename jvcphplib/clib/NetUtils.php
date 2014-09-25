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
 * Net tools.
 * 
 * @author JavocSoft 2014
 * @version 1.0
 */
class NetUtils {

	/**
	 * Checks if an url exists or not
	 *
	 * @param String $url
	 */
	public static function checkUrl($url){
		$file_headers = @get_headers($url);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$exists = false;
		}
		else {
			$exists = true;
		}

		return $exists;
	}

	
	/**
	 * Makes a GHET to the specified URL.
	 * 
	 * Usage:
	 * 
	 * 		$data = array('first_name' => 'John', 'surname' => 'Sample', 'phone' => '1234567890')
     * 		doGETWithCURL('http://www.url.com/', $data, $returnStatusCode)
	 * 
	 * @param string $url 				The endpoint of this POST call.
	 * @param array $data 				An array of url key-value params
	 * @param string $returnStatusCode 	The resulting status code.
	 * @param boolean $isREST 			Set to TRUE if the request is to a RestFUL service.
	 * @param array $extraHeaders 		Optional. Some extra headers to set in the request.
	 * @param string|mixed $data 		The data to be sent as JSON. It can be plain text or an object.
	 * @param string $caCertsFilePath 	Optional. Required in https (SSL) call. The path to the file containing a list of valid public CA Certificates (PEM format)  
	 * @param string $proxy 			Optional. If a proxy is used, set it here.
	 * @param string $userAgent 		Optional. Set here any custom agent you may want to set.
	 * @param boolean $followLocation  	Optional. Default false. Set to true to make the POSt follow any redirection in the endpoint.
	 * @param boolean $isDebug  		Optional. Default is FALSE. If true, header and curl header data is put also in the body output.
	 * @return boolean|mixed 			In case of error returns FALSE, otherwise the response of the call.
	 */
	public static function doGETWithCURL($url, $data = null, &$returnStatusCode, $isREST = false, $extraHeaders = null, $caCertsFilePath = null, $proxy = null, $userAgent = null, $followLocation = false, $isDebug = false)	{
	
		if(empty($url) OR (!$isREST && empty($data)))
		{
			return false;
		}
	
		//Initializes the Curl
		$ch = curl_init($url);
		if ($ch == FALSE) {
			return FALSE;
		}

		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		if(!is_null($userAgent)){
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		}
		if($followLocation){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		
		//Prepare headers
		$header_list = array(				
								'Connection: Close'
							);
		if(is_array($extraHeaders) && !empty($extraHeaders)) {
			foreach($extraHeaders as $key=>$value){
				$header_list[]=$key . ': ' . $value;
			}
		}
		//..set the headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);
		
		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //seconds

		//http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
		if(\jvcphplib\clib\StringUtils::startsWith($url, "https")){
			if(!is_null($caCertsFilePath)){
				// Download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html"
				// and set the directory path of the certificate as shown below. Ensure the file is
				// readable by the webserver.
				// This is mandatory for some environments.
				//$cert = __DIR__ . "/certs/cacert.pem";
				$caCertsFile = trim($caCertsFilePath);				
			}else{
				$caCertsFilePath = LIB_CACERTS_FILE_PATH;
				self::logMsg("Curl Operation. Endpoint is HTTPS, CACERTS file not specified, using default one (" . $caCertsFilePath . ").", \jvcphplib\clib\LoggerUtils::WARNING);
			}
			curl_setopt($ch, CURLOPT_CAINFO, $caCertsFile);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
		
		//Optional proxy configuration
		if(!is_null($proxy)){
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		}
		
		if($isDebug) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}
		
		if(!$isREST) {
			//url-ify the data for the get  : Actually create datastring
			$fields_string = '';
			foreach($data as $key=>$value){
				$fields_string[]=$key.'='.urlencode($value).'&amp;'; 
			}			
		
			$urlStringData = $url.'?'.implode('&amp;',$fields_string);
		}else{
			$urlStringData = $url;
		}
		curl_setopt($ch, CURLOPT_URL, $urlStringData ); #set the url and get string together
	
		$result = curl_exec($ch);
		
		//We update the status code of the parameter passed by reference.
		$returnStatusCode = self::getHttpStatusCode($ch);
		
		curl_close($ch);
	
		return $result;
	}
	
	/**
	 * Makes a GET to the specified URL downloading the file.
	 *
	 * @param string $url 				The endpoint of this POST call.
	 * @param array $data 				An array of url key-value params
	 * @param string $returnStatusCode 	The resulting status code.
	 * @param boolean $isREST 			Set to TRUE if the request is to a RestFUL service.
	 * @param array $extraHeaders 		Optional. Some extra headers to set in the request.
	 * @param string|mixed $data 		The data to be sent as JSON. It can be plain text or an object.
	 * @param string $caCertsFilePath 	Optional. Required in https (SSL) call. The path to the file containing a list of valid public CA Certificates (PEM format)
	 * @param string $proxy 			Optional. If a proxy is used, set it here.
	 * @param string $userAgent 		Optional. Set here any custom agent you may want to set.
	 * @param boolean $followLocation  	Optional. Default false. Set to true to make the POSt follow any redirection in the endpoint.
	 * @param boolean $isDebug  		Optional. Default is FALSE. If true, header and curl header data is put also in the body output.
	 * @return boolean|mixed 			In case of error returns FALSE, otherwise the response of the call.
	 */
	public static function doGETWithCURL_DOWNLOADFILE($url, $fileName, $data = null, &$returnStatusCode, $isREST = false, $extraHeaders = null, $caCertsFilePath = null, $proxy = null, $userAgent = null, $followLocation = false, $isDebug = false)	{
	
		if(empty($url) OR (!$isREST && empty($data)))
		{
			return false;
		}
	
		//Initializes the Curl
		$ch = curl_init($url);
		if ($ch == FALSE) {
			return FALSE;
		}
	
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		if(!is_null($userAgent)){
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		}
		if($followLocation){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	
		//Prepare headers
		$header_list = array(
				'Connection: Close'
		);
		if(is_array($extraHeaders) && !empty($extraHeaders)) {
			foreach($extraHeaders as $key=>$value){
				$header_list[]=$key . ': ' . $value;
			}
		}
		//..set the headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);
	
		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //seconds
	
		//http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
		if(\jvcphplib\clib\StringUtils::startsWith($url, "https")){
			if(!is_null($caCertsFilePath)){
				// Download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html"
				// and set the directory path of the certificate as shown below. Ensure the file is
				// readable by the webserver.
				// This is mandatory for some environments.
				//$cert = __DIR__ . "/certs/cacert.pem";
				$caCertsFile = trim($caCertsFilePath);				
			}else{
				$caCertsFilePath = LIB_CACERTS_FILE_PATH;
				self::logMsg("Curl Operation. Endpoint is HTTPS, CACERTS file not specified, using default one (" . $caCertsFilePath . ").", \jvcphplib\clib\LoggerUtils::WARNING);				
			}
			curl_setopt($ch, CURLOPT_CAINFO, $caCertsFile);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
	
		//Optional proxy configuration
		if(!is_null($proxy)){
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		}
	
		if($isDebug) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}
	
		if(!$isREST) {
			//url-ify the data for the get  : Actually create datastring
			$fields_string = '';
			foreach($data as $key=>$value){
				$fields_string[]=$key.'='.urlencode($value).'&amp;';
			}
	
			$urlStringData = $url.'?'.implode('&amp;',$fields_string);
		}else{
			$urlStringData = $url;
		}
		curl_setopt($ch, CURLOPT_URL, $urlStringData ); #set the url and get string together
		
		//File to save the contents to
		$fp = fopen ($fileName, 'w+');
		
		//give curl the file pointer so that it can write to it
		curl_setopt($ch, CURLOPT_FILE, $fp);
		
		$result = curl_exec($ch);
		
		//We update the status code of the parameter passed by reference.
		$returnStatusCode = self::getHttpStatusCode($ch);
	
		curl_close($ch);
	
		return $result;
	}
	
	
	
	/**
	 * Makes a DELETE to the specified URL.
	 *
	 * @param string $url 				The endpoint of this DELETE call.
	 * @param string $returnStatusCode 	The resulting status code.
	 * @param string|mixed $data 		Optional. The data to be sent as JSON. It can be plain text or an object.
	 * @param array $extraHeaders 		Optional. Some extra headers to set in the request.
	 * @param string $caCertsFilePath 	Optional. Required in https (SSL) call. The path to the file containing a list of valid public CA Certificates (PEM format)
	 * @param string $proxy 			Optional. If a proxy is used, set it here.
	 * @param string $userAgent 		Optional. Set here any custom agent you may want to set.
	 * @param boolean $followLocation  	Optional. Default false. Set to true to make the POSt follow any redirection in the endpoint.
	 * @param boolean $isDebug  		Optional. Default is FALSE. If true, header and curl header data is put also in the body output.
	 * @return boolean|mixed 			In case of error returns FALSE, otherwise the response of the call.
	 */
	public static function doJSONDeleteWithCURL($url, &$returnStatusCode, $data = null, $extraHeaders = null, $caCertsFilePath = null, $proxy = null, $userAgent = null, $followLocation = false, $isDebug = false){
	
		//Prepares the data to be sent.
		if(!empty($data)) {
			if(!is_string($data)){
				$data_string = json_encode($data);
			}else{
				$data_string = $data;
			}
		}
	
		//Initializes the Curl
		$ch = curl_init($url);
		if ($ch == FALSE) {
			return FALSE;
		}
		//Curl Options (http://curl.haxx.se/libcurl/c/curl_easy_setopt.html)
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		if(!is_null($userAgent)){
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		}
		if($followLocation){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		if(!empty($data)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		}
	
		//Prepare headers
		$header_list = array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string),
				'Connection: Close'
		);
		if(is_array($extraHeaders) && !empty($extraHeaders)) {
			foreach($extraHeaders as $key=>$value){
				$header_list[]=$key . ': ' . $value;
			}
		}
		//..set the headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);
	
		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //seconds
	
		//http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
		if(\jvcphplib\clib\StringUtils::startsWith($url, "https")){
			if(!is_null($caCertsFilePath)){
				// Download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html"
				// and set the directory path of the certificate as shown below. Ensure the file is
				// readable by the webserver.
				// This is mandatory for some environments.
				//$cert = __DIR__ . "/certs/cacert.pem";
				$caCertsFile = trim($caCertsFilePath);				
			}else{
				$caCertsFilePath = LIB_CACERTS_FILE_PATH;
				self::logMsg("Curl Operation. Endpoint is HTTPS, CACERTS file not specified, using default one (" . $caCertsFilePath . ").", \jvcphplib\clib\LoggerUtils::WARNING);				
			}
			curl_setopt($ch, CURLOPT_CAINFO, $caCertsFile);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
	
		//Optional proxy configuration
		if(!is_null($proxy)){
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		}
	
		if($isDebug) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}
	
		$result = curl_exec($ch);
	
		//We update the status code of the parameter passed by reference.
		$returnStatusCode = self::getHttpStatusCode($ch);
	
		return $result;
	}
	
	
	
	/**
	 * Makes a JSON POST to the specified URL. 
	 * 
	 * @param string $url 				The endpoint of this POST call.
	 * @param string $returnStatusCode 	The resulting status code.
	 * @param string|mixed $data 		The data to be sent as JSON. It can be plain text or an object.
	 * @param array $extraHeaders 		Optional. Some extra headers to set in the request.
	 * @param string $caCertsFilePath 	Optional. Required in https (SSL) call. The path to the file containing a list of valid public CA Certificates (PEM format)  
	 * @param string $proxy 			Optional. If a proxy is used, set it here.
	 * @param string $userAgent 		Optional. Set here any custom agent you may want to set.
	 * @param boolean $followLocation  	Optional. Default false. Set to true to make the POSt follow any redirection in the endpoint.
	 * @param boolean $isDebug  		Optional. Default is FALSE. If true, header and curl header data is put also in the body output.
	 * @return boolean|mixed 			In case of error returns FALSE, otherwise the response of the call.
	 */
	public static function doJSONPostWithCURL($url, &$returnStatusCode, $data, $extraHeaders = null, $caCertsFilePath = null, $proxy = null, $userAgent = null, $followLocation = false, $isDebug = false){
	
		//Prepares the data to be sent.
		if(!is_string($data)){
			$data_string = json_encode($data);
		}else{
			$data_string = $data;
		}
	
		//Initializes the Curl
		$ch = curl_init($url);
		if ($ch == FALSE) {
			return FALSE;
		}
		//Curl Options (http://curl.haxx.se/libcurl/c/curl_easy_setopt.html)
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		if(!is_null($userAgent)){
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		}
		if($followLocation){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		
		//Prepare headers
		$header_list = array( 	
								'Content-Type: application/json',
								'Content-Length: ' . strlen($data_string),
								'Connection: Close'
							);
		if(is_array($extraHeaders) && !empty($extraHeaders)) {
			foreach($extraHeaders as $key=>$value){
				$header_list[]=$key . ': ' . $value;
			}
		}
		//..set the headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);
				
		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //seconds
	
		//http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
		if(\jvcphplib\clib\StringUtils::startsWith($url, "https")){
			if(!is_null($caCertsFilePath)){
				// Download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html"
				// and set the directory path of the certificate as shown below. Ensure the file is
				// readable by the webserver.
				// This is mandatory for some environments.
				//$cert = __DIR__ . "/certs/cacert.pem";
				$caCertsFile = trim($caCertsFilePath);				
			}else{
				$caCertsFilePath = LIB_CACERTS_FILE_PATH;
				self::logMsg("Curl Operation. Endpoint is HTTPS, CACERTS file not specified, using default one (" . $caCertsFilePath . ").", \jvcphplib\clib\LoggerUtils::WARNING);				
			}
			curl_setopt($ch, CURLOPT_CAINFO, $caCertsFile);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
	
		//Optional proxy configuration
		if(!is_null($proxy)){
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		}
	
		if($isDebug) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}
		
		$result = curl_exec($ch);
		
		//We update the status code of the parameter passed by reference.
		$returnStatusCode = self::getHttpStatusCode($ch);
		
		return $result;
	}
	
	
	/**
	 * Makes a JSON Multipart POST to the specified URL. In this case, the post data is saved inside
	 * a variable with the name contained in the "postFieldName" parameter. All data is url encoded.
	 * 
	 * Use this when the post body could have more than one field.
	 * 
	 * @param string $url 				The endpoint of this POST call.
	 * @param string $returnStatusCode 	The resulting status code.
	 * @param string $postFieldName 	The name of the field in the POST data that will cointain our data.
	 * @param string|mixed $data 		The data to be sent as JSON. It can be plain text or an object (it will be json_encoded and urlencoded).
	 * @param array $extraHeaders 		Optional. Some extra headers to set in the request.
	 * @param string $caCertsFilePath 	Optional. Required in https (SSL) call. The path to the file containing a list of valid public CA Certificates (PEM format)
	 * @param string $proxy 			optional. If a proxy is used, set it here.
	 * @param string $userAgent 		Optional. Set here any custom agent you may want to set.
	 * @param boolean $followLocation  	Optional. Default false. Set to true to make the POSt follow any redirection in the endpoint.
	 * @param boolean $isDebug 			Optional. Default is FALSE. If true, header and curl header data is put also in the body output.
	 * @return boolean|mixed 			In case of error returns FALSE, otherwise the response of the call.
	 */
	public static function doJSONMultipartPostWithCURL($url, &$returnStatusCode, $postFieldName, $data, $extraHeaders = null, $caCertsFilePath = null, $proxy = null, $userAgent = null, $followLocation = false, $isDebug = false){
	
		//Prepares the data to be sent.
		if(!is_string($data)){
			$data_string = json_encode($data);
		}else{
			$data_string = $data;
		}
	
		$postDataField = array ($postFieldName =>urlencode($data_string));
		
		//Initializes the Curl
		$ch = curl_init($url);
		if ($ch == FALSE) {
			return FALSE;
		}
		//Curl Options (http://curl.haxx.se/libcurl/c/curl_easy_setopt.html)
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);		
		if(!is_null($userAgent)){
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		}
		if($followLocation){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		}		
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataField);
		
		//Prepare headers
		$header_list = array(
								'Content-Type: multipart/form-data',
								'Connection: Close'
							);
		if(is_array($extraHeaders) && !empty($extraHeaders)) {
			foreach($extraHeaders as $key=>$value){
				$header_list[]=$key . ': ' . $value;
			}
		}
		//..set the headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);
		
		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //seconds
	
		//http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
		if(\jvcphplib\clib\StringUtils::startsWith($url, "https")){
			if(!is_null($caCertsFilePath)){
				// Download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html"
				// and set the directory path of the certificate as shown below. Ensure the file is
				// readable by the webserver.
				// This is mandatory for some environments.
				//$cert = __DIR__ . "/certs/cacert.pem";
				$caCertsFile = trim($caCertsFilePath);				
			}else{
				$caCertsFilePath = LIB_CACERTS_FILE_PATH;
				self::logMsg("Curl Operation. Endpoint is HTTPS, CACERTS file not specified, using default one (" . $caCertsFilePath . ").", \jvcphplib\clib\LoggerUtils::WARNING);
			}
			curl_setopt($ch, CURLOPT_CAINFO, $caCertsFile);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
	
		//Optional proxy configuration
		if(!is_null($proxy)){
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		}
	
		if($isDebug) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}
	
		$result = curl_exec($ch);
		
		//We update the status code of the parameter passed by reference.
		$returnStatusCode = self::getHttpStatusCode($ch);
		
		return $result;
	}
	
	/**
	 * Makes a JSON Multipart POST to the specified URL. In this case, the post data is saved inside
	 * a variable with the name contained in the "postFieldName" parameter. All data is url encoded.
	 *
	 * Use this when the post body could have more than one field.
	 *
	 * @param string $url 				The endpoint of this POST call.
	 * @param string $returnStatusCode 	The resulting status code.
	 * @param string $postFieldName 	The name of the field in the POST data that will cointain our data.
	 * @param array|mixed $files 		The files to upload. Use $_FILES['<field_name>'] to get them.
	 * @param array $extraHeaders 		Optional. Some extra headers to set in the request.
	 * @param string $caCertsFilePath 	Optional. Required in https (SSL) call. The path to the file containing a list of valid public CA Certificates (PEM format)
	 * @param string $proxy 			optional. If a proxy is used, set it here.
	 * @param string $userAgent 		Optional. Set here any custom agent you may want to set.
	 * @param boolean $followLocation  	Optional. Default false. Set to true to make the POSt follow any redirection in the endpoint.
	 * @param boolean $isDebug 			Optional. Default is FALSE. If true, header and curl header data is put also in the body output.
	 * @param number timeout		Optional. Default 30 seconds.
	 * @return boolean|mixed 			In case of error returns FALSE, otherwise the response of the call.
	 */
	public static function doUploadPostWithCURL($url, &$returnStatusCode, $postFieldName, $files, $extraHeaders = null, $caCertsFilePath = null, $proxy = null, $userAgent = null, $followLocation = false, $isDebug = false, $timeout = null){
		
		if(!is_array($files['name'])){
			//In case the upload is of type single-file,
			//we fake the request to appears a multiple-selection
			//so we have not to modify this method.
			$files['name'] = array(
									0 => $files['name']
								);
			$files['type'] = array(
									0 => $files['type']
								);
			$files['tmp_name'] = array(
									0 => $files['tmp_name']
								);
			$files['error'] = array(
									0 => $files['error']
								);
			$files['size'] = array(
									0 => $files['size']
								);
		}
				
		//Recreate the upload data.
		$deliveryData = array();		
		foreach($files['name'] as &$fileName) {
			$nameArray[] = $fileName;
		}
		$deliveryData['name'] = $nameArray;		
		foreach($files['type'] as &$fileType) {
			$typeArray[] = $fileType;
		}
		$deliveryData['type'] = $typeArray;		
		foreach($files['tmp_name'] as &$fileTmpName) {
			if (move_uploaded_file($fileTmpName, $fileTmpName . "_upd") === true) {
				chmod($fileTmpName . "_upd", 0666); //To be able afterwards to move, delete it.
			}
			$tmpNameArray[] = $fileTmpName . "_upd";
		}	
		$deliveryData['tmp_name'] = $tmpNameArray;		
		foreach($files['error'] as &$fileError) {			
			$errorArray[] = $fileError;
		}
		$deliveryData['error'] = $errorArray;		
		foreach($files['size'] as &$fileSize) {
			$sizeArray[] = $fileSize;
		}
		$deliveryData['size'] = $sizeArray;		
				
		$postDataField = array ($postFieldName => json_encode($deliveryData));
		
		//Initializes the Curl
		$ch = curl_init($url);
		if ($ch == FALSE) {
			return FALSE;
		}
		//Curl Options (http://curl.haxx.se/libcurl/c/curl_easy_setopt.html)
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		if(!is_null($userAgent)){
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		}
		if($followLocation){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataField );
	
		//Prepare headers
		$header_list = array(
				'Content-Type: multipart/form-data',
				'Connection: Close'
		);
		if(is_array($extraHeaders) && !empty($extraHeaders)) {
			foreach($extraHeaders as $key=>$value){
				$header_list[]=$key . ': ' . $value;
			}
		}
		//..set the headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_list);
	
		// Set TCP timeout to 30 seconds
		if(is_null($timeout)){
		  $timeout = 30;
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); //seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); //seconds
	
		//http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
		if(\jvcphplib\clib\StringUtils::startsWith($url, "https")){
			if(!is_null($caCertsFilePath)){
				// Download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html"
				// and set the directory path of the certificate as shown below. Ensure the file is
				// readable by the webserver.
				// This is mandatory for some environments.
				//$cert = __DIR__ . "/certs/cacert.pem";
				$caCertsFile = trim($caCertsFilePath);				
			}else{
				$caCertsFilePath = LIB_CACERTS_FILE_PATH;
				self::logMsg("Curl Operation. Endpoint is HTTPS, CACERTS file not specified, using default one (" . $caCertsFilePath . ").", \jvcphplib\clib\LoggerUtils::WARNING);
			}
			curl_setopt($ch, CURLOPT_CAINFO, $caCertsFile);	
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
	
		//Optional proxy configuration
		if(!is_null($proxy)){
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		}
	
		if($isDebug) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}
		
		$result = curl_exec($ch);
			
		//We update the status code of the parameter passed by reference.
		$returnStatusCode = self::getHttpStatusCode($ch);
	
		return $result;
	}	
	
	/**
	 * Returns the status code for the specified curl connection channel.
	 * 
	 * @param mixed $channel
	 * @return int The status code.
	 */
	public static function getHttpStatusCode($channel){
		$httpCode = curl_getinfo($channel, CURLINFO_HTTP_CODE);
		return $httpCode;
	}
	
	/**
	 * returns TRUE if the status code is between 200 and 300.
	 * 
	 * @param int $httpCode
	 * @return boolean
	 */
	public static function isHttpResponseOk($httpCode){
		/* If the call returned successfully without any redirection or error */
		if ($httpCode >= 200 && $httpCode < 300) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Uploads the files to the server.
	 * 
	 * File format: 
	 * 
	 * @param array $files Use $_FILES['uploads'] to get them.
	 * @param string $destinationFolder Must be a writable and readable folder.
	 * @param string $filePrefix A mark to set for the uploaded file names.
	 * @param string $fileSuffix A mark to set for the uploaded file names.
	 * @param boolean $directUpload When TRUE, uploads goes directly to the server,
	 * 								through a form, FALSE means that the form upload
	 *								goes to another PHP that will upload through CURL
	 *								the files, @see doUploadPostWithCURL().
	 * @return array
	 */
	public static function uploadFiles($files, $destinationFolder, $filePrefix, $fileSuffix, $directUpload = true) {
		
		$updResultBean = new \jvcphplib\clib\beans\UploadResultBean();
			
		$imgs = array();
		$errors = array();
			
		try{
			//Iterate through uploaded files
			for($i = 0 ; $i < count($files) ; $i++) {
				if (isset($files['error'][$i]) && $files['error'][$i] === 0) {
					
					$imgNameParts = explode(".", $files['name'][$i]);
					$imgExtension = $imgNameParts[(count($imgNameParts)-1)];
						
					//This concatenates at the end of the name the unique id.
					$name = uniqid($filePrefix . "_" . $fileSuffix . '-'. 
								DateTimeUtils::getCurrentDateTime()->format('Y-m-d-His').'_') 
								. "." . $imgExtension;
					//echo $files['tmp_name'][0];
					//Save the file
					if($directUpload) {
						if (move_uploaded_file($files['tmp_name'][$i], $destinationFolder . $name) === true) {						
							//Save the uploaded file data in a list, just for info.
							$imgs[] = array('url' => $destinationFolder . $name, 'name' => $files['name'][$i], 'error' => 'no');						
						}else{
							$errors[] = array('url' => $destinationFolder . $name, 'name' => $files['name'][$i], 'error' => 'yes');
						}
					}else{
						if (copy($files['tmp_name'][$i], $destinationFolder . $name) === true ) {
							unlink($files['tmp_name'][$i]); //Delete temporal file.
							//Save the uploaded file data in a list, just for info.
							$imgs[] = array('url' => $destinationFolder . $name, 'name' => $files['name'][$i], 'error' => 'no');						
						}else{
							$errors[] = array('url' => $destinationFolder . $name, 'name' => $files['name'][$i], 'error' => 'yes');
						}
					}
				}else{					
					$errors[] = array('url' => $destinationFolder . $name, 'name' => $files['name'][$i], 'error' => 'yes');
				}
			}
			
		}catch (\Exception $e) {}
		
		$updResultBean->uploadedFiles = $imgs;
		$updResultBean->failedFiles = $errors;
		
		return $updResultBean;
	}
	
	
	//AUXILIAR
	
	/**
	 * Logs a message.
	 *
	 * @param unknown $msg
	 */
	private static function logMsg($msg, $logLevel = null){
		if(is_null($logLevel)){
			\jvcphplib\clib\LoggerUtils::writeLog($msg, APP_API_FOLDER . APP_LOG_PATH);
		}else{
			\jvcphplib\clib\LoggerUtils::writeLogWithLevel($msg, APP_API_FOLDER . APP_LOG_PATH, $logLevel);
		}
	}
}


?>
