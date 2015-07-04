<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Reloj | Departamento</title>
</head>
<body>

<div id="container">
	<h1>Inserta Departamento!</h1>

	<div id="body">
		<p>Insertar Departamentos en el Sistema.</p>
		{titulo}
		<form action="departamento/save" method="post">
		<!--{form_open("empleados/save")}-->
			<label>IDEEM: </label>
				<input ideem="ideem" name="ideem"/>
			<label>IDDEP: </label>
				<input iddep="iddep" name="iddep"/>
			<label>Departamento: </label>
				<input departamento="departamento" name="departamento"/>
			
	<button type="submit">Guardar</button>
			
		</form>
<a href="<?php echo site_url('departamento');?>/listar">Listar</a>
	

</body>
</html>
			