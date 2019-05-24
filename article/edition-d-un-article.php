<?php
session_start();
require('../php/connect.php');
function secure ($n) {
    $n = htmlspecialchars($n);
    $n =  strip_tags($n);
    $n = strip_tags($n);
    $n = trim($n);

    return $n;
}
$article_id = (string) secure($_GET['article_id']);
$_SESSION['article_id'] = $article_id;

$req = $db->prepare('SELECT * FROM articles WHERE  img =  ?');
$req->execute(array($article_id));
$articlesInfo  =  $req->fetch();

$count  = $req->rowCount();

if($count == 1) {
    $article_id  =  $articlesInfo[0];
    $title  = $articlesInfo['title'];
    $user_id = $articlesInfo['user_id'];
    $text  = trim($articlesInfo['textValue']);
    if(!empty($_POST['title']))
        $updateTitle =  secure(ucfirst($_POST['title']));

    if($user_id == $_SESSION['id'] || $_SESSION['id'] == 2) { // si c'est l'ateur de l'article ou le modérateur du site alors on affiche les contenus sinon on envoie l'utilisateur vers la page d'erreur 404

        if(isset($_POST['submitBtn'])) {

            if(!empty($_POST['textarea'])) {
                $txt  = ucfirst($_POST['textarea']) ; // converts the first character of a string to uppercase
                $size =  str_word_count($txt);
                if(!empty($_POST['source'])) {
                    $source = nl2br(secure($_POST['source']));

                    $updateSource = $db->prepare("UPDATE articles SET remarks = ? WHERE article_id = ?"); // mise à jour de données
                    $updateSource->execute(array($source, $article_id) );
                } else {
                    $source  = "Aucunes informations supplémentaires de la part de l'utilisateur";
                    $updateSource = $db->prepare("UPDATE articles SET remarks = ? WHERE article_id = ?");
                    $updateSource->execute(array($source, $article_id) );
                }

                if($size >  100) { // si l'article continet moins de 100 mots il ne pourra être publié ! 
                               
                        $updateText = $db->prepare("UPDATE articles SET textValue = ? WHERE article_id = ?");
                        $updateText->execute(array($txt, $article_id) );
                        //update published file  
                        $updatePublished = $db->prepare("UPDATE articles SET isPublished =? WHERE article_id = ? ");
                        $updatePublished->execute(array(1, $article_id) );
                        header("Location: ../article/article-contenu.php?article_id=" . $article_id);

                        // update title 
                        if(!empty($updateTitle)) {
                            $updateTitleQuery = $db->prepare("UPDATE articles  SET title  = ? WHERE article_id  =  ?");
                            $updateTitleQuery->execute(array($updateTitle, $article_id));
                            header("Location: ../article/article-contenu.php?article_id=" . $article_id);
                        }




                } else {
                    $error = "Votre article doit contenir plus de 500 mots";
                }
            } else {
                $error = 'Articles incomplets! ';
            }
        }


    } else {
        header('Location:../erreur.php');
        exit();
    }

// update file


    


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

            <title>Edition :</title>
            <link rel="stylesheet" type="text/css" href="../css/article.css">
            <link rel="stylesheet" type="text/css" href="../css/anim.css">            
            <script type="text/javascript" src="../assets/ckeditor/ckeditor.js"></script>
            <script type="text/javascript" src="../js/jquery.js"></script>
        </head>

        <body>
            <!-- nav bar -->
            <?php include ('../assets/navbar.php'); ?>
            <?php
                if(isset($error)) {
                    echo "<br /> <br /> <p style ='color :#fff; background : #333; padding : 17px; text-align :center; position : fixed; bottom : 0; left : 0; right : 0; z-index : 97;'>" . $error . "</p>";
                } 
            ?>
            

            <div class="content next"> 
                <p class="p"> All changes saved </p>
                <!-- Multistep Form -->
                <div class="main">
                    <form action="#" class="regform" method="POST" enctype="multitpart/form-data">
                    <!-- Progressbar -->
                        <ul id="progressbar"> <br /><br />
                            <li id="step1">Rédaction de l'artciles</li>
                            <li id="step2">Vos sources</li>
                        </ul>
                        <!-- info -->
                    <h2><br /> <input type="text" name="title" value="<?= $title ?>" style='width: 80%; padding: 35px; text-align: center; border : 1px solid gray; border-radius: 7px;'> </h2>
                    <img src="../img/articles/<?= $articlesInfo['img'] ?>" style="width: auto;height: auto; min-width: 350px; max-width: 500px; " />

                        <!-- container -->
                       <fieldset id="second">
                            <h2 class="title">Contenu ! </h2>
                            <p class="subtitle">Ecrivez votre article ci-dessus  : </p>

                            <!-- text area article -->

                             <textarea  id="textarea" class="ckeditor" name="textarea" ></textarea> <br>

                           

                            <input id="next_btn2" name="next" onclick="next_step2()" type="button" value="Next">
                        </fieldset>

                        <fieldset id="third">
                            <h2 class="title">Vos sources</h2>
                            <p class="subtitle">Étape 3</p>
                            <p> Des informations suuplémentaires </p>
                            <i> (facultatifs) </i>  <br />
                            <!-- bbc code -->

                            <textarea name="source" placeholder="Ajouter une remarque, vos source..." ><?=   $articlesInfo['remarks']   ?></textarea>
                            <input id="pre_btn2" onclick="prev_step2()" type="button" value="Précédent">
                            <input class="submit_btn" onclick="validation(event)" type="submit" value="Publier" name="submitBtn">
                        </fieldset>


                        <!-- get the value  of hidden text area -->
                        <div class= "val" hidden="true" > 
                            <textarea id='txt_val'> <?=  $text?> </textarea>
                         </div>
                    </form>
                </div>
            </div>
            <!-- js file -->
            <script src="../js/article.js"> </script>
            <!-- footer -->
            <?php
            include ('../assets/footer.html') ; ?>

        </body>
    </html>


<?php 
} else {
  header("Location : ../erreur.html");
}
?>