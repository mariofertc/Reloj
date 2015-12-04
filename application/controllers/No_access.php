<?php
class No_access extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index($module_id='')
	{
		$data['module_name']=$this->Module_model->get_module_name($module_id);
		$this->twiggy->set($data,null);
		$this->twiggy->display('no_access');
	}
}