<?php

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);


function display_score($score) {
    $full = "<img src=\"images/star.gif\" alt=\"\">";
	for ($i=0; $i < $score; $i++)
	    echo "$full";
}

function write_review($restoid) {
	include('header.php');
	echo 'Temporalmente fuera de servicio';
	include('footer.php');
}

function write_review_old($restoid) {
    global $admin, $sitename, $user, $cookie, $prefix, $user_prefix, $currentlang, $multilingual, $dbi, $module_name;
    include ('header.php');
/*if (is_user($user)) {
    $resultuservoto=sql_query("select userrvoto from ".$user_prefix."_restovotos where userrvoto='$cookie[1]' and restoid='$restoid'",$dbi) ;
	list($yavoto) = sql_fetch_row($resultuservoto, $dbi);
	if ($yavoto){  // si el usuario ya votó indica un mensaje e impide el voto
	  OpenTable();
	  echo $yavoto;
	  //echo "select userrvoto from ".$user_prefix."_restovotos where userrvoto='$cookie[1]' and restoid='$restoid'";
	  echo ": "._USTEDYAVOTO."";
	  CloseTable();
	  include('footer.php');
	}*/
    OpenTable();
	$result=sql_query("select nombre from ".$user_prefix."_restaurantes where restoid='$restoid'",$dbi);
	list($nombre) = sql_fetch_row($result, $dbi);
    echo "<center>
    <b>"._WRITEREVIEWFOR." $nombre</b><br><br>
    <i>"._ENTERINFO."</i><br>";
    echo "<form method=\"post\" action=\"modules.php?name=$module_name&file=votos\">";
    $result=sql_query("select uname, name, email from ".$user_prefix."_users where uname='$cookie[1]'", $dbi);
    list($uname, $name, $email) = sql_fetch_row($result, $dbi);
	
    echo "<b>"._NICKNAME.":</b>&nbsp;$uname&nbsp;&nbsp;<b>"._EMAIL.":</b>&nbsp;$email
	<input type=\"hidden\" name=\"userrvoto\"  value=\"$uname\">
	<input type=\"hidden\" name=\"restoid\"  value=\"$restoid\"><br><br>"; 
     
    echo "<table  align=center border=0><tr><td><b><font color=\"red\">"._FOOD."</font></b>&nbsp;
    <td><select name=\"vrfood\">
    <option name=\"vrfood\" value=\"5\">"._EXCEPTIONAL."</option>
    <option name=\"vrfood\" value=\"4\">"._VERYGOOD."</option>
    <option name=\"vrfood\" value=\"3\">"._GOOD."</option>
    <option name=\"vrfood\" value=\"2\">"._ACCEPTABLE."</option>
    <option name=\"vrfood\" value=\"1\">"._NEEDSWORK."</option>
    </select></td><td width=\"10%\">&nbsp;</td>
    </td><td>";
	echo "<b><font color=\"red\">"._SERVICE."</font></b>&nbsp;
    <td><select name=\"vrservice\">
    <option name=\"vrservice\" value=\"5\">"._EXCEPTIONAL."</option>
    <option name=\"vrservice\" value=\"4\">"._VERYGOOD."</option>
    <option name=\"vrservice\" value=\"3\">"._GOOD."</option>
    <option name=\"vrservice\" value=\"2\">"._ACCEPTABLE."</option>
    <option name=\"vrservice\" value=\"1\">"._NEEDSWORK."</option>
    </select></td>
    </td></tr>";
	echo "<tr><td><b><font color=\"red\">"._AMBIANCE."</font></b>&nbsp;
    <td><select name=\"vrambiance\">
    <option name=\"vrambiance\" value=\"5\">"._EXCEPTIONAL."</option>
    <option name=\"vrambiance\" value=\"4\">"._VERYGOOD."</option>
    <option name=\"vrambiance\" value=\"3\">"._GOOD."</option>
    <option name=\"vrambiance\" value=\"2\">"._ACCEPTABLE."</option>
    <option name=\"vrambiance\" value=\"1\">"._NEEDSWORK."</option>
    </select></td><td>&nbsp;</td>
    </td><td>";
	echo "<b><font color=\"red\">"._OVERALL."</font></b>&nbsp;
    <td><select name=\"vroverall\">
    <option name=\"vroverall\" value=\"5\">"._EXCEPTIONAL."</option>
    <option name=\"vroverall\" value=\"4\">"._VERYGOOD."</option>
    <option name=\"vroverall\" value=\"3\">"._GOOD."</option>
    <option name=\"vroverall\" value=\"2\">"._ACCEPTABLE."</option>
    <option name=\"vroverall\" value=\"1\">"._NEEDSWORK."</option>
    </select></td>
    </td></tr>";
	echo "<tr><td><b>"._CLEANLINESS."</b>&nbsp;
    <td><select name=\"vrclean\">
    <option name=\"vrclean\" value=\"5\">"._EXCEPTIONAL."</option>
    <option name=\"vrclean\" value=\"4\">"._VERYGOOD."</option>
    <option name=\"vrclean\" value=\"3\">"._GOOD."</option>
    <option name=\"vrclean\" value=\"2\">"._ACCEPTABLE."</option>
    <option name=\"vrclean\" value=\"1\">"._NEEDSWORK."</option>
    </select></td><td>&nbsp;</td>
    </td><td>";
	echo "<b>"._VALUEFORMONEY."</b>&nbsp;
    <td><select name=\"vrmoney\">
    <option name=\"vrmoney\" value=\"5\">"._EXCEPTIONAL."</option>
    <option name=\"vrmoney\" value=\"4\">"._VERYGOOD."</option>
    <option name=\"vrmoney\" value=\"3\">"._GOOD."</option>
    <option name=\"vrmoney\" value=\"2\">"._ACCEPTABLE."</option>
    <option name=\"vrmoney\" value=\"1\">"._NEEDSWORK."</option>
    </select></td>
    </td></tr>";
	echo "<tr><td><b>"._WINELIST."</b>&nbsp;
    <td><select name=\"vrwinelist\">
    <option name=\"vrwinelist\" value=\"5\">"._EXCEPTIONAL."</option>
    <option name=\"vrwinelist\" value=\"4\">"._VERYGOOD."</option>
    <option name=\"vrwinelist\" value=\"3\">"._GOOD."</option>
    <option name=\"vrwinelist\" value=\"2\">"._ACCEPTABLE."</option>
    <option name=\"vrwinelist\" value=\"1\">"._NEEDSWORK."</option>
    </select></td><td>&nbsp;</td>
    </td><td>";
	echo "<b>"._KIDFRIEND."</b>&nbsp;
    <td><select name=\"vrkid\">
    <option name=\"vrkid\" value=\"5\">"._EXCEPTIONAL."</option>
    <option name=\"vrkid\" value=\"4\">"._VERYGOOD."</option>
    <option name=\"vrkid\" value=\"3\">"._GOOD."</option>
    <option name=\"vrkid\" value=\"2\">"._ACCEPTABLE."</option>
    <option name=\"vrkid\" value=\"1\">"._NEEDSWORK."</option>
    </select></td>
    </td></tr></table><br>";
    echo "<b>"._COMENTARIOS.":</b><br>
    <textarea name=\"vrcoment\" rows=\"15\" wrap=\"virtual\" cols=\"90\"></textarea><br>";
    if ($multilingual == 1) {
	echo "<br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"vrlanguage\">";
	$handle=opendir('language');
	while ($file = readdir($handle)) {
	    if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	        $langFound = $matches[1];
	        $languageslist .= "$langFound ";
	    }
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++) {
	    if($languageslist[$i]!="") {
		echo "<option value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$currentlang) echo "selected";
		echo ">$languageslist[$i]</option>\n";
	    }
	}
	echo "</select><br><br>";
    } else {
	echo "<input type=\"hidden\" name=\"vrlanguage\" value=\"$language\"><br><br>";
    }	   
    echo "<input type=\"hidden\" name=\"rop\" value=\"preview_review\">
    <input type=\"submit\" value=\""._PREVIEW."\"> <input type=\"button\" onClick=\"history.go(-1)\" value=\""._CANCEL."\"></form>";
    CloseTable();
    include ("footer.php");
  /*} else { // if is_user
      OpenTable();
      echo ""._MODULEUSERS."";
      CloseTable();
	}*/
}

