<?php 

/************************************************************************/
/* MODULO DE LISTADO DE HOTELES                                           */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2003 por Enrique Adelino Montes Araujo                 */
/* http://www.turista.com.mx                                            */
/*                                                                      */
/* =========================                                            */
/************************************************************************/

if (!defined('MODULE_FILE')) {
	die ("You can't access this file directly...");
}
//define('NO_EDITOR', 1); //For Php-Nuke 7.8 remove it for previous vertions
$version_MHOTELES = "1.3.2"; 

//$banners = 0;
$index = 3;

$module_name = basename(dirname(__FILE__));
define('_MODULE_NAME',$module_name);
get_lang(_MODULE_NAME);

//require_once("mainfile.php");

include("modules/"._MODULE_NAME."/h_config.php");
include("modules/"._MODULE_NAME."/funcs.php");


//               ************************************************
//               ***  Empieza parte de desplegado principal  ****
//               ************************************************


global $sitename,$selvista,$currentlang, $selame, $aselame;
$tempsitename=$sitename;
$sitename="";
//          *****************************************
//          ***  Definición del Título de Página  ***
//          *****************************************
$checavista = checavista($selvista);
if (!$checavista ){
	header("HTTP/1.0 404 Not Found");
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();


}

if (isset($selvista) or isset($busca)){
	$vistasinespacios = str_replace(" ","_",$checavista );
	$pagetitle = _GUIDEFOR." "._HOTELSIN." ".$vistasinespacios." ".str_replace(" ","_",$tempsitename);
	$despliegaprincipal = false;
} else {// isset($selvista) 
  if($currentlang == 'spanish'){
    $pagetitle = $titulopaginaspanish;
  }  
  else{
    $pagetitle = $titulopaginaenglish;
  }  
 $despliegaprincipal = false;     
}

if (isset($selame))
  if ($selame<>'resta'){
    $pagetitle .= nombreamenitie($selame);
  }  
  else{	
    $pagetitle .= " - $selame";
  }	
	
if (isset($page))
  $pagetitle .= " - pag$page"; 
  
 

  if (!$despliegaprincipal){     // si no es el desplegado principal pone keywords genérícos para la meta description
      $sloganaux = _HOTELSIN." ".$checavista;
      if ($currentlang=='english')
          $sloganaux .= $sloganauxprincipalenglish;
      else
          $sloganaux .= $sloganauxprincipalspanish;
      $subsmetas = _AMENITIES.", Hotel,"._RESERVATION.","._CUARTOS.","._VISTA.",";
      $subsmetas .= $tempsitename.","._GUIDEFOR." "._HOTELS.","._HOTELSBETTERRATES.","._HOTELSIN." ".$checavista; 
  } else {
      if ($currentlang=='spanish'){
        $sloganaux = "Guia de hoteles en Mexico";
        $subsmetas = "hoteles en mexico, hoteles mexico, turismo cultural, sol y playa";
      }else{
        $sloganaux = "Guide for Hotels in Mexico";
        $subsmetas = "mexico hotels, sun and beach, cultural tourism, hotels in mexico";    
      }    
  }  
// Terminan definiciones de título y página

// Rutina para que despliegue en una nueva ventana el html referente a la zona (contenido en el directorio docs)
$archliga = _MHOTELES;
if (isset($selvista)) $archliga .= "-".str_replace(" ","-",$checavista);
$letliga=str_replace("-"," ",$archliga);
$letliga=str_replace(_MHOTELES,"",$letliga);
if ($currentlang=="english")
  $nombreestado = str_replace("é","e",$nombreestado);
$letliga.= " ".$nombreestado;
$archliga = str_replace("ú","u",$archliga); // para que quite los acentos en el nombre de archivo
$archliga = str_replace("á","a",$archliga);
$archbanner = $archliga.".jpg"; // archivo para banner
$archliga = "modules/"._MODULE_NAME."/docs/$archliga-$currentlang.php";

