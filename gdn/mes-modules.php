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
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Gestion de Notes e-Miage: interface ETUDIANTS</title>
</head>

<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>
            <div id="corps">
                       <?php include "menus/menu-etudiant.php"?>
                <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>
                <?php
				//affichage de la photo par défaut ou de celle uploadée
				$requeteCheminphoto = mysql_query ("SELECT photo FROM etudiants WHERE idETUDIANT = '$id'");
        			$resultatCheminphoto = mysql_fetch_array($requeteCheminphoto);

				echo '<img src="'.$resultatCheminphoto['photo'].'" alt="photo-utilisateur"/>';
                        // rechercher le parcours de l'étudiant
                     $recherche_parcours = mysql_query("SELECT parcours FROM etudiants WHERE idETUDIANT='$_SESSION[id]';");
                     $resultat_parcours = mysql_fetch_assoc($recherche_parcours);
                    $parcours = $resultat_parcours['parcours'];
				?>
                </div>
                <h1> Mes modules </h1>
                <table>
                    <tr><th>Nom du module</th><th>Statut du module</th><th>Situation actuelle</th></tr>
                    <?php
                    
                    // rechercher les modules de l'étudiant
                    $recherche_modules= mysql_query("SELECT modulesparcours.*, modules.nom FROM modulesparcours, modules
                            WHERE modulesparcours.parcours='$parcours'
                            AND modulesparcours.module=modules.idMODULE ORDER BY statut;
                            ") OR die(mysql_error());
                     while($modules = mysql_fetch_array($recherche_modules)) // liste des modules
                     {
                         echo "<tr> <th>$modules[module]: $modules[nom]</th><td>$modules[statut]</td>";
                            $recherche_moyenne =mysql_query("SELECT MAX(moyenne) AS moyenne FROM moyennes
                            WHERE etudiant='$_SESSION[id]' AND module='$modules[module]'
                            ;");
                          $resultat_moyenne = mysql_fetch_array($recherche_moyenne);
                          $moyenne =  $resultat_moyenne['moyenne'];
                          if($moyenne!=null)   // si l'étudiant a déjà une moyenne
                          {
                             if($moyenne>=10)
                             {
                                 echo "<td style='background:#1AFF76;'>Module validé</td>";
                             }
                             elseif($moyenne > 6 AND $moyenne < 10 )
                             {
                                 echo "<td style='background:#FFC90E;'>La note peut être gardée</td>";
                             }
                             else {
                                 echo "<td style='background:#FF4248;'>Note éliminatoire</td>";
                             }
                          }
                          else {       // sinon on affiche une case vide

                              echo "<td>--</td>";
                          }
                         echo"</tr>";
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
