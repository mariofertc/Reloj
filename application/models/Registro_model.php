<?php 
/**
 * @deprecated since version 1.0.0
 */
 class Registro_model extends CI_Model{

	public function leer_datos($path){
		//return $string = fgetcsv($path);
		if (($handle = fopen($path, "r")) !== FALSE) {
    		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		        $num = count($data);
		        echo "<p> $num fields in line $row: <br /></p>\n";
		        $row++;
		        for ($c=0; $c < $num; $c++) {
	            	echo $data[$c] . "<br />\n";
	        	}
       		}
    		fclose($handle);
		}
	}
}