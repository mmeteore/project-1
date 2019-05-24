<?php
require '../php/connect.php'; // connexion à la base de donnée
if(!empty($_GET['article_id'])) { // si le variable conteant l'identifiant de l'article existe
	$article_id = intval($_GET['article_id']); // sécurisation de var => conversion en un nombre entier car article_id peut être modifié par l'utilisateur.
	$query = $db->prepare('SELECT * FROM articles WHERE article_id = ?');
	$query->execute(array($article_id));

	$getAutorInfo = $query->fetch();
	$email  = $getAutorInfo['email'];



} else {
	exit();
}

if(isset($_POST['report'])) {
	if(!empty($_POST['textareaReport'])) {
		$textareaReport = htmlspecialchars($_POST['textareaReport']); // case à cocher contenant les raions de ce signalemnent
		$check = '';
		if(isset($_POST['1'])) {
			$check .=  'Les informations  sont inexactes!';
		}
		if(isset($_POST['2'])) {
			$check .=  'Ne cite pas assez ses sources!';
		}
		if(isset($_POST['3'])) {
			$check .=  'Caractères haineux ou incite à la violence!';
		}
		if(isset($_POST['4'])) {
			$check .=  'Droit d\'auteurs!';
		}


		// send email

			require '../email/class.phpmailer.php'; // ajout de la biblo email
			require '../email/class.smtp.php'; // pour permettre d'envoi des email
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->setFrom($email);
			$mail->AddEmbeddedImage('m.jpg', 'logo_2u');// ajout de logo
			$txt = '
				<div style=" background:#fff; text-align : center;" > <br />
				
					<div  style=" width: 380px; margin:auto; background-color: white; padding: 5px; ;">
						<h1  style="color: white; background: #242943; width: 100%; text-align: center; height: 120px;"> Votre article a ete signale par un autre utilisateur.</h1> <br /> 
						<img src="cid:logo_2u" alt="Le logo du site e-encyclopedia" width="180px">
						<hr />
						<br />
						<br />
						Un modérateur va lire votre article car vous avez redige un article qui ne respecte les regles de notre site. <br /> 
						Pour plus d\'informations <br /><br /><br /><br />
						 <a href="http://localhost/projet/article/article-contenu.php?article_id=' . $article_id . '&code=' . 12 . '"  style="color: white; background-color: #337ab7; min-width: 400px; padding: 15px; text-decoration: none;"> Cliquez-ici </a> 
						 <br /> <br /> <br /> <br/>

						<b> Les raisons  de ce signalement ? </b> <br /> <br />
						' . $check . '
						<br />
						
						<b>Un mot de l\'utilisateur  :  </b>
						' . $textareaReport .' <br /> <br />
						Toute l\'equipe vous remercie. Nous esperons vous revoir tres vite.  <br/> <b> <i>
						Cordialement Administrateur. </b> </i>
						  <br /> <br /> <br /> 
						<p style="color: white; background: #242943; width: 100%; text-align: center; height: 110px;">  
						<i> Pour toutes questions n\'hesitez pas a nous contacter, sur ce mail  <b>shawonshoot@gmail.com.</b> Nous sommes a votre ecoute. </i>
					</div>  <br />
			   
			    </div>
				
				';
			//
			$mail->addAddress($email);
			$mail->AddCC('fantom7469r6@gmail.com', 'Modérateur' ); // envoi d'email au modérateur et à l'auteur de l'article
			$mail->Subject = 'e-encylopedia | Avertissement.';
			$mail->Body = $txt;

			$mail->isHTML(true);
			$mail->IsSMTP();
			$mail->SMTPSecure = 'ssl';
			$mail->Host = 'ssl://smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Port = 465;
			//existing gmail address as user name
			$mail->Username = 'shawonshoot@gmail.com';

			//Set the password of  gmail address here
			$mail->Password = "#P@258*******"

			if(!$mail->send()) {
			  	$error =  'Email is not sent.  PLease try again! <br />';
			  	$error =  'Email error: ' . $mail->ErrorInfo;
			} else {
			  $i =  'Email a été envoyé, un modérateur va lire ce contenu et merci de votre particapation. ';    
			}



	} else 
		$i =  'Une erreur est survenue! Vous devez complététer les raisons de ce signalement.';
}
	if(isset($i)) { ?>

		<script type="text/javascript"> alert('<?= $i?>') </script>
		<?php
	}	

?>

			<button class="btn btn-danger alertBtn"   id='report-btn' > Signaler cet article! </button>
		

