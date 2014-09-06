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
class ListeDevoirs extends Common
{
	var $Editor;

	function displayHtml()
	{
		?>
                    <div id="corps">
                        <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
                        
                            <div id="photo">
                            <strong>
                                Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom']; ?>
                            </strong>
                            </div>
                      
                        <h1>Liste des devoirs Emiage </h1>
                        <p>
                            <button onclick="self.location.href='creer-devoir.php'">
                                Ajouter un nouveau devoir
                           </button>
                         </p>
			<div align="left" style="position: relative;">
                            <div id="ajaxLoader1">
                                <img src="images/ajax_loader.gif" alt="Loading..." />
                            </div>
                        </div>

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
		$tableColumns['idDEVOIR'] = array(
                    'display_text' => 'ID',
                    'req' =>'req',
                    'perms' => 'T'
                    );
		$tableColumns['module'] = array(
                    'display_text' => 'MODULE',
                    'req' =>'req',
                    'perms' => 'VCTXQSHO'
                    );
                $tableColumns['numero'] = array(
                    'display_text' => 'NUMERO DU DEVOIR',
                    'perms' => 'TVCXQSHO',
                    );
                $tableColumns['nom'] = array(
                    'display_text' => 'NOM DU DEVOIR',
                    'req' =>'req',
                    'perms' => 'EVCTXQSHO'
                    );
		$tableColumns['session'] = array(
                    'display_text' => 'SESSION',
                    'perms' => 'TVCXQSHO',
                    );
                
                $tableColumns['date'] = array(
                    'display_text' => 'DATE',
                    'perms' => 'EVTCAXQSHO',
                    'req' => 'req'
                    );
                $tableColumns['tuteur'] = array(
                    'display_text' => 'TUTEUR',
                    'req' =>'req',
                    'perms' => 'TEVCXQSHO',
                     'join' => array(
                         'table' =>'tuteurs',
                         'column' => 'idTUTEUR',
                         'display_mask' => "concat(tuteurs.prenom,' ',tuteurs.nom)",
                         'type' => 'left'
                         ),
                    );

		$tableName = 'devoirs';
		$primaryCol = 'idDEVOIR';
		$errorFun = array(&$this,'logError');
		$permissions = 'EUVIDQSXHO';

		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="90%" class="mateTable"');
		$this->Editor->setConfig('orderByColumn','date');
                $this->Editor->setConfig('viewRowTitle','Informations sur le devoir');
		$this->Editor->setConfig('iconTitle','Op&eacute;ration');
                $this->Editor->setConfig('tableTitle','');
                $this->Editor->setConfig('ascOrDesc','desc');
	}


	function listeDevoirs()
	{
		if(isset($_POST['json']))
		{
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
$lte = new listeDevoirs();
?>


