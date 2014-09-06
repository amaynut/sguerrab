<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Paysages de Kabylie- Formulaire de contact</title>
    <!--   				declaration des styles 						-->
    <link rel="stylesheet" type="text/css" href="styles/cssJTL.css" media="screen" title="my favorite style"/>
    <!--					fin de la declaration des styles 				-->
    <meta name="author" content="guerrab said"/>
    <meta name="copyright" content="guerrab said, obtobre 2008"/>
    <meta name="description"
          content="ce site est d�di� aux sites touristiques en kabylie, r�gion berb�rophone au nord de l'alg�rie"/>
    <meta name="keywords"
          content="visiter l'afrique du nord, kabylie, alg�rie, maghreb, berb�rophonie, tourisme en kabylie, djurdjura, b�jaia"/>
    <meta name="publisher" content="said guerrab"/>
    <meta name="revisit-after" content="30 days"/>
    <meta name="robots" content="all"/>
    <link rel="shortcut icon" href="/photos/favicon.ico" type="image/x-icon"/>
    <meta name="verify-v1" content="Rkag5PgmbbuVKzEagHo2dhxDgogdIVY8Tl021/tGAhg="/>

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
</head>

<body>
<a href="index.htm">
<div id="banner">
    <img src="./images/logodusite.jpg" alt="payages de kabylie"/>
</div>
</a>
<div id="formContact">
    <h1> Formulaire de contact</h1>

    <form method="post" action="sendeail.php">
        <?php
        $ipi = getenv("REMOTE_ADDR");
        $httprefi = getenv("HTTP_REFERER");
        $httpagenti = getenv("HTTP_USER_AGENT");
        ?>
        <fieldset>
            <input type="hidden" name="ip" value="<?php echo $ipi ?>"/>
            <input type="hidden" name="httpref" value="<?php echo $httprefi ?>"/>
            <input type="hidden" name="httpagent" value="<?php echo $httpagenti ?>"/>
            <label for="visitor"> Votre Nom :</label>
            <input type="text" name="visitor" size="35"/>
            <label for="visitormail">Votre Email :</label>
            <input type="text" name="visitormail" size="35"/>
            <label for="attn">Le destinataire :</label>
            <select name="attn" size="1">
                <option value="">Choisissez le destinateir</option>
                <option value="GUERRAB SAID">Sa&iuml;d GUERRAB</option>
                <option value="Webmaster">Webmaster</option>
            </select>
            <label for="subject"> Sujet : </label>
            <input type="text" name="subject"/>
            <label for="notes">Votre message :</label>
            <textarea name="notes" rows="10" cols="40"></textarea>
            <input type="submit" value="Envoyer le message"/>
        </fieldset>
    </form>
</div>
</body>
</html>











