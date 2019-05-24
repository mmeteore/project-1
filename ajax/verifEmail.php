<?php

	try {
		$db =new PDO("mysql:host=localhost;dbname=projet",'root','');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo ' ';
	} catch (PDOException $e) {
		echo $e->getMessage();

	}
	    if(isset($_GET['emailExist'])){
	        $user =   (String) trim($_GET['emailExist']);

	        $request = $db->prepare("SELECT * FROM member WHERE email = ?");
	        $request->execute(array($user));
	        $answer =  $request->rowCount();

	        if($answer == 1) { // if the mail  exist in our database
	            $error  = 'Ce mail existe déjà !';
	            echo $error;
	        }
	    }
?>   
