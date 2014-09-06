<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="images/favicon32.ico"/>
    <link rel="stylesheet" type="text/css" href="styles/style.css" id="bleu" />
    <title>Gestion de Note Emiage : Réinitialisation du mot de passe</title>
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

         id: "required",
         mail:{
                required: true,
                email: true
         }
     },
     messages: {

         id: "Veuillez entrer votre identifiant",
         mail:{
                required: "Veuillez entrer votre adresse email",
                email: "Veuillez saisir une adresse email valide"
         }
     }

 })
  });

  </script>
</head>

    <body>
    
        	<div id="banniere">
            	<img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>
 
        <div id="corps">
         <div>
               <img src="images/globe.png" alt="globe" id="globe"/>
               </div>
            <h1>Réinitialiser mon mot de passe perdu</h1>
            <form method="post" action="mdp-reinitialise.php" id="formulaire_mdp">
                 <label for="id">Nom d'utilisateur</label>
                    <input type="text" name="id" id="id"/>
                <label for="mail">Email</label>
                    <input type="text" name="mail" id="mail"/>
                    <input type="submit" value="Envoyer"/>
            </form>
              
            <br />
            <a href="index.php" style="display:block; text-align: center">Retour à la page d'identification</a>
        </div>
        <div id="pied">
             <img src="images/pied_page.jpg" alt="pied de page"/>
        </div>  
    </body>
</html>
