<?php
/************************************************************************/
/* VISTA DE HOTEL                                                       */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 por Enrique Adelino Montes Araujo                 */
/* http://www.turista.com.mx                                            */
/*                                                                      */
/* =========================                                            */
/************************************************************************/


$index = 3;
global $hotelid, $dbhot;
$module_name = basename(dirname(__FILE__));
define('_MODULE_NAME',$module_name);
get_lang($module_name);
$footerhotelid=$hotelid;  // para que haga un respaldo y guarde el id para el footer
include("modules/$module_name/funcs.php");
include("modules/$module_name/h_config.php");





// ****************************************************
// ******** Inicia Parte de Desplegado Principal ******
// ****************************************************

$result = $dbhot->sql_query("select nombre,booking,bestdayid,vista,rating from ".$prefixhot."_hoteles WHERE hotelid=$hotelid");
$hotel = $dbhot->sql_fetchrow($result);
$nombre = $hotel['nombre'];
$booking = $hotel['booking'];
$bestdayid = $hotel['bestdayid'];
$vista = $hotel['vista'];
$rating = $hotel['rating'];
if ($currentlang=='english') $nombretitpag=""; else $nombretitpag="Hotel ";
$nombretitpag .= str_replace(" ","-",$nombre);
if ($currentlang=='english') $nombretitpag .= " Hotel - Booking on-line $opcion"; else $nombretitpag .= " Reservación en-linea $opcion";
$pagetitle = $nombretitpag;
if ($currentlang=='english') $sloganaux = ' Booking on line for '; else $sloganaux .= ' Reservación en línea para ';
$sloganaux .= " $nombre ($currentlang)";
if(isset($diasadesp)) $index=3; 
//$banners=false;  // para que no despliegue los banners y el usuario no se distraiga de la reservación
if ($booking == 1){  // para que no despliegue banners distractores en caso de que tenga booking
  $banners=false;
  $banhotel="modules/hoteles/images/banhotel/hotel-$hotelid-$currentlang.gif";
  if(file_exists($banhotel))
    $subsbanner="<img src=\"$banhotel\">";
} // if $booking  

// Inicias construcción de la navegación (Descripciòn, Ubicación, fotos, etc
   
	if (!isset($diasadesp)) {
	  $diasadesp=3;
	  if (!isset($rooms)) $rooms=1;
	  if (!isset($adults)) $adults=2;
	  if (!isset($children)) $children=0;
	  if (!isset($fecini)) $fecini=Date("Y-m-d", mktime(0,0,0,  date(m), date(d)+7, date(Y)) ); 
	  switch ($op){
	  case 'fotos':
		$textoadesplegar = navegacionhotel('fotos');
		$textoadesplegar.= "<br>".fotos($hotelid);
		$lettitpag=_GALERIAFOTOS;
	  break;	
	  case 'foros':
		$textoadesplegar = navegacionhotel('foros');
		$textoadesplegar.= "<br>".foros($hotelid);
		if ($currentlang=='english') $lettitpag='Forum'; else $lettitpag='Foros';
	  break;	
	  case 'ubica':
		$textoadesplegar = navegacionhotel('ubica');
		$textoadesplegar.= "<br>".ubica($hotelid);
		$lettitpag=_LOCATION;
	  break;	
	  case 'ratesbd':
		$textoadesplegar = navegacionhotel('ratesbd');
		$textoadesplegar.= "<br>".ratesbd($hotelid);
		$lettitpag=_ONLINERESERVATION;
	  break;	
	  case 'rooms':
		$textoadesplegar = navegacionhotel('rates');
		$textoadesplegar .= "<br>";
		$textoadesplegar.=despliega_tarifas($hotelid,$nombre,$fecini,$diasadesp,$rooms,$adults,$children);
		if ($currentlang=='english') $lettitpag='Rooms & Rates'; else $lettitpag='Cuartos y Tarifas';
	  break;	
	  default:
		$textoadesplegar=Ver_Hotel();
		//Letrero para título de página 
		if ($currentlang=='english') $lettitpag = checavista($vista).' '._MHOTELES;  
		else $lettitpag = 'Hoteles en '.checavista($vista);
	  break;
	  } //switch $op
	}  //if not set $diasadesp
	
	else { // de isset diasadesp
	  //$onlinetext=Pide_Fechas($fecini,$diasadesp,$rooms,$adults,$children);
	  //$onlinetext.="<br><br><center><b>[<a href=\"modules.php?name=hoteles&amp;file=hotel&amp;hotelid=$hotelid\" >"._VIEWHOTEL."</A>]</b></center>";  
	  $textoadesplegar = navegacionhotel('rates');
	  $textoadesplegar .= "<br>";
	  $textoadesplegar.=despliega_tarifas($hotelid,$nombre,$fecini,$diasadesp,$rooms,$adults,$children);
	}  

// Termina construcciòn de la navegación


// Inicia construcciòn del encabezado.  (Título de página, metaelementos, etc)
$tempsitename=$sitename;
$sitename='';
	/*
    if ($currentlang=='english') $nombretitpag=""; 
    else $nombretitpag="Hotel ";
    
	$nombretitpag .= str_replace(" ","-",$nombre);
	if ($currentlang=='english') $nombretitpag .= " Hotel - $lettitpag"; 
    else $nombretitpag .= " - $lettitpag";
    */
    if ($currentlang == 'english') 
      $nombrehotel = str_replace(" ","-",$nombre)." Hotel";
    else
      $nombrehotel = "Hotel ".str_replace(" ","-",$nombre);  
    
    $nombretitpag = $nombrehotel."/".$lettitpag;
       
	//$pagetitle = $nombretitpag.' - '.$nukeurl;
    $pagetitle = $nombretitpag;
	//if ($currentlang=='english') $sloganaux = ' Booking on line for '; else $sloganaux .= ' Reservación en línea para ';
	$sloganaux = " $nombre $lettitpag, ";
	if (!isset($externo))
	  $externo = 0;
	if ($externo == 1)
	  include("headerhotelexterno.php");
	else
	  include("header.php");
