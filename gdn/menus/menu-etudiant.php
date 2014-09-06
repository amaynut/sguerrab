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
        <li><a href="etudiant.php">Ma Fiche</a></li>
        <li><a href="mes-modules.php">Mes Modules</a></li>
        <li><a>Mes Notes</a>
            <ul>
                <li><a href="mes-notes-devoirs.php"> Mes notes de devoirs</a></li>
                <li><a href="mes-notes-examens.php"> Mes notes d'examens</a></li>
                <li><a href="etudiant-moyennes-modules.php"> Mes moyennes de modules</a></li>
            </ul>
        </li>
        <li><a href="resultats.php">Les Résultats par formation</a></li>
        <li><a href="index.php?action=logout" title="se déconnecter">Quitter</a></li>
     </ul>        