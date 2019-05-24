// verify if the user click the bannerConsent 
function clickCounter() { // si  l'utilisateur a déjà accepté les conditons générales pour écrire un article si ce n'est pas le cas bannerCookies = 0 donc on affiche le  pop-up
  if (typeof(Storage) !== "undefined") {
    if (localStorage.bannerConsent) {
      localStorage.bannerConsent = (localStorage.bannerConsent)+1;
    } else {
      localStorage.bannerConsent = 1;
    }
    console.log( "You have clicked the button " + localStorage.bannerConsent + " time(s)."  );
  } 

}

sessionStorage.clear();
setInterval(function() {

 if(localStorage.bannerConsent >= 1) {
    document.querySelector('.pop-up').style.display= "none";
    document.querySelector('.readMsg').style.display= "none";

  } else { 
      document.querySelector('.readMsg').style.display = "block";
      $('.readMsg').addClass('fadeUp');
   
  }

}, 2000);