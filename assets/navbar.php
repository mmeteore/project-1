 <!-- nav bar -->
      <div id="navbar" align='center' >   
         <div id="close" class="closebtn" onclick="closeNav()" style="color :red;text-align:center;">X</div>

         <div class="dropdown">
          <button class="dropbtn">Lire un article 
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-content">
            <a href="http://localhost/projet/article/index.php?section=1"> Science (Maths, astronomie)</a>
            <a href="http://localhost/projet/article/index.php?section=2">Physique-Chimie</a>
            <a href="http://localhost/projet/article/index.php?section=3">Biologie(écologie, géologie, les animaux)</a>
            <a href="http://localhost/projet/article/index.php?section=4">Informatique</a>
            <a href="http://localhost/projet/article/index.php?section=5">Histoire</a>
            <a href="http://localhost/projet/article/index.php?section=6"> Géographie</a>
            <a href="http://localhost/projet/article/index.php?section=7"> Économie</a>
            <a href="http://localhost/projet/article/index.php?section=8"> Littérature (films, art, peinture)</a>
            <a href="http://localhost/projet/article/index.php?section=9">Sport</a>
          </div>
        </div> 


         <a href="http://localhost/projet/index.html"  style='color: white; float: ; padding-right:103px'>Accueil</a>
         <a href="http://localhost/projet/assets/cgu.html" target="_blank">À propos</a>
         <?php
             if(isset($_SESSION['id'])) {     ?>
                <a href="http://localhost/projet/article/ecriture-d-un-article.php">Participer au projet</a>

                <a href="http://localhost/projet/profil.php?id=<?= $_SESSION['id'] ?>">Profil</a>
                <a href="#" onclick=" disconnect(); "> Se déconnecter </a>
                <script>
                  function disconnect() {
                  
                    if(confirm("Vous êtes sur de vouloir se déconnecter ? ")) {
                      window.location.href ="http://localhost/projet/se-deconnecter.php" ;

                    }

                  }
                </script>
                <?php

              } else { 
                   ?>
                  <a href="http://localhost/projet/inscription.php"> S'inscrire</a>
                  <a href="http://localhost/projet/connexion.php"> Se connecter</a> 
                  <?php

              } 
                ?>

          <!-- scroll indicator -->
          <div class="header">
             <div class="progress-container">
                 <div class="progress-bar" id="myBar"></div>
             </div> 
          </div>
         <!--#scroll indicator -->          
      </div>

      <!-- only for small media  -->
      <span style="font-size:30px;cursor:pointer;background-color:black; color: white; height: 51px; " id="msNavbar" class="ms" onclick="openNav()">&#9776; <span style='text-align: center; padding-left: 10%;' > e-encylopedia </span> </span>

      <!-- Top Button-->
      <button id="myBtn" style="display: none; position: fixed; bottom: 20px;right: 30px;  z-index: 99;  background: none; border: none;cursor: pointer;" onclick ="
        "> <img style="width: 40px; height: 40px; border :none;" src="http://localhost/projet/assets/top.png"> </button>
      <!--#Top Button -->

  <!--#Nav Bar -->

<script type="text/javascript">

var prevScrollpos = window.pageYOffset;
$(window).on('scroll', function(e) {
  

      // hidden or show nab bar
      e = window.pageYOffset; // la position de la page
     if (prevScrollpos > e) { // si l'utilisateur "scroll" vers la bas alors on affiche la barre de navigation il y a deux barres de navigations car ceux-ci s'adaptent en fonction de la taille d'écran
        document.getElementById("navbar").style.top = "0"; // display : block and the position top  : 0
        document.getElementById("msNavbar").style.top = "0";
     } else { // sinon on "cache" la barre de navigation
        document.getElementById("navbar").style.top = "-47px"; // dispay : none and the position at the top is : -47px
        document.getElementById("msNavbar").style.top = "-47px";
     }
     prevScrollpos = e; // réactualisation de la position de l'utilisateur à chaqe scroll

    // top button
    if (document.body.scrollTop > 97 || document.documentElement.scrollTop > 97) { // si l'utilisateur a "scrollé"  plus de 97px alors on affiche le bouton qui permet de monter vers le haut + usage de polyfill
      document.getElementById("myBtn").style.display = "block";
      $('#myBtn').addClass('slide'); // jaouts des animations
    } else {
      document.getElementById("myBtn").style.display = "none";
      $('#myBtn').removeClass('slide');
    }

    // scroll indicator   
    var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    var height = document.documentElement.scrollHeight - document.documentElement.clientHeight; // barre de scroll pour savoir où se trouve l'utilisateur
    var scrolled = (winScroll / height) * 100; // conversion en pourcentage valueur x compris entre 0 <= x <= 100
    document.getElementById("myBar").style.width = scrolled + "%"; // affichage en pourcentage

});


