<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// End the transaction
if(!defined('END_TRANSACTION')) {
  define('END_TRANSACTION', 2);
}

// Get php version
$phpver = phpversion();


// override old superglobals if php is higher then 4.1.0
if($phpver >= '4.1.0') {
  $HTTP_GET_VARS = $_GET;
  $HTTP_POST_VARS = $_POST;
  $HTTP_SERVER_VARS = $_SERVER;
  $HTTP_POST_FILES = $_FILES;
  $HTTP_ENV_VARS = $_ENV;
  $PHP_SELF = $_SERVER['PHP_SELF'];
  if(isset($_SESSION)) {
    $HTTP_SESSION_VARS = $_SESSION;
  }
  if(isset($_COOKIE)) {
    $HTTP_COOKIE_VARS= $_COOKIE;
  }
}

// After doing those superglobals we can now use one
// and check if this file isnt being accessed directly
if (stristr(htmlentities($_SERVER['PHP_SELF']), "mainfile.php")) {
    header("Location: index.php");
    exit();
}

if (!function_exists("floatval")) {
    function floatval($inputval) {
        return (float)$inputval;
    }
}


$phpver = explode(".", $phpver);
$phpver = "$phpver[0]$phpver[1]";
if ($phpver >= 41) {
	$PHP_SELF = $_SERVER['PHP_SELF'];
}

// para que importe las variables dadas en los parametros (como $name, &file, etc)
extract($_REQUEST, EXTR_OVERWRITE|EXTR_REFS, '');




if(!function_exists('stripos')) {
	function stripos_clone($haystack, $needle, $offset=0) {
		$return = strpos(strtoupper($haystack), strtoupper($needle), $offset);
		if ($return === false) {
			return false;
		} else {
			return true;
		}
	}
} else {
	// But when this is PHP5, we use the original function
	function stripos_clone($haystack, $needle, $offset=0) {
		$return = stripos($haystack, $needle, $offset=0);
		if ($return === false) {
			return false;
		} else {
			return true;
		}
	}
} 

// Additional security (Union, CLike, XSS)
if(isset($_SERVER['QUERY_STRING']) && (!stripos_clone($_SERVER['QUERY_STRING'], "ad_click"))) {
	$queryString = $_SERVER['QUERY_STRING'];
    if (stripos_clone($queryString,'%20union%20') OR stripos_clone($queryString,'/*') OR stripos_clone($queryString,'*/union/*') OR stripos_clone($queryString,'c2nyaxb0') OR stripos_clone($queryString,'+union+') OR (stripos_clone($queryString,'cmd=') AND !stripos_clone($queryString,'&cmd')) OR (stripos_clone($queryString,'exec') AND !stripos_clone($queryString,'execu')) OR stripos_clone($queryString,'concat')) {
    	die('Illegal Operation');
    }
}

$postString = "";
foreach ($_POST as $postkey => $postvalue) {
    if ($postString > "") {
     $postString .= "&".$postkey."=".$postvalue;
    } else {
     $postString .= $postkey."=".$postvalue;
    }
}
str_replace("%09", "%20", $postString);
$postString_64 = base64_decode($postString);

if (stripos_clone($postString,'%20union%20') OR stripos_clone($postString,'*/union/*') OR stripos_clone($postString,' union ') OR stripos_clone($postString_64,'%20union%20') OR stripos_clone($postString_64,'*/union/*') OR stripos_clone($postString_64,' union ') OR stripos_clone($postString_64,'+union+')) {
	header("Location: index.php");
	die();
}

if(isset($admin)) {
	$admin = base64_decode($admin);
	$admin = addslashes($admin);
	$admin = base64_encode($admin);
}

if(isset($user)) {
	$user = base64_decode($user);
	$user = addslashes($user);
	$user = base64_encode($user);
}

// Die message for not allowed HTML tags
$htmltags = "<center><img src=\"images/logo.gif\"><br><br><b>";
$htmltags .= "The html tags you attempted to use are not allowed</b><br><br>";
$htmltags .= "[ <a href=\"javascript:history.go(-1)\"><b>Go Back</b></a> ]";

// Die message for empty HTTP_REFERER
$posttags = "<b>Warning:</b> your browser doesn't send the HTTP_REFERER header to the website.<br>";
$posttags .= "This can be caused due to your browser, using a proxy server or your firewall.<br>";
$posttags .= "Please change browser or turn off the use of a proxy<br>";
$posttags .= "or turn off the 'Deny servers to trace web browsing' in your firewall<br>";
$posttags .= "and you shouldn't have problems when sending a POST on this website.";



// Posting from other servers in not allowed
// Fix by Quake
// Bug found by PeNdEjO

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_SERVER['HTTP_REFERER'])) {
    	if (!stripos_clone($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
        	die('Posting from another server not allowed!');
    	}
  	} else {
    	die($posttags);
  	}
}

// Define the INCLUDE PATH
if(defined('FORUM_ADMIN')) {
	define('INCLUDE_PATH', '../../../');
} elseif(defined('INSIDE_MOD')) {
	define('INCLUDE_PATH', '../../');
} else {
	define('INCLUDE_PATH', './');
}