function preview_review($restoid, $userrvoto, $vrfecha, $vrfood, $vrservice, $vrambiance, $vroverall, $vrclean, $vrmoney, $vrwinelist, $vrkid, $vrcoment, $vrlanguage)
{
    global $admin, $multilingual, $module_name, $user_prefix, $dbi;
    include ('header.php');
    OpenTable();
    echo "<form method=\"post\" action=\"modules.php?name=$module_name&file=votos\">";


    if (($vrfood < 1) || ($vrfood > 5)) {
	$error = 1;
	echo ""._INVALIDSCORE."<br>";
    }

	if ($error == 1)
	    echo "<br>"._GOBACK."";
	else
	{
	if ($vrfecha == "")
	    $vrfecha = date("Y-m-d", time());
	    $year2 = substr($vrfecha,0,4);
	    $month = substr($vrfecha,5,2);
	    $day = substr($vrfecha,8,2);
	    $fdate = date("F jS Y",mktime (0,0,0,$month,$day,$year2));
    	    echo "<table border=\"0\" width=\"100%\"><tr><td colspan=\"2\">";
	    $result=sql_query("select nombre from ".$user_prefix."_restaurantes where restoid='$restoid'",$dbi);
	    list($nombre) = sql_fetch_row($result, $dbi);
        echo "<p><font class=\"title\"><i><b>"._WRITEREVIEWFOR." &nbsp;$nombre</b></i></font><br>";
		echo "<b>"._USER."</b> $uservoto<br>";
	    echo "<b>"._ADDED."</b> $fdate<br>";
	    if ($multilingual == 1) {
		echo "<b>"._LANGUAGE."</b> $vrlanguage<br>";
	    }

	    echo "<font color=red><b>"._FOOD."</b> ";
	    display_score($vrfood);
		echo "&nbsp;&nbsp;&nbsp;<b>"._SERVICE."</b> ";
	    display_score($vrservice);
		echo "<br><b>"._AMBIANCE."</b> ";
	    display_score($vrambiance);
		echo "&nbsp;&nbsp;&nbsp;<b>"._OVERALL."</b></font> ";
	    display_score($vroverall);
		echo "<br><b>"._CLEANLINESS."</b> ";
	    display_score($vrclean);
		echo "&nbsp;&nbsp;&nbsp;<b>"._VALUEFORMONEY."</b> ";
	    display_score($vrmoney);
		echo "<br><b>"._WINELIST."</b> ";
	    display_score($vrwinelist);
		echo "&nbsp;&nbsp;&nbsp;<b>"._KIDFRIEND."</b> ";
	    display_score($vrkid);
	    echo "<br><br><b>"._COMENTARIOS."</b><br>$vrcoment";
	 
	    echo "</font></blockquote>";
	    echo "</td></tr></table>";
	    $vrcoment = urlencode($vrcoment);
	    echo "<p><i>"._LOOKSRIGHT."</i> ";		
	    echo "<input type=\"hidden\" name=\"restoid\" value=$restoid>
		  <input type=\"hidden\" name=\"userrvoto\" value=\"$userrvoto\">
		  <input type=\"hidden\" name=\"rop\" value=send_review>
		  <input type=\"hidden\" name=\"vrfecha\" value=\"$vrfecha\">
		  <input type=\"hidden\" name=\"vrfood\" value=\"$vrfood\">
		  <input type=\"hidden\" name=\"vrservice\" value=\"$vrservice\">
		  <input type=\"hidden\" name=\"vrambiance\" value=\"$vrambiance\">
		  <input type=\"hidden\" name=\"vroverall\" value=\"$vroverall\">
		  <input type=\"hidden\" name=\"vrclean\" value=\"$vrclean\">
		  <input type=\"hidden\" name=\"vrmoney\" value=\"$vrmoney\">
		  <input type=\"hidden\" name=\"vrwinelist\" value=\"$vrwinelist\">
		  <input type=\"hidden\" name=\"vrcoment\" value=\"$vrcoment\">
		  <input type=\"hidden\" name=\"vrkid\" value=\"$vrkid\">";
		  echo "<input type=\"hidden\" name=\"vrlanguage\" value=\"$vrlanguage\">";
		echo "<input type=\"submit\" name=\"rop\" value=\""._YES."\"> <input type=\"button\" onClick=\"history.go(-1)\" value=\""._NO."\">";
	    if($id != 0)
	    	$word = ""._RMODIFIED."";
	    else
	    	$word = ""._RADDED."";
	}
    CloseTable();
    include ("footer.php");
}

