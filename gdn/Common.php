<?php
/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2008 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
class Common
{		
	// Mysql Variables
	var $mysqlUser = 'root';
	var $mysqlDb = 'subuntug_emiage';
	var $mysqlHost = 'localhost';
	var $mysqlDbPass = '';
	
	var $langVars;
	var $dbc;
        
	
	function mysqlConnect()
	{
                mysql_query("SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'");
                if(!mysql_select_db ($this->mysqlDb))
            
		if($this->dbc = mysql_connect($this->mysqlHost, $this->mysqlUser, $this->mysqlDbPass)) 
		{	
			if(!mysql_select_db ($this->mysqlDb))
			{
				$this->logError(sprintf($this->langVars->errNoSelect,$this->mysqlDb),__FILE__, __LINE__);
			}
		}
		else
		{
			$this->logError($this->langVars->errNoConnect,__FILE__, __LINE__);
		}
	}
	
	function logError($message, $file, $line)
	{
		$message = sprintf($this->langVars->errInScript,$file,$line,$message);
		var_dump($message);
		die;
	}


	function displayHeaderHtml()
	{
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <title>Gestion de Notes e-Miage</title>
                <link rel="shortcut icon" href="images/favicon32.ico"/>

			<link href="css/table_styles.css" rel="stylesheet" type="text/css" />
			<link href="css/icon_styles.css" rel="stylesheet" type="text/css" />
                        <link href="styles/style-page-donnees.css" rel="stylesheet" type="text/css" />

                        <script type="text/javascript" src="js/jquery-1.4.2.js"></script>
                         <script type="text/javascript" src="js/jquery-validate.js"></script>
			<script type="text/javascript" src="js/prototype.js"></script>
			<script type="text/javascript" src="js/scriptaculous-js/scriptaculous.js"></script>
			<script type="text/javascript" src="js/lang/lang_vars-fr.js"></script>
			<script type="text/javascript" src="js/ajax_table_editor.js"></script>
			
		</head>	
		<body>                
                <div id="banniere">
                    <img src="images/en-tete.jpg" alt="banniere du site"/>
                </div>
                 
		<?php
	}	
	
	function displayFooterHtml()
	{
		?>
                   
            <div id="pied">
                <img src="images/pied_page.jpg" alt="pied de page"/>
            </div>
		</body>
		</html>
		<?php
	}	

}
?>
