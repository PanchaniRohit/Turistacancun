<?php

/************************************************************************/
/* PHP-NUKE: Registro de Restaurantes                                   */
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

function displayrestaurante() {
    global $user_prefix, $dbi, $currentlang, $user, $admin, $cookie, $module_name;
    include("header.php");
    OpenTable();
	// Obtiene el Nombre de Usuario
      cookiedecode( $user );
      $add_userresto = $cookie[1];
    echo "<center><font class=\"option\"><b>"._AGREGARRESTAURANTE."</b></font><br><br>"
	."<form action=\"modules.php?name=restaurantes&amp;file=editarestaurantes\" method=\"post\">"
	."<table border=\"0\" width=\"100%\">"
	."<tr><td>"._USUARIOAUTORIZADO.":</td>"
	."<td>$add_userresto</td>"
	//."<td><input type=\"text\" name=\"add_userresto\" value=\"$add_userresto\" size=\"15\" maxlength=\"20\">"
	//._WARNINGUSER."</td></tr>"
        ."<tr><td>"._NOMBRE."</td>"
        ."<td><input type=\"text\" name=\"add_nombre\" size=\"78\" maxlength=\"255\">"._REQUERIDO." </td></tr>"
        ."<tr><td>"._DIRECCION."</td>"
        ."<td><input type=\"text\" name=\"add_direccion\" size=\"78\" maxlength=\"255\">"._REQUERIDO."</td></tr>"
		."<tr><td>"._TELEFONO."</td>"
        ."<td><input type=\"text\" name=\"add_telefono\" Value=\"$cambia_telefono\" size=\"78\" maxlength=\"20\">"._REQUERIDO."</td></tr>"
        ."<tr><td>"._EMAIL."</td>"
        ."<td><input type=\"text\" name=\"add_email\" size=\"78\" maxlength=\"67\">"._REQUERIDO." </td></tr>"
        ."<tr><td>"._URL."</td>"
        ."<td><input type=\"text\" name=\"add_paginaweb\" size=\"80\" maxlength=\"67\"></td></tr>"       
        ."<tr><td>"._ESPECIALIDAD.":</td>"
		."<td><select name=\"add_especialidad\"> ";
		// Desplegado de Especialidades (Tipo de Cocina)
		$sqlview="select * from ".$user_prefix."_restaurantesespecial";
		$resulview = sql_query($sqlview, $dbi);		
		$descri="resp_desc_".$currentlang;
		while ($views = sql_fetch_array($resulview, $dbi) ){		
		 
		    echo "<option value=$views[respid]>$views[$descri]</option>";
		} // while $views
		echo "</select></td></tr>"  	
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
		."<tr><td>"._SERVICIOS."</td>"
		."<td>"._DESAYUNO."<input type=\"checkbox\" name=\"desayuno\" VALUE=\"1\">"
		."&nbsp;"._COMIDA."<input type=\"checkbox\" name=\"comida\" VALUE=\"1\">"
		."&nbsp;"._CENA."<input type=\"checkbox\" name=\"cena\" VALUE=\"1\"></td>"
		."<tr><td>"._TARJETAS."</td>"
        ."<td>";
		for ($i=1; $i<=4; $i ++) {
		 echo "<img src=\"modules/$module_name/images/creditcard_$i.gif\">
		      <input type=\"checkbox\" name=\"tarjeta$i\" VALUE=\"1\">";
		} // for $i
		echo "</td>"
		."<input type=\"hidden\" name=\"add_userresto\" value=$add_userresto>"
        ."<input type=\"hidden\" name=\"op\" value=\"addRestaurante\">"
        ."<tr><td><input type=\"submit\" value=\"Agregar Restaurante\"></form></td></tr>"
        ."</table>";
    CloseTable();
    include("footer.php");
}

