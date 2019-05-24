<?php 
session_start();
require('php/connect.php'); // connexion à la base de données !
if(!empty($_SESSION['id'])) { // si l'utilisateur est déjà connecté alors redirection vers la page de profil ! 
	header("Location:profil.php?id=" . $_SESSION['id'] );

}
if(isset($_POST['submitBtn'])) {
	if(!empty($_POST['userName']) AND !empty($_POST['email']) AND !empty($_POST['pwd']) AND !empty($_POST['pwd2']) AND !empty($_POST['captcha']) ) {
		
		function secure($n){
			$n = htmlspecialchars($n);
			$n = stripcslashes($n);
			$n = strip_tags($n);
			$n = trim($n);
			return $n;
	    }

	  	$email = secure($_POST['email']);
	  	$userName = secure($_POST['userName']);
	  	$pwd = ($_POST['pwd']);
	  	$pwd2 = ($_POST['pwd2']);
	  	$captcha  = secure($_POST['captcha']);
		$code = rand(100, 999);
		$_SESSION['code'] = $code;
		$avatar = "default.jpg";
		$bio = "Le salaire est déterminé par la lutte entre capitaliste et ouvrier. La victoire appartient nécessairement au capitaliste. Karl Marx.";


	  	if(preg_match("#^[a-z0-9._-]+@[a-z]{3,}+\.[a-z]{2,5}$#i", $email)) { // vérification de l'email avec utilisation de regex

	  		if(preg_match("#^[a-z0-9-_.]{2,}$#i", $userName)) {

	  			if($pwd2 == $pwd ) { // les condtions pour les mots de passes :  doivent être identiques & + de 5 caractères
	  				if(strlen($pwd) >5 ) {

	  					$option = [
		  					'cost' => 11,
		  				];
		  				$pwd = password_hash($pwd2, PASSWORD_BCRYPT, $option); // hashage de  mots de passes

		  				if(strlen($email) < 27 || strlen($userName)  < 15) {

		  					$request = $db->prepare("SELECT * FROM member WHERE email = ?");
		  					$request->execute(array($email));
		  					$answer =  $request->rowCount();

		  					if($answer == 0) { // if the mail doesn't exist in our database

		  						if(isset($_POST['checkbox'])) { //si l'utilisateur a accepté les conditions
 
		  							if($_SESSION['captcha'] == $captcha) { // s'il a su résourdre l'algo anti-robot

			  							$insert  = $db->prepare('INSERT INTO member (pseudo, email, pwd, date_time_register, isConfirm, code, token, bio, avatar) VALUES (?,?,?,CURDATE(),?,?, 4,? , ? )');
			  							$insert->execute(array($userName, $email, $pwd, 0, $code, $bio,  $avatar));
			  							$_SESSION['email'] = $email; // insertion de données :  inscriptions réussies
			  							header("Location:php/sendemail.php"); // page qui permet de enovyer un email pour activer son compte! 

		  							} else {
		  								$error = "Désolé le captcha n'est pas valide";
		  							}

		  						} else {
		  							$error = "Avez-vous accepté la condition générales d'utilisation?";
		  						}
		  						

		  					} else {
		  						$error =  "Ce mail est déjà utilisé! ";
		  					}
		  				} else {	  					
		  					$error = " Votre mail ou votre pseudo n'est pas valide";
		  				}
 
	  				} else {
	  					$error = "Les deux mots de passses doivent être identiques et contenir  plus de 6 caractère! ";

	  				}

	  				
	  			} else {
	  				$error = "Les deux mots de passses doivent être identiques et contenir  plus de 6 caractère! ";
	  			}

	  		} else {
	  			$error = "Votre pseudo ne doit contenir uniquement des caractères alphanumériques !";
	  		}
	  	 	
  	    } else {
  	 		$error =" Votre mail n'est pas valide ! " ;
  	    }

  	} else {
  		$error = "Veuillez remplir tous les champs ! ";
  	} 
}



