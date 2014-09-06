<!--  Lien pour le menu multi niveaux -->
<link rel="stylesheet" type="text/css" href="css/superfish.css" media="screen"/>
<script type="text/javascript" src="js/hoverIntent.js"></script>
<script type="text/javascript" src="js/superfish.js"></script>
<script type="text/javascript">
		// initialise les plugins pour le menu multi niveaux
		jQuery(function(){
			jQuery('ul.sf-menu').superfish();
		});
		</script>
<!-- -->
<ul class="sf-menu">
                    <li><a href="cf.php" >Ma Fiche</a></li>
                    <li><a>Gestion des formations</a>
                        <ul>
                            <li><a>Formations</a>
                                <ul>
                                    <li><a href="formations.php">Liste des Formations</a></li>
                                    <li><a href="formations-par-centre.php">Formations par Centre</a></li>
                                </ul>
                            </li>
                            <li><a href="parcours.php">Parcours</a></li>
                            <li><a>Modules</a>
                                <ul>
                                    <li><a href="modules.php">Liste des Modules</a></li>
                                    <li><a href="modules-par-formation.php">Modules par Formation</a></li>
                                     <li><a href="modules-par-parcours.php">Modules par Parcours</a></li>
                                </ul>
                           </li>
                            <li><a href="centres.php">Centres</a></li>
                        </ul>
                    </li>
                    <li><a>Gestion des notes</a>
                        <ul>
                            <li><a>Gestion des examens</a>
                                <ul>
                                    <li><a href="gestion-notes-examens.php">Notes des examens</a></li>
                                    <li><a href="examens.php">Liste des examens</a></li>
                                </ul>
                            </li>
                            <li><a>Gestion des devoirs</a>
                                <ul>
                                    <li><a href="gestion-notes-devoirs.php">Notes des devoirs</a></li>
                                    <li><a href="devoirs.php">Liste des devoirs</a></li>
                                </ul>
                            </li>
                        	
                        </ul>
                    </li>
                    <li><a>Gestion des utilisateurs</a>
                        <ul>
                            <li><a href="gestion-etudiants.php">Etudiants</a></li>
                            <li><a href="gestion-correcteurs.php">Correcteurs</a></li>
                            <li><a href="gestion-tuteurs.php">Tuteurs</a></li>
                            <li><a href="gestion-cf.php">Coordinateurs de Formations</a></li>
                            <li><a href="gestion-rp.php">Responsables P&eacute;dagogiques</a></li>
                        </ul>
                    </li>
                    <li><a>R&eacute;sultats</a>
                        <ul>
                            <li><a href="moyennes-par-module.php">R&eacute;sultats par module</a></li>
                            <li><a href="resultats.php">R&eacute;sultats par formation</a></li>                            
                            <li><a href="etudiants-diplomes.php">Liste des dipl&ocirc;m&eacute;s</a></li>                     
                        </ul>
                    </li>
                    <li><a>Statistiques</a>
                        <ul>
                            <li><a href="statistiques-notes.php">Statistiques des r&eacute;sultats</a></li>
                            <li><a href="statistiques-etudiants.php">Statistiques des &eacute;tudiants</a></li>
                        </ul>
                    </li>
                    <li><a href="index.php?action=logout" title="se d&eacute;connecter">Quitter</a></li>
</ul>
