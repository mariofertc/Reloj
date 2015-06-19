<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Reloj | Empleados</title>
</head>
<body>
<div id="container">
	<h1>Lista Empleado!</h1>
	<div id="body">
		<p>Listado de empleados en el Sistema.</p>
		<table>
			<thead>
				<th>ID</th></th>
				<th>Nombre</th></th>
				<th>Apellido</th></th>
				<th>Edad</th></th>
				<th>Cedula</th></th>
				<th>Estadocivil</th></th>
				<th>Dirección</th></th>
				<th>Fecha</th></th>
				<th>Fecha Ingreso</th></th>
			</thead>
		{datos}
			<tr>
				<th>{id}</th></th>
				<td>{nombre}</td></td>
				<td>{Apellido}</td></td>
				<td> {edad}</td></td>
				<td> {cedula}</td></td>
				<td> {estadocivil}</td></td>
				<td> {direccion}</td></td>
				<td> {fecha}</td></td>
				<td> {fecha_ingreso}</td></td>
			</tr>
		{/datos}
		</table>
		<a href="<?php echo site_url('empleados')?>/buscar_vista">Buscar</a>
</body>
</html>
<html>
<body>
	<p>
		<a href="<?php echo site_url("empleados")?>">Ingresar otro empleado</a>
	</p>
</body>
</html>