<?

	global $prefix, $galleryvar,$bgcolor1, $gallerypath, $adminurl, $font;

	include("header.php");
  	
  	echo "<br>";
  	OpenTable();
	echo "id del Restaurante: $cambia_restoid";
  	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td>";
	
	 echo    "<form method=\"POST\" action=\"modules.php?name=restaurantes&amp;file=editarestaurantes\" enctype=\"multipart/form-data\">
			<input type=\"hidden\" name=\"op\" value=\"insertafoto\">
			<input type=\"hidden\" name=\"cambia_restoid\" value=\"$cambia_restoid\">
		</td>
	  <tr>
		<td width=\"100%\" bgcolor=\"$bgcolor1\">
			  <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" ><tr>
			    <td align=\"left\" valign=\"top\" >
			        <font class=\"".$font['title']."\">Archivo a Insertar</font><br>";
					echo "<INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000\">";
				echo "
					<input type=\"file\" name=\"userfile\" size=\"14\"><br>
			     </td>
			     </tr>
			<tr><td align=\"center\"><input type=\"submit\" value=\"Insert\" name=\"add\"></td></tr>
	          </table>
		      </td>
			  </tr>
		</form>  
	  </table>";
	  echo "Las fotografías deben de ser de formato .jpg (el nombre de la extensión debe estar con minúsculas<br>
	        Las dimensiones de la foto deberán ser proporcionales a 160 x 96 pixeles (de otra manera pueden verse deformadas)<br>
			El Tamaño máximo para un Archivo es de 100 Kbytes, es recomendable que éste no exceda los 10 Kbytes para lograr un despliegue más rápido.  ";
	CloseTable();
    include("footer.php");

?>

