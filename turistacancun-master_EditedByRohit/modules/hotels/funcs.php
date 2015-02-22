<?php

function checanombrecuarto($hotelid,$productid) { //  básicamente es usada por el yieldmanagement
global $prefixhot,$dbhot;
$resultNOMCUA=$dbhot->sql_query("SELECT pnombre FROM ".$prefixhot."_hotelesproductos WHERE hotelid='$hotelid' and productid='$productid'");
			list($pnombre) = $dbhot->sql_fetchrow($resultNOMCUA);
			return $pnombre;
}

function nombreamenitie($amid){
global $prefixhot,$dbhot,$currentlang;
  $sql="select hfa_desc_".$currentlang." from ".$prefixhot."_hotelesfacili where hfaid='$amid'";
      $resame = $dbhot->sql_query($sql);
      $amenombre = $dbhot->sql_fetchrow($resame);
 return $amenombre;	  
} // nombreamenitie



function checavista($viewid) {
global $prefixhot, $dbhot,$currentlang;
 $regreso = _ALL;
 $sqlreg='select * from ' .$prefixhot. '_hotelesviews where hviid="' .$viewid . '"';
    $resulview = $dbhot->sql_query($sqlreg);
    if ($resulview){
    	$row = $dbhot->sql_fetchrow($resulview);
    	$descri="hvi_desc_".$currentlang;
    	$regreso = $row[$descri];
    }
   return $regreso;
}

function getMonthName2($Date) {
        $month = Date("m",$Date);
        if ($month == "01") {
                $monthname = _CALJAN;
        } elseif ($month == "02") {
                $monthname = _CALFEB;
        } elseif ($month == "03") {
                $monthname = _CALMAR;
        } elseif ($month == "04") {
                $monthname = _CALAPR;
        } elseif ($month == "05") {
                $monthname = _CALMAY;
        } elseif ($month == "06") {
                $monthname = _CALJUN;
        } elseif ($month == "07") {
                $monthname = _CALJUL;
        } elseif ($month == "08") {
                $monthname = _CALAUG;
        } elseif ($month == "09") {
                $monthname = _CALSEP;
        } elseif ($month == "10") {
                $monthname = _CALOCT;
        } elseif ($month == "11") {
                $monthname = _CALNOV;
        } elseif ($month == "12") {
                $monthname = _CALDEC;
        }
        return $monthname;
}




function calc_rooms($adults,$maxadu,$children,$maxnin){
$calcrooms=ceil($adults/$maxadu);  // número de cuartos necesarios para los adultos
		if ($children > 0){
		  if($maxnin<>0){
		    $roomsnin=ceil($children/$maxnin); // número de cuartos necesarios para los niños
		    $totmaxocu=$calcrooms*$maxocu; // Total ocupación máxima en personas
			$persdisp=$totmaxocu-$adults; // personas disponibles
			if($roomsnin>$calcrooms){
			  $calcrooms=$roomsnin; 
			  $totmaxocu=$calcrooms*$maxocu; // Total ocupación máxima en personas
			  $persdisp=$totmaxocu-$adults; // personas disponibles 
			}  			
			if($children>$persdisp){
			  if ($roomsnin>=$calcrooms)
			    $calcrooms=$roomsnin+1;
			  else
			    $calcrooms++;	
			} 
		  } // if $maxnin<>0
		} // if $children > 0
		return $calcrooms;
}

function calc_cuartos($calcadults,$maxadu,$calcchildren,$maxnin){
global $module_name;
 while($calcadults>0){		
		    $rooms++;
			if($calcadults>=$maxadu) $letadults=$maxadu; else $letadults=$calcadults;
			if ($calcchildren>0){ //si solicitaron niños el asunto se complica			
			  if($calcchildren>=$maxnin) $letchildren=$maxnin; else $letchildren=$calcchildren;			  
			}  else $letchildren=0; 
			//if($letadults+$letchildren >= $maxocu)  //ocupación máxima de 3 peronas
			    
		    $xproductos.= _ROOM."<B>$rooms</b><br>
			&nbsp;&nbsp;<img src=\"modules/$module_name/images/cuadults.gif\" title=\""._ADULTS."\" height=\"16\" ><b>$letadults</b>
			<img src=\"modules/$module_name/images/cuchild.gif\" title=\""._KIDS."\" height=\"14\"><b>$letchildren</b>
			<br>";
			
			$calcadults=$calcadults - $maxadu; 
			$calcchildren=$calcchildren-$maxnin;
			
		}  // while       
	return array ($rooms,$calcchildren,$xproductos);
}



