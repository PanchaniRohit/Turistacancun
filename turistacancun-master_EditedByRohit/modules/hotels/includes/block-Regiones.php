<?php
/************************************************************************/
/*         Bloque para desplegado de Regiones de Hoteles          */
/************************************************************************/
   
if ($currentlang=='english'){
  $titulo = $tithotzonesenglish;
} else {// if currentlang  
  $titulo = $tithotzonesspahish;
} 	
	$sqlreg="select * from ".$prefixhot."_hotelesviews order by hviid";
    $resulview = $dbhot->sql_query($sqlreg);
    $descri="hvi_desc_".$currentlang;
	$i=0;
	$content='';
	foreach ($resulview as $views){
	  $i++;
	  if ($selvista==$views[hviid]){ 
		$content.="<a href=\"modules.php?name=$module_name&amp;selvista=$views[hviid]&amp;selame=$selame&amp;aselame=$aselame\" ><img src=\"modules/$module_name/images/icon_select.gif\" border=\"0\"></a>
		<big><b>$views[$descri]</b></big><br><br>";
	  } // if $selvista	    
	  else{  
	    $cuerdam='';
		if(isset($selame) and $selame<>'') $cuerdam="&amp;selame=$selame";
		if(isset($aselame) and $aselame<>'') $cuerdam="&amp;selame=$selame&amp;aselame=$aselame";
		$content.="<a href=\"modules.php?name=$module_name&amp;selvista=$views[hviid]$cuerdam\" >
		<img src=\"modules/$module_name/images/icon_dot.gif\" border=\"0\">
		<b>$views[$descri]</b></a><br>";
	  }	// else $selvista  
      
	} // while $views
?>
