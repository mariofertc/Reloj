<?php

//*****************************************************************************//
//*****************************Empleados***************************************//
//*****************************************************************************//


/*
  Gets the html table to manage incidencias.
 */
function get_empleado_admin_table() {
    $table = '<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover dataTable no-footer" id="sortable_table">
		<thead>
			<tr>
				<th width="3%"><input type="checkbox" id="select_all" /></th>
				<th width="10%">Nombre</th>
				<th width="10%">Apellido</th>
				<th width="30%">Edad</th>
				<th width="10%">Cédula</th>
				<th width="10%">Direccion</th>
				<th width="10%">Fecha Ingreso</th>
				<th width="10%">Acciones</th>
			</tr>
		</thead>
		<tbody>
	<!--Esto se llena con  ajax cloro -->	
		</tbody>
		<tfoot>
			
		</tfoot>
	</table>';
    return $table;
}

function get_empleado_data_row($data, $controller) {
    $CI = & get_instance();
    $width = $controller->get_form_width();
    $controller_name = $controller->controller_name;
    
    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' id='empleado_" . $data->id . "' value='" . $data->id . "'/></td>";
    $table_data_row.='<td width="20%">' . character_limiter($data->nombre, 13) . '</td>';
    $table_data_row.='<td width="30%">' . character_limiter($data->apellido, 10) . '</td>';
    $table_data_row.='<td width="20%">' . character_limiter($data->edad, 13) . '</td>';
    $table_data_row.='<td width="20%">' . character_limiter($data->cedula, 13) . '</td>';
    $table_data_row.='<td width="20%">' . character_limiter($data->direccion, 13) . '</td>';
    $table_data_row.='<td width="30%">' . date('Y-m-d', strtotime($data->fecha_ingreso)) . '</td>';
    $table_data_row.='<td width="5%">' . anchor($controller_name . "/view/" . $data->id . "?width=600&height=430", 'Editar', array('class' => 'modal_btn', 'title' => 'Editar')) . 
             anchor($controller_name . "/vacaciones/" . $data->id . "?width=600&height=430", 'Vacaciones', array('class' => 'modal_btn', 'title' => 'Editar')) . '</td>';
    $table_data_row.='</tr>';

    return $table_data_row;
}

/**
 * Tabla de Horarios
 * @return string
 */
function get_horario_admin_table() {
    $table = '<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover dataTable no-footer" id="sortable_table">
		<thead>
			<tr>
				<th width="3%"><input type="checkbox" id="select_all" /></th>
				<th width="10%">Nombre</th>
				<th width="10%">Número de Horas</th>
				<th width="30%">Picadas</th>
				<th width="10%">Minutos Gracia</th>
				<th width="10%">Acciones</th>
			</tr>
		</thead>
		<tbody>
	<!--Esto se llena con  ajax cloro -->	
		</tbody>
		<tfoot>
			
		</tfoot>
	</table>';
    return $table;
}

function get_horario_data_row($data, $controller) {
    $CI = & get_instance();
    $width = $controller->get_form_width();
    $height = $controller->get_form_height();
    $controller_name = $controller->controller_name;
    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' id='horario_" . $data->id . "' value='" . $data->id . "'/></td>";
    $table_data_row.='<td width="20%">' . character_limiter($data->nombre, 13) . '</td>';
    $table_data_row.='<td width="30%">' . character_limiter($data->numero_horas, 10) . '</td>';
    $table_data_row.='<td width="20%">' . character_limiter($data->picadas, 13) . '</td>';
    $table_data_row.='<td width="30%">' . $data->minuto_gracia . '</td>';
    $table_data_row.='<td width="5%">' . anchor($controller_name . "/view/" . $data->id . "?width=" . $width . "&height=" . $height, 'Editar', array('class' => 'modal_btn', 'title' => 'Editar')) . '</td>';
    $table_data_row.='</tr>';

    return $table_data_row;
}

/**
 * Tabla de Permisos
 * @return string
 */
function get_permiso_admin_table() {
    $table = '<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover dataTable no-footer" id="sortable_table">
		<thead>
			<tr>
				<th width="3%"><input type="checkbox" id="select_all" /></th>
				<th width="20%">Nombre</th>
				<th width="20%">Tipo Permiso</th>
				<th width="20%">Fecha Creación</th>
				<th width="10%">Acciones</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		<tfoot>
			
		</tfoot>
	</table>';
    return $table;
}

function get_permiso_data_row($data, $controller) {
    $CI = & get_instance();
    $width = $controller->get_form_width();
    $height = $controller->get_form_height();
    $controller_name = $controller->controller_name;
    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' id='horario_" . $data->id . "' value='" . $data->id . "'/></td>";
    $table_data_row.='<td width="20%">' . character_limiter($data->nombre, 25) . '</td>';
    $table_data_row.='<td width="30%">' . character_limiter($data->tipo_permiso, 15) . '</td>';
    $table_data_row.='<td width="30%">' . date('Y-m-d', strtotime($data->fecha_actualizacion)) . '</td>';
    $table_data_row.='<td width="5%">' . anchor($controller_name . "/view/" . $data->id . "?width=" . $width . "&height=" . $height, 'Editar', array('class' => 'modal_btn', 'title' => 'Editar')) . '</td>';
    $table_data_row.='</tr>';

    return $table_data_row;
}