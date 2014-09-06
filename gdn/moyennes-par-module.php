<?php
            session_start();
            //  si l'utilisateur n'est un CF, le renvoyer vers la page d'accès
           if($_SESSION['type']!='cf' AND $_SESSION['type']!='rp' AND $_SESSION['type']!='correcteur')
            {
                header( "Location:index.php" );
            }
            $id = $_SESSION['id'];
			$type = $_SESSION['type'];
			require_once 'connexion_bdd.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="styles/style.css" id="bleu" />
<script type ="text/javascript" src="js/jquery-1.4.2.js"></script>
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Gestion de Notes e-Miage: Résultats par module</title>
<!-- liens pour la validation du formulaire côté client     -->
<script type="text/javascript" src="js/jquery-validate.js"></script>
<style type="text/css">
    * { font-family: Verdana; font-size: 96%; }
    label { display: block}
    label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
    .submit { margin-left: 12em; }
    em { font-weight: bold; padding-right: 1em; vertical-align: top; }
    span {color: red;}
    tr:nth-child(odd) {background-color: #D4D4D4;}
    h3 {text-align: center;}
</style>
<script type="text/javascript">
 $(document).ready(function(){
    // validation du formulaire d'affichage
 $("#moyennesModule").validate({
     rules: {
		 session: "required",
                 module: "required",
                 formation: "required"

     },
     messages: {
		 session: "Veuillez sélectionner une session",
                 module: "Veuillez sélectionner un module",
                 formation: "Veuillez sélectionner une formation"
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
                <?php include "menus/menu-cf.php"?>
               <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>
                </div>

                <h1> Moyennes d'un module </h1>
                <form method="get" action="<?php echo $_SERVER[PHP_SELF]?>" id="moyennesModule">
                     <!-- choisir une session -->
                    <label>Choisir une session <span>*</span></label>
                    <select name="session" id="session">
                            <option></option>
                            <?php
                            $recherche_sessions = mysql_query("SELECT DISTINCT session FROM examens ORDER BY `date` DESC ;") OR die (mysql_error());
                            $nombre_sessions = mysql_num_rows($recherche_sessions);
                            for($i=0; $i<$nombre_sessions; $i++)
                            {
                                $id_session = mysql_result($recherche_sessions, $i, 'session');
                             echo "<option value='$id_session'>$id_session</option>";
                            }
                            ?>
                     </select>
                    <label>Choisir un module <span>*</span></label>
                    <select name="module" id="module">
                            <option></option>
                            <?php
                            // rechercher les modules 
                            $recherche_modules= mysql_query("SELECT idMODULE FROM modules;
                            ") OR die(mysql_error());
                    while($modules = mysql_fetch_array($recherche_modules))
                    {
                        $module = $modules['idMODULE'];
                        echo "<option value='$module'>$module</option>";
                    }
                            ?>
                     </select>
                    <label>Choisir une formation <span>*</span></label>
                    <select name="formation">
                            <option></option>
                            <?php
                            $recherche_formations = mysql_query("SELECT * FROM formations ORDER BY `idFORMATION` ASC ;") OR die (mysql_error());
                            $nombre_formations = mysql_num_rows($recherche_formations);
                            for($i=0; $i<$nombre_formations; $i++)
                            {
                                $id_formation = mysql_result($recherche_formations, $i, 'idFORMATION');
                             echo "<option value='$id_formation'>$id_formation</option>";                             }
                            ?>
                        </select>

                    <input type="submit" value="valider"/>
                </form>
                     <?php
                    if(isset($_GET['session'])AND !is_null($_GET['session']) AND isset($_GET['module'])AND !is_null($_GET['module']) AND isset($_GET['formation'])AND !is_null($_GET['formation']))
                    {
                        // afficher le tableau des moyennes du module
                        ?>
                 <h3>Formation: <?php echo $_GET['formation'] ?></h3>
                 <h3>Session: <?php echo $_GET['session'] ?></h3>
                  <h3>Module: <?php echo $_GET['module'] ?></h3>
                  <table>
                      <tr><th>Etudiant</th><th>Moyenne</th></tr>
                      <?php
                      $module = $_GET['module'];
                      $formation = $_GET['formation'];
                      // rechercher la liste des étudiants qui ont une moyenne dans le module sélectionné
                      $recherche_etudiants = mysql_query("SELECT CONCAT(etudiants.nom, ' ', etudiants.prenom) AS etudiant, etudiants.idETUDIANT AS id
                          FROM etudiants WHERE formation='$formation'; ") OR die(mysql_error());
                      while($etudiants= mysql_fetch_array($recherche_etudiants))
                      {
                            $etudiant = $etudiants['etudiant'];
                            $idETUDIANT = $etudiants['id'];
                            // rechercher la note
                            $recherche_note = mysql_query("SELECT moyenne FROM moyennes 
                                    WHERE etudiant='$idETUDIANT'
                                    AND module='$module'
                                    AND semestre = '$_GET[session]'
                                    ;") OR die (mysql_error()) ;
                            $nbr_ligne = mysql_num_rows( $recherche_note);

                            if($nbr_ligne == 1)  // si une note est trouvée pour l'étudiant sélectionné
                            {
                            $note = mysql_result($recherche_note, 0, 'moyenne');
                            $note_arrondie = round($note, 2);

                          echo "<tr><td>$etudiant</td><td>$note_arrondie</td></tr>";
                            }
                      }
                      
                      ?>

                  </table>

                <?php
                    }

                ?>
                <br />
            </div>
            <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>

