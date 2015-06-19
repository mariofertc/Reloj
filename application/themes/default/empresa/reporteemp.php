<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Reloj | Empresa</title>
</head>
<body>
<div id="container">
	<h1>Datos Empresa!</h1>
	<div id="body">
		<table>
			<thead>
				<th>ID</th></th>
				<th>Nombre</th></th>
				<th>Dirección</th></th>
				<th>Telefono</th></th>
				<th>Tipo</th></th>
				<th>Ruc</th></th>
			</thead>
		{datos}
			<tr>
				<th>{ide}</th></th>
				<th>    {nombree}</th></th>
				<td>    {direccione}</td></td>
				<td>   {telefonoe}</td></td>
				<td> {tipo}</td></td>
				<td> {ruc}</td></td>
			</tr>
		{/datos}
		</table>
		<a href="<?php echo site_url('empresa')?>/buscar_vista">Buscar</a>
	

</body>
</html>
<html>
<body>
	<p>
		<a href="<?php echo site_url("empresa")?>"></a>
	</p>
</body>
</html>