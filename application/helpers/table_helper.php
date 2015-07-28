<?php
//*****************************************************************************//
//***************************INCIDENCIAS***************************************//
//*****************************************************************************//


/*
Gets the html table to manage incidencias.
*/
function get_empleado_admin_table()
{
	$table='<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover dataTable no-footer" id="sortable_table">
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

function get_empleadoa_data_row($data,$controller)
{	
	$CI =& get_instance();
	$width = $controller->get_form_width();

	$table_data_row='<tr>';
	$table_data_row.="<td width='5%'><input type='checkbox' id='incidencia_".$data['id']."' value='".$data['_id']."'/></td>";
	$table_data_row.='<td width="20%">'.character_limiter($data['nombre'],13).'</td>';
	$table_data_row.='<td width="30%">'.mailto($data['email'],character_limiter($data['email'],10)).'</td>';
	$table_data_row.='<td width="20%">'.character_limiter($data['detalle'],13).'</td>';	
	$table_data_row.='<td width="20%">'.character_limiter($data['tipo'],13).'</td>';		
	$table_data_row.='<td width="30%">'.date('Y-m-d', strtotime($data['fecha'])).'</td>';		
	//$table_data_row.='<td width="20%">'.date('Y-m-d', strtotime($incidencia->fechaAtencion)).'</td>';		
	//$table_data_row.='<td width="20%">'.character_limiter($incidencia->ipAddress,13).'</td>';		
	$table_data_row.='<td width="20%">'.character_limiter($data['estado'],13).'</td>';		
	$table_data_row.='<td width="20%">'.character_limiter($data['atendidoPor'],13).'</td>';		
	//$table_data_row.='<td width="20%">'.character_limiter($incidencia->comentarios,13).'</td>';		
	$table_data_row.='<td width="5%">'.anchor("empleados/view/".$data['_id']."?width=600&height=430", 'Editar',array('class'=>'thickbox','title'=>'Editar')).'</td>';		
	$table_data_row.='</tr>';
	
	return $table_data_row;
}