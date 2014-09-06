<?php
            session_start();
             if(empty($_SESSION))
            {
                header( "Location:index.php" );
            }
            require 'connexion_bdd.php';
            $id = $_SESSION['id'];
	$type = $_SESSION['type'];		
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" href="styles/style.css" id="bleu" />
	<script type ="text/javascript" src="js/jquery-1.4.2.js"></script>
	<link rel="shortcut icon" href="images/favicon32.ico"/>
	<title>Gestion de Notes e-Miage : Changer son image perso</title>
        <style type="text/css">
            
          ol {
              margin-left: 25%;
              margin-right: 25%;
              width:50%;
              font-size: small
                      }
          ol > li {color: white;}
          ul > li, span{color:black;}
        </style>
</head>

<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="banniÃ¨re du site"/>
            </div>
            
            <div id="corps">
                <?php 
		include "menus/menu-$type.php" // le menu selon le type d'utilisateur
		?>
                
               	<h3>Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne																																																					                ?></h3>
          
                <h1>Changement d'image perso</h1>
                
	<form enctype="multipart/form-data" action="verif_photo.php" method="POST">          
            <input type="hidden" name="posted" value="1" />
            <input name="fichier" type="file" />
            <input type="submit" value="Uploader" />
        </form>
                
                    <ol>                    
                        <li>Extensions autorisées de l'image:
                            <ul>
                                <li>jpg</li>
                                <li>png</li>
                                <li>gif</li>
                            </ul>
                        </li>
                        <li>Dimensions maximales:
                            <ul>
                                <li>Largeur maximale: 92 pixels </li>
                                <li>Heuteur maximale: 102 pixels</li>
                            </ul>
                        </li>
                        <li>Poid maximal: <span>100 KB</span> </li>
                    </ol>
                </div>
           
           
            <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>