function modifyRestaurante($cambia_restoid) {
    global $user_prefix, $dbi, $currentlang, $user, $admin, $cookie, $module_name;
    include("header.php");
	$puedeeditar = 0;
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ADMINISTRACIONRESTAURANTES."</b></font></center>";
    CloseTable();
    echo "<br>";
	$sqlcambio = "select restoid,nombre,userresto,direccion,telefono,email,paginaweb,horario, desc_english, desc_spanish, desc_portuguese,
	desc_german, desc_italian, desc_french, especialidad, servicio, tarjetas from ".$user_prefix."_restaurantes where restoid='$cambia_restoid'";
	$result = sql_query($sqlcambio, $dbi);
    if(sql_num_rows($result, $dbi) > 0) {
      list($cambia_restoid, $cambia_nombre, $cambia_userresto, $cambia_direccion, $cambia_telefono, $cambia_email, $cambia_paginaweb, 
	  $cambia_horario, $cambia_desc_english, $cambia_desc_spanish, $cambia_desc_portuguese, $cambia_desc_german, $cambia_desc_italian,
	  $cambia_desc_french, $cambia_especialidad, $cambia_servicio, $cambia_tarjetas) = sql_fetch_row($result, $dbi);
 	  OpenTable();
	  // Obtiene el Nombre de Usuario
      cookiedecode( $user );
      $username = $cookie[1];
      if( $username == $cambia_userresto ) {
	      $puedeeditar = 1;} 
	  if(is_admin($admin)) 
      { $puedeeditar = 1;} 
		  
	  if ($puedeeditar == 1)
	  {
 	    echo "<center><font class=\"option\"><b>"._ACTUALIZARDATOSRESTAURANTE.": <i>$chng_nombre</i></b></font></center>"
	    ."<form method=\"post\">"
	    ."<table border=\"0\">"
	    ."<tr><td>"._RESTOID."</td>"
	    ."<td>$cambia_restoid&nbsp;&nbsp;&nbsp;&nbsp;Usuario Autorizado: "
	    ."<input type=\"text\" name=\"cambia_userresto\" value=\"$cambia_userresto\" size=\"15\" maxlength=\"20\"></td></tr>"
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
        ."<tr><td>"._ESPECIALIDAD."</td>"
        ."<td><select name=\"cambia_especialidad\"> ";
		// Desplegado de Vistas
		$sqlespecial="select * from ".$user_prefix."_restaurantesespecial";
		$resulespecial = sql_query($sqlespecial, $dbi);		
		$descri="resp_desc_".$currentlang;
		while ($especialidades = sql_fetch_array($resulespecial, $dbi) ){		
		  if ($cambia_especialidad == $especialidades[respid])
		    echo "<option value=$especialidades[respid] selected>$especialidades[$descri]</option>";
		  else
		    echo "<option value=$especialidades[respid]>$especialidades[$descri]</option>";
		} // while $especialidades
		echo "</select></td></tr>"  	
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
		."<tr><td>"._SERVICIOS."</td>";
		if ( substr($cambia_servicio,0,1) == 1) 
		  echo "<td>"._DESAYUNO."<input type=\"checkbox\" name=\"desayuno\" VALUE=\"1\" checked>";
		else
		  echo "<td>"._DESAYUNO."<input type=\"checkbox\" name=\"desayuno\" VALUE=\"1\">";
		if ( substr($cambia_servicio,1,1) == 1) 
		  echo "&nbsp;"._COMIDA."<input type=\"checkbox\" name=\"comida\" VALUE=\"1\" checked>";
		else
		  echo "&nbsp;"._COMIDA."<input type=\"checkbox\" name=\"comida\" VALUE=\"1\">";
		if ( substr($cambia_servicio,2,1) == 1) 
		  echo "&nbsp;"._CENA."<input type=\"checkbox\" name=\"cena\" VALUE=\"1\" checked></td>";
		else
		  echo "&nbsp;"._CENA."<input type=\"checkbox\" name=\"cena\" VALUE=\"1\"></td>";
		echo "<tr><td>"._TARJETAS."</td><td>";
        for ($i=1; $i<=4; $i ++) {
		 echo "<img src=\"modules/$module_name/images/creditcard_$i.gif\">";
		 if ( substr($cambia_tarjetas.$i, $i-1, 1) == 1)
		    echo "<input type=\"checkbox\" name=\"tarjeta$i\" VALUE=\"1\" checked>";
		 else 
		   echo "<input type=\"checkbox\" name=\"tarjeta$i\" VALUE=\"1\">";
		} // for $i
		echo "</td></tr>";
		echo "<input type=\"hidden\" name=\"cambia_restoid\" value=\"$cambia_restoid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"actualizarestaurante\">"
	    ."<tr><td><input type=\"submit\" value=\""._SAVECHANGES."\"></form></td></tr>"
	    ."</table>";
	   
	    echo "<br>";
        echo "<img src=\"modules/$module_name/images/fotos/$cambia_restoid.jpg\">"
        ."<br><A HREF=\"modules.php?name=restaurantes&amp;file=enviarfoto&amp;cambia_restoid=$cambia_restoid\">Enviar Nueva Fotografía</a>";
        CloseTable();
	  } // if puede editar
	  else {
	    echo "<center><b>Usted no está autorizado para modificar los datos de este restaurante</b></center>";
		CloseTable();
	  }
    } else {
	OpenTable();
	echo "<center><b>No se encontró el Restaurante solicitado</b><br><br>"
	    .""._GOBACK."</center>";
	CloseTable();
    }
    include("footer.php");
}

