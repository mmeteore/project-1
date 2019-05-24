<?php
session_start();
if (!isset($_SESSION['try']))  
	{
		$_SESSION['try']=5;
		$_SESSION['mysteriousNumber']=rand()%100;
		$i="";	    
    }else
    {
		$_SESSION['try']--;
		if ($_SESSION['try']==0){ session_destroy();}
		if (!empty($_POST['userNumber']))
		{
		$userNumber=$_POST['userNumber'];
		}
	    else
	    {
	    	$error = "Veuillez compléter tous les  champs ! Stupide !";
	    	$userNumber = "";
	    }
		if ($_SESSION['mysteriousNumber']>$userNumber)
		    $i='+';
	    else if ($_SESSION['mysteriousNumber']<$userNumber)
		    $i='-';
		else 
			$i= '=';

   }

?>

<!-- error message -->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title> Page inexistant !</title>
    <meta name="viewport"  content="width=device-width, initial-scale=1">
  </head> 
  <body onload="draw();">  
 
       
   <canvas id="canvas" width="580"  height="480" style="margin-left: - 230px; background: ;" ></canvas> 
     

    <div id="container" align="" class="h">
      <h1 style="margin-bottom:  24%;">Erreur de navigation <br/>
           </h1>    
      <input type="button" name="" onclick="gi()" value="\/"  style="margin-bottom:  100%;" class="btn-design">
    </div>

<script type="text/javascript">
      
var countDownDate = new Date().getSeconds()+10;
var timeOut = setInterval(function() {

    var now = new Date().getSeconds();
    var distance = countDownDate - now;
    
    if (distance< 0) {
        clearInterval(timeOut);
         
    }
}, 1000);
function draw() {
  var canvas = document.getElementById('canvas');
  if (canvas.getContext) {
     var ctx = canvas.getContext('2d');
    ctx.lineWidth ="28";
    ctx.font = '130px Arial';
    ctx.fillText("4", 220, 425);

    ctx.lineWidth ="06";
    ctx.arc(375, 375, 50, 0, Math.PI * 2, true); 
 
      ctx.moveTo(365, 395);
     ctx.arc(380, 395, 7,0, Math.PI , true);
    //oeil gauche
    ctx.moveTo(365, 365);
    ctx.arc(360, 365, 5, 0, Math.PI * 2, true); 
       //pleure
     ctx.moveTo(355, 375);
     ctx.lineTo(355, 385); 
 
     // oeil droit
   
    ctx.moveTo(395, 365);
    ctx.arc(390, 365, 5, 0, Math.PI * 2, true); 
       //pleure
     ctx.moveTo(395, 375);
    ctx.lineTo(395, 385); 
    ctx.stroke();


    ctx.lineWidth ="28";
    ctx.font = '130px Arial';
    ctx.fillText("4", 450, 425);
  }
}

</script>



<div class="game1">

	<div align="center" class="container" id="i">
		<?php
			if($i=="=") {
			echo "Bravo, vous avez trouve.";
			session_destroy();
			}
			if ($_SESSION['try']==0&&$i!="=")
			 echo "<br/>Vous avez perdu.
			Le nombre etait ".$_SESSION['mysteriousNumber'].'<br/>';
		?>
			<form method="POST" action="#i" onsubmit= "document.getElementById('userNumber').focus()" id='inscription' >
          <h2 style="background: red; max-width: 80%;text-align: center;">
            <?=" <b> <i> " . $i . "</b> </i> "; ?> 
          </h2> <br  />

				<p>Entrez un nombre compris entre 1 et 100 :</p>   

				<input type="text" name='userNumber' max="100" min = "0" value="" id ='userNumber' placeholder="Entrez un nombre entier	..."  autocomplete="off" required /> <br/>
				Il vous reste <?php echo $_SESSION['try'];?> tentatives.<br/>

				<input type="submit" class='btn' id="submitBtn" value="<?php if ($i=='='||$_SESSION['try']==0)
				echo 'Rejouer' ;else echo 'Rejouer';?>" > <br />
					<script>

						var userNumber= document.getElementById('userNumber');
						var submitBtn = document.getElementById('submitBtn');
						var numbers = /[0-9]/;
						userNumber.onkeyup = function () 
						{
												
							if(isNaN(userNumber.value))
							{
							document.getElementById('error').innerHTML ="<h3> Ce n'est pas un nombre ! Abruti ! </h3>";
							submitBtn.disabled = true;
							submitBtn.style.backgroundColor = "lightgrey";

						    }
							else 
							{
								document.getElementById('error').innerHTML ="";	
								if(userNumber.value >100 || userNumber.value <0)
									{
										document.getElementById('error').innerHTML ="<h3> Ce n'est pas un nombre entier  compris entre 1 et 100 ! P'tit con !</h3>" ;
										submitBtn.disabled = true;
									    submitBtn.style.backgroundColor = "lightgrey";
									}
								else
								{
									submitBtn.disabled = false;
									submitBtn.style.backgroundColor = "green";
								
								}
							}

						}
					</script>
				<div id="error"></div>
			</form>

		<style type="text/css">
		
		

		</style>
		<?php // echo "<br/>Le nombre mystère est :" . $_SESSION['mysteriousNumber'];
		if (isset($error)){
			echo " <br /><h4>".$error. "</h4>";
			}
		?>
	</div>
