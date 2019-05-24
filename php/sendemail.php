<?php 
session_start();
require('connect.php');
if(!empty($_SESSION['email']) ) {
	$email = $_SESSION['email'];
} else {
	header("Location : erreur.php"); 
}
$error = 0;;
$request = $db->prepare('SELECT * FROM member WHERE email = ?');
$request->execute(array($email));
$answer = $request->rowCount();
if($answer ==  1) {
	$userInfo = $request->fetch();
	$code =  $userInfo['code'];
	$token = $userInfo['token'];
	$id = $userInfo['id'];

	if($error == 0) {

		if($token > 3) {

			require '../email/class.phpmailer.php';
			require '../email/class.smtp.php';
			$mail = new PHPMailer;
			$mail->setFrom($email);
			$mail->AddEmbeddedImage('m.jpg', 'logo_2u');
			$txt = '
				<div style=" background:#eee;" align="center"> <br />
				
					<div  style="font-family : freemono; width: 350px; background-color: white; padding: 5px; ;">
						<h1  style="color: white; background: #242943; width: 100%; text-align: center; height: 120px;"> Merci de votre inscription sur notre site.</h1> <br /> 
						<img src="cid:logo_2u" alt="Le logo du site e-encyclopedia" width="180px">
						<hr />
						<br />
						<br />
						Il ne reste plus qu\'un dernier pas avant de pouvoir acceder a votre compte. <br /> 
						Alors sans plus attendre voici le lien qui vous permettera d\'activer votre compte. <br /><br /><br /><br />
						<a href="http://localhost/projet/php/verif.php?email=' . $email . '&code=' . $code . '" style="color: white; background-color: #337ab7; width: 400px; padding: 15px; text-decoration: none;"> Cliquez-ici pour se connecter.</a> <br /> <br /> <br /> <br/>

						<b> Une fois inscrit(e)? </b> <br /> <br />
						Vous pouvez acceder a votre page de profil, interagir avec les autres membres, poster des articles et des commentaires. N\'hesitez pas a faire un petit tour sur votre page de profil pour personnaliser votre avatar, pseudo, de theme etc... 
						<br />
						<br />
						<br />
						Toute l\'equipe vous souhaite la bienvenue. Nous esperons vous revoir tres vite.  <br/> <b> <i>
						Cordialement Administrateur. </b> </i>
						  <br /> <br /> <br /> 
						<p style="color: white; background: #242943; width: 100%; text-align: center; height: 110px;">  
						<i> Pour toutes questions n\'hesitez pas a nous contacter, sur ce mail  <b>shawonshoot@gmail.com.</b> Nous sommes a votre ecoute. </i>
					</div>  <br />
			   
			    </div>
				
				';
			//
			$mail->addAddress($email);
			$mail->Subject = 'e-encylopedia | Confirmation de votre compte.';
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
			$mail->Password = '#@P@*********';

			if(!$mail->send()) {
			  	$error =  'Email is not sent.  PLease try again! <br />';
			  	$error =  'Email error: ' . $mail->ErrorInfo;
		      	$error = $db->prepare("DELETE FROM member WHERE email = ?" );
		      	$error->execute(array($email) );
			} else {
			  $error =  'Email has been sent. ';    
	            $updateText = $db->prepare("UPDATE member SET token = 3 WHERE email = ?");
	            $updateText->execute(array($email) );
			}


		} else {
			$error =  "Un mail a déjà été envoyé  ! N'oubliez pas de vérifier votre dossier spam";
		}

	}


} else {
	$error =  "Erreur fatale ! Nous somme désolés une erreur est survenue. Veuillez réeassyer!";
}



if(isset($error)) 
	echo  "<p class='error' style='color: #fff; background: #242943;padding: 27Px; text-align: center; margin: 20%;'>"  . $error . "</p>" ;