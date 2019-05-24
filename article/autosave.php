<?php
session_start();
require('../php/connect.php');
  if(isset($_POST['content']))  {
        $content = (String) trim($_POST['content']);
        $req = $db->prepare("SELECT * FROM articles WHERE img = ?"); 
        $req->execute(array($_SESSION['article_id'] ) ) ;
        $info =  $req->fetch();

        $id = $info['article_id'];

        $sql = $db->prepare("UPDATE articles SET textValue = ? WHERE article_id = ?"); // enregistrement de donnée à un intervalle de temps régulier que j'ai fixé en JavaScript
        $sql->execute(array($content,  $id ));  

    } 

