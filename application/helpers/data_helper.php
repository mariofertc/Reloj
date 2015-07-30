<?php

function get_where($aColumns, $filter, $db) {
	$sWhere = "";
	$mWhere = array();
	if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
		$sWhere = '(';
		for ($i = 1; $i < count($aColumns); $i++) {
			switch ($db) {
				case "mongo":
					$mWhere[] = array($aColumns[$i] => new MongoRegex('/' . $_GET['sSearch'] . '/i'));
					break;
				case "oci":
					//$sWhere .= $aColumns[$i] . " = '%" . ( $_GET['sSearch'] ) . "%' OR ";
					//$sWhere .= "regexp_like(" . $aColumns[$i] .", '^" . $_GET['sSearch'] . "$', 'i') OR ";
					$sWhere .= "regexp_like(" . $aColumns[$i] .", '" . $_GET['sSearch'] . "', 'i') OR ";
					break;
				default:					
					$sWhere .= $aColumns[$i] . " LIKE '%" . ( $_GET['sSearch'] ) . "%' OR ";
					break;
			}
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}
	$m_where_and = array();
	/* Individual column filtering */
	for ($i = 1; $i < count($aColumns); $i++) {
		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
			switch ($db) {
				case "mongo":
					$m_where_and []= array($aColumns[$i] => new MongoRegex('/' . $_GET['sSearch'] . '/i'));
					break;
				default:
					if ($sWhere == "") {
						$sWhere = " where ";
					} else {
						$sWhere .= " AND ";
					}
					$sWhere .= $aColumns[$i] . " LIKE '%" . ($_GET['sSearch_' . $i]) . "%' ";
					break;
			}
		}
	}
	
	if(!is_null($filter)){
			switch ($db) {
			case "mongo":
				$m_where_and[]=$filter;
				break;
			default:
				$sWhere .=" and ($filter)";
			}
	}
	
	$where=array();
	if(!empty($mWhere)){
		$where=array('$or'=>$mWhere);
	}

	if(!empty($m_where_and)){
		$where=array('$and'=>$m_where_and);
	}

	if(!empty($mWhere)&&!empty($m_where_and)){
		$where=array('$and'=>$m_where_and,'$or'=>$mWhere);
	}
		
	switch ($db) {
		case "mongo":
			return $where;
		default:
			return $sWhere;
	}
}

function get_order($aColumns, $db) {
	$sOrder = "";
	$mOrder = array();
	if (isset($_GET['iSortCol_0'])) {
		$sOrder = "";
		for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				switch ($db) {
					case "mongo":
						$mOrder = array_merge($mOrder, array($aColumns[intval($_GET['iSortCol_' . $i])] => ($_GET['sSortDir_' . $i] == 'asc' ? 1 : -1)));
						break;
					default:
						$sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ( $_GET['sSortDir_' . $i] ) . ", ";
						break;
				}
			}
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
	switch ($db) {
		case "mongo":
			return $mOrder;
		default:
			return $sOrder;
	}
}

/**
 * Retorna los datos para el DataTable
 * <p>BDD Relacional</p>
 * @param CI_Model model Modelo para acceder a los datos almacenados.
 * @param array aColumns Lista de los nombres de los encabezados.
 * @param array cllAccion Lista con las acciones para Edición u otras.
 * @param string db mongo,oci,mysql.
 */
