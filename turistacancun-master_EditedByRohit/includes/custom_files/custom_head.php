<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2004 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

echo '<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-693957-18";
urchinTracker();
</script>';

ob_start();

function replace_for_mod_rewrite(&$s) {
global $currentlang;
$urlin = array(


// Photos
"'(?<!/)modules.php\?name=Photos&pid=([0-9]*)&htaid=([0-9]*)&page=([0-9]*)'",
"'(?<!/)modules.php\?name=Photos&aid=([0-9]*)&page=([0-9]*)'",
"'(?<!/)modules.php\?name=Photos&amp;aid=([0-9]*)'",
"'(?<!/)modules.php\?name=Photos&amp;pid=([0-9]*)'",

// Secciones
"'(?<!/)modules.php\?name=Sections&amp;op=(printpage|viewarticle)&amp;artid=([0-9]*)&amp;page=([0-9]*)'e",
"'(?<!/)modules.php\?name=Sections&amp;op=(printpage|viewarticle)&amp;artid=([0-9]*)'e",
"'(?<!/)modules.php\?name=Sections&amp;op=listarticles&amp;secid=([0-9]*)'",
"'(?<!/)modules.php\?name=Sections&amp;op=listsections'",
"'(?<!/)modules.php\?name=Sections'",

// Empiezan Hoteles
//Bloques de Listados de Hoteles
"'(?<!/)modules.php\?name=hotels&amp;selvista=([0-9]*)&amp;selame=([a-zA-Z0-9_-]*)&amp;aselame=([a-zA-Z0-9_-]*)&amp;page=([0-9]*)'e",
"'(?<!/)modules.php\?name=hotels&amp;selvista=([0-9]*)&amp;selame=([a-zA-Z0-9_-]*)&amp;aselame=([a-zA-Z0-9_-]*)'e",
"'(?<!/)modules.php\?name=hotels&amp;selvista=([0-9]*)&amp;selame=([a-zA-Z0-9_-]*)'e",
"'(?<!/)modules.php\?name=hotels&amp;selvista=([0-9]*)'e", //para cuando las amenities están en blanco

//Para un solo hotel
"'(?<!/)modules.php\?name=hotels&amp;file=hotel&amp;hotelid=([0-9]*)&amp;newlang=([a-zA-Z]*)&amp;fecini=([a-zA-Z0-9_-]*)&amp;diasadesp=([0-9]*)&amp;adults=([0-9]*)'e",

"'(?<!/)modules.php\?name=hotels&amp;file=hotel&amp;hotelid=([0-9]*)&amp;newlang=([a-zA-Z]*)&amp;op=([a-zA-Z]*)'e",  // para op=fotos (por ejemplo)
//"'(?<!/)/modules.php\?name=hotels&file=hotel&hotelid=([0-9]*)&newlang=([a-zA-Z]*)'", // para nuevo lenguage
"'(?<!/)modules.php\?name=hotels&amp;file=hotel&amp;hotelid=([0-9]*)&amp;newlang=([a-zA-Z]*)'e",


"'(?<!/)/modules.php\?name=hotels&newlang=([a-zA-Z]*)'",
"'(?<!/)modules.php\?name=hotels'",

);  //termina primer array para substitución


$urlout = array(

// Photos
"cancun-photo-\\1-\\2-\\3.html",
"cancun-photos-\\1-\\2.html",
"cancun-photo-album-\\1.html",
"cancun-photo-\\1.html",

// incian SECTIONS
"nombre_articulopaginado(\\1,\\2,\\3)",
"nombre_articulo(\\1,\\2)",
"cancun-s-\\1.html",
"cancuninfolist.html",
"cancuninfo.html",

// Empiezan Hoteles
//Bloque de listados de Hoteles
"rhoteles('\\1').'-\\1-\\2-\\3-p\\4.html'",
"rhoteles('\\1').'-\\1-\\2-\\3.html'",
"rhoteles('\\1').'-\\1-\\2.html'",
"rhoteles('\\1').'-\\1.html'",

//Desplegado de un solo hotel

"rhotel('\\1').'-\\1-\\2-\\3-\\4-\\5.html'", // para desplegado de tarifas
"rhotel('\\1').'-\\1-\\2-\\3.html'",  // para vista de fotos op=fotos o x
//"hotel-\\1-\\2.html", //con cambio de idioma
"rhotel('\\1').'-\\1-\\2.html'",


"hoteles-lang-\\1.html",
"hotels.html",


);


$s = preg_replace($urlin, $urlout, $s);
return $s;
}

function nombre_articulopaginado($modo,$artid,$pagina){  // para que obtenga el nombre de un artículo
global $db,$prefix,$currentlang;
  $sql="SELECT title from ".$prefix."_sections_articles_titles 
  where artid='".$artid."' and language='".$currentlang."'";
  $renglon = $db->sql_fetchrow($db->sql_query($sql));
  $title=$renglon[title];
//$title.="$artid";
  $urlsection=strtolower( urlencode($title) );
  $articulo="$urlsection-$modo-$artid-$pagina.html";
  //return $artid;
  return $articulo;
}

function nombre_articulo($modo,$artid){  // para que obtenga el nombre de un artículo
global $db,$prefix,$currentlang;
  $sql="SELECT title from ".$prefix."_sections_articles_titles 
  where artid='".$artid."' and language='".$currentlang."'";
  $renglon = $db->sql_fetchrow($db->sql_query($sql));
  $title=$renglon[title];
//$title.="$artid";
  $urlsection=strtolower( urlencode($title) );
  $articulo="$urlsection-$modo-$artid.html";
  //return $artid;
  return $articulo;
}

function rhotel($hotelid){
	global $dbhot,$prefixhot,$currentlang;
	$cNoUsables = array('‡á','éŽ','í’','ó—','úœ','-','/','(',')');
	$cUsables = array  ('a','e','i','o','u','' ,'' ,'' ,'');
	$sql="SELECT nombre from ".$prefixhot."_hoteles where hotelid='".$hotelid."' limit 1";
	$result = $dbhot->sql_query_cached($sql,'hotelNombre' . $hotelid);
	$renglon = $dbhot->sql_fetchrow($result);
	$nombre = $renglon['nombre'];
	$sinacentos = str_replace($cNoUsables, $cUsables, $nombre);
	$urldesc=strtolower( urlencode($sinacentos) );
	if ($currentlang=='english')
		return "$urldesc-hotel";
		else
			return "hotel-$urldesc";
}//rhotel

function rhoteles($vista){
global $currentlang;
  $urldesc = consigue_vista($vista);
  if ($currentlang=='english')
    return "$urldesc-hotels";
  else
    return "hoteles-$urldesc";	
}

function consigue_vista($vista)
{
	$cNoUsables = array('‡á','éŽ','í’','ó—','úœ','-','/','(',')');
	$cUsables = array  ('a','e','i','o','u','' ,'' ,'' ,'');
	if(!empty($vista)){
		global $dbhot, $currentlang;
		$descri = 'hvi_desc_'.$currentlang;
		$sqlvistas2 = 'select '.$descri.' from hot_hotelesviews where hviid='.$vista;
		$resulview = $dbhot->sql_query_cached($sqlvistas2, 'vistaNombre' . $vista);
		$row = $dbhot->sql_fetchrow($resulview);
		$descrip = $row[$descri];
		$sinacentos = str_replace($cNoUsables, $cUsables, $descrip);
		$urldesc=strtolower(urlencode($sinacentos));
		return $urldesc;
	}

}

?>
