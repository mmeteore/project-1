<?php
require('php/connect.php');
if(empty($_SESSION['id']) AND !empty($_COOKIE['email']) AND !empty($_COOKIE['pwd'])) {

	$option = [
		'cost' => 11,
	];
	$pwd = password_hash(($_COOKIE['pwd']), PASSWORD_BCRYPT, $option);

	$request = $db->prepare("SELECT * FROM member WHERE email = ? ");
	$request->execute(array($_COOKIE['email']) ) ;

	//echo $pwd . "<br />" ;
	$userexist = $request->rowCount();
	if($userexist == 1)   { 
        $userinfo = $request->fetch();
    	//echo $userinfo['pwd'];
	    $_SESSION['id'] = $userinfo['id'];
	    $_SESSION['email'] = $userinfo['email'];
		$_SESSION['pseudo'] = $userinfo['pseudo'];
		// $_SESSION['avatar'] = $userinfo['avatar'];
		header('Location:profil.php?id=' . $userinfo['id']);
	    	
	    }
}

?>