function despliega_calendariotarifas($rooms,$anyo,$mes,$dia,$hotelid, $productid, $fecini, $diasadesp, $adults,$children,$costeo,$maxadu){
global $prefixhot, $dbhot,$currentlang,$externo;
  $bgcolor2="#CCF2FF";
  $disponible=true;  // hacemos el cuarto disponible hasta que se demuestre lo contrario (para desplegado del botón de booking)	
  if ($diasadesp > 7) // por si hay más de una semana
    $semanas=ceil($diasadesp/7); 
  else
    $semanas = 1; 
	
  	  
  $xproductos.="<table border=\"0\"><tr><td valign=\"top\">";	 
  $xproductos.="<table bgcolor=\"#ffffff\" width=\"75%\" align=\"center\"><tr>"; 
  //Inicia encabezado con los días de la semana
  if ($diasadesp > 7 ){
    $contador=7;
    $xproductos.="<td bgcolor=\"$bgcolor2\">&nbsp;</td>";
  }else
    $contador=$diasadesp;
  
  for ($i=0; $i<$contador; $i++) {
    $fecdes=Date("D", mktime(0,0,0, $mes, $dia+$i, $anyo) );
    $mesdes=substr($fecdes,4,2);
    $diades=substr($fecdes,6,2);
	if($currentlang=='spanish'){  //$fecdes = str_replace("Mon Tue Wed Thu Fri Sat Sun","Lun Mar Mie Jue Vie Sab Dom",$fecdes);
	  $fecdes=str_replace("Mon","Lun",$fecdes);$fecdes=str_replace("Tue","Mar",$fecdes);$fecdes=str_replace("Wed","Mie",$fecdes);
	  $fecdes=str_replace("Thu","Jue",$fecdes);$fecdes=str_replace("Fri","Vie",$fecdes);$fecdes=str_replace("Sat","Sab",$fecdes);$fecdes=str_replace("Sun","Dom",$fecdes);
	}
    $xproductos.= "<TD bgcolor=\"$bgcolor2\" noWrap align=middle width=\"10%\"><FONT class=footmsg><B>$fecdes</B></FONT></TD>";  
  } // for $i

  $xproductos.="</TR><tr>"; // termina encabezado con los días de la semana
  if ($semanas > 1){
    $xproductos.="<td bgcolor=\"$bgcolor2\"><table>";	  
    $xproductos.="<tr><td>wk1</td></tr>";
    $xproductos.="</table>";  
  } //  if semanas > 1
		
  //**********  iniciamos desplegado de tarifas *********	  
  $anyo = substr($fecini,0,4);$mes = substr($fecini,5,2);$dia = substr($fecini,8,2); 
  $pretotal=0; 
  $j=1; // contador para semanas 
  for ($i=0; $i<($diasadesp); $i++) {		  
    $fecdes=Date("Ymd", mktime(0,0,0, $mes, $dia+$i, $anyo) );
    $sqltari="select singlep,doublep,extra,child,minstay from ".$prefixhot."_hotelesprecios where hotelid='$hotelid' and productid='$productid' and fecpre='$fecdes' ";
    if ( $dbhot->sql_numrows($dbhot->sql_query($sqltari) ) > 0){  // obtiene las tarifas para el día en cuestión
      list($singlep, $doublep,$extra,$child,$minstay) = $dbhot->sql_fetchrow($resulttari);
      if ($costeo=='P'){	// Si el costeo de Cuarto está definido por personas   
	    $precio=0;
		$calcadults=$adults;    
        for($l=0;$l<$rooms;$l++){
		  if($calcadults>=$maxadu) $letadults=$maxadu; else $letadults=$calcadults;				
		  if ($letadults==1) $xprecio=$singlep; if ($letadults==2) $xprecio=$doublep; if ($letadults>2) $xprecio=$doublep+($extra*($letadults-2));		  
	      $precio=$precio+$xprecio;		
		  $calcadults=$calcadults - $maxadu; 
		} // for($l=0;$l<$rooms;$l++)
		if($children>0) $precio=$precio + ($children * $child);
	  } else $precio=$singlep*$rooms; // si el costeo es por cuartos la cosa está fácil, nada más multiplica los adultos por el precio
      $fprecio = "$".number_format($precio,2);	
	  $pretotal = $pretotal+$precio;
	  $preprom = $pretotal/$diasadesp;
      $fpreprom = "<B>$".number_format($preprom,2)."</b>";
      // empiesa el rollo para verificar disponibilidad
      $orden="cualquiera";
	  if ($i==0) $orden="primero";	
	  if ($i==$diasadesp-1) $orden="ultimo";
	  if ( checa_disponibilidad($hotelid,$productid,$fecdes,$orden,$rooms) ){ // si hay disponibilidad (definida en funcs.php)
		  $bgdispo = "#C6FFAA";
	  } else { // if checa_disponibilidad
		  $bgdispo = "#cccccc";
		  $fprecio = _NOTAVAILABLE;
		  $fpreprom = _NOTAVAILABLE;
		  $disponible = false;
	  } // else de if checa_disponibilidad
	  if ($diasadesp > 7 ){ // Si hay más de una semana a desplegar
	    if ($i/7 == $j){	    
			$j++;  
			$xproductos.= "</tr><tr><td bgcolor=\"$bgcolor2\">wk$j</td>";	
	    } // if $i/ == $j
      } // if $diasadesp >7
	  $diadelmes=$fecdes=Date("M/d", mktime(0,0,0, $mes, $dia+$i, $anyo) );
      $xproductos.= "<td bgcolor=\"$bgdispo\" align=\"right\"><font class=\"footmsg\">$diadelmes<br> <b>$fprecio</b></font></td>";
    } // if sql_numrows que verifica que estén definidas tarifas para el día a desplegar
	else{	
	  if ($diasadesp > 7){ // si hay más de una semana a desplegar
	    if ($i/7 == $j){
		  $j++;
		  if ($i==7)
				  //$xproductos.= "<td rowspan=$semanas bgcolor=\"$bgcolor2\">&nbsp;</td><td rowspan=$semanas bgcolor=\"$bgcolor2\">"._NOTAVAILABLE."</td>";
				$xproductos.= "</tr><tr><td bgcolor=\"$bgcolor2\">wk$j</td>";
		  } // if ($i==7)
		} // if ($i/7 == $j)
		$bgdispo = "#cccccc"; 	  		  
		$xproductos.= "<td align=\"center\" bgcolor=\"#cccccc\"><b>"._NOTAVAILABLE."</b></td>";			
		$disponible = false;
    } // else de if sql_numrows que verifica que estén definidas tarifas para el día a desplegar
  } // for $i	(contador de días a desplegar)	
			
			
			$xproductos.="</tr></table>";
			
  $xproductos.="</td>";
  if($disponible){
    $botonbooking = "<a href=\"modules.php?name=reservaciones&amp;op=3&amp;hotelid=$hotelid&amp;productid=$productid
			  &amp;fecini=$fecini&amp;diasadesp=$diasadesp&amp;rooms=$rooms&amp;adults=$adults&amp;kids=$children
			  &amp;preprom=$preprom&amp;total=$pretotal&amp;newlang=$currentlang&amp;externo=$externo\">
			  <img src=\"modules/hoteles/images/bookit-$currentlang.gif\" border=\"0\"></a>";
    $xproductos.="<td valign=\"top\">
    <table border=\"0\" cellspacing=\"0\"><tr>
    <TD bgcolor=\"$bgcolor2\" colSpan=\"2\" align=\"center\"><FONT class=colHead><B>"._AVERAGERATE."</B> </FONT></TD></tr>
    <tr bgcolor=\"#ffffff\"><td align=\"center\">$fpreprom<br><font class=\"footmsg\">("._PERNIGHT.")</font></td><td>$botonbooking</td>
    </tr></table>";
  } // if $disponible	
  $xproductos.="</td></tr></table>";
  return array($xproductos,$preprom,$pretotal);
} //despliega_calendariotarifas

function checa_disponibilidad($hotelid,$productid,$fecinv,$orden,$rooms){ // Función para checar la disponibilidad de un cuarto
// la variable $orden sirve para saber el orden del día (si es el primero o el último para saber si hay closed on arrival y esas cosas)
global $prefixhot, $dbhot;
  $anyo = substr($fecinv,0,4);$mes = substr($fecinv,4,2);$dia = substr($fecinv,6,2);
  $fecsol=mktime(0,0,0, $mes, $dia, $anyo);
  $hoy=mktime(0,0,0,  date(m), date(d), date(Y));
  if($fecsol<$hoy) return false;
  $sql="select totcua,reserv,status from ".$prefixhot."_hotelesinventario where hotelid='$hotelid' and productid='$productid' and fecinv='$fecinv'";
  $result=$dbhot->sql_query($sql);
  //echo $sql;
  list($totcua,$reserv,$status) = $dbhot->sql_fetchrow($result);
  //echo "<br>$totcua, $reserv, $status<br>";
  if ($status=="C") //si está cerrado
    return false;
  if ($orden=="primero" and $status=="CA") // si es Closed on Arrival
    return false;
  if ($orden=="ultimo" and $status=="CD") // si es Closed on Departure
    return false;
  $disponibles = $totcua - $reserv;
  if ($disponibles < $rooms)
    return false;

  return true;	
}

function DateDropDown($size=90,$default,$fecini="hoy",$nomselect="DropDate") {
//global $checadisp;
   // $size = the number of days to display in the drop down
   // $default = Todays date in m:d:Y format (SEE DATE COMMAND ON WWW.PHP.NET)
   // $skip = if set then the program will skip Sundays and Saturdays
   $skip=1;
   $checadisp.= "<select name=$nomselect STYLE=\"font-family: monospace;\">\n";
   for ($i = 0; $i <= $size; $i++) {
      if ($fecini=="hoy")
        $theday = mktime (0,0,0,date("m") ,date("d")+$i ,date("Y"));
	  else{
	    $anyo = substr($fecini,0,4);$mes = substr($fecini,5,2);$dia = substr($fecini,8,2);
	    $theday = mktime(0,0,0, $mes, $dia+$i, $anyo);
	  }
      $option=date("D M j,y",$theday);
      $value=date("Y-m-d",$theday);
      $dow=date("D",$theday);
      if ($dow=="Mon") {
         $checadisp.= "<option disabled>&nbsp;</option>\n";
      }
      if ($value == $default) {
         $selected="SELECTED";
      } else {
         $selected="";
      }
      //if (($dow!="Sun" and $dow!="Sat") or !$skip) {
         $checadisp.= "<option value=\"$value\" $selected>$option</option>\n";
      //}
   }
   $checadisp.= "</select>\n";
   
   return $checadisp;
} //dateDropDown

