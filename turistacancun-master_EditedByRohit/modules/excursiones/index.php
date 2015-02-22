<?php 

/************************************************************************/
/* MODULO DE LISTADO DE RESTAURANTES                                    */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 por Enrique Adelino Montes Araujo                 */
/* http://www.turista.com.mx                                            */
/*                                                                      */
/* =========================                                            */
/************************************************************************/



require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

function despliega_estrellas($score) {
    if ( intval($score) == 1) $letrero= ""._NEEDSWORK."&nbsp;($score)";
	if ( intval($score) == 2) $letrero= ""._ACCEPTABLE."&nbsp;($score)";
	if ( intval($score) == 3) $letrero= ""._GOOD."&nbsp;($score)";
	if ( intval($score) == 4) $letrero= ""._VERYGOOD."&nbsp;($score)";
	if ( intval($score) == 5) $letrero= ""._EXCEPTIONAL."&nbsp;($score)";
    $image = "<img src=\"images/blue.gif\" alt=\"$letrero\">";
    $halfimage = "<img src=\"images/bluehalf.gif\" alt=\"$letrero\">";
    $full = "<img src=\"images/star.gif\" alt=\"$letrero\">";

    if ($score == 5) {
	for ($i=0; $i < 5; $i++)
	    echo "$full";
    } else {
	  for ($i=0; $i < intval($score); $i++) 
	    echo "$image";
	  if ($score-intval($score) >= .5) echo "$halfimage";
    } // else
}

function vistas() {
    /* Crea la lista de vistas y les hace Una Liga. */
    global $sortby, $user_prefix, $currentlang, $dbi, $module_name;
	$sqlview="select * from ".$user_prefix."_restaurantesespecial";
    $resulview = sql_query($sqlview, $dbi);		
    $descri="resp_desc_".$currentlang;
	echo "<center>"._SELECCIONEESPECIALIDAD."<br>";
    foreach ($resulview as $views){		
      echo "[<a href=\"modules.php?name=$module_name&amp;selvista=$views[respid]&amp;sortby=$sortby\">
	  $views[$descri]</a>] ";
	  
    } // while $views
	echo "[<a href=\"modules.php?name=restaurantes\">"._TODOS."</a>]</center>";
}

function alpha() {
    /* Crea la lista de letras y les hace Una Liga. */
    global $sortby, $module_name;
        $alphabet = array ("All", "A","B","C","D","E","F","G","H","I","J","K","L","M",
                            "N","O","P","Q","R","S","T","U","V","W","X","Y","Z","Other");
        $num = count($alphabet) - 1;
        echo "<center>[ ";
	/* inicio del HTML */
        $counter = 0;
        while (list(, $ltr) = each($alphabet)) {
            echo "<A HREF=\"modules.php?name=$module_name&amp;letter=$ltr&amp;sortby=$sortby\">$ltr</a>";
            if ( $counter == round($num/2) ) {
                echo " ]\n<br>\n[ "; 
            } elseif ( $counter != $num ) {
                echo "&nbsp;|&nbsp;\n";
            }
            $counter++;
        }
        echo " ]\n</center>\n<br>\n";  // fin del HTML
}

function SortLinks($letter) {  // Hace las ligas de Order by..
        global $sortby, $module_name;
        if ($letter == "front") { 
	    $letter = "All"; 
	}
        echo "\n<center>\n"; // Inicio del HTML
        echo ""._SORTBY." <b>[</b> ";
        if ($sortby == "nombre" OR !$sortby) {
            echo ""._NOMBRE."&nbsp;|&nbsp;";
        } else {
            echo "<A HREF=\"modules.php?name=$module_name&amp;letter=$letter&amp;sortby=nombre\">"._NOMBRE."</a>&nbsp;|&nbsp;";
        }
		if ($sortby == "rating") {
            echo ""._VIEW."&nbsp;| &nbsp;";
        } else {
           
        }
        
         echo " <b>]</b>\n</center>\n";// fin del HTML
}
        