// Include the required files
@require_once(INCLUDE_PATH."config.php");

if(!$dbname) {
    die("<br><br><center><img src=images/logo.gif><br><br><b>There seems that PHP-Nuke isn't installed yet.<br>(The values in config.php file are the default ones)<br><br>You can proceed with the <a href='./install/index.php'>web installation</a> now.</center></b>");
}

@require_once(INCLUDE_PATH."db/db.php");


// Error reporting, to be set in config.php
$display_errors = 1;
if($display_errors) {
	ini_set('display_errors', 1);
  	error_reporting(E_ALL^E_NOTICE);
} else {
  	ini_set('display_errors', 1);
  	error_reporting(1);
}

define('NUKE_FILE', true);

$result = $db->sql_query("SELECT * FROM ".$prefix."_config");
$row = $db->sql_fetchrow($result);
$sitename = filter($row['sitename'], "nohtml");
$nukeurl = filter($row['nukeurl'], "nohtml");
$site_logo = filter($row['site_logo'], "nohtml");
$slogan = filter($row['slogan'], "nohtml");
$startdate = filter($row['startdate'], "nohtml");
$adminmail = filter($row['adminmail'], "nohtml");
$anonpost = intval($row['anonpost']);
$Default_Theme = filter($row['Default_Theme'], "nohtml");
$foot1 = filter($row['foot1']);
$foot2 = filter($row['foot2']);
$foot3 = filteR($row['foot3']);
$commentlimit = intval($row['commentlimit']);
$anonymous = filter($row['anonymous'], "nohtml");
$minpass = intval($row['minpass']);
$pollcomm = intval($row['pollcomm']);
$articlecomm = intval($row['articlecomm']);
$broadcast_msg = intval($row['broadcast_msg']);
$my_headlines = intval($row['my_headlines']);
$top = intval($row['top']);
$storyhome = intval($row['storyhome']);
$user_news = intval($row['user_news']);
$oldnum = intval($row['oldnum']);
$ultramode = intval($row['ultramode']);
$banners = intval($row['banners']);
$backend_title = filter($row['backend_title'], "nohtml");
$backend_language = filter($row['backend_language'], "nohtml");
$language = filter($row['language'], "nohtml");
$locale = filter($row['locale'], "nohtml");
$multilingual = intval($row['multilingual']);
$useflags = intval($row['useflags']);
$notify = intval($row['notify']);
$notify_email = filter($row['notify_email'], "nohtml");
$notify_subject = filter($row['notify_subject'], "nohtml");
$notify_message = filter($row['notify_message'], "nohtml");
$notify_from = filter($row['notify_from'], "nohtml");
$moderate = intval($row['moderate']);
$admingraphic = intval($row['admingraphic']);
$httpref = intval($row['httpref']);
$httprefmax = intval($row['httprefmax']);
$CensorMode = intval($row['CensorMode']);
$CensorReplace = filter($row['CensorReplace'], "nohtml");
$copyright = filter($row['copyright']);
$Version_Num = filter($row['Version_Num'], "nohtml");
$domain = $nukeurl;
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$start_time = $mtime;

		

if (isset($newlang) AND !stripos_clone($newlang,".")) {
	if (file_exists("language/lang-".$newlang.".php")) {
		setcookie("lang",$newlang,time()+31536000);
		include_once("language/lang-".$newlang.".php");
		$currentlang = $newlang;
	} else {
		setcookie("lang",$language,time()+31536000);
		include_once("language/lang-".$language.".php");
		$currentlang = $language;
	}
} elseif (isset($lang)) {
	include_once("language/lang-".$lang.".php");
	$currentlang = $lang;
} else {
	setcookie("lang",$language,time()+31536000);
	include_once("language/lang-".$language.".php");
	$currentlang = $language;
}

function makePass() {
	$cons = "bcdfghjklmnpqrstvwxyz";
	$vocs = "aeiou";
	for ($x=0; $x < 6; $x++) {
		mt_srand ((double) microtime() * 1000000);
		$con[$x] = substr($cons, mt_rand(0, strlen($cons)-1), 1);
		$voc[$x] = substr($vocs, mt_rand(0, strlen($vocs)-1), 1);
	}
	mt_srand((double)microtime()*1000000);
	$num1 = mt_rand(0, 9);
	$num2 = mt_rand(0, 9);
	$makepass = $con[0] . $voc[0] .$con[2] . $num1 . $num2 . $con[3] . $voc[3] . $con[4];
	return($makepass);
}

function get_lang($module) {
	global $currentlang, $language;
	if (file_exists("modules/$module/language/lang-".$currentlang.".php")) {
		if ($module == "admin") {
			include_once("admin/language/lang-".$currentlang.".php");
		} else {
			include_once("modules/$module/language/lang-".$currentlang.".php");
		}
	} else {
		if ($module == "admin") {
			include_once("admin/language/lang-".$currentlang.".php");
		} else {
			include_once("modules/$module/language/lang-".$language.".php");
		}
	}
}