function fotos($hotelid){
global $currentlang,$module_name,$dbhot,$prefixhot, $urlgaleria;
$result=$dbhot->sql_query("select albumfotos from ".$prefixhot."_hoteles where hotelid='$hotelid'");
$row = $dbhot->sql_fetchrow($result);
$albumfotos=$row['albumfotos'];
	  $content = "<br><iframe width=\"755\" height=\"370\" align=\"center\"
	  src=\"$urlgaleria/modules.php?name=Photos&amp;aid=$albumfotos\">";
	  $content .= "</iframe>";
  return $content;

return $content;


} // fotos

function ubica($hotelid){
global $prefixhot, $dbhot,$currentlang,$module_name,$urlfotos,$dirfotos;
$loca="loca_".$currentlang;
$result=$dbhot->sql_query("select $loca from ".$prefixhot."_hoteles where hotelid=$hotelid");
$row=$dbhot->sql_fetchrow($result);
$ubica = $row[$loca];

  if ( file_exists("$dirfotos/$hotelid-6.jpg") )
    $imagenubica = "
	<img src=\"$urlfotos/$hotelid-6.jpg\" >";
  else
    $imagenubica = "";
  $content = '<p align="justify"><font class="cuerpojustificado">'.$ubica.'</font></p>';
  $content .= '<p align="center">'.$imagenubica.'</p>';
return $content;
} // ubica

function ratesbd($hotelid){
global $currentlang,$module_name,$dbhot,$prefixhot;
$result=$dbhot->sql_query("select bestdayid from ".$prefixhot."_hoteles where hotelid='$hotelid'");
$row=$dbhot->sql_fetchrow();
$bestdayid=$row['bestdayid'];
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
	  
 	  $content .= "<br><iframe width=\"100%\" height=\"700\" align=\"center\"
	  src=\"$fuenteBestday\">";
	  $content .= "</iframe>";
  return $content;
} // ratesbd


function navegacionhotel($opcion){
global $hotelid,$module_name,$prefixhot,$dbhot,$currentlang,$externo;
if ($externo==1)
  $paghotel="modules.php?"._MODULE_NAME."&amp;file=hotel&amp;hotelid=$hotelid&amp;newlang=$currentlang&amp;externo=$externo";
else
  $paghotel="modules.php?name="._MODULE_NAME."&amp;file=hotel&amp;hotelid=$hotelid&amp;newlang=$currentlang";  
//$fecini=Date("Y-m-d", mktime(0,0,0,  date(m), date(d)+7, date(Y)) );
//$pagcuartos="&amp;fecini=$fecini&amp;diasadesp=3&amp;adults=2";
$pagcuartos="&amp;op=rooms";
if ($opcion=='fotos'){
  $textofotos="<font class=\"boxtitle\">"._GALERIAFOTOS."</font>";
  $textohotel="<a href=\"$paghotel\" class=\"topnav\"><small>"._HOTELDESCRIPTION."</small></a>";
  $textoforos="<a href=\"forum3.html\" class=\"topnav\" target=blank><small>Question & Answer Forum</small></a>";
  $textorates="<a href=\"$paghotel$pagcuartos\" class=\"topnav\"><small>"._ROOMSANDRATES."</small></a>";
  $textoubica="<a href=\"$paghotel&amp;op=ubica\" class=\"topnav\"><small>"._LOCATION."</small></a>";
  $textoratesbd="<a href=\"$paghotel&amp;op=ratesbd\" class=\"topnav\"><small>"._BOOKBYTRAVELAGENCY."</small></a>";
  $selfoto='-sel';
}
if ($opcion=='descrip'){
  $textofotos="<a href=\"$paghotel&amp;op=fotos\" class=\"topnav\"><small>"._GALERIAFOTOS."</small></a>";
  $textohotel="<font class=\"boxtitle\">"._HOTELDESCRIPTION."</font>";
  $textoforos="<a href=\"forum3.html\" class=\"topnav\" target=blank><small>Question & Answer Forum</small></a>";
  $textorates="<a href=\"$paghotel$pagcuartos\" class=\"topnav\"><small>"._ROOMSANDRATES."</small></a>";
  $textoubica="<a href=\"$paghotel&amp;op=ubica\" class=\"topnav\"><small>"._LOCATION."</small></a>";
  $textoratesbd="<a href=\"$paghotel&amp;op=ratesbd\" class=\"topnav\"><small>"._BOOKBYTRAVELAGENCY."</small></a>";
  $seldesc='-sel';
}  
if ($opcion=='foros'){
  $textofotos="<a href=\"modules.php?name=hoteles&amp;file=hotel&amp;hotelid=$hotelid&amp;newlang=$currentlang&amp;op=fotos\" class=\"topnav\"><small>"._GALERIAFOTOS."</small></a>";
  $textohotel="<a href=\"$paghotel\" class=\"topnav\"><small>"._HOTELDESCRIPTION."</small></a>";
  $textoforos="Hotel Forum";
  $textorates="<a href=\"$paghotel$pagcuartos\" class=\"topnav\"><small>"._ROOMSANDRATES."</small></a>";
  $textoubica="<a href=\"$paghotel&amp;op=ubica\" class=\"topnav\"><small>"._LOCATION."</small></a>";
  $textoratesbd="<a href=\"$paghotel&amp;op=ratesbd\" class=\"topnav\"><small>"._BOOKBYTRAVELAGENCY."</small></a>";
  $selforo='-sel';
} 
if ($opcion=='rates'){
  $textofotos="<a href=\"$paghotel&amp;op=fotos\" class=\"topnav\"><small>"._GALERIAFOTOS."</small></a>";
  $textohotel="<a href=\"$paghotel\" class=\"topnav\"><small>"._HOTELDESCRIPTION."</small></a>";
  $textoforos="<a href=\"forum3.html\" class=\"topnav\" target=blank><small>Question & Answer Forum</small></a>";
  $textorates="<font class=\"boxtitle\">"._ROOMSANDRATES."</font>";
  $textoubica="<a href=\"$paghotel&amp;op=ubica\" class=\"topnav\"><small>"._LOCATION."</small></a>";
  $textoratesbd="<a href=\"$paghotel&amp;op=ratesbd\" class=\"topnav\"><small>"._BOOKBYTRAVELAGENCY."</small></a>";
  $selrates='-sel';
}
if ($opcion=='ubica'){
  $textofotos="<a href=\"$paghotel&amp;op=fotos\" class=\"topnav\"><small>"._GALERIAFOTOS."</small></a>";
  $textohotel="<a href=\"$paghotel\" class=\"topnav\"><small>"._HOTELDESCRIPTION."</small></a>";
  $textoforos="<a href=\"forum3.html\" class=\"topnav\" target=blank><small>Question & Answer Forum</small></a>";
  $textorates="<a href=\"$paghotel$pagcuartos\" class=\"topnav\"><small>"._ROOMSANDRATES."</small></a>";
  $textoubica="<font class=\"boxtitle\">"._LOCATION."</font>";
  $textoratesbd="<a href=\"$paghotel&amp;op=ratesbd\" class=\"topnav\"><small>"._BOOKBYTRAVELAGENCY."</small></a>";
  $selubica='-sel';
}
if ($opcion=='ratesbd'){
  $textofotos="<a href=\"$paghotel&amp;op=fotos\" class=\"topnav\"><small>"._GALERIAFOTOS."</small></a>";
  $textohotel="<a href=\"$paghotel\" class=\"topnav\"><small>"._HOTELDESCRIPTION."</small></a>";
  $textoforos="<a href=\"forum3.html\" class=\"topnav\" target=blank><small>Question & Answer Forum</small></a>";
  $textorates="<a href=\"$paghotel$pagcuartos\" class=\"topnav\"><small>"._ROOMSANDRATES."</small></a>";
  $textoubica="<a href=\"$paghotel&amp;op=ubica\" class=\"topnav\"><small>"._LOCATION."</small></a>";
  $textoratesbd="<font class=\"boxtitle\">"._BOOKBYTRAVELAGENCY."</font></a>";
  $selratesbd='-sel';
}
  $textoadesplegar="<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\"><tr>
	<td align=\"left\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-izq$seldesc.gif\" ></td>
		<td background=\"modules/$module_name/images/tabsup-linea$seldesc.gif\" bgcolor=\"#996600\" align=\"center\" valign=\"top\">
		$textohotel
		<td align=\"right\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-der$seldesc.gif\" ></td></tr></table>";
   $loca = "loca_".$currentlang;
   $result=$dbhot->sql_query("select albumfotos,booking,$loca,bestdayid from ".$prefixhot."_hoteles where hotelid=$hotelid");
   $nahotel = $dbhot->sql_fetchrow($result);
   $album = $nahotel['album'];
   $booking = $nahotel['booking'];
   $ubica = $nahotel[$loca];
   $bestdayid = $nahotel['bestdayid'];

   // Ubicación
   if ($ubica<>''){
     $textoadesplegar.="<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\"><tr>
	<td align=\"left\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-izq$selubica.gif\" ></td>
		<td background=\"modules/$module_name/images/tabsup-linea$selubica.gif\" bgcolor=\"#996600\" align=\"center\" valign=\"top\">
		$textoubica
		<td align=\"right\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-der$selubica.gif\" ></td></tr></table>";     
   }
   // fotos
   if ($album<>''){
     $textoadesplegar.="<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\"><tr>
	<td align=\"left\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-izq$selfoto.gif\" ></td>
		<td background=\"modules/$module_name/images/tabsup-linea$selfoto.gif\" bgcolor=\"#996600\" align=\"center\" valign=\"top\">
		$textofotos
		<td align=\"right\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-der$selfoto.gif\" ></td></tr></table>";     
   }
   // foros
   if ($foro<>''){
   //if ($hotelid==81){
    $textoadesplegar.="<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\"><tr>
	<td align=\"left\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-izq$selforo.gif\" ></td>
		<td background=\"modules/$module_name/images/tabsup-linea$selforo.gif\" bgcolor=\"#996600\" align=\"center\" valign=\"top\">
		$textoforos
		<td align=\"right\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-der$selforo.gif\" ></td></tr></table>"; 
	} // if foro	
	// Tarifas y cuartos(Rates)
	$booking = 0;
   if ($booking=='1' and $hotelid<>'114'){
    $textoadesplegar.="<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\"><tr>
	<td align=\"left\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-izq$selrates.gif\" ></td>
		<td background=\"modules/$module_name/images/tabsup-linea$selrates.gif\" bgcolor=\"#996600\" align=\"center\" valign=\"top\">
		$textorates
		<td align=\"right\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-der$selrates.gif\" ></td></tr></table>"; 
	} // if booking

  // Tarifas con bestday
  //if ($booking<>'1' and $bestdayid<>''){
  if ($bestdayid>0){
 	$textoadesplegar.="<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\"><tr>
	<td align=\"left\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-izq$selratesbd.gif\" ></td>
		<td background=\"modules/$module_name/images/tabsup-linea$selratesbd.gif\" bgcolor=\"#996600\" align=\"center\" valign=\"top\">
		$textoratesbd
		<td align=\"right\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-der$selratesbd.gif\" ></td></tr></table>";   
  }// tarifas bestday
  
  return $textoadesplegar;
} // navegacion hotel

