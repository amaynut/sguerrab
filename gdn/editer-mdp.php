<?php
            session_start();
            // si aucune session n'est ouverte, renvoyer l'utilisateur sur la page d'accès
            if(empty($_SESSION))
            {
                header( "Location:index.php" );
            }
            $id = $_SESSION['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="images/favicon32.ico"/>
    <link rel="stylesheet" type="text/css" href="styles/style.css" id="bleu" />
    <title>Gestion de Note Emiage : Changement du mot de passe</title>
    
    <!-- liens pour la validation du formulaire côté client     -->
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
 $("#formulaire_mdp").validate({
     rules: {
         ancien_mdp: {
             required: true,
             remote: "verif_mdp.php"
                },
         nouveau_mdp: "required",
         confirme_mdp:{
                required: true,
                equalTo: "#nouveau_mdp"
         }
     },
     messages: {
         ancien_mdp: {
             required: "Veuillez entrer votre ancien mot de passe",
             remote: "Ce n'est pas votre ancien mot de passe"
         },
         nouveau_mdp: "Veuillez entrer votre nouveau mot de passe",
         confirme_mdp:{
                required: "Veuillez confirmer votre nouveau mot de passe",
                equalTo: "La confirmation de votre mot de passe n'est pas bonne"
         }
     }

 })
  });

  </script>
</head>

    <body>
    
        	<div id="banniere">
            	<img src="images/en-tete.jpg" alt="banni?re du site"/>
            </div>
 
        <div id="corps">
             <?php include_once "menus/menu.php"; // inclure un menu selon le type d'utilisateur?>
             <p> Bonjour <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </p>
        
            <h1>Changer mon mot de passe</h1>
            <form action="mdp-edite.php" method="post" id="formulaire_mdp">
                <label for="ancien_mdp">Ancien mot de passe</label>
                    <input type="password" name="ancien_mdp" id="ancien_mdp"/>
                <label for="nouveau_mdp">Nouveau mot de passe</label>
                    <input type="password" name="nouveau_mdp" id="nouveau_mdp"/>
                <label for="confirme_mdp">Confirmation du mot de passe</label>
                    <input type="password" name="confirme_mdp" id="confirme_mdp"/>

                    <input type="submit" value="valider" />

            </form>
            <br />   	
        </div>
        <div id="pied">
             <img src="images/pied_page.jpg" alt="pied de page"/>
        </div>  
    </body>
</html>