$htmlbanner='';
if(file_exists("modules/"._MODULE_NAME."/images/banners/$archbanner")) // checa si existe el archivo del banner
  $htmlbanner = "<img src=\"modules/"._MODULE_NAME."/images/banners/$archbanner\" alt=\"$letliga\" title=\"$letliga\" border=\"0\" height=\"60\">";
else
  $htmlbanner = $letliga;  
//if(!file_exists("modules/_MODULE_NAME/docs/$archliga")) // si no existe el archivo de Documentación (intro)
  if($htmlbanner=='')
    $subsbanner = "<center><big>"._HOTELSIN." <B>".$checavista."</B><big></center>";
  else
    $subsbanner = $htmlbanner;

// termina la rutina de desplegar liga en ventana nueva


// Empezamos parte del desplegado en sí
$subsbanner .= "<img src=\"modules/"._MODULE_NAME."/images/banners/instruc-$currentlang.gif\" alt=\" \">";

include("header.php");

if($currentlang=="spanish"){
  echo "<!-- Este módulo de hoteles es un desarrollo del Turista México a cargo de Enrique Adelino Montes Araujo -->";
  echo "\n<!-- Si su hotel no aparece listado o tiene errores comuníquese con nosotros al (998) 843 57 74 en Cancún, Quintana Roo, México -->";
} else {
  echo "<!-- This hotels module is a development of Turista Mexico made by Enrique Adelino Montes Araujo -->";
  echo "\n<!-- If your hotel is not listed here or has errors please contact us in the phone (52)(998) 843 57 74 in Cancun, Quintana Roo, Mexico -->";
}    

if (!$despliegaprincipal){
    if (!isset($page) || $page=='') 
      $inicio=0;
    else 
      $inicio=($page-1)*$cuantos;

    $limite = "limit $inicio,$cuantos";
    $letparaseleccionar=_PARASELECCIONARAME.":<br>"; // Letrero en el bloque de la izquierda de las amenities que aparecesolamente si no hay amenities en el filtro

    // ** inicia definición del where 
    if (!isset($busca)){
      if (!isset($selvista)) $selvista='1';
      if ($selvista=='All' ) $where='';
        else $where="where vista='$selvista'"; 
      
    } // if !isset $busca
    else {
      $where = "where nombre like '%".$busca."%'";	
      $cuantos = 50;
    } // else if isset $busca 
     
	    // *** Comienza el engorroso asunto de los filtros para los amenities

    $expselame = explode('-', $selame);  //luego mandan cadenas como 17-a25a83a18-restaa83a12-restaa83-resta-a25a83-restaa83-resta-a25a83a83-restaa17-a25a83a18-restaa83a12-restaa83-resta-a25a83-restaa83-resta
    $expselame = array_filter($expselame);
    $selame = '';
    foreach ($expselame as $value){
    	if ($value<>''){
    		if ( (substr($value, 0,1)=='a') && (substr($value, 1,1)<>'a') ){
    			$aselame = $value;
    			break;
    		} else {
    			if ($selame==''){
    				$selame = $value;
    			}
    		}
    	}
    }
    // Zend_Debug::dump($expselame);
    // Zend_Debug::dump($selame);
    // Zend_Debug::dump($aselame);
    
    if ($selame <> '' && $selame<>'resta'){
    	$where.=" AND SubString(amenities,".$selame.",1)='1'";
    }
    
    if ($aselame <> ''){
    	$arreglo = explode('a', $aselame);
    	foreach ($arreglo as $value){
    		//Zend_Debug::dump($value);
    		if(is_numeric($value)){
    			$where.=" AND SubString(amenities,".$value.",1)='1'";
    		}
    	}
    }
      
    // Termina definición del where	

    if (!isset($sortby)) { $sortby = "rating"; }
    if ($sortby=='rating') $order = 'order by booking DESC,rating DESC, ames DESC';
    if ($sortby=='nombre') $order = 'order by booking DESC,nombre';
    $sql="select *, length(replace(amenities,'0','')) as ames from ".$prefixhot."_hoteles $where and visible='1' $order $limite";

    // ** Bloque de los resultados de las búsquedas
      $sqlcuenta="select *, length(replace(amenities,'0','')) as ames from ".$prefixhot."_hoteles $where and visible='1' $order"; // este va sin el limit para que dé el total de encontrados
      $numhotels=$dbhot->sql_numrows($dbhot->sql_query($sqlcuenta) );
      $textofound="<b>$numhotels</b> "._HOTELSFOUND.":";
      $textofound2 .= "<br><B>"._MHOTELES." ".$checavista."</b>"; 

     
      if(isset($selame) and $selame<>''){ 
        if ($selame<>'resta'){ 
	      $arreglo=explode("a",$aselame);
	      if (!in_array($selame,$arreglo))
	        $aselame .="a$selame";
	    } // if $selame<>resta	     
        $textofound2.="<br>"._AMENITIES.": <BR>".facilities($selame,$aselame,'si');	
      } // if isset $selame 
      $textofound.=$textofound2; 	
      if ($numhotels > $cuantos){
        if(!isset($page) || $page=='')
	      $page=1;
        $total_pags = ceil($numhotels / $cuantos);
	    $textofound.="<p align=\"center\"><b>$total_pags</b> "._PAGES;
	    $textofoundpaginas = "<br>[";  // este es un añadido para tener en una variable aparte el listado de ligas a las páginas de resultados
	    for($n=1;$n<$total_pags+1;$n++){
	      if ($page==$n)
	        $textofoundpaginas.=" <b>$n</b> ";
	      else
	        $textofoundpaginas.="<A HREF=\"modules.php?name="._MODULE_NAME."&amp;selvista=$selvista&amp;selame=$selame&amp;aselame=$aselame&amp;page=$n\">$n</a>";	
	      if ($n<$total_pags)
	        $textofoundpaginas.=" | ";
	    } // for $n
	    $textofoundpaginas.= "]</p>";
      } // if numhotels > cuantos
} // if !$desplegadoprincipal

