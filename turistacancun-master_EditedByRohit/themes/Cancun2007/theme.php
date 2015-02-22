<?php

// THEME NAME: Cancun2007 for PHP-Nuke 5.5
// Copyright (c) 2001-2002 Somara Sem (http://www.pixelmayhem.com)


// Some theme color definitions
$bgcolor1 = "#ffffff";
$bgcolor2 = "#CCFFCC";
$bgcolor3 = "#FFFFCC";
$bgcolor4 = "#339999";
$textcolor1 = "#000000";
$textcolor2 = "#000000";

/************************************************************/

function Closediv()
{
echo "</div>\n";
}

function Opendivright(){
echo " <div id=\"divright\">\n";
}
function Opendivrightheader(){
echo " <div id=\"divrightheader\">\n";
}

function Opendivrightcontent(){
echo " <div id=\"divrightheader\">\n";
}

function OpenOneTable(){
echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"8\" ><tr><td>\n";
}

function CloseOneTable(){
echo "</td></tr></table>\n";
}

function OpenTable() {
    global $bgcolor1, $bgcolor2;
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$bgcolor2\"><tr><td>\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"8\" bgcolor=\"$bgcolor1\"><tr><td>\n";
}

function CloseTable() {
    echo "</td></tr></table></td></tr></table>\n";
}

function OpenTable2() {
    global $bgcolor1, $bgcolor2;
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>\n";
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"8\" bgcolor=\"$bgcolor1\"><tr><td>\n";
}

function CloseTable2() {
    echo "</td></tr></table></td></tr></table>\n";
}

/************************************************************/


function FormatStory($thetext, $notes, $aid, $informant) {
    global $anonymous;
    if ($notes != "") {
	$notes = "<br><br><b>"._NOTE."</b> <i>$notes</i>\n";
    } else {
	$notes = "";
    }
    if ("$aid" == "$informant") {
	echo "<font class=\"content\" color=\"#505050\">$thetext$notes</font>\n";
    } else {
	if($informant != "") {
	    $boxstuff = "<a href=\"user.php?op=userinfo&amp;uname=$informant\">$informant</a> ";
	} else {
	    $boxstuff = "$anonymous ";
	}
	$boxstuff .= ""._WRITES." <i>\"$thetext\"</i>$notes\n";
	echo "<font class=\"content\" color=\"#505050\">$boxstuff</font>\n";
    }
}

/************************************************************/


function themeheader() {
    global $user, $banners, $sitename, $slogan, $cookie,  $index, $name;
    cookiedecode($user);
    $username = $cookie[1];
    if ($username == "") {
        $username = "Anonymous";
    }
    echo "<!-- Copyright (c) Turista Cancun -->\n\n\n"
    ."<body bgcolor=\"#6699CC\" text=\"#000000\" link=\"#363636\" vlink=\"#363636\" alink=\"#d5ae83\">\n";
    if ($banners) {
	 $ads = ads(0);
    }
    $barrasuperior = Barra_Superior(); 
    $tmpl_file = "themes/Cancun2007/header.html";
    $thefile = implode("", file($tmpl_file));
    $thefile = addslashes($thefile);
    $thefile = "\$r_file=\"".$thefile."\";";
    eval($thefile);
    print $r_file;
   
	if ($index <> 3){
      $blocks = blocks(left);
      echo $blocks; 
      $tmpl_file = "themes/Cancun2007/left_center.html";
      $thefile = implode("", file($tmpl_file));
      $thefile = addslashes($thefile);
      $thefile = "\$r_file=\"".$thefile."\";";
      eval($thefile);
      print $r_file;
	}
	
	
	 
    
}

/************************************************************/

function Barra_Superior(){
	global $db,$prefix,$currentlang;
	$cuerda = "<A href=\"index.php\" class=\"topnav\">"._HOME."</a>\n"
	."&nbsp&nbsp;\n";
	// Hace Búsqueda y Desplegado de los Artículos en la Sección Fija 1
	 	  
	$cuerda .= "<a class=\"topnav\" href=\"modules.php?name=hotels\"><b>"._THOTELS."</A></b>&nbsp;&nbsp;";
	 
	$secid=1;  // Indica la Sección de Productos con Id 1
	$query = "select t1.artid, t2.title
from ".$prefix."_sections_articles as t1, ".$prefix."_sections_articles_titles as t2
where t1.secid = '1' and t1.artid = t2.artid and t2.language='".$currentlang."' order by t1.artid";
	
    $result = $db->sql_query($query);
    if ($result){
    	foreach ($result as $row){
    		$artid = $row[artid];
    		$title = $row[title];
    		$cuerda .= "
    		<a href=\"modules.php?name=Sections&amp;op=viewarticle&amp;artid=$artid\" class=\"topnav\">$title</a>
    		&nbsp;&nbsp;
    		";
    	} //while
    }
	
    return $cuerda;
}

