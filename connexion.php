<?php
session_start();
require('php/connect.php'); // connexion à la base de donnée
include('cookie.php'); // connexion automatique si l'utilisateur a accepté se souvenir de moi

	function secure($n){ // permet de sécuriser les vzriables
			$n = htmlspecialchars($n); //sécuriser les caractères hmtl
			$n = stripcslashes($n); 
			$n = strip_tags($n);
			$n = trim($n);
			return $n;
	}

if(!empty($_SESSION['id'])) { // si l'utilisateur est déjà connecté alors redirection vers sa page de profil !
	  header('Location: profil.php?id='.$_SESSION['id']);	
}

if(isset($_POST['submitBtn']) ) { // connexion
	if(!empty($_POST['email']) && !empty($_POST['pwd'])) {


	  	$email = secure($_POST['email']);
	  	$pwd = ($_POST['pwd']);

	  	$request =  $db->prepare("SELECT *  FROM member WHERE email  =  ? ");
	  	$request->execute(array($email));
	  	$userDefinded = $request->rowCount() ;  

	  	if($userDefinded  ==  1) { // si le mail rentré existe de notre base de données si ce n'est pas le cas les identifiants sont invalides
	  		$info = $request->fetch();
		    if(password_verify($pwd, $info['pwd'])){	        // vérification de mots de passes avec le mots de passe hashés

		        $userinfo = $request->fetch();
		          // confirm mail 
		        if($info['isConfirm'] != 0) { // si l'utilsateur a accepté le mail

		        	if($info['token'] > 0) { // set cookie to remember user password ! 
		        		if(isset($_POST['checkbox']))    {
				           setcookie('email',$email,time()+60*60*24*31,null,null,false,true);
				           setcookie('pwd',$pwd, time()+60*60*24*31,null,null,false,true);
				        } 

			            $_SESSION['id'] = $info['id'];
			            $_SESSION['email'] = $info['email'];
			            $_SESSION['pseudo'] = $info['pseudo'];

			            header('Location: profil.php?id='.$_SESSION['id']);		  // redirection vers sa page de profil      		
		        	} else {
		        		$error = "Vous n'êtes plus autorisé à utiliser ce compte ! ";
		        	}
		        } else {
		            $error = "Avez -vous confimré votre email! N'hésitez pas à vérifier votre dossie spam";
		        }

	  		} else {
	  			$error = " Identifiants invalides !  ";
	  		}

	  	} else {
	  		$error =  " Identifiants invalides ! ";
	  	}

	} else {
		$error  = "Tous les champs doivent être compléte  !";
	}
}



?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Encylopédie en ligne">
    <meta name="abstract" content="Encylopédie en ligne">
    <meta name="keywords" content="Science, Maths, Informatiques, Sport, littérature">
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
	<meta property="article:published_time" content="2018-04-11T16:50:33Z" />
	<meta name="robots" content="noodp" />
	<meta name="DC.Format" content="text/html" />
	<meta name="DC.Type" content="Text" />
    <link rel="apple-touch-icon" sizes="180x180" href="./images/apple-touch-icon.png" />
    <link rel="icon" type="image/png" href="./images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="./images/favicon-16x16.png" sizes="16x16" />
    
	<title>Connexion - e-encylopedia.</title>
	<script type="text/javascript" src="js/jquery.js"></script>	
	<link rel="stylesheet" type="text/css" href="css/form.css">

</head>
<body>  <br /> <br />

<div class="mobile-screen"  >
  <div class="header">
    <h1>Log in</h1>
  </div>
  
  <div class="logo"></div>


	<form method="POST" action="#" enctype="multipart/form-data" id='login-form'>
	   	 <input type="email" name="email" placeholder="E-mail" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>">
	   	 <input type="password" name="pwd" placeholder="Password">

	    <!-- Checkbox -->
	    <label class="switch checkbox-align">
	        <input type="checkbox" name="checkbox" id="checkbox">
	        <span class="slider round"></span>
	    </label>
	        <p  style="color:  rgba(255,255,255,.5);">Se souvenir de moi! </p><br /> <br />


	    <?php 
	    	if(isset($error)) {
	    		echo "<p class='psw_msg' > " . $error . "</p>"; // affichage d'erreur. 
	    	}
	    ?>

	    <button type="submit" class="login-btn" name="submitBtn"><span> Envoyer </span></button>
  </form>
  
  <div class="other-options">
    <div class="option" id="newUser"><a href="inscription.php"><p class="option-text">New User</p></a></div>
    <div class="option" id="fPass"><a href="assets/oublie-de-mot-de-passe.php"><p class="option-text"> Forgotten password</p></a></div>
    <div class="option" ><a href='mailto:shawonshoot@gmail.com'> <p class="option-text" >Nous contacter </p></div>
  </div>
</div>

<!--#Alert box -->
<script type="text/javascript" src="js/cookie.js"></script>
</body>
</html>
