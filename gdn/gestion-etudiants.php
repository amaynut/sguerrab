<?php
            session_start();
             // si l'utilisateur n'est un CF, le renvoyer vers la page d'accès
            if($_SESSION['type']!='cf')
            {
                header( "Location:index.php" );
            }
            require_once 'connexion_bdd.php';
            $id = $_SESSION['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="styles/style.css" id="bleu" />
<link rel="shortcut icon" href="images/favicon32.ico"/>
<!-- liens pour la validation du formulaire côté client     -->
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/jquery-validate.js"></script>

<script type="text/javascript">

 $(document).ready(function(){
    // validation du formulaire d'affichage des listes d'étudiants
 $("#gestion_etudiants").validate({
     rules: {
         formation: "required"
     },
     messages: {
         formation: "Veuillez sélectionner une formation"
     }
 })
  });

  </script>

<title>Gestion de Notes e-Miage: interface Coordinateur de Formation (CF)</title>
</head>
<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>
            <div id="corps">
                 <?php include "menus/menu-cf.php"?>               
               <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>            
                </div>
                
                <h1> Gestion des étudiants </h1>
                <form method="post" action="etudiants-par-formation.php" id="gestion_etudiants">
                    <h2>Sélectionner une formation</h2>
                    <select name="formation" id="formation">
                        <option value=''></option>
                        <?php
                        $recherche_formation = mysql_query("SELECT idFORMATION, nom FROM formations WHERE cf='$_SESSION[id]';");
                        $nombre_formations = mysql_num_rows($recherche_formation);
                        for($i=0; $i<$nombre_formations; $i++)
                        {                           
                            $id_formation = mysql_result($recherche_formation, $i, 'idFORMATION');
                            $nom_formation = mysql_result($recherche_formation, $i, 'nom');
                         echo "<option value='$id_formation'>$nom_formation</option>";  
                        }
                        ?>
                    </select> <br/><br/>                    
                    <input type="submit" value="Valider"/>
                </form>              
                <br />
            </div>
            <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>