$sitename=$tempsitename;	  
//Termina construcción del encabezado

// ************************************************************
// **** Formación y desplegado de bloques de la izquierda *****
// ************************************************************
echo "<table border=\"0\" align=\"center\" width=\"100%\"><tr><td valign=\"top\" >";
if ($booking=='1'){      
  if($bestdayid > 0){
  	if ($currentlang == 'english'){
  		$idioma = 'ING';
  		$currency = 'US';
  	} else {
  		$idioma = 'ESP';
  		$currency = 'PE';
  	}
  	$fuenteBestday = 'http://www.e-tsw.com/Hoteles/Tarifas?Af=tcancun'
  			. '&Ln=' . $idioma . '&Ht=' . $bestdayid
  			. '&Cu=' . $currency;
    
	$textobook="<a href=\"$fuenteBestday\" target=\"blank\" class=\"topnav\">
	<img src=\"modules/$module_name/images/ratesandhelp-$currentlang.gif\" title=\""._CHECKRATES." $nombre\" border=0 align=\"center\"></a>
	";
  } else {
  	$textobook="<center><a href=\"modules.php?name=hoteles\">
    <img src=\"modules/$module_name/images/banners/nodisponible-$currentlang.gif\" border=\"0\"></a></center>";
  }
}
// }else{ //$textobooking == 1
//   /*if ($hotelid=='114')
//     $textobook="<a href=\"http://www.clubsolariscancun.com/modules.php?name=reservations&clubid=998\" target=\"blank\">Club Solaris Cancun</a>";
//   else  */
//    // $textobook=Pide_Fechas($fecini,$diasadesp,$rooms,$adults,$children);
// }	
themesidebox(_ONLINERESERVATION,$textobook );
  
  if($externo<>1){
    $textocaja  ="<a href=\"modules.php?name="._MODULE_NAME."\">"._HOTELSMAIN."</a><br><br>";
	$textocaja .="<a href=\"modules.php?name="._MODULE_NAME."&amp;selvista=$vista&amp;selame=&amp;aselame=\">"._HOTELSIN." ".checavista($vista)."</a><br><br>";
    $textocaja .="<a href=\"javascript:history.go(-1)\">"._REGRE1PAG."</a>";
    themesidebox(_OPTIONS,$textocaja);
  }
// Bloque de Idiomas (Causa Conflictos con el nuke 8.0
/*include("blocks/block-Languages.php");
themesidebox(_LANGUAGES,$content);
*/
  


// **************************************************
// ** Desplegado del listado resultante de Hoteles **
// **           Parte Central                      **
// **************************************************
echo "</td><td valign=\"top\" align=\"center\">";
 


// Parte donde manda la plantilla para el desplegado
	if ($externo == 1)
      $tmpl_file = "modules/hoteles/hotel-templateexterno.html";
	else
	  if (file_exists("themes/$ThemeSel/hotel-template.html"))
	    $tmpl_file = "themes/$ThemeSel/hotel-template.html";
	  else
	    $tmpl_file = "modules/hoteles/templates/hotel-template.html";
    $thefile = implode("", file($tmpl_file));
    $thefile = addslashes($thefile);
    $thefile = "\$r_file=\"".$thefile."\";";
    eval($thefile);
    print $r_file;
	
// termina parte en donde manda la plantilla para el desplegado

   
// ***************************
// ** Bloques de la derecha **
// ***************************
echo "</td><td valign=\"top\" align=\"right\" >";

// Bloque para más hoteles  
$tophotelstext = "<B><center>".checavista($vista)."</b>";
$numtop=20;
if ($rating == 6){
	if ($currentlang == 'spanish'){
		$letrating = "Gran Turismo";
	} else {
		$letrating = "Grand Tourism";
	}
} else {
	$letrating = $rating." "._STARS;
}
$tophotelstext .= "<br><b><small>$letrating</small></b></center>";
$sqltop="select hotelid, rating, nombre from ".$prefixhot."_hoteles where vista='$vista' and rating='$rating' and visible<>'0' limit $numtop";
$resulttop = $dbhot->sql_query($sqltop);
if ($op <> '') $desop="&amp;op=$op"; //para que no ponga una rayita extra en el url del hotel
foreach ($resulttop as $hottop){
	$hotelidlista = $hottop['hotelid'];
	$estrellas = $hottop['rating'];
	$nombrelista = $hottop['nombre'];
 if ($hotelidlista<>$hotelid)
   $tophotelstext .= "<br><a href=\"modules.php?name="._MODULE_NAME."&amp;file=hotel&amp;hotelid=$hotelidlista&amp;newlang=$currentlang$desop\" >$nombrelista</a>";
 else
   $tophotelstext .= "<br><b>$nombrelista</b>";  

}  
if ($externo<>1)
  themesidebox(_MOREHOTELS,$tophotelstext );  



// ** Terminan Bloque de la derecha
echo "</td></tr></table>";



if ($externo == 1){    
  echo "<center><h1>".str_replace(" ","-",$nombre)."</h1></center>";
  include("footerhotelexterno.php");
  }
else{
    // $nombretitpag = $lettitpag."/".$nombrehotel;
  echo "<center><h1>$lettitpag - $nombre</h1></center>";
  include("footer.php");
}  
?>