function Pide_Fechas($fecini,$diasadesp,$rooms,$adults,$children){
  global $hotelid,$module_name,$admin,$user,$ThemeSel,$bgcolor3;
  $checadisp ="
	  <table bgcolor=\"#FFbb55\" align=\"center\" border=\"1\">
	  <tr><td>
	  <table width=\"100%\">	  
	  <tr><td ><form method=\"post\">"._FECING.": <br>";
	  $hoy=date("Y-m-d");
	  $checadisp .=DateDropDown(365,$fecini,$hoy,"fecini"); //definida en funcs.php para hacer un drop down con la fecha
	  $checadisp .= "<tr><td align=\"right\">"._NIGHTS.":
	   <select name=\"diasadesp\">";
	  for ($i=1;$i<32;$i++){
	    if ($i==$diasadesp) $selected="selected"; else $selected="";
	    $checadisp .= "<option value=$i $selected>$i</option>";
	  }
	  $checadisp .= "</select>";
	  /*<td>"._ROOMS.":<br><select name=\"rooms\">";
	  for ($i=1;$i<15;$i++){
	    if ($i==$rooms) $selected="selected"; else $selected="";
	    $checadisp .= "<option value=$i $selected>$i</option>";
	  }
	  $checadisp .= "</select></td></tr>*/
	  $checadisp .= "<br>"._ADULTS.":
	   <select name=\"adults\" >";
	  for ($i=1;$i<21;$i++){
	    if ($i==$adults) $selected="selected"; else $selected="";
	    $checadisp .= "<option value=$i $selected>$i</option>";
	  }
	  $checadisp .= "</select>
	  <br>"._KIDS.": <select name=\"children\">";
	  for ($i=0;$i<15;$i++){
	    if ($i==$children) $selected="selected"; else $selected="";
	    $checadisp .= "<option value=$i $selected>$i</option>";
	  }
	  $checadisp .= "</select></TD></tr>
	 <tr><td align=\"center\">
	 <INPUT type=\"submit\" style=\"background-color:#339900 \" style=\"color: #ffffff\" class=\"boxtitle\" value=\""._APPLY."\" >
	  </form></td></tr></table></td></tr></table>";
	  $checadisp .= "<i>"._TOCHECKAVAILABILITY."</i>";
	  return $checadisp;
} // Pide Fechas

function top_hotels($selvista,$numhotels){ // Empieza bloque de Top Hotels
global $prefixhot, $dbhot, $numtop, $topcomolinks;

$tophotelstext = "<B><center>".checavista($selvista)."</center></b>";

for ($i=1;$i<5;$i++){
  if ($i==1){
    $tophotelstext .= "<hr><b><small>"._HOTELSBETTERRATES."</small></b>";
    $sqltop="select hotelid, nombre, visible as cuenta from ".$prefixhot."_hoteles where vista='$selvista' and visible='1' and booking='1' order by rating DESC limit $numtop";
  }
  if ($i==2){
    $tophotelstext .= "<hr><b><small>"._HOTELSWITHMOREAMENITIES."</small></b>";
    $sqltop="select hotelid, nombre, length(replace(amenities,'0','')) as cuenta from ".$prefixhot."_hoteles where vista='$selvista' and visible='1' order by cuenta desc limit $numtop";
  }
  if ($i==3){
    $tophotelstext .= "<hr><b><small>"._HOTELSWITHMORESTARS."</small></b>";
    $sqltop="select hotelid, nombre, rating as cuenta from ".$prefixhot."_hoteles where vista='$selvista' and visible='1' order by cuenta desc limit $numtop";
  }
  if ($i==4){
    $tophotelstext .= "<hr><b><small>"._HOTELSWITHMOREROOMS."</small></b>";
    $sqltop="select hotelid, nombre, cuartos as cuenta from ".$prefixhot."_hoteles where vista='$selvista' and visible='1' order by cuenta desc limit $numtop";
  }
  $resulttop = $dbhot->sql_query($sqltop);
  if ($resulttop){
  	foreach ($resulttop as $row){
  		$nombre = substr($row['nombre'],0,17);
  		$hotelid = intval($row['hotelid']);
  		$cuenta = intval($row['cuenta']);
  		if($topcomolinks)
  			$tophotelstext .= "<br><a href=\"modules.php?name="._MODULE_NAME."&amp;file=hotel&amp;hotelid=$hotelid&amp;newlang=$currentlang\" >$nombre</a>";
  		else
  			$tophotelstext .= "<br><font class=\"footmsg\">$nombre</font>";
  		if ($cuenta > 1)
  			$tophotelstext .=" <small>(<b>$cuenta</b>)</small>";
  	}//while
  }
  
  
}//for i


return $tophotelstext;

} // top_hotels

