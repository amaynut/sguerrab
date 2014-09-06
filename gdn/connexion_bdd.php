<?php
$link = mysql_connect('amaynut.ipagemysql.com', 'amaynut', 'emiage');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(emiage);


