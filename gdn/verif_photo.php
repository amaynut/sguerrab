<?php
            session_start();
            if(empty($_SESSION))
            {
                header( "Location:index.php" );
            }
            require 'connexion_bdd.php';
              $type = $_SESSION['type'];
                $id = $_SESSION['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="styles/style.css" id="bleu" />
<script type ="text/javascript" src="js/jquery-1.4.2.js"></script>
<link rel="shortcut icon" href="images/favicon32.ico"/>
<title>Gestion de Notes e-Miage: interface Coordinateur de Formation (CF)</title>

</head>

<body>
            <div id="banniere">
                <img src="images/en-tete.jpg" alt="bannière du site"/>
            </div>
            <div id="corps">
                <?php include "menus/menu-$type.php"?>
<?php
                                                    
                       $redirection =  '<script language="javascript"> <!--
                                setTimeout(\'window.location="upload-photo.php"\', 4000);
                		// --> </script>'; // redirection en cas d'erreur
			// ParamÃ¨tres de la photo
			$target     = 'photos-utilisateurs/';  // Repertoire cible	
                        $ext_valide= array('jpg', 'JPG', 'png', 'PNG', 'gif', 'GIF'); // Extensions autorisées
			$max_size   = 100000;     // Taille max en octets du fichier
			$width_max  = 92;        // Largeur max de l'image en pixels
			$height_max = 102;        // Hauteur max de l'image en pixels
			
			//  Variables liÃ©es au fichier
			$nom_fichier   = $_FILES['fichier']['name'];
                        $ext_fichier = substr($nom_fichier, -3);
			$taille     = $_FILES['fichier']['size'];
			$tmp        = $_FILES['fichier']['tmp_name']; 
			
			if(!empty($_POST['posted'])) 
					{
    				
						// On vÃ©rifie si le champ est rempli
    					if(!empty($_FILES['fichier']['name'])) 
						{
        			
							// On vÃ©rifie l'extension du fichier
        					if(in_array($ext_fichier, $ext_valide))
							{
								// On rÃ©cupÃ¨re les dimensions du fichier
            					$infos_img = getimagesize($_FILES['fichier']['tmp_name']);
            
            					// On vÃ©rifie les dimensions et taille de l'image
            					if(($infos_img[0] <= $width_max) && ($infos_img[1] <= $height_max) && ($_FILES['fichier']['size'] <= $max_size)) 
								{                				
									// Si c'est OK, on teste l'upload
                					if(move_uploaded_file($_FILES['fichier']['tmp_name'],$target.$_FILES['fichier']['name'])) 						
									{
                    													
									// Si upload OK alors on affiche le message de rÃ©ussite et on insÃ¨re le lien dans la bdd, on commence par renommer le fichier
                   					$nouveaunomphoto = $target."photo-$id.$ext_fichier";
									rename("$target$nom_fichier", "$nouveaunomphoto");
					
									echo '<div class="message succes">Image uploadée avec succès !</div>';
							
									switch($type)
									{
                					case 'etudiant';
                    				echo '<script language="javascript"> <!--
              						setTimeout(\'window.location="etudiant.php"\', 3000);
                					// --> </script>';
                    				$requeteInsertion = mysql_query ("UPDATE etudiants SET photo = '$nouveaunomphoto' WHERE idETUDIANT = '$id'");
																		
																		
									break;
                 					
									case 'tuteur';
                    				echo '<script language="javascript"> <!--
              						setTimeout(\'window.location="tuteurs.php"\', 3000);
                					// --> </script>';
                    				$requeteInsertion = mysql_query ("UPDATE tuteurs SET photo = '$nouveaunomphoto' WHERE idTUTEUR = '$id'");
																		
																		
                    				break;
                 					
									case 'correcteur';
                    				echo '<script language="javascript"> <!--
              						setTimeout(\'window.location="correcteurs.php"\', 3000);
                					// --> </script>';
                    				$requeteInsertion = mysql_query ("UPDATE correcteurs SET photo = '$nouveaunomphoto' WHERE idCORRECTEUR = '$id'");
																		
																		
                    				break;
                 					
									case 'cf';
                   					echo '<script language="javascript"> <!--
              						setTimeout(\'window.location="cf.php"\', 3000);
                					// --> </script>';
                    				$requeteInsertion = mysql_query ("UPDATE cf SET photo = '$nouveaunomphoto' WHERE idCF = '$id'");
																		
																		
                    				break;
                 					
									case 'rp';
                    				echo '<script language="javascript"> <!--
              						setTimeout(\'window.location="rp.php"\', 3000);
                					// --> </script>';
                    				$requeteInsertion = mysql_query ("UPDATE rp SET photo = '$nouveaunomphoto' WHERE idRP = '$id'");
																		
																		
                    				break;
          							}				
                					} //fin if upload
									
                                else
                                {

                                // Sinon on affiche une erreur systÃ¨me
                        echo '<div class="message echec">Problème lors de l\'upload !', $_FILES['fichier']['error'], '</div>';
                                echo $redirection;

                }
                } //fin if taille image

                                else
                                {

                                // Sinon on affiche une erreur pour les dimensions et taille de l'image
                echo '<div class="message echec">Problème dans les dimensions ou la taille de l\'image !</div>';
                echo $redirection;
								
								}
        					} //fin if extension image
		
							else 
							{
            
							// Sinon on affiche une erreur pour l'extension
            				echo '<div class="message echec">Votre image ne comporte pas une extension autorisée (jpg, png ou gif) !<div>';
        					echo $redirection;
							
							}
    					} //fin if nom de l'image 
	
                    else
                    {
                    // Sinon on affiche une erreur pour le champ vide
            echo '<div class="message echec">Le champ du formulaire est vide !</div>';
                    echo $redirection;

            }

					}
			
?>
            </div>
     <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
</body>
                </html>