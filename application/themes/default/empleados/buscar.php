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
	<h1>Busca Empleado!</h1>
	<div id="body">
		<p><div style="text-align: center">Listado de empleados en el Sistema.</p></div>
		
		{datos}
			<form action="save/{id}" method="post">
		<!--{form_open("empleados/save")}-->
			<label>ID</label>
				<input id="id" name="id" value="{id}"/>
			<label>Nombre</label>
				<input id="nombre" name="nombre" value="{nombre}"/>
			<label>Apellido</label>
				<input id="Apellido" name="Apellido" value="{Apellido}"/>	
			<label>Edad</label>
				<input id="edad" name="edad" value ="{edad}"/>
			<label>EstadoCivil</label>
				<input id="estadocivil" name="estadocivil" value="{estadocivil}"/>
			<label>Cedula</label>
				<input id="cedula" name="cedula" value="{cedula}"/>
			<label>Dirección</label>
				<input id="direccion" name="direccion" value="{direccion}"/>
			<label>Fecha</label>
				<input name="fecha" value ="{fecha}"/>
				<button type="submit">Guardar</button>
		</form>
		{/datos}

		<p>El cálculo realizado es {resultado}</p>
		<form method="post" action="<?php echo site_url('empleados')?>/buscar_vista">
			<label>Id:</label>
			<input name="q"/>
		</form>
	
</body>
</html>
<html>
<body>
	<p>
		<a href="<?php echo site_url("empleados")?>">Ingresar otro empleado</a>
	</p>
</body>
</html>
<html>
<body>
	<p>
		<a href="<?php echo site_url("menu")?>">Página de Inicio</a>
	</p>
</body>
</html>