<?php
    session_start();
     // si aucune session n'est ouverte, renvoyer l'utilisateur sur la page d'accès
            if(empty($_SESSION))
            {
                header( "Location:index.php" );
            }
     require 'connexion_bdd.php'; // se connecter à la base de données de l'application pour la mettre à jours
    $type = $_SESSION['type']; // pour savoir quelle table mettre à jours
    $id = $_SESSION['id']; // pour savoir quel entrée mettre à jours
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="images/favicon32.ico"/>
    <link rel="stylesheet" type="text/css" href="styles/message-validation.css" />
    <title>Gestion de Note Emiage : Fiche perso éditée</title>
</head>

    <body>
<?php
// récupérer les nouvelles entrées de l'utilisateurs et éviter les attaques SQL
// et éviter la présence de balises HTML et PHP dans les entrées utilisateur
$rue = mysql_real_escape_string(strip_tags($_POST['rue'])); // mysql_real_escape_string() est pour éviter les attaques SQL
$cp = mysql_real_escape_string(strip_tags($_POST['cp'])); // strip_tags() est pour éviter les balises HTML et PHP
$ville = mysql_real_escape_string(strip_tags($_POST['ville']));
$pays = mysql_real_escape_string(strip_tags($_POST['pays']));
$mail = mysql_real_escape_string(strip_tags($_POST['mail']));
$phone = mysql_real_escape_string(strip_tags($_POST['phone']));
// retrouver le nom de la table à mettre à jours
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
// extraire le mot de passe de l'utilisateur pour le mettre à jour
$requeteMdp = mysql_query("SELECT motPass FROM $table WHERE id$type = \"$id\";");
$resultatMdp = mysql_fetch_assoc($requeteMdp);
// vérifier si l'utilisateur a mis à rempli au moins 1 champ
if(!isset($_POST) OR (empty($rue) AND empty($cp) AND empty($ville) AND empty($pays) AND empty($mail) AND empty($phone)) )
{
     echo '<script language="javascript"> <!--
              setTimeout(\'window.location="editer-fiche.php"\', 5000);
                // --> </script>';
        echo '<div class="message">
            Veuillez remplir au moins un champ pour que la mise à jours soit prise en compte, patientez 5 secondes ou cliquez
            sur <a href="editer-fiche.php">Accueil</a>. </div>';                    
        exit;
}
  else{
    if(!empty($rue))
    {
        mysql_query("UPDATE $table SET `adresse` =  \"$rue\" WHERE id$type = \"$id\";");
    }
    if(!empty($cp))
    {
        mysql_query("UPDATE $table SET `codePostal` =  \"$cp\" WHERE `id$type` = \"$id\";");
    }
    if(!empty($ville))
    {
        mysql_query("UPDATE $table SET `ville` =  \"$ville\" WHERE `id$type` = \"$id\";");
    }
     if(!empty($pays))
    {
        mysql_query("UPDATE $table SET `pays` =  \"$pays\" WHERE `id$type` = \"$id\";");
    }
     if(!empty($mail))
    {
        mysql_query("UPDATE $table SET `mail` =  \"$mail\" WHERE `id$type` = \"$id\";");
    }
     if(!empty($phone))
    {
        mysql_query("UPDATE $table SET `phone` =  \"$phone\" WHERE `id$type` = \"$id\";");
    }
   echo "<script language=\"javascript\"> <!--
              setTimeout('window.location=\"$type.php\"', 5000);
                // --> </script>";
        echo "<div class='message'>
            Votre fiche a été mise à jour, patientez 5 secondes ou cliquez
            sur <a href=\"$type.php\">Accueil</a>.</div>";                        
  }
?>
    </body>
</html>