function getData($model, $aColumnas, $cllAccion = array(), $es_mas = false, $filter = null, $db = "mongo") {
	$CI = & get_instance();
	$sIndexColumn = "id";
	$controller_name = strtolower($CI->uri->segment(1));

	$aColumns = get_fields($aColumnas);
	/*
	 * Ordering
	 */
	$sOrder = get_order($aColumns, $db);

	/* Filtro de search */
	$sWhere = get_where($aColumns,$filter, $db);

	$page = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
	$offset = isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 0;
	//return json_encode($sOrder);
	$rResult = $CI->$model->get_all($offset, ($page == null ? 0 : $page), $sWhere, $sOrder);
	//$rResult = $CI->$model->get_all($offset, ($page == null ? 0 : $page), $mWhere, $mOrder);
	if(!is_null($filter))
		$total = $CI->$model->get_total_filtered($filter);
	else
		$total = $CI->$model->get_total();
	$iFilteredTotal = gettype($total) == "integer" ? $total : $total->total;
	$iTotal = count($rResult);
	
	/*$total = $CI->$model->get_total();
	$iFilteredTotal = gettype($total) == "integer" ? $total : $total->total;
	$iTotal = count($rResult);*/
	
	/*
	 * Output
	 */
	$output = array(
			"iDraw" => intval($_GET['sEcho']),
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
	);
	/*$output = array(
			"draw" => intval($_GET['sEcho']),
			"recordsTotal" => $iFilteredTotal,
			"recordsFiltered" => $iTotal,
			"aaData" => array()
	);*/

	$id = 0;
	$limit = count($cllAccion) == 0 ? count($aColumns) : count($aColumns) - 1;

	$output['aaData'] = get_data($rResult, $aColumns, $cllAccion, $es_mas);

	return json_encode($output);
}

function getDataMongo($model, $aColumns, $cllAccion = array(), $es_mas = false) {
	$CI = & get_instance();
	$controller_name = strtolower($CI->uri->segment(1));
	//echo "URI:".$CI->uri->segment(1).'-'.$CI->uri->segment(2);
	/*
	 * Ordering
	 */
	$sOrder = "";
	$mOrder = array();
	if (isset($_GET['iSortCol_0'])) {
		$sOrder = "";
		for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				$sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " .
								( $_GET['sSortDir_' . $i] ) . ", ";
				$mOrder = array_merge($mOrder, array($aColumns[intval($_GET['iSortCol_' . $i])] => ($_GET['sSortDir_' . $i] == 'asc' ? 1 : -1)));
			}
		}

		$sOrder = substr_replace($sOrder, "", -2);
	}
	//print_r( $mOrder);
	/* Filtro de search */
	$sWhere = "";
	$mWhere = array();
	if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
		$sWhere = '(';
		for ($i = 1; $i < count($aColumns); $i++) {
			$sWhere .= $aColumns[$i] . " LIKE '%" . ( $_GET['sSearch'] ) . "%' OR ";
			$mWhere = array_merge($mWhere, array($aColumns[$i] => new MongoRegex('/' . $_GET['sSearch'] . '/i')));
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}
	/* Individual column filtering, yet not implemented by MT. */
	for ($i = 1; $i < count($aColumns); $i++) {
		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
			if ($sWhere == "") {
				$sWhere = " where ";
			} else {
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i] . " LIKE '%" . ($_GET['sSearch_' . $i]) . "%' ";
		}
	}
	$page = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
	$offset = isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 0;
	//return json_encode($sOrder);
	$rResult = $CI->$model->get_all($offset, ($page == null ? 0 : $page), $mWhere, $mOrder);
	$total = $CI->$model->get_total();


	$iFilteredTotal = gettype($total) == "integer" ? $total : $total->total;

	$iTotal = count($rResult);
	/*
	 * Output
	 */
	$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
	);

	$id = 0;
	$limit = count($cllAccion) == 0 ? count($aColumns) : count($aColumns) - 1;

	$output['aaData'] = get_data($rResult, $aColumns, $cllAccion, $es_mas);


	return json_encode($output);
}

