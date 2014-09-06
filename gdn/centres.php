<?php
            session_start();
            // si aucune session n'est ouverte, renvoyer l'utilisateur sur la page d'accès
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
class listeCentres extends Common
{
	var $Editor;

	function displayHtml()
	{
		?>
                    <div id="corps">
                        <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
                        
                            <div id="photo">
                            <strong >
                                Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; ?>
                            </strong>
                            </div>
                       
                        <h1>Gestion des Centres Emiage </h1>                      
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
		$tableColumns['idCENTRE'] = array('display_text' => 'Identifiant', 'req' =>'req','perms' => 'AETVQSXO');
		$tableColumns['nom'] = array('display_text' => 'Nom du Centre','req' =>'req', 'perms' => 'EVCTAXQSHO');
		$tableColumns['adresse'] = array('display_text' => 'Adresse', 'perms' => 'EVCAXQSHO',);
                $tableColumns['ville'] = array('display_text' => 'Ville', 'req' =>'req','perms' => 'TEVCAXQSHO',);
                $tableColumns['codePostal'] = array('display_text' => 'Code Postal', 'perms' => 'EVCAXQSHO',);
                $tableColumns['Pays'] = array('display_text' => 'Pays', 'perms' => 'EVCTAXQSHO',);
                $tableColumns['mail'] = array('display_text' => 'Email de contact', 'perms' => 'EVCAXQSHO',);
                $tableColumns['phone'] = array('display_text' => 'Tel', 'perms' => 'EVCAXQSHO',);

		$tableName = 'centres';
		$primaryCol = 'idCENTRE';
		$errorFun = array(&$this,'logError');
		$permissions = 'EUAVIDQSXHO';

		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="90%" class="mateTable"');
		$this->Editor->setConfig('orderByColumn','ville');
		$this->Editor->setConfig('addRowTitle','Ajouter un Centre Emiage');
		$this->Editor->setConfig('editRowTitle','Modfier un Centre Emiage');
                $this->Editor->setConfig('viewRowTitle','Informations sur le Centre');
		$this->Editor->setConfig('iconTitle','Op&eacute;ration');
                $this->Editor->setConfig('tableTitle','Liste des centres emiage');
	}


	function listeCentres()
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
            header('Content-Type: text/xls');
            header('Content-Disposition: attachment; filename="'.$this->Editor->tableName.'.xls"');
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
$lte = new listeCentres();
?>

