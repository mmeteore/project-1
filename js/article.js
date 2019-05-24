/* -- ckeditor text -------*/

var theme = 0;
var editor = CKEDITOR.replace('textarea')
editor.addCommand("myCommand", { // ajout d'une commande qui permet de modiifer la couleur de l'éditeur de texte
    exec: function(edt) {
       // alert(editor.getData());
       if( theme == 1) {          
           edt.document.getBody().setStyle('background-color', '#fff');
           edt.document.getBody().setStyle('color', '#000');
           theme = 0;
       } else {
          edt.document.getBody().setStyle('background-color', '#262728');
          edt.document.getBody().setStyle('color', '#f1f1f1');
          theme = 1;

       }
    }

});
editor.ui.addButton('SuperButton', {
    label: "Thème (foncé ou clair)",
    command: 'myCommand',
    toolbar: 'insert',
    icon: 'https://st3.depositphotos.com/1172692/18909/v/1600/depositphotos_189092546-stock-illustration-tile-black-white-cross-vector.jpg'
});


var counter = 0;
editor.on('key', function(event) {

	var val =editor.getData(); // avoir les données  de l'éditeur de texte
	

	/* in php it exits a function to calculate the number of words in a sentence, but after avir researches this 
	function is non existent in js so I created my own function that allows to calculate the number of words*/

	function wordCount(e) {  // découpage d'une chaine de caractère
  		return e.split(" ").length;
	}
	//console.log(wordCount(val));// show the number of word


	if(val == " " ) {
		val =  "empty! ";
	}

	var wordCounter = wordCount(val);
   if(wordCounter % 17 == 0) { // à chauqe fois que l'utilisateur aura écrit 17 mots enregister ces donées sur la base de donnée. Pourquoi 17 parce que c'est un nombre premier & c'est aussi un des nombres que je préfère le plus
   		console.log('Autosave');
   			// get the url article 
   			var url = new URL(window.location.href );
			var c = url.searchParams.get("article_id"); // récupération de l'url en JS
			console.log(c);
          	$.ajax({
             	type: 'POST',
                url: 'autosave.php',
                data: 'content=' + encodeURIComponent(val)  , // envoi de données au serveur
              
          });


          	$(".p").css('display', 'block'); // affichage de message "all change saved"
          	$('.p').addClass('slideRight');
         
            setTimeout(function() {
	          $('.p').css('display', 'none');
	          $('.p').removeClass('slideRight');
          }, 5000);




      }

      counter++;

     // console.log(val);



    //console.log(getTimeBefore);

});


$(document).ready(function () {
	var txt_val = $('#txt_val').val();
	editor.setData(txt_val);	
	$(txt_val).css('display', 'none');
})


  



// fonction qui s'exécute dès lors qu'un utilisateur a cliqué surle bouton next 
function next_step2() {
document.getElementById("second").style.display = "none";
document.getElementById("third").style.display = "block"; // affichage de  la section 3 et on cache la section deux 
document.getElementById("step2").style.color = "red";
var val = editor.getData();
$('.textarea-val').html(val);
$('.next').addClass('slideRight');
$('.next').removeClass('slideLeft');

}
// fonction qui s'exécute dès lors qu'un utilisateur a cliqué surle bouton previous 
function prev_step2() {
document.getElementById("third").style.display = "none"; // affichage de  la section 2 & on cache la section 3 
document.getElementById("second").style.display = "block";
document.getElementById("step1").style.color = "red";
document.getElementById("step2").style.color = "gray";
$('.next').removeClass('slideRight'); // ajouts des animations en JS
$('.next').addClass('slideLeft');
}