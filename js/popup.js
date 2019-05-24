var modal =  document.querySelector('.pop-up-container');
	var popup =  document.querySelector('.pop-up');
    var checkbox2 =  document.querySelector('#checkbox2');
	window.addEventListener('click', function(e) {
		if(e.target == modal) {
			modal.style.display = 'none';
		}	
	});

	checkbox2.oninput = function () {
		if(checkbox2.checked) {
			document.querySelector('.readMsg').style.backgroundColor = "black";
			popup.style.background = "#131418";
			popup.style.color = 'white';


		} else {			
			document.querySelector('.readMsg').style.backgroundColor = "white";
			popup.style.color = 'black';
			popup.style.backgroundColor = "#fefefe";

		}	
	}