function send_review($restoid, $userrvoto, $vrfecha, $vrfood, $vrservice, $vrambiance, $vroverall, $vrclean, $vrmoney, $vrwinelist, $vrkid, $vrcoment, $vrlanguage) {
    global $admin, $EditedMessage, $prefix, $dbi, $module_name;
    include ('header.php');
    $vrcoment = stripslashes(Fixquotes(urldecode(check_html($vrcoment, ""))));
    OpenTable();
    echo "<br><center>"._RTHANKS."";
	$id = 0;  // indica que es un alta
    if ($id != 0)
	echo " "._MODIFICATION."";
    else
	echo ", $reviewer";
    echo "!<br>";
    //if ((is_admin($admin)) && ($id == 0)) {
	if ($id == 0) {
	sql_query("INSERT INTO ".$prefix."_restovotos VALUES (NULL, '$restoid', '$userrvoto', '$vrfecha', '$vrfood', '$vrservice', '$vrambiance', '$vroverall', '$vrclean', '$vrmoney', '$vrwinelist', '$vrkid', '$vrcoment', '$vrlanguage')", $dbi);
	echo ""._ISAVAILABLE."";
    } else if ((is_admin($admin)) && ($id != 0)) {
	sql_query("UPDATE ".$prefix."_reviews SET date='$date', title='$title', text='$text', reviewer='$reviewer', email='$email', score='$score', cover='$cover', url='$url', url_title='$url_title', hits='$hits', rlanguage='$rlanguage' where id = $id", $dbi);
	echo ""._ISAVAILABLE."";
    } 
    echo "<br><br>[ <a href=\"modules.php?name=$module_name\">"._RBACK."</a> ]<br></center>";
    CloseTable();
    include ("footer.php");
}


