<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Reloj | seccion</title>
</head>
<body>
<div id="container">
	<h1>Lista secciones!</h1>
	<div id="body">
		<p>Listado de secciones en el Sistema.</p>
		<table>
			<thead>
				<th>Idsec</th></th>
				<th>Iddep</th></th>
				<th>Seccion</th></th>
				
			</thead>
		{datos}
			<tr>
				<th>{idsec}</th></th>
				<td>{iddep1}</td></td>
				<td>{seccion}</td></td>
				
			</tr>
		{/datos}
		</table>
		<a href="<?php echo site_url('seccion')?>/buscar_vista">Buscar</a>
</body>
</html>
<html>
</html>
<html>
<body>
	<p>
		<a href="<?php echo site_url("seccion")?>">Ingresar otra seccion</a>
	</p>
</body>
</html>