function is_admin($admin) {
    if (!$admin) { return 0; }
    if (isset($adminSave)) return $adminSave;
    if (!is_array($admin)) {
        $admin = base64_decode($admin);
        $admin = addslashes($admin);
        $admin = explode(":", $admin);
    }
    $aid = $admin[0];
    $pwd = $admin[1];
    $aid = substr(addslashes($aid), 0, 25);
    if (!empty($aid) && !empty($pwd)) {
        global $prefix, $db;
        $sql = "SELECT pwd FROM ".$prefix."_authors WHERE aid='$aid'";
        $result = $db->sql_query($sql);
        $pass = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);
        if ($pass[0] == $pwd && !empty($pass[0])) {
            static $adminSave;
        	return $adminSave = 1;
        }
    }
    static $adminSave;
    return $adminSave = 0;
}

function is_user($user) {
    if (!$user) { return 0; }
    if (isset($userSave)) return $userSave;
    if (!is_array($user)) {
        $user = base64_decode($user);
        $user = addslashes($user);
        $user = explode(":", $user);
    }
    $uid = $user[0];
    $pwd = $user[2];
    $uid = intval($uid);
    if (!empty($uid) AND !empty($pwd)) {
        global $db, $user_prefix;
        $sql = "SELECT user_password FROM ".$user_prefix."_users WHERE user_id='$uid'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);
        if ($row[0] == $pwd && !empty($row[0])) {
            static $userSave;
        	return $userSave = 1;
        }
    }
    static $userSave;
    return $userSave = 0;
}

function is_group($user, $name) {
          global $prefix, $db, $user_prefix, $cookie, $user;
     if (is_user($user)) {
          if(!is_array($user)) {
          $cookie = cookiedecode($user);
          $uid = intval($cookie[0]);
          } else {
          $uid = intval($user[0]);
          }
          $result = $db->sql_query("SELECT points FROM ".$user_prefix."_users WHERE user_id='$uid'");
          list($points) = $db->sql_fetchrow($result);
          $points = intval($points);
          $db->sql_freeresult($result);
          $result2 = $db->sql_query("SELECT mod_group FROM ".$prefix."_modules WHERE title='$name'");
          list($mod_group) = $db->sql_fetchrow($result2);
          $mod_group = intval($mod_group);
          $db->sql_freeresult($result2);
          $result3 = $db->sql_query("SELECT points FROM ".$prefix."_groups WHERE id='$mod_group'");
          list($rpoints) = $db->sql_fetchrow($result3);
          $grp = intval($rpoints);
          $db->sql_freeresult($result3);
          if (($points >= 0 AND $points >= $grp) OR $mod_group == 0) {
        	return 1;
          }
     }
     return 0;
}

function update_points($id) {
  global $user_prefix, $prefix, $db, $user;
  if (is_user($user)) {
    if(!is_array($user)) {
      $cookie = cookiedecode($user);
      $username = trim($cookie[1]);
    } else {
      $username = trim($user[1]);
    }
    if ($db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_groups")) > '0') {
      $id = intval($id);
      $result = $db->sql_query("SELECT points FROM ".$prefix."_groups_points WHERE id='$id'");
      list($points) = $db->sql_fetchrow($result);
      $db->sql_freeresult($result);
      $rpoints = intval($points);
      $db->sql_query("UPDATE ".$user_prefix."_users SET points=points+".$rpoints." WHERE username='$username'");
    }
  }
}

function title($text) {
	OpenTable();
	echo "<center><span class=\"title\"><strong>$text</strong></span></center>";
	CloseTable();
	echo "<br>";
}

function is_active($module) {
    global $prefix, $db;
    static $save;
    if (is_array($save)) {
        if (isset($save[$module])) return ($save[$module]);
        return 0;
    }
    $sql = "SELECT title FROM ".$prefix."_modules WHERE active=1";
    $result = $db->sql_query($sql);
    foreach ($result as $row){
        $save[$row[0]] = 1;
    }
    $db->sql_freeresult($result);
    if (isset($save[$module])) return ($save[$module]);
    return 0;
}

function render_blocks($side, $blockfile, $title, $content, $bid, $url) {
	if(!defined('BLOCK_FILE')) {
	  define('BLOCK_FILE', true);
	}
	if (empty($url)) {
		if (empty($blockfile)) {
			if ($side == "c") {
				themecenterbox($title, $content);
			} elseif ($side == "d") {
				themecenterbox($title, $content);
			} else {
				themesidebox($title, $content);
			}
		} else {
			if ($side == "c") {
				blockfileinc($title, $blockfile, 1);
			} elseif ($side == "d") {
				blockfileinc($title, $blockfile, 1);
			} else {
				blockfileinc($title, $blockfile);
			}
		}
	} else {
		if ($side == "c" OR $side == "d") {
			headlines($bid,1);
		} else {
			headlines($bid);
		}
	}
}