function despliega_listado($query){
global $prefixhot,$dbhot,$admin,$user,$bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$textcolor1,$textcolor2,$module_name,$currentlang,
       $selame,$sortby,$aselame,$selvista,$busca,$ThemeSel,$urlfotos,$dirfotos,$nukeurl,
	   $largoimagen,$longtexto,$largoimagenbooking,$longtextobooking,$despliegamhome; 	   	   
	   
  $result=$dbhot->sql_query($query);
  if ($result){
	  foreach ($result as $hotel){
	    $rating=intval($hotel[rating]);
	      // *** Inicia Tabla con desplegado de los datos generales del hotel ****
		  if ($currentlang=="spanish")
		    echo "\n\n<!-- Inicia tabla con desplegado de los datos del hotel $hotel[nombre] -->\n";
		  else	
		    echo "\n\n<!-- Begin the table with the data display of the hotel $hotel[nombre] -->\n";
		  $direccion = "<font class=\"mininegro\">$hotel[direccion]</font>";
		  $descvista = checavista($hotel[vista]);
		  	
			  // Desplegado de Título p.ej  hoteles en acapulco 5 estrellas 
		  if($temrating<>$hotel[rating]){ // aquí detecta si hay que pintar el título
		    echo "<table bgcolor=\"$bgcolor1\"><tr><td align=\"center\">";		
			if (!isset($busca)){  
			  if ($rating<6) { // si el rating es de 6 es que es de gran turismo
			    echo _HOTELSIN." $descvista";
			    $j=0;
		        while ($j < $hotel[rating]) {  
		 	      echo "<img src=\"modules/"._MODULE_NAME."/images/hestrellachica.gif\">";
			      $j++;
	            } //while j			
		        echo " <b>$rating "._STARS."</b><br>";
			  } // if $rating < 6
			  else // para los de rating 6 o más
			    echo _HOTELSIN." $descvista <big><b>"._GRANTURISMO."</b></big><br>";
			} // if !isset busca  
			else
			  echo _MHOTELES."<b> $busca</b> $hotel[rating] "._STARS."<br>";
			echo "</td></tr></table>";
		  } // if temrating	
		  
		  if ($hotel[visible] <> 'N') {
		    if ($hotel[albumfotos]<>''){
		    
		      $titulohotel ="<A HREF=\"modules.php?name="._MODULE_NAME."&amp;file=hotel&amp;hotelid=$hotel[hotelid]&amp;newlang=$currentlang&amp;op=fotos\">
			  <img src=\"modules/$module_name/images/fotos.gif\" height=\"18\" align=\"right\" border=\"0\"></a>";
		    } else {
			  $titulohotel ='';
		    }  
			$titulohotel .= "<A HREF=\"modules.php?name="._MODULE_NAME."&amp;file=hotel&amp;hotelid=$hotel[hotelid]&amp;newlang=$currentlang\" class=\"topnav\"><strong><big>$hotel[nombre]</big></strong></a>&nbsp;";
		    	
			
			// ** Segundo renglón 
			
			
			if ($hotel[booking]==1) {// Aquí despliega el Botón de RESERVACIONES
		      /*$puedereservar = "<br><A HREF=\"modules.php?name=hoteles&amp;file=hotel&amp;hotelid=$hotel[hotelid]&amp;newlang=$currentlang\" class=\"destacado\">";    
			  $puedereservar .= "<b>"._PUEDERESERVAR."</b></a>";*/
			  $largoimagenlista=$largoimagenbooking;
			  $longtextolista=$longtextobooking;
			}  // if booking  
			else {
			  //$puedereservar="";
			  $largoimagenlista=$largoimagen;
			  $longtextolista=$longtexto;
			} //else del if booking	
			
			// Fotografía del Hotel en portada
	        $fotohotel = $hotel[hotelid];
		    $archivofoto="<img src=\"$urlfotos/$fotohotel-1.jpg\" height=$largoimagenlista align=\"right\" border=\"0\" >"; //alt=\"$hotel[nombre]\"
			$carchivofoto = "$dirfotos/$fotohotel-1.jpg";
			if (!file_exists($carchivofoto)) $archivofoto="&nbsp;";
		
			
			// Despliega Amenities
			$cuerdaameni='';
			if ($hotel[booking]==1  || $despliegamhome){
		      $sqlame="select * from ".$prefixhot."_hotelesfacili";
	          $resulame = $dbhot->sql_query($sqlame);		
	          $descri="hfa_desc_".$currentlang;
	          $i=1; $j=1;
		      foreach ($resulame as $ameni){		
		        if ( substr($hotel[amenities], $i-1, 1) == 1) {
			      //if (round($j/16)==$j/16) echo "<br>";
		  	      //echo "<a href=\"modules.php?name=hoteles&amp;selame=$i&amp;sortby=$sortby&amp;aselame=$aselame&amp;selvista=$selvista\">";
			      $cuerdaameni .="<img src=\"modules/"._MODULE_NAME."/images/oamenities$ameni[hfaid].gif\" width=\"12\"  border=\"0\" >"; //alt=\"$ameni[$descri]\"
				  $j++;
			    } // if substr($hotel)
			    $i++;
		      }; //while $ameni
			} // if $hotelbooking
			/*if (isset($busca)) // si está definida la varible de búsqueda despliega la ubicación
			  $descvista = "<b>".checavista($hotel[vista])."</b>";
			else
			  $descvista = "";*/
			
			$descri = "desc_".$currentlang;
			if ($hotel[$descri]=='') // si no hay la descripción en el idioma la pone en inglés
			  $descri = 'desc_english';
		    $descripcion = "<font class=\"Cuerpojustificado\">".substr($hotel[$descri],0,$longtextolista);  // el número de caracteres de yahoo es 226
		    if (substr($hotel[$descri],0,$longtexto) <> $hotel[$descri])
		      $descripcion .= "...";	
			$descripcion = str_replace("<br>","&nbsp;",$descripcion);  		   
		    $descripcion .= "</font>";
			
			if ($hotel[bestdayid]>0 || $hotel[booking]==1){
			  $textotarifas=_CHECKRATES." hotel "._IN;
			 
			  if ($hotel[bestdayid]>0){
			    if($currentlang=='spanish') $idioma="esp"; else $idioma="ing";
	           
			    $textotarifas .= ' [<a href="http://www.e-tsw.com.mx/Hoteles/Tarifas?af=turista&ln=' . $idioma . '&cu=US&ds=9&ht='
			    		. $hotel['bestdayid'] . '" target="blank">'
			    				. _TRAVELAGENCY . '</a>]';
			  } // if bestday
			} else // if principal de tarifas y booking
			  $textotarifas='';
		  } // if Visible
		  
		  $temrating=$hotel[rating];
		  if (file_exists("themes/$ThemeSel/hotel-home-template.html"))
		    $tmpl_file = "themes/$ThemeSel/hotel-home-template.html";
		  else
		    $tmpl_file = "modules/"._MODULE_NAME."/templates/hotel-home-template.html";
		  $thefile = implode("", file($tmpl_file));
	      $thefile = addslashes($thefile);
	      $thefile = "\$r_file=\"".$thefile."\";";
	      eval($thefile);
	      print $r_file;	
		  echo "<img src=\"themes/$ThemeSel/images/pixel.gif\" height=\"2\" border=\"0\">";
		} // while $hotel
  }//if result
} // despliegalistado