function reviews($letter, $field, $order) {
    global $bgcolor4, $sitename, $prefix, $multilingual, $currentlang, $dbi, $module_name;
    include ('header.php');
    if ($multilingual == 1) {
    $querylang = "AND rlanguage='$currentlang'";
    } else {
    $querylang = "";
    }
    OpenTable();
    echo "<center><b>$sitename "._REVIEWS."</b><br>";
    echo "<i>"._REVIEWSLETTER." \"$letter\"</i><br><br>";
    switch ($field) {

	case "reviewer":
	$result = sql_query("SELECT id, title, hits, reviewer, score FROM ".$prefix."_reviews WHERE UPPER(title) LIKE '$letter%' $querylang ORDER by reviewer $order", $dbi);
	break;

	case "score":
	$result = sql_query("SELECT id, title, hits, reviewer, score FROM ".$prefix."_reviews WHERE UPPER(title) LIKE '$letter%' $querylang ORDER by score $order", $dbi);
	break;

	case "hits":
	$result = sql_query("SELECT id, title, hits, reviewer, score FROM ".$prefix."_reviews WHERE UPPER(title) LIKE '$letter%' $querylang ORDER by hits $order", $dbi);
	break;

	default:
	$result = sql_query("SELECT id, title, hits, reviewer, score FROM ".$prefix."_reviews WHERE UPPER(title) LIKE '$letter%' $querylang ORDER by title $order", $dbi);
	break;

    }
    $numresults = sql_num_rows($result, $dbi);
    if ($numresults == 0) {
	echo "<i><b>"._NOREVIEWS." \"$letter\"</b></i><br><br>";
    } elseif ($numresults > 0) {
	echo "<TABLE BORDER=\"0\" width=\"100%\" CELLPADDING=\"2\" CELLSPACING=\"4\">
		<tr>
		<td width=\"50%\" bgcolor=\"$bgcolor4\">
		<P ALIGN=\"LEFT\"><a href=\"modules.php?name=$module_name&rop=$letter&amp;field=title&amp;order=ASC\"><img src=\"images/download/up.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\""._SORTASC."\"></a><B> "._PRODUCTTITLE." </B><a href=\"modules.php?name=$module_name&rop=$letter&amp;field=title&amp;order=DESC\"><img src=\"images/download/down.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\""._SORTDESC."\"></a>
		</td>
		<td width=\"18%\" bgcolor=\"$bgcolor4\">
		<P ALIGN=\"CENTER\"><a href=\"modules.php?name=$module_name&rop=$letter&amp;field=reviewer&amp;order=ASC\"><img src=\"images/download/up.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\""._SORTASC."\"></a><B> "._REVIEWER." </B><a href=\"modules.php?name=$module_name&rop=$letter&amp;field=reviewer&amp;order=desc\"><img src=\"images/download/down.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\""._SORTDESC."\"></a>
		</td>
		<td width=\"18%\" bgcolor=\"$bgcolor4\">
		<P ALIGN=\"CENTER\"><a href=\"modules.php?name=$module_name&rop=$letter&amp;field=score&amp;order=ASC\"><img src=\"images/download/up.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\""._SORTASC."\"></a><B> "._SCORE." </B><a href=\"modules.php?name=$module_name&rop=$letter&amp;field=score&amp;order=DESC\"><img src=\"images/download/down.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\""._SORTDESC."\"></a>
		</td>
		<td width=\"14%\" bgcolor=\"$bgcolor4\">
		<P ALIGN=\"CENTER\"><a href=\"modules.php?name=$module_name&rop=$letter&amp;field=hits&amp;order=ASC\"><img src=\"images/download/up.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\""._SORTASC."\"></a><B> "._HITS." </B><a href=\"modules.php?name=$module_name&rop=$letter&amp;field=hits&amp;order=DESC\"><img src=\"images/download/down.gif\" border=\"0\" width=\"15\" height=\"9\" Alt=\""._SORTDESC."\"></a>
		</td>
		</tr>";
	while($myrow = sql_fetch_array($result, $dbi)) {
	    $title = $myrow["title"];
	    $id = $myrow["id"];
	    $reviewer = $myrow["reviewer"];
	    $email = $myrow["email"];
	    $score = $myrow["score"];
	    $hits = $myrow["hits"];
	    echo "<tr>
		    <td width=\"50%\" bgcolor=\"#EEEEEE\"><a href=\"modules.php?name=$module_name&rop=showcontent&amp;id=$id\">$title</a></td>
		    <td width=\"18%\" bgcolor=\"#EEEEEE\">";
	    if ($reviewer != "")
		echo "<center>$reviewer</center>";
	    echo "</td><td width=\"18%\" bgcolor=\"#EEEEEE\"><center>";
	    display_score($score);
	    echo "</center></td><td width=\"14%\" bgcolor=\"#EEEEEE\"><center>$hits</center></td>
		  </tr>";
	}
	echo "</TABLE>";
	echo "<br>$numresults "._TOTALREVIEWS."<br><br>";
    }
    echo "[ <a href=\"modules.php?name=$module_name\">"._RETURN2MAIN."</a> ]";
    CloseTable();
    include ("footer.php");
}

