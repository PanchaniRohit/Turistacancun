<?php

/************************************************************************/
/* PHP-NUKE: Registro de Excursiones                                   */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 Enrique Montes                                    */
/* http://www.turista.com.mx                                            */
/************************************************************************/

if (!eregi("modules.php", $PHP_SELF)) {
    die ("No se puede accesar a este archivo directamente...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
/*********************************************************/
/* Funciones para Usuarios                               */
/*********************************************************/

function displayexcursion() {
    global $user_prefix, $dbi, $currentlang, $user, $admin, $cookie, $module_name;
    include("header.php");
    OpenTable();
	// Obtiene el Nombre de Usuario
      cookiedecode( $user );
      $add_userexcur = $cookie[1];
    echo "<center><font class=\"option\"><b>"._AGREGAR."</b></font><br><br>"
	."<form action=\"modules.php?name=$module_name&amp;file=editarexcursiones\" method=\"post\">"
	."<table border=\"0\" width=\"100%\">"
	."<tr><td>"._USUARIOAUTORIZADO.":</td>"
	."<td>$add_userexcur</td>"
        ."<tr><td>"._NOMBRE."</td>"
        ."<td><input type=\"text\" name=\"add_nombre\" size=\"78\" maxlength=\"255\">"._REQUERIDO." </td></tr>"
        ."<tr><td>"._DIRECCION."</td>"
        ."<td><input type=\"text\" name=\"add_direccion\" size=\"78\" maxlength=\"255\">"._REQUERIDO."</td></tr>"
		."<tr><td>"._TELEFONO."</td>"
        ."<td><input type=\"text\" name=\"add_telefono\" Value=\"$cambia_telefono\" size=\"78\" maxlength=\"20\">"._REQUERIDO."</td></tr>"
        ."<tr><td>"._EMAIL."</td>"
        ."<td><input type=\"text\" name=\"add_email\" size=\"78\" maxlength=\"67\">"._REQUERIDO." </td></tr>"
        ."<tr><td>"._HORARIO."</td>"
        ."<td><input type=\"text\" name=\"add_horario\" size=\"80\" maxlength=\"67\"></td></tr>"  
		."<tr><td>"._URL."</td>"
        ."<td><input type=\"text\" name=\"add_paginaweb\" size=\"80\" maxlength=\"67\"></td></tr>"       	
		."<tr><td>"._DESCRIPCIONENGLISH."</td>"
        ."<td><textarea name=\"add_desc_english\" rows=\"6\" cols=\"80\"></textarea></td></tr>"
        ."<tr><td>"._DESCRIPCIONSPANISH."</td>"
        ."<td><textarea name=\"add_desc_spanish\" rows=\"6\" cols=\"80\"></textarea></td></tr>"
		."<tr><td>"._DESCRIPCIONPORTUGUESE."</td>"
        ."<td><textarea name=\"add_desc_portuguese\" rows=\"6\" cols=\"80\"></textarea></td></tr>"
		."<tr><td>"._DESCRIPCIONGERMAN."</td>"
        ."<td><textarea name=\"add_desc_german\" rows=\"6\" cols=\"80\"></textarea></td></tr>"
		."<tr><td>"._DESCRIPCIONITALIAN."</td>"
        ."<td><textarea name=\"add_desc_italian\" rows=\"6\" cols=\"80\"></textarea></td></tr>"
		."<tr><td>"._DESCRIPCIONFRENCH."</td>"
        ."<td><textarea name=\"add_desc_french\" rows=\"6\" cols=\"80\"></textarea></td></tr>"
		
		."<input type=\"hidden\" name=\"add_userexcur\" value=$add_userexcur>"
        ."<input type=\"hidden\" name=\"op\" value=\"addExcursion\">"
        ."<tr><td><input type=\"submit\" value=\"Agregar Excursi�n\"></form></td></tr>"
        ."</table>";
    CloseTable();
    include("footer.php");
}

function modifyExcursion($cambia_excurid) {
    global $user_prefix, $dbi, $currentlang, $user, $admin, $cookie, $module_name;
    include("header.php");
	$puedeeditar = 0;
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ADMINISTRACIONEXCURSIONES."</b></font></center>";
    CloseTable();
    echo "<br>";
	$sqlcambio = "select excurid,nombre,userexcur,direccion,telefono,email,paginaweb,horario, desc_english, desc_spanish, desc_portuguese,
	desc_german, desc_italian, desc_french from ".$user_prefix."_excursiones where excurid='$cambia_excurid'";
	$result = sql_query($sqlcambio, $dbi);
    if(sql_num_rows($result, $dbi) > 0) {
      list($cambia_excurid, $cambia_nombre, $cambia_userexcur, $cambia_direccion, $cambia_telefono, $cambia_email, $cambia_paginaweb, 
	  $cambia_horario, $cambia_desc_english, $cambia_desc_spanish, $cambia_desc_portuguese, $cambia_desc_german, $cambia_desc_italian,
	  $cambia_desc_french, $cambia_especialidad) = sql_fetch_row($result, $dbi);
 	  OpenTable();
	  // Obtiene el Nombre de Usuario
      cookiedecode( $user );
      $username = $cookie[1];
      if( $username == $cambia_userexcur ) {
	      $puedeeditar = 1;} 
	  if(is_admin($admin)) 
      { $puedeeditar = 1;} 
		  
	  if ($puedeeditar == 1)
	  {
 	    echo "<center><font class=\"option\"><b>"._ACTUALIZARDATOSEXCURSION.": <i>$chng_nombre</i></b></font></center>"
	    ."<form method=\"post\">"
	    ."<table border=\"0\">"
	    ."<tr><td>"._EXCURID."</td>"
	    ."<td>$cambia_excurid&nbsp;&nbsp;&nbsp;&nbsp;Usuario Autorizado: "
	    ."<input type=\"text\" name=\"cambia_userexcur\" value=\"$cambia_userexcur\" size=\"15\" maxlength=\"20\"></td></tr>"
	    ."<td>"._NOMBRE."</td>"
        ."<td><input type=\"text\" name=\"cambia_nombre\" Value=\"$cambia_nombre\" size=\"80\" maxlength=\"255\"> </td></tr>"
        ."<tr><td>"._DIRECCION."</td>"
        ."<td><input type=\"text\" name=\"cambia_direccion\" Value=\"$cambia_direccion\" size=\"80\" maxlength=\"255\"></td></tr>"
		."<tr><td>"._TELEFONO."</td>"
        ."<td><input type=\"text\" name=\"cambia_telefono\" Value=\"$cambia_telefono\" size=\"80\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._EMAIL."</td>"
        ."<td><input type=\"text\" name=\"cambia_email\" Value=\"$cambia_email\" size=\"80\" maxlength=\"67\"> </td></tr>"
        ."<tr><td>"._URL."</td>"
        ."<td><input type=\"text\" name=\"cambia_paginaweb\" Value=\"$cambia_paginaweb\" size=\"80\" maxlength=\"67\"></td></tr>"
		."<tr><td>"._HORARIO."</td>"
        ."<td><input type=\"text\" name=\"cambia_horario\" Value=\"$cambia_horario\" size=\"80\" maxlength=\"67\"></td></tr>"	
	    ."<tr><td>"._DESCRIPCIONENGLISH."</td>"
        ."<td><textarea name=\"cambia_desc_english\" rows=\"6\" cols=\"80\">$cambia_desc_english</textarea></td></tr>"
        ."<tr><td>"._DESCRIPCIONSPANISH."</td>"
        ."<td><textarea name=\"cambia_desc_spanish\" rows=\"6\" cols=\"80\">$cambia_desc_spanish</textarea></td></tr>"
	    ."<tr><td>"._DESCRIPCIONPORTUGUESE."</td>"
        ."<td><textarea name=\"cambia_desc_portuguese\" rows=\"6\" cols=\"80\">$cambia_desc_portuguese</textarea></td></tr>"
		."<tr><td>"._DESCRIPCIONGERMAN."</td>"
        ."<td><textarea name=\"cambia_desc_german\" rows=\"6\" cols=\"80\">$cambia_desc_german</textarea></td></tr>"
		."<tr><td>"._DESCRIPCIONITALIAN."</td>"
        ."<td><textarea name=\"cambia_desc_italian\" rows=\"6\" cols=\"80\">$cambia_desc_italian</textarea></td></tr>"
		."<tr><td>"._DESCRIPCIONFRENCH."</td>"
        ."<td><textarea name=\"cambia_desc_french\" rows=\"6\" cols=\"80\">$cambia_desc_french</textarea></td></tr>"
		."</td></tr>";
		echo "<input type=\"hidden\" name=\"cambia_excurid\" value=\"$cambia_excurid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"actualizarexcursion\">"
	    ."<tr><td><input type=\"submit\" value=\""._SAVECHANGES."\"></form></td></tr>"
	    ."</table>";
	   
	    echo "<br>";
        echo "<hr>Imagen en P�gina de Listado general de Excursiones:<br>";
        echo "<img src=\"modules/$module_name/images/fotos/$cambia_excurid-1.jpg\">"
        ."<br><A HREF=\"modules.php?name=$module_name&amp;file=enviarfoto&amp;cambia_excurid=$cambia_excurid&amp;numfoto=1\">Enviar Nueva Fotograf�a</a>"
        ."<br>Esta Fotograf�a debe ser jpg y con dimensiones proporcionales a 350 x 70 pixeles";
		echo "<hr>Imagen 1 en P�gina de Vista detallada de la Excursi�n:<br>";
        echo "<img src=\"modules/$module_name/images/fotos/$cambia_excurid-2.jpg\">"
        ."<br><A HREF=\"modules.php?name=$module_name&amp;file=enviarfoto&amp;cambia_excurid=$cambia_excurid&amp;numfoto=2\">Enviar Nueva Fotograf�a</a>"
        ."<br>Esta Fotograf�a debe ser jpg y ser� deplegada con un ancho de 200 pixeles";
		echo "<hr>Imagen 2 en P�gina de Vista detallada de la Excursi�n:<br>";
        echo "<img src=\"modules/$module_name/images/fotos/$cambia_excurid-3.jpg\">"
        ."<br><A HREF=\"modules.php?name=$module_name&amp;file=enviarfoto&amp;cambia_excurid=$cambia_excurid&amp;numfoto=3\">Enviar Nueva Fotograf�a</a>"
        ."<br>Esta Fotograf�a debe ser jpg y ser� deplegada con un ancho de 200 pixeles";
		echo "<hr>Imagen 3 en P�gina de Vista detallada de la Excursi�n:<br>";
        echo "<img src=\"modules/$module_name/images/fotos/$cambia_excurid-4.jpg\">"
        ."<br><A HREF=\"modules.php?name=$module_name&amp;file=enviarfoto&amp;cambia_excurid=$cambia_excurid&amp;numfoto=4\">Enviar Nueva Fotograf�a</a>"
        ."<br>Esta Fotograf�a debe ser jpg y ser� deplegada con un ancho de 200 pixeles";
		echo "<hr>Imagen 4 en P�gina de Vista detallada de la Excursi�n:<br>";
        echo "<img src=\"modules/$module_name/images/fotos/$cambia_excurid-5.jpg\">"
        ."<br><A HREF=\"modules.php?name=$module_name&amp;file=enviarfoto&amp;cambia_excurid=$cambia_excurid&amp;numfoto=5\">Enviar Nueva Fotograf�a</a>"
        ."<br>Esta Fotograf�a debe ser jpg y ser� deplegada con un ancho de 200 pixeles";
        CloseTable();
	  } // if puede editar
	  else {
	    echo "<center><b>Usted no est� autorizado para modificar los datos de esta excursi�n</b></center>";
		CloseTable();
	  }
    } else {
	OpenTable();
	echo "<center><b>No se encontr� la Excursi�n solicitada</b><br><br>"
	    .""._GOBACK."</center>";
	CloseTable();
    }
    include("footer.php");
}


switch($op) {

    case "mod_excursiones":
    displayexcursion();
    break;
	
    case "modifyExcursion":
    modifyExcursion($cambia_excurid);
    break;

    case "actualizarexcursion": 
	$sql = "update ".$user_prefix."_excursiones set nombre='$cambia_nombre',userexcur='$cambia_userexcur',direccion='$cambia_direccion', telefono='$cambia_telefono',email='$cambia_email', paginaweb='$cambia_paginaweb', horario='$cambia_horario',
	desc_english='$cambia_desc_english', desc_spanish='$cambia_desc_spanish', desc_portuguese = '$cambia_desc_portuguese', 
	desc_german = '$cambia_desc_german', desc_italian = '$cambia_desc_italian', desc_french = '$cambia_desc_french' 
	where excurid='$cambia_excurid'";
	sql_query($sql, $dbi);
	sql_query("delete from ".$user_prefix."_excursiones where excurid='$del_excurid'", $dbi);
	//Header("Location: modules.php?name=$module_name");
	Include("header.php");
	OpenTable();
	echo "Datos de la Excursi�n Actualizados";
	echo "<br>[<a href=\"modules.php?name=$module_name&amp;file=excursion&amp;excurid=$cambia_excurid\">Ver Excursi�n $cambia_nombre</a>]
	&nbsp;[<a href=\"modules.php?name=$module_name&amp;file=editarexcursiones&amp;cambia_excurid=$cambia_excurid&amp;op=modifyExcursion\">Editar la excursi�n</a>]";

	CloseTable();
	Include("footer.php");
    break;

    case "delExcursion":
    include("header.php");
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ADMINISTRACIONEXCURSIONES."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>Borrar Excursion</b></font><br><br>"
	."�Est� seguro que desea eliminar los datos de la excursi�n $cambia_excurid?<br><br>"
	."[ <a href=\"modules.php?name=$module_name&amp;file=editarexcursiones&amp;op=delExcursionConf&amp;del_excurid=$cambia_excurid\">"._YES."</a> | <a href=\"modules.php?name=$module_name\">"._NO."</a> ]</center>";
    CloseTable();
    include("footer.php");
    break;

    case "delExcursionConf":
    sql_query("delete from ".$user_prefix."_excursiones where excurid='$del_excurid'", $dbi);
    Header("Location: modules.php?name=$module_name");
    break;

    case "addExcursion":
    if (!($add_nombre && $add_direccion && $add_email &&$add_telefono)) { // Si Alg�n campo est� vac�o
	include("header.php");
	OpenTable();
	echo "<center><font class=\"title\"><b>"._AGREGAR."</b></font></center>";
	CloseTable();
	echo "<br>";
        OpenTable();
	echo "<center><b>"._NEEDTOCOMPLETE."</b><br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
        return;
    }

    // Inicia secci�n de inserci�n del registro
    $sql = "insert into ".$user_prefix."_excurqueue ";
    $sql .= "(excurid,nombre,userexcur,direccion, telefono, email,paginaweb,horario,";
	$sql .= "desc_english, desc_spanish, desc_portuguese, desc_german, desc_italian, desc_french)";
    $sql .= "values (NULL,'$add_nombre','$add_userexcur','$add_direccion','$add_telefono','$add_email','$add_paginaweb','$add_horario',";
	$sql .= "'$add_desc_english','$add_desc_spanish','$add_desc_portuguese','$add_desc_german','$add_desc_italian','$add_desc_french')";	
    $result = sql_query($sql, $dbi);
    if (!$result) {
	    echo "hubo un error en los datos de env�o<br>";
		echo "El sql fue ".$sql;
        return;
    } 
	
    include ('header.php');
    OpenTable();
    $result = sql_query("select * from ".$prefix."_excurqueue", $dbi);
    $waiting = sql_num_rows($result, $dbi);
    echo "<center><font class=\"title\">"._SUBSENT."</font><br><br>"
	."<font class=\"content\"><b>"._THANKSSUB."</b><br><br>"
	.""._SUBTEXT.""
	."<br>"._WEHAVESUB." $waiting "._WAITING."";
    CloseTable();
    include ('footer.php');
    //break;
	    
	//inicia secci�n de INSERTAR FOTO
		case "insertafoto": //OK! 
				include ("admin/modules/gallery/fileFunctions.php");
				if (isset($add) && $add=="Insert") {
					//list($galloc) = mysql_fetch_row(mysql_query("SELECT galloc FROM $prefix"._gallery_categories." WHERE gallid=$Category"));					
					$wdir = "/";
					$directorio = "modules/$module_name/images/fotos/";
					$userfile_name=  traite_nom_fichier($userfile_name);                  
					$ext = substr($userfile_name, (strrpos($userfile_name,'.') +  1));
					/*$result = mysql_query("select filetype from $prefix"._gallery_media_types." where extension='$ext'");							
					if (mysql_num_rows($result)==0) {*/
					if ($ext <> 'jpg'){
						include ("header.php");						
					  	OpenTable();
					  	echo "<center><font size=\"4\"><b>Inserci�n de Fotograf�as</b></font></center>";
					  	CloseTable();
					  	echo "<br>";
					  	OpenTable();
						echo '<br>';
						echo '<center>Por el momento solamente se admiten archivos tipo .jpg<br>
						(En el nombre de archivo la extensi�n debe de estar con min�sculas)<br>'._GOBACK.'</center>';
						CloseTable();
						include ("footer.php");
					}
					$archivodestino = $cambia_excurid."-".$numfoto.".jpg";
					$upload_return = UploadFile($directorio, $userfile, $archivodestino, $userfile_size);
					if ($upload_return=="OK") {						
						include("header.php");
						OpenTable();
						echo "Archivo $archivodestino enviado exitosamente";
						echo "<br><a href=\"modules.php?name=$module_name&file=editarexcursiones&cambia_excurid=$cambia_excurid&op=modifyExcursion\">
						     Regresar a la Edici�n de la Excursi�n</a>";
						CloseTable();
						include("footer.php");
					} // $upload_return =="OK"
					else {
						include ("header.php");
					  	OpenTable();
					  	echo "<center><font size=\"4\"><b>Error en Env�o de Fotograf�as</b></font></center>";
					  	CloseTable();
					  	echo "<br>";
					  	OpenTable();
						echo '<br>';
						echo '<center>'.$upload_return.'<br><br>Regresar</center>';
						CloseTable();
						include ("footer.php");
					}
					//Header("Location: $adminurl");
				}
				else {
					include ("$adminpath/createmedia.php");
					createmedia();
				}
				rebuild_totals();
				rebuild_lastadd();
				break;		
}

/*} else {
    echo "Access Denied";
}*/

?>