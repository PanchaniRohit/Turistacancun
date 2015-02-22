<?php
$cpg_dir = "/home/cuntur01/public_html/gallery";
$urlmaingallery = "http://www.turistacancun.com/gallery";
$albums_dir = "$urlmaingallery/albums";

//include "$cpg_dir/include/config.inc.php";
include 'gallery/include/config.inc.php';
$dbgal = new sql_db($CONFIG['dbserver'], $CONFIG['dbuser'], $CONFIG['dbpass'], $CONFIG['dbname'], false);
$galprefix =  $CONFIG['TABLE_PREFIX'];

$urlfotos = $albums_dir;
$dirfotos="/home/cuntur01/public_html/gallery/albums";
$despliegaalbumeshermanos=1;
?>
