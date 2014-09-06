<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="images/favicon32.ico"/>
    <link rel="stylesheet" type="text/css" href="styles/message-validation.css" />
    <title>Gestion de Note Emiage : Mot de passe réinitialisé</title>
</head>
    <body>    
        <?php
 // ce script sert à réinialiser le mot de passe si l'utilisateur a oublié son mot de passe.
        require_once "connexion_bdd.php"; // se connecter à la base de données
        //stocker les données entrées par l'utilisateur dans des variable
        $mail = $_POST['mail'];
        $id = $_POST['id'];
        // éviter les attaques SQL
        $mail = mysql_real_escape_string($mail);
        $id= mysql_real_escape_string($id);
        // générer un mot de passe aléatoire qui sera envoyé par email et remplacer l'ancien mot de passe oublié
        $mdp_genere = substr(uniqid(),1 , 8); // génère un mot de passe aléatoire de 8 charactères
        $nouveau_mdp = MD5($mdp_genere); // le nouveau mot de passe à stocker sur la bdd en MD5  
        $message_mail = "Bonjour \r \n Votre mot de passe a été réinitialisé avec succès. Voici votre nouveau mot de passe: $mdp_genere \r \n Vous pouvez le changer à tout moment sur votre interface utilisateur.
        \r \n Cordialement, le webmaster du site de gestion de notes des formations emiage"; // message à envoyer par email à l'utilisateur en cas de succès";
        $message_succes = ' <div> Votre mot de passe viens d\'être réinitialisé avec succès. Votre nouveau mot de passe vient d\'être envoyé sur votre boite email. Patientez 10 secondes ou cliquez
            sur <a href=\'index.php\'>Identification</a> pour revenir sur la page d\'identification</div>
            <script language="javascript"> <!--
              setTimeout("window.location=\'index.php\'", 10000);
                // --> </script>'; // rediriger l'utilisateur vers la page d'identification si le mot de passe a été bien réinitialisé
        // vérifier que l'utilisateur a bien rempli tous les champs du formulaires
        if(!isset($_POST) OR (empty($_POST['id'])OR empty($_POST['mail'])))
        {
              echo "<script language='javascript'> <!--
              setTimeout('window.location=\'mdp-reinitialisation.php\'', 5000);
                // --> </script>";
        echo "<div class='message'>
            Veuillez remplir tous les champs, patientez 5 secondes ou cliquez
            sur <a href='mdp-reinitialisation.php'>Réinitialisation du mot de passe</a>. pour réinitialiser votre mot de passe.
            </div>
            ";
        exit;
        }
        // si tous les champs sont bien remplis, voir si l'identifiant existe dans la bdd
       else
       {
      // créer les requêtes de recherche d'utilisateurs dans la base de données
          // 1. recherche dans la table étudiant
          $requeteEtudiant = mysql_query("SELECT * FROM etudiants
             WHERE idETUDIANT='$id' AND
             mail='$mail';");
          // 2. recherche dans la table RP
           $requeteRP = mysql_query("SELECT * FROM rp
             WHERE idRP='$id' AND
             motPass='$mail';");
          // 3. recherche dans la table Coordinateur de formation
            $requeteCF = mysql_query("SELECT * FROM cf
             WHERE idCF='$id' AND
             mail='$mail';");
           // 4. recherche dans la table TUTEURS
           $requeteTuteur = mysql_query("SELECT * FROM tuteurs
             WHERE idTuteur='$id' AND
             mail='$mail';");
           //  5. recherche dans la table CORRECTEURS
           $requeteCorrecteur = mysql_query("SELECT * FROM correcteurs
             WHERE idCORRECTEUR='$id' AND
             mail='$mail';");
       // vérifier l'existance de l'utilisateur et l'orienter vers son interface utilisateur
          // 1. si l'utilisateur est un etudiant
      if (mysql_num_rows($requeteEtudiant) !== 0) {
                mysql_query("UPDATE etudiants SET motPass = '$nouveau_mdp' WHERE idETUDIANT='$id';"); // mettre à jour le champs mot de passe       
                // envoyer par email le nouveau mot de passe de l'utilisateur
                mail($mail, "Votre nouveau mot de passe", $message_mail);
                echo $message_succes;
                exit;
      }
            // 2. si l'utilisateur est un responsable pédagogique (RP)
      elseif (mysql_num_rows($requeteRP) !== 0) {
                 mysql_query("UPDATE rp SET motPass = '$nouveau_mdp' WHERE idRP='$id';"); // mettre à jour le champs mot de passe
                // envoyer par email le nouveau mot de passe de l'utilisateur
                mail($mail, "Votre nouveau mot de passe", $message_mail, $headers);
                 echo $message_succes;
                exit;
      }
            // 3. si l'utilisateur est un coordinateur de formation (CF)
      elseif (mysql_num_rows($requeteCF) !== 0) {
          mysql_query("UPDATE cf SET motPass = '$nouveau_mdp' WHERE idCF='$id';"); // mettre à jour le champs mot de passe
                // envoyer par email le nouveau mot de passe de l'utilisateur
                mail($mail, "Votre nouveau mot de passe", $message_mail, $headers);
                 echo $message_succes;
                exit;         
      }
            // 4. si l'utilisateur est un tuteur
      elseif (mysql_num_rows($requeteTuteur) !== 0) {
          mysql_query("UPDATE tuteurs SET motPass = '$nouveau_mdp' WHERE idTUTEUR='$id';"); // mettre à jour le champs mot de passe
                // envoyer par email le nouveau mot de passe de l'utilisateur
                mail($mail, "Votre nouveau mot de passe", $message_mail, $headers);
                 echo $message_succes;
                exit;
      }
            // 5. si l'utilisateur est un correcteur
      elseif (mysql_num_rows($requeteCorrecteur) !== 0) {
          mysql_query("UPDATE correcteurs SET motPass = '$nouveau_mdp' WHERE idCORRECTEUR='$id';"); // mettre à jour le champs mot de passe
                // envoyer par email le nouveau mot de passe de l'utilisateur
                mail($mail, "Votre nouveau mot de passe", $message_mail, $headers);
                 echo $message_succes;
                exit;        
      }
           // si l'identifiant de l'utilisateur ne correspond pas à l'adresse email donnée
                // informer l'utilisateur du fait que les informations entrées sont incorrectes et l'inviter à réessayer
      else
          {
          echo '<script language="javascript"> <!--
              setTimeout("window.location=\'mdp-reinitialisation.php\'", 5000);
                // --> </script>';
        echo "
            <div class='message'>
            L'identifiant et l'adresse email entrés ne correspondent à aucun utilisateur , patientez 5 secondes ou cliquez sur <a href='mdp-reinitialisation.php'>Reinitialisation du mot de passe</a>.
            </div>
            ";
         }        
       }
        ?>
    </body>
</html>