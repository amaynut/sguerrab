<?php
            session_start();
           if($_SESSION['type']!='cf' AND $_SESSION['type']!='rp' AND $_SESSION['type']!='correcteur')
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
 $("#notesExamen").validate({
     rules: {
         examen: "required"
     },
     messages: {
         examen: "Veuillez sélectionner un examen"
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
               <strong>Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?></strong>
               </div>

                 <h1>Gérer les notes d'un examen existant</h1>
                 
                <form action="notes-examen.php" method="post" id="notesExamen">
                    <label>Choisir un examen: <span>*</span></label>
                    <pre>Le nom de l'examen est sous format: <strong>E-MODULE-SESSION</strong>. Les examens sont classés du plus récent au plus ancien</pre>
                    <select name="examen">
                            <option></option>
                            <!--   si l'utilisateur est un RP ou CF, afficher tous les examens disponibles  -->
                            <?php
                            if($_SESSION['type']=='cf' OR $_SESSION['type']=='rp' )
                                {
                                    $recherche_examens = mysql_query("SELECT * FROM examens ORDER BY `date` DESC ;") OR die (mysql_error());
                                    $nombre_examens = mysql_num_rows($recherche_examens);
                                    for($i=0; $i<$nombre_examens; $i++)
                                    {
                                        $id_examen = mysql_result($recherche_examens, $i, 'idEXAMEN');
                                     echo "<option value='$id_examen'>$id_examen</option>";
                                    }
                                }
                            else // si l'utilisateur est un correcteur d'examens,
                            //on affiche juste la liste d'examens dont il est correcteur
                                {
                                    $recherche_examens = mysql_query("SELECT * FROM examens WHERE correcteur='$_SESSION[id]' ORDER BY `date` DESC ;") OR die (mysql_error());
                                    $nombre_examens = mysql_num_rows($recherche_examens);
                                    for($i=0; $i<$nombre_examens; $i++)
                                    {
                                        $id_examen = mysql_result($recherche_examens, $i, 'idEXAMEN');
                                     echo "<option value='$id_examen'>$id_examen</option>";
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
                