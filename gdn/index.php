<?php
    session_start();
    // Destruction de la session
	if ((isset($_GET['action'])) && ($_GET['action'] == 'logout'))
	{
				session_destroy();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="images/favicon32.ico"/>
    <link rel="stylesheet" type="text/css" href="styles/style.css" id="bleu" />
    <script type="text/javascript" src="js/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="js/jquery-validate.js"></script>
<style type="text/css">
    * { font-family: Verdana; font-size: 96%; }
    label { display: block}
    label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
    .submit { margin-left: 12em; }
    em { font-weight: bold; padding-right: 1em; vertical-align: top; }
</style>
    <script type="text/javascript">

 $(document).ready(function(){
    // validation du formulaire de changement de mot de passe
 $("#auth").validate({
     rules: {
         id: "required",
         mdp: "required"
     },
     messages: {
         id: "Veuillez entrer votre nom d'utilisateur",
         mdp: "Veuillez entrer votre mot de passe"
     }

 })
  });

  </script>
    <title>Gestion de Note Emiage : Page d'accueil</title>
</head>
    <body>
        	<div id="banniere">
            	<img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>
 
        <div id="corps" >
         <div>
               <img src="images/globe.png" alt="globe" id="globe"/>
               </div>
            <h1> Accès</h1>	
        	<form method="post" action="auth.php" id="auth">
            <label for="id"> Nom d'utilisateur</label>
            	<input type="text" name="id"/>
            <label for="mdp"> Mot de passe</label>
                <input type="password" name="mdp"/>
                <input type="submit" value="Entrer"/>    
            </form>
            <div style="text-align: center">
            <a href="mdp-reinitialisation.php">Mot de passe perdu ?</a>
            </div>
            <br />   	
        </div>
        <div id="pied">
            <img src="images/pied_page.jpg" alt="pied de page"/>
        </div>  
    </body>
</html>
