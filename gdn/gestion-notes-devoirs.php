<?php
            session_start();
            if($_SESSION['type']!='cf' AND $_SESSION['type']!='rp' AND $_SESSION['type']!='tuteur')
            {
                header( "Location:index.php" );
            }
            require_once 'connexion_bdd.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="styles/style.css" id="bleu" />
<!-- liens pour la validation du formulaire côté client     -->
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/jquery-validate.js"></script>

<script type="text/javascript">

 $(document).ready(function(){

    // validation du formulaire d'affichage de notes'
 $("#notesDevoir").validate({
     rules: {
         devoir: "required"
     },
     messages: {
         devoir: "Veuillez sélectionner un devoir"
     }
 })
  });

  </script>
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Gestion de Notes e-Miage: interface Coordinateur de Formation (CF)</title>
</head>
<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>
            <div id="corps">
               <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
               
               <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>
				</div>
                
                 <h1>Gérer les notes d'un devoir existant</h1>
                <form action="notes-devoir.php" method="post" id="notesdevoir">
                    <label>Choisir un devoir: <span>*</span></label>
                    <pre>Le nom du devoir est sous format: <strong>DN°-MODULE-SESSION</strong>. Les devoirs sont classés du plus récent au plus ancien</pre>
                    <select name="devoir">
                            <option></option>
                             <!--   si l'utilisateur est un RP ou CF, afficher tous les devoirs disponibles  -->
                            <?php
                            if($_SESSION['type']=='cf' OR $_SESSION['type']=='rp' )
                              {
                                $recherche_devoirs = mysql_query("SELECT * FROM devoirs ORDER BY `date` DESC ;") OR die (mysql_error());
                                $nombre_devoirs = mysql_num_rows($recherche_devoirs);
                                for($i=0; $i<$nombre_devoirs; $i++)
                                {
                                    $id_devoir = mysql_result($recherche_devoirs, $i, 'iddevoir');
                                 echo "<option value='$id_devoir'>$id_devoir</option>";
                                }
                              }
                              else // si il s'agit d'un tuteur, afficher seulement les devoirs qu'il corrige
                              {
                                $recherche_devoirs = mysql_query("SELECT * FROM devoirs WHERE tuteur='$_SESSION[id]' ORDER BY `date` DESC ;") OR die (mysql_error());
                                $nombre_devoirs = mysql_num_rows($recherche_devoirs);
                                for($i=0; $i<$nombre_devoirs; $i++)
                                {
                                    $id_devoir = mysql_result($recherche_devoirs, $i, 'iddevoir');
                                 echo "<option value='$id_devoir'>$id_devoir</option>";
                                }
                              }
                            ?>
                        </select>
                    <input type="submit" name="envoie" value="valider" />
                </form>
            </div>
<div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>
