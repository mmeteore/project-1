	function addText(e, subject) { // premier argument contient le texte à ajouter et le deuxième l'id de text area
		    subject = document.getElementById('message');     
	        var startPos = subject.selectionStart; //avoir toutes les chaînes de caractères avant le foucs
	        var endPos = subject.selectionEnd; // //avoir toutes les chaînes de caractères après le focus
	        var msgValue =$(subject).val() ; //avoir l'ensemble de contenu de textarea

	        subject.value = msgValue.substring(0, startPos) + e + msgValue.substring(endPos, msgValue.length ); // découpage de chaîne de caractère avec substr puis ajout du texte enfin ajout du texte après le focus.

	        subject.selectionStart = startPos + e.length; // inuitile car je n'ai pas réussi ajouter une fonctionnalité  supllémentaire mais je suis entrain de rechercher la solution et je vais y arriver,  un jour, le jour j'aurais accomplis de grands exploits  :)
          
	        subject.selectionEnd = endPos + e.length;

	        subject.focus(); // remettre le focus  sur le textarea
	} 

	// heart imgage



$(".heart").on("click", function() { // envoi de manière asynchrone les données lorsque l'utilisateur clique sur le bouton j'iame
	
	$(this).addClass("is-active");
	$.ajax({
		type : "POST",
		url : '../assets/like.php',
        'data'  : 'like=' +  encodeURIComponent(1),
        
		
	});
});
