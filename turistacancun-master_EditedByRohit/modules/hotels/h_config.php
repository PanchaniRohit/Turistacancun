<?php

######################################################################
# Modulo de Reservaciones Turista: Reservación de Hoteles en Línea
# ===========================
#
# Copyright (c) 2004 by Enrique Montes (emontes@dstr.net)
# http://www.turista.com.mx
#
# Este modulo es para configurar las opciones de Reservaciones
#
######################################################################

######################################################################
# Preferencias para el módulo de reservaciones
#
# $urlfotos:      	    	Dirección de donde se tomarán las fotos ej http://www.turista.com.mx 
# $difotos:					Diretorio de donde se toman las fotos ej."/home/turist2/public_html/quintanaroo";
# $nombreestado:   			Nombre del Estado ej. chiapas, mexico
######################################################################
global $dbhost;
$urlgaleriafotos="http://www.cancunpictures.net";
$dirgaleriafotos="/vol/www/cunpic01/";
$titulopaginaenglish = "Cancun Hotels";
$titulopaginaspanish = "Hoteles en Cancun";
$sloganauxprincipalenglish = ", . Online reservations and information";
$sloganauxprincipalspanish = ", . Reservaciones en línea e información de tarifas, servicios y cuartos";
$urlfotos = "http://www.turista.com.mx/quintanaroo/modules/hoteles/images/fotos";
$dirfotos="/home/turist2/public_html/quintanaroo/modules/hoteles/images/fotos";
$nombreestado="Cancun";
$prefixhot = "hot";  //Prefijo para la base de hoteles
$vistadefault = 5; //Vista por default cuando se ingresa a hoteles (5 es palenque)
$tithotzonesenglish = "Mexican Caribbean Hotels";
$tithotzonesspahish = "Hoteles en el Caribe Mexicano";
$cuantos=20; // cuántos hoteles va a mostrar por página
$numtop=5; // Número de hoteles a desplegar en el top
$topcomolinks = true; // para que despliegue los nombre de hoteles en el top como liniks o como texto solamente
$urlgaleria="http://t-cancun.com";
$editahoteluser=false; //para que el usuario pueda o no editar el hotel (para evitar que se hagan ediciones en un dominio secundario)
$largoimagen=60; //largo de la imagen para listado general de hoteles
$longtexto=230; //longitud del texto en caracteres para el listado general
$largoimagenbooking=80; //largo de la imagen para listado general de hoteles
$longtextobooking=226; //longitud del texto en caracteres para el listado general
$despliegamhome=true;  //Para indicar que despliegue amenities en el listado de hoteles
$altoimagen1hotel=170; //De qué alto es la imagen de portada (la 1) en la página del hotel
$anchofotoshotel=180; //De qué ancho son las fotos en la página del hotel
$muestraidagencia=true; // Para que no muestre el id en agencia de viajes externa
$colorfondotablacalendario="#FFFFff";
$colorfondotablabookit="#FFFF99";
$colorfondotitulocalendario="#006699";
$colorfondodiascalendario="#D1E9E4";
$colortituloscalendario="#FFFF00";
// Definición de estilos para calendario
$estiloscalendario = "<style type=\"text/css\">
.titulosdiascalendario {font-family: Arial, Helvetica, sans-serif;font-size: 8px;color: #ffff00;}
.preciosencalendario {font-family: Arial, Helvetica, sans-serif;font-size: 9px;color: #2E859E;}
.preciopromedio {font-family: Verdana, Helvetica; FONT-WEIGHT: bold; font-size: 13px;color: #2E859E;}
</style>";
?>
