var i = 0;
var text = 'Une façon ludique et simple d\'apprendre!';
var speed = 50;

function typeWriter() {
  if (i < text.length) {
    document.getElementById("demo").innerHTML += text.charAt(i);
    i++;     
  }
}
setInterval(typeWriter, 81);
// draw cavas 
      
      (function() {
          var canvas  = document.querySelector('#canvas');
          var context = canvas.getContext('2d');
          
          context.translate(75,75);
      
          context.fillStyle = "rgb(123,23,235)";
          context.rotate((Math.PI / 180) * 45);        
          context.fillRect(0, 0, 50, 50);
          
          context.fillStyle = "orange";
          context.rotate(Math.PI / 2);        
          context.fillRect(0, 0, 50, 50);          
          
          context.fillStyle = "rgb(123,23,235)";
          context.rotate(Math.PI / 2);        
          context.fillRect(0, 0, 50, 50);        

      })();
$(document).ready(function(){  
  $(window).scroll(function() {
    $(".slideanim").each(function(){
      var pos = $(this).offset().top;

      var winTop = $(window).scrollTop();
        if (pos < winTop +500) {
          $(this).addClass("slide");
        }
    });
  });


  $('#myBtn').on('click', function(e) {
    e.preventDefault();
    $('html,body').animate({
      'scrollTop'  : 0,
    }, 1900);
  });
  
});
