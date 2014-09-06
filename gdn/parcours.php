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
class listeParcours extends Common
{
	var $Editor;

	function displayHtml()
	{
		?>
                    <div id="corps">
                         <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
                         <div id="photo">
                        <strong style="margin-bottom:1em; display: block">
                       
                        Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; ?>
                        </strong><br/>
                        </div>
                        
                        <h1>Gestion des Parcours </h1>
                        
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
                                var ajaxUrl = '<?php echo $_SERVER['PHP_SELF'] ?>';
				toAjaxTableEditor('update_html','');
			</script>
                        </div>
		<?php
	}

	function initiateEditor()
	{
		$tableColumns['idPARCOURS'] = array('display_text' => 'Identifiant', 'req' =>'req','perms' => 'AETVQSXO');
		$tableColumns['nom'] = array('display_text' => 'Nom du Parcours', 'req' =>'req','perms' => 'AEVCTAXQSHO');
                $tableColumns['formation'] = array('display_text' => 'Formation',
                 'req' =>'req', 'perms' => 'AEVCTAXQ',
                 'join' => array(
                     'table' =>'formations',
                     'column' => 'idFORMATION'
                     )
                    );
                $tableColumns['modulesoptionnels'] = array('display_text' => 'Nombre de modules optionnels', 'req' =>'req','perms' => 'AEVCTAXQSHO');
                $tableColumns['moduleslibres'] = array('display_text' => 'Nombre de modules libres', 'req' =>'req','perms' => 'AEVCTAXQSHO');

		$tableName = 'parcours';
		$primaryCol = 'idPARCOURS';
		$errorFun = array(&$this,'logError');
		$permissions = 'AEVIDQSXHO';

		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="90%" class="mateTable"');
		$this->Editor->setConfig('orderByColumn','formation');
		$this->Editor->setConfig('addRowTitle','Ajouter un Parcours');
		$this->Editor->setConfig('editRowTitle','Modifier le Parcours');
		$this->Editor->setConfig('iconTitle','Op&eacute;ration');
                $this->Editor->setConfig('viewRowTitle','Informations sur le Parcours');
	}


	function listeParcours()
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
$lte = new listeParcours();
?>

