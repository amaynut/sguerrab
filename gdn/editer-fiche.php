<?php
   session_start();
            // si aucune session n'est ouverte, renvoyer l'utilisateur sur la page d'accès
            if(empty($_SESSION))
            {
                header( "Location:index.php" );
            }
            
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="images/favicon32.ico"/>
    <link rel="stylesheet" type="text/css" href="styles/style.css" id="bleu" />
    <script type ="text/javascript" src="js/jquery-1.4.2.js"/>
    <title>Gestion de Note Emiage : Editer ma fiche perso</title>
</head>

    <body>

        	<div id="banniere">
            	<img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>

        <div id="corps">
            <?php include_once "menus/menu.php"; // inclure un menu selon le type d'utilisateur?>
             <p> Bonjour <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </p>
            <h1> Mettre à jour ma fiche perso</h1>
        	<form method="post" action="fiche-editee.php">
                    <fieldset> <legend>Adresse</legend>                          
                        <label for="rue"> Rue</label>
                            <input type="text" name="rue"/>
                        <label for="cp"> Code Postal</label>
                            <input type="text" name="cp"/>
                        <label for="ville"> Ville</label>
                            <input type="text" name="ville"/>
                        <label for="pays"> Pays</label>
                            <input type="text" name="pays"/>
                    </fieldset>
                    <fieldset> <legend>Contact</legend>
                        <label for="mail"> email</label>
                            <input type="text" name="mail"/>
                        <label for="phone"> Téléphone</label>
                            <input type="text" name="phone"/>
                    </fieldset>                
                            <input type="submit" value="Valider"/>
            </form>
            <br />
        </div>
        <div id="pied">
             <img src="images/pied_page.jpg" alt="pied de page"/>
        </div>
    </body>
</html>
