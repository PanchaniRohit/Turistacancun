<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (stristr(htmlentities($_SERVER['PHP_SELF']), "meta.php")) {
    Header("Location: ../index.php");
    die();
}

global $currentlang,$sitename,$slogan;  
##################################################
# Include for Meta Tags generation               #
##################################################

$metastring = "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset="._CHARSET."\">\n";
$metastring .= "<META HTTP-EQUIV=\"EXPIRES\" CONTENT=\"0\">\n";
$metastring .= "<META NAME=\"RESOURCE-TYPE\" CONTENT=\"DOCUMENT\">\n";
$metastring .= "<META NAME=\"DISTRIBUTION\" CONTENT=\"GLOBAL\">\n";
$metastring .= "<META NAME=\"AUTHOR\" CONTENT=\"$sitename\">\n";
$metastring .= "<META NAME=\"COPYRIGHT\" CONTENT=\"Copyright (c) by $sitename\">\n";


if (!$sloganaux)
  $metastring .= "<META NAME=\"DESCRIPTION\" CONTENT=\"$slogan\">\n";
else
  $metastring .= "<META NAME=\"DESCRIPTION\" CONTENT=\"$sloganaux\">\n";

if (!$subsmetas){
  if ($currentlang=='english')
    $metastring .= "<META NAME=\"KEYWORDS\" CONTENT=\"Cancun, cancun vacations, cancun hotels, great vacations, Cancun website, sports, family, fun, romance, adventure, relax, culture, business, shop\">\n";
  else
    $metastring .= "<META NAME=\"KEYWORDS\" CONTENT=\"Cancun, cancun vacaciones, cancun hoteles, Página de Cancún, deportes, familia, diversión, romance, aventura, relax, cultura, negocios, compras\">\n";
} else { //if $subsmetas
  $metastring .= "<META NAME=\"KEYWORDS\" CONTENT=\"$subsmetas\">\n";
} 


$metastring .= "<META NAME=\"ROBOTS\" CONTENT=\"INDEX, FOLLOW\">\n";
$metastring .= "<META NAME=\"REVISIT-AFTER\" CONTENT=\"1 DAYS\">\n";
$metastring .= "<META NAME=\"RATING\" CONTENT=\"GENERAL\">\n";

###############################################
# DO NOT REMOVE THE FOLLOWING COPYRIGHT LINE! #
# YOU'RE NOT ALLOWED TO REMOVE NOR EDIT THIS. #
###############################################

// IF YOU REALLY NEED TO REMOVE IT AND HAVE MY WRITTEN AUTHORIZATION CHECK: http://phpnuke.org/modules.php?name=Commercial_License
// PLAY FAIR AND SUPPORT THE DEVELOPMENT, PLEASE!
global $commercial_license;
if ($commercial_license != 1) {
	$metastring .= "<META NAME=\"GENERATOR\" CONTENT=\"PHP-Nuke Copyright (c) 2006 by Francisco Burzi. This is free software, and you may redistribute it under the GPL (http://phpnuke.org/files/gpl.txt). PHP-Nuke comes with absolutely no warranty, for details, see the license (http://phpnuke.org/files/gpl.txt).\">\n";
}


echo $metastring;

?>