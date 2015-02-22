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

include("mainfile.php");
include("includes/ipban.php");
global $prefix, $dbhot, $nukeurl;
header("Content-Type: text/xml");


echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n";
echo "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\"\n";
echo " \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\n\n";
echo "<rss version=\"0.91\">\n\n";
echo "<channel>\n";
echo "<title>".htmlentities($sitename)."</title>\n";
echo "<link>$nukeurl</link>\n";
echo "<description>".htmlentities($backend_title)."</description>\n";
echo "<language>$backend_language</language>\n\n";

// $result = $dbhot->sql_query('SELECT hotelid')

// foreach ($result as $row){
// 	$rsid = intval($row['sid']);
// 	$rtitle = filter($row['title'], "nohtml");
// 	$rtext = filter($row['hometext']);
// 	echo "<item>\n";
// 	echo "<title>".htmlentities($rtitle)."</title>\n";
// 	echo "<link>$nukeurl/modules.php?name=News&amp;file=article&amp;sid=$rsid</link>\n";
// 	echo "<description>".htmlentities($rtext)."</description>\n";
// 	echo "</item>\n\n";
// }
echo "</channel>\n";
echo "</rss>";

?>