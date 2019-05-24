<?php
session_start();
require '../php/connect.php';
function secure($n) {
	$n = htmlspecialchars($n);
	$n = stripcslashes($n);
	$n = strip_tags($n);
	$n = trim($n);

	return $n;
}
if(isset($_POST['submitBtn'])) {

	if(!empty($_POST['email'])) {
		$email = secure($_POST['email']);
		$req = $db->prepare("SELECT * FROM member WHERE  email  = ?");
		$req->execute(array($email));

		$count = $req->rowCount();
		if($count  == 1) {
			$info = $req->fetch();
			$code = $info['pwd'];

			
			require '../email/class.phpmailer.php';
			require '../email/class.smtp.php';
			$mail = new PHPMailer;
			$mail->setFrom($email);
			$txt = '
				<div style=" background:#eee;" align="center"> <br />
				
					<div  style="font-family : serif; width: 370px; background-color: white; padding: 5px;" box-shadow :  5px 5px gray;>
						<h1  style="color: white; background: #242943; width: 100%; text-align: center; height: 120px;"> Mot de passe oublié.</h1> <br /> 
						
						<hr />
						<br />
						<br />
						Il ne reste plus qu\'un dernier pas avant de pouvoir acceder a votre compte. <br /> 
						Alors sans plus attendre voici le lien qui vous permettera de changer de mot de passe. <br /><br /><br /><br />
						<a href="http://localhost/projet/assets/nouveau-mot-de-passe.php?email=' . $email . '&code=' . $code . '" style="color: white; background-color: #337ab7; width: 400px; padding: 20px; text-decoration: none;"> Cliquez-ici pour se connecter.</a> <br /> <br /> <br /> <br/>

						<b>Je ne suis pas à l\'origine de cette action? </b> <br /> <br />
							Ceci est un mail automatique donc vous n\' avez rien à craindre! Si vous n\'êtes pas à l\'origine cette action, vous n\'avez rien à faire mais pour plus de sécurité nous vous rappellons quand même qu\'il est conseiller d\'utiliser un mot de passe "fort" avec des caractères spéciaux, long, chiffres et des majuscule! 
						<br />
						<br />
						<br />
						Toute l\'equipe vous remercie. Nous esperons vous revoir tres vite.  <br/> <b> <i>
						Cordialement Administrateur. </b> </i>
						  <br /> <br /> <br /> 
						<p style="color: white; background: #242943; width: 100%; text-align: center; height: 110px;">  
						<i> Pour toutes questions n\'hesitez pas a nous contacter, sur ce mail  <b>shawonshoot@gmail.com.</b> Nous sommes a votre ecoute. </i>
					</div>  <br />
			   
			    </div>
				
				';
			//
			$mail->addAddress($email);
			$mail->Subject = 'e-encylopedia | Oubli de mot de passe  !';
			$mail->Body = $txt;

			$mail->isHTML(true);
			$mail->IsSMTP();
			$mail->SMTPSecure = 'ssl';
			$mail->Host = 'ssl://smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Port = 465;
			//existing gmail address as user name
			$mail->Username = 'shawonshoot@gmail.com';

			//Set the password of  gmail address here
			$mail->Password = '#@P@********';

			if(!$mail->send()) {
			  	$error =  'Email is not sent.  PLease try again! <br />';
			  	$error =  'Email error: ' . $mail->ErrorInfo;

			} else {
			  $error =  'Email has been sent. ';    
			}


		


		} else {
			$error = "Désolé mail inexistant ! ";
		}


	} else {
		$error = 'Veuillez entrer une adresse valide!';
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Envoi d'un mot de passe !</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/form.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>


<div class="mobile-screen" style="max-height:350px; margin-top: 120px;">
  <div class="header">
    <h1>Log in</h1>
  </div> <br /> <br /> <br />


	<form method="POST" action="#" enctype="multipart/form-data" id='login-form'>
	   	 <input type="email" name="email" placeholder="E-mail" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" id="email">
			<?php if(isset($error)) 
				echo "<p class='psw_msg'>" . $error ." </p>" ; 
			?>
		
	    <button type="submit" class="login-btn" name="submitBtn"><span> Envoyer </span></button> <br /> 
  </form>
  
  <div class="other-options">
    <div class="option" id="newUser"><a href="../inscription.php"><p class="option-text">New User</p></a></div>
    <div class="option" id="fPass"><a href="../Connexion.php"><p class="option-text"> Connexion</p></a></div>
    <div class="option" ><a href='mailto:shawonshoot@gmail.com'> <p class="option-text" >Nous contacter </p></div>
  </div>
</div>
