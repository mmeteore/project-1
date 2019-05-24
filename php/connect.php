<?php
try {
	$db =new PDO("mysql:host=localhost;dbname=projet;",'root','');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
} catch (PDOException $e) {
	echo $e->getMessage();
}


/* 
@ This class is used to secure all data in the database, both incoming and outgoing
*/
/*class Secure {
		/*
		* the bbc code function protects incoming data, 
		*especially against SQL injection 
		*
		
		public static function outgoing($text) {
			$isInvalid  = array('-', '&', "<", ">", '"', "'", "(", ")");
			// it is interesting to check the types of variables and data that the
			// user enters on the data base especially for the prepared query
			if(ctype_digit($text)) {
				$text = intval($text);

			} else if (!ctype_alnum(str_replace($isInvalid, "", $text))) {
				$text = string($text);
			} else {
				$text = htmlspecialchars($text);
				$text = addcslashes($text, '%_');
			}
				
			return $text;

		}
		/*  
		@this function protects especially against the XSS fault
		*
		
		public static function incoming($text) {
			$text = htmlspecialchars($text);
			$text = stripcslashes($text);
			$text =  strip_tags($text);
			$text = trim ($text);
			$text = htmlentities($text);

			return ($text);
		}

		/* 
		* this function calculate the count of sentence on a paragraph
		

		public static function countWord($text) {
			echo 'Birnvenue ! ';
			 
		}
}

*/


