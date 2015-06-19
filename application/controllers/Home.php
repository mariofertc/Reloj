<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        $data['controller_name'] = "home";
        $data['title'] = "Menú Reloj";

        $this->twiggy->set($data);
        //$this->twiggy->template('front_end/home')->display();
        $this->twiggy->display('front_end/home');

        //$this->parser->parse('_layout/header', $data, true);
        //$this->parser->parse('home', $data, true);
    }
}