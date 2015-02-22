<?

	$module_name = basename(dirname(__FILE__));
get_lang($module_name);

	global $prefix, $galleryvar,$bgcolor1, $gallerypath, $adminurl, $font;

	include("header.php");
  	
  	echo "<br>";
  	OpenTable();
	echo "id de la Excursión: $cambia_excurid id de la foto: $numfoto";
  	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td>";
	
	 echo    "<form method=\"POST\" action=\"modules.php?name=$module_name&amp;file=editarexcursiones\" enctype=\"multipart/form-data\">
			<input type=\"hidden\" name=\"op\" value=\"insertafoto\">
			<input type=\"hidden\" name=\"cambia_excurid\" value=\"$cambia_excurid\">
			<input type=\"hidden\" name=\"numfoto\" value=\"$numfoto\">
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
	  echo _INSTRUCFOTOS1;
	CloseTable();
    include("footer.php");

?>

