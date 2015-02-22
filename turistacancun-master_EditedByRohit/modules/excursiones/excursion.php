<?
/************************************************************************/
/* VISTA DE RESTAURANTE                                                 */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 por Enrique Adelino Montes Araujo                 */
/* http://www.turista.com.mx                                            */
/*                                                                      */
/* =========================                                            */
/************************************************************************/

if (!eregi("modules.php", $PHP_SELF)) {
    die ("No puede accesar este archivo directamente...");
}
require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
$index=0;

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
	    $regreso .= $full;
    } else {
	  for ($i=0; $i < intval($score); $i++) 
	    $regreso .= "$image";
	  if ($score-intval($score) >= .5) $regreso .= "$halfimage";
    } // else
	return $regreso;
}

function ver_excur($excurid) {
global $user_prefix, $db, $module_name, $admin, $user, $cookie, $currentlang;

$resultado = $db->sql_query("select * from ".$user_prefix."_excursiones WHERE excurid=$excurid");
$excurlistado = $db->sql_fetchrow($resultado);

// Verifica si el usuario es administrador o es el propietario de la excursión para poder editarla
if(is_admin($admin)) 
  {
    $editar = "[ <A HREF=\"modules.php?name=$module_name&amp;file=editarexcurisones&amp;cambia_excurid=$excurid&amp;op=modifyExcursion\">"._EDIT."</a> ] \n";
  }
  else {$editar = "";}

// Obtiene el Nombre de Usuario
   cookiedecode( $user );
   $username = $cookie[1];

   if( is_user( $user ) and $username==$excurlistado[userexcur])
   {
      $editar = "[ <A HREF=\"modules.php?name=$module_name&amp;file=editarexcursiones&amp;cambia_excurid=$excurid&amp;op=modifyExcursion\" class=\"topnav\">"._EDIT."</a> ] \n";
   }  
   else {$editar = "";}

include("header.php");
  $telefono = _TELEFONO.":&nbsp;".$excurlistado[telefono];
  $descri = "desc_".$currentlang;
  $descripcion = $excurlistado[$descri];

  if ( file_exists("modules/$module_name/images/fotos/$excurlistado[excurid]-2.jpg") )
    $imagen1 = "<img src=\"modules/$module_name/images/fotos/$excurlistado[excurid]-2.jpg\" width=200  align=left>";
  else
    $imagen1 = "";
  if ( file_exists("modules/$module_name/images/fotos/$excurlistado[excurid]-3.jpg") )
    $imagen2 = "<img src=\"modules/$module_name/images/fotos/$excurlistado[excurid]-3.jpg\" width=200  align=left>";
  else
    $imagen2 = "";
  if ( file_exists("modules/$module_name/images/fotos/$excurlistado[excurid]-4.jpg") )
    $imagen3 = "<img src=\"modules/$module_name/images/fotos/$excurlistado[excurid]-4.jpg\" width=200  align=left>";
  else
    $imagen3 = "";
  if ( file_exists("modules/$module_name/images/fotos/$excurlistado[excurid]-5.jpg") )
    $imagen4 = "<img src=\"modules/$module_name/images/fotos/$excurlistado[excurid]-5.jpg\" width=200  align=left>";
  else
    $imagen4 = "";
  
	  
	  
	 // Obtiene las calificaciones registradas
	  $resultcalifs=$db->sql_query("select AVG(vroverall), AVG(vrfood), AVG(vrservice), AVG(vrambiance), 
	  AVG(vrclean), AVG(vrmoney), AVG(vrwinelist), AVG(vrkid) from ".$user_prefix."_restovotos where restoid='$excurlistado[excurid]'");
	    list($overall, $food, $service, $ambiance, $clean, $money, $winelist, $kid) =  $db->sql_fetchrow($resultcalifs);
	  $calificaciones = "<table>
	  <tr><td align=\"center\" bgcolor=#FFFFCC>"._OVERALL."</td><td bgcolor=#FFFFCC >";
	  $calificaciones .= despliega_estrellas($overall);
	  $calificaciones .= "</td></tr>
	  <tr><td bgcolor=#CCFFFF align=\"center\">"._FOOD."</td><td bgcolor=#CCFFFF>";
	  $calificaciones .= despliega_estrellas($food);
	  $calificaciones .= "</td></tr>
	  <tr><td bgcolor=#FFCCFF align=\"center\">"._SERVICE."</td><td bgcolor=#FFCCFF>";
	  $calificaciones .= despliega_estrellas($service);
	  $calificaciones .= "</td></tr>
	  <tr><td bgcolor=#D5F99F align=\"center\">"._AMBIANCE."</td><td bgcolor=#D5F99F>";
	  $calificaciones .= despliega_estrellas($ambiance);
	  $calificaciones .= "</td></tr>
	  <tr><td bgcolor=#99CCCC align=\"center\">"._CLEANLINESS."</td><td bgcolor=#99CCCC>";
	  $calificaciones .= despliega_estrellas($clean);
	  $calificaciones .= "</td></tr>
	   <tr><td bgcolor=#99CCCC align=\"center\">"._VALUEFORMONEY."</td><td bgcolor=#99CCCC>";
	  $calificaciones .= despliega_estrellas($money);
	  $calificaciones .= "</td></tr>
	  <tr><td bgcolor=#99CCCC align=\"center\">"._WINELIST."</td><td bgcolor=#99CCCC>";
	  $calificaciones .= despliega_estrellas($winelist);
	  $calificaciones .= "</td></tr>
	  <tr><td bgcolor=#99CCCC align=\"center\">"._KIDFRIEND."</td><td bgcolor=#99CCCC>";
	  $calificaciones .= despliega_estrellas($kid);
	  $calificaciones .= "</td></tr>
	 </table>";
	  
	// Obtiene Comentarios de los Usuarios
	$sqlvotos="select * from ".$user_prefix."_restovotos where restoid='$restolistado[restoid]' order by vrfecha desc";
	$resvotos= $db->sql_query($sqlvotos);
	$comentarios = "<table bgcolor=\"#99CCCC\" width=\"97%\" align=center border=0>";
	if ($resvotos) {	  
	  $comentarios .="<tr>"._OPINIONESGENTE."</tr>";
	  //$comentarios .=" $restolistado[restoid]";
	  foreach ($resvotos as $listavotos){
	    $comentarios .= "<tr><td><img src=\"images/language/flag-$listavotos[vrlanguage].png\"&nbsp;</td>
		<td><b>"._ENVIADOPOR."&nbsp;$listavotos[userrvoto]</b> on $listavotos[vrfecha]";
		if(is_admin($admin))
		  $comentarios .= "<a href=\modules.php?name=restaurantes&file=restaurante&do=borrarcomentario&rvotoid=$listavotos[rvotoid]&restoid=$listavotos[restoid]>
		  <img src=\"modules/My_eGallery/images/delete.gif\" border=0></a>";
		$comentarios .= "<br>$listavotos[vrcoment]</td></tr>";
	  } // while
	} // if $resvotos
    $comentarios .= "</table>";
	
    $tmpl_file = "modules/$module_name/excursion-template.html";
    $thefile = implode("", file($tmpl_file));
    $thefile = addslashes($thefile);
    $thefile = "\$r_file=\"".$thefile."\";";
    eval($thefile);
    print $r_file;	
include("footer.php");
} // funcion ver_resto

switch ($do) {

case 'ver_excur':
  ver_excur($excurid);
  break;
  
case 'borrarcomentario':
                $sql = "DELETE from $prefix"._restovotos." where rvotoid=$rvotoid";
		mysql_query($sql);
                ver_resto($restoid);
		break;
}
?>