function vistas1() {
    global $sortby, $prefixhot, $currentlang, $dbhot, $selvista,$bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$textcolor1,$textcolor2,$letter,
	$selame,$aselame,$module_name;
	$sql="select * from ".$prefixhot."_hotelesviews";
    $resulview = $dbhot->sql_query($sql);
    $descri="hvi_desc_".$currentlang;
	echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\" ><tr>";
	$selec='';
	$i=0;
	foreach ($resulview as $views){
	  $i++;
	  if(ceil($i/6)==$i/6) echo "</tr><tr><td colspan=\"20\"></td></tr></table><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"  align=\"center\"><tr>";
	  if ($selvista==$views['hviid']){ 
	    $selec='-sel';
		$textodesp="<font class=\"boxtitle\"><small>"._HOTELS." $views[$descri]</small></font>";
	  } // if $selvista	    
	  else{  
	    $selec='';
		$cuerdam='';
		//if(isset($selame)) $cuerdam="&amp;selame=$selame";
		//if(isset($aselame)) $cuardam="&amp;selame=$selame&amp;aselame=$aselam";
		//$textodesp="<a href=\"modules.php?name=hoteles&amp;selvista=$views[hviid]&amp;selame=$selame&amp;aselame=$aselame\" class=\"topnav\">
		$textodesp="aqui<a href=\"modules.php?name=hoteles&amp;selvista=$views[hviid]$cuerdam\" class=\"topnav\">
		<small><b>$views[$descri]</b></small></a>";
	  }	// else $selvista  
      echo "<td align=\"left\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-izq$selec.gif\" ></td>
		<td background=\"modules/$module_name/images/tabsup-linea$selec.gif\" bgcolor=\"#996600\" align=\"center\" valign=\"top\">
		";
		
		  echo $textodesp;
		echo "</td> ";
	  echo "<td align=\"right\" valign=\"top\"><img src=\"modules/$module_name/images/tabsup-der$selec.gif\" ></td>";  
      echo "<td>&nbsp;</td>";
	} // while $views
  echo "</tr></table>";	
} // vistas

function facilities($selame,$aselame,$seleccionados) {
  global $sortby, $prefixhot, $currentlang, $dbhot, $sortby, $selvista; 
 // Despliega Amenities
	    $sqlame="select * from ".$prefixhot."_hotelesfacili";
        $resulame = $dbhot->sql_query($sqlame);		
        $descri="hfa_desc_".$currentlang;
        $i=1;$j=1; 
		$texto = "<table cellspacing=\"0\" align=\"center\"><tr>";
		$arr_amenities=explode("a",$aselame);
	    foreach ($resulame as $ameni){	
		  if ($seleccionados=='no'){
		    $inout=!in_array($ameni[hfaid],$arr_amenities);
			$cuerdaarame = '';
			  if ($aselame<>'') $cuerdaarame="&amp;aselame=$aselame";
			$liga="<a href=\"modules.php?name="._MODULE_NAME."&amp;selvista=$selvista&amp;selame=$i$cuerdaarame\">";
		  }else{	
		    $inout=in_array($ameni[hfaid],$arr_amenities);
		  }	// else if $seleccionados=no
		  if ($inout){	
		    if ($seleccionados=='si'){
			  for($h=1;$h<count($arr_amenities);$h++){
			    if($ameni[hfaid]<>$arr_amenities[$h])
			      $extractame.="a$arr_amenities[$h]";
			  }//for h
			  $cuerdaarame = '';
			  if ($extractame<>'') $cuerdaarame="&amp;aselame=$extractame";
			  $liga="<a href=\"modules.php?name="._MODULE_NAME."&amp;selvista=$selvista&amp;selame=resta$cuerdaarame\" class=\"footmsg\">";
	        } // if $seleccionados = si
			  
			$texto.="<td width=\"37\" height=\"37\" align=\"center\" valign=\"top\">";	
	  	    $texto.= $liga;
			//$descamedesp = substr($ameni[$descri],0,7);
			$descamedesp = $ameni[$descri];
			//if( strlen($ameni[$descri])>7) $altame=$ameni[$descri]; else $altame='';
			$altame='';
		    //$texto.="<img src=\"modules/$module_name/images/oamenities$ameni[hfaid].gif\" align=\"center\" "
			$texto.="<img src=\"modules/"._MODULE_NAME."/images/oamenities$i.gif\" align=\"center\" "
		    ." border=\"0\" alt=\"$altame\"></a><br><font class=\"footmsg\">$descamedesp</font>";			//alt=\" $ameni[$descri]\"
		    $texto.="</td>";
		    if (round($j/3)==$j/3) $texto.= "</tr><tr>";
		    $j++;
		  }	// if
		  $i++;
		  $extractame='';
	    }; //while $ameni
 $texto.= "</tr></table>";
 return $texto;
} // facilities


function facilities_sintablas($selame,$aselame,$seleccionados) {
  global $sortby, $prefixhot, $currentlang, $dbhot, $sortby, $selvista; 
 // Despliega Amenities
	    $sqlame="select * from ".$prefixhot."_hotelesfacili";
        $resulame = $dbhot->sql_query($sqlame);		
        $descri="hfa_desc_".$currentlang;
        $i=1;$j=1; 
		$texto = "";
		$arr_amenities=explode("a",$aselame);
	    foreach ($resulame as $ameni){	
		  if ($seleccionados=='no'){
		    $inout=!in_array($ameni[hfaid],$arr_amenities);
			$cuerdaarame = '';
			  if ($aselame<>'') $cuerdaarame="&amp;aselame=$aselame";
			$liga="<a href=\"modules.php?name="._MODULE_NAME."&amp;selvista=$selvista&amp;selame=$i$cuerdaarame\">";
		  }else{	
		    $inout=in_array($ameni[hfaid],$arr_amenities);
		  }	// else if $seleccionados=no
		  if ($inout){	
		    if ($seleccionados=='si'){
			  for($h=1;$h<count($arr_amenities);$h++){
			    if($ameni[hfaid]<>$arr_amenities[$h])
			      $extractame.="a$arr_amenities[$h]";
			  }//for h
			  $cuerdaarame = '';
			  if ($extractame<>'') $cuerdaarame="&amp;aselame=$extractame";
			  $liga="<a href=\"modules.php?name="._MODULE_NAME."&amp;selvista=$selvista&amp;selame=resta$cuerdaarame\" class=\"footmsg\">";
	        } // if $seleccionados = si
			  
			
	  	    $texto.= $liga;
			//$descamedesp = substr($ameni[$descri],0,7);
			$descamedesp = $ameni[$descri];
			//if( strlen($ameni[$descri])>7) $altame=$ameni[$descri]; else $altame='';
			$altame='';
		    //$texto.="<img src=\"modules/$module_name/images/oamenities$ameni[hfaid].gif\" align=\"center\" "
			$texto.="<img src=\"modules/"._MODULE_NAME."/images/oamenities$i.gif\" align=\"center\" "
		    ."width=\"17\"  border=\"0\" >$descamedesp</a>";			//alt=\" $ameni[$descri]\"
		    $texto.="<br>";
		    
		    $j++;
		  }	// if
		  $i++;
		  $extractame='';
	    }; //while $ameni
 return $texto;
} // facilities


