<?php
            session_start();
            // si aucune session n'est ouverte, renvoyer l'utilisateur sur la page d'accès
            if(empty($_SESSION))
            {
                header( "Location:index.php" );
            }
            $id = $_SESSION['id'];
            require_once 'connexion_bdd.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="styles/style.css" id="bleu" />
<link rel="shortcut icon" href="images/favicon32.ico"/>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<title>Gestion de Notes e-Miage: interface Correcteurs d'examens</title>
</head>

<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>
            <div id="corps">
              <?php include "menus/menu-correcteur.php"?>
                 <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>       
                
                
                <?php
				//affichage de la photo par défaut ou de celle uploadée		
				$requeteCheminphoto = mysql_query ("SELECT photo FROM correcteurs WHERE idCORRECTEUR = '$id'");
				$resultatCheminphoto = mysql_fetch_array($requeteCheminphoto);
																
				echo '<img src="'.$resultatCheminphoto['photo'].'" alt="photo-utilisateur"/>';
								
				?>
                </div>       
                
                
                <h1> Fiche Perso </h1>
                 <?php
                    // rechercher les coordonnées du correcteur
                    $requeteCoordonnees= mysql_query("SELECT * FROM `correcteurs` WHERE `idCORRECTEUR`= \"$id\"");
                    $resultatCoordonnees = mysql_fetch_assoc($requeteCoordonnees);
			?>
                <table>
                    <tr><th>Nom </th> <td><?php echo $resultatCoordonnees['nom'];?></td></tr>
                    <tr><th> Prénom</th> <td> <?php echo $resultatCoordonnees['prenom'];?></td></tr>
                    <tr><th> Rue</th> <td> <?php echo $resultatCoordonnees['adresse'];?></td></tr>
                    <tr><th>Code Postal </th> <td> <?php echo $resultatCoordonnees['codePostal'];?></td></tr>
                    <tr><th> Ville</th> <td><?php echo $resultatCoordonnees['ville'];?> </td></tr>
                    <tr><th> Pays</th> <td><?php echo $resultatCoordonnees['pays'];?> </td></tr>
                    <tr><th> email</th> <td><?php echo $resultatCoordonnees['mail'];?> </td></tr>
                    <tr><th> Téléphone</th> <td><?php echo $resultatCoordonnees['phone'];?> </td></tr>
                </table>
                <p></p>
                <div id="bouton_modif">
                    <button type="button" onclick="self.location.href='editer-fiche.php'">Modifier ma fiche</button>
                    <button type="button" onclick="self.location.href='editer-mdp.php'">Modifier mon mot de passe</button>
                    <button type="button" onclick="self.location.href='upload-photo.php'">Modifier mon image perso</button>
                </div>
                <br />
            </div>
            <div id="pied">
                 <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>
