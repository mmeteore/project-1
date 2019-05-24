<?php
session_start();
require('php/connect.php');
// get the first 20 words 
function subStrWords($text, $lens) {
      if (str_word_count($text) > $lens) {
            $words = str_word_count($text, 2);// 2 - returns an associative array, where the key is the numeric position of the word inside the string and the   value is the actual word itself 
            $i = array_keys($words);
            $text = substr($text, 0, $i[$lens]) . '...';
      }

      return strip_tags($text);
}


if(!empty($_GET['id']) AND $_GET['id'] >0) {


    $getid = intval($_GET['id']);
    $req = $db->prepare("SELECT * FROM member WHERE id = ?");
    $req->execute(array($getid));
    $userinfo = $req->fetch();

    if($req->rowCount() == 0)   {  
       header("Location:erreur.php");
       exit();

    } 

  // get user info about article !
    $query =  $db->prepare("SELECT * FROM articles WHERE user_id = ?  ORDER BY article_id DESC   ");
    $query->execute(array($getid));
    $getArticleInfo = $query->rowCount();
    $i = 0;

    
  
} else {
  header ('Location:erreur.php');
  exit();
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
    <meta name="robots" content="noodp" />
    <meta name="DC.Format" content="text/html" />
    <meta name="DC.Type" content="Text" />

    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png" />
    <link rel="icon" type="image/png" href="img/logo.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="img/logo.png" sizes="16x16" />

  	<title> Profil de  <?=$userinfo['pseudo'] ?></title>  
  	<script src="js/jquery.js" ></script>
  	<link rel="stylesheet" type="text/css" href="css/profil.css">
    <link rel="stylesheet" type="text/css" href="css/section.css">

</head>
<body> 



  <!-- nav bar -->
  <?php 
     include_once('assets/navbar.php'); 
  ?>

  <!--#Nav Bar -->



	<br /> <br /> <br />

	<!-- BACKGROUND PICTURE -->
<div class="bg-pic  content">
	<div class="profile-pic">

        <img style="vertical-align: middle;height: 179px; " src="<?php echo('img/avatars/' . $userinfo['avatar']) ?>" />
        <span style="vertical-align: middle; display:inline-block; padding-left: 17px; text-align: center;"> <h2 class="sign"> <?= $userinfo["bio"] ?></h2></span>
      
  </div>
</div>
<!-- # background Picture -->

<div class="info content">
	<div class="col s12 m6 profile-numbers">
        <div class="spacer-number">
            <p class="name" style="font-size:20px;"><?= $userinfo['pseudo'] ?></p>
            <p class="designation">Un pur génie</p>
        </div>
      
        <div class="spacer-number">
            <p class="name" style="font-size:20px;"><?= $getArticleInfo ?></p>
            <p class="designation">Articles écrits</p>
        </div>
       
        <div class="spacer-number">
            <p class="name" style="font-size:20px;">0</p>
            <p class="designation">En cours de rédaction</p>
        </div>
    </div>
    <?php 

     if(!empty($_SESSION['id']) && $_SESSION['id'] == $userinfo['id']) {
     	?>
		     <a href="editionProfil.php"><button class="btn"> Éditer</button> </a>
    	 <?php	
     }
     ?>
 </div>

 <!-- Information about the user -->
 <?php 
  if($getArticleInfo  == 0) {  ?>
      <div class="comments-section" >
            <div class="comments">
              <h4> Les articles de <?=$userinfo['pseudo'] ?></h4>
              <div id="comments-container">
                <div class="comment">
                  <div class="comment-user">
                    <span class="user-details"><span class="username"><p> Vous n'avez écrit actuellement aucun article ! </p></span>
                  </div>
                  <div class="comment-text">
                     <h2>Nous avons besoin de votre aide. Vous pouvez commencer à écrire article. <br /> Aidez-nous, vous l'internaute à créer des articles, pour améliorer ce site! </h2>
                     
                     <?php if(!empty($_SESSION['id']) && $_SESSION['id'] == $getid['id']) { ?>
                                  <a href="http://localhost/projet/article/article-contenu.php?article_id=<?= $getArticle['article_id'] ?>" > <br /><button class="btn btn-primary">Commencer à créer un article.</button></a>

                     <?php }  ?>

                  </div> 
                  <hr />
                  <p> <a><?= $userinfo['bio'] ?> </a> </p>

                </div>
              </div>
            </div>
      </div>
    <?php
 } // END IF 
  if($getArticleInfo != 0) { 
      ?> 
    
      <div class="comments-section">
        <?php 

          while($r =  $query->fetch() ) {  
            $i++;
            $getContent  = $r['textValue'];
            $content =  subStrWords( $getContent, 83);

             ?>
            <div class="comments  ">
              <h4>Articles n° <?= $i  ?></h4>
              <div id="comments-container ">
                <div class="comment ">
                  <div class="comment-user slider " >
                    <span class="user-details"><span class="username"><h3><?= $r['title'] ?> </h3> </span><span>le </span>
                      <span>  <?=  $r['dateTimePublished'] ?> </span> 
                    </span>
                  </div>
                  <div class="comment-text">
                     <p><?= $content?> </p> <br><br>
                   <a href="http://localhost/projet/article/article-contenu.php?article_id=<?= $r['article_id'] ?>" > <br /><button class="btn btn-primary">Lire cet article.</button></a>
                  </div>
                </div>
                  </div>
               </div>

            <?php

        } // END WHILE
    } //ENDIF

        ?>
    </div>

 



	<br /> <br /> <br />
	<br /> <br /> <br />
	<br /> <br /> <br />
	<br /> <br /> <br />
	<br /> <br /> <br />



<?php 
include_once('assets/footer.html');
?>
<script> 
  window.addEventListener('scroll', function() {
     $(".slider").each(function(){
      var pos = $(this).offset().top;

      var winTop = $(window).scrollTop();
        if (pos < winTop ) {
          $(this).css('display', 'block');
        }
    });
  });
</script>

</body>
</html>