function blocks($side) {
	global $storynum, $prefix, $multilingual, $currentlang, $db, $admin, $user;
	if ($multilingual == 1) {
		$querylang = "AND (blanguage='$currentlang' OR blanguage='')";
	} else {
		$querylang = "";
	}
	if (strtolower($side[0]) == "l") {
		$pos = "l";
	} elseif (strtolower($side[0]) == "r") {
		$pos = "r";
	}  elseif (strtolower($side[0]) == "c") {
		$pos = "c";
	} elseif  (strtolower($side[0]) == "d") {
		$pos = "d";
	}
	$side = $pos;
	$sql = "SELECT bid, bkey, title, content, url, blockfile, view, expire, action, subscription FROM ".$prefix."_blocks WHERE bposition='$pos' AND active='1' $querylang ORDER BY weight ASC";
	$result = $db->sql_query($sql);
	foreach ($result as $row){
		$bid = intval($row['bid']);
		$title = filter($row['title'], "nohtml");
		$content = $row['content'];
		$url = filter($row['url'], "nohtml");
		$blockfile = filter($row['blockfile'], "nohtml");
		$view = intval($row['view']);
		$expire = intval($row['expire']);
		$action = filter($row['action'], "nohtml");
		$action = substr($action, 0,1);
		$now = time();
		$sub = intval($row['subscription']);
		if ($sub == 0 OR ($sub == 1 AND !paid())) {
			if ($expire != 0 AND $expire <= $now) {
				if ($action == "d") {
					$db->sql_query("UPDATE ".$prefix."_blocks SET active='0', expire='0' WHERE bid='$bid'");
					return;
				} elseif ($action == "r") {
					$db->sql_query("DELETE FROM ".$prefix."_blocks WHERE bid='$bid'");
					return;
				}
			}
			if ($row['bkey'] == "admin") {
				adminblock();
			} elseif ($row['bkey'] == "userbox") {
				userblock();
			} elseif (empty($row['bkey'])) {
				if ($view == 0) {
					render_blocks($side, $blockfile, $title, $content, $bid, $url);
				} elseif ($view == 1 AND is_user($user) || is_admin($admin)) {
					render_blocks($side, $blockfile, $title, $content, $bid, $url);
				} elseif ($view == 2 AND is_admin($admin)) {
					render_blocks($side, $blockfile, $title, $content, $bid, $url);
				} elseif ($view == 3 AND !is_user($user) || is_admin($admin)) {
					render_blocks($side, $blockfile, $title, $content, $bid, $url);
				}
			}
		}
	}
}


function online() {
  
}

function blockfileinc($title, $blockfile, $side=0) {
	$blockfiletitle = $title;
	$file = file_exists("blocks/".$blockfile."");
	if (!$file) {
		$content = _BLOCKPROBLEM;
	} else {
		include("blocks/".$blockfile."");
	}
	if (empty($content)) {
		$content = _BLOCKPROBLEM2;
	}
	if ($side == 1) {
		themecenterbox($blockfiletitle, $content);
	} elseif ($side == 2) {
		themecenterbox($blockfiletitle, $content);
	} else {
		themesidebox($blockfiletitle, $content);
	}
}

function selectlanguage() {
	global $useflags, $currentlang;
	if ($useflags == 1) {
		$title = _SELECTLANGUAGE;
		$content = "<center><font class=\"content\">"._SELECTGUILANG."<br><br>";
		$langdir = dir("language");
		while($func=$langdir->read()) {
			if(substr($func, 0, 5) == "lang-") {
				$menulist .= "$func ";
			}
		}
		closedir($langdir->handle);
		$menulist = explode(" ", $menulist);
		sort($menulist);
		for ($i=0; $i < sizeof($menulist); $i++) {
			if($menulist[$i]!="") {
				$tl = str_replace("lang-","",$menulist[$i]);
				$tl = str_replace(".php","",$tl);
				$altlang = ucfirst($tl);
				$content .= "<a href=\"index.php?newlang=".$tl."\"><img src=\"images/language/flag-".$tl.".png\" border=\"0\" alt=\"$altlang\" title=\"$altlang\" hspace=\"3\" vspace=\"3\"></a> ";
			}
		}
		$content .= "</font></center>";
		themesidebox($title, $content);
	} else {
		$title = _SELECTLANGUAGE;
		$content = "<center><font class=\"content\">"._SELECTGUILANG."<br><br></font>";
		$content .= "<form action=\"index.php\" method=\"get\"><select name=\"newlanguage\" onChange=\"top.location.href=this.options[this.selectedIndex].value\">";
		$handle=opendir('language');
		while ($file = readdir($handle)) {
			if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
				$langFound = $matches[1];
				$languageslist .= "$langFound ";
			}
		}
		closedir($handle);
		$languageslist = explode(" ", $languageslist);
		sort($languageslist);
		for ($i=0; $i < sizeof($languageslist); $i++) {
			if($languageslist[$i]!="") {
				$content .= "<option value=\"index.php?newlang=$languageslist[$i]\" ";
				if($languageslist[$i]==$currentlang) $content .= " selected";
				$content .= ">".ucfirst($languageslist[$i])."</option>\n";
			}
		}
		$content .= "</select></form></center>";
		themesidebox($title, $content);
	}
}



function cookiedecode($user) {
    global $cookie, $db, $user_prefix;
    static $pass;
    if(!is_array($user)) {
        $user = base64_decode($user);
        $user = addslashes($user);
        $cookie = explode(":", $user);
    } else {
        $cookie = $user;
    }
    if (!isset($pass)) {
       $sql = "SELECT user_password FROM ".$user_prefix."_users WHERE username='$cookie[1]'";
       $result = $db->sql_query($sql);
       list($pass) = $db->sql_fetchrow($result);
       //$db->sql_freeresult($result);
    }
    if ($cookie[2] == $pass && !empty($pass)) { return $cookie; }
}