function themefooter() {
    global $index , $prefix, $multilingual, $currentlang, $dbi;
    if ($index == 1) {
	echo "</td><td><img src=\"themes/Cancun2007/images/pixel.gif\" width=\"15\" height=\"1\" border=\"0\" alt=\"\"></td><td valign=\"top\" width=\"150\">\n";
	blocks(right);
    }
    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#ED1C24\" style=\"margin-top:10px\" align=\"center\">\n"
        ."<tr>\n"
        ."<td>\n"
		."<div><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#ED1C24\" align=\"center\">\n"
        ."<tr>\n"
        ."<td bgcolor=\"#999933\" align=\"center\" height=\"20\" width=\"100%\"><font class=\"topnav\"><B>\n";
      echo Barra_Superior(); 
        echo "</B></font>\n"
        ."</td>\n"
        ."</tr>\n"
        ."</table>\n"
		."</div>\n"
        ."</td>\n"
		."</tr>\n"
		."<tr>\n"
		."<td>"
		."<div style=\"background-color: white; padding-bottom: 20px;\">\n"
		
		."<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\">\n"
        ."<tr align=\"center\">\n"
        ."<td width=\"100%\" colspan=\"3\">\n";
    
    echo '
    		In this website you will find information about Cancun Hotels, Restaurants, Excursions and everytihing you need to have a perfect Cancun vacation. <br>
© 2007 by <a href="http://www.turista.com.mx">Turista.com.mx S.A. de C.V.</a> 
    		';    
        
        
    echo "</td>\n"
        ."</tr>\n"
		."</table>\n"
		."</div>\n"
		."<div style=\"float: left; width: 100%; height: 30px;\"\n>"
        ."<div style=\"float: left; width: 10%; background-color: white;\"\n>"
        ."<img align=\"left\" src=\"images/corner-bottom-left.gif\"\n>"
       	."</div>\n"
		."<div style=\"float: left; background-color: #ffffff; height: 30px; width: 80%;\">\n"
		."</div>\n"
		."<div style=\"float: left; width: 10%; background-color: white;\"\n>"
		."<img align=\"right\" src=\"images/corner-bottom-right.gif\">\n"
		."</div>\n"
   		."</div>\n"
		."</td>\n"
        ."</tr>\n"
		."</table>\n"
		;
}



function themefooter_Old() {
    global $index , $prefix, $multilingual, $currentlang, $dbi;
    if ($index == 1) {
	echo "</td><td><img src=\"themes/Cancun2007/images/pixel.gif\" width=\"15\" height=\"1\" border=\"0\" alt=\"\"></td><td valign=\"top\" width=\"150\">\n";
	blocks(right);
    }
    echo "</td><td><img src=\"themes/Cancun2007/images/pixel.gif\" width=10 height=1 border=0 alt=\"\">\n"
	."</td></tr></table>\n"

        ."<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#ED1C24\" align=\"center\">\n"
        ."<tr valign=\"top\">\n"
        ."<td bgcolor=\"#000000\"><img src=\"themes/Cancun2007/images/pixel.gif\" width=\"1\" height=\"1\"></td>\n"
        ."</tr>\n"
        ."<td bgcolor=\"#999933\" align=\"center\" height=\"20\" width=\"70%\"><font class=\"topnav\"><B>\n";
      echo Barra_Superior(); 
        echo "</B></font>\n"
        ."</td>\n"
		
        ."<tr valign=\"top\">\n"
        ."<td bgcolor=\"#000000\"><img src=\"themes/Cancun2007/images/pixel.gif\" width=\"1\" height=\"1\"></td>\n"
        ."</tr>\n"
        ."</table>\n"

        ."<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#ffffff\" align=\"center\">\n"
        ."<tr align=\"center\">\n"
        ."<td width=\"100%\" colspan=\"3\">\n";
    
    echo '
    		In this website you will find information about Cancun Hotels, Restaurants, Excursions and everytihing you need to have a perfect Cancun vacation. <br>
© 2007 by <a href="http://www.turista.com.mx">Turista.com.mx S.A. de C.V.</a> 
    		';    
        
        
    echo "</td>\n"
        ."</tr><tr>\n"
        ."<td><IMG height=\"30\" alt=\"\" hspace=\"0\" src=\"themes/Cancun2007/images/corner-bottom-left.gif\" width=\"30\" align=\"left\"></td>\n"
        ."<td width=\"100%\">&nbsp;</td>\n"
        ."<td><IMG height=\"30\" alt=\"\" hspace=\"0\" src=\"themes/Cancun2007/images/corner-bottom-right.gif\" width=\"30\" align=\"right\"></td>\n"
        ."</tr></table>\n";
}

/************************************************************/