function search(){
global $bgcolor3;
  $content = _BYHOTELNAME.":";
  $content .= "<table align=\"center\"><tr><td>";
  $content .= "<form action=\"modules.php?name="._MODULE_NAME."\" method=\"post\">";
  $content .= "<input type=\"text\" name=\"busca\" size=\"20\">";
  $content .= "<br><input type=\"submit\" value=\""._SEARCH."\">";
  $content .= "</td></tr></table>";  
  return $content;
}

function DaysBetween( $date1, $date2 ) {
   return ( date( "Y", $date1 ) * 366 + date( "z", $date1 ) ) -
       ( date( "Y", $date2 ) * 366 + date( "z", $date2 ) );
}


function Ver_Hotel(){
global $hotelid,$prefixhot,$dbhot,$module_name,$admin,$user,$ThemeSel,$currentlang,$dirfotos,$urlfotos,
$anchofotoshotel,$altoimagen1hotel;
/*
$row = $db->sql_fetchrow($db->sql_query("SELECT cid, title, parentid from " . $prefix . "_links_categories where cid='$parentid'"));
$cid = intval($row['cid']);
*/

$resultado = $dbhot->sql_query("select nombre,amenities,rating, desc_english, desc_spanish, cuartos, albumfotos from ".$prefixhot."_hoteles WHERE hotelid=$hotelid");
$row = $dbhot->sql_fetchrow($resultado);
  $nombre= $row['nombre'];
  $amenities = $row['amenities'];
  $rating = $row['rating'];
  $cuartos = intval($row['cuartos']);
  $albumfotos = intval($row['albumfotos']);
$Location = " "._LOCATION." ";
  $Services = " "._SERVICES." ";
  $Rooms = " "._CUARTOS." ";
  $vistadescri="No se ha definido vista para este Hotel";
  //$amenities = $hotellistado[amenities];
  $estrellas = "modules/$module_name/images/hestrella$rating.gif";
  
  $descri = "desc_".$currentlang;
  
  if ($row[$descri]=='')
    $descripcion = $row['desc_english'];
  else
    $descripcion = $row[$descri];

    
/* Desplegado de Fotografías del Hotel */
  
    if ( file_exists("$dirfotos/$hotelid-1.jpg") )
      $imagen = "<img src=\"$urlfotos/$hotelid-1.jpg\" height=$altoimagen1hotel  align=left alt=\"$nombre\">";
    else
	  $imagen1 = "";
if ($albumfotos > 0){
  $tirafotos = despliega_galeriaenhome($albumfotos);	
  
} else {		  
  for ($i=2;$i<6;$i++){
    $m=$i-1;
    $variable="imagen$m";
    if ( file_exists("$dirfotos/$hotelid-$i.jpg") )
      $$variable = "<img src=\"$urlfotos/$hotelid-$i.jpg\" width=\"$anchofotoshotel\" >";
    else
      $$variable = "";
  } // for i
} //ese de if $albumfotos 
  

 
		
  //  **** Obtiene Cuerda con los Amenities ****
	$cuerdaamenities = "<table border=\"0\"><tr>";
	  $sqlame="select * from ".$prefixhot."_hotelesfacili";
        $resulame = $dbhot->sql_query($sqlame);		
        $descri="hfa_desc_".$currentlang;
        $i=1; $j=1;
	    foreach ($resulame as $ameni){		
	      if ( substr($amenities, $i-1, 1) == 1) {	
		    $descdesp = str_replace(" ","<br>",$ameni[$descri]);	    
		    $cuerdaamenities .="<td align=\"center\" valign=\"top\"><img src=\"modules/$module_name/images/oamenities$ameni[hfaid].gif\" align=\"Center\" "
		    ." border=\"0\" ><br><font class=\"mininegro\">$descdesp</font></td>";
			if($j/10==round($j/10))
		    $cuerdaamenities.="</tr><tr>";
			$j++;
		  } // if substr($hotel)
		  
		  $i++;
	    } //while $ameni
		$cuerdaamenities.="</tr></table>";
	  // **** Integra con el Template de Hoteles ****
	$textoadesplegar = navegacionhotel('descrip')."<br>"; // definida en funcs
    if (file_exists("themes/$ThemeSel/hotel-description-$currentlang.html"))
	    $tmpl_file = "themes/$ThemeSel/hotel-description-$currentlang.html";
	  else
	    $tmpl_file = "modules/$module_name/templates/hotel-description-$currentlang.html";
    $thefile = implode("", file($tmpl_file));
    $thefile = addslashes($thefile);
    $thefile = "\$r_file=\"".$thefile."\";";
    eval($thefile);
    //print $r_file;
    $textoadesplegar.= $r_file;
	return $textoadesplegar;

} // ver_hotel

function despliega_galeriaenhome($galid){
global $urlgaleriafotos, $dirgaleriafotos;	
// Coppermine configuration file

// MySQL configuration
$CONFIG['dbserver'] =                       '162.243.114.210';        // Your database server
$CONFIG['dbuser'] =                         'root';        // Your mysql username
$CONFIG['dbpass'] =                         'ptrcvu70';                // Your mysql password
$CONFIG['dbname'] =                         'cunpic01_coppermine';        // Your mysql database name


// MySQL TABLE NAMES PREFIX
$CONFIG['TABLE_PREFIX'] =                'cpg_';
$dbgal = new sql_db($CONFIG['dbserver'], $CONFIG['dbuser'], $CONFIG['dbpass'], $CONFIG['dbname'], false);

$cpg_prefix = $CONFIG['TABLE_PREFIX'];
$ALBUM_SET = " and aid = '$galid'";

$numberpic=11; //number of thumbs
$largotira = 5;
$limit = $numberpic;
$result = $dbgal->sql_query("SELECT COUNT(*) from " . $cpg_prefix . "pictures WHERE approved = 'YES' $ALBUM_SET");
$nbEnr = $dbgal->sql_fetchrow($result);
$pic_count = $nbEnr[0];
$tirafotos = "";
// if we have more than 1000 pictures, we limit the number of picture returned
// by the SELECT statement as ORDER BY RAND() is time consuming

	$sql = "SELECT pid, filepath, filename, p.aid, p.title FROM ".$cpg_prefix."pictures AS p WHERE approved='YES' $ALBUM_SET LIMIT $limit";
    $result = $dbgal->sql_query($sql);
    


$tirafotos .= '<table width="100%" border="0" align="center" cols="' . $limit2 . '" cellpadding="0" cellspacing="0"><tr>';

$contador = 1;
if ($result){
	foreach ($result as $row){
	
	
		if ($row['title'] != '') {
			$thumb_title = $row['title'];
		} else {
			$thumb_title = substr($row['filename'], 0, -4);
		}
	
		stripslashes($thumb_title);
		//' . get_pic_url($row, 'thumb') . '
		if ($contador < $numberpic){
			$picurl = $urlgaleriafotos."/albums/".$row['filepath']."/thumb_".$row[filename];
			$tirafotos .= '<td align="center" valign="baseline">
      <img src="'.$picurl.'" border="0" alt="' . $thumb_title . '" title="' . $thumb_title . '"><br />' . $thumb_title . '</td>';
	
			if( ($contador/$largotira) == intval($contador/$largotira) ){
				$tirafotos .= "</tr><tr>";
			}
			$contador ++;
	
		}
	}
}

$tirafotos .= '</tr><tr align="center"><td colspan="' . $limit2 . '" valign="baseline"><a href="' . $CPG_M_URL . '">' . $lang_pagetitle_php["photogallery"] . '</a></center></td></tr></table>';
return $tirafotos;	
}


