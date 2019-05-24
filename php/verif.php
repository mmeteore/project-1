<?php 
session_start();
require('connect.php');

$email = $_GET['email'];
$getCode = $_GET['code'];

$request = $db->prepare('SELECT * FROM member WHERE email = ?');
$request->execute(array($email));

$answer = $request->rowCount();
if($answer ==  1) {
	$userInfo = $request->fetch();
	$code =  $userInfo['code'];
	$token = $userInfo['token'];
	$id = $userInfo['id'];
	$isConfirm = $userInfo['isConfirm'];
	echo "$getCode == $code";

	if($getCode == $code && $token >= 3) {
	    $insert = $db->prepare("UPDATE member SET isConfirm=? WHERE email =?");
	    $insert->execute(array(1, $email) );
        header("Location:../connexion.php");
	}

	$insert = $db->prepare("UPDATE member SET token=? WHERE email =?");
	$insert->execute(array(3, $email) );

} else {
	echo 'Error';
}