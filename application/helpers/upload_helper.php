<?php
/**
 * Facilita subir los archivos al servidor.
 * @param string $path
 * @param string $fileFormat
 * @param string $rename
 * @return array
 */
function do_upload($path = '', $fileFormat = '*',$rename='') {
	$CI = & get_instance();
	$config['upload_path'] = './uploads/' . $path;
	if(!empty($rename))
		$config['file_name']=$rename;
	$config['allowed_types'] = $fileFormat;
	$config['max_size'] = '102400';
	$CI->load->library('upload', $config);

	if (!$CI->upload->do_upload('userfile')) 
		return array('error' => $CI->upload->display_errors('', ''));
        else 
		return array('error' => 0, 'upload_data' => $CI->upload->data());
	
}