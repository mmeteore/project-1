<?php
session_start();
require('../php/connect.php');

//connexion automatic

// si l'utilisateur a coché se souvenir de moi alors on le connecte automatiquement sauf si les cookies sont expirés #:o_O:

if(!isset($_SESSION['id']) AND isset($_COOKIE['email']) AND isset($_COOKIE['pwd']) AND !empty($_COOKIE['email']) AND !empty($_COOKIE['pwd'])) {


	$request = $db->prepare("SELECT * FROM member WHERE email = ? ");
	$request->execute(array($_COOKIE['email']) ) ;

	$cookie_user_info  = $request->fetch();

	if($request->rowCount() == 1) {
		$_SESSION['id'] = $cookie_user_info['id'];
	}
}



function secure($n){
	$n = htmlspecialchars($n);
	$n = stripcslashes($n);
	$n = strip_tags($n);
	$n = trim($n);
	
	return $n;
}


 
if(!empty($_GET['article_id']) AND $_GET['article_id'] > 0) {
	
    $getid = intval($_GET['article_id']);
    $req = $db->prepare("SELECT * FROM articles WHERE article_id = ? ");
    $req->execute(array($getid));
    $infoArticle = $req->fetch();

    if($req->rowCount() == 0)   {  
       echo 'Erreur de navigation.';
       header("Location:../erreur.php");
       exit();
      

    } 


    
    if(!empty($_SESSION['id'])) { //si l'utilisateur est connécté

	    $query =  $db->prepare('SELECT * FROM member WHERE id = ?');
	    $query->execute(array($_SESSION['id']));
	    $userinfo= $query->fetch();


    }

    $_SESSION['article_id'] = $getid;


    // like 

    $like_query = $db->prepare('SELECT * FROM likeBtn  WHERE article_id  =  ?');
    $like_query->execute(array( $_SESSION['article_id']));

    $info_like_button  = $like_query->fetch(); // affichage de nombre de j'aime uniquement pour les utilisateur connecté peuvent le voir




    // post comment par exemple si on trouve [b]text[/b] cela sera remplacé par <b>text</b> et s'affichera en gras le texte
    // si un utilisateur rentre :) cela sera remplacé par un smiley :)

    // bbcode pour la mise en forme des texte 

    if(isset($_POST['submitBtn'])) {
    	if(!empty($_POST['message'])) {
    		$msg = nl2br(secure($_POST['message'])); 

    		$msg = str_replace(':pirate:', '<img src="../img/bbc/1.gif"  alt="" style="background-color:none;margin  : 0 10px; width : 26px;height:26px; border :  none;" border="0"/>', $msg);
    		$msg = str_replace(':triste:', '<img src="../img/bbc/2.gif"  alt="" style="background-color:none;margin  : 0 10px; width : 26px;height:26px; border :  none;" border="0"/>', $msg);
    		$msg = str_replace(':lol:', '<img src="../img/bbc/3.gif"  alt="" style="background-color:none;margin  : 0 10px; width : 26px;height:26px; border :  none;" border="0"/>', $msg);
    		$msg = str_replace(':)', '<img src="../img/bbc/4.gif"  alt="" style="background-color:none;margin  : 0 10px; width : 26px;height:26px; border :  none;" border="0"/>', $msg);
    		$msg = str_replace(':ninja:', '<img src="../img/bbc/5.gif"  alt="" style="background-color:none;margin  : 0 10px; width : 26px;height:26px; border :  none;" border="0"/>', $msg);
    		$msg = str_replace('^^', '<img src="../img/bbc/6.gif"  alt="" style="background-color:none;margin  : 0 10px; width : 26px;height:26px; border :  none;" border="0"/>', $msg);
    		
    		// bbcode 
    		$msg  = preg_replace('#\[b\](.+)\[/b\]#isU', '<b>$1</b>', $msg);
    		$msg =  preg_replace("#\[code\](.+)\[/code\]#isU", "<div style=' padding : 17px; background-color: #ddd; color  : #333;box-shadow : 5px 5px 15px #OOO; max-height : 350px; overflow  : auto;'>$1</div>", $msg);

    		$ins = $db->prepare("INSERT INTO comment (article_id, user_id , textValue, dateTimePublished, pseudo, avatar, commentImage) VALUES (? , ?, ? , CURDATE(), ? , ?, ?)");
    		$ins->execute(array($_SESSION['article_id'], $_SESSION['id'], $msg, $userinfo['pseudo'], $userinfo['bio'], $userinfo['avatar']));
    	} else {
    		$error = "Veuillez remplir ce champs ! ";
    	}
    }

    // comment  & pagnination system
    $commentParPage = 1;
    $comment = $db->prepare('SELECT comment_id FROM comment WHERE article_id = ? ');
   	$comment->execute(array($_SESSION['article_id']));
   	$getNumberOfComment = $comment->rowCount();
   	$totalComment = ceil($getNumberOfComment/$commentParPage);
   	//echo "$totalArticle";
	if(!empty($_GET['t']) AND $_GET['t'] > 0 AND $_GET['t'] <= $totalComment) {
	   $_GET['t'] =  intval($_GET['t']);
	   $currentPage = $_GET['t'];
	} else {
	   $currentPage = 1;
	}
    $start = ($currentPage-1) * $commentParPage;



	$reqComment = $db->prepare("SELECT * FROM comment WHERE article_id  = ? ORDER BY comment_id DESC LIMIT " . $start . "," . $commentParPage);
	$reqComment->execute(array($getid));  // get id is the id of article :)

    
  	
} else {
  header ('Location:../erreur.php');
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


    <title><?= $infoArticle['title'] ?></title>
	<link rel="stylesheet" type="text/css" href="../css/boots.css">
	<link rel="stylesheet" type="text/css" href="../css/comment.css">
	<link rel="stylesheet" type="text/css" href="../css/anim.css">
	<link rel="stylesheet" type="text/css" href="../css/heart-img.css">	
	<script type="text/javascript" src="../js/jquery.js"> </script>
	<script type="text/javascript" src ='../js/scroll.js'></script>
	
	<!-- dark mode only for the member of my web site -->
	<?php if(!empty($_SESSION['id'])) {  // si un utilisateur est connecté & il a choisi le thème sombre alors 
		if($userinfo['img'] > 0 ) { // cela revient à img  = 1 dans ce cas on affiche le thème sombre & dans les autres cas le thème blanc.
			echo '<link rel="stylesheet" type="text/css" href="../css/dark-mode.css"> ';
		} 	else  {
		    echo '<link rel="stylesheet" href="../css/main-article.css "> ';
		}

	} else {
		echo '<link rel="stylesheet" href="../css/main-article.css "> ';

	}
	?>

</head>
<body style="word-wrap: break-word; text-align: justify; font-family: arial;">

		<?php include('../assets/navbar.php') ?> <br /> <br /> <br /> 

		<?php 
			if(!empty($_SESSION['id'])) {
				if($_SESSION['id'] == $infoArticle['user_id'] || $_SESSION['id'] == 2) {  // visible uniquement pour le modérateur & l'auteur de l'article
					?>
					<a href="<?='http://localhost/projet/article/edition-d-un-article.php?article_id=' . $infoArticle['img'] ?> "><button class="btn btn-primary" style="margin-left:  20px;">Modifier</button></a>
					<?php
				}

				// delete article

				if($_SESSION['id'] == 2) {  // supprimer visible uniquement par le modérateur.
					?>

					<button class="btn btn-primary" id="delete-btn" style="margin-left:  20px;">Supprimer</button></a>
					<script>
						$('#delete-btn').on('click',  function() {
							if(confirm("Tu es sûr de vouloir supprimer cet article ?")) {
								window.location.href = "supprimer.php?article_id=" + <?= $getid ?>;
							}
						});
					</script>

					<?php


				}


			} // end non empty 


		?>

	<div class="main-article" style="font-family: arial">
		<h2> <?= $infoArticle['title'] ?> </h2>
		<div align="center" style="padding-bottom: 79px;"> <img src="../img/articles/<?=$infoArticle['img'] ?> " class='img-thumbnail img-responsive img-article' style="max-width: 100%; max-height: 100%; min-width: 315px;" /> </div>
		
		<div class="content">
			<div class="content-text">
				<?= $infoArticle['textValue'] ?>
			</div>

			<?php if(!empty($_SESSION['id'])) { ?>
				<!-- like button -->
				<h6>
					<a href="#" class="heart"  onclick="return (false);"  >
						<div align="center">
				      		<div class="heart" click ="return heart();" id="heart"></div>
						</div>
					</a>
					<h4 id="heart-msg" style="text-align: center;color :gray;">+<?= $like_query->rowCount(); // le nombre de like  ?></h4>
				</h6>

				<?php



			} ?>

			<div class="df" style="background-color: #eee; color: black; padding-top: 60px;padding-bottom: 60px;padding-left: 12px;"> 
				<img src="../img/avatars/default.jpg  " class='img-fluid rounded img-circle float-right img-thumbnail' style='width: 200px; height: auto;' />
				 Un article écrit par <b><?=$infoArticle['pseudo'] ?></b> le <?= $infoArticle['dateTimePublished']?> <br />
				  <h3 style="font-size: 16px; color :gray;">Remarque(s) de l'auteur : <br /> <?= $infoArticle['remarks'] ?> </h3> <br />
							  
				<!-- signaler un article an article -->
				<?php include ('signaler-un-article.php') ?>
				<!-- #report an article -->
			 </div>
						
			

		</div>
	</div>

	<?php if(!empty($_SESSION['id']) ) { ?>


			<form method="POST" action="#report-btn">
				<!-- add comment -->

				<!--bbc code -->

			    <div class="panel-button" align="center">

				 	 <a onclick="addText(':pirate:');return(false)"><img src="../img/bbc/1.gif" border="0" alt="" style="width: 30px; height: 30px;" /></a>
					  <a onclick="addText(':triste:');return(false)"><img src="../img/bbc/2.gif" border="0" alt=""  style="width: 30px; height: 30px;"/></a>
					  <a onclick="addText(':lol:');return(false)"><img src="../img/bbc/3.gif" border="0" alt=""  style="width: 30px; height: 30px;"/></a>
					  <a onclick="addText(':)');return(false)"><img src="../img/bbc/4.gif" border="0" alt=""  style="width: 30px; height: 30px;"/></a>
					  <a onclick="addText(':ninja:');return(false)"><img src="../img/bbc/5.gif" border="0" alt=""  style="width: 30px; height: 30px;"/></a>
					  <a onclick="addText('^^');return(false)"><img src="../img/bbc/6.gif" border="0" alt=""  style="width: 30px; height: 30px;"/></a>
					  <a href="#" onclick="addText('[b]     [/b]');return(false)">	<button type="button" id="bold" style="width: 30px; height: 30px;"><i class="fa fa-bold"></i></button></span> </a>
					  <a href="#" onclick="addText('[i]    [/i]');return(false)">	<button type="button" id="italic" style="width: 30px; height: 30px;"><i class="fa fa-italic"></i></button></span></a>
					  <a href="#" onclick="addText('[code]   [/code]');return(false)"> <button type="button" id="forecolor" style="width: 30px; height: 30px;"><i class="fa fa-tint"></i></button> </span> </a> 
				</div>

				<!--comment section -->

			    <div class="comment-editor" align="center">
			      <h4 style="color : grey">LEAVE A COMMENT</h4>
			      <textarea style="width :100%;max-width: 768px; margin: auto; height: 250px;background-color: #28282B; color: white; border : none; padding: 12px;" name="message" id="message" placeholder="Ajouter un commentaire..."></textarea> <br />
					<?php 
						if(isset($error)) 
							echo " <br /><p style='color :red; background-color :#eee; padding: 13px; font-size:23px;' >" . $error ." </p>" ; 
					?>					
					<button type="submit" name="submitBtn" id="submitBtn" class="submitBtn btn btn-md btn-warning"> Envoyer </button> <br /> <br /> <br /> <br />
			      <div id="comment-form"></div>
			    </div>


			</form>



			<!-- show comment -->
		<div id="comments-container-section" >
			<?php  
				while($r = $reqComment->fetch()) { // affichage de commentaire ?> 
					<div id="comment-current-page-container" >

						<div class="comments-section">
						  <div class="comments">
						    <h4 style="background-color: none;">Commentaire :  </h4>
						    <div id="comments-container">
						      <div class="comment">
						        <div class="comment-user "><img src="../img/avatars/<?= $r['commentImage']?>" class='img-circle'>
						          <span class="user-details"><span class="username" > <a style='text-decoration : underline' href="../profil.php?id=<?=$infoArticle['user_id'] ?>"><?= $r['pseudo'] ?> </a></span><span>le </span><span><?=$r['dateTimePublished']?></span></span>
						            
						            <?php 
						            	if($r['user_id'] == $_SESSION['id'] || $_SESSION['id'] == 2) {
						            		?>
						            		<a href='supprimer-commentaire.php?comment_id=<?=$r['comment_id'] ?>' class="btn btn-basic" >Supprimer </a>
						            		<?php

						            	}
						            ?>
						         
						        
						        </div>
						        <div class="comment-text">
						           <p > <?= $r['textValue'] ?> </p><br><hr/>
						           <a href="#"><?= $r['avatar'] ?></a> 
						        </div>
						      </div>
						    </div>
						  </div>
						</div>
					</div>
					<?php 
				} 
			?>

		</div>

		<div id ='pagination'>
		
			<!-- pagination system -->
			    <?php
			        for($i=1; $i<=$totalComment; $i++) { // affichage de commentaire de manière dynamique grâce à JS bibliothèque scroll.js
			        	$myLink = "article-contenu.php?article_id=" .$article_id;
			           if($i == $currentPage) { ?>
			           	  <ul class="pagination "><li class="active"> <a href="" onclick="return false;" > <?= $i ?></a> </li> </ul> <?php 
			              
			           } else if ($i == $currentPage + 1) { ?>
			          	<ul class="pagination"><li><a href="<?=$myLink . '&t=' .  $i ?>" id='next' > <?= $i ?></a> </li> </ul> <?php

			           } else  { ?>
			          	<ul class="pagination"><li><a href="<?=$myLink . '&t=' .  $i ?>" > <?= $i ?></a> </li> </ul> <?php
			           }
			        }
	            ?>
	    </div>




		<!-- js file -->
		<script type="text/javascript" src="../js/bbcode.js"></script>
		<script>
			$('img').addClass('img-thumbnail');
		var ias = jQuery.ias({
						container:  '#comments-container-section',
						item:       '#comment-current-page-container',
						pagination: '#pagination',
						next:       '#next'
					});

		// Add a text when there are no more pages left to load
		ias.extension(new IASNoneLeftExtension({text: "You reached the end"}));
	



		</script>

		<?php  
	} else { ?>

			<br /> <br /> <br />
		<div class="alert alert-danger container-fluid" style="padding-top: 127px;padding-bottom: 127px; font-size : 23px; text-align: center; box-shadow: 5px 5px gray; max-width: 7168px; ">
		  <strong>Attention!</strong> Vous devez vous connecter pour accéder à l'espace commentaire. </div>
		</div>

		<?php

	}

	include '../assets/footer.html';


 ?>

	<style type="text/css">
		.img {
			background-color: #fef;
			padding: 15px;
			height: 60px;
			padding-left: 20px;
		}
	</style>
	<?php
		if(!empty($_SESSION['id'])) {
			$user_like = $db->prepare("SELECT * FROM likeBtn WHERE article_id  = ?  AND user_id = ?");
			$user_like->execute(array($getid, $_SESSION['id']));

			$count_user_like = $user_like->rowCount();
			if($count_user_like == 1 ) { // si l'utilisateur a déjà mis j'aime alors on l'affiche le bouton j'aime en rouge.
				?> 
				<script type="text/javascript">
					$('#heart').addClass('is-active');
				</script>
				<?php
			} else {
				?>
				<script>
					$('#heart').removeClass('is-active');
				</script>

				<?php
			}

		}
	?>

	<style>
		.content .content-text div  {
			
			border-radius: 13px;
		}


		.main-article h2 {
			 color: gray;
		}
	</style>
</body>
</html>