
$(document).ready(function () {

	document.querySelector('.search-button').addEventListener('click', function() {
		$(this).parent().toggleClass('open'); // affichade pop-up
	});


    //pop -up 
    var modal =  document.querySelector('.pop-up-container');
	  var popup =  document.querySelector('.pop-up');
    var checkbox2 =  document.querySelector('#checkbox2');
	
	window.addEventListener('click', function(e) {
		if(e.target === modal) { // si l'utilisateur clique sur une zone vide en dehors du pop-up alors on le ferme
			$(modal).css('display', 'none');
		}	
	});

	var searchButton = document.querySelector('.search-bar-btn');
	searchButton.addEventListener('click', function() {
		document.querySelector('.pop-up-container').style.display='block'; //afficher le pop-up
	});

	var search = document.getElementById('search');
     search.addEventListener('keyup', function(e) {     	
            $('#result').html(''); // affcihages de données transmies par le serveur

            var q = $(this).val(); // le variable qui contient tous les données transmis au serveur par l'utilisateur

            if(q != ""){ 
                $.ajax({
                    type: 'GET',
                    url: 'rechercher.php',
                    data: 'q=' + encodeURIComponent(q),
                    beforeSend: function() {
                        $("#loading").show(); // affichaged de loader 
                    },
                    success: function(data){
                        if(data != ""){ // si la requête a reçu une réponse alors on l'affiche
                            $('#result').append(data); 
                        }else{ // sinon 
                    
                        	$('#result').html('<h2 style="color : red ">Aucun résultat !</h2> ');
                        }// endIF

                        $("#loading").hide(); // on cache le loader d'attente o_O

                    }
                });
            } //endIF

            
    });


    //  loader creatd for HTML5 by meteor
    var canvas = document.querySelector('#canvasLoader'); 
    var ctx = canvas.getContext('2d');
    var angle = 0;
    canvas.width = 80;
    canvas.height = 80;
    draw();

    function draw(){
          
          ctx.fillStyle = '#333333';          // couleur de fond de loader
          ctx.strokeStyle = 'white';          // couleur de loader
          ctx.lineWidth = 17;          // la taille de loader
          ctx.fillRect(0,0,canvas.width,canvas.height);      // à chaque instant supprimes toutes les données de l'utilisateur    
          ctx.save();          // enregistrement de l'état de canvas actuel
          ctx.translate(canvas.width/2, canvas.height/2);          
          ctx.rotate(angle)          
          ctx.beginPath();
          ctx.arc(0,0,19,0, Math.PI * 1.5);       // x, y, rayon, l'angle de dépar , angle de fin (en radiian)
          angle+=0.09;
          ctx.stroke(); // dessiner le dessin

          ctx.restore(); // revenir à l'état initail
          
          requestAnimationFrame(draw); // à,chauqe instant la fonction doit être executé ^^
       
    }

});



