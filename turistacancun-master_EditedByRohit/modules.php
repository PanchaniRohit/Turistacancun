<?php
// para que importe las variables dadas en los parametros (como $name, &file, etc)
extract($_REQUEST, EXTR_OVERWRITE|EXTR_REFS, '');
global $prefix, $db, $name,$file;
if (!isset($name)){
	die('No puede acceder directo');
}

define('MODULE_FILE', true);
$module = 1;
$name = trim($name);
if ( ($file == null) || ($file == '') ){
	$file = 'index';
}
$dir = 'modules/' . $name . '/';
if(is_dir($dir)){
	require_once("mainfile.php");
	$result = $db->sql_query("SELECT active, view FROM ".$prefix."_modules WHERE title='$name'");
	$row = $db->sql_fetchrow($result);
	$mod_active = intval($row['active']);
	$view = intval($row['view']);
	if ($mod_active == 1) {
	
		$ThemeSel = get_theme();
			
		$modpath = "modules/$name/".$file.".php";
		if (file_exists($modpath)) {
			include($modpath);
		} else {
			include("header.php");
			OpenTable();
			echo "<center>El Archivo No existe<br><br>"
					.""._GOBACK."</center>";
			CloseTable();
			include("footer.php");
		}
	
	} else {
		include("header.php");
		OpenTable();
		echo "<center>"._MODULENOTACTIVE."<br><br>"
				.""._GOBACK."</center>";
		CloseTable();
		include("footer.php");
	}
}	else {
	header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
}
	
	


?>