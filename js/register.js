
	/*
	* this  function verify if the user email already exist in our data base!
	* it s a ajax request 
	* I use jqery for a better compatibility with all types of browser web!
	*/
	$(document).ready(function() {
		$('.email').keyup(function(e) {

			$('.msgEmail').html();
			var emailValue = $(this).val();

			if(emailValue != '') {
				$.ajax ({
					'type'  :  'GET',
					'url'   :  'ajax/verifEmail.php',
                    'data'  : 'emailExist=' +  encodeURIComponent(emailValue),
                    'success' : function(data) {
                    	if(data != '' ) {
                    		$('.msgEmail').append(data);//si le mail existe déjà sur notre base de donnée
                    	} 

                    }

				});
			}
			if(e.keyCode == 8) {
				$('.msgEmail').empty();
			}			

		});
	});
// si le mot de passe de l'utilsiateur remplie toutes les conditions nécessaire  :)
var pwd = document.getElementById('pwd');
pwd.addEventListener('keyup', function() {
	// when the user write password
	var pwdMsg =  document.getElementById('case');	
	var submitBtn = document.getElementById('submitBtn');
	submitBtn.disabled  = false;
	pwdMsg.style.display =  "block";
	var pwd = $('#pwd');
	var pwd = document.getElementById('pwd');


	var myRegex = /[a-z]/i;
	var upperCase  = /[A-Z]/i;
	var number = /[0-9]/;


	if(pwd.value.length > 5) { // la taille de mot de passe  > 5
		document.getElementById('countChar').style.display =  "none";
	} else {
		document.getElementById('countChar').style.color =  "red";
		document.getElementById('countChar').style.display =  "block";

	}

	if(pwd.value.match(number)) {
		document.getElementById('number').style.display ="none";
	} else {
		document.getElementById('number').style.color ="red";
		document.getElementById('number').style.display ="block";
    }
    if((pwd.value.length > 5)  && pwd.value.match(number)) { // si touttes les conditions sont remplies il pouura cliquer sur le bouton submit.
    	submitBtn.disabled = false;
    }
});


setInterval(function() {

	var pwd = $('#pwd');
	var pwd2 = $('#pwd2');
    if(( pwd.value == "") || ( pwd2.value == "")){
		if(pwd.value === pwd2.value){
			  document.getElementById('pdwNoMatch').style.display ="none"
		}	else{
			  document.getElementById('pdwNoMatch').style.color ="red"
			  document.getElementById('pdwNoMatch').style.display ="block"
		}

    }

},300);


var userName = document.getElementById('userName');
userName.addEventListener('keyup', function() { // vérification de pseudo pas de caractère spéciaux.
	// when the user write his name
	var userName = document.getElementById('userName');

	var alphNum =/[a-z0-9._-]$/i ;

	if(userName.value.match(alphNum))  {
		document.getElementById('alphNum').style.display ="none";
	} else {
		document.getElementById('alphNum').style.color = "red";
		document.getElementById('alphNum').style.display = "block";

	}
	if(userName.value.match(alphNum)) {
		submitBtn.disabled = false;
	}	
});
   