function despliega_tarifas($hotelid,$nombre,$fecini,$diasadesp,$rooms,$adults,$children){
	
}

function despliega_tarifas_old($hotelid,$nombre,$fecini,$diasadesp,$rooms,$adults,$children){
global $prefixhot,$dbhot,$currentlang,$ThemeSel,$module_name,$bgcolor2,$bgcolor3,$bgcolor4,$urlfotos,$dirfotos;
if ($fecini<>''){  
	  $anyo = substr($fecini,0,4);$mes = substr($fecini,5,2);$dia = substr($fecini,8,2);
	  $fecfin=Date("Y-m-d", mktime(0,0,0, $mes, $dia+$diasadesp-1, $anyo) );  
	  if ($children>0)
		$dispchildren="<b>$children</b> "._KIDS;
	  else
		$dispchildren="";
	  $fecdes=Date("D", mktime(0,0,0, $mes, $dia, $anyo) );
	  
	  $checadisp.="<BR>"._CHECKRATES.":  <b>$adults</b> "._ADULTS." $dispchildren "._FROM." <B>$fecini</B> "._TO." <B>$fecfin</B>"; 
	  
	  $titureserv=_BOOKINGFOR." $nombre</strong>"; 	  	   	  	
	  $descrip="";		
	  $descri="desc_".$currentlang;
	  $result = $dbhot->sql_query("select hotelid, productid, pnombre, maxocu, maxadu, maxnin, costeo, ".$descri." as desc1 from ".$prefixhot."_hotelesproductos WHERE hotelid=$hotelid");  
	  $xproductos="<br>";
	  foreach ($result as $row){
	    $hotelid = intval($row[hotelid]);
	    $productid = intval($row[productid]);
		$pnombre = stripslashes($row[pnombre]);
		$descrip = stripslashes($row[desc1]);
		$maxocu = intval($row[maxocu]);
		$maxadu = intval($row[maxadu]);
		$maxnin = intval($row[maxnin]);		
		$costeo = $row[costeo];
		if($maxnin==0 and $children>0){ //restringe para que dé 0 cuartos en caso de que no haya capacidad para niños
		  $rooms=0; $disponible=false;}
		$sqltari="select singlep,doublep,extra,child from ".$prefixhot."_hotelesprecios where hotelid='$hotelid' and productid='$productid' and fecpre='$fecini' ";
        $resulttari=$dbhot->sql_query($sqltari);  // obtiene las tarifas para el día inical
        list($singlep, $doublep,$extra,$child) = $dbhot->sql_fetchrow($resulttari);
		$nochesdesp = _NIGHTS.": $diasadesp $fecinix $fecfinx $anyox";
		$descrip.= "<br><BR>"._MAXOCUPANCY." "._ADULTS.":<b>$maxocu</b> "._KIDS.":<B>$maxnin</b>";
		if($costeo=='P')		
		  $descrip.="<br>"._SINGLE.":<b>$".number_format($singlep,2)."</b> "._DOUBLE.":<b>$".number_format($doublep,2)."</b> "
		  ._EXTRA.":<b>$".number_format($extra,2)."</b> "._KIDS.":<b>$".number_format($child,2)."</b>";  
		else
		  $descrip.="<br>"._COSTODEHABITACION.":<B>$".number_format($singlep,2)."<b>";
		$xproductos .="<TABLE BORDER=\"3\" cellSpacing=2 cellPadding=1><TR><TD>
		<TABLE  width=\"100%\" border=\"0\" cellSpacing=0 cellPadding=0>"; 		
		$archfotocuarto="modules/$module_name/images/fotos/$hotelid-c$productid.jpg";
		if (file_exists($dirfotos."/".$archfotocuarto)){
		   if($diasadesp<7)$anchofoto=250-($diasadesp*20); else $anchofoto=100; 
		   $fotocuarto="<img src=\"$urlfotos/$archfotocuarto\" align=\"left\" height=\"$anchofoto\">";		   
		}else
		  $fotocuarto='';    			  
		$xproductos.="<tr bgcolor=\"$bgcolor4\"><td ><img src=\"modules/$module_name/images/invcuartos.gif\">
		&nbsp;<font class=\"boxtitle\"><b>$pnombre</b></font></td></tr>
		<tr><td>  
		 $descrip</td></tr>"; 
		$xproductos.="<tr><td align=\"center\">$checadisp</td></tr></table>";
		//$calcadults = $adults; $calcchildren = $children;
		$rooms=0;
		$xproductos.="<table border=\"0\" align=\"center\" cellSpacing=0 cellPadding=0>
		<tr><td>$fotocuarto</td><td>
		<table align=\"center\" bgcolor=\"#006699\" width=\"100%\"><tr><td bgcolor=\"#CCF2FF\" valign=\"top\">"._DISTRIBUCION."<br><br>";
		list ($rooms,$calcchildren,$descrip) = calc_cuartos($adults,$maxadu,$children,$maxnin);
		$xproductos.= $descrip."</td>";
		if ($calcchildren > 0)
		  $xproductos.= "<td bgcolor=\"#0099cc\">"._NUMERONINYOSEXCEDE1." <b>$calcchildren</b> "._NUMERONINYOSEXCEDE2."</td><td>";
		else {  
		  $xproductos.="<td align=\"center\"><B>"._RATES." "._FOR." $rooms"." "._ROOMS." "._IN." "._OCUPACIONNORMAL."</B>";
		  list($cadena,$preprom,$pretot) = despliega_calendariotarifas($rooms,$anyo,$mes,$dia,$hotelid, $productid, $fecini, $diasadesp, $adults,$children,$costeo,$maxadu);
		  $xproductos.= $cadena;
		  if ($adults>$maxadu and $maxocu>$maxadu){
		    $xproductos.="</td></tr></table>";
		    $xproductos.="<table align=\"center\" bgcolor=\"#0099cc\"><tr><td bgcolor=\"#CCF2FF\" valign=\"top\">"._DISTRIBUCION."<br><br>";
			list ($rooms,$calcchildren,$descrip) = calc_cuartos($adults,$maxocu,$children,$maxnin);
		    $xproductos.= $descrip."</td>";
			if ($calcchildren > 0)
		      $xproductos.= "<td bgcolor=\"#ffffff\">"._NUMERONINYOSEXCEDE1." <b>$calcchildren</b> "._NUMERONINYOSEXCEDE2."</td><td>";
		    else{
			  $xproductos.="<td align=\"center\"><B>$rooms"." "._ROOMS." "._CONADULTOSEXTRA."</B>";
			  list($cadena,$preprom,$pretot) = despliega_calendariotarifas($rooms,$anyo,$mes,$dia,$hotelid, $productid, $fecini, $diasadesp, $adults,$children,$costeo,$maxocu); 
		      $xproductos.= $cadena;
			}  
		  }  //if ($adults>$maxadu and $maxocu>$maxadu)
		} // else de if ($calcchildren > 0)
		$xproductos.="</td></tr></table>";
		$xproductos."</td></tr></table>";
		
		$xproductos.="</td></tr></table><b><i>"._PRICESDONOTINCLUDE."</i></b></td></tr></table>";
		//$xproductos.= "<i>"._PRICESDONOTINCLUDE."</i></td></tr></table></TD></TR></TABLE><br>";
			
	  } // while listaproductos
  } // if $fecin<>''
   return $xproductos;
} // despliega tarifas

?>