</div>
         <br />
		   <button id="submitBtn" type="button" onclick="startGame()" class="game3" >Jouer à un autre jeu </button>

<!--error msg-->


    <script src="js/jquery.js"></script>

<div class="main game2">
	 <div id="txt">
		   <h1>  Alors es-tu prêt pour jouer ?</h1>
		   <p> Clique ici pour jouer</p>
	  </div>

</div>

	<script type="text/javascript">
		// error 404
		var countDownDate = new Date().getSeconds()+10;
		var timeOut = setInterval(function() {

		    var now = new Date().getSeconds();
		    var distance = countDownDate - now;
		
		    
		    if (distance< 0) {
		        clearInterval(timeOut);
		         
		    }
		}, 1000);

		$("#submitBtn").click(function () 
     {
        $("#txt").hide();
        $("#canvas").show();
        var canvas = document.getElementById('canvas');
        console.log(canvas);
        canvas.style.backgroundColor = "#eee";
     });

function startGame()
{
function random(min, max)
{
 return Math.floor(Math.random() * (max - min +1)) +min;
}
document.getElementById("canvas").style.backgroundColor = "lightblue"; 
document.getElementById("canvas").style.width = "380px";
document.getElementById("canvas").style.marginTop = "17px";  


  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera


setInterval(random,1000);
var directionY = random(-5, -2);
var directionX = random(2,3);
var canvas = document.getElementById("canvas");
var ctx = canvas.getContext("2d");
var ballRadius = 8;
var x = canvas.width/2;
var y = canvas.height-50;
var paddleH = 6;
var paddleW = 55;
var paddleX = (canvas.width-paddleW)/2;
var brickRow = 7;
var brickColumn = random(3,5);
var brickWidth = 50;
var brickHeight = 15;
var brickPadding = 23;
var brickOffsetTop = 25;
var brickOffsetLeft = 25;
var score = 0;

var bricks = [];
for(var column =0; column<brickColumn; column++)
 {
      bricks[column] = [];
      for(var row=0; row<brickRow; row++)
       {
            bricks[column][row] = 
             { x: 0,
               y: 0,
              status: 1 
            };

       }
 }
 
document.addEventListener("mousemove", mouseMoveHandler, false);


 function mouseMoveHandler(e) 
 {
 var x = e.clientX - canvas.offsetLeft;
      if(x > 0 && x < canvas.width)
       {
        paddleX = x - paddleW/2;
       }
 }
function collision() {
  for(var c=0; c<brickColumn; c++) 
  {
    for(var r=0; r<brickRow; r++)
     {
        var bcks = bricks[c][r];
        if(bcks.status == 1) 
        {
            if(x > bcks.x && x < bcks.x+brickWidth && y > bcks.y && y < bcks.y+brickHeight) 
            {
              directionY = -directionY+random(-1,1);
              bcks.status = 0;
              score++;
              if(score == brickRow*brickColumn)
               {
                alert("Gagné !");
                document.location.reload();
               }
            }
        }
     }
   }
}

function ball() 
{
  ctx.beginPath();
  ctx.arc(x, y, ballRadius, 7, Math.PI*2);
  ctx.fillStyle = "grey";
  ctx.fill();
  ctx.closePath();
}
function drawPaddle() 
{
  ctx.beginPath();
  ctx.rect(paddleX, canvas.height-paddleH, paddleW, paddleH);
  ctx.fillStyle = "black";
  ctx.fill();
  ctx.closePath();
}
function drawButton ()
{
	ctx.beginPath();
	ctx.lineWidth = "4";
	ctx.strokeStyle = "blue";
	ctx.fillStyle = " blue"
	ctx.rect(25, 275, 20, 5);
	ctx.stroke();
	ctx.fill();
	ctx.closePath()
;	
}
// below function  code founded in internet
function drawBricks() {
  for(var c=0; c<brickColumn; c++) {
    for(var r=0; r<brickRow; r++) {
      if(bricks[c][r].status == 1) {
        var brickX = (r*(brickWidth+brickPadding))+brickOffsetLeft;
        var brickY = (c*(brickHeight+brickPadding))+brickOffsetTop;
        bricks[c][r].x = brickX;
        bricks[c][r].y = brickY;
        ctx.beginPath();
        ctx.rect(brickX, brickY, brickWidth, brickHeight);
        ctx.fillStyle = "red";
        ctx.fill();
        ctx.closePath();
      }
    }
  }
}
function drawScore() {
  ctx.font = "30px Colibri ";
  ctx.fillStyle = "red";
  ctx.fillText("Score: "+score, 8, 20);
}

function draw()
 {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  drawBricks();
  ball();
  drawPaddle();
  drawScore();
  collision();
  drawButton();

  if(x + directionX > canvas.width-0 || x + directionX < 5) {
    directionX = -directionX;
  }
  if(y + directionY < ballRadius) {
    directionY = -directionY;
  }
  else if(y + directionY > canvas.height-ballRadius) {
    if(x > paddleX && x < paddleX + paddleW) {
      directionY = -directionY;
    }
    else {
     // alert("GAME OVER");
		 clearInterval(draw);
		 $('.alert').html('<p> Vous avez perdu!</p>');
      document.location.reload();
    }
  }
   



  x += directionX;
  y += directionY;
	 
  if (directionY === 0)
    {
      directionY += -1
    }

}
setInterval(draw, 15);
}



