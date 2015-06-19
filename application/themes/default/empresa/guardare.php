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
		<p>Ingrese los datos de la Empresa.</p>
		{titulo}
		<form action="empresa/save/0" method="post">
		<!--{form_open("empleados/save")}-->
		<label>ID: </label>
				<input nombre="ide" name="ide"/></th></th></p>
			<label>Nombre: </label>
				<input nombre="nombree" name="nombree"/></th></th></p>
			<label>Dirección: </label>
				<input direccione="direccione" name="direccione"/></th></th></p>
			<label>Teléfono: </label>
				<input telefonoe="telefonoe" name="telefonoe"/></th></th></p>
			<label>Tipo: </label>
							<select name="tipo">
							<option value="Privada">Pública</option>
							<option value="Pública">Privada</option>
							</select>  </th></th></p>
 			<label>RUC: </label>
				<input ruc="ruc" name="ruc"/></th></th></p>
			<button type="submit">Guardar</button>
			
		</form>
<a href="<?php echo site_url('empresa');?>/listar">Listar34</a>
</body>
</html>