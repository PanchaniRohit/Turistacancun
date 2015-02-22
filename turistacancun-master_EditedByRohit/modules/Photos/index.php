        <?php

######################################################################
# Photos: Módulo cliente para tomar fotografías de coppermine
# ===========================================
#
#
# Copyright (c) 2006 Enrique Montes Araujo www.dstr.net
######################################################################

if (!defined('MODULE_FILE')) {
	die ("You can't access this file directly...");
}

//require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
include ("modules/$module_name/g-config.php");


$postcard = "<a href=\"$urlmaingallery/ecard.php?album=30&pid=$pid\" target=\"_blank\"><img src=\"modules/Photos/images/postcard.gif\" border=\"0\"></a>";

$perpage = 15;
if (!$page || $page==1){
  $min = 0;
  $page=1;
}  
else
  $min = ($perpage *($page-1));

if ($aid || $htaid ){ // si está seleccionada una categoría
  $tempaid = $aid;
	if (!$htaid ){
	  $sql="SELECT pid from ".$galprefix."pictures 
	  where aid='$aid' limit $min, 1";
	  $row=$dbgal->sql_fetchrow($dbgal->sql_query($sql) );
	  $pid=intval($row[pid]);
  	} else $tempaid = $htaid;
}

if(!$pid)
  $pid = 33;

// Obtiene los datos generales de la imagen
$sql="SELECT pid, aid, filepath, filename, pwidth, pheight, title, caption 
FROM ".$galprefix."pictures WHERE pid=$pid";
$row = $dbgal->sql_fetchrow($dbgal->sql_query($sql) );
$aid = intval($row[aid]);
$filepath = $row[filepath];
$filename = $row[filename];
$title = $row[title];
$caption = $row[caption];

//Obtiene los álbumes hermanos
if ($despliegaalbumeshermanos==1){
	$sql = "SELECT category from ".$galprefix."albums
	where aid='$aid'";
	$row=$dbgal->sql_fetchrow($dbgal->sql_query($sql));
	$category = $row[category];
	$sql = "SELECT aid, title from ".$galprefix."albums where category='$category' and visibility='0'";
	$result=$dbgal->sql_query($sql);
	$locres="modules.php?name=Photos&amp;aid=";
	
	$menuhermanos = "<select name=\"aid\" class=\"body\" style=\"font-size: 11px; width:180px;\" onChange=\"top.location.href=this.options[this.selectedIndex].value\">";
	//while($row = $db->sql_fetchrow($result)) {
	foreach ($result as $row){
	  $haid=intval($row[aid]);
	  $htitle=$row[title];
	  if ($tempaid==$haid) $selected="Selected"; else $selected="";
	  $menuhermanos .= "<option value=\"$locres$haid\" $selected>$htitle</option>";
	} //while
	$menuhermanos .="</select>";
}//if $despliegaalbumeshermanos

//Define la foto principal
$tipoimagen="normal_";
$archfoto = "$dirfotos/$filepath$tipoimagen$filename";
if (file_exists($archfoto))
   $fotoprincipal="$urlfotos/$filepath$tipoimagen$filename";  
else
  $fotoprincipal="$urlfotos/$filepath$filename";

//obtiene las fotos en el mismo album (Thumbs hermanos)
$sql = "SELECT pid, aid, filepath, filename, title from ".$galprefix."pictures where aid='$aid' order by pid limit $min,$perpage";
$result=$dbgal->sql_query($sql);
$tipoimagen="thumb_";
$fotosthumb1="";
$fotosthumb2="";
$fotosthumb3="";
$conta = 1;
foreach ($result as $row) {
  $htpid = intval($row[pid]);
  $htaid = intval($row[aid]);
  $htfilepath = $row[filepath];
  $htfilename = $row[filename];
  $httitle = $row[title];
  
  $liga = "<a href=\"modules.php?name=Photos&pid=$htpid&htaid=$htaid&page=$page\">";
  $archivothumb="$urlfotos/$htfilepath$tipoimagen$htfilename";
  $imagen = "<img src=\"$archivothumb\" title=\"$httitle\" width=\"72\" height=\"43\" border=\"0\" class=\"slides-pictures-tlbr\" vspace=\"4\">";
  if ($conta<6)
    $fotosthumb1.="$liga$imagen</a><br>";
  if ($conta>5 and $conta < 11)
    $fotosthumb2.="$liga$imagen</a><br>";
  if ($conta>10)
    $fotosthumb3.="$liga$imagen</a><br>";	
  $conta++;
} // while

#################################
##
## Definición del Paginado
##
#################################

$cuantos = $dbgal->sql_numrows($dbgal->sql_query("SELECT pid from ".$galprefix."pictures where aid='$aid' order by pid"));
$paginas = ceil($cuantos / $perpage);

if ($paginas > 1){
   for($n=1;$n<$paginas+1;$n++){
	  if ($page==$n)
	    $textofoundpaginas.=" <b>$n</b> ";
	  else
	    $textofoundpaginas.="<A HREF=\"modules.php?name=Photos&aid=$htaid&page=$n\">$n</a>";	
	  if ($n<$paginas)
	    $textofoundpaginas.=" | ";
	} // for $n
	$pagenext = $page+1;
	if ($pagenext <= $paginas)
 	  $medianext = "<a href=\"modules.php?name=Photos&aid=$htaid&page=$pagenext\"><img src=\"modules/$module_name/images/media-next.gif\" height=\"16\" width=\"85\" border=\"0\"></a>";
  
	$pageprev = $page-1;
	if ($pageprev > 0)
	  $mediaback = "<a href=\"modules.php?name=Photos&aid=$htaid&page=$pageprev\"><img src=\"modules/$module_name/images/media-back.gif\" height=\"16\" width=\"85\" border=\"0\"></a>";

}  // if paginas > 1

include_once("includes/custom_files/custom_head.php");

$tmpl_file = "modules/$module_name/photo-template.html";
$thefile = implode("", file($tmpl_file));
		$thefile = addslashes($thefile);
		$thefile = "\$r_file=\"".$thefile."\";";
		eval($thefile);
		print $r_file;
include_once("includes/custom_files/custom_footer.php");		
?>