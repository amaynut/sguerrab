<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

	<head>
			<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			<title>Paysages de Kabylie - R&eacute;ltat du formulaire de contact</title>

			<!--   				declaration des styles 						-->
			<link rel="stylesheet" type="text/css" href="styles/cssJTL.css" media="screen" title="my favorite style"/>			

			<!--					fin de la declaration des styles 				-->
			<meta name="author" content="guerrab said"/> 
			<meta name="copyright" content="guerrab said, obtobre 2008"/> 
			<meta name="description" content="ce site est d�di� aux sites touristiques en kabylie, r�gion berb�rophone au nord de l'alg�rie" />
			<meta name="keywords" content="visiter l'afrique du nord, kabylie, alg�rie, maghreb, berb�rophonie, tourisme en kabylie, djurdjura, b�jaia" />
			<meta name="publisher" content="said guerrab" />
			<meta name="revisit-after" content="30 days" />
			<meta name="robots" content="all"/>
			<link rel="shortcut icon" href="/photos/favicon.ico" type="image/x-icon" />
			<meta name="verify-v1" content="Rkag5PgmbbuVKzEagHo2dhxDgogdIVY8Tl021/tGAhg=" />
			<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			var pageTracker = _gat._getTracker("UA-2307965-2");
			pageTracker._trackPageview();
			</script>

			<!-- correction des bugs d'internet exprolrer   d�but **********************************  -->
				<!--[if lt IE 8]>
				<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
				<![endif]-->
			<!-- correction des bugs d'internet explorer  fin *************************************** -->

		<style type="text/css">				    
		 #messageSended 
		 { 
		 	font-size: 150%;					
		 	border: 0.3em gold dotted;					
		 	text-align: center;					
		 	width: 50%;					
		 	padding:2em;					
		 	margin-top: 3em;					
		 	position: relative;					
		 	left: 25%;			
		 }			 								
		 </style>
	</head>
<body>

<?php
// r�cup�rer les variables d'environnement : Adresse Ip, URL de r�f�rence, navigateur web
	$ip = $_POST['ip'];
	$httpref = $_POST['httpref'];
	$httpagent = $_POST['httpagent'];

	$visitor = $_POST['visitor']; // r�cup�re le nom du visiteur
	$visitormail = $_POST['visitormail']; // r�cup�re email du visiteur
	$notes = $_POST['notes']; // r�cup�re le message du visiteur 
	$attn = $_POST['attn']; // le destinataire
    $to ="";
	switch($attn)
	{
		case "GUERRAB SAID": $to = "said.guerrab@gmail.com";
		break;
		case "Webmaster": $to = "said.guerrab@gmail.com" ;
		break;
        default: $to = "said.guerrab@gmail.com";
	}
	$subject = $_POST['subject']; // l'objet

	// validation du formulaire avant envoi
			// si les entr�es ne sont pas valides
	if(!$visitormail == "" && (!strstr($visitormail,"@") || !strstr($visitormail,".")))
	{
		echo "<h2>Retourner en arri&eacute;re  - Entrez une adresse email valide</h2>\n";
		$badinput = "<h2>Votre message n'a pas &eacute;t&eacute; envoy&eacute;</h2>\n";
		echo $badinput;
		die ("Retourner en arri&egrave;re");
	}
			// si l'un des champs est vide
	if(empty($visitor) || empty($visitormail) || empty($notes ))
	 {
		echo "<h2>Utilisez le bouton \" Page Pr&eacute;c&eacute;dente\" - et remplissez tous les champs du formulaire</h2>\n";
		die ("Retourner en arri&egrave;re");
	}
	//afficher la date en fran�ais -->
	$date1 = date("d/n/Y") ;
	$date2 = date("N");
	switch($date2)
	{
		case 1: $datedujour = "le lundi " . $date1;
		break;
		case 2: $datedujour = "le mardi " . $date1;
		break;
		case 3: $datedujour = "le mercredi " . $date1;
		break;
		case 4: $datedujour = "le jeudi " . $date1;
		break;
		case 5: $datedujour = "le vendredi " . $date1;
		break;
		case 6: $datedujour = "le samedi " . $date1;
		break;
		case 7: $datedujour = "le dimanche " . $date1;
		break;
	}	

     $notes = stripcslashes($notes);	// d�barasse le message des caract�res HTML et PHP
	$message = " $datedujour \n
	Attention: $attn \n
	Message: $notes \n
	From: $visitor ($visitormail)\n
	Additional Info : IP = $ip \n
	Browser Info: $httpagent \n
	Referral : $httpref \n
	";
	$from = "From: $visitormail\r\n";
	mail($to, $subject, $message, $from);
?>
<!-- le feedback, merci pour le message s'affiche ici -->

<div id="messageSended">
	<p>
		Date : <?php echo $datedujour ?>
	</p>
	<p> 
		Merci : <?php echo $visitor ?> ( <?php echo $visitormail ?> ) pour votre message.
	</p>
	<p> 
		A l'attention de : <?php echo $attn ?>
	</p>
	<p>Le contenu de votre message &eacute;t&eacute; :<br />
	<?php $notesout = str_replace("\r", "<br/>", $notes);
	echo $notesout; ?> </p>
	<p>Votre adresse IP est : <?php echo $ip ?></p>
	<a href="index.htm"> Retourner &agrave; la page d'accueil du site </a>

</div>
</body>
</html>