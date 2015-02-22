<?php 
/************************************************************************/
/* TURISTA-NUKE: Advanced Content Management System                     */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2012 by Enrique Montes                                 */
/* http://turista.com.mx                                                */
/*                                                                      */
/************************************************************************/

require_once 'mainfile.php';
global $prefix, $db, $dbhot;

//define esta constante para que por seguridad luego no puedan acceder a los phps de los mÛdulos
$modpath = '';
define('MODULE_FILE', true);
$_SERVER['SCRIPT_NAME'] = "modules.php";

$name = 'TuristaCancun';
$file = 'index';
$home = 1;

$ThemeSel = get_theme();

$modpath .= "modules/$name/".$file.".php";
if (file_exists($modpath)) {
	include($modpath);
} else {
	die('Problema con el homepage');
}

