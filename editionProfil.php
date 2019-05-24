<?php
session_start();
require('php/connect.php'); // connexion à la base de données


if(!empty($_SESSION['id'])) { // si l'utilisateur est connecté sinon redirection vers la page d'erreur!
	$error = "";
	$_SESSION['avatar'] = "default.jpg";

	function secure($n){
			$n = htmlspecialchars($n);
			$n = stripcslashes($n);
			$n = strip_tags($n);
			$n = trim($n);
			return $n;
	}
	$query = $db->prepare('SELECT * FROM member WHERE id = ?');
	$query->execute(array($_SESSION['id']));

	$info =  $query->fetch();


		if(!empty($_POST['newUserName'])) { // mise à jour de pseudo

			$newName = secure($_POST['newUserName']);
			if(preg_match("#[a-z0-9]#i", $newName)) {
				$upadateName = $db->prepare("UPDATE member SET pseudo = ? WHERE id = ?");
				$upadateName->execute(array($newName, $info['id']));
				$error = "";
				header("Location:profil.php?id=". $info['id']);
			} else {
				$error ="Votre pseudo doit contenir unqiement des caractères alphanulérique";
			}	
		}


		if(!empty($_POST['newPwd']) && !empty($_POST['newPwd2'])) { // mise à jour de mots de passes :)
			$pwd  = $_POST['newPwd'];
			$pwd2 = $_POST['newPwd2'];
			$pwdLens = strlen($pwd);
			if($pwd == $pwd2) {
				$option =  [
					'cost' => 11,
				];
				if($pwdLens > 5) {
					$pwd = password_hash($pwd2, PASSWORD_DEFAULT, $option); // hash mot de passe 60 caractères !
					$upadatePwd = $db->prepare("UPDATE member SET pwd = ? WHERE id = ?");
					$upadatePwd->execute(array($pwd, $info['id']));
					$error = "ok";
					header("Location:profil.php?id=". $info['id']);
				} else {
					$error=  'Un mot de passe doit contenir plus de 5 caractères';
				}
			} else {
				$error = "Vos deux mots de passe ne correspondent pas!";
			}
		} 

		if(!empty($_POST['sign'])) { // mise à jour des informations personelles sur l'utilisateur

			$newSign = secure($_POST['sign']);
			if(preg_match("#[a-z0-9]#i", $newSign)) {
				$upadateSign = $db->prepare("UPDATE member SET bio = ? WHERE id = ?");
				$upadateSign->execute(array($newSign, $info['id']));				
				$error = "";
				header("Location:profil.php?id=". $info['id']);
			} else {
				$error ="Votre pseudo doit contenir unqiement des caractères alphanulérique";
			}	
		}
  

    if(!empty($_FILES['avatar']['name'])) { // mise à jour de photo de profil
      

        $fileImage = $_FILES['avatar']['name'] ;
        $extension = pathinfo($fileImage, PATHINFO_EXTENSION); // récupération de l'extension de fichier (jpg, png)
        $tmp_name = $_FILES["avatar"]["tmp_name"];

        if((exif_imagetype($tmp_name) == 2) || ( exif_imagetype($tmp_name) == 3) ) { // s'il sagit d'un fichier png ou jpeg alors on met à jour sinon on affichec une erreur
              $userAvatar =  $_SESSION['id'] . '.' . $extension;
              $_SESSION['avatar'] = $userAvatar;
              $uploadDestination = "img/avatars/" . $userAvatar;
              $insertAvatar= $db->prepare("UPDATE member SET avatar = ? WHERE id = ?");
              $insertAvatar->execute(array($userAvatar, $_SESSION['id']));
              move_uploaded_file($tmp_name, $uploadDestination);
              header('Location:profil.php?id='.$_SESSION['id']); 

        }  else {
            $error ='Ce format d\'iamge est inconnu! ';
        }

    }

    if(isset($_POST['checkbox'])) { // changmeent de thème si la valeur vaut 1 alors thème noir sinon thème blanc ^^
		$setTheme = $db->prepare("UPDATE member SET img = 1 WHERE id = ?");
		$setTheme->execute(array( $_SESSION['id']));				
		
		header("Location:profil.php?id=". $info['id']);
 
    } else {
		$setTheme = $db->prepare("UPDATE member SET img = -1 WHERE id = ?");
		$setTheme->execute(array( $_SESSION['id']));	

    }
  

  ?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Éditer le profil de <?= $info['pseudo'] ?> </title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/edit-profil.css">
		<script type="text/javascript" src="js/jquery.js"></script>
	</head>
	<body style='background:none; text-align: center;'>
		<?php 
					include_once('assets/navbar.php'); 
		?>
		<br /><br /><br />
		
	<div class="form-edit">
  		<h2>Édition de profil</h2>
        <form method="POST" action="#" name="editProfil" enctype="multipart/form-data">
		    <input type="text" name="newUserName" placeholder="pseudo" style="box-shadow: none; color: grey"  value="<?= $info['pseudo'] ?>" />
		    <input type="password" name="newPwd" placeholder="mot de passe" style="box-shadow: none; color: grey" />
		    <input type="password" name="newPwd2" placeholder="mot de pass (Confirmation)" style="box-shadow: none; color: grey" /> <br />
		    <textarea placeholder="Signature" class="sign" name="sign" id='sign' onkeyup="adjust_textarea(this)"> <?= $info['bio'] ?></textarea> <br />

			<div class="dragover">
				<label for='avatar' class="file" > Glisser déposer un fichier <br /> ou cliquer sur ce bouton pour <br /> changer la photo de profil. <br /> <sub> Formats acceptés (jpeg, jpg, png) </sub>  <br />
					<input type="file" name="avatar" id="avatar"> 
				</label>
			</div> <br />


		    <!-- Checkbox -->
		    <label class="switch">
		        <input type="checkbox" name="checkbox" id="checkbox">
		        <span class="slider round"></span>
		    </label>	Changer de thème!  
		    <button class="show" onclick="document.querySelector('.pop-up-container').style.display='block';" type="button" checked>(voir un démo) ?</button><br />



			<?php
				if(isset($error)) {
					echo "<p style='color :red '>" .  $error . '</p> ';
				}        
			?>

		    <button name="submitBtn" id="submitBtn" class="registerbtn" style="margin-bottom:  20px" type="submit"> Modifier!</button> 

	  </form>
    </div>


    <!-- pop -up -->
    <div class="pop-up-container readMsg">
    	<div class="pop-up">
    		<label class="switch">
		        <input type="checkbox" name="checkbox" id="checkbox2">
		        <span class="slider round"></span>
		    </label>Changer de thème!  <button  onclick="document.querySelector('.pop-up-container').style.display='none'">&times</button><br /><br />
    		<h1>  Quam tuis non dicam cursibus, sed victoriis lustratae sunt.  </h1> <br />
				<p>Excogitatum est super his, ut homines quidam ignoti, vilitate ipsa parum cavendi ad colligendos rumores per Antiochiae latera cuncta destinarentur relaturi quae audirent. hi peragranter et dissimulanter honoratorum circulis adsistendo pervadendoque divites domus egentium habitu quicquid noscere poterant vel audire latenter intromissi per posticas in regiam nuntiabant, id observantes conspiratione concordi, ut fingerent quaedam et cognita duplicarent in peius, laudes vero supprimerent Caesaris, quas invitis conpluribus formido malorum inpendentium exprimebat. <br /><br /><br />

				Restabat ut Caesar post haec properaret accitus et abstergendae causa suspicionis sororem suam, eius uxorem, Constantius ad se tandem desideratam venire multis fictisque blanditiis hortabatur. quae licet ambigeret metuens saepe cruentum, spe tamen quod eum lenire poterit ut germanum profecta, cum Bithyniam introisset, in statione quae Caenos Gallicanos appellatur, absumpta est vi febrium repentina. cuius post obitum maritus contemplans cecidisse fiduciam qua se fultum existimabat, anxia cogitatione, quid moliretur haerebat. <br /><br />

				Soleo saepe ante oculos ponere, idque libenter crebris usurpare sermonibus, omnis nostrorum imperatorum, omnis exterarum gentium potentissimorumque populorum, omnis clarissimorum regum res gestas, cum tuis nec contentionum magnitudine nec numero proeliorum nec varietate regionum nec celeritate conficiendi nec dissimilitudine bellorum posse conferri; nec vero disiunctissimas terras citius passibus cuiusquam potuisse peragrari, quam tuis non dicam cursibus, sed victoriis lustratae sunt.  <br /><br /><br />

				Altera sententia est, quae definit amicitiam paribus officiis ac voluntatibus. Hoc quidem est nimis exigue et exiliter ad calculos vocare amicitiam, ut par sit ratio acceptorum et datorum. Divitior mihi et affluentior videtur esse vera amicitia nec observare restricte, ne plus reddat quam acceperit; neque enim verendum est, ne quid excidat, aut ne quid in terram defluat, aut ne plus aequo quid in amicitiam congeratur.  <br /><br /><br />

				Etenim si attendere diligenter, existimare vere de omni hac causa volueritis, sic constituetis, iudices, nec descensurum quemquam ad hanc accusationem fuisse, cui, utrum vellet, liceret, nec, cum descendisset, quicquam habiturum spei fuisse, nisi alicuius intolerabili libidine et nimis acerbo odio niteretur. Sed ego Atratino, humanissimo atque optimo adulescenti meo necessario, ignosco, qui habet excusationem vel pietatis vel necessitatis vel aetatis. Si voluit accusare, pietati tribuo, si iussus est, necessitati, si speravit aliquid, pueritiae. Ceteris non modo nihil ignoscendum, sed etiam acriter est resistendum.  <br /><br /><br /><br />

				Etenim si attendere diligenter, existimare vere de omni hac causa volueritis, sic constituetis, iudices, nec descensurum quemquam ad hanc accusationem fuisse, cui, utrum vellet, liceret, nec, cum descendisset, quicquam habiturum spei fuisse, nisi alicuius intolerabili libidine et nimis acerbo odio niteretur. Sed ego Atratino, humanissimo atque optimo adulescenti meo necessario, ignosco, qui habet excusationem vel pietatis vel necessitatis vel aetatis. Si voluit accusare, pietati tribuo, si iussus est, necessitati, si speravit aliquid, pueritiae. Ceteris non modo nihil ignoscendum, sed etiam acriter est resistendum.  <br /><br />
				 
				Haec igitur lex in amicitia sanciatur, ut neque rogemus res turpes nec faciamus rogati. Turpis enim excusatio est et minime accipienda cum in ceteris peccatis, tum si quis contra rem publicam se amici causa fecisse fateatur. Etenim eo loco, Fanni et Scaevola, locati sumus ut nos longe prospicere oporteat futuros casus rei publicae. Deflexit iam aliquantum de spatio curriculoque consuetudo maiorum.  <br />
			</p>
		</div>
	</div>
	<script type="text/javascript" src="js/popup.js"> 	
</script>

	<?php 
	include_once('assets/footer.html');

}	else {
	header("Location: erreur.php");
	exit();
}		
	?>


	</body>
	</html>

	