function themeindex ($aid, $informant, $time, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext) {
    global $anonymous, $tipath;
    echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
    ."<tr>\n"
    ."<td align=\"left\" valign=\"top\" width=\"16\" height=\"29\"><img src=\"themes/Cancun2007/images/sidebox-title-left.gif\" width=\"16\" height=\"29\"></td>\n"
    ."<td align=\"left\" valign=\"middle\" bgcolor=\"#339999\" width=\"100%\"><font class=\"sideboxtitle\">&nbsp;<b>$title</b></font></td>\n"
    ."<td align=\"left\" valign=\"top\" width=\"13\" height=\"29\"><img src=\"themes/Cancun2007/images/sidebox-title-right.gif\" width=\"13\" height=\"29\"></td>\n"
    ."</tr>\n"
    ."</table>\n"

    ."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
    ."<tr>\n"
    ."<td align=\"left\" valign=\"top\" width=\"5\" background=\"themes/Cancun2007/images/sidebox-content-left.gif\"><img src=\"themes/Cancun2007/images/sidebox-content-left.gif\" width=\"5\"></td>\n"
    ."<td align=\"left\" valign=\"top\" bgcolor=\"#ffffff\" width=\"1280\">\n"

    ."<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">\n"
    ."<tr>\n"
    ."<td>\n"
	."<font color=\"#999999\"><b><a href=\"modules.php?name=News&amp;new_topic=$topic\"><img src=\"images/topics/$topicimage\" border=\"0\" Alt=\"$topictext\" align=\"right\" hspace=\"10\" vspace=\"10\"></a></B></font>\n";
    FormatStory($thetext, $notes, $aid, $informant);
    echo "</td>\n"
    ."</tr>\n"
    ."<tr>\n"
    ."<td bgcolor=\"#FFFFCC\" align=\"center\">\n"
	.""._POSTEDON." ";
    //formatAidHeader($aid);
    echo " $time $timezone ($counter "._READS.")<br>\n"
	."$morelink\n"
	."</td>\n"
	."</tr>\n"
    ."</table>\n"


    ."</td>\n"
    ."<td align=\"right\" valign=\"top\" width=\"8\" background=\"themes/Cancun2007/images/sidebox-content-right.gif\"><img src=\"themes/Cancun2007/images/sidebox-content-right.gif\" width=\"8\"></td>\n"
    ."</tr>\n"
    ."</table>\n"


    ."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
    ."<tr>\n"
    ."<td width=\"17\" height=\"14\"><img src=\"themes/Cancun2007/images/storybox-bottom-left.gif\" width=\"17\" height=\"14\"></td>\n"
    ."<td width=\"1280\" height=\"14\" background=\"themes/Cancun2007/images/storybox-bottom-middle.gif\" width=\"3\" height=\"14\"><img src=\"themes/Cancun2007/images/storybox-bottom-middle.gif\" width=\"3\" height=\"14\"></td>\n"
    ."<td align=\"right\" valign=\"top\" width=\"17\" height=\"14\"><img src=\"themes/Cancun2007/images/storybox-bottom-right.gif\" width=\"17\" height=\"14\"></td>\n"
    ."</tr>\n"
    ."</table><br>\n";


}

/************************************************************/


function themearticle ($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext) {
    global $admin, $sid, $tipath;

// Read More Title Area
    echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ffffff\" width=\"100%\">\n"
        ."<tr>\n"
        ."<td>\n"
        ."<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"#000000\" width=\"100%\">\n"
        ."<tr>\n"
        ."<td>\n"
        ."<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bgcolor=\"#339999\" width=\"100%\">\n"
        ."<tr>\n"
        ."<td align=\"left\">\n"
        ."<font class=\"title\" color=\"#363636\"><b>$title</b></font><br>\n"
        ."<font class=\"posted\">"._POSTEDON." $datetime "._BY." ";
    formatAidHeader($aid);
    if (is_admin($admin)) {
	echo "<br>[ <a class=\"topnav\" href=\"admin.php?op=EditStory&amp;sid=$sid\">"._EDIT."</a> | <a class=\"topnav\" href=\"admin.php?op=RemoveStory&amp;sid=$sid\">"._DELETE."</a> ]\n";
    }
    echo "</td>\n"
        ."</tr>\n"
        ."</table>\n"
        ."</td>\n"
        ."</tr>\n"
        ."</table>\n"

        ."</td>\n"
        ."</tr>\n"
        ."</table>\n\n\n"

// Read More Content Area
        ."<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bgcolor=\"#ffffff\" width=\"100%\">\n"
        ."<tr>\n"
        ."<td>\n";
    echo "<a class=\"topnav\" href=\"modules.php?name=News&amp;new_topic=$topic\"><img src=\"images/topics/$topicimage\" border=\"0\" Alt=\"$topictext\" align=\"right\" hspace=\"10\" vspace=\"10\"></a>\n";
    FormatStory($thetext, $notes="", $aid, $informant);
    echo "</td>\n"
        ."</tr>\n"
        ."</table><br>\n\n\n";
}

/************************************************************/


function themesidebox($title, $content) {
    if (substr($title,0,1) == "_"){ 
       $title= constant($title); 
    }
     $tmpl_file = "themes/Cancun2007/blocks.html";
    $thefile = implode("", file($tmpl_file));
    $thefile = addslashes($thefile);
    $thefile = "\$r_file=\"".$thefile."\";";
    eval($thefile);
    print $r_file;
    

}

?>