<?php
session_start();
require('php/connect.php'); 
if(!empty($_GET['id']) AND $_GET['id'] >0) {

    $getid = intval($_GET['id']);
    $req = $db->prepare("SELECT * FROM member WHERE id = ?");
    $req->execute(array($getid));
    $userinfo = $req->fetch();
    $number = $userinfo['avatar'] . ".png";

    if($req->rowCount() == 0)   {  
       header("location:connexion.php");

    } 
    
  
} else {
  header ('location: connexion.php');
}

 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Page de profil de <?= $userinfo['pseudo'] ?></title>
</head>
<body style="background: #fff;">


  <!-- nav bar -->
      <div id="navbar" align='center' >   
         <div id="close" class="closebtn" onclick="closeNav()" style="color :red;text-align:center;">X</div>
         <a href="#home">Home</a>
         <a href="#news">News</a>
         <a href="#contact">Contact</a>
         <a href="#">Index</a>
         <a href="">Profil</a>
         <a href="">Article</a>

          <!-- scroll indicator -->
          <div class="header">
             <div class="progress-container">
                 <div class="progress-bar" id="myBar"></div>
             </div> 
          </div>
         <!--#scroll indicator -->          
      </div>

      <!-- only for small media  -->
      <span style="font-size:30px;cursor:pointer;background-color:#242943;" id="msNavbar" class="ms" onclick="openNav()">&#9776; Spartx </span>

      <!-- Top Button-->
      <button id="myBtn" style="display: none; position: fixed; bottom: 20px;right: 30px;  z-index: 99; font-size: 18px;
        border: none; padding: 16px;border-radius : 50%;" onclick ="
        document.body.scrollTop = 0;   document.documentElement.scrollTop = 0; ">/\</button>
      <!--#Top Button -->*

  <!--#Nav Bar -->

<div class="body"  style="max-width: 997px; background: #eee; margin: auto; border : 2px dashed #eee; ">

    <br />  <br /><br />
	<h2> Bienvue sur le profil de <?= $userinfo['pseudo'] ?> </h2>  <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />



<!------------- footer -->
        <footer class="footer-distributed">

            <div class="footer-left">

                <h3>Spartx<span>Meteor</span></h3>
                <p class="footer-company-name">Un nouveau article chaque jour !</p>
            </div>

            <div class="footer-left">

                <div>
                    <i class="fa fa-address-card-o"></i>
                    <p><span>Thèmes  </span></p>
                </div>

                <div>
                    <i class="  fa fa-address-book-o"></i>  
                    <p><span>Lire </span></p>
                </div>

                <div>
                    <i class="fa fa-cog"></i>
                    <p><a href="mailto:support@company.com">Nous soutenir</a></p>
                </div>

            </div>

            <div class="footer-right">
                  <div class="panel panel-default afooter" >
                    <div class="panel-heading"><p><i><span>Qui sui-je ?</span> </i> </p></div>
                       <div class="panel-body">
                           <p> Passioné de l'informatique, je suis un fou de la programmation.<br /> Grâce à ce site vous pouvez suivre mon aventure en temps réel ! Suivez-moi également sur: </p>
                       </div>
                  </div>

                <div class="footer-icons">

                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-linkedin"></i></a>
                    <a href="#"><i class="fa fa-github"></i></a>

                </div>

            </div>

        </footer>
</div>


<script type="text/javascript">
	
var prevScrollpos = window.pageYOffset;
window.addEventListener('scroll', function() {
	console.log(document.documentElement.scrollHeight);

  // hidden or show nab bar
   var currentScrollPos = window.pageYOffset;
   if (prevScrollpos > currentScrollPos) {
      document.getElementById("navbar").style.top = "0";
      document.getElementById("msNavbar").style.top = "0";
   } else {
      document.getElementById("navbar").style.top = "-47px";
      document.getElementById("msNavbar").style.top = "-35px";
   }
   prevScrollpos = currentScrollPos;

  // top button
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }

  // scroll indicator   
  var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var scrolled = (winScroll / height) * 100;
  document.getElementById("myBar").style.width = scrolled + "%";

});


function openNav() {
    document.getElementById("navbar").style.width = "250px";
    document.getElementById("msNavbar").style.width = "100%";  
}

function closeNav() {
    document.getElementById("navbar").style.width = "0";
    document.getElementById("msNavbar").style.width = "100%";  
    document.body.style.backgroundColor = "rgba(0,0,0,0)";
}
</script>

