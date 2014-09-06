<?php
            session_start();
           if($_SESSION['type']!='cf' AND $_SESSION['type']!='rp' AND $_SESSION['type']!='correcteur')
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
 * 
 */
require_once('Common.php');
require_once('php/lang/LangVars-fr.php');
require_once('php/AjaxTableEditor.php');

class notesExamen extends Common
{
	var $Editor;
           // fonction de validation de la note
        function validerNote($id,$col,$row)
    {

            if($row['note'] <0 OR $row['note'] >20 ) // vérifier que le nombre entré se situ entre 0 et 20
            {
                $this->Editor->retArr[] = array(
                    'where' => 'javascript',
                    'value' => 'alert("La note doit etre comprise entre 0 et 20");');

                return false;
            }
            elseif(!is_numeric($row['note'])) // vérifier que la note est bien un nombre
            //(les virgules "," ne sont pas admises, il faut utiliser un "." à la place)
            {
                $this->Editor->retArr[] = array(
                    'where' => 'javascript',
                    'value' => 'alert("Veuillez entrer un nombre dans le champ NOTE. La virgule doit etre un point.");');

                return false;
            }
            else {
                return true;
            }
    }
	function displayHtml()
	{
		?>
                    <div id="corps">
                         
						 
						 <?php include "menus/menu.php" // choisi le menu de l'utilisateur selon son type'?>
                         
                        
                        <p> 
                            <div id="photo">
                            <strong>
                                Bienvenue <?php echo $_SESSION['prenom'] . " ". $_SESSION['nom'];?>
                            </strong>
                            </div> 
                        </p>
                           <h1>Notes de l'examen: </h1>
                           <?php 
                                $examen = mysql_query("SELECT * FROM examens WHERE idEXAMEN='$_POST[examen]'")OR die (mysql_error());
                                $resultat = mysql_fetch_assoc($examen);
                           ?>
                           <div style="text-align: center">
                               <p><strong>Module: </strong><?php echo $resultat['module']?> </p>
                               <p><strong>Session: </strong><?php echo htmlentities($resultat['session'])?>  </p>
                           </div>
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
                                var ajaxUrl = '<?php echo $_SERVER['PHP_SELF']."?examen=$_POST[examen]"; ?>';
				toAjaxTableEditor('update_html','');
			</script>
                        </div>
		<?php
	}

	function initiateEditor()
	{
		$tableColumns['idNOTE'] = array('display_text' => 'ID', 'perms' => '');
                 $tableColumns['etudiant'] = array(
                     'display_text' =>' ETUDIANT (NOM Prenom (identifiant) | Formation | Parcours)',
                     'perms' => 'AMEVCTAXQ',
                     'join' => array(
                         'table' =>'etudiants',
                         'column' => 'idETUDIANT',
                        'display_mask' => "concat(etudiants.nom,' ', etudiants.prenom,' (', etudiants.idETUDIANT,')',
                     ' | ', etudiants.formation, ' | ', etudiants.parcours)",'type' => 'left'),                                    
                     );

                 
		$tableColumns['examen'] = array(
                    'display_text' => 'EXAMEN',
                     'default' => "$_GET[examen]",
                    'perms' => 'AEDVCTAXQSHO',
                    'input_info'   => 'readonly="readonly"',
                     );
                                        
                $tableColumns['note'] = array(
                    'display_text' => 'NOTE',
                    'perms' => 'AEDTVCXQSHO',
                    'req' => 'req',
                    'val_fun' => array(&$this,'validerNote')
                    );
            
                $tableName = 'notesexamens';
		$primaryCol = 'idNOTE';
		$errorFun = array(&$this,'logError');
		$permissions = 'ADUEVIQSXHO';



		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="90%" class="mateTable"');
		$this->Editor->setConfig('orderByColumn','note');
		$this->Editor->setConfig('iconTitle','Op&eacute;ration');
                $this->Editor->setConfig('sqlFilters',"examen='$_GET[examen]'");
                $this->Editor->setConfig('editRowTitle',"Modifier la note");
                $this->Editor->setConfig('viewRowTitle',"Informations sur la note" );
                $this->Editor->setConfig('tableTitle','Notes des Etudiants');
	}


	function notesExamen()
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
$lte = new notesExamen();
?>