include("header.php");
$pagesize = 20;

if (!isset($letter)) { $letter = "All";}
if (!isset($sortby)) { $sortby = "nombre"; }
if (!isset($page)) { $page = 1; }
if (!isset($selvista)) {$selvista = "All"; }


/* Esta es la sección de encabezado */

OpenTable();
if ( ($letter == "All") and ($selvista == "All") and (!isset($selame)) and ($page == 1) ) {
  $inicialtitulo = substr(_EXCURSIONES,0,1);
  $restotitulo = substr(_EXCURSIONES,1); 
  echo "<span class=\"InICIAL\">$inicialtitulo</span><span class=\"Cuerpojustificado\">$restotitulo<br>"
  ."<img src=\"modules/$module_name/images/excursiones.jpg\" align=\"left\" >"
  .""._INTROEXCURSION."</span><br><br>";	  
} // if $letter
//vistas();
//facilities();
/* finaliza la sección de encabezado  */

$min = $pagesize * ($page - 1); // Aquí inicia el recordset
$max = $pagesize; // Cuantos renglones se seleccionan
$count = "SELECT COUNT(excurid) AS total FROM ".$user_prefix."_excursiones "; // Cuenta todas las excursiones en la db
$select = "select * from ".$user_prefix."_excursiones "; //selecciona nuestro datos
$where = "where nombre != '' "; // donde el nombre sea diferente de ''
// Despliega amenities seleccionadas (si las hay)
$letfound = $letter;  // es el letrero que pone en x restaurantes encontrados para...


if ($selvista != "All") {
  $where .= "AND especialidad ='".$selvista."'";
  $letfound = $selvista;
}
if ( ( $letter != "Other" ) AND ( $letter != "All" ) ) {  // estamos listando  "all" u "other" ?
  $where .= "AND nombre like '".$letter."%' "; // Suponemos que no.. 
} else if ( ( $letter == "Other" ) AND ( $letter != "All" ) ) { //¿Pero "other" es numérica ?
    $where .= "AND nombre REGEXP \"^\[1-9]\" "; // REGEX :D, al parecer es para MySQL solamente                                                       
       } else { // o no lo sabemos del todos..
         $where .= ""; // esto es para evitar un mensaje de "undefinied variable"
       }
$sort = "order by $sortby"; //ordenado por .....
$limit = " ASC LIMIT ".$min.", ".$max; // solo queremos los renglones de $min  a $max
/* dado a como trabaja, se necesita el número total de hoteles 
por grupo de letra, entonces podemos atrapar los que queremos ver */
$count_result = $db->sql_query($count.$where);
$num_rows_per_order = $db->sql_numrows($count_result);
        
/* Aquí es donde conseguimos el result set con límite */
$result = $db->sql_query($select.$where.$sort.$limit); 

