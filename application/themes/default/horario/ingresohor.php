<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Reloj | Horario</title>
</head>
<body>

<div codigo="container">
	<h1>Ingresar el Horario!</h1>
		<form action="horario/save/0" method="post">
		<!--{form_open("hentradaleados/save")}-->
			<label>codigo: </label>
				<input codigo="codigo" name="codigo"/></th></th></p>
			<label>picada: </label>
				<select picada="picada">
							<option value='2'>2</option>
							<option value='4'>4</option>
							<option value='6'>6</option>
							</select>  </th></th></p>
			<label>Tipo: </label>
				<input tipo="tipo" tipo="tipo" /></th></th></p>
			<label>HoraEntrada: </label>
				<input hentrada="hentrada" hentrada="hentrada"/></th></th></p>
			<label>HoraSalida: </label>
				<input hsalida="hsalida" hsalida="hsalida"/></th></th></p>
			<label>HoraAlmuerzo: </label>
				<input hent="hent" hent="hent"/></th></th></p>
			<label>SalidaAlmuerzo: </label>
				<input hsal="hsal" hsal="hsal"/></th></th></p>
			<label>Entrada1: </label>
				<input halm="halm" halm="halm"/></th></th></p>
			<label>Salida1: </label>
			<input hentalm="hentalm" hentalm="hentalm"/></th></th></p>
			<button type="submit">Guardar</button>
			
		</form>
<a href="<?php echo site_url('horario');?>/listar">Listar</a>
</body>
</html>