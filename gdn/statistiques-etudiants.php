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
                         require_once('maxChart.class.php');
                   //ici on utilise une classe php qui s'appelle MaxChart
                   // cette classe est disponible sur http://www.phpf1.com/product/php-chart-script.html
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
		 session: "required"

     },
     messages: {
		 session: "Veuillez sélectionner une session"
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
                <h1>Statistiques des étudiants</h1>
                <h2> Etudiants par centre eMiage</h2>
               <?php
                   // rechercher les centres eMIAGE
               $recherche_centre = mysql_query("SELECT * FROM centres");
               while($centres= mysql_fetch_array($recherche_centre))
               {
                   $id_centre = $centres['idCENTRE'];
                   $nom_centre = $centres['nom'];
                   // rechercher le nombre d'étudiant inscrits dans ce centre
                   $recherche_etudiant = mysql_query("SELECT idETUDIANT FROM etudiants WHERE centre = '$id_centre'") OR die (mysql_error());
                   $nombre_etudiant = mysql_num_rows($recherche_etudiant);
                   $centre[$nom_centre] =  $nombre_etudiant;
               }
                $mc = new maxChart($centre);
                    $mc->displayChart('Etudiants par centre - 1',1,500,350);
               ?>
               <br />
               <h2>Etudiants par pays</h2>
                <?php
                   // rechercher des pays des étudiants
               $recherche_pays = mysql_query("SELECT DISTINCT pays FROM etudiants");
               while($pays= mysql_fetch_array($recherche_pays))
               {
                   $pays = $pays['pays'];
                   // rechercher le nombre d'étudiant inscrits dans ce centre
                   $recherche_etudiant = mysql_query("SELECT idETUDIANT FROM etudiants WHERE pays = '$pays'") OR die (mysql_error());
                   $nombre_etudiant = mysql_num_rows($recherche_etudiant);
                   $data[$pays] =  $nombre_etudiant;
               }
                $mc = new maxChart($data);
                    $mc->displayChart('Etudiants par pays - 1',1,500,350);
               ?>
               <br />
               <h2> Etudiants par formation</h2>
               <?php
                   // rechercher des pays des étudiants
               $recherche_formation = mysql_query("SELECT * FROM formations");
               while($formations= mysql_fetch_array($recherche_formation))
               {
                   $id_formation = $formations['idFORMATION'];
                   $nom_formation = $formations['nom'];
                   // rechercher le nombre d'étudiant inscrits dans la formation
                   $recherche_etudiant = mysql_query("SELECT idETUDIANT FROM etudiants WHERE formation = '$id_formation'") OR die (mysql_error());
                   $nombre_etudiant = mysql_num_rows($recherche_etudiant);
                   $formation[$nom_formation] =  $nombre_etudiant;
               }
                $mc = new maxChart($formation);
                    $mc->displayChart('Etudiants par formation - 1',1,300,350);
               ?>
               <br />
               <h2> Etudiants par parcours</h2>
               <?php

                   // rechercher des pays des étudiants
               $recherche_parcours = mysql_query("SELECT * FROM parcours");
               while($parcours= mysql_fetch_array($recherche_parcours))
               {
                   $id_parcours = $parcours['idPARCOURS'];
                   $nom = $parcours['nom'];
                   // rechercher le nombre d'étudiants inscrits dans le parcours
                   $recherche_etudiant = mysql_query("SELECT idETUDIANT FROM etudiants WHERE parcours = '$id_parcours'") OR die (mysql_error());
                   $nombre_etudiant = mysql_num_rows($recherche_etudiant);
                   $donnees[$nom] =  $nombre_etudiant;
               }
               
                $mc = new maxChart($donnees);
                    $mc->displayChart('Etudiants par parcours - 1',1,500,350);
               ?>
               <br />
                
            </div>
            <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>
