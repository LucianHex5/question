<?php

/* 
 * Created on: 12-Jul-2016 11:40:38
 * Author(s): Lucian I. Last
 */

/**
 * a liburay to interact with the user in php-cli
 */
class Question {

	function __construct() {
		/* Define STDIN in case if it is not already defined by PHP for some reason */
		if(!defined("STDIN")) {
			define("STDIN", fopen('php://stdin','r'));
		}
	}
	
	/**
	 * An easier fread()
	 * @param string $msg question
	 * @param boolean $addLineEnd will add a line end at the end of the $msg
	 *                if FALSE will be the same as fread()
	 * @return string read string from terminal
	 */	
	function qread($msg, $addLineEnd = TRUE) {
		$possibleLineEnd = "";
		if($addLineEnd === FALSE) {
			$possibleLineEnd = "\n";
		}
		
		echo $msg.$possibleLineEnd;
		return rtrim( fread(STDIN, 80), $possibleLineEnd);
	}
	
	/**
	 * Handels Yes || no questions
	 * @see ::qread()
	 * @return boolean ans is yes or no
	 * @return null invalid answer
	 */
	function qreadYn($question) {
		$reply = strtolower(
				$this->qread($question." [y/N]") );

		if ( mb_ereg_match("^y", $reply) ) {
			return TRUE;
		} elseif ( mb_ereg_match("^n", $reply) ) {
			return FALSE;
		} else {
			return NULL;
		}

	}

	/**
	 * Multiple choice qread
	 * @param string $question
	 * @param array $ansArr multiple choice
	 */
	function qreadchoice($question, $ansArr) {
		$question .= " [1 to ". sizeof($ansArr)."]";

		foreach ($ansArr as $key => $value) {
			$question .= "\n\t".($key+1).".  ".$value;
		}

		$reply = (int) ( $this->qread($question) );

		if( $reply <= sizeof($ansArr) ){
			return $reply -1;
		} else {
			return FALSE;
		}
	}
}