<!-- pop -up s'affiche en cas de clique -->
    <div class="pop-up-container readMsg">
    	<div class="pop-up">

		<form method="POST" action='#report-btn' class="form-report">
		  <span class="closebtn" onclick="this.parentElement.style.display='none';"> &times;</span> 
		  <p> Expliquez-nous pourquoi vous voulez signaler cet  article ?   <br />
		   Nous vous rapellons que, les documents peuvent être signalés si :  </p> <br />
						<b> Tout le contenu estdupliqué, téléchargé depuis un autre site, ou plagiés.</b>
					   
					        <i> L'article   INCLUT :</i>
					        <p>du contenu copié sur d'autres sites (sauf avec l'accrod de ce dernier) </p>
					        <p>des éléments faisant l'objet d'un droit d'auteur</p>
					        <p>des obscénités</p>   
					        <p>du contenu relatif au piratage</p>
					        <p>ou encore tout autre contenu illégal, faisant la promotion d'une activité illégale ou portant atteinte aux droits d'un tiers</p>
					     
					        <p> Ce site est un site sous Licences LL-48-50-CC, ce qui signifie que chaque article publié est un droit publique.En d'autres termes quiconque (y compris le créateur de l'article ou l'administratuer de ce site) ne peut réclamer l'appropration des articles publiés! </p>
					        <p> Enfin nous vous remercions pour participer au développement de notre site et bon courage. </p>
					        <p style="color:red">Les documents non conformes aux règles énoncées ci-dessus seront supprimés et dans certains cas le compte sera suspendu ! </p>
					    
						    
			  
			    <input type="checkbox" onchange="checkboxes();" name='1'>    Les informations  sont inexactes!  <br /> <br />
			  
			    <input type="checkbox" onchange="checkboxes();" name="2"> Ne cite pas assez ses sources! <br /> 
			      <br     />

			    <input type="checkbox" onchange="checkboxes();" name="3">   Caractères haineux ou incitent à la violence! <br /> <br />
			  
			    <input type="checkbox" onchange="checkboxes();" name="4">  Droit d'auteurs! <br /> <br />

			  <p style="color:#000;"> Ajouter un commentaire supplémentaire...<i><b>(obligatoire ! )</b></i> </p>
			  <div align="center">
			  <textarea style="width :100%;max-width: 768px; margin: auto; height: 250px;background-color: #28282B; color: white; border : none; padding: 12px;" placeholder= "Décrivez la situation." id="textarea" name="textareaReport"></textarea>
			  </div>
			  <br /> 
			<input class="btn-primary btn" onclick="pressBtn();" type="submit" id="submitBtn" value="Soumettre" name="report"> <br /> <br /><br /> <br />
		</form>
	</div>
</div>

<script>
	$('#report-btn').on('click', function() {
		document.querySelector('.pop-up-container').style.display='block';
		$('.readMsg').addClass('fadeUp');
	});
</script>

<style type="text/css">
	.alert {
  padding: 20px;
  margin : auto;
  max-width: 768px;
  color: #333;
  text-align: justify;
  box-shadow: 5px 5px 15px gray;

  
}

/* pop up */

.pop-up {
  z-index: 1000;
  max-width: 650px;
  width : 100%;
  margin: auto; 
  background-color: #fff; 
  padding: 5px; 
  margin-top: 90px;
  margin-bottom: 150px;
  overflow: auto;
  padding-top: 13px;
  overflow-x: auto;
  overflow-y: auto;
  overflow: auto;
  height: 95%;
  color: black;
  padding : 15px;
  max-height: 450px;
  box-shadow: 5px 5px 15px gray;
}
.pop-up p {
  text-align: justify;
  font-size: 19px;
  font-family: serif;
}
.readMsg {
  background-color: #33333333;
  width: 100%;
  height: 100%;
  border : 2px solid black;
  position: fixed;
  top: 0;
  left : 0;
  right: 0;
  display: none;
  cursor: pointer;
  position: fixed;
}
.pop-up button {
  background-color: none;
  color: red;
  border : none;
  cursor: pointer;
  font-size: 29px;
  float: right;
}

@media screen and (max-width: 768px){
  .pop-up {
    width: 87%;
  }

} 


/* pop-up */
.closebtn {
  margin-left: 15px;
  color: red;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
  
}

.closebtn:hover {
  color: white;
}


/*chekbox  */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #4CAF50;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}


/* alertBtn */
.alertBtn {
  color :white;
  border : none;
  padding: 5px 15px;
  display: inline-block;
  font-size: 16px;
  cursor :pointer;
  height: 35px;
  
   
}

.alertBtn:hover {
  opacity: 0.8;
  transition: 1.5s;
}
.display-or-not {
	display: none;
}

</style>
	<script type="text/javascript" src="../js/popup.js"> 	


</script>