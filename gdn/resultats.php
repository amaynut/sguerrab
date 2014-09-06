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
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<!-- liens pour la validation du formulaire côté client     -->
<script type="text/javascript" src="js/jquery-validate.js"></script>
<style type="text/css">
    * { font-family: Verdana; font-size: 96%; }
    label { display: block}
    label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
    .submit { margin-left: 12em; }
    em { font-weight: bold; padding-right: 1em; vertical-align: top; }
    span {color: red;}
    pre {font-size: 80%;
    
    }
    table {background:#D4D4D4;}
    table#notes td , table#notes tr>th {font-size:7px}
    td.vert {background-color: #1AFF76;}  /*     Le vert pour le module validé*/
    td.orange {background-color: #FFC90E;}   /*      l'orange pour le module où il peut concerver sa note '*/
    td.rouge {background-color: #FF4248;}  /*  Le rouge pour la note éliminatoire*/
</style>
<!--[if IE ]>
  <style type="text/css">
            table#notes td , table#notes tr>th {font-size:11px}
</style>
<![endif]-->
<script type="text/javascript">

 $(document).ready(function(){

    // validation du formulaire d'affichage
 $("#resultats").validate({
     rules: {
		 session: "required",
                 formation: "required"
     },
     messages: {
		 session: "Veuillez sélectionner une session",
                 formation: "Veuillez sélectionner une formation"
     }

 })
  });

  </script>
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Résultats</title>
</head>

<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>

            <div id="corps">
                       <?php include "menus/menu.php"?>
                <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>
                </div>
                <h1> Les Résultats de la Formation </h1>
                <form method="get" action='<?php echo "$_SERVER[PHP_SELF]?session=$_GET[session]&formation=$_GET[formation]"?>' id="resultats">
                    <!-- choisir une session -->
                    <label>Choisir une session <span>*</span></label>
                    <select name="session" id="session">
                            <option></option>
                            <?php
                            $recherche_sessions = mysql_query("SELECT DISTINCT session FROM examens ORDER BY `idEXAMEN` ASC ;") OR die (mysql_error());
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

                <!-- afficher les résultats si une session est sélectionnée  ************** -->
                <?php
                    if(isset($_GET['session']) AND isset($_GET['formation']) AND !is_null($_GET['session'])AND !is_null($_GET['formation']))
                    { ?>
                <h2>Session: <?php echo "$_GET[session]"?></h2>
                <h2>Formation: <?php echo "$_GET[formation]"?></h2>
                <!--  Légende du tableau-->
                <p style="text-align:center">Légende du tableau des résultats</p>
                <table>
                    <tr><td class="rouge">DNR</td><td>L'étudiant a passé l'examen, mais n'a pas rendu tous les devoirs</td></tr>
                    <tr><td class="rouge">NA</td><td>Note Annulée, à cause d'une ou de plusieures notes de devoirs inférieures à 5</td></tr>
                    <tr><td class="vert">&nbsp;</td><td>Le module est validé</td></tr>
                    <tr><td class="orange">&nbsp;</td><td>L'étudiant peut conserver sa note</td></tr>
                    <tr><td class="rouge">&nbsp;</td><td>C'est une note éliminatoire</td></tr>

                </table>
                <br />
                  <!-- tableau des résultats de la session pour la formation sélectionnée -->    
                  <table id="notes">
                <?php
                    // rechercher les modules de la formation
                    $recherche_modules= mysql_query("SELECT module FROM modulesformation WHERE formation='$_GET[formation]';
                            ") OR die(mysql_error());
                    // affichage de la liste des modules en horizontal
                    echo "<tr><td>Etudiants\Modules</td>";
                    while($modules = mysql_fetch_array($recherche_modules)) 
                    {
                        echo "<th>$modules[module]</th>";
                    }
                    echo "</tr>";

                    // recherche des étudiants inscrits dans la formation
                    $recherche_etudiants = mysql_query("SELECT CONCAT(nom , ' ' , prenom ) AS etudiant, idETUDIANT
                            FROM etudiants WHERE formation='$_GET[formation]'") OR die (mysql_error());
  
                    while($etudiants = mysql_fetch_array($recherche_etudiants))
                    {
                        echo "<tr><th>$etudiants[etudiant]</th>"; // afficher le nom de chaque étudiant sur une ligne horizontal

                        // rechercher à nouveau les modules
                         $recherche_modules= mysql_query("SELECT module FROM modulesformation WHERE formation='$_GET[formation]';
                            ") OR die(mysql_error());
                        // afficher les moyennes de chaque étudiant sur une seule ligne
                        while($modules = mysql_fetch_array($recherche_modules))
                        {
                            // pour chaque module calculer la moyenne et l'affiche dans une case correspondante
                           
                            /*    ***************************************
                             *     Cas de figure pour le calcul des moyennes:
                             * X   I. Le module ne possède pas de devoirs (Note Module = Note Examen):
                             * X         I.1: Si l'étudiant a passé l'examen afficher sa note
                             * X        I.2: Sinon afficher une case vide
                             * X   II. Le module ne possède pas d'examen (cas des modules qui un ou plusieurs projet à réaliser au cours du semestre)
                             * X         II.1: si l'étudiant à rendu tous ses devoirs (note module = moyenne des devoirs)
                             * X         II. 2 : Si l'étudiant n'a pas rendu tous ses devoir (note = 0, donc afficher une case vide)
                             * X   III. Le module possède un examen plus un ou plusieur devoirs
                             * X        III.1: Si l'étudiant n'a pas passé l'examen (note = 0, donc case vide)
                             * X         III.2: Si l'étudiant a passé l'examen:
                             * X                 III.2.a: si l'étudiant n'a pas rendu tous ses devoirs (la note de l'examen est annulée, on affiche la mention DNR
                             *
                             * X                  III.2.b: si l'étudiant a rendu tous ses devoirs:
                             *                         // si l'étudiant à 1 devoir inférieure à 5 dans un module qui moins de 3 devoirs, la note est annulé
                             *                         // si l'étudiant à plus d'une note inférieure à 5 dans un module qui 3 devoirs ou plus, la note est annulé egalement
                             *                            // si tout est bon on vérifie si la note d'examen est superieur à celle des devoirs 
                             * X                       III.2.b.i: Si la note de l'examen est superieur à la moyenne des devoirs
                             * X                       III.2.b.ii: Si la note de l'examen est inférieure à la moyenne des devoirs  (la note module = (Examen * 3 + Moyenne Devoirs )/4
                             * 
                             * ***********************************************
                             */
                            /* Les résultats de chaque session sont stockés dans une table 'moyennes'
                             * pour faciliter le suivi de parcours, les stats et la détection des diplômés */
                            // rechercher les examens de chaque module pour la session
                            $recherche_examen = mysql_query("SELECT idEXAMEN FROM examens
                                WHERE session='$_GET[session]' AND module='$modules[module]'") OR die (mysql_error());
                             $nbr_examens = mysql_num_rows($recherche_examen);
                             // rechercher les devoirs de chaque module pour la session
                             $recherche_devoirs = mysql_query("SELECT idDEVOIR FROM devoirs
                                    WHERE module='$modules[module]' AND session='$_GET[session]';");
                                $nbr_devoirs = mysql_num_rows($recherche_devoirs); // nombre des devoirs du module pour le semestre en cours

                                 // vérifier si l'étudiant à rendu tous ses devoirs
                             $recherche_nbr_devoirs_rendus = mysql_query("
                                  SELECT COUNT(notesdevoirs.note) AS nombre
                                        FROM notesdevoirs, devoirs
                                        WHERE notesdevoirs.devoir = devoirs.idDEVOIR
                                        AND devoirs.module = '$modules[module]'
                                        AND devoirs.session = '$_GET[session]'
                                        AND notesdevoirs.etudiant = '$etudiants[idETUDIANT]';
                                  ") OR die(mysql_error());
                                  // déterminer la note de l'examen si elle existe
                              $recherche_note_examen = mysql_query("SELECT notesexamens.note FROM notesexamens, examens
                                     WHERE  examens.module='$modules[module]'
                                    AND examens.session='$_GET[session]'
                                    AND notesexamens.etudiant='$etudiants[idETUDIANT]'
                                    AND notesexamens.examen = examens.idEXAMEN
                                         ")OR die(mysql_error());
                                 $resultat_note_examen = mysql_fetch_assoc($recherche_note_examen);
                                 
                            $resultat_nbr_devoirs_rendus = mysql_fetch_assoc($recherche_nbr_devoirs_rendus);
                             $nbr_devoirs_rendus = $resultat_nbr_devoirs_rendus['nombre'];
                             
                                // déterminer la moyenne des devoirs si elle existe
                                 $recherche_moyenne_devoirs = mysql_query("
                                        SELECT AVG(notesdevoirs.note) AS moyenne
                                        FROM notesdevoirs, devoirs
                                        WHERE notesdevoirs.devoir = devoirs.idDEVOIR
                                        AND devoirs.module = '$modules[module]'
                                        AND devoirs.session = '$_GET[session]'
                                        AND notesdevoirs.etudiant = '$etudiants[idETUDIANT]';
                                        ")OR die (mysql_error());
                                $resultat_moyenne_devoirs = mysql_fetch_assoc($recherche_moyenne_devoirs);
                                 $moyenneDevoirs = $resultat_moyenne_devoirs['moyenne'];  // retrouver la moyenne
                                 $moyenneDevoirs = round($moyenneDevoirs, 2);  // arroundir la moyenne
   
                             // I. Le module ne possède pas de devoirs (Note Module = Note Examen):
                             if($nbr_devoirs == 0 AND $nbr_examens != 0)
                             {
                                 // dans ce cas seule la note d'examen sera prise en compte                           
                                 $note = $resultat_note_examen['note'] ;                               
                                 if(!is_null($note))  // I.1: Si l'étudiant à passé l'examen afficher sa note
                                 {
                                     // déterminer les couleur des notes
                                    if($note>=10){$couleur='vert';}
                                    elseif($note < 6){$couleur='rouge';}
                                    else {$couleur='orange';}

                                   echo "<td class='$couleur'>$note</td>";
                                    mysql_query("REPLACE INTO moyennes (etudiant, semestre, module, moyenne) VALUES
                                              ('$etudiants[idETUDIANT]', '$_GET[session]', '$modules[module]', '$note')"); // Stocker cette moyenne dans une table mysql
                                 }
                                 else {    //I.2: Sinon afficher une case vide
                                     echo "<td>&nbsp;</td>";
                                 }
                             }
                             ///////////////////////////////////////////////////////////////////////////////////////
                             
                             //  II. Le module ne possède pas d'examen (cas des modules qui un ou plusieurs projet à réaliser au cours du semestre)
                             elseif($nbr_examens == 0 AND $nbr_devoirs!=0) {
                                     if($nbr_devoirs == $nbr_devoirs_rendus) //II.1: si l'étudiants à rendu tous ses devoirs (note module = moyenne des devoirs)
                                     {                                                                                 
                                           $note = $moyenneDevoirs;
                                           // déterminer les couleur des notes
                                    if($note>=10){$couleur='vert';}
                                    elseif($note < 6){$couleur='rouge';}
                                    else {$couleur='orange';}

                                           echo "<td class='$couleur'>$note</td>"; // on affiche la note dans une case correspondante
                                            mysql_query("REPLACE INTO moyennes (etudiant, semestre, module, moyenne) VALUES
                                              ('$etudiants[idETUDIANT]', '$_GET[session]', '$modules[module]', '$note')"); // Stocker cette moyenne dans une table mysql
                                     }
                                     else {  //II. 2 : Si l'étudiant n'a pas rendu tous ses devoir (note = 0, donc afficher une case vide)
                                         // déterminer les couleur des notes
                                    if($note>=10){$couleur='vert';}
                                    elseif($note < 6){$couleur='rouge';}
                                    else {$couleur='orange';}
                                         echo "<td class='$couleur'>$note</td>";
                                          mysql_query("REPLACE INTO moyennes (etudiant, semestre, module, moyenne) VALUES
                                              ('$etudiants[idETUDIANT]', '$_GET[session]', '$modules[module]', '$note')"); // Stocker cette moyenne dans une table mysql
                                     }
                                 
                             }
                             ///////////////////////////////////////////////////////////////////////////////////////////////////
                             elseif($nbr_examens == 0 AND $nbr_devoirs == 0)   // Cas exeptionnel à gérer: si le module n'a ni examen ni devoirs pour la session en question
                             {
                                 echo "<td>&nbsp</td>"; // on affiche une case vide pour tous les étudiant
                             }
                             else { // III. Le module possède un examen plus un ou plusieur devoirs
                                         $noteExamen = $resultat_note_examen['note'];
                                     if(is_null($noteExamen)) //III.1: Si l'étudiant n'a pas passé l'examen (note = 0, donc case vide)
                                     {
                                          echo "<td>&nbsp;</td>";
                                     }

                                     else {   // III.2: Si l'étudiant a passé l'examen:

                                         //  III.2.a: si l'étudiant n'a pas rendu tous ses devoirs (la note de l'examen est annulée, on affiche la mention DNR
                                         if($nbr_devoirs!=$nbr_devoirs_rendus)
                                         {
                                             echo "<td class='rouge'>DNR</td>";
                                         }
                                         //  III.2.b: si l'étudiant a rendu tous ses devoirs:
                                         else
                                         { /////////////////////////////////////////////////////////////////////////////

                                             // vérifier si l'étudiant n'a pas eu de notes < 5 dans les devoirs
                                             $recherche_devoirs_eliminatoires = mysql_query("
                                        SELECT *
                                        FROM notesdevoirs, devoirs
                                        WHERE notesdevoirs.devoir = devoirs.idDEVOIR
                                        AND notesdevoirs.note < 5
                                        AND devoirs.module = '$modules[module]'
                                        AND devoirs.session = '$_GET[session]'
                                        AND notesdevoirs.etudiant = '$etudiants[idETUDIANT]';
                                        ");
                                             $nbr_devoirs_eliminatoires = mysql_num_rows($recherche_devoirs_eliminatoires);
                                             if($nbr_devoirs == 3 AND $nbr_devoirs_eliminatoires > 1)
                                                  {
                                             echo "<td class='rouge'>NA</td>"; // note annulée cause des mauvaises notes de devoirs
                                                    }
                                             elseif($nbr_devoirs < 3 AND $nbr_devoirs_eliminatoires >=1)
                                                  {
                                             echo "<td class='rouge'>NA</td>"; // note annulée cause des mauvaises notes de devoirs
                                                    }
                                             else {
                                               //  III.2.b.i: Si la note de l'examen est superieur à la moyenne des modules
                                             if($noteExamen > $moyenneDevoirs)
                                             {
                                                 $note= $noteExamen; //  c'est à dire, les notes des devoirs ne sont pas prises en compte
                                                 // déterminer les couleur des notes
                                    if($note>=10){$couleur='vert';}
                                    elseif($note < 6){$couleur='rouge';}
                                    else {$couleur='orange';}
                                                 echo "<td class='$couleur'>$note</td>";
                                                  mysql_query("REPLACE INTO moyennes (etudiant, semestre, module, moyenne) VALUES
                                              ('$etudiants[idETUDIANT]', '$_GET[session]', '$modules[module]', '$note')"); // Stocker cette moyenne dans une table mysql
                                             }
                                             //III.2.b.ii: Si la note de l'examen est inférieure à la moyenne des modules  (la note module = (Examen * 3 + Moyenne Devoirs )/4
                                             else {
                                                 $note = ($noteExamen * 3  + $moyenneDevoirs)/4; // calculer la note selon la regle ci-dessus
                                                 $note = round($note, 2); // arrondir la note à deux chiffre après la virgule
                                                 // déterminer les couleur des notes
                                    if($note>=10){$couleur='vert';}
                                    elseif($note < 6){$couleur='rouge';}
                                    else {$couleur='orange';}
                                                 echo "<td class='$couleur'>$note</td>";
                                                  mysql_query("REPLACE INTO moyennes (etudiant, semestre, module, moyenne) VALUES
                                              ('$etudiants[idETUDIANT]', '$_GET[session]', '$modules[module]', '$note')"); // Stocker cette moyenne dans une table mysql
                                             }
                                           }
                                         } //////////////////////////////////////////////////////////////////////////////////
                                     }
                             }
                        }
                        echo "</tr>"; // fin afficher chaque étudiant
                    }
                    ?>

                </table>
                   <?php }
                   //  ************  fin de l'affichage des moyennes des modules  *********************
                ?>

                <br />
            </div>
            <div id="pied">
                 <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>