//vistas();

// *******************************************
// ** Desplegado de Bloques de la izquierda **   
// *******************************************

echo "\n";
if ($currentlang=='spanish')
  echo "<!-- Inicia parte con el desplegado de los bloques de la izquierda con los idiomas, hoteles en $nombreestado, hoteles top y otros destinos para hotel -->";
else
  echo "<!-- Begin the display of the left blocks with the languages $nombreestado hotels, top hotels, and other destinations -->";  
echo "\n";
echo "<table border=\"0\" align=\"center\" width=\"100%\">
       <tr><td valign=\"top\" >";

if (!$despliegaprincipal){     
include("modules/"._MODULE_NAME."/includes/block-Regiones.php");
themesidebox($titulo,$content);

if(!isset($busca)){
  themesidebox(_TOPHOTELS." ".substr($checavista,0,8),top_hotels($selvista,$cuantos));
}  
// Termina Bloque de Top Hotels 

// Bloque de "Otros Destinos"
include("modules/"._MODULE_NAME."/includes/block-Otros_Destinos.php");
themesidebox('Other Destinations',$content);

 } //if!despliegprincipal

if($currentlang=='spanish')
echo "<!-- Inicia parte del desplegado central con el listado de hoteles -->
<!-- Procuramos hacer bastante énfasis en $subsmetas -->\n";
else
echo "<!-- Begins the part with the center display of the hotels listing -->
<!-- we procure to emphatice $subsmetas -->\n";
echo "</td><td valign=\"top\" align=\"center\">\n";

// **************************************************
// ** Desplegado del listado resultante de Hoteles **
// **           Parte Central                      **
// **************************************************
//OpenTable();

//SortLinks($letter,$selvista,$sortby,$selame,$aselame);
//echo $sql;

