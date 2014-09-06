<?php
            session_start();
             // si l'utilisateur n'est pas un CF, le renvoyer vers la page d'acc?s
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
                <img src="images/en-tete.jpg" alt="banni?re du site"/>
            </div>
            <div id="corps">
                <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
               <h3> Vous ?tes <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>
               <?php
                        if(!isset($_POST)
                                OR empty($_POST['module'])
                                OR empty($_POST['session'])
                                OR empty($_POST['tuteur'])
                                OR empty($_POST['num_devoir'])
                                OR empty($_POST['date'])
                                OR empty($_POST['nom_devoir'])

                                )
                        {
                            echo "<div class='message echec'>
                                les champs suivis d'une ?toile sont obligatoires
                                <div>";
                            exit;
                        }
                        // si tout est rempli, proc?der ? la cr?ation de l'devoir
                        else
                        {
                            $module = $_POST['module'];
                            $tuteur = $_POST['tuteur'];
                            $session = $_POST['session'];
                            $date = $_POST['date'];
                            $num_devoir = $_POST['num_devoir'];
                            $nom_devoir = mysql_real_escape_string(strip_tags($_POST['nom_devoir']));
                             // d?terimer l'identifiant de l'devoir sous la forme E-module-session
                            $id_devoir = "D$num_devoir-$module-$session";
                            $recherche_devoirs = mysql_query("SELECT * FROM devoirs WHERE idDEVOIR = '$id_devoir'") OR die (mysql_error());
                            $nombre_devoirs = mysql_num_rows($recherche_devoirs);
                            if($nombre_devoirs!=0)
                            {
                                echo "<div class='message echec'>
                                Le devoir du module $module pour la session $session existe d?j? dans la base de donn?es
                                        Veuillez choisir une autre session ou un autre module.
                                </div>";
                                exit;
                            }
                            else
                            {      // si le devoir n'existe pas d?j? dans la bdd, proc?der ? sa cr?ation
                                mysql_query("INSERT INTO devoirs (`idDEVOIR`, NUMERO,`date`, `session`, `tuteur`, `module`, nom)
                                    VALUES ('$id_devoir', '$num_devoir', '$date', '$session', '$tuteur', '$module', '$nom_devoir')") OR die(mysql_error());
                                  // informer l'utilisateur sur la cr?ation de l'devoir
                                echo "<div class='message succes'>
                                  Le devoir du module $module pour la session $session vient d'?tre cr?? avec succ?s.
                                  </div> ";
                                echo '<script language="javascript"><!--
                                       setTimeout(\'window.location="devoirs.php"\', 2000);
                                        // --></script>';// le d?riger vers la liste des devoirs
                            }
                     }
               ?>
            </div>
            <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>