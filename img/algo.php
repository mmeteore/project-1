<?php
session_start();
header('Content-Type: image/png'); // type de fichier attendu pour le navigatuer est une image  au format png




// Create the image
$imgage = imagecreatetruecolor(85, 20); // la largeur *  la longueur

// Create some colors
$white = imagecolorallocate($imgage, 255, 255, 255); 
$grey = imagecolorallocate($imgage, 128, 128, 128);
$black = imagecolorallocate($imgage, 0, 0, 0);
imagefilledrectangle($imgage, 0, 0, 75, 27, $white); 

// image line
$line_color = imagecolorallocate($imgage, 64,64,64); 
for($i=0;$i<7;$i++) {
    imageline($imgage, 0, rand()%50, 200, rand()%50, $line_color); // générer des lignes aléatoires 
}

//generate rabdom dots 

$pixel_color = imagecolorallocate($imgage, 0,0,255);
for($i=0;$i<1000;$i++) {
    imagesetpixel($imgage, rand()%200, rand()%50, $pixel_color); // génére des points aléatoires pour que ça soit le moins lisibles pour les robots.
}  

// The text to draw

$_SESSION['captcha'] = rand(1000, 9000);

// Replace path by  own font path
$font = 'fontPolice.ttf'; // police de caraactère 

// Add some shadow to the text
imagettftext($imgage, 20, 0, 11, 21, $grey, $font, $_SESSION['captcha'] );

// Add the text
imagettftext($imgage, 20, 0, 10, 20, $black, $font,$_SESSION['captcha']);



imagepng($imgage);
imagedestroy($imgage); // fin de l'image
?>