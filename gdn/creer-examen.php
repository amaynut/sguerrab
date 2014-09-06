<?php
            session_start();
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
<!-- liens pour la validation du formulaire c�t� client     -->
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/jquery-validate.js"></script>

<script type="text/javascript">

 $(document).ready(function(){
      // validation du formulaire d'ajout d'examen
    $("#ajoutExamen").validate({

        rules: {
            module: "required",
            session: "required",
            correcteur: "required",
            date: {
                required: true,
                dateISO: true
            }
        },
        messages: {
            module: "Veuillez indiquer le module concern�",
            session: "Veuillez indiquer la session d'examen",
            correcteur: "Veuillez choisir le correcteur de cet examen",
            date:
                {
                required: "Veuillez choisir la date d'examen",
                dateISO: "veuillez choisir une date au format AAAA-MM-JJ"
            }
        }
});
  });

  </script>
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Gestion de Notes e-Miage: interface Coordinateur de Formation (CF)</title>
</head>
<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="banni�re du site"/>
            </div>
            <div id="corps">
               <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
               <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>
               </div>
               
                <h1>Cr�er un nouvel examen</h1>
                <form action="ajout-examen.php" method="post" id="ajoutExamen">
                <label>S�lectionner un module: <span>*</span></label>
                 <select name="module" id="module">
                        <option value=''></option>
                        <?php
                        $recherche_modules = mysql_query("SELECT idMODULE FROM modules;") OR die (mysql_error());
                        $nombre_modules = mysql_num_rows($recherche_modules);
                        for($i=0; $i<$nombre_modules; $i++)
                        {
                            $id_module = mysql_result($recherche_modules, $i, 'idMODULE');
                         echo "<option value='$id_module'>$id_module</option>";
                        }
                        ?>
                    </select>
                <label>Choisir une session: <span>*</span></label>
                <select name="session" id="session">
                    <option value=""></option>
                    <?php
                        for($i=9; $i<30; $i++)
                        {
                           $annee = 2000+$i;   // d�finir les semestre jusqu'� 2029
                            ?>
                    <option value="<?="juin$annee"?>"> Juin <?=$annee?></option>
                    <option value="<?="decembre$annee"?>"> D�cembre <?=$annee?></option>
                    <?php
                        }
                    ?>
                </select>
                <label>Choisir un correcteur: <span>*</span></label>
                <select name="correcteur" id="correcteur">
                        <option value=''></option>
                        <?php
                        $recherche_correcteurs = mysql_query("SELECT * FROM correcteurs;") OR die (mysql_error());
                        $nombre_correcteurs = mysql_num_rows($recherche_correcteurs);
                        for($i=0; $i<$nombre_correcteurs; $i++)
                        {
                            $id_correcteur = mysql_result($recherche_correcteurs, $i, 'idCORRECTEUR');
                            $nom_correcteur = mysql_result($recherche_correcteurs, $i, 'nom');
                            $prenom_correcteur = mysql_result($recherche_correcteurs, $i, 'prenom');
                         echo "<option value='$id_correcteur'>$prenom_correcteur $nom_correcteur</option>";
                        }
                        ?>
                    </select>
                <label>Choisir une date (format AAAA/MM/JJ) <span>*</span>:</label>
                <input type="text" name="date" id="date"/>
                
                <input type="submit" value="Valider" name="envoie"/>
                </form> 

            </div>
<div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>
