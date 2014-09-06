<?php
            session_start();
            //  si l'utilisateur n'est un CF, le renvoyer vers la page d'accès
           if($_SESSION['type']!='cf' AND $_SESSION['type']!='rp')
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
<link href="styleMaxChart/style.css" rel="stylesheet" type="text/css" />
<script type ="text/javascript" src="js/jquery-1.4.2.js"></script>
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Gestion de Notes e-Miage: Statistiques des notes</title>
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
</style>
<script type="text/javascript">
 $(document).ready(function(){
    // validation du formulaire d'affichage
 $("#stats").validate({
     rules: {
		 session: "required",
                 formation: "required"

     },
     messages: {
		 session: "Veuillez sélectionner une session",
                 formation: "Veuillez choisir une formation"
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
                <?php include "menus/menu.php"?>              
                <h1> Statistiques Des Moyennes </h1>
                <form method="get" action="<?php echo $_SERVER[PHP_SELF]?>" id="stats">
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
                     <!-- choisir une formation -->
                    <label>Choisir une formation <span>*</span></label>
                    <select name="formation" id="formation">
                            <option></option>
                            <?php
                            $recherche_formations = mysql_query("SELECT idFORMATION FROM formations;") OR die (mysql_error());
                            $nombre_formations = mysql_num_rows($recherche_formations);
                            for($i=0; $i<$nombre_formations; $i++)
                            {
                                $id_formation = mysql_result($recherche_formations, $i, 'idFORMATION');
                             echo "<option value='$id_formation'>$id_formation</option>";
                            }
                            ?>
                     </select>
                    <input type="submit" value="valider"/>
                </form>
                <?php
                    if(isset($_GET['session']) OR isset($_GET['formation']) OR !is_null($_GET['session'])OR !is_null($_GET['formation']))
                    {
                        // afficher le tableau des statistiques
                        ?>
                <h3 style="text-align:center">Session: <?php echo $_GET['session'] ?></h3>
                <h2>Graphe des moyennes dans tous les modules</h2>
                 <?php // graphe des moyennnes
                   require_once('maxChart.class.php');
                   //ici on utilise une classe php qui s'appelle MaxChart
                   // cette classe est disponible sur http://www.phpf1.com/product/php-chart-script.html

                     // statistiques des notes
         // notes entre 0 et 6 (notes éliminatoires)
         $recherche_note0_6  = mysql_query ("SELECT moyennes.moyenne FROM moyennes, etudiants WHERE moyennes.moyenne < 6 AND moyennes.moyenne >=0 AND
                 moyennes.semestre='$_GET[session]' AND etudiants.formation='$_GET[formation]' AND moyennes.etudiant = etudiants.idETUDIANT") OR die(mysql_error());
         $resultat_notes0_6 = mysql_num_rows($recherche_note0_6);
         $notes['entre 0 et 6 (notes éliminatoires)'] = $resultat_notes0_6;
         // notes entre 6 et 10 (peut garder sa note)
          $recherche_note6_10  = mysql_query ("SELECT moyennes.moyenne FROM moyennes, etudiants WHERE moyennes.moyenne < 10 AND moyennes.moyenne >=6 AND
                 moyennes.semestre='$_GET[session]' AND etudiants.formation='$_GET[formation]' AND moyennes.etudiant = etudiants.idETUDIANT") OR die(mysql_error());
         $resultat_notes6_10 = mysql_num_rows($recherche_note6_10);
         $notes['entre 6 et 10 (peuvent garder leur note)'] = $resultat_notes6_10;
         // notes entre 10 et 15
          $recherche_note10_15  = mysql_query ("SELECT moyennes.moyenne FROM moyennes, etudiants WHERE moyennes.moyenne < 15 AND moyennes.moyenne >=10 AND
                 moyennes.semestre='$_GET[session]' AND etudiants.formation='$_GET[formation]' AND moyennes.etudiant = etudiants.idETUDIANT") OR die(mysql_error());
         $resultat_notes10_15 = mysql_num_rows($recherche_note10_15);
         $notes['entre 10 et 15 (modules validés)'] = $resultat_notes10_15;
         // notes entre 15 et 20
          $recherche_note15_20  = mysql_query ("SELECT moyennes.moyenne FROM moyennes, etudiants WHERE moyennes.moyenne <= 20 AND moyennes.moyenne >=15 AND
                 moyennes.semestre='$_GET[session]' AND etudiants.formation='$_GET[formation]' AND moyennes.etudiant = etudiants.idETUDIANT") OR die(mysql_error());
         $resultat_notes15_20 = mysql_num_rows($recherche_note15_20);
         $notes['entre 15 et 20 (modules validés)'] = $resultat_notes15_20;

            $mc = new maxChart($notes);
            $mc->displayChart('Statistiques des notes - 1',1,500,250);
            echo "<br/><br/>"; ?>
                <h2>Graphe des meilleures moyennes pour chaque module</h2>
               
                    <?php
                        // liste des modules
                     $recherche_modules= mysql_query("SELECT module FROM modulesformation WHERE formation='$_GET[formation]';
                            ")  OR die(mysql_error());
                    while($modules = mysql_fetch_array($recherche_modules))
                    {
                        $module = $modules['module'];
                        // rechercher la meilleure moyenne
                      $recherche_meilleure_moyenne = mysql_query("SELECT MAX(moyennes.moyenne) AS meilleure_moyenne FROM moyennes, etudiants
                              WHERE moyennes.module='$module' AND moyennes.semestre='$_GET[session]'
                              AND etudiants.formation='$_GET[formation]'
                              AND moyennes.etudiant = etudiants.idETUDIANT")OR die(mysql_error());
                      $meilleure_moyenne = mysql_result($recherche_meilleure_moyenne, 0, 'meilleure_moyenne');
                      $meilleure_moyenne_arrondie = round($meilleure_moyenne, 2); // arroundir à deux chiffres après la virgule
                      $data[$module] = $meilleure_moyenne_arrondie; 
                    }
                    
                    $mc = new maxChart($data);
                    $mc->displayChart('Statistiques des notes - 1',1,900,250);
                    ?>
                <h2>Graphe des notes les plus basses pour chaque module</h2>

                    <?php
                        // liste des modules
                     $recherche_modules= mysql_query("SELECT module FROM modulesformation WHERE formation='$_GET[formation]';
                            ") OR die(mysql_error());
                    while($modules = mysql_fetch_array($recherche_modules))
                    {
                        $module = $modules['module'];
                        // rechercher la meilleure moyenne
                      $recherche_mauvaise_note =  mysql_query("SELECT MIN(moyennes.moyenne) AS mauvaise_note FROM moyennes, etudiants
                              WHERE moyennes.module='$module' AND moyennes.semestre='$_GET[session]'
                              AND etudiants.formation='$_GET[formation]'
                              AND moyennes.etudiant = etudiants.idETUDIANT")OR die(mysql_error());
                      $mauvaise_note = mysql_result($recherche_mauvaise_note, 0, 'mauvaise_note');
                      $mauvaise_note_arrondie = round($mauvaise_note, 2); // arroundir à deux chiffres après la virgule
                      $notebasse[$module] = $mauvaise_note_arrondie;
                    }
                    
                    $mc = new maxChart($notebasse);
                    $mc->displayChart('Statistiques des notes - 1',1,900,250);
                    ?>
                <h2>Graphe des notes moyennes des étudiants dans chaque module</h2>

                    <?php
                        // liste des modules
                     $recherche_modules= mysql_query("SELECT module FROM modulesformation WHERE formation='$_GET[formation]';
                            ") OR die(mysql_error());
                    while($modules = mysql_fetch_array($recherche_modules))
                    {
                        $module = $modules['module'];
                        // rechercher la meilleure moyenne
                      $recherche_note_moyenne =  mysql_query("SELECT AVG(moyennes.moyenne) AS note_moyenne FROM moyennes, etudiants
                              WHERE moyennes.module='$module' AND moyennes.semestre='$_GET[session]'
                              AND etudiants.formation='$_GET[formation]'
                              AND moyennes.etudiant = etudiants.idETUDIANT")OR die(mysql_error());
                      $note_moyenne = mysql_result($recherche_note_moyenne, 0, 'note_moyenne');
                      $note_moyenne_arrondie = round($note_moyenne, 2); // arroundir à deux chiffres après la virgule
                      $notemoyenne[$module] = $note_moyenne_arrondie;
                    }

                    $mc = new maxChart($notemoyenne);
                    $mc->displayChart('Statistiques des notes - 1',1,900,250);
                    ?>
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
