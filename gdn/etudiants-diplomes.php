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
<script type ="text/javascript" src="js/jquery-1.4.2.js"></script>
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Gestion de Notes e-Miage: Liste des diplômés</title>
<style type="text/css">
    tr:nth-child(odd) {background-color: #D4D4D4;}
</style>

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

                <h1> Liste des diplômés </h1>
                <table>
                    <tr><th>Nom</th><th>Formation</th><th>Parcours</th><th>Moyenne Générale</th><th>Mention</th></tr>
                    <?php
                         // liste des étudiants
                       // recherche des étudiants inscrits dans la formation
                    $recherche_etudiants = mysql_query("SELECT CONCAT(nom , ' ' , prenom ) AS etudiant,
                        idETUDIANT, parcours, formation
                            FROM etudiants ORDER BY parcours;") OR die (mysql_error());
                    while($etudiants = mysql_fetch_array($recherche_etudiants))
                    {
                        $nom_etudiant = $etudiants['etudiant'];
                        $id_etudiant = $etudiants['idETUDIANT'];
                        $parcours = $etudiants['parcours'];
                        $formation = $etudiants['formation'];
                       // rechercher le nombre de modules obligatoire qu'il doit valider
                        $recherche_nbr_modules_ob = mysql_query("SELECT module FROM modulesparcours 
                                WHERE statut='obligatoire' AND parcours='$parcours'")OR die(mysql_error());
                        $nbr_modules_ob = mysql_num_rows($recherche_nbr_modules_ob);
                        // rechercher le nombre de modules optionnels et libres qu'il doit valider
                        $recherche_modules_op_et_li = mysql_query("SELECT modulesoptionnels, moduleslibres 
                                FROM parcours WHERE idPARCOURS='$parcours'") OR die(mysql_error());
                        $nbr_modules_op= mysql_result($recherche_modules_op_et_li, 0, 'modulesoptionnels'); // nombre de modules optionnels
                        $nbr_modules_li= mysql_result($recherche_modules_op_et_li, 0, 'moduleslibres'); // nombre de modules libres

                        // recherche le nombre de module obligatoires validés ou il peut garder sa note
                        $recherche_modules_ob_valides = mysql_query("
                            SELECT notes.moyenne AS moyenne_module, notes.module, notes.statut
                            FROM (
                            SELECT moyennes.etudiant, moyennes.module, modulesparcours.statut AS statut, moyennes.semestre, MAX( moyennes.moyenne ) AS moyenne
                            FROM moyennes, modulesparcours
                            WHERE modulesparcours.module = moyennes.module
                            AND modulesparcours.parcours =  '$parcours'
                            AND moyennes.etudiant =  '$id_etudiant'
                            AND modulesparcours.statut =  'obligatoire'
                            AND moyennes.moyenne >= 6
                             GROUP By module
                             ) AS notes    
                                ")OR die(mysql_error());
                        $nbr_modules_ob_valides = mysql_num_rows($recherche_modules_ob_valides);
                        // recherche le nombre de module optionnel validés ou il peut garder sa note (notes >=6)
                         $recherche_modules_op_valides = mysql_query("
                            SELECT notes.moyenne AS moyenne_module, notes.module, notes.statut
                            FROM (
                            SELECT moyennes.etudiant, moyennes.module, modulesparcours.statut AS statut, moyennes.semestre, MAX( moyennes.moyenne ) AS moyenne
                            FROM moyennes, modulesparcours
                            WHERE modulesparcours.module = moyennes.module
                            AND modulesparcours.parcours =  '$parcours'
                            AND moyennes.etudiant =  '$id_etudiant'
                            AND modulesparcours.statut =  'optionnel'
                            AND moyennes.moyenne >= 6
                             GROUP By module
                             ) AS notes
                                ")OR die(mysql_error());
                        $nbr_modules_op_valides = mysql_num_rows($recherche_modules_op_valides);

                            // recherche le nombre de module optionnel validés ou il peut garder sa note (notes >=6)
                         $recherche_modules_li_valides = mysql_query("
                            SELECT notes.moyenne AS moyenne_module, notes.module, notes.statut
                            FROM (
                            SELECT moyennes.etudiant, moyennes.module, modulesparcours.statut AS statut, moyennes.semestre, MAX( moyennes.moyenne ) AS moyenne
                            FROM moyennes, modulesparcours
                            WHERE modulesparcours.module = moyennes.module
                            AND modulesparcours.parcours =  '$parcours'
                            AND moyennes.etudiant =  '$id_etudiant'
                            AND modulesparcours.statut =  'libre'
                            AND moyennes.moyenne >= 6
                             GROUP By module
                             ) AS notes
                                ")OR die(mysql_error());
                        $nbr_modules_li_valides = mysql_num_rows($recherche_modules_li_valides);
                           // si l'étudiant à une note > 6 dans tous les modules obligatoires
                           // + une note >=6 dans le nombre de modules optionnels demandés et le nombre de modules libres demandés
                           if($nbr_modules_ob_valides==$nbr_modules_ob && $nbr_modules_op_valides>=$nbr_modules_op && $nbr_modules_li_valides>=$nbr_modules_li)
                           {
                               // dans ce cas vérifier si l'étudiant est diplômé
                               // on calcule sa moyenne générale en tenant compte des coefficient de chaque module
                               // 1. calculer le total des notes de modules obligatoire
                               // 2. calculer le total des notes de modules optionnel, si l'étudiant a validé plus de module optionnels que demandé
                               // on garde les meilleures notes, par exemple les deux meilleurs notes si le module comporte 2 module optionnels
                               // 3. calculer le total des notes des modules libres
                               // 4. additionner le tout puis le diviser par le total des coefficients
                               // 5. une fois la moyenne générale est obtenue, on vérifie si elle est égale ou superieur à 10
                               // si c'est le cas, on déclare l'étudiant diplômé et on calcule sa mention.

                               // 1. calculer le total des notes de module obligatoire
                               $recherche_total_ob = mysql_query("SELECT SUM(notes.moyenne) AS total_obligatoire
                                FROM
                                (
                                select moyennes.etudiant, moyennes.module, modulesparcours.statut AS statut,
                                moyennes.semestre, max(moyennes.moyenne*modules.coefficient) -- ici le coefficient du module est pris en compte --
                                AS moyenne FROM moyennes, modulesparcours, modules
                                WHERE
                                modulesparcours.module = moyennes.module
                                AND modules.idMODULE = moyennes.module
                                AND modulesparcours.parcours='$parcours'
                                AND moyennes.etudiant = '$id_etudiant'
                                AND modulesparcours.statut = 'obligatoire'
                                AND moyennes.moyenne >= 6
                                 GROUP By module
                                 ) AS notes") OR die(mysql_error());
                                $total_ob = mysql_result($recherche_total_ob, 0, 'total_obligatoire' );
                                // 2. calculer le total des notes de modules optionnel,
                             $recherche_total_op = mysql_query("
                                    SELECT SUM(notes.moyenne) AS total_optionnel
                                    FROM
                                    (
                                    select moyennes.etudiant, moyennes.module,
                                    modulesparcours.statut AS statut, moyennes.semestre,
                                    max(moyennes.moyenne*modules.coefficient)
                                    AS moyenne FROM moyennes, modulesparcours, modules
                                    WHERE
                                    modulesparcours.module = moyennes.module
                                    AND modules.idMODULE = moyennes.module
                                    AND modulesparcours.parcours='$parcours'
                                    AND moyennes.etudiant = '$id_etudiant'
                                    AND modulesparcours.statut = 'optionnel'
                                    AND moyennes.moyenne >= 6
                                     GROUP By module ORDER BY moyenne DESC LIMIT 0, $nbr_modules_op
                                     ) AS notes;") OR die(mysql_error());
                              $total_op = mysql_result($recherche_total_op, 0, 'total_optionnel');
                               // 3. calculer le total des notes des modules libres
                               $recherche_total_li = mysql_query("
                                    SELECT SUM(notes.moyenne) AS total_libre
                                    FROM
                                    (
                                    select moyennes.etudiant, moyennes.module,
                                    modulesparcours.statut AS statut, moyennes.semestre,
                                    max(moyennes.moyenne*modules.coefficient)
                                    AS moyenne FROM moyennes, modulesparcours, modules
                                    WHERE
                                    modulesparcours.module = moyennes.module
                                    AND modules.idMODULE = moyennes.module
                                    AND modulesparcours.parcours='$parcours'
                                    AND moyennes.etudiant = '$id_etudiant'
                                    AND modulesparcours.statut = 'libre'
                                    AND moyennes.moyenne >= 6
                                     GROUP By module ORDER BY moyenne DESC LIMIT 0, $nbr_modules_li
                                     ) AS notes;") OR die(mysql_error());
                              $total_li = mysql_result($recherche_total_li, 0, 'total_libre');

                              // 4. additionner le tout puis le diviser par le total des coefficients
                              // total général des notes
                              $total_general = $total_ob + $total_op + $total_li;
                              // calculer le total des coefficient des modules obligatoires 
                              $recherche_total_coef_ob = mysql_query("
                            SELECT SUM(coef_ob)AS total_coef_ob
                            FROM (
                            SELECT moyennes.etudiant, moyennes.module,
                            MAX( moyennes.moyenne ) AS moyenne, modules.coefficient AS coef_ob
                            FROM moyennes, modulesparcours, modules
                            WHERE modulesparcours.module = moyennes.module
                            AND modulesparcours.parcours =  'simiclass'
                            AND moyennes.etudiant =  'aly'
                            AND modulesparcours.statut =  'obligatoire'
                            AND moyennes.moyenne >= 6
                            AND modules.idMODULE = moyennes.module
                             GROUP By module
                             ) AS notes
                                ")OR die(mysql_error());
                              $total_coef_ob = mysql_result($recherche_total_coef_ob, 0, 'total_coef_ob');
                            // calculer le total des coefficient des modules optionnels
                              $recherche_total_coef_op = mysql_query("
                            SELECT SUM(coef_op)AS total_coef_op
                            FROM (
                            SELECT moyennes.etudiant, moyennes.module,
                            MAX( moyennes.moyenne ) AS moyenne, modules.coefficient AS coef_op
                            FROM moyennes, modulesparcours, modules
                            WHERE modulesparcours.module = moyennes.module
                            AND modulesparcours.parcours =  'simiclass'
                            AND moyennes.etudiant =  'aly'
                            AND modulesparcours.statut =  'optionnel'
                            AND moyennes.moyenne >= 6
                            AND modules.idMODULE = moyennes.module
                             GROUP By module ORDER BY moyenne DESC LIMIT 0, $nbr_modules_op
                             ) AS notes
                                ")OR die(mysql_error());
                              $total_coef_op = mysql_result($recherche_total_coef_op, 0, 'total_coef_op');
                              // calculer le total des coefficients des modules optionnels pris en compte
                              $recherche_total_coef_li = mysql_query("
                            SELECT SUM(coef_li)AS total_coef_li
                            FROM (
                            SELECT moyennes.etudiant, moyennes.module,
                            MAX( moyennes.moyenne ) AS moyenne, modules.coefficient AS coef_li
                            FROM moyennes, modulesparcours, modules
                            WHERE modulesparcours.module = moyennes.module
                            AND modulesparcours.parcours =  '$parcours'
                            AND moyennes.etudiant =  '$id_etudiant'
                            AND modulesparcours.statut =  'libre'
                            AND moyennes.moyenne >= 6
                            AND modules.idMODULE = moyennes.module
                             GROUP By module ORDER BY moyenne DESC LIMIT 0, $nbr_modules_li
                             ) AS notes
                                ")OR die(mysql_error());
                              $total_coef_li = mysql_result($recherche_total_coef_li, 0, 'total_coef_li');
                           // total général des coefficient
                              $total_coef_general = $total_coef_ob + $total_coef_op + $total_coef_li;

                            // calculer la moyenne générale de l'étudiant
                              $moyenne_generale = $total_general/$total_coef_general;
                              // arrondir cette moyenne générale
                              $moyenne_generale = round($moyenne_generale, 2);
                              
                              // 5. une fois la moyenne générale est obtenue, on vérifie si elle est égale ou superieur à 10
                              if($moyenne_generale >= 10)
                              {
                                  // déterminer sa mention
                                  $mention;
                                  switch($moyenne_generale) {
                                      case $moyenne_generale < 12:
                                          $mention = 'Passable';
                                          break;
                                      case $moyenne_generale < 14:
                                          $mention = 'Assez Bien';
                                          break;
                                      case $moyenne_generale < 16:
                                          $mention = 'Bien';
                                          break;
                                      case $moyenne_generale < 18:
                                          $mention = 'Très Bien';
                                          break;
                                      case $moyenne_generale >= 18:
                                          $mention = 'Félicitations du jury';
                                          break;
                                  }
                                 echo "<tr><td> $nom_etudiant</td><td>$formation</td><td>$parcours</td>
                                <td> $moyenne_generale</td>
                                <td>$mention</td></tr>";
                              }
                           }            
                    }
                    ?>

                </table>

                <br />
            </div>
            <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>
