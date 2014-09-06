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
<!-- liens pour la validation du formulaire côté client     -->
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/jquery-validate.js"></script>

<script type="text/javascript">

 $(document).ready(function(){
      // validation du formulaire d'ajout d'examen
    $("#ajoutDevoir").validate({

        rules: {
            module: "required",
            session: "required",
            tuteur: "required",
            num_devoir:"required",
            nom_devoir: "required",
            date: {
                required: true,
                dateISO: true
            }
        },
        messages: {
            module: "Veuillez indiquer le module concerné",
            session: "Veuillez indiquer la session du devoir",
            tuteur: "Veuillez choisir le tuteur de ce devoir",
            num_devoir: "Veuillez choisir le numéro du devoir",
            nom_devoir: "Veuillez donner un nom au devoir",
            date:
                {
                required: "Veuillez choisir la date du devoir",
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
                <img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>
            <div id="corps">
               <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
               
               <div id="photo">
               <h3> Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; //Affichage du nom de la personne ?> </h3>
</div>
                <h1>Créer un nouveau devoir </h1>
                <form action="ajout-devoir.php" method="post" id="ajoutDevoir">
                <label>Sélectionner un module: <span>*</span></label>
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
                           $annee = 2000+$i;   // définir les semestre jusqu'à 2029
                            ?>
                    <option value="<?="juin$annee"?>"> Juin <?=$annee?></option>
                    <option value="<?="decembre$annee"?>"> Décembre <?=$annee?></option>
                    <?php
                        }
                    ?>
                </select>
                <label>Choisir le numéro du devoir</label>
                <select name="num_devoir" id="num_devoir">
                    <option></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <label for="nom_devoir">Donner un nom au devoir <span>*</span></label>
                <pre style="font-size: xx-small; text-align: center"> Exemple: Devoir N°1 du module B211, session juin 2011</pre>
                <input type="text" name="nom_devoir" id="nom_devoir"/>
                <label>Choisir un tuteur: <span>*</span></label>
                    <select name="tuteur" id="tuteur">
                        <option value=''></option>
                        <?php
                        $recherche_tuteurs = mysql_query("SELECT * FROM tuteurs;") OR die (mysql_error());
                        $nombre_tuteurs = mysql_num_rows($recherche_tuteurs);
                        for($i=0; $i<$nombre_tuteurs; $i++)
                        {
                            $id_tuteur = mysql_result($recherche_tuteurs, $i, 'idTUTEUR');
                            $nom_tuteur = mysql_result($recherche_tuteurs, $i, 'nom');
                            $prenom_tuteur = mysql_result($recherche_tuteurs, $i, 'prenom');
                         echo "<option value='$id_tuteur'>$prenom_tuteur $nom_tuteur</option>";
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