/*********************************************************/
/* text filter                                           */
/*********************************************************/



function check_html ($str, $strip="") {
	$str = strip_tags($str);
	return $str;
}


function filter($what, $strip="", $save="", $type="") {
	$what = strip_tags($what);
	return($what);
}

/*********************************************************/
/* formatting stories                                    */
/*********************************************************/

function formatTimestamp($time) {
    global $datetime, $locale;
    setlocale(LC_TIME, $locale);
    if (!is_numeric($time)) {
        preg_match('/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/', $time, $datetime);
        $time = gmmktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
    }
    $time -= date("Z");
    $datetime = strftime(_DATESTRING, $time);
    $datetime = ucfirst($datetime);
    return $datetime;
}

function get_author($aid) {
	global $prefix, $db;
    static $users;
    if (isset($users[$aid]) AND is_array($users[$aid])) {
        $row = $users[$aid];
    } else {
        $sql = "SELECT url, email FROM ".$prefix."_authors WHERE aid='$aid'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $users[$aid] = $row;
        $db->sql_freeresult($result);
    }
	$aidurl = filter($row['url'], "nohtml");
	$aidmail = filter($row['email'], "nohtml");
    if (isset($aidurl)) {
        $aid = "<a href=\"".$aidurl."\">$aid</a>";
    } elseif (isset($aidmail)) {
        $aid = "<a href=\"mailto:".$aidmail."\">$aid</a>";
    } else {
        $aid = $aid;
    }
    return $aid;
}

function formatAidHeader($aid) {
  $AidHeader = get_author($aid);
  echo $AidHeader;
}

if(!function_exists("themepreview")) {
function themepreview($title, $hometext, $bodytext="", $notes="") {
	echo "<b>$title</b><br><br>$hometext";
	if (!empty($bodytext)) {
		echo "<br><br>$bodytext";
	}
	if (!empty($notes)) {
		echo "<br><br><b>"._NOTE."</b> <i>$notes</i>";
	}
  }
}

function adminblock() {
	global $admin, $prefix, $db, $admin_file;
	if (is_admin($admin)) {
	        $sql = "SELECT title, content FROM ".$prefix."_blocks WHERE bkey='admin'";
		$result = $db->sql_query($sql);
		while (list($title, $content) = $db->sql_fetchrow($result)) {
			$content = filter($content);
			$title = filter($title, "nohtml");
			$content = "<span class=\"content\">".$content."</span>";
			themesidebox($title, $content);
		}
		$title = _WAITINGCONT;
		$num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_queue"));
		$content = "<span class=\"content\">";
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$admin_file.".php?op=submissions\">"._SUBMISSIONS."</a>: $num<br>";
		$num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_reviews_add"));
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$admin_file.".php?op=reviews\">"._WREVIEWS."</a>: $num<br>";
		$num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_links_newlink"));
		$brokenl = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_links_modrequest WHERE brokenlink='1'"));
		$modreql = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_links_modrequest WHERE brokenlink='0'"));
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$admin_file.".php?op=Links\">"._WLINKS."</a>: $num<br>";
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$admin_file.".php?op=LinksListModRequests\">"._MODREQLINKS."</a>: $modreql<br>";
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$admin_file.".php?op=LinksListBrokenLinks\">"._BROKENLINKS."</a>: $brokenl<br>";
		$num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_newdownload"));
		$brokend = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_modrequest WHERE brokendownload='1'"));
		$modreqd = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_modrequest WHERE brokendownload='0'"));
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$admin_file.".php?op=downloads\">"._UDOWNLOADS."</a>: $num<br>";
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$admin_file.".php?op=DownloadsListModRequests\">"._MODREQDOWN."</a>: $modreqd<br>";
		$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$admin_file.".php?op=DownloadsListBrokenDownloads\">"._BROKENDOWN."</a>: $brokend<br></span>";
		themesidebox($title, $content);
	}
}

function loginbox() {
	global $user, $sitekey, $gfx_chk;
	mt_srand ((double)microtime()*1000000);
	$maxran = 1000000;
	$random_num = mt_rand(0, $maxran);
	$datekey = date("F j");
	$rcode = hexdec(md5($_SERVER['HTTP_USER_AGENT'] . $sitekey . $random_num . $datekey));
	$code = substr($rcode, 2, 6);
	if (!is_user($user)) {
		$title = _LOGIN;
		$boxstuff = "<form action=\"modules.php?name=Your_Account\" method=\"post\">";
		$boxstuff .= "<center><font class=\"content\">"._NICKNAME."<br>";
		$boxstuff .= "<input type=\"text\" name=\"username\" size=\"8\" maxlength=\"25\"><br>";
		$boxstuff .= ""._PASSWORD."<br>";
		$boxstuff .= "<input type=\"password\" name=\"user_password\" size=\"8\" maxlength=\"20\"><br>";
		if (extension_loaded("gd") AND ($gfx_chk == 2 OR $gfx_chk == 4 OR $gfx_chk == 5 OR $gfx_chk == 7)) {
			$boxstuff .= ""._SECURITYCODE.": <img src='?gfx=gfx&amp;random_num=$random_num' border='1' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'><br>\n";
			$boxstuff .= ""._TYPESECCODE."<br><input type=\"text\" NAME=\"gfx_check\" SIZE=\"7\" MAXLENGTH=\"6\">\n";
			$boxstuff .= "<input type=\"hidden\" name=\"random_num\" value=\"$random_num\"><br>\n";
		} else {
			$boxstuff .= "<input type=\"hidden\" name=\"random_num\" value=\"$random_num\">";
			$boxstuff .= "<input type=\"hidden\" name=\"gfx_check\" value=\"$code\">";
		}
		$boxstuff .= "<input type=\"hidden\" name=\"op\" value=\"login\">";
		$boxstuff .= "<input type=\"submit\" value=\""._LOGIN."\"></font></center></form>";
		$boxstuff .= "<center><font class=\"content\">"._ASREGISTERED."</font></center>";
		themesidebox($title, $boxstuff);
	}
}

