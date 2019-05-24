<?php
session_start();
require("../php/connect.php");
if(isset($_SESSION['id'])) {
	function secure($n){
		$n = htmlspecialchars($n);
		$n = stripcslashes($n);
		$n = strip_tags($n);
		$n = trim($n);
		return $n;
	}

	$query = $db->prepare('SELECT * FROM member WHERE id = ?');
	$query->execute(array($_SESSION['id']));

	$info =  $query->fetch();

	if(isset($_POST['submitBtn'])) {
		if(!empty($_POST['title']) && !empty($_FILES['myFile']['name'] ) ) {
			if(  $_POST['option'] !=  "0" ) {
				$title = secure(ucfirst($_POST['title'])); // converts the first character of a string to uppercase
				$articleSection =  secure($_POST['option']);
				if(preg_match("#[a-z0-9]#i", $title) && ((strlen($title > 7) ) || strlen($title < 209)) ){

					$name  = (substr(str_shuffle("1234567890azertyiopqsdfghjklmwxcvbn_-_"),0,5));
			        $fileImage = $_FILES['myFile']['name'] ;
			        $extension = pathinfo($fileImage, PATHINFO_EXTENSION);
			        $tmp_name = $_FILES["myFile"]["tmp_name"];

			        if((exif_imagetype($tmp_name) == 2) || ( exif_imagetype($tmp_name) == 3) || (exif_imagetype($tmp_name) == 1) ) { //gif, png, jpeg

			            $articleImage =  $name. '.' . $extension;
			            $uploadDestination = "../img/articles/" . $articleImage;
						$setTitle = $db->prepare("INSERT INTO articles(title,dateTimePublished, user_id, section,  img, isPublished, pseudo, email) VALUES (?, CURDATE(),  ?, ?, ?, -1, ?, ?)");
						$setTitle->execute(array($title, $info['id'],$articleSection, $articleImage, $info['pseudo'], $info['email']));
			            move_uploaded_file($tmp_name, $uploadDestination);
			            header("Location:edition-d-un-article.php?article_id=" . $articleImage);

			        }  else {
			            $error ='Formats d\'images inconnus!';
			        }

				} else {
					$error ="Le titre n'est  pas valide!";
				}

			} else {
				$error = "Veuillez choisir une section!";
			}


		}else {
			$error = "Tous les champs doivent être complétés ! ";
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

	    <link rel="apple-touch-icon" sizes="180x180" href="../img/logo.png" />
	    <link rel="icon" type="image/png" href="../img/logo.png" sizes="32x32" />
	    <link rel="icon" type="image/png" href="../img/logo.png" sizes="16x16" />
		<link rel="stylesheet" type="text/css" href="css/form.css">
        <title>Edition </title>


		<link rel="stylesheet" type="text/css" href="../css/article.css">
		<link rel="stylesheet" type="text/css" href="../css/anim.css">
		<link rel="stylesheet" type="text/css" href="../css/logo.css">
		<script type="text/javascript" src="../js/jquery.js"></script>
	</head>
	<body>
		<?php 
			include('../assets/navbar.php');
		?>

		   <?php
                if(isset($error)) {
                    echo "<br /> <br /> <p style ='color :#fff; background : #333; padding : 17px; text-align :center; position : relativd; z-index : 97;'>" . $error . "</p>";
                } 
            ?>
	<div class="content">
		<!-- Multistep Form -->
		<div class="main">
			<form method="POST" action="#" class="regform" enctype="multipart/form-data" >
			<!-- Progressbar -->
				<ul id="progressbar">
				<li id="step1">Création de l'article.</li>
				<li id="step2">Contenu de l'article</li>
				<li id="step3"> Détails</li>
				</ul>
				<!-- Fieldsets -->
				<fieldset id="first">
					<h2 class="title">Créer un article!</h2>
					<p class="subtitle">Etape 1</p>
					<input class="text_field" name="title" placeholder="Le titre de votre article..." type="text" value=" <?php if(isset($_POST['title'])) echo $_POST['title'];?>" > <br /><br /> <br /> <br />
					<div class="file ">
						<label class=""> Veuillez choisir une miniature our votre article(obligatoire) Formats acceptés : (jpeg, png, jpg, gif)
						
							<input type="file" name="myFile" id="myFile"  >  <br /> </label> 
					</div> <br /><br /> <br /> <br /> 
					<select class="options" name="option">
						<option  value="0">---------Choisir un thème ---------</option>
						<option value= "1 " >Science (Maths, astronomie)</option>
						<option value= "2" > Physique-Chimie</option>
						<option value= "3" >Biologie(écologie, géologie, les animaux)</option>
						<option value= "4" >Informatique</option>
						<option value= "5" >Histoire</option>
						<option value= "6" >Géographie</option>
						<option value= "7" >Économie</option>
						<option value= "8" >Littérature (films, art, peinture)</option>
						<option value= "9" >Sport</option>
					</select>
				
					<input id="next_btn1"  type="submit" value=Suivant name="submitBtn">
				</fieldset>

			</form>
		</div>
	</div>

	<!-- pop up -->
		<div class="pop-up-container readMsg">

			<div class="pop-up">
				<h3> Je m'engage à respecter les règles annoncées ci-dessous :  </h3> <br />
				<b> Attention, vous devez être l'auteur du document que vous envoyez. Tous les documents dupliqués, téléchargés depuis un autre site, ou plagiés  seront supprimés.</b>
			    <ul>
			        <i> Vos documents ne doivent PAS INCLURE :</i>
			        <p>du contenu copié sur d'autres sites (sauf avec l'accrod de ce dernier) </p>
			        <p>des éléments faisant l'objet d'un droit d'auteur</p>
			        <p>des obscénités</p>
			        <p>du contenu relatif au piratage</p>
			        <p> Ce site est un site sous Licences LL-48-50-CC, ce qui signifie que chaque article publier est un droit publique.En d'autres termes quiconque (y compris le créateur de l'article ou l'administratuer de ce site) ne peut réclamer l'appropration des articles publiés! </p>
			        <p>ou encore tout autre contenu illégal, faisant la promotion d'une activité illégale ou portant atteinte aux droits d'un tiers</p>
			        <p> Enfin nous vous remercions pour participer au développement de notre site et bon courage. </p>
			        <p style="color:red">Les documents non conformes aux règles énoncées ci-dessus seront supprimés et votre compte sera suspendu ! </p>
			    </ul>
			    
				<input type="button" name="" id="button" value="J'accepte" onclick="clickCounter();" style="margin-bottom: 100px;margin-left: 40%; border: 2px solid green;">
			</div>
		</div>


		<!-- js file -->
		<script type="text/javascript" src="../js/app.js"></script>
			<!--foter -->
		<?php
			include('../assets/footer.html');
		?>
	</body>
	</html>

<?php
} else {
	header('Location:../erreur.html');

}
	
?>