function getDataMongo_filtered($model, $aColumns, $cllAccion = array(), $es_mas = false, $filter) {
	$CI = & get_instance();
	$controller_name = strtolower($CI->uri->segment(1));
//	echo $controller_name;
	/*
	 * Ordering
	 */
	$sOrder = "";
	$mOrder = array();
	if (isset($_GET['iSortCol_0'])) {
		$sOrder = "";
		for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				$sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " .
								( $_GET['sSortDir_' . $i] ) . ", ";
				$mOrder = array_merge($mOrder, array($aColumns[intval($_GET['iSortCol_' . $i])] => ($_GET['sSortDir_' . $i] == 'asc' ? 1 : -1)));
			}
		}

		$sOrder = substr_replace($sOrder, "", -2);
	}
	//echo $sOrder;
	/* Filtro de search */
	$sWhere = "";
	$mWhere = array();
	if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
		$sWhere = '(';
		for ($i = 1; $i < count($aColumns); $i++) {
			$sWhere .= $aColumns[$i] . " LIKE '%" . ( $_GET['sSearch'] ) . "%' OR ";
			$mWhere = array_merge($mWhere, array($aColumns[$i] => new MongoRegex('/' . $_GET['sSearch'] . '/i')));
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}
	/* Individual column filtering, yet not implemented by MT. */
	for ($i = 1; $i < count($aColumns); $i++) {
		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
			if ($sWhere == "") {
				$sWhere = " where ";
			} else {
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i] . " LIKE '%" . ($_GET['sSearch_' . $i]) . "%' ";
		}
	}
	$page = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : 0;
	$offset = isset($_GET['iDisplayLength']) ? $_GET['iDisplayLength'] : 0;
	//return json_encode($sOrder);
	$rResult = $CI->$model->get_all_filtered($offset, ($page == null ? 0 : $page), $mWhere, $mOrder, $filter);
	$total = $CI->$model->get_total_filtered($filter);

	if (gettype($total) == "integer")
		$iFilteredTotal = $CI->$model->get_total_filtered($filter);
	else
		$iFilteredTotal = $CI->$model->get_total_filtered($filter)->total;

	$iTotal = count($rResult);
	/*
	 * Output
	 */
	$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
	);

	$id = 0;
	$limit = count($cllAccion) == 0 ? count($aColumns) : count($aColumns) - 1;

	$output['aaData'] = get_data_filtered($rResult, $aColumns, $cllAccion, $es_mas);


	return json_encode($output);
}

function getColumnAccion($cllAccion, $id) {
	$CI = & get_instance();
	$controller_name = strtolower($CI->uri->segment(1));
	if (count($cllAccion) == 0)
		return;
	$accion = "";
	foreach ($cllAccion as $acc) {
		//$width = ;
//	$height = ;
		//Agregado Ruta absoluta con #.
		$accion.=anchor((strpos($acc['function'], '#') !== false ? "" : $controller_name . "/") . $acc['function'] . "/$id?" . (isset($acc['width']) ? 'width=' . $acc['width'] : '') . (isset($acc['height']) ? "&height=" . $acc['height'] : ''), $CI->lang->line($acc['common_language']), array('class' => (isset($acc['modal_btn'])) ? '' : 'modal_btn', 'title' => $CI->lang->line($controller_name . $acc['language']))) . " ";
		//$accion.=anchor($controller_name . "/" . $acc['function'] . "/$id?" . (isset($acc['width']) ? 'width=' . $acc['width'] : '') . (isset($acc['height']) ? "&height=" . $acc['height'] : ''), $CI->lang->line($acc['common_language']), array('class' => (isset($acc['thickbox'])) ? '' : 'thickbox', 'title' => $CI->lang->line($controller_name . $acc['language']))) . nbs();
	}
	return $accion;
}

function getColumnAccion_filtered($cllAccion, $id) {
	$CI = & get_instance();
	$controller_name = strtolower($CI->uri->segment(1));
	if (count($cllAccion) == 0)
		return;
	$accion = "";
	foreach ($cllAccion as $acc) {
		//$width = ;
//	$height = ;
		$accion.=anchor($controller_name . "/" . $acc['function'] . "/$id?" . (isset($acc['width']) ? 'width=' . $acc['width'] : '') . (isset($acc['height']) ? "&height=" . $acc['height'] : ''), $CI->lang->line($acc['common_language']), array('class' => 'thickbox', 'title' => $CI->lang->line(strtolower($CI->uri->segment(2)) . $acc['language']))) . nbs();
	}
	return $accion;
}

/*
 * Formatea Array del Mongo
 */