function userblock() {
  global $user, $cookie, $db, $user_prefix, $userinfo;
  if(is_user($user)) {
    getusrinfo($user);
    if($userinfo['ublockon']) {
      $sql = "SELECT ublock FROM ".$user_prefix."_users WHERE user_id='$cookie[0]'";
      $result = $db->sql_query($sql);
      list($ublock) = $db->sql_fetchrow($result);
      $title = _MENUFOR." ".$cookie[1];
      themesidebox($title, $ublock);
    }
  }
}

function getTopics($s_sid) {
	global $topicname, $topicimage, $topictext, $prefix, $db;
	$sid = intval($s_sid);
	$result = $db->sql_query("SELECT t.topicname, t.topicimage, t.topictext FROM ".$prefix."_stories s LEFT JOIN ".$prefix."_topics t ON t.topicid = s.topic WHERE s.sid = '".$sid."'");
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	$topicname = filter($row[topicname], "nohtml");
	$topicimage = filter($row[topicimage], "nohtml");
	$topictext = filter($row[topictext], "nohtml");
}

function headlines($bid, $cenbox=0) {
	global $prefix, $db;
	$bid = intval($bid);
	$result = $db->sql_query("SELECT title, content, url, refresh, time FROM ".$prefix."_blocks WHERE bid='$bid'");
	list($title, $content, $url, $refresh, $otime) = $db->sql_fetchrow($result);
	$title = filter($title, "nohtml");
	$content = filter($content);
	$url = filter($url, "nohtml");
	$refresh = intval($refresh);
	$past = time()-$refresh;
	if ($otime < $past) {
		$btime = time();
		$rdf = parse_url($url);
		$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
		if (!$fp) {
			$content = "";
			$db->sql_query("UPDATE ".$prefix."_blocks SET content='$content', time='$btime' WHERE bid='$bid'");
			$cont = 0;
			if ($cenbox == 0) {
				themesidebox($title, $content);
			} else {
				themecenterbox($title, $content);
			}
			return;
		}
		if ($fp) {
			if (!empty($rdf['query']))
			$rdf['query'] = "?" . $rdf['query'];

			fputs($fp, "GET " . $rdf['path'] . $rdf['query'] . " HTTP/1.0\r\n");
			fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
			$string	= "";
			while(!feof($fp)) {
				$pagetext = fgets($fp,300);
				$string .= chop($pagetext);
			}
			fputs($fp,"Connection: close\r\n\r\n");
			fclose($fp);
			$items = explode("</item>",$string);
			$content = "<font class=\"content\">";
			for ($i=0;$i<10;$i++) {
				$link = ereg_replace(".*<link>","",$items[$i]);
				$link = ereg_replace("</link>.*","",$link);
				$title2 = ereg_replace(".*<title>","",$items[$i]);
				$title2 = ereg_replace("</title>.*","",$title2);
				$title2 = stripslashes($title2);
				if (empty($items[$i]) AND $cont != 1) {
					$content = "";
					$db->sql_query("UPDATE ".$prefix."_blocks SET content='$content', time='$btime' WHERE bid='$bid'");
					$cont = 0;
					if ($cenbox == 0) {
						themesidebox($title, $content);
					} else {
						themecenterbox($title, $content);
					}
					return;
				} else {
					if (strcmp($link,$title2) AND !empty($items[$i])) {
						$cont = 1;
						$content .= "<strong><big>&middot;</big></strong><a href=\"$link\" target=\"new\">$title2</a><br>\n";
					}
				}
			}

		}
		$db->sql_query("UPDATE ".$prefix."_blocks SET content='$content', time='$btime' WHERE bid='$bid'");
	}
	$siteurl = str_replace("http://","",$url);
	$siteurl = explode("/",$siteurl);
	if (($cont == 1) OR (!empty($content))) {
		$content .= "<br><a href=\"http://$siteurl[0]\" target=\"blank\"><b>"._HREADMORE."</b></a></font>";
	} elseif (($cont == 0) OR (empty($content))) {
		$content = "<font class=\"content\">"._RSSPROBLEM."</font>";
	}
	if ($cenbox == 0) {
		themesidebox($title, $content);
	} else {
		themecenterbox($title, $content);
	}
}



