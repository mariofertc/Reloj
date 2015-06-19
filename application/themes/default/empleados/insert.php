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
	<h1>Inserta Empleado!</h1>

	<div id="body">
		<p>Insertar empleados en el Sistema.</p>
		{titulo}
		<form action="empleados/save" method="post">
		<!--{form_open("empleados/save")}-->
			<label>ID: </label>
				<input id="id" name="id"/></th></th></p>
			<label>Nombres: </label>
				<input nombre="nombre" name="nombre"/></th></th></p>
			<label>Apellidos: </label>
				<input nombre="Apellido" name="Apellido"/></th></th></p>
			<label>Edad: </label>
				<input edad="edad" name="edad"/></th></th></p>
			<label>Cedula: </label>
				<input cedula="cedula" name="cedula"/></th></th></p>
			<label>Estado Civil: </label>
							<select name="estadocivil">
							<option value="Casado/a">Casado/a</option>
							<option value="Soltero/a">Soltero/a</option>
							<option value="Viudo/a">Viudo/a</option>
							<option value="Unión de hecho">Union de Hecho</option>
	    					</select>  </th></th></p>
	    	
 				<!--<input estadocivil="estadocivil" name="estadocivil"/></th></th></p>-->	
			<label>Dirección: </label>
				
				<input direccion="direccion" name="direccion"/></th></th></p>
			<label>Fecha: </label>
				<input fecha="fecha" name="fecha"/></th></th></p>
			
			<button type="submit">Guardar</button>
			
		</form>
<a href="<?php echo site_url('empleados');?>/listar">Listar0</a>
	

</body>
</html>