function format_array($mongo_array) {
	$new_array = array("datos" => array());
	foreach ($mongo_array as $id => $val) {
		$arreglo = array('indice' => $id);
		foreach ((array) $val as $dat => $value) {
			if (gettype($value) == "object") {
				foreach ($value as $dat2 => $value2)
					if (isset($value2->sec)) {
//						$arreglo += array('fecha' => gmdate('M d Y H:i:s', $value2->sec));
//						$arreglo += array('fecha' => date('M d Y H:i:s', $value2->sec));
//						$arreglo += array('fecha' => date('Y-M-d H:i:s.u', $value2->sec));
						$date = date('Y-M-d H:i:s.u', $value2->sec);
						$milliseconds = $value2->usec;

						$arreglo += array('fecha' => date(preg_replace('`(?<!\\\\)u`', $milliseconds, 'Y-M-d H:i:s.u'), $value2->sec));
						//var_dump($value2);
					} else {
						$arreglo += array($dat2 => gettype($value2) == "object" ? (array) $value2 : $value2);
						if ($dat == "_id")
							$arreglo += array('id' => $value2);
					}
			}
			else
				$arreglo += array($dat => gettype($value) == "object" ? (array) $value : $value);
		}
		$new_array['datos'][] = $arreglo;
//		$new_array['datos'] += array($arreglo);
	}
//	die(var_dump($new_array['datos']));
	return $new_array;
}

function export_to_csv($json_data, $file_name = null) {
//	$file = file_get_contents('http://example.com/blah/blah');
	$file_name = $file_name == null ? "file.csv" : $file_name;
	$csvfile = fopen($file_name, 'w+');
//	$cabecera = array("año", "mes", "dia", "variable", "valor", "muestras");
	if (isset($json_data[0]))
		$cabecera = array_keys($json_data[0]);
	else
		$cabecera = array_keys($json_data);
	//Cambia _id por fecha
	if (in_array("_id", $cabecera))
		$cabecera[array_search("_id", $cabecera)] = "fecha";
//	var_dump($json_data);
	fputcsv($csvfile, $cabecera);
	$line = array();
	disect($json_data, $csvfile, $line, $cabecera);
	fclose($csvfile);
	force_dowload($file_name);
}

function force_dowload($path = null) {
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false); // required for certain browsers 
//	header("Content-Type: txt");

	header("Content-Description: File Transfer");
	header("Content-Type: application/octet-stream");

// change, added quotes to allow spaces in filenames, by Rajkumar Singh
	header("Content-Disposition: attachment; filename=\"" . basename($path) . "\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: " . filesize($path));
	readfile($path);
	exit();
}

function disect($json, &$csvfile, &$line, $cabecera) {
	foreach ($json as $row => $value) {
		if (gettype($value) == "array") {
			if (current($value)) {
				$dato = get_deep($value);
				$line[] = $dato;
				//Quitamos el deep ya adherido.
				unset($value[key($value)]);
				disect($value, $csvfile, $line, $cabecera);
			}
			else
				disect($value, $csvfile, $line, $cabecera);
		}
		else
			$line[] = $value;
		if (count($line) == count($cabecera)) {
//			echo implode($line, ",") . "<br>";
			fputcsv($csvfile, $line);
			$line = array();
		}
	}
}

/**
 * Generalizado para Fechas
 * @param type $data
 * @return type
 */
function get_deep($data) {
	if (gettype(current($data)) == "array")
		return get_deep(current($data));
	$dato = "";
	foreach ($data as $val) {
		$dato .= $val . ($val === end($data) ? "" : "-");
	}
	return $dato;
}

function get_data_mongo($mongo_result, &$key) {
	foreach ($mongo_result as $key_find => $value) {
		if ($key_find === $key)
			return $value;
		if (gettype($value) == "array") {
			return get_data_mongo($value, $key);
		}
	}
	return null;
}

function get_value($row, $column_key, $id = null) {
	if (gettype($column_key) == 'array') {
		$value = $row[$column_key[0]];
		$object = $column_key[1];
		$tag = sprintf($object[1], $id);
		//tag - styles
		return '<' . $object[0] . ' ' . $tag . '>' . $value . '</' . $object[0] . '>';
	} elseif (array_key_exists($column_key, $row)) {
		$dato = $row[$column_key];
		if (gettype($dato) == "object") {
			if (get_class($dato) == 'MongoDate')
				return date('Y-M-d H:i:s.u', $dato->sec);
			if (get_class($dato) == 'MongoId')
				return (string) $dato;
		}

		return $dato;
	}
	else
		return "-";
}

