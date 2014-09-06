<?php
session_start(); // démarrer une session
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
        <title>Authentification échouée</title>
        <link rel="shortcut icon" href="images/favicon32.ico"/>
        <link rel="stylesheet" type="text/css" href="styles/style.css" />
    </head>
    <body style="background: white">
<?php
    require 'connexion_bdd.php'; // se connecter à la base de données de l'application
    // stocker les identifiants dans des variables
    $id =$_POST['id'];
    $mdp = $_POST['mdp'];
    // éviter les requêtes SQL dans les données entrées par l'utilisateur
    $id = mysql_real_escape_string($id);
    $mdp = mysql_real_escape_string($mdp);
    // sécuriser le mot de passe en MD5 (tous les mots de passe sont stockés en MD5 sur la BDD
    $mdp = MD5($mdp);
  // vérifier si l'utilisateur a bien rempli le champs "nom et d'utilisateur" et "mot de passe"
   if (!isset($_POST) OR empty($_POST['id']) OR empty($_POST['mdp']))
       {            
     echo '<div class="message">Veuillez remplir tous les champs, patientez 5 secondes ou cliquez
            sur <a href="index.php">Accueil</a>.</div>';
     echo '<script language="javascript"> <!--
              setTimeout(\'window.location="index.php"\', 5000);
                // --> </script>';// le rediriger vers la page de login                
        }
   else
       {
      // créer les requêtes de recherche d'utilisateurs dans la base de données
          // 1. recherche dans la table étudiant
          $rechercheEtudiant = mysql_query("SELECT * FROM etudiants
             WHERE idETUDIANT=\"$id\" AND
             motPass=\"$mdp\";");
          $resultatEtudiant = mysql_fetch_assoc($rechercheEtudiant);
          // 2. recherche dans la table RP
           $rechercheRP = mysql_query("SELECT * FROM rp
             WHERE idRP=\"$id\" AND
             motPass=\"$mdp\";");
          $resultatRP = mysql_fetch_assoc($rechercheRP);
          // 3. recherche dans la table Coordinateur de formation
            $rechercheCF = mysql_query("SELECT * FROM cf
             WHERE idCF=\"$id\" AND
             motPass=\"$mdp\";");
            $resultatCF = mysql_fetch_assoc($rechercheCF);
           // 4. recherche dans la table TUTEURS
           $rechercheTuteur = mysql_query("SELECT * FROM tuteurs
             WHERE idTuteur='$id' AND
             motPass=\"$mdp\";");
           $resultatTuteur = mysql_fetch_assoc($rechercheTuteur);
           //  5. recherche dans la table CORRECTEURS
           $rechercheCorrecteur = mysql_query("SELECT * FROM correcteurs
             WHERE idCORRECTEUR=\"$id\" AND
             motPass=\"$mdp\";");
           $resultatCorrecteur = mysql_fetch_assoc($rechercheCorrecteur);
       // vérifier l'existance de l'utilisateur et l'orienter vers son interface utilisateur
          // 1. si l'utilisateur est un etudiant
      if (mysql_num_rows($rechercheEtudiant) !== 0) {
		 $_SESSION['id'] = $id;
		 $_SESSION['mdp'] = $mdp;
		 $_SESSION['type'] = "etudiant";
                 $_SESSION['nom']=$resultatEtudiant['nom'];
                 $_SESSION['prenom']= $resultatEtudiant['prenom'];
         echo '<script language="javascript"> <!--               
                document.location.replace("etudiant.php");         
               // -->  </script>'; // le dériger vers l'interface étudiant
      }
            // 2. si l'utilisateur est un responsable pédagogique (RP)
      elseif (mysql_num_rows($rechercheRP) !== 0) {
		 $_SESSION['id'] = $id;
		 $_SESSION['mdp'] = $mdp;
		 $_SESSION['type'] = "rp";
                 $_SESSION['nom']=$resultatRP['nom'];
                 $_SESSION['prenom']= $resultatRP['prenom'];
          echo '<script language="javascript"><!--             
                document.location.replace("rp.php");       
                // --> </script>'; // le dériger vers l'interface Résponsable Pédagogique
      }
            // 3. si l'utilisateur est un coordinateur de formation (CF)
      elseif (mysql_num_rows($rechercheCF) !== 0) {
		 $_SESSION['id'] = $id;
		 $_SESSION['mdp'] = $mdp;
		 $_SESSION['type'] = "cf";
                 $_SESSION['nom']=$resultatCF['nom'];
                 $_SESSION['prenom']= $resultatCF['prenom'];
         echo '<script language="javascript"><!--              
                document.location.replace("cf.php");             
                // --></script>'; // le dériger vers l'interface Coordinateur de Formation
      }
            // 4. si l'utilisateur est un tuteur
      elseif (mysql_num_rows($rechercheTuteur) !== 0) {
		 $_SESSION['id'] = $id;
		 $_SESSION['mdp'] = $mdp;
		 $_SESSION['type'] = "tuteur";
                 $_SESSION['nom']=$resultatTuteur['nom'];
                 $_SESSION['prenom']= $resultatTuteur['prenom'];
         echo '<script language="javascript">
                <!--
                document.location.replace("tuteur.php");
                // -->
                </script>';  // le dériger vers l'interface tuteur
      }
            // 5. si l'utilisateur est un correcteur
      elseif (mysql_num_rows($rechercheCorrecteur) !== 0) {
		 $_SESSION['id'] = $id;
		 $_SESSION['mdp'] = $mdp;
		 $_SESSION['type'] = "correcteur";
                 $_SESSION['nom']=$resultatCorrecteur['nom'];
                 $_SESSION['prenom']= $resultatCorrecteur['prenom'];
          echo '<script language="javascript"><!--              
                document.location.replace("correcteur.php");
                // --></script>';// le dériger vers l'interface Correcteur            
      }
            // si les identifiants ne correcspondent à aucun utilisateur présent dans la base,
                // informer l'utilisateur du fait que ces identifiants sont incorrects et l'inviter à réessayer
      else
          {
          echo '<script language="javascript"> <!--
              setTimeout(\'window.location="index.php"\', 5000);
                // --> </script>';// le rediriger vers la page de login              
          echo '
            <div class="message echec">
            Vos identifiants sont incorrects, patientez 5 secondes ou cliquez sur <a href="index.php">Accueil</a>.
            </div>
            ';
         }
   }
?>
 </body>
</html>