/* Crap code ends here */
echo "</td></tr><tr><td>";
if ( $letter != "front" ) {
  $a = 0;
  $dcolor_A = "$bgcolor2";
  $dcolor_B = "$bgcolor3";
  $num_excursiones = $db->sql_numrows($result); //número de excursiones a desplegar con query ordenado y limitado
  if ( $num_excursiones > 0  ) {
    foreach ($result as $excursion){
      //$dcolor = ($a == 0 ? $dcolor_A : $dcolor_B);
	  $dcolor = "#ddd";
      // *** Inicia Tabla con desplegado de los datos generales del Restaurante ****
	  echo "<table width=\"100%\" border=1><tr><td>
	  <table CELLSPACING=0 CELLPADDING=0 width=\"100%\" border=0>	  
      <tr bgcolor=$bgcolor4><b><big>$excursion[nombre]</big></b>&nbsp;
	  \n";
	 
	  echo "</tr><tr><td><font color=#000099>$excursion[direccion]"
	  ."<br><strong>"._TELEFONO.":&nbsp;&nbsp;</strong> $excursion[telefono]"
	  ."<br><strong>"._HORARIO.":&nbsp;&nbsp;</strong> $excursion[horario]";
 
	  echo "<table width=\"100%\" border=0><tr><td>";	  
	  if ($excursion[email]!='') 
	    echo "&nbsp;<a href=\"mailto:$excursion[email]\"><img src=\"modules/$module_name/images/mail.gif\" border=0 alt=\""._EMAIL."\"></a>";
	  if ($excursion[paginaweb]!='') 
	    echo "&nbsp;<a href=\"http://$excursion[paginaweb]\" target=blank >
		<img src=\"modules/$module_name/images/link.gif\" border=0 alt=\""._HOMEPAGE."\"></a>";
	  $descri = "desc_".$currentlang;
	  $descripcion = $excursion[$descri];	  
	  if ($descripcion != '')
	    echo "&nbsp;<A HREF=\"modules.php?name=$module_name&amp;file=excursion&do=ver_excur&excurid=$excursion[excurid]\">
		<img src=\"modules/$module_name/images/vistadetallada.gif\" border=0 alt=\"Detailed View\"></a>";
	  echo "</td></td></tr></table>";
	  //Inicia la columna de la foto
	  echo "<td align=\"center\">";
	  $archivofoto="modules/$module_name/images/fotos/$excursion[excurid]-1.jpg";
	  if (file_exists($archivofoto)) 
	    echo "<img src=\"$archivofoto\" width=350 height=70>";	       
      echo "</td>";
	  // Termina la columna de la Foto
      $a = ($dcolor == $dcolor_A ? 1 : 0);
	  $resultcalifs=$db->sql_query("select AVG(vroverall), AVG(vrfood), AVG(vrservice), AVG(vrambiance) from ".$user_prefix."_restovotos where restoid='$excursion[excurid]'");
	    list($overall, $food, $service, $ambiance) = $db->sql_fetchrow($resultcalifs);
	  echo "<td width=\"32%\" valign=\"bottom\">"
	  ."<a href=\"modules.php?name=$module_name&file=votos&excurid=$excursion[excurid]\">"
	  ."<img src=\"modules/My_eGallery/images/rate.jpg\" align=left border=0 alt=\""._CALIFICARRESTO."\"></a>"
	  ."<table  width=\"100%\" CELLSPACING=2 CELLPADDING=2 border=0 align=\"top\" >
	  <tr><td align=\"center\" bgcolor=#FFFFCC>"._OVERALL."</td><td bgcolor=#FFFFCC >";
	  despliega_estrellas($overall);
	  echo "</td></tr>
	  <tr><td bgcolor=#CCFFFF align=\"center\">"._FOOD."</td><td bgcolor=#CCFFFF>";
	  despliega_estrellas($food);
	  echo "</td></tr>
	  <tr><td bgcolor=#FFCCFF align=\"center\">"._SERVICE."</td><td bgcolor=#FFCCFF>";
	  despliega_estrellas($service);
	  echo "</td></tr>
	  <tr><td bgcolor=#D5F99F align=\"center\">"._AMBIANCE."</td><td bgcolor=#D5F99F>";
	  despliega_estrellas($ambiance);
	  echo "</td></tr>
	 </table></td></tr>"
	  ." </table></td></tr></table><br>";
	} // while $hotel
	
    // inicio de las ligas next/prev/row.
    echo "\n<tr><td colspan='$cols' align='right'>\n";
	echo "<br><br>";		
	OpenTable();
    echo "\t<table width='100%' cellspacing='0' cellpadding='0' border=0><tr>";                
    if ( $num_rows_per_order > $pagesize ) { // si hay más de una página
      $total_pages = ceil($num_rows_per_order / $pagesize); // Con cuántas páginas estamos tratando ??
      $prev_page = $page - 1;                    
      if ( $prev_page > 0 ) {
	    if (!isset($selvista))   // si no hay selecionada una vista entonces hay una letra
          echo "<td align='left' width='15%'><a href='modules.php?name=$module_name&amp;letter=$letter&amp;sortby=$sortby&amp;page=$prev_page'>";
		else
		  echo "<td align='left' width='15%'><a href='modules.php?name=$module_name&amp;selvista=$selvista&amp;sortby=$sortby&amp;page=$prev_page'>";
        echo "<img src=\"images/download/left.gif\" border=\"0\" Alt=\""._PREVIOUS." ($prev_page)\"></a></td>";
      } else { 
          echo "<td width='15%'>&nbsp;</td>\n"; }                             
      echo "<td align='center' width='70%'>";
      echo "<font class=tiny>$num_rows_per_order "._HOTELSFOUND." <b>$letfound</b> ($total_pages "._PAGES.", $num_users "._HOTELSSHOWN.")</font>";
      echo "</td>";
      $next_page = $page + 1;
      if ( $next_page <= $total_pages ) {
	    if (!isset($selvista))   // si no hay selecionada una vista entonces hay una letra
          echo "<td align='right' width='15%'><a href='modules.php?name=$module_name&amp;letter=$letter&amp;sortby=$sortby&amp;page=$next_page'>";
		else
		  echo "<td align='right' width='15%'><a href='modules.php?name=$module_name&amp;selvista=$selvista&amp;sortby=$sortby&amp;page=$next_page'>";
        echo "<img src=\"images/download/right.gif\" border=\"0\" Alt=\" "._NEXT." ($next_page)\"></a></td>";
      } else 
        echo "<td width='15%'>&nbsp;</td></tr>\n"; 
                    
      /* Una vez agregada una lista de páginas numerada, solamente enseña 50 páginas. */              
      echo "<tr><td colspan=\"3\" align=\"center\">";
      echo " <font class=tiny>[ </font>";                        
      for($n=1; $n < $total_pages; $n++) {                            
        if ($n == $page) 
		  echo "<font class=tiny><b>$n</b></font></a>";
        else {
		  echo "<a href='modules.php?name=$module_name&amp;letter=$letter&amp;sortby=$sortby&amp;page=$n'>";
	 	  echo "<font class=tiny>$n</font></a>";
		}
        if($n >= 50) {  // si se requieren más de 50 páginas, break en 50.
          $break = true; 
          break;
        } else   // suponemos que no.
            echo "<font class=tiny> | </font>"; 
      } // fon ($n=1)                        
      if(!isset($break)) { // se supone que no hay un break 						 
	    if ($n == $page) 
          echo "<font class=tiny><b>$n</b></font></a>";
	    else {
       	  echo "<a href='modules.php?name=$module_names&amp;letter=$letter&amp;sortby=$sortby&amp;page=$total_pages'>";
          echo "<font class=tiny>$n</font></a>";
		}
      }
      echo " <font class=tiny>]</font> ";
      echo "</td></tr>";
        
    /* Aquí termina esto del if( $num_rows_per_order > 0  )*/
    }else{  // no hay mád de una página
      echo "<td align='center'>";
      //echo "<font class=tiny>$num_rows_per_order "._HOTELSFOUND." $letfound</font>";
      echo "</td></tr>";                    
    }                
    echo "</table>\n";
	CloseTable();
    echo "</td></tr>\n";
  // end of next/prev/row links                
  } else { // no tienes hoteles en esta letra, hahaha
    echo "<tr><td bgcolor=\"$dcolor_A\" colspan=\"$cols\" align=\"center\"><br>\n";
    echo "<b><font color=\"$textcolor1\">"._NOHOTELS." $letter</font></b>\n";
    echo "<br></td></tr>\n";
  }            
  echo "\n</table><br>\n";
}
alpha();
SortLinks($letter);    
CloseTable();
include("footer.php");

?>