function subefoto($cambia_hotelid)
{
include ("header.php");
        /********************************************************
	    // Inicia Rutina de Subir Fotografía
		**********************************************************/
	    OpenTable();
	    echo "$cambia_restoid <br>$userfile_name<br>		
		 <form enctype=\"multipart/form-data\" action=\"$PHP_SELF\" method=\"POST\">"	    
		."<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"5000000\">"
        ."Enviar este archivo: "
        ."<input name=\"userfile\" type=\"file\"><br>"
        ."<input type=\"submit\" name=\"submit\" value=\"Enviar Fotografía\">"
        ."</form>";	    
	    CloseTable();
		$dir="/httpdocs/modules/$module_name/images/fotos/";
		// copy the file to the server
        if (isset($submit)){
		   echo "voy a hacer el copy";
           copy($userfile,"$dir.$userfile_name");		
	      if (!is_uploaded_file ($userfile)){
		    echo " <b>$userfile_name</b> couldn't be copied!!";
	      }
		echo "Ya pasé por el copy";
        }
        // check whether it has been uploaded
        if (is_uploaded_file ($userfile)){
		 
	       echo " <b>$userfile_name</b> copied succesfully!!";
		}
  include ("footer.php");
}

function actualizarestaurante($cambia_restoid, $cambia_nombre, $cambia_userresto, $cambia_direccion, $cambia_telefono, $cambia_email, 
$cambia_paginaweb, $cambia_horario, $cambia_desc_english, $cambia_desc_spanish,  $cambia_desc_portuguese, $cambia_desc_german,
	$cambia_desc_italian, $cambia_desc_french, $cambia_especialidad, $desayuno, $comida, $cena, 
	$tarjeta1, $tarjeta2, $tarjeta3, $tarjeta4) {
    global $user_prefix, $dbi;
	if ($desayuno == 1) $cambia_servicio='1'; else $cambia_servicio='0';
	if ($comida == 1) $cambia_servicio.='1'; else $cambia_servicio.='0';
	if ($cena == 1) $cambia_servicio.='1'; else $cambia_servicio.='0';
	if ($tarjeta1 == 1) $add_tarjetas='1'; else $add_tarjetas='0';
	if ($tarjeta2 == 1) $add_tarjetas.='1'; else $add_tarjetas.='0';
	if ($tarjeta3 == 1) $add_tarjetas.='1'; else $add_tarjetas.='0';
	if ($tarjeta4 == 1) $add_tarjetas.='1'; else $add_tarjetas.='0';
	sql_query("update ".$user_prefix."_restaurantes set nombre='$cambia_nombre',userresto='$cambia_userresto',direccion='$cambia_direccion', telefono='$cambia_telefono',email='$cambia_email', paginaweb='$cambia_paginaweb', horario='$cambia_horario',
	desc_english='$cambia_desc_english', desc_spanish='$cambia_desc_spanish', desc_portuguese = '$cambia_desc_portuguese', 
	desc_german = '$cambia_desc_german', desc_italian = '$cambia_desc_italian', desc_french = '$cambia_desc_french', 
	especialidad='$cambia_especialidad', servicio='$cambia_servicio', tarjetas='$add_tarjetas' where restoid='$cambia_restoid'", $dbi);
	Header("Location: modules.php?name=restaurantes");
}  // actualizaRestaurante

switch($op) {

    case "mod_restaurantes":
    displayrestaurante();
    break;
	
	case "subefoto":
	subefoto($cambia_restoid);
	break;
	
    case "modifyRestaurante":
    modifyRestaurante($cambia_restoid);
    break;

    case "actualizarestaurante":
    actualizarestaurante($cambia_restoid, $cambia_nombre, $cambia_userresto, $cambia_direccion, $cambia_telefono, $cambia_email, 
	$cambia_paginaweb, $cambia_horario, $cambia_desc_english, $cambia_desc_spanish, $cambia_desc_portuguese, $cambia_desc_german,
	$cambia_desc_italian, $cambia_desc_french, $cambia_especialidad, $desayuno, $comida, $cena, 
	$tarjeta1, $tarjeta2, $tarjeta3, $tarjeta4);
    break;

    case "delRestaurante":
    include("header.php");
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ADMINISTRACIONRESTAURANTES."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>Borrar Restaurante</b></font><br><br>"
	."¿Está seguro que desea eliminar los datos del restaurante $cambia_restoid?<br><br>"
	."[ <a href=\"modules.php?name=restaurantes&amp;file=editarestaurantes&amp;op=delRestauranteConf&amp;del_restoid=$cambia_restoid\">"._YES."</a> | <a href=\"modules.php?name=restaurantes\">"._NO."</a> ]</center>";
    CloseTable();
    include("footer.php");
    break;

    case "delRestauranteConf":
    sql_query("delete from ".$user_prefix."_restaurantes where restoid='$del_restoid'", $dbi);
    Header("Location: modules.php?name=restaurantes");
    break;

    case "addRestaurante":
    if (!($add_nombre && $add_direccion && $add_email &&$add_telefono)) { // Si Algún campo está vacío
	include("header.php");
	OpenTable();
	echo "<center><font class=\"title\"><b>"._ALTARESTAURANTE."</b></font></center>";
	CloseTable();
	echo "<br>";
        OpenTable();
	echo "<center><b>"._NEEDTOCOMPLETE."</b><br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
        return;
    }
    if ($desayuno == 1) $add_servicio='1'; else $add_servicio='0';
	if ($comida == 1) $add_servicio.='1'; else $add_servicio.='0';
	if ($cena == 1) $add_servicio.='1'; else $add_servicio.='0';
	if ($tarjeta1 == 1) $add_tarjetas='1'; else $add_tarjetas='0';
	if ($tarjeta2 == 1) $add_tarjetas.='1'; else $add_tarjetas.='0';
	if ($tarjeta3 == 1) $add_tarjetas.='1'; else $add_tarjetas.='0';
	if ($tarjeta4 == 1) $add_tarjetas.='1'; else $add_tarjetas.='0';
    // Inicia sección de inserción del registro
    $sql = "insert into ".$user_prefix."_restoqueue ";
    $sql .= "(restoid,nombre,userresto,direccion, telefono, email,paginaweb,";
	$sql .= "desc_english, desc_spanish, desc_portuguese, desc_german, desc_italian, desc_french, especialidad, servicio, tarjetas)";
    $sql .= "values (NULL,'$add_nombre','$add_userresto','$add_direccion','$add_telefono','$add_email','$add_paginaweb',";
	$sql .= "'$add_desc_english','$add_desc_spanish','$add_desc_portuguese','$add_desc_german','$add_desc_italian','$add_desc_french',";
	$sql .= "'$add_especialidad','$add_servicio','$add_tarjetas')";	
    $result = sql_query($sql, $dbi);
    if (!$result) {
	    echo "hubo un error en los datos de envío<br>";
		echo "El sql fue ".$sql;
        return;
    } 
	
    include ('header.php');
    OpenTable();
    $result = sql_query("select * from ".$prefix."_restoqueue", $dbi);
    $waiting = sql_num_rows($result, $dbi);
    echo "<center><font class=\"title\">"._SUBSENT."</font><br><br>"
	."<font class=\"content\"><b>"._THANKSSUB."</b><br><br>"
	.""._SUBTEXT.""
	."<br>"._WEHAVESUB." $waiting "._WAITING."";
    CloseTable();
    include ('footer.php');
    //break;
	    
		//inicia sección de INSERTAR FOTO
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
					  	echo "<center><font size=\"4\"><b>Inserción de Fotografías</b></font></center>";
					  	CloseTable();
					  	echo "<br>";
					  	OpenTable();
						echo '<br>';
						echo '<center>Por el momento solamente se admiten archivos tipo .jpg<br>
						(En el nombre de archivo la extensión debe de estar con minúsculas)<br>'._GOBACK.'</center>';
						CloseTable();
						include ("footer.php");
					}
					//$upload_return = UploadFile($gallerypath.$wdir.$galloc, $userfile, $userfile_name, $userfile_size);
					$archivodestino = $cambia_restoid.".jpg";
					$upload_return = UploadFile($directorio, $userfile, $archivodestino, $userfile_size);
					if ($upload_return=="OK") {						
						include("header.php");
						OpenTable();
						echo "Archivo $archivodestino enviado exitosamente";
						CloseTable();
						include("footer.php");
					} // $upload_return =="OK"
					else {
						include ("header.php");
					  	OpenTable();
					  	echo "<center><font size=\"4\"><b>"._GALADMIN."</b></font></center>";
					  	CloseTable();
					  	echo "<br>";
					  	OpenTable();
						echo '<br>';
						echo '<center>'.$upload_return.'<br><br>'._GOBACK.'</center>';
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