//function has_deep($datos){
//	if (gettype($row) == "array")
//		has_deep($row);
//}

function get_data($norma, $aColumns, $cllAccion, $es_mas = false) {
	$resp = array();
	$id = "";
	foreach ($norma as $aRow) {
		$row = array();
		for ($i = 0; $i < count($aColumns); $i++) {
//			echo gettype($aColumns[$i]);
			if (gettype($aColumns[$i]) == "string") {
				if ($i == 0) {
					// $id = mb_strtolower($aRow[$aColumns[$i]]);
					$id = $aRow[$aColumns[$i]];
					$row[] = (!$es_mas) ? "<input type='checkbox' id='empleado_$id' value='" . $id . "' title='Id=$id'/>" : '<img src="' . asset_url() . '/images/table/add.png">';
				} else if ($aColumns[$i] == "email") {
					$row[] = mailto($aRow['email'], character_limiter($aRow['email'], 10));
				} else if ($aColumns[$i] != ' ') {
					/* General output */
					$row[] = get_value($aRow, $aColumns[$i]);
				}
			} else {
				$row[] = get_value($aRow, $aColumns[$i], $id);
			}
		}

		$acciones = getColumnAccion($cllAccion, $id);
		if (count($cllAccion))
			$row[] = $acciones;

		$resp[] = $row;
	}
	return $resp;
}

function get_data_old($norma, $aColumns, $cllAccion, $es_mas = false) {
	$resp = array();
	foreach ($norma as $aRow) {
		$row = array();
		for ($i = 0; $i < count($aColumns); $i++) {
			if ($i == 0) {
				$id = mb_strtolower($aRow[$aColumns[$i]]);
				$row[] = (!$es_mas) ? "<input type='checkbox' id='empleado_$id' value='" . $id . "'/>" : '<img src="' . asset_url() . '/images/table/add.png">';
			} else if ($aColumns[$i] == "email") {
				$row[] = mailto($aRow['email'], character_limiter($aRow['email'], 10));
			} else if ($aColumns[$i] != ' ') {
				/* General output */
				$row[] = get_value($aRow, $aColumns[$i]);
			}
		}

		$acciones = getColumnAccion($cllAccion, $id);
		if (count($cllAccion))
			$row[] = $acciones;

		$resp[] = $row;
	}
	return $resp;
}

function get_data_filtered($norma, $aColumns, $cllAccion, $es_mas = false) {
	$resp = array();
	foreach ($norma as $aRow) {
		$row = array();
		for ($i = 0; $i < count($aColumns); $i++) {
			if ($i == 0) {
				$id = mb_strtolower($aRow[$aColumns[$i]]);
				$row[] = (!$es_mas) ? "<input type='checkbox' id='empleado_$id' value='" . $id . "'/>" : '<img src="../images/ico/add.png">';
			} else if ($aColumns[$i] == "email") {
				$row[] = mailto($aRow['email'], character_limiter($aRow['email'], 10));
			} else if ($aColumns[$i] != ' ') {
				/* General output */
				$row[] = get_value($aRow, $aColumns[$i]);
			}
		}

		$acciones = getColumnAccion_filtered($cllAccion, $id);
		if (count($cllAccion))
			$row[] = $acciones;

		$resp[] = $row;
	}
	return $resp;
}

function format_data($norma, $aColumns, $cllAccion, $es_mas = false) {
	$resp = get_data($norma, $aColumns, $cllAccion, $es_mas);
	$html = "";
	foreach ($resp as $row) {
		$html.="<tr>";
		foreach ($row as $column) {
			$html .= "<td>" . $column . "</td>";
		}
		$html.="</tr>";
	}
	return $html;
}

function get_fields($aColumnas = null) {
	$columnas = array();
	foreach ($aColumnas as $columna){
		$columnas[] = gettype($columna) == 'array' ? $columna[0] : $columna;
	}
	return $columnas;
}