<style type="text/css">
	/* nav bar */
body {
  margin :0;  
  background-color: rgba(100, 100, 100, 0.1); /* Black w/ opacity */   
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
}

#navbar a {
   float: left;
   display: block;
   color: #f2f2f2;
   text-align: center;
   padding: 15px;
   text-decoration: none;
   font-size: 17px;

}

#navbar a:hover {
   background-color: #ddd;
   color: black;
}
.ms {
    display: none;
}
#close {
    display: none;
}

@media screen and (max-width: 600px)
{

#navbar {
    height: 100%;
    width: 0%;
    position: fixed;
    z-index: 1;
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
    color: #f1f1f1;
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
    width: 100%;
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
  background: yellow;
  width: 0%;
}

 html {
  scroll-behavior: smooth;
}



/* footer desing */

    .footer-distributed{
    background-color: #292c2f;
    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.12);
    box-sizing: border-box;
    width: 100%;
    text-align: left;
    font: bold 16px sans-serif;

    padding: 55px 50px;
    margin-top: 80px;
}

.footer-distributed .footer-left,
.footer-distributed .footer-left,
.footer-distributed .footer-right{
    display: inline-block;
    vertical-align: top;
}

/* Footer left */

.footer-distributed .footer-left{
    width: 40%;
}

/* The company logo */

.footer-distributed h3{
    color:  #ffffff;
    font: normal 16px 'monospace', cursive;
    margin: 0;
}
.footer-distributed p{
    color:  #ffffff;
    font: normal 16px 'monospace', cursive;
    margin: 0;
}

.footer-distributed h3 span{
    color:  #5383d3;
}
.footer-distributed  span{
    color:  #5383d3;
}

/* Footer links */

.footer-distributed .footer-links{
    color:  #ffffff;
    margin: 20px 0 12px;
    padding: 0;
}

.footer-distributed .footer-links a{
    display:inline-block;
    line-height: 1.8;
    text-decoration: none;
    color:  inherit;
}

.footer-distributed .footer-company-name{
    color:  #8f9296;
    font-size: 14px;
    font-weight: normal;
    margin: 0;
}

/* Footer Center */

.footer-distributed .footer-left{
    width: 35%;
}

.footer-distributed .footer-left i{
    background-color:  #33383b;
    color: #ffffff;
    font-size: 25px;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    text-align: center;
    line-height: 42px;
    margin: 10px 15px;
    vertical-align: middle;
}

.footer-distributed .footer-left i.fa-envelope{
    font-size: 17px;
    line-height: 38px;
}

.footer-distributed .footer-left p{
    display: inline-block;
    color: #ffffff;
    vertical-align: middle;
    margin:0;
}

.footer-distributed .footer-left p span{
    display:block;
    font-weight: normal;
    font-size:14px;
    line-height:2;
}

.footer-distributed .footer-left p a{
    color:  #5383d3;
    text-decoration: none;;
}


/* Footer Right */

.footer-distributed .footer-right{
    width: 20%;
}

.footer-distributed .footer-company-about{
    line-height: 20px;
    color:  #92999f;
    font-size: 13px;
    font-weight: normal;
    margin: 0;
}

.footer-distributed .footer-company-about span {
    display: block;
    color:  #ffffff;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 20px;
}

.footer-distributed .footer-icons{
    margin-top: 25px;
}

.footer-distributed .footer-icons a{
    display: inline-block;
    width: 35px;
    height: 35px;
    cursor: pointer;
    background-color:  #33383b;
    border-radius: 2px;

    font-size: 20px;
    color: #ffffff;
    text-align: center;
    line-height: 35px;

    margin-right: 3px;
    margin-bottom: 5px;
}

/* If you don't want the footer to be responsive, remove these media queries */

@media (max-width: 768px) {

    .footer-distributed{
        font: bold 14px sans-serif;
    }

    .footer-distributed .footer-left,
    .footer-distributed .footer-left,
    .footer-distributed .footer-right{
        display: block;
        width: 100%;
        margin-bottom: 40px;
        text-align: center;
    }

    .footer-distributed .footer-left i{
        margin-left: 0;
    }

}
footer a:hover {
  box-shadow: 5px 5px white;
  transition: 0.7s;
}


</style>
</body>
</html>