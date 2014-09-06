<?php
            session_start();
             // si l'utilisateur n'est pas un CF, le renvoyer vers la page d'acc�s
           if($_SESSION['type']!='cf' AND $_SESSION['type']!='rp')
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
        <link rel="shortcut icon" href="images/favicon32.ico"/>
        <script type="text/javascript" src="js/jquery-1.4.2.js"></script>
        <title>Gestion de Notes e-Miage: interface Coordinateur de Formation (CF)</title>
    </head>
<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="banni�re du site"/>
            </div>
            <div id="corps">
               <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
               <h3> Vous �tes <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>
               <?php                    
                        if(!isset($_POST) OR empty($_POST['module']) OR empty($_POST['session'])OR empty($_POST['correcteur']))
                        {
                            echo "<div class='message echec'>
                                les champs suivis d'une �toile sont obligatoires
                                <div>";
                            exit;
                        }
                        // si tout est rempli, proc�der � la cr�ation de l'examen
                        else 
                        {                           
                            $module = $_POST['module'];
                            $correcteur = $_POST['correcteur'];
                            $session = $_POST['session'];
                            $date = $_POST['date'];
                             // d�terimer l'identifiant de l'examen sous la forme E-module-session
                            $id_examen = "E-$module-$session";
                            $recherche_examens = mysql_query("SELECT * FROM examens WHERE idEXAMEN = '$id_examen'") OR die (mysql_error());
                            $nombre_examens = mysql_num_rows($recherche_examens);
                            if($nombre_examens!=0)
                            {
                                echo "<div class='message echec'>
                                L'examen du module $module pour la session $session existe d�j� dans la base de donn�es
                                        Veuillez choisir une autre session ou un autre module.
                                </div>";
                                echo '<script language="javascript"><!--
                                       setTimeout(\'window.location="examens.php"\', 5000);
                                        // --></script>';// le d�riger vers la liste des examens
                            }
                            else 
                            {      // si l'examen n'existe pas d�j� dans la bdd, proc�der � sa cr�ation
                                mysql_query("INSERT INTO examens (`idEXAMEN`, `date`, `session`, `correcteur`, `module`)
                                    VALUES ('$id_examen', '$date', '$session', '$correcteur', '$module')") OR die(mysql_error());                                   
                                  // informer l'utilisateur sur la cr�ation de l'examen
                                echo "<div class='message succes'>
                                  L'examen du module $module pour la session $session vient d'�tre cr�� avec succ�s.
                                  </div> ";
                                echo '<script language="javascript"><!--              
                                       setTimeout(\'window.location="examens.php"\', 5000);
                                        // --></script>';// le d�riger vers la liste des examens
                            }
                     }
               ?>
            </div>
            <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>