function openNav() {
    document.getElementById("navbar").style.width = "250px"; // affichage de la barre de navigation :)
    document.getElementById("msNavbar").style.width = "100%";  
}

function closeNav() {
    document.getElementById("navbar").style.width = "0";
    document.getElementById("msNavbar").style.width = "100%";  
    document.body.style.backgroundColor = "rgba(0,0,0,0)";
}
// anim top button
$("#myBtn").on('click', function(e) {
  e.preventDefault();
  $('html,body').animate({
    scrollTop  : 0 // ajout d'une petite animation lorsque l'utilisateur décide de remonter tout en haut de la page
  }, 1100);
});
</script>

<style type="text/css">
  /* nav bar */
body {
  margin :0;    
  color:;
}
#navbar {
   background:black; 
   position: fixed;
   top: 0;
   width: 100%;
   display: block;
   transition: top 0.3s;
   text-align: center;
   z-index: 1187;
}

#navbar a {
   float: left;
   display: block;
   color: #f2f2f2;
   text-align: center;
   padding: 15px;
   text-decoration: none;
   font-size: 17px;
   min-width: 125px;

}

#navbar a:hover {
   background-color: #333;
   color: white;
}
.ms {
    display: none;
}
#close {
    display: none;
}

@media screen and (max-width: 1050px)
{

#navbar {
    height: 100%;
    width: 0%;
    position: fixed;
    z-index: 100000;
    top: 0;
    left: 0;
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 60px;
}

#navbar a {
    padding: 8px 8px 8px 32px;
    text-decoration: none;
    font-size: 25px;
    color: #818181;
    display: block;
    transition: 0.3s;
    width:100%;
    display:inline;
}

#navbar a:hover {
    color: red;
    background-color: red;
}

#navbar .closebtn {
    position: absolute;
    top: 0;
    right: 25px;
    font-size: 36px;
    margin-left: 50px;
}

.ms {
    display: block;
    position: fixed;
    top:0;
    width: 150%;
}
#close
{
    display: block;
    position: fixed;
    left:0;
    top:0;
}

}
/* scroll indicator */

.header {
  position: fixed;
  top: 0;
  z-index: 1;
  width: 100%;
}


.progress-container {
  width: 100%;
  height: 3px;
}

.progress-bar {
  height: 2px;
  background: yellow!important;
  
}

 html {
  scroll-behavior: smooth;
}



  @-webkit-keyframes slide {
    from {
      opacity: 0;
      -webkit-transform: translate3d(2000px, 0, 0);
      transform: translate3d(2000px, 0, 0);
    }

    to {
      opacity: 1;
      -webkit-transform: translate3d(0, 0, 0);
      transform: translate3d(0, 0, 0);
    }
    }

  @keyframes slide {
    from {
      opacity: 0;
      -webkit-transform: translate3d(2000px, 0, 0);
      transform: translate3d(2000px, 0, 0);
    }

    to {
      opacity: 1;
      -webkit-transform: translate3d(0, 0, 0);
      transform: translate3d(0, 0, 0);
    }
    }


     .slide {
      display: block;
      animation: slide 2s ease 1;
      -webkit-animation: slide 2s ease 1;;
    }




/* drop down */



.dropdown {
  float: left;
  overflow: hidden;
}

.dropdown .dropbtn {
  font-size: 16px;  
  border: none;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit;
  margin: 0;
}

.navbar a:hover, .dropdown:hover .dropbtn {
  background-color: red;
}

.dropdown-content {
  display: none;
  position: fixed;
  background-color: black;
  z-index: 100000;
}

.dropdown-content a {
  float: none;
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align: left;
}

.dropdown-content a:hover {
  background-color: red;
  display: block;
}
 .dropdown:hover {
  display: block;

}
/* drop down */


</style>
<script type="text/javascript">
  $('.dropdown').on('click', function () {
    $('.dropdown-content').css('display','block');

  });

  $('.dropdown').on('mouseover', function () {
    $('.dropdown-content').css('display','block');
  }); 

  $('.dropdown-content').on('mouseout', function () {
    $(this).css('display','none');

  });


</script>