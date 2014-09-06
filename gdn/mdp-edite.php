<?php
            session_start();
            // si aucune session n'est ouverte, renvoyer l'utilisateur sur la page d'accès
            if(empty($_SESSION))
            {
                header( "Location:index.php" );
            }
            require 'connexion_bdd.php'; // se connecter à la base de données de l'application pour la mettre à jours
            $type = $_SESSION['type']; // pour savoir quelle table mettre à jours
            $id = $_SESSION['id']; // pour savoir quelle ligne mettre à jours
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
        <title>Mot de passe édité</title>
        <link rel="shortcut icon" href="images/favicon32.ico"/>
        <link rel="stylesheet" type="text/css" href="styles/message-validation.css" />
    </head>
    <body>
       <?php
          $table;
          switch($type){
                case 'etudiant';
                    $table = 'etudiants';
                    break;
                 case 'tuteur';
                    $table = 'tuteurs';
                    break;
                 case 'correcteur';
                    $table = 'correcteurs';
                    break;
                 case 'cf';
                    $table = 'cf';
                    break;
                 case 'rp';
                    $table = 'rp';
                    break;
          }
            // récupérer les mots de passe entrés et se protéger contre les attaques SQL
        $ancien_mdp = mysql_real_escape_string($_POST['ancien_mdp']);
        $nouveau_mdp = mysql_real_escape_string($_POST['nouveau_mdp']);
        $confirme_mdp = mysql_real_escape_string($_POST['confirme_mdp']);
            // sécuriser les mots de passe en SQL
        $ancien_mdp = MD5($ancien_mdp);
        $nouveau_mdp = MD5($nouveau_mdp);
        $confirme_mdp = MD5($confirme_mdp);
            // récupérer le mot de passe de de la session (celui utilisé pour entrer dans son espace)
        $mdp = $_SESSION['mdp'];
        if(!isset($_POST) OR (empty($_POST['ancien_mdp']) OR empty($_POST['nouveau_mdp']) OR empty($_POST['confirme_mdp']))){
            echo '<script language="javascript"> <!--
              setTimeout(\'window.location="editer-mdp.php"\', 5000);
                // --> </script>';
        echo '<div class=\'message\'>
            Veuillez remplir tous les champs, patientez 5 secondes ou cliquez
            sur <a href="editer-mdp.php">Changement du mot de passe</a>. pour rééditer votre mot de passe.
            </div>
            ';
        exit;
        }
        // si tous les champs sont remplis
        elseif($ancien_mdp!==$mdp){ // si le mot de passe tapé n'est pas conforme à celui de la session
             echo '<script language="javascript"> <!--
              setTimeout(\'window.location="editer-mdp.php"\', 5000);
                // --> </script>';
        echo "<div class='message'>
            L'ancien mot de passe tapé n'est pas le bon, patientez 5 secondes ou cliquez
            sur <a href='editer-mdp.php'>Changement du mot de passe</a> pour rééditer votre mot de passe.
            </div>
            ";
        exit;
        }
        // si l'ancien mot de passe entré est le bon
        elseif($nouveau_mdp!==$confirme_mdp){ // si la confirmation du nouveau mdp n'est pas bonne
             echo '<script language="javascript"> <!--
              setTimeout(\'window.location="editer-mdp.php"\', 5000);
                // --> </script>';
        echo "<div class='message'>
            Le nouveau mot de passe n'est pas identique au mot de passe de confirmation, patientez 5 secondes ou cliquez
            sur <a href='editer-mdp.php'>Changement du mot de passe</a> pour rééditer votre mot de passe.
            </div>
            ";
        exit;
        }
        // si tout est bon, procéder à l'enregistrement du nouveau mot de passe
        else {
             mysql_query("UPDATE $table SET `motPass` =  \"$nouveau_mdp\" WHERE id$type = \"$id\";");
            echo "<script language='javascript'> <!--
              setTimeout('window.location=\"$type.php\"', 5000);
                // --> </script>";
        echo "<div class='message'>
            Votre nouveau mot de passe a été bien pris en compte, patientez 5 secondes ou cliquez
            sur <a href='$type.php'>Accueil</a>. pour revenir à la page d'accueil. Type= $type et id = $id
            </div>
            ";

        }
        ?>
   </body>
</html>