?>
<!DOCTYPE html>
<html lang="fr">
<head>  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Encylopédie en ligne">
    <meta name="abstract" content="Encylopédie en ligne">
    <meta name="keywords" content="<?=$infoArticle['title']?>" />
    <meta name="author" content="meteor">
    <meta name="generator" content="PHPMyADMIN" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="identifier-url" content="http://e-encylopedia.com/" />
    <meta name="revisit-after" content="1" />
    <meta content="origin" name="referrer"> 
    <meta data-n-head="true" name="referrer" content="origin-when-crossorigin">
    <meta name="language" content="FR" />
    <meta name="robots" content="All" />  
    <meta name="twitter:site" content="@e-encylopedia">
    <meta name="twitter:creator" content="@e-encylopedia">
    <meta property="og:site_name" content="e-encylopedia"/>
    <meta name="twitter:widgets:csp" content="on"/>
    <link rel="alternate" href="https://e-encylopedia.com/" hreflang="x-default"/>
    <meta name="theme-color" content="#4F4F4F" />
    <meta name="msapplication-navbutton-color" content="#4F4F4F">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta property="article:published_time" content="<?=$infoArticle['dateTimePublished'] ?>T16:50:33Z" />
    <meta name="robots" content="noodp" />
    <meta name="DC.Format" content="text/html" />
    <meta name="DC.Type" content="Text" />

    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png" />
    <link rel="icon" type="image/png" href="img/logo.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="img/logo.png" sizes="16x16" />
	<link rel="stylesheet" type="text/css" href="css/form.css">

	<title>Inscription | e-encylopedia.</title>
    <script type="text/javascript" src="js/jquery.js"></script>
	<!-- don't forget to create the logo -->

</head>
<body> 

<div class="mobile-screen" style="max-height: 650px;">
  
  <div class="header">
    <h1>Sign in</h1>
  </div>
  


	<form method="POST" action="#" enctype="multipart/form-data" id='login-form'> 


	    <input type="text" name="userName" id="userName" placeholder= "Votre pseudo " value="<?php if(isset($_POST['userName'])) echo $_POST['userName'];?>" > <br/>
	    <p id='alphNum' class="hidden" > Uniquement des caratères alphanumériques.</p>

	    <input type="" placeholder="Enter Email" name="email" class= "email" autocomplete="off" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" >
	    <p class="msgEmail psw_msg"> </p> <br/>
	    <input type="password" placeholder="Enter Password" name="pwd" id="pwd" >  <br />
	    <p id='countChar' class="hidden" > Doit contenir au moins 6 caractères </p>
	    <p id='number' class="hidden" > Dont 1 chiffez.</p>


	    <input type="password" placeholder="Repeat Password" name="pwd2" id='pwd2' > <br />
	    <p id='pwdNoMatch' class="hidden"> Les deux mots de passes ne sont pas identiques </p>
        
        <p style="margin : 0px 37px;"><img src="img/algo.php"></p><br />
	    <input type='text' name='captcha' id='cpatcha' placeholder="Écrivez le code ..." alt=""> <br />

	   
	    <!-- Checkbox -->
	    <label class="switch checkbox-align">
	        <input type="checkbox" name="checkbox" id="checkbox">
	        <span class="slider round"></span>
	    </label>
	        <sup  style="color:  rgba(255,255,255,.5);">J'accepte les conditions générales <a href="assets/cgu.html"  target="_blank" style="color:  rgba(255,255,255,.5); font-size: 13px;"  >d'utilisation!</a>
	        </sup>



	    

	    <button type="submit" class="login-btn" name="submitBtn" id='submitBtn'><span> Envoyer </span></button>

	    

	</form>




  <div class="other-options">
    <div class="option" id="newUser"><a href="inscription.php"><p class="option-text">New User</p></a></div>
    <div class="option" id="fPass"><a href="connexion.php"><p class="option-text"> Connexion</p></a></div>
    <div class="option" ><a href='mailto:shawonshoot@gmail.com'><p class="option-text" >Nous contacter </p></div>
  </div>
</div>


<?php 
	    	if(isset($error)) {
	    		?>
	    		 <p class='alert' style='text-align: center;'> <?=$error  ?>
	    		 	 <span onclick='this.parentElement.style.display="none"; ' style="cursor: pointer; float: right; font-size: 19px; color : red;">&times;</span>
	    		 </p>

	    		<?php 
	    	}
	    ?>
	<!-- #script js  -->

	<script type="text/javascript" src='js/register.js'></script>

</body>
</html>