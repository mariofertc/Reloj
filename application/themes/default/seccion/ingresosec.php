<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Reloj | Seccion</title>
</head>
<body>

<div id="container">
	<h1>Inserta seccion!</h1>

	<div id="body">
		<p>Insertar secciones en el Sistema.</p>
		{titulo}
		<form action="seccion/save/0" method="post">
		<!--{form_open("empleados/save")}-->
			<label>idsec: </label>
				<input idsec="idsec" name="idsec"/></th></th></p>
			<label>iddep1: </label>
				<input iddepp="iddepp" name="iddepp"/></th></th></p>
			<label>seccion: </label>
				<input seccion="seccion" name="seccion"/></th></th></p>
			
	<button type="submit">Guardar</button>
			
		</form>

<a href="<?php echo site_url('seccion');?>/listar">Listar</a>
	

</body>
</html>
			