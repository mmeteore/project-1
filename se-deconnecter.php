<?php
session_start();

setcookie('email','',time()-1123*1234); // suppresion de cookies
setcookie('pwd','',time()-24*13*365);
$_SESSION = array(); // unset all session value
session_destroy(); // destruction de la session


header("Location:connexion.php"); // redirection vers la page de connexion.
 ?>
