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
    pre {font-size: 80%;}
     tr:nth-child(odd) {background-color: #D4D4D4;}
</style>
<script type="text/javascript">
   
 $(document).ready(function(){
     
    // validation du formulaire d'affichage 
 $("#moyennesModules").validate({
     rules: {
		 session: "required"
     },
     messages: {
		 session: "Veuillez sélectionner une session"
     }
	 
 })
  });
  
  </script>
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
								
				?>
                </div>       
                <h1> Mes moyennes de modules </h1>
                
                <!-- choisir une session -->

                <form method="get" action="<?php echo "etudiant-moyennes-modules.php?session=$_GET[session]"?>" id="moyennesModules">
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
                    <input type="submit" value="valider"/>
                </form>

                <!-- afficher les moyennes si une session est sélectionnée par l'étudiant ************** -->
                <?php
                    if(isset($_GET['session']) AND !is_null($_GET['session']))
                    { ?>
                <h2>Session: <?php echo "$_GET[session]"?></h2>
                <table>
                    <tr><th>Module</th><th>Moyenne</th></tr>
                    <?php
                        // liste des modules
                     $recherche_modules= mysql_query("SELECT module FROM moyennes
                             WHERE semestre='$_GET[session]'
                             AND etudiant = '$_SESSION[id]'
                             ;
                            ") OR die(mysql_error());

                    while($modules = mysql_fetch_array($recherche_modules) )
                    {
                        $module = $modules['module'];
                        // rechercher la moyenne de l'étudiant pour le module sélectionné
                        $recherche_moyenne_module = mysql_query("SELECT moyenne FROM moyennes
                            WHERE module='$module'
                            AND etudiant='$_SESSION[id]'
                            AND semestre= '$_GET[session]'
                                ;")OR die(mysql_error());                  
                        $moyenns_module = mysql_result($recherche_moyenne_module, 0, 'moyenne');
                        // afficher la ligne de résultats
                        echo "<tr><td>$module </td><td>$moyenns_module</td></tr>";
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

