<?php
session_start();
require '../php/connect.php';

function secure ($n) {
	$n = trim($n);
	$n = htmlspecialchars($n);
	$n = stripcslashes($n);
	$n = strip_tags($n);

	return $n;
}

// get the 20 words 
function subStrWords($text, $lens) {
      if (str_word_count($text) > $lens) {
            $words = str_word_count($text, 2);// 2 - returns an associative array, where the key is the numeric position of the word inside the string and the   value is the actual word itself 
            $i = array_keys($words);
            $text = substr($text, 0, $i[$lens]) . '...'; // découpage d'une chaîne de caractère mot par mot
      }
      return strip_tags($text); // on enlève toutes les balises html pour éviter une erreur d'affichage en hmtl
    }


// get the section value ...
if(!empty($_GET['section'])) {	
	if($_GET['section'] > 0 && $_GET['section'] <= 9) { // si utilisateur trouve dans une de nos sections de notre base de donnée
		$section = intval(secure($_GET['section']));
	} else {  // sinon redirection vers la page d'erreur ^^
		header("Location:http://localhost/projet/erreur.php");
		exit();
	}
} else {
	header("Location:http://localhost/projet/erreur.php");
	exit();
}



//pagination system 
$articleParPage = 3;
$requestArticle = $db->prepare('SELECT article_id FROM articles WHERE section = ? ');
$requestArticle->execute(array($section));
$numberOfArticle = $requestArticle->rowCount(); // on compte tous les articles d'une section donnée

$totalArticle = ceil($numberOfArticle/$articleParPage);  // nombre de page ceil permet de convertir en un nombre entier supérieur. Par exemple si nous avons 8 articles dans une section et on met 3 articles par page alors on aura 8/3 =  2.666.... et en convertissant ce nombre on aura 3 page. Ainsi sur les deux premières page nous aurons 3 articles et la dernière seulment 2
//echo "$totalArticle";
if(!empty($_GET['t']) && $_GET['t'] > 0 && $_GET['t'] <= $totalArticle) { // permet de savoir sur quelle page se trouve l'utilisateur
   $_GET['t'] = secure( intval($_GET['t']) );
   $currentPage = $_GET['t'];
} else {
   $currentPage = 1;
}
$start = ($currentPage-1) * $articleParPage;

 

// get all article


$req = $db->prepare("SELECT * FROM articles WHERE section  = ? ORDER BY article_id DESC LIMIT " . $start . "," . $articleParPage); // on range dans l'ordre décroissant de sorte l'article le + récent appraissent en premier.
$req->execute(array($section)); 

$articleNumber = 0;

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



	<title>Lire des articles.</title>
	<link rel="stylesheet" type="text/css" href="../css/section.css">
	<link rel="stylesheet" type="text/css" href="../css/boots.css">
	<script type="text/javascript" src="../js/jquery.js"></script>
</head>
<body>
	<!--nav bar -->
	<?php include('../assets/navbar.php'); ?>

	<!-- search bar -->
	<div class="main">
		<button class="search-bar-btn btn btn-primary " style="margin-left:calc(100% - 150px) ;"><i>Rechercher...</i></button>
	    <div class="pop-up-container readMsg">
	    	<div class="pop-up">
	    		<h2><i> Vous recherchez</i> ....</h2>
				<div class="search">
				  <input type="search" class="search-box" id="search" autocomplete="off" />
				  <span class="search-button">
				    <span class="search-icon"></span>
				  </span>
				  <!-- show user query result -->
				
				</div>
	    		  <div id='result' ><h6> </h6> </div> 
	    		  <div id="loading" style="display: none;"><h2> <canvas id="canvasLoader"></canvas></h2></div>
			</div>
		</div>
		<!-- #search bar -->



		<!-- show article -->

			<div class="comments-section">
				<?php
					while($getArticle =  $req->fetch()) {    // on cherche tous les commentaires sur un article donné
						$articleNumber++;
						$getContent  = $getArticle['textValue'];
						$content =  subStrWords( $getContent, 83); // on affiche seuelement 83 premiers mots o_O

						?>
					  <div class="comments">
					    <h4>Articles n° <?= ($articleParPage * ( $currentPage - 1 ) ) + $articleNumber  ?></h4>
					    <div id="comments-container">
					      <div class="comment">
					        <div class="comment-user">
					          <span class="user-details"><span class="username"><h3><?= $getArticle['title'] ?> </h3> </span><span>le </span>
					            <span>	<?=  $getArticle['dateTimePublished'] ?> </span> 
					          </span>
					        </div>
					        <div class="comment-text">
					           <p><?= $content?> </p> <br><br>
					         <a href="http://localhost/projet/article/article-contenu.php?article_id=<?= $getArticle['article_id'] ?>" > <br /><button class="btn btn-primary">Lire cet article.</button></a>
					        </div>
					      </div>
			            </div>
			         </div>

					    <?php

			        } 

			    ?>
			</div>

			<!-- pagination system -->
			    <?php
			        for($i=1;$i<=$totalArticle;$i++) {
			        	$myLink = "index.php?section=" . $section; // pour changer de page & afficher les autres articles de la même section
			           if($i == $currentPage) { ?>
			           	  <ul class="pagination "><li class="active"> <a href="index.php?section=3&t=<?= $i ?>" > <?= $i ?></a> </li> </ul> <?php 
			              
			           } else { ?>
			          	<ul class="pagination"><li><a href="<?=$myLink . '&t=' .  $i ?>" > <?= $i ?></a> </li> </ul> <?php
			           }
			      }
	            ?>



	</div>

	        <!--script js -->

		<script type="text/javascript" src="../js/searchBar.js"></script>
	      <!--footer -->
	    <?php include ('../assets/footer.html'); ?>






</body>
</html>