function postcomment($id, $title) {
    global $user, $cookie, $AllowableHTML, $anonymous, $module_name;
    include("header.php");
    cookiedecode($user);
    $title = urldecode($title);
    OpenTable();
    echo "<center><font class=option><b>"._REVIEWCOMMENT." $title</b><br><br></font></center>"
	."<form action=modules.php?name=$module_name method=post>";
    if (!is_user($user)) {
	echo "<b>"._YOURNICK."</b> $anonymous [ "._RCREATEACCOUNT." ]<br><br>";
	$uname = $anonymous;
    } else {
	echo "<b>"._YOURNICK."</b> $cookie[1]<br>
	<input type=checkbox name=xanonpost> "._POSTANON."<br><br>";
	$uname = $cookie[1];
    }
    echo "
    <input type=hidden name=uname value=$uname>
    <input type=hidden name=id value=$id>
    <b>"._SELECTSCORE."</b>
    <select name=score>
    <option name=score value=10>10</option>
    <option name=score value=9>9</option>
    <option name=score value=8>8</option>
    <option name=score value=7>7</option>
    <option name=score value=6>6</option>
    <option name=score value=5>5</option>
    <option name=score value=4>4</option>
    <option name=score value=3>3</option>
    <option name=score value=2>2</option>
    <option name=score value=1>1</option>
    </select><br><br>
    <b>"._YOURCOMMENT."</b><br>
    <textarea name=comments rows=10 cols=70></textarea><br>
    "._ALLOWEDHTML."<br>";
    while (list($key,)= each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "<br><br>
    <input type=hidden name=rop value=savecomment>
    <input type=submit value=Submit>
    </form>
    ";
    CloseTable();
    include("footer.php");
}

function savecomment($xanonpost, $uname, $id, $score, $comments) {
    global $anonymous, $user, $cookie, $prefix, $dbi, $module_name;
    if ($xanonpost) {
	$uname = $anonymous;
    }
    $comments = stripslashes(FixQuotes(check_html($comments)));
    sql_query("insert into ".$prefix."_reviews_comments values (NULL, '$id', '$uname', now(), '$comments', '$score')", $dbi);
    Header("Location: modules.php?name=$module_name&rop=showcontent&id=$id");
}

function r_comments($id, $title) {
    global $admin, $prefix, $dbi, $module_name;
    $result = sql_query("select cid, userid, date, comments, score from ".$prefix."_reviews_comments where rid='$id' ORDER BY date DESC", $dbi);
    while(list($cid, $uname, $date, $comments, $score) = sql_fetch_row($result, $dbi)) {
	OpenTable();
	$title = urldecode($title);
	echo "
	<b>$title</b><br>";
	if ($uname == "Anonymous") {
	    echo ""._POSTEDBY." $uname "._ON." $date<br>";
	} else {
	    echo ""._POSTEDBY." <a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;uname=$uname\">$uname</a> "._ON." $date<br>";
	}
	echo ""._MYSCORE." ";
	display_score($score);
	if (is_admin($admin)) {
	    echo "<br><b>"._ADMIN."</b> [ <a href=\"modules.php?name=$module_name&rop=del_comment&amp;cid=$cid&amp;id=$id\">"._DELETE."</a> ]</font><hr noshade size=1><br><br>";
	} else {
	    echo "</font><hr noshade size=1><br><br>";
	}
	$comments = FixQuotes(nl2br(filter_text($comments)));
	echo "
	$comments
	";
	CloseTable();
	echo "<br>";
    }
}

function showcontent($id, $page) {
    global $admin, $uimages, $prefix, $dbi, $module_name;
    include ('header.php');
    OpenTable();
    if (($page == 1) OR ($page == "")) {
	sql_query("UPDATE ".$prefix."_reviews SET hits=hits+1 WHERE id=$id", $dbi);
    }
    $result = sql_query("SELECT * FROM ".$prefix."_reviews WHERE id=$id", $dbi);
    $myrow =  sql_fetch_array($result, $dbi);
    $id =  $myrow["id"];
    $date = $myrow["date"];
    $year = substr($date,0,4);
    $month = substr($date,5,2);
    $day = substr($date,8,2);
    $fdate = date("F jS Y",mktime (0,0,0,$month,$day,$year));
    $title = $myrow["title"];
    $text = $myrow["text"];
    $cover = $myrow["cover"];
    $reviewer = $myrow["reviewer"];
    $email = $myrow["email"];
    $hits = $myrow["hits"];
    $url = $myrow["url"];
    $url_title = $myrow["url_title"];
    $score = $myrow["score"];
    $rlanguage = $myrow["rlanguage"];
    $contentpages = explode( "<!--pagebreak-->", $text );
    $pageno = count($contentpages);
    if ( $page=="" || $page < 1 )
	$page = 1;
    if ( $page > $pageno )
	$page = $pageno;
    $arrayelement = (int)$page;
    $arrayelement --;
    echo "<p><i><b><font class=\"title\">$title</b></i></font><br>";
    echo "<BLOCKQUOTE><p align=justify>";
    if ($cover != "")
    echo "<img src=\"images/reviews/$cover\" align=right border=1 vspace=2 alt=\"\">";
    echo "$contentpages[$arrayelement]
    </BLOCKQUOTE><p>";
    if (is_admin($admin))
		echo "<b>"._ADMIN."</b> [ <a href=\"modules.php?name=$module_name&rop=mod_review&amp;id=$id\">"._EDIT."</a> | <a href=modules.php?name=$module_name&rop=del_review&amp;id_del=$id>"._DELETE."</a> ]<br>";
    echo "<b>"._ADDED."</b> $fdate<br>";
    if ($reviewer != "")
	echo "<b>"._REVIEWER."</b> <a href=mailto:$email>$reviewer</a><br>";
    if ($score != "")
	echo "<b>"._SCORE."</b> ";
    display_score($score);
    if ($url != "")
		echo "<br><b>"._RELATEDLINK.":</b> <a href=\"$url\" target=new>$url_title</a>";
    echo "<br><b>"._HITS.":</b> $hits";
    echo "<br><b>"._LANGUAGE.":</b> $rlanguage";
    if ($pageno > 1) {
	echo "<br><b>"._PAGE.":</b> $page/$pageno<br>";
    }
    echo "</font>";
    echo "</CENTER>";
    $title = urlencode($title);
    if($page >= $pageno) {
	  $next_page = "";
    } else {
	$next_pagenumber = $page + 1;
	if ($page != 1) {
	    $next_page .= "<img src=\"images/blackpixel.gif\" width=\"10\" height=\"2\" border=\"0\" alt=\"\"> &nbsp;&nbsp; ";
	}
	$next_page .= "<a href=\"modules.php?name=$module_name&rop=showcontent&amp;id=$id&amp;page=$next_pagenumber\">"._NEXT." ($next_pagenumber/$pageno)</a> <a href=\"modules.php?name=$module_name&rop=showcontent&amp;id=$id&amp;page=$next_pagenumber\"><img src=\"images/download/right.gif\" border=\"0\" alt=\""._NEXT."\"></a>";
    }
    if($page <= 1) {
	$previous_page = "";
    } else {
	$previous_pagenumber = $page - 1;
	$previous_page = "<a href=\"modules.php?name=$module_name&rop=showcontent&amp;id=$id&amp;page=$previous_pagenumber\"><img src=\"images/download/left.gif\" border=\"0\" alt=\""._PREVIOUS."\"></a> <a href=\"modules.php?name=$module_name&rop=showcontent&amp;id=$id&amp;page=$previous_pagenumber\">"._PREVIOUS." ($previous_pagenumber/$pageno)</a>";
    }
    echo "<center>"
	."$previous_page &nbsp;&nbsp; $next_page<br><br>"
	."[ <a href=\"modules.php?name=$module_name\">"._RBACK."</a> | "
	."<a href=\"modules.php?name=$module_name&rop=postcomment&amp;id=$id&amp;title=$title\">"._REPLYMAIN."</a> ]";
    CloseTable();
    if (($page == 1) OR ($page == "")) {
	echo "<br>";
	r_comments($id, $title);
    }
    include ("footer.php");
}

function mod_review($id) {
	global $admin, $prefix, $dbi, $module_name;
	include ('header.php');
	OpenTable();
	if (($id == 0) || (!is_admin($admin)))
	    echo "This function must be passed argument id, or you are not admin.";
	else if (($id != 0) && (is_admin($admin)))
	{
		$result = sql_query("select * from ".$prefix."_reviews where id = $id", $dbi);
		while($myrow =  sql_fetch_array($result, $dbi))
		{
			$id =  $myrow["id"];
			$date = $myrow["date"];
			$title = $myrow["title"];
			$text = $myrow["text"];
			$cover = $myrow["cover"];
			$reviewer = $myrow["reviewer"];
			$email = $myrow["email"];
			$hits = $myrow["hits"];
			$url = $myrow["url"];
			$url_title = $myrow["url_title"];
			$score = $myrow["score"];
			$rlanguage = $myrow["rlanguage"];
		}
		echo "<center><b>"._REVIEWMOD."</b></center><br><br>";
		echo "<form method=POST action=modules.php?name=$module_name&rop=preview_review><input type=hidden name=id value=$id>";
		echo "<TABLE BORDER=0 width=100%>
			<tr>
				<td width=12%><b>"._RDATE."</b></td>
				<td><INPUT TYPE=text NAME=date SIZE=15 VALUE=\"$date\" MAXLENGTH=10></td>
			</tr>
			<tr>
				<td width=12%><b>"._RTITLE."</b></td>
				<td><INPUT TYPE=text NAME=title SIZE=50 MAXLENGTH=150 value=\"$title\"></td>
			</tr>
			<tr>";
		echo "<td width=12%><b>"._LANGUAGE."</b></td>
				<td><select name=\"rlanguage\">";
			    $handle=opendir('language');
				    while ($file = readdir($handle)) {
					if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
				            $langFound = $matches[1];
				            $languageslist .= "$langFound ";
				        }
				    }
				    closedir($handle);
				    $languageslist = explode(" ", $languageslist);
				    for ($i=0; $i < sizeof($languageslist); $i++) {
					if($languageslist[$i]!="") {
					    echo "<option value=\"$languageslist[$i]\" ";
						if($languageslist[$i]==$rlanguage) echo "selected";
						echo ">$languageslist[$i]</option>\n";
					}
			    }

	    echo "</select></td></tr>";
		echo "<tr>
				<td width=12%><b>"._RTEXT."</b></td>
				<td><TEXTAREA class=textbox name=text rows=20 wrap=virtual cols=60>$text</TEXTAREA></td>
			</tr>
			<tr>
				<td width=12%><b>"._REVIEWER."</b></td>
				<td><INPUT TYPE=text NAME=reviewer SIZE=41 MAXLENGTH=40 value=\"$reviewer\"></td>
			</tr>
			<tr>
				<td width=12%><b>"._REVEMAIL."</b></td>
				<td><INPUT TYPE=text NAME=email value=\"$email\" SIZE=30 MAXLENGTH=80></td>
			</tr>
			<tr>
				<td width=12%><b>"._SCORE."</b></td>
				<td><INPUT TYPE=text NAME=score value=\"$score\" size=3 maxlength=2></td>
			</tr>
			<tr>
				<td width=12%><b>"._RLINK."</b></td>
				<td><INPUT TYPE=text NAME=url value=\"$url\" size=30 maxlength=100></td>
			</tr>
			<tr>
				<td width=12%><b>"._RLINKTITLE."</b></td>
				<td><INPUT TYPE=text NAME=url_title value=\"$url_title\" size=30 maxlength=50></td>
			</tr>
			<tr>
				<td width=12%><b>"._COVERIMAGE."</b></td>
				<td><INPUT TYPE=text NAME=cover value=\"$cover\" size=30 maxlength=100></td>
			</tr>
			<tr>
				<td width=12%><b>"._HITS.":</b></td>
				<td><INPUT TYPE=text NAME=hits value=\"$hits\" size=5 maxlength=5></td>
			</tr>
		</TABLE>";
		echo "<input type=hidden name=rop value=preview_review><input type=submit value=\""._PREMODS."\">&nbsp;&nbsp;<input type=button onClick=history.go(-1) value="._CANCEL."></form>";
	}
	CloseTable();
	include ("footer.php");
}

