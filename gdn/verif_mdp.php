<?php
session_start();

$mdp = $_SESSION['mdp'];
$ancien_mdp = MD5($_REQUEST['ancien_mdp']);

if($mdp!=$ancien_mdp)

    echo 'false';
else
    echo 'true';

?>