if(!function_exists("themecenterbox")) {
function themecenterbox($title, $content) {
	OpenTable();
	echo "<center><font class=\"option\"><b>$title</b></font></center><br>".$content;
	CloseTable();
	echo "<br>";
  }
}

function public_message() {
	global $prefix, $user_prefix, $db, $user, $admin, $p_msg, $cookie, $broadcast_msg;
	if ($broadcast_msg == 1) {
		if (is_user($user)) {
			cookiedecode($user);
			$result = $db->sql_query("SELECT broadcast FROM ".$user_prefix."_users WHERE username='$cookie[1]'");
			$row = $db->sql_fetchrow($result);
			$upref = intval($row['broadcast']);
			if ($upref == 1) {
				$t_off = "<br><p align=\"right\">[ <a href=\"modules.php?name=Your_Account&amp;op=edithome\">";
				$t_off .= "<font size=\"2\">"._TURNOFFMSG."</font></a> ]";
				$pm_show = 1;
			} else {
				$pm_show = 0;
			}
		} else {
			$t_off = "";
		}
		if (!is_user($user) OR (is_user($user) AND ($pm_show == 1))) {
			$c_mid = base64_decode($p_msg);
			$c_mid = addslashes($c_mid);
			$c_mid = intval($c_mid);
			$result2 = $db->sql_query("SELECT mid, content, date, who FROM ".$prefix."_public_messages WHERE mid > '$c_mid' ORDER BY date ASC LIMIT 1");
			$row2 = $db->sql_fetchrow($result2);
			$mid = intval($row2['mid']);
			$content = filter($row2['content'], "nohtml");
			$tdate = $row2['date'];
			$who = filter($row2['who'], "nohtml");
			if ((!isset($c_mid)) OR ($c_mid = $mid)) {
				$public_msg = "<br><table width=\"90%\" border=\"1\" cellspacing=\"2\" cellpadding=\"0\" bgcolor=\"FFFFFF\" align=\"center\"><tr><td>\n";
				$public_msg .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" bgcolor=\"FF0000\"><tr><td>\n";
				$public_msg .= "<font color=\"FFFFFF\" size=\"3\"><b>"._BROADCASTFROM." <a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$who\"><font color=\"FFFFFF\" size=\"3\">$who</font></a>: \"$content\"</b>";
				$public_msg .= "$t_off";
				$public_msg .= "</td></tr></table>";
				$public_msg .= "</td></tr></table>";
				$ref_date = $tdate+600;
				$actual_date = time();
				if ($actual_date >= $ref_date) {
					$public_msg = "";
					$numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_public_messages"));
					if ($numrows == 1) {
						$db->sql_query("DELETE FROM ".$prefix."_public_messages");
						$mid = 0;
					} else {
						$db->sql_query("DELETE FROM ".$prefix."_public_messages WHERE mid='$mid'");
					}
				}
				if ($mid == 0 OR empty($mid)) {
					setcookie("p_msg");
				} else {
					$mid = base64_encode($mid);
					$mid = addslashes($mid);
					setcookie("p_msg",$mid,time()+600);
				}
			}
		}
	} else {
		$public_msg = "";
	}
	return $public_msg;
}

function get_theme() {
    global $user, $userinfo, $Default_Theme, $name;
    
    return $Default_Theme;
}

function removecrlf($str) {
	// Function for Security Fix by Ulf Harnhammar, VSU Security 2002
	// Looks like I don't have so bad track record of security reports as Ulf believes
	// He decided to not contact me, but I'm always here, digging on the net
	return strtr($str, "\015\012", ' ');
}

function validate_mail($email) {
  if(strlen($email) < 7 || !preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $email)) {
     //include_once("header.php");
     OpenTable();
     echo _ERRORINVEMAIL;
     CloseTable();
     //include_once("footer.php");
  }
  else {
     return $email;
  }
}

function encode_mail($email) {
  $finished = "";
  for($i=0; $i<strlen($email); ++$i) {
    $n = mt_rand(0, 1);
    if($n) {
      $finished .= '&#x'.sprintf("%X",ord($email{$i})).';';
    }
    else {
      $finished .= '&#'.ord($email{$i}).';';
    }
  }
  return $finished;
}

function paid() {
	global $db, $user, $cookie, $adminmail, $sitename, $nukeurl, $subscription_url, $user_prefix, $prefix;
	if (is_user($user)) {
		if (!empty($subscription_url)) {
			$renew = ""._SUBRENEW." $subscription_url";
		} else {
			$renew = "";
		}
		cookiedecode($user);
		$sql = "SELECT * FROM ".$prefix."_subscriptions WHERE userid='$cookie[0]'";
		$result = $db->sql_query($sql);
		$numrows = $db->sql_numrows($result);
		$row = $db->sql_fetchrow($result);
		if ($numrows == 0) {
			return 0;
		} elseif ($numrows != 0) {
			$time = time();
			if ($row['subscription_expire'] <= $time) {
				$db->sql_query("DELETE FROM ".$prefix."_subscriptions WHERE userid='$cookie[0]' AND id='".intval($row['id'])."'");
				$from = "$sitename <$adminmail>";
				$subject = "$sitename: "._SUBEXPIRED."";
				$body = ""._HELLO." $cookie[1]:\n\n"._SUBSCRIPTIONAT." $sitename "._HASEXPIRED."\n$renew\n\n"._HOPESERVED."\n\n$sitename "._TEAM."\n$nukeurl";
				$row = $db->sql_fetchrow($db->sql_query("SELECT user_email FROM ".$user_prefix."_users WHERE id='$cookie[0]' AND nickname='$cookie[1]' AND password='$cookie[2]'"));
				mail($row['user_email'], $subject, $body, "From: $from\nX-Mailer: PHP/" . phpversion());
			}
			return 1;
		}
	} else {
		return 0;
	}
}

