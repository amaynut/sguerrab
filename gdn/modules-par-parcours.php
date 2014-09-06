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
 * 
 */
require_once('Common.php');
require_once('php/lang/LangVars-fr.php');
require_once('php/AjaxTableEditor.php');
class modulesParcours extends Common
{
	var $Editor;

	function displayHtml()
	{
		?>
                    <div id="corps">
                        <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
                        
                            <div id="photo">
                            <strong>
                                Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom'];?>
                            </strong>
                            </div>
                        
                        <h1>Modules par Parcours </h1>
                         <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" style="text-align:center;">
                    <label>Choisir une formation : <span>*</span></label>
                    <select name="formation">
                        <option></option>
                            <?php
                            $recherche_formations = mysql_query("SELECT * FROM formations ORDER BY `idFORMATION` ASC ;") OR die (mysql_error());
                            $nombre_formations = mysql_num_rows($recherche_formations);
                            for($i=0; $i<$nombre_formations; $i++)
                            {
                                $id_formation = mysql_result($recherche_formations, $i, 'idFORMATION');
                             echo "<option value='$id_formation'>$id_formation</option>";                             }
                            ?>
                        </select>   
                             <label>Choisir un parcours : <span>*</span></label>


                    <select name="parcours">
                            <option></option>
                            <?php
                            $formation = $_GET['formation'];
                            $recherche_parcours = mysql_query("SELECT * FROM parcours") OR die (mysql_error());
                            $nombre_parcours = mysql_num_rows($recherche_parcours);
                            for($i=0; $i<$nombre_parcours; $i++)
                            {
                                $id_parcours = mysql_result($recherche_parcours, $i, 'idPARCOURS');
                             echo "<option value='$id_parcours'>$id_parcours</option>";                             }
                            ?>
                        </select>
                       
                    <input type="submit" name="envoie" value="valider" />                       
                     </form>
                       


                         <?php if(isset ($_GET['formation']))
						 {
							 echo "<h2>Formation : ".$_GET['formation']."</h2>";
						 }?>
                        <?php if(isset ($_GET['parcours']))
						 {
							 echo "<h2>Parcours : ".$_GET['parcours']."</h2>";
						 }?>
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
                                var ajaxUrl = '<?php echo $_SERVER['PHP_SELF'].'?formation='.$_GET['formation'].'&parcours='.$_GET['parcours']; ?>';
				toAjaxTableEditor('update_html','');
			</script>
                        </div>
		<?php
	}

	function initiateEditor()
	{
		$tableColumns['idMODULESPARCOURS'] =
                    array(
                            'display_text' => 'Identifiant',
                            'perms' => ''
                            );

                $tableColumns['parcours'] =
                    array(
                            'display_text' => 'Parcours',
                            'perms' => 'EVTCAXQSHO',
                            'req' => 'req',
                          'join' =>
                    array(
                            'table' =>'parcours',
                            'column' => 'idPARCOURS',
                            'display_mask' => "concat(parcours.nom, ' (', parcours.formation, ')')",
                            'type' => 'left'
                        ),
                        );

		$tableColumns['module'] = 
                    array(
                            'display_text' => 'Module',
                            'perms' => 'EVCTAXQSHO',
                            'req' => 'req',
                            'join' =>
                    array(
                            'table' =>'modules',
                            'column' => 'idMODULE',
                            'display_mask' => "concat(modules.idMODULE,' (', modules.nom,')')",
                            'type' => 'left'
                        ),
                        );
             
                $tableColumns['statut'] =
                    array(
                            'display_text' => 'Statut du Module',
                            'perms' => 'EDVTCAXQSHO',
                             'req' => 'req',
                            'select_array' =>
                    array(
                            'obligatoire',
                            'optionnel',
                            'libre' 
                                )
                        );


                $tableName = 'modulesparcours';
		$primaryCol = 'idMODULESPARCOURS';
		$errorFun = array(&$this,'logError');
		$permissions = 'AEUVIDQSXHO';


		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="90%" class="mateTable"');
		$this->Editor->setConfig('orderByColumn','parcours');
		$this->Editor->setConfig('iconTitle','Op&eacute;ration');
                $this->Editor->setConfig('tableTitle','Liste des modules par parcours');
                $this->Editor->setConfig('sqlFilters',"formation ='$_GET[formation]' AND parcours='$_GET[parcours]'");

	}


	function modulesParcours()
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
$lte = new modulesParcours();
?>

