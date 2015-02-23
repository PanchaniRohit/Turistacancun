 <?php

 /************************************************************************/
 /* TURISTA CANCUN: Módulo para el home                                  */
 /* ===========================                                          */
 /* 
 /************************************************************************/

 if (!defined('MODULE_FILE')) {
 	die ("You can't access this file directly...");
 }

 require_once("mainfile.php");
 $module_name = basename(dirname(__FILE__));
 get_lang($module_name);

 global $prefix, $db, $CONFIG, $Version_Num, $cpg_dir;
//global $ALBUM_SET;
$cpg_dir = "gallery";
$albums_dir = "albums";

include "$cpg_dir/include/config.inc.php";
$dbgal = new sql_db($CONFIG['dbserver'], $CONFIG['dbuser'], $CONFIG['dbpass'], $CONFIG['dbname'], false);
$cpg_prefix = $CONFIG['TABLE_PREFIX'];


$numberpic=5; //number of thumbs
$limit = $numberpic;

$result = $dbgal->sql_query("SELECT COUNT(*) from " . $cpg_prefix . "pictures WHERE approved = 'YES' $ALBUM_SET");

$nbEnr = $dbgal->sql_fetchrow($result);
$pic_count = $nbEnr[0];

// if we have more than 1000 pictures, we limit the number of picture returned
// by the SELECT statement as ORDER BY RAND() is time consuming
if ($pic_count > 1000) {
    $result = $dbgal->sql_query("SELECT COUNT(*) from " . $cpg_prefix . "pictures WHERE approved = 'YES'");
    $nbEnr = $dbgal->sql_fetchrow($result);
    $total_count = $nbEnr[0];

    $granularity = floor($total_count / 1000);
    $cor_gran = ceil($total_count / $pic_count);
    srand(time());
    for ($i = 1; $i <= $cor_gran; $i++) $random_num_set = rand(0, $granularity) . ', ';
    $random_num_set = substr($random_num_set, 0, -2);

    $result = $dbgal->sql_query("SELECT pid, filepath, filename, p.aid, p.title FROM ".$cpg_prefix."pictures AS p INNER JOIN ".$cpg_prefix."albums AS a ON (p.aid = a.aid AND ".VIS_GROUPS.") WHERE randpos IN ($random_num_set) AND approved='YES' GROUP BY pid ORDER BY RAND() DESC LIMIT $limit");
} else {
	$sql = "SELECT pid, filepath, filename, p.aid, p.title FROM ".$cpg_prefix."pictures AS p INNER JOIN ".$cpg_prefix."albums AS a ON (p.aid = a.aid) WHERE approved='YES' GROUP BY pid ORDER BY RAND() DESC LIMIT $limit";
    $result = $dbgal->sql_query($sql);
    
}

$tirafotos = "";
$tirafotos .= '<table width="100%" border="0" align="center" cols="' . $limit2 . '" cellpadding="0" cellspacing="0"><tr>';

$contador = 1;
foreach ($result as $row){
    
        if ($row['title'] != '') {
            $thumb_title = $row['title'];
        } else {
            $thumb_title = substr($row['filename'], 0, -4);
        } 
    
    stripslashes($thumb_title);
    //' . get_pic_url($row, 'thumb') . '
    if ($contador < $numberpic){ 
      $picurl = $cpg_dir."/".$albums_dir."/".$row['filepath']."/thumb_".$row[filename];
      $tirafotos .= '<td align="center" valign="baseline">
      <a href="modules.php?name=Photos&amp;pid='.$row['pid'].'" onclick="NewWindow(this.href,\'mywin\',\'802\',\'380\',\'no\',\'center\');return false" ><img src="'.$picurl.'" border="0" alt="' . $thumb_title . '" title="' . $thumb_title . '"><br />' . $thumb_title . '</a></td>';
      $contador ++;
    } else {//if contador 
      $fotocentral = $cpg_dir."/".$albums_dir."/".$row['filepath']."/".$row[filename];	
      $image_title = $thumb_title;
    }	
} 
$tirafotos .= '</tr><tr align="center"><td colspan="' . $limit2 . '" valign="baseline"><a href="' . $CPG_M_URL . '">' . $lang_pagetitle_php["photogallery"] . '</a></center></td></tr></table>';

/******************************************************
/* Empieza desplegado de texto principal
/*******************************************************/
	$content .= "<table border=\"0\"><tr><td><p class=\"Cuerpoderecha\"> 
	<img src=\"$fotocentral\" border=\"0\" alt=\"$image_title\" title=\"$image_title\" align=\"left\" height=\"200\">";
    $inicial = substr(_INTRODUCCION1,0,1);
	$restointro = substr(_INTRODUCCION1,1);
	$content .= "<font class=\"inicial\">$inicial</font>$restointro</p>
	<br><br><p class=\"Cuerpoderecha\">"._INTRODUCCION3."</p>
	   </td></tr>  <tr><td>                                    
       <center><h1>"._INTRODUCCION2."</h1></center>                      
       
       <p class=\"titulohotel\">"._INTRODUCCION4."</font></p></td></tr></table>";
/******************************************************
/* Termina desplegado de texto principal
/*******************************************************/
$content .= $tirafotos;	
global $pagetitle;
$pagetitle=_PAGETITLE;
include ("header.php");
Opendivright();
OpenTable();
echo $content;
CloseTable();
Closediv();
echo "<br>";
include("footer.php");
 

?>