<?php
            session_start();
          if($_SESSION['type']!='cf' AND $_SESSION['type']!='rp')
            {
                header( "Location:index.php" );
            }
            require_once 'connexion_bdd.php';
            $id = $_SESSION['id'];
?>
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
require_once('Common.php');
require_once('php/lang/LangVars-fr.php');
require_once('php/AjaxTableEditor.php');
class listeRp extends Common
{
	var $Editor;

	function displayHtml()
	{
		?>
                    <div id="corps">
                        <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
                        
                        <div id="photo">
                        <strong>Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom'];?>
                        </strong>
                        </div>
                        
                       <h1>Gestion des Responsables P&eacute;dagogiques</h1>
                        <button style="display:block" onclick="self.location.href='ajouter-utilisateur.php?type=rp'"> Ajouter un responsable p&eacute;dagogique
                           </button>
			<div align="left" style="position: relative;"><div id="ajaxLoader1"><img src="images/ajax_loader.gif" alt="Loading..." /></div></div>

			<br />

			<div id="historyButtonsLayer" align="left">
			</div>

			<div id="historyContainer">
				<div id="information">
				</div>

				<div id="titleLayer" style="padding: 2px; font-weight: bold; font-size: 150%; text-align: center;">
				</div>

				<div id="tableLayer" align="center">
				</div>

				<div id="recordLayer" align="center">
				</div>

				<div id="searchButtonsLayer" align="center">
				</div>
			</div>

			<script type="text/javascript">
				trackHistory = false;
                                var ajaxUrl = '<?php echo $_SERVER['PHP_SELF']; ?>';
				toAjaxTableEditor('update_html','');
			</script>
                        </div>
		<?php
	}

	function initiateEditor()
	{
		$tableColumns['idRP'] = array('display_text' => 'Identifiant', 'perms' => 'TVQSXO');
		$tableColumns['nom'] = array('display_text' => 'Nom', 'perms' => 'EVCTAXQSHO');
		$tableColumns['prenom'] = array('display_text' => 'Pr&eacute;nom', 'perms' => 'EVCTAXQSHO');
		$tableColumns['mail'] = array('display_text' => 'mail', 'perms' => 'EVCAXQSHO');
                $tableColumns['phone'] = array('display_text' => 'T&eacute;l&eacute;phone', 'perms' => 'EVCAXQSHO');
                $tableColumns['adresse'] = array('display_text' => 'Adresse', 'perms' => 'EVCAXQSHO');
                $tableColumns['codePostal'] = array('display_text' => 'Code Postal', 'perms' => 'EVCAXQSHO');
                $tableColumns['pays'] = array('display_text' => 'Pays', 'perms' => 'EVCAXQSHO');

                $tableName = 'rp';
		$primaryCol = 'idRP';
		$errorFun = array(&$this,'logError');
		$permissions = 'UVIDQSXHO';


		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="90%" class="mateTable"');
		$this->Editor->setConfig('orderByColumn','nom');
		$this->Editor->setConfig('iconTitle','Op&eacute;ration');
	}


	function listeRp()
	{
		if(isset($_POST['json']))
		{	
			// Initiating lang vars here is only necessary for the logError, and mysqlConnect functions in Common.php.
			// If you are not using Common.php or you are using your own functions you can remove the following line of code.
			$this->langVars = new LangVars();
			$this->mysqlConnect();
			if(ini_get('magic_quotes_gpc'))
			{
				$_POST['json'] = stripslashes($_POST['json']);
			}
			if(function_exists('json_decode'))
			{
				$data = json_decode($_POST['json']);
			}
			else
			{
				require_once('php/JSON.php');
				$js = new Services_JSON();
				$data = $js->decode($_POST['json']);
			}
			if(empty($data->info) && strlen(trim($data->info)) == 0)
			{
				$data->info = '';
			}
			$this->initiateEditor();
			$this->Editor->main($data->action,$data->info);
			if(function_exists('json_encode'))
			{
				echo json_encode($this->Editor->retArr);
			}
			else
			{
				echo $js->encode($this->Editor->retArr);
			}
		}
		else if(isset($_GET['export']))
		{
            ob_start();
            $this->mysqlConnect();
            $this->initiateEditor();
            echo $this->Editor->exportInfo();
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-type: application/x-msexcel");
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$this->Editor->tableName.'.csv"');
            exit();
        }
		else
		{
			$this->displayHeaderHtml();
			$this->displayHtml();
			$this->displayFooterHtml();
		}
	}
}
$lte = new listeRp();
?>

