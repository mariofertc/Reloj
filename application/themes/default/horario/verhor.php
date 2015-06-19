<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Reloj | Horario</title>
</head>
<body>
<div id="container">
	<h1>Lista Horarios</h1>
	<div id="body">
		<p>Listado de horarios en el Sistema.</p>
		<table>
			<thead>
				<th>Codigo</th></th>
				<th>Picada</th></th>
				<th>Tipo</th></th>
				<th>Hentrada</th></th>
				<th>HSalida</th></th>
				<th>HAlmuerzo</th></th>
				<th>EAlmuerzo</th></th>
				<th>Hentra1</th></th>
				<th>Hsal1</th></th>
			</thead>
		{datos}
			<tr>
				<th>{codigo}</th></th>
				<td>{picada}</td></td>
				<td>{tipo}</td></td>
				<td> {hentrada}</td></td>
				<td> {hsalida}</td></td>
				<td> {hent}</td></td>
				<td> {hsal}</td></td>
				<td> {halm}</td></td>
				<td> {hentalm}</td></td>
			</tr>
		{/datos}
		</table>
		<a href="<?php echo site_url('horario')?>/buscar_vista">Buscar</a>
</body>
</html>
<html>
<body>
	<p>
		<a href="<?php echo site_url("horario")?>">Ingresar otro horario</a>
	</p>
</body>
</html>