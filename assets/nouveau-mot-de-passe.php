<?php
session_start();
require '../php/connect.php';
if(!empty($_GET['email'])) {
	$email  = htmlspecialchars($_GET['email']);
	if(!empty($_GET['code'])) {
		$code  = htmlspecialchars($_GET['code']);
	} else {
		exit();
    } 

}  else {
     exit();
}

$req = $db->prepare('SELECT * FROM member WHERE email = ?');
$req->execute(array($email));

$info = $req->fetch();

if($req->rowCount() == 0) {
	echo 'Une erreur inconnue est survenu, nous sommes désolés! ';
	exit();
}


if($code == $info['pwd']) {
	$userPwd =  $info['pwd'];
} else {

	echo 'Une erreur inconnue est survenu, nous sommes désolés! ';
	exit();
}

//update password 
if(isset($_POST['submitBtn'])) {
	if(!empty($_POST['pwd']) && !empty($_POST['pwd2'])) {
		$pwd =  $_POST['pwd'];
		$pwd2 = $_POST['pwd2'];

		if($pwd == $pwd2) {

			if(strlen($pwd2) > 5) {
				$option = [
		  					'cost' => 11,
		  		];
		  		$pwd2 = password_hash($pwd2, PASSWORD_BCRYPT, $option);
		  		if(($info['pwd'] == $code)) {

			  		$upadatePwd = $db->prepare("UPDATE member SET pwd = ? WHERE email = ?");
					$upadatePwd->execute(array($pwd2, $email));
					header("Location:  http://localhost/projet/connexion.php");


		  		} else {
		  			$error =  "Une erreur fatale est survenue";
		  		}



			} else {
				$error = 'Doit contenir plus de 5 caractères ! ';
			} 

	    } else {
		     $error = 'Les mots de passe ne sont pas identiques ';
		}

	} else {
		$error = 'Tous les champs doivent être complété ! ';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Nouveau mot de passe !</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/form.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="mobile-screen" style="max-height:500px; margin-top: 120px;">
  <div class="header">
    <h1>Log in</h1>
  </div>
<br /> <br /> <br />

	<form method="POST" action="#" enctype="multipart/form-data" id='login-form'>
			<input type="password" name="pwd" placeholder="Votre nouveau mot de passe..." required> <br />

			<input type="password" name="pwd2" placeholder="Confirmation de nouveau pot de passe..." required> <br />

			
			<?php if(isset($error))
			 echo "<p class='psw_msg'>" . $error . "</p>" ; ?>

		 <button type="submit" class="login-btn" name="submitBtn"><span> Envoyer </span></button>
  </form>
  
  <div class="other-options">
    <div class="option" id="newUser"><a href="../inscription.php"><p class="option-text">New User</p></a></div>
    <div class="option" id="fPass"><a href="../connexion.php"><p class="option-text"> Connexion</p></a></div>
    <div class="option" ><a href='mailto:shawonshoot@gmail.com'> <p class="option-text" >Nous contacter </p></div>
  </div>
</div>
</body>
</html>