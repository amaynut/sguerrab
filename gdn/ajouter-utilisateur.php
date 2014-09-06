<?php
            session_start();
           
           if($_SESSION['type']!='cf' AND $_SESSION['type']!='rp')
            {
                header( "Location:index.php" );
            }
            require_once 'connexion_bdd.php'
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" href="styles/style.css" id="bleu" />
<link rel="stylesheet" href="styles/message-validation.css" id="bleu" />
<!-- liens pour la validation du formulaire côté client     -->
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/jquery-validate.js"></script>

<script type="text/javascript">
 $(document).ready(function(){
    $("#ajoutUtilisateur").validate({

    rules: {
            id: "required",
            nom: "required",
            prenom: "required",
            mail: {
                required: true,
                email: true
            },
            parcours: "required",
            centre: "required"
        },
        messages: {
            id: "Veuillez attribuer un identifiant au nouvel utilisateur",
            prenom: "Veuillez indiquer un prénom",
            nom: "Veuillez indiquer un nom",
            mail: {
                required: "Veuillez saisir une adresse email",
                email: "Veuillez saisir une adresse email valide"
            },
            parcours: "Veuillez choisir le parcours de l'étudiant",
            centre: "Veuillez indiquer le centre de rattachement du nouvel étudiant"
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
               <h1>Ajouter un <?php echo $_GET['type']?></h1>
               <?php // déterminer l'URL de traitement selon le type d'utilisateur 
                    if($_GET['type']=='etudiant'){
                        $url ="ajouter-utilisateur.php?type=etudiant&formation=$_GET[formation]";
                        
                    }
                    else {
                        $url = "ajouter-utilisateur.php?type=$_GET[type]";
                    }
               
               ?>
               <form method="post" action='<?=$url?>' id="ajoutUtilisateur">
                   <!-- champs communs à tous les utilisateurs               ------------   -->
                   <fieldset> <legend>Identité</legend>
                   <label for="id">Identifiant <span>*</span></label>
                   <input type="text" name="id" id="id" class="required"/>

                   <label for="nom">Nom <span>*</span></label>
                   <input type="text" name="nom" id="nom" class="required" />

                   <label for="prenom">Prénom <span>*</span></label>
                   <input type="text" name="prenom" id="prenom" class="required"/>
                   </fieldset>
                   <fieldset> <legend>Adresse</legend>
                   
                   <label for="adresse">Adresse</label>
                   <input type="text" name="adresse" id="adresse"/>

                   <label for="cp">Code Postal</label>
                   <input type="text" name="cp" id="cp"/>

                   <label for="ville">Ville</label>
                   <input type="text" name="ville" id="ville"/>

                   <label for="pays">Pays</label>
                   <input type="text" name="pays" id="pays"/>
                   </fieldset>
                   <fieldset> <legend>Contact</legend>
                   <label for="phone">Téléphone</label>
                   <input type="text" name="phone" id="phone"/>

                   <label for="mail">Email <span>*</span></label>
                   <input type="text" name="mail" id="mail"/>
                    </fieldset>
                   <!-- si le nouvel utilisateur est un étudiant -->
                   <?php
                        if($_GET['type']=='etudiant' AND $_SESSION['type'] == 'cf')
                        {
                   ?>
                   <fieldset> <legend> Formation</legend>
                   <label for="formation">Formation</label>
                   <input type="text" readonly="readonly" name="formation" value="<?php echo "$_GET[formation]"; ?>"/>

                   <label for="parcours">Parcours <span>*</span></label>
                   <select name="parcours" id="parcours" class="required">
                        <option></option>
                         <?php
                        $recherche_parcours = mysql_query("SELECT idPARCOURS, nom FROM parcours WHERE formation='$_GET[formation]';");
                        $nombre_parcours = mysql_num_rows($recherche_parcours);
                        for($i=0; $i<$nombre_parcours; $i++)
                        {
                            $id_parcours = mysql_result($recherche_parcours, $i, 'idPARCOURS');
                            $nom_parcours = mysql_result($recherche_parcours, $i, 'nom');
                         echo "<option value='$id_parcours'>$nom_parcours</option>";
                        }
                        ?>"
                    </select>

                   <label for="centre">Centre <span>*</span></label>
                   <select name="centre" id="centre" class="required" size="1">
                       <option></option>
                        <?php
                        $recherche_centres = mysql_query("SELECT idCENTRE, nom FROM centres ;");
                        $nombre_centres = mysql_num_rows($recherche_centres);
                        for($i=0; $i<$nombre_centres; $i++)
                        {
                            $id_centre = mysql_result($recherche_centres, $i, 'idCENTRE');
                            $nom_centre = mysql_result($recherche_centres, $i, 'nom');
                         echo "<option value='$id_centre' size='35'>$nom_centre</option>";
                        }
                        ?>
                    </select>
                   <br />
                   </fieldset>
                   <?php
                        }
                        ?>
                   <input type="submit" value="Valider" />                  
               </form>
<!-- -------------------------------------------------       Traitement du formulaire --------- ------------------------------------     --->
            <?php 
               // Vérifier si les variables $_GET et _POST nécessaires au traitement existent et ne sont pas vides //////////////////////////////
               if(!isset($_POST) OR !isset($_GET) OR empty($_GET) OR empty($_POST['id']))
               {
                   exit;
               }
               else {
               // stocker et protéger les données du formulaire
                   // mysql_real_escape() evite les injection sql
                   // strip_tags() évite le code PHP et HTML
                   // mb_strtoupper convertie la chaine en majuscules

               $id_utilisateur = mysql_real_escape_string(strip_tags($_POST['id']));
               $nom_utilisateur = mysql_real_escape_string(strip_tags(mb_strtoupper($_POST['nom'])));
               $prenom_utilisateur = mysql_real_escape_string(strip_tags($_POST['prenom']));
               $adresse_utilisateur = mysql_real_escape_string(strip_tags($_POST['adresse']));
               $cp_utilisateur = mysql_real_escape_string(strip_tags($_POST['cp']));
               $ville_utilisateur = mysql_real_escape_string(strip_tags($_POST['ville']));
               $pays_utilisateur = mysql_real_escape_string(strip_tags($_POST['pays']));
               $mail_utilisateur = mysql_real_escape_string(strip_tags($_POST['mail']));
               $phone_utilisateur = mysql_real_escape_string(strip_tags($_POST['phone']));

                // générer un mot de passe pour le nouvel utilisateur,
               // sachant qu'il peut le changer à tout moment une fois son compte est créé
               $mdp_utilisateur = substr(uniqid(),1 , 8); // génère un mot de passe aléatoire de 8 charactères
               $mdp_md5 = md5($mdp_utilisateur);  // le crypte en MD5 pour être ensuite stocké sur la bdd

               // message à envoyer au nouvel utilisateur
                $message = "Bonjour \n nous venons de vous créer un compte $_GET[type] sur le site Gestion de Note Emiage. \n\r Voici vos identifiants:
                       Nom d'utilisateur : $id_utilisateur
                       Mot de passe:  $mdp_utilisateur
                       URL du site: http://www.gdn-emiage.net
                       Remarque: Vous pouvez modier votre mot de passe à tout moment ainsi que les information contenues dans votre fiche utilisateur en vous connectant sur le site.  \n\r
                       Cordialement, l'équipe du site Gestion de Notes Emiage. ";
               }
               // déterminer le nom de la table à mettre à jours
               if($_GET['type']=='cf' OR $_GET['type']=='rp')
               {
                   $table = $_GET['type'];
               }
               else {
                   $table = $_GET['type'].'s';
               }
               // rechercher si l'identifiant est déjà attribué à un autre utilisateur ///////////////////////////////////////////////////////
               function verif_existance_id() {
                   global $id_utilisateur;
                   $recherche_idETUDIANT =mysql_query("SELECT idETUDIANT FROM etudiants WHERE idETUDIANT='$id_utilisateur';");
                   $recherche_idTUTEUR =mysql_query("SELECT idTUTEUR FROM tuteurs WHERE idTUTEUR='$id_utilisateur';");
                   $recherche_idCORRECTEUR =mysql_query("SELECT idCORRECTEUR FROM correcteurs WHERE idCORRECTEUR='$id_utilisateur';");
                   $recherche_idCF =mysql_query("SELECT idCF FROM cf WHERE idCF='$id_utilisateur';");
                   $recherche_idRP =mysql_query("SELECT idRP FROM rp WHERE idRP='$id_utilisateur';");
                   if(mysql_num_rows($recherche_idETUDIANT)!=0)
                   {
                       return true;
                   }
                   elseif(mysql_num_rows($recherche_idTUTEUR)!=0)
                   {
                       return true;
                   }
                   elseif(mysql_num_rows($recherche_idCORRECTEUR)!=0)
                   {
                       return true;
                   }
                   elseif(mysql_num_rows($recherche_idCF)!=0)
                   {
                       return true;
                   }
                   elseif(mysql_num_rows($recherche_idRP)!=0)
                   {
                       return true;
                   }
                   else {
                       return false;
                   }
               }
               verif_existance_id();  // excuter la fonction de vérification d'existance de l'id

               /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
               if($_GET['type']=='etudiant')  // dans le cas ou l'utilisateur à ajouter est un étudiant
               {
                   // définir les variables propres à la table etudiants
                    $formation = mysql_real_escape_string(strip_tags($_POST['formation']));
                    $parcours = mysql_real_escape_string(strip_tags($_POST['parcours']));
                    $centre = mysql_real_escape_string(strip_tags($_POST['centre']));
                    // si tous les champs obligatoires ne sont pas complétés
                    if(empty($centre) OR empty($parcours) OR empty($id_utilisateur) OR empty($nom_utilisateur) OR empty($prenom_utilisateur) OR empty( $mail_utilisateur ))
                   {
                       echo "<div class='message'>Les champs suivis d'une étoile rouge sont obligatoires. Patienter 5 secondes ou appuyez sur le lien suivant pour
                       <a href='ajout-utilisateur.php?type=etudiant&formation=$formation'>retourner au formulaire d'ajout d'utilisateur</a></div> ";
                       exit;
                   }
                   // si l'identifiant existe déjà dans la bdd
                   elseif(verif_existance_id()==true){
                       echo "<div class='message'>L'identifiant est déjà attribué à un autre utilisateur, veuillez choisir un autre identifiant. <br />
                        </div> ";
                       exit;
                   }
                   //  si tout est bon, procéder à la création du nouveau compte étudiant
                   else {
                       // enregistré le nouvel étudiant sur la bdd
                       mysql_query("INSERT INTO `etudiants` (`idETUDIANT`, `motPass`, `nom`, `prenom`, `adresse`, `codePostal`, `ville`,
                               `pays`, `phone`, `mail`, `centre`, `formation`, `parcours`, `photo`)
                               VALUES ('$id_utilisateur', '$mdp_md5', '$nom_utilisateur', '$prenom_utilisateur', ' $adresse_utilisateur ', ' $cp_utilisateur ', ' $ville_utilisateur ',
                               ' $pays_utilisateur ', ' $phone_utilisateur ', ' $mail_utilisateur ', '$centre', '$formation', '$parcours', '');" ) or die(mysql_error());
                      // envoyer un mail d'information (inclure son identifiant et son mdp)  pour le nouvel étudiant
                       mail(" $mail_utilisateur ", "Création d'un nouveau compte sur gdn-emiage.net", "$message");
                       echo "<div class='message'>un nouvel étudiant vient d'être ajouté<br/>
                                  Identifiant: $id_utilisateur <br />
                                  Nom: $nom_utilisateur <br />
                                  Prénom: $prenom_utilisateur <br />
                                  Email: $mail_utilisateur <br />
                                  Formation: $formation <br />
                                  Parcours: $parcours <br />
                        </div>";

                       exit;
                   }
               }
               /////////////////////////////////////////////////////////////////////////////////////////////////////////////
               // si l'utilisateur n'est pas un étudiant mais un des 4 autres type d'utilisateurs
               elseif($_GET['type']!='etudiant' AND $_GET['type']=='cf' OR $_GET['type']=='rp' OR $_GET['type']=='correcteur' OR $_GET['type']=='tuteur')
               {
                   // si tous les champs obligatoires ne sont pas completés
                   if(empty($id_utilisateur) OR empty($nom_utilisateur) OR empty($prenom_utilisateur) OR empty( $mail_utilisateur ))
                   {
                       echo "<div>Les champs suivis d'une étoile rouge sont obligatoires ! <a href='ajout-utilisateur.php?type='$_GET[type]''>Retourner au formulaire d'ajout d'utilisateur</a></div>";
                       exit;
                   }
                   // vérifier l'identifiant n'est pas déjà attribué
                   elseif(verif_existance_id()==true){
                       echo "<div class='message'>L'identifiant est déjà attribué à un autre utilisateur, veuillez choisir un autre identifiant</div>";
                       exit;
                   }
                   // si tous est bon, procéder à la création du nouveau compte
                   else{
                       mysql_query("INSERT INTO $table (`id$_GET[type]`, `nom`, `prenom`, `adresse`, `codePostal`, `ville`,`pays`, `phone`, `mail`, `motPass`, `photo`)
                                   VALUES ('$id_utilisateur', '$nom_utilisateur', '$prenom_utilisateur', ' $adresse_utilisateur ', ' $cp_utilisateur ',  ' $ville_utilisateur ', ' $pays_utilisateur ', ' $phone_utilisateur ', ' $mail_utilisateur ', '$mdp_md5', '');")
                                     or die(mysql_error());
                       // envoyer un mail d'information (inclure son identifiant et son mdp)  pour le nouvel utilisateurt
                       mail(" $mail_utilisateur ", "Création d'un nouveau compte sur gdn-emiage.net", "$message");
                       echo "<div class='message'>un $_GET[type] vient d'être ajouté. <br />
                                  Identifiant: $id_utilisateur <br />
                                  Nom: $nom_utilisateur <br />
                                  Prénom: $prenom_utilisateur <br />
                                  Email: $mail_utilisateur
                                     </div>";

                       exit;
                   }
               }
               /////////////////////////////////////////////////////////////////////////////////////////////
               else {  // quitter si le type d'utilisateur ne fait partie d'aucun des 5 types d'utilisateurs notre bdd
                   exit;
               }
               ?>
                <br />  
            </div>
            <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
</html>