$(document).ready(function(){
  $(".game3").on('click', function(event) {

    if (this.hash !== "") {
      event.preventDefault();

      // Store hash
      var hash = this.hash;
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 800, function(){

        window.location.hash = hash;
      });
    }
  });
});


function gi() {
  $('html,body').animate({
    scrollTop: $(document).height(),
  }, 800);
  // body...
}
	</script>

<style type="text/css">
	button, .btn {
  background: #008CBA;
  border :  none;
  color: #fff;
  border-radius: 7px;
  padding: 17px;
  width: 150px;
  cursor: pointer;
  display: inline-block;
  
}
input[type=text] {
	min-width: 250px;
  width : 80%;
	border-radius: 7px;
	padding: 15px;
	border : none;
}

p {
  color :  white;
  font-size:  19px;
  
}

body {
  background: #333333;
  text-align: center;
  color: #fff;
}

.main {
  width: 100%;
  
}




body
{
    background: rgba(0, 0, 0, 0.8);
  font-weight:bolder;
  color:white;
  padding-left:5%;
 
  font-style:italic;
  font-size:1.5em;
}
.h
{
  font-family:monospace;
  color:;

}
h2
{
 text-align:center;
  
  color:white;
  font-weight:bolder;
  font-style:italic;
  font-size:6em;
}

.game2 {
	display: none;
}

canvas {
	margin-left: -260px;
	margin-top: -250px
}

.btn-design {
  border: 0px solid gray;
  background: none;
  color: white;
  letter-spacing: 0;
  font-size: 33px;
  cursor: pointer;
  width: 100%;
  padding: 23px;
  
}
  



</style>


</body>
</html>
	

