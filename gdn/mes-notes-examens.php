<?php
            session_start();
            // si aucune session n'est ouverte, renvoyer l'utilisateur sur la page d'acc�s
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
<!-- liens pour la validation du formulaire c�t� client     -->
<script type="text/javascript" src="js/jquery-validate.js"></script>
<style type="text/css">
    * { font-family: Verdana; font-size: 96%; }
    label { display: block}
    label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
    .submit { margin-left: 12em; }
    em { font-weight: bold; padding-right: 1em; vertical-align: top; }
    span {color: red;}
</style>
<script type="text/javascript">

 $(document).ready(function(){

    // validation du formulaire d'affichage
 $("#notesExamens").validate({
     rules: {
		 session: "required"
     },
     messages: {
		 session: "Veuillez s�lectionner une session"
     }

 })
  });

  </script>
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Gestion de Notes e-Miage: interface ETUDIANTS</title>
</head>

<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="banni�re du site"/>
            </div>

            <div id="corps">
                       <?php include "menus/menu-etudiant.php"?>
                <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>       
                
                
                <?php
				//affichage de la photo par d�faut ou de celle upload�e	
				$requeteCheminphoto = mysql_query ("SELECT photo FROM etudiants WHERE idETUDIANT = '$id'");
        			$resultatCheminphoto = mysql_fetch_array($requeteCheminphoto);
																
				echo '<img src="'.$resultatCheminphoto['photo'].'" alt="photo-utilisateur"/>';
								
				?>
                </div>       
                <h1> Mes notes d'examens </h1>
                <form method="post" action='<?php echo "$_SERVER[PHP_SELF]"?>' id="notesExamens">
                    <label for="session">choisir une session: <span>*</span></label>
                    <select name="session" id="session">
                    <option></option>
                    <?php
                        $recherche_sessions = mysql_query("SELECT DISTINCT session FROM examens ORDER BY `date` DESC ;")
                        OR die (mysql_error());
                        $nombre_sessions = mysql_num_rows($recherche_sessions);
                        for($i=0; $i<$nombre_sessions; $i++)
                        {
                            $session = mysql_result($recherche_sessions, $i, 'session');
                         echo "<option value='$session'>$session</option>";
                        }
                    ?>
                     </select>
                    <input type="submit"/>
                </form>
               <!--    Script d'affichage des notes d'examen     -->
               <?php
                    if(isset($_POST['session']) AND !is_null($_POST['session']))
                    {  ?>
                         <h2> Session: <?php echo $_POST['session']?></h2>
                            <table>
                                <tr><th>Module</th><th>Notes</th></tr>
                                <?php
                       // rechercher les modules
                    $recherche= mysql_query("SELECT examens.module, notesexamens.note FROM examens, notesexamens
                        WHERE notesexamens.etudiant='$_SESSION[id]'
                        AND examens.session = '$_POST[session]'
                        AND examens.idEXAMEN = notesexamens.examen;
                            ") OR die(mysql_error());
                    $nbr_resultat = mysql_num_rows($recherche);
                    for($i=0; $i<$nbr_resultat; $i++)
                    {
                        $module = mysql_result($recherche,$i, 'module');
                        $note = mysql_result($recherche,$i, 'note');
                        echo "<tr><td>$module</td><td>$note</td></tr>";
                    }
                    ?>


                            </table>
                         <br />

               <?php

               }
               ?>
                
            </div>
            <div id="pied">
                 <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>

