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
namespace jvcphplib\clib\mail;


require_once('phpmailer/class.phpmailer.php');

/**
 * Mailing module.
 * 
 * @author JavocSoft 2014
 * @version 1.0
 */
class Mailing {
	
	/** Enables SMTP debug information (for testing) */
	const DEBUG_MODE = 0; //1 = errors and messages, 2 = messages only
	
	const MAIL_SENT_OK = 1;
	const MAIL_SENT_ERROR = -1;
	

	/**
	 * Sends an e-mail.
	 *
	 * @param $content
	 * @param $subject
	 * @param $addressTo
	 * @param $attachmentFile	Can be null.
	 * @param $attachmentData	Can be null.
	 * @param $attachmentName	Can be null.
	 */
	public static function sendEmail($content, $subject, $addressTo, 
				$attachmentFile = NULL, $attachmentData = NULL, $attachmentName = NULL,
				$mailSpamCtrlFile = NULL, $mailSpamCtrlInterval = NULL){
		try{
			$send = true;
			if(!is_null($mailSpamCtrlFile) && !is_null($mailSpamCtrlInterval)){
				if(!\jvcphplib\clib\FileOutput::isMailIntervalPassed($mailSpamCtrlFile, $mailSpamCtrlInterval)){
					$send = false;
				}
			}
			
			if($send){
				if($content!=null){
					$body = $content;
					return self::prepareAndSend($body, $subject, $addressTo, 
												$attachmentFile, $attachmentData, $attachmentName,
												$mailSpamCtrlFile);
				}else{
					return self::MAIL_SENT_ERROR;
				}
			}

		} catch (phpmailerException $e) {
			//echo $e->errorMessage();
			return self::MAIL_SENT_ERROR; 
		} catch (Exception $e) {
			//echo $e->getMessage();
			return self::MAIL_SENT_ERROR; 
		}

	}

	/**
	 * Sends an e-mail. The body is the content of the
	 * specified file.
	 * 
	 * @param $file
	 * @param $subject
	 * @param $addressTo
	 * @param $attachmentFile	Can be null.
	 * @param $attachmentData	Can be null.
	 * @param $attachmentName	Can be null.
	 */
	public static function sendEmailFromFile($file = null, $subject, $addressTo, 
				$attachmentFile = NULL, $attachmentData = NULL, $attachmentName = NULL,
				$mailSpamCtrlFile = NULL, $mailSpamCtrlInterval = NULL){

		try{
			$send = true;
			if(!is_null($mailSpamCtrlFile) && !is_null($mailSpamCtrlInterval)){
				if(!\jvcphplib\clib\FileOutput::isMailIntervalPassed($mailSpamCtrlFile, $mailSpamCtrlInterval)){
					$send = false;
				}
			}
			
			if($send){
				if($file!=null){
					$body = file_get_contents($file);
					return self::prepareAndSend($body, $subject, $addressTo, 
												$attachmentFile, $attachmentData, $attachmentName,
												$mailSpamCtrlFile);
				}else{
					return self::MAIL_SENT_ERROR;
				}
			}
				
		} catch (phpmailerException $e) {
			//echo $e->errorMessage();
			return self::MAIL_SENT_ERROR;
		} catch (Exception $e) {
			//echo $e->getMessage();
			return self::MAIL_SENT_ERROR;
		}
	}
	
	
	//AUXILIAR FUNCTIONS
	
	/*
	 * Prepares and sends an e-mail.
	 * 
	 * @param $body
	 * @param $subject
	 * @param $addressTo
	 * @param $attachmentFile	Can be null.
	 * @param $attachmentData	Can be null.
	 * @param $attachmentName	Can be null.
	 */
	private static function prepareAndSend($body, $subject, $addressTo, 
					$attachmentFile = NULL, $attachmentData = NULL, $attachmentName = NULL,
					$mailSpamCtrlFile = NULL){

		try{
			$mail = new \PHPMailer();
			
			$body = preg_replace('/\\\/','',$body);
			
			// Here's the code that allows special chars in subject and body
			$mail->CharSet  = 'UTF-8';
			$mail->Encoding = 'quoted-printable';
			
			$mail->IsSMTP(); 							// telling the class to use SMTP
			$mail->SMTPDebug  = self::DEBUG_MODE;				//
			$mail->SMTPAuth   = true;                  	// enable SMTP authentication
			$mail->SMTPSecure = SMTP_SECURITY;         	// sets the prefix to the servier
			$mail->Host       = SMTP_SERVER_HOST; 		// sets the SMTP server
			$mail->Port       = SMTP_PORT;              // set the SMTP port for the GMAIL server
			$mail->Username   = SMTP_USERNAME; 			// SMTP account username
			$mail->Password   = SMTP_PASSWORD;        	// SMTP account password
			//$mail->Subject    = '=?UTF-8?Q?' . quoted_printable_encode($subject) . '?='; //$subject; 
			$mail->Subject    = $subject; 
			$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
			
			$mail->SetFrom(SMTP_FROM_ADDRESS, SMTP_FROM_ADDRESS_NAME);
			//$mail->AddReplyTo(SMTP_FROM_ADDRESS, SMTP_FROM_ADDRESS_NAME);
			$mail->AddAddress($addressTo,$addressTo);
			
			//$mail->Sender=SMTP_FROM_ADDRESS; 
			//$mail->ReturnPath=SMTP_FROM_ADDRESS;
			//$mail->AddCustomHeader('Precedence: list');
			//$mail->AddCustomHeader('List-Unsubscribe: <mailto:info@vainillaclub.com>');
			
						
			$mail->MsgHTML($body);
			
			if(!is_null($attachmentFile)){
				$mail->AddAttachment($attachmentFile);				
			}else if (!is_null($attachmentData) && !is_null($attachmentName)){
				$mail->AddStringAttachment($attachmentData, $attachmentName);
			}
			
			if(!$mail->Send()){				
				return self::MAIL_SENT_ERROR; //$mail->ErrorInfo;
			}else{
				if(!is_null($mailSpamCtrlFile)){
					$mControlFileName = "msendctrl.info";
					\jvcphplib\clib\FileOutput::updateFileModifiedTime($mControlFileName);
				}
				return self::MAIL_SENT_OK;
			}
			
		} catch (phpmailerException $e) {
			\jvcphplib\clib\LoggerUtils::writeLog("PHPMailer error preparing and sending mail: " . $e->getMessage(), LIB_BASEDIR_PATH . APP_LOG_PATH);
			return self::MAIL_SENT_ERROR;			
		} catch (Exception $e) {
			\jvcphplib\clib\LoggerUtils::writeLog("Un-expected error preparing and sending mail: " . $e->getMessage(), LIB_BASEDIR_PATH . APP_LOG_PATH);
			return self::MAIL_SENT_ERROR;			
		}
	}

}


