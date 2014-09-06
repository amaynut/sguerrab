<?php
            session_start();
            // si aucune session n'est ouverte, renvoyer l'utilisateur sur la page d'accï¿½s
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
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Gestion de Notes e-Miage: interface ETUDIANTS</title>
</head>

<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="banniï¿½re du site"/>
            </div>

            <div id="corps">
                       <?php include "menus/menu-etudiant.php"?>
                <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>       
                
                
                <?php
				//affichage de la photo par dï¿½faut ou de celle uploadï¿½e	
				$requeteCheminphoto = mysql_query ("SELECT photo FROM etudiants WHERE idETUDIANT = '$id'");
        			$resultatCheminphoto = mysql_fetch_array($requeteCheminphoto);
																
				echo '<img src="'.$resultatCheminphoto['photo'].'" alt="photo-utilisateur"/>';
								
				?>
                </div>       
                <h1> Fiche Perso </h1>
                <?php   
                    // rechercher les coordonnï¿½es de l'ï¿½tudiant
                    $requeteCoordonnees= mysql_query("SELECT * FROM `etudiants` WHERE `idETUDIANT`= \"$id\"");
                    $resultatCoordonnees = mysql_fetch_assoc($requeteCoordonnees);
                    // chercher le nom de sa formation
                    $requeteFormation = mysql_query("SELECT nom FROM `formations` WHERE idFORMATION =\"$resultatCoordonnees[formation]\"");
                    $resultatFormation= mysql_fetch_assoc($requeteFormation);
                    //  chercher le nom de son parcours
                    $requeteParcours = mysql_query("SELECT nom FROM `parcours` WHERE idPARCOURS =\"$resultatCoordonnees[parcours]\"");
                    $resultatParcours= mysql_fetch_assoc($requeteParcours);
                    // chercher le nom de son centre
                     $requeteCentre = mysql_query("SELECT nom FROM `centres` WHERE idCENTRE =\"$resultatCoordonnees[centre]\"");
                    $resultatCentre= mysql_fetch_assoc($requeteCentre);

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
                    <tr><th> Formation</th> <td> <?php echo $resultatFormation['nom'];?></td></tr>
                    <tr><th> Parcours</th> <td> <?php echo $resultatParcours['nom'];?></td></tr>
                    <tr><th>Centre  </th> <td> <?php echo $resultatCentre['nom'];?></td></tr>
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