function del_review($id_del) {
    global $admin, $prefix, $dbi, $module_name;
    if (is_admin($admin)) {
    	sql_query("delete from ".$prefix."_reviews where id = $id_del", $dbi);
	sql_query("delete from ".$prefix."_reviews_comments where rid='$id_del'", $dbi);
	Header("Location: modules.php?name=$module_name");
    } else {
    	echo "ACCESS DENIED";
    }
}

function del_comment($cid, $id) {
    global $admin, $prefix, $dbi, $module_name;
    if (is_admin($admin)) {
        sql_query("delete from ".$prefix."_reviews_comments where cid='$cid'", $dbi);
        Header("Location: modules.php?name=$module_name&rop=showcontent&id=$id");
    } else {
        echo "ACCESS DENIED";
    }
}

switch($rop) {	

	case "showcontent":
	showcontent($id, $page);
	break;

	case "preview_review":
	preview_review($restoid, $userrvoto, $vrfecha, $vrfood, $vrservice, $vrambiance, $vroverall, $vrclean, $vrmoney, $vrwinelist, $vrkid, $vrcoment, $vrlanguage);
	break;

	case ""._YES."":
	send_review($restoid, $userrvoto, $vrfecha, $vrfood, $vrservice, $vrambiance, $vroverall, $vrclean, $vrmoney, $vrwinelist, $vrkid, $vrcoment, $vrlanguage);
	break;

	case "del_review":
	del_review($id_del);
	break;

	case "mod_review":
	mod_review($id);
	break;

	case "postcomment":
	postcomment($id, $title);
	break;

	case "savecomment":
	savecomment($xanonpost, $uname, $id, $score, $comments);
	break;

	case "del_comment":
	del_comment($cid, $id);
	break;

	default:
	write_review($restoid);
	break;
}

?>