if ($despliegaprincipal)   {
  if (file_exists("themes/$ThemeSel/hotel-home-main-$currentlang.html"))
	    $tmpl_file = "themes/$ThemeSel/hotel-home-main-$currentlang.html";
	  else
	    $tmpl_file = "modules/"._MODULE_NAME."/templates/hotel-home-main-$currentlang.html";
	  $thefile = implode("", file($tmpl_file));
      $thefile = addslashes($thefile);
      $thefile = "\$r_file=\"".$thefile."\";";
      eval($thefile);
      print $r_file;           
}else { // Si no es el desplegado principal saca el listado general


  if($numhotels == 0)
    echo "<big><br><br>"._NOHOTELS.": $textofound2</BIG>";

  despliega_listado($sql);
  
      // Resultado inferior de los filtros  y paginado
    if (!isset($busca)){
      OpenTable();      
      echo "<table align=\"center\" border=\"0\"><tr><td align=\"center\" >";
      $prev_pag = $page -1;
      if($prev_pag > 0)
        echo "<A HREF=\"modules.php?name="._MODULE_NAME."&amp;selvista=$selvista&amp;selame=$selame&amp;aselame=$aselame&amp;page=$prev_pag\">
	    <img src=\"modules/"._MODULE_NAME."/images/nanterior.gif\" alt=\""._MHOTELES." ".$checavista."&#10;"._PREVIOUS." ($prev_pag)\" border=\"0\"><BR>"._PREVIOUS."</a>";
      echo "</td><td align=\"center\">";
      echo $textofound.$textofoundpaginas;
      if ($numhotels > $cuantos) $mostrados=$cuantos;
      else $mostrados=$numhotels;
      echo "<br><b>$mostrados</b> "._HOTELSSHOWN;
      echo "</td><td align=\"center\">";
      $next_page = $page + 1;
      if($next_page <= $total_pags)
        echo "<A HREF=\"modules.php?name="._MODULE_NAME."&amp;selvista=$selvista&amp;selame=$selame&amp;aselame=$aselame&amp;page=$next_page\">
	    <img src=\"modules/"._MODULE_NAME."/images/nsigiente.gif\" alt=\""._MHOTELES." ".$checavista."&#10;"._NEXT." ($next_page)\" border=\"0\">
        <br>"._NEXT."</a>";
	    
      echo "</td></tr></table>";
      echo "<!-- ".$checavista." -->";  
      CloseTable();   
    } // if not isset $busca 
}   // else de if $despliegaprincipal


// ***************************
// ** Bloques de la derecha **
// ***************************

if ($currentlang=="spanish")
  echo "<!-- Inicia el desplegado de los bloques de la derecha  con el resultado de filtros, búsqueda de hotel y listado de servicios-->\n";
else
  echo "<!-- Begins the displaying of the right blocks with the filtering result, hotel search and the list of amenities-->\n";  
echo "</td><td valign=\"top\" align=\"right\" >\n";

themesidebox(_SEARCH." Hotel",search());

if (!isset($busca)){    
    if (!$despliegaprincipal){
      themesidebox(_FILTERRESULTS,$textofound);
      if($numhotels > 1){
      	themesidebox(_AMENITIES,$letparaseleccionar.facilities($selame,$aselame,'no'));
      }
       
    }  
}

/*
include("blocks/block-Languages.php");
themesidebox(_LANGUAGES,$content);                 //CAUSA UNA BRONCA CON NUKE 8.0
*/ 

// ** Terminan Bloque de la derecha
echo "</td></tr></table>";

 
/*$archliga = _MHOTELES;
if (isset($selvista)) $archliga .= "-".str_replace(" ","-",$checavista);
$letliga=str_replace("-"," ",$archliga);
$archliga .= "-$currentlang";
echo "<a href=\"modules/_MODULE_NAME/docs/$archliga.html\" target=\"blank\">$letliga</a>";*/
if (!$despliegaprincipal) 
  echo "<h1><center>"._HOTELS." ".$checavista." $nombreestado</center></h1>";
echo "<!-- ".$checavista." -->";

include("footer.php");


?>
