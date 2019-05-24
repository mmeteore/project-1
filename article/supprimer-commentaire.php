<?php
session_start();
require '../php/connect.php';// connexion à la base de données
if(!empty($_SESSION['id'])) { // si l'utilisateur est connecté
		if(!empty($_GET['comment_id'])) { // si le variable contenat l'identifiant du commentaire existe sion on quitte la page ! 
			$comment_id = intval($_GET['comment_id']); // conversion de ce variable de type get en un nombre entier . permet de sécuriser les données 

			$req = $db->prepare("DELETE FROM comment WHERE comment_id = ?"); // suppresion du commentaire
			$req->execute(array($comment_id));
			echo 'commentaire supprimé!';
			if(!empty($_SESSION['article_id'])) {
				header("Location:article-contenu.php?article_id=" . $_SESSION['article_id'] ); // redirection vers la page d'articles

			} else {
				header("Location:../erreur.php");
			}
		}
		
}  else {
	exit();
}