function ads($position) {
	$ads = '
			<script type="text/javascript"><!--
google_ad_client = "ca-pub-3640924718734312";
/* Turista Mexico Banner 468 x 60 */
google_ad_slot = "2012063647";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
			
			';
	return $ads;
}

function redir($content) {
	global $nukeurl;
	unset($location);
	$content = filter($content);
	$links = array();
	$hrefs = array();
	$pos = 0;
	while (!(($pos = strpos($content,"<",$pos)) === false)) {
		$pos++;
		$endpos = strpos($content,">",$pos);
		$tag = substr($content,$pos,$endpos-$pos);
		$tag = trim($tag);
		if (isset($location)) {
			if (!strcasecmp(strtok($tag," "),"/A")) {
				$link = substr($content,$linkpos,$pos-1-$linkpos);
				$links[] = $link;
				$hrefs[] = $location;
				unset($location);
			}
			$pos = $endpos+1;
		} else {
			if (!strcasecmp(strtok($tag," "),"A")) {
				if (eregi("HREF[ \t\n\r\v]*=[ \t\n\r\v]*\"([^\"]*)\"",$tag,$regs));
				else if (eregi("HREF[ \t\n\r\v]*=[ \t\n\r\v]*([^ \t\n\r\v]*)",$tag,$regs));
				else $regs[1] = "";
				if ($regs[1]) {
					$location = $regs[1];
				}
				$pos = $endpos+1;
				$linkpos = $pos;
			} else {
				$pos = $endpos+1;
			}
		}
	}
	for ($i=0; $i<sizeof($hrefs); $i++) {
		$url = urlencode($hrefs[$i]);
		$content = str_replace("<a href=\"$hrefs[$i]\">", "<a href=\"$nukeurl/index.php?url=$url\" target=\"_blank\">", $content);
	}
	return($content);
}

function info_box($graphic, $message) {
	// Function to generate a message box with a graphic inside
	// $graphic value can be whichever: warning, caution, tip, note.
	// Then the graphic value with the extension .gif should be present inside /images/system/ folder
	if (file_exists("images/system/".$graphic.".gif") AND !empty($message)) {
		Opentable();
		$graphic = filter($graphic, "nohtml");
		$message = filter($message, "");
		echo "<table align=\"center\" border=\"0\" width=\"80%\" cellpadding=\"10\"><tr>"
			."<td valign=\"top\"><img src=\"images/system/".$graphic.".gif\" border=\"0\" alt=\"\" title=\"\" width=\"34\" height=\"34\"></td>"
			."<td valign=\"top\">$message</td>"
			."</tr></table>";
		CloseTable();
	} else {
		return;
	}
}

if (isset($gfx)){
switch($gfx) {

	case "gfx":
	$datekey = date("F j");
	$rcode = hexdec(md5($_SERVER['HTTP_USER_AGENT'] . $sitekey . $random_num . $datekey));
	$code = substr($rcode, 2, 6);
	$ThemeSel = get_theme();
	if (file_exists("themes/".$ThemeSel."/images/code_bg.jpg")) {
		$image = ImageCreateFromJPEG("themes/".$ThemeSel."/images/code_bg.jpg");
	} else {
		$image = ImageCreateFromJPEG("images/code_bg.jpg");
	}
	$text_color = ImageColorAllocate($image, 80, 80, 80);
	Header("Content-type: image/jpeg");
	ImageString ($image, 5, 12, 2, $code, $text_color);
	ImageJPEG($image, '', 75);
	ImageDestroy($image);
	die();
	break;

	case "gfx_little":
	$datekey = date("F j");
	$rcode = hexdec(md5($_SERVER['HTTP_USER_AGENT'] . $sitekey . $random_num . $datekey));
	$code = substr($rcode, 2, 3);
	$ThemeSel = get_theme();
	if (file_exists("themes/".$ThemeSel."/images/code_bg_little.jpg")) {
		$image = ImageCreateFromJPEG("themes/".$ThemeSel."/images/code_bg_little.jpg");
	} else {
		$image = ImageCreateFromJPEG("images/code_bg_little.jpg");
	}
	$text_color = ImageColorAllocate($image, 80, 80, 80);
	Header("Content-type: image/jpeg");
	ImageString ($image, 5, 12, 2, $code, $text_color);
	ImageJPEG($image, '', 75);
	ImageDestroy($image);
	die();
	break;
   }
}

?>