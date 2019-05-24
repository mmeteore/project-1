<?php
session_start();
require '../php/connect.php';
$article_id = $_SESSION['article_id'];

if(!empty($_SESSION['id'])) {
	$user_id = $_SESSION['id']; 

} else {
	exit();
}
if(!empty($_SESSION['article_id'])){
	$article_id  = $_SESSION['article_id'];
} else {
	exit();
}

$req = $db->prepare("SELECT user_id FROM likeBtn WHERE article_id  = ? AND user_id = ? ");
$req->execute(array($article_id, $_SESSION['id']));

$count = $req->rowCount();

if($count == 0) {
	$insert  = $db->prepare('INSERT INTO likeBtn (article_id, user_id, val) VALUES (?,?,?)');
	$insert->execute(array($article_id, $user_id, $count+1));
} else { ?>
	<script>
		document.querySelector('#heart').classList.add('is-active');
	</script>
	
	<?php

}

?> 