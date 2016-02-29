<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Track {

    var $CI;

    public function __construct() {
        //parent::__construct();
        //    Chargement des ressources pour tout le contrôleur
        $this->CI = &get_instance();
        //$this->CI->load->model('procesos/Audit_model', 'AuditManager');
        $this->CI->load->model('audit/Audit_model', 'AuditManager');
    }

    public function track_user() {
        // Start off with the session stuff we know
        $options_echappees_audit = array();
        $options_non_echappees_audit = array();

        // getting the agent
        
        $options_echappees_audit['agentId'] = $this->CI->session->userdata('person_id');
//        $options_echappees_audit['agentId'] = $this->CI->agent->browser();
        //var_dump($this->CI->session->userdata());
        $user_info = $this->CI->session->userdata('user_info');
        
        if ($user_info) {
            $options_echappees_audit['nombre'] = $user_info->nombre;
            //$options_echappees_audit['apellido'] = $user_info->BPERS_APELLIDO_PATER;
        }else{
            $options_echappees_audit['agentId'] = $this->CI->session->userdata('identity');
            $options_echappees_audit['nombre'] = $this->CI->session->userdata('username');            
        }

        // getting the client IP address (we first asks whether it is behind a proxy)
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $options_echappees_audit['adresseIp'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $options_echappees_audit['adresseIp'] = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $options_echappees_audit['adresseIp'] = $_SERVER['REMOTE_ADDR'];
        }
        //getting client's domain
        $options_echappees_audit['domaine'] = gethostbyaddr($options_echappees_audit['adresseIp']);


        $options_echappees_audit['navigator'] = $_SERVER['HTTP_USER_AGENT'];

        // REFERER
        if (isset($_SERVER['HTTP_REFERER'])) {
            //if (eregi($_SERVER['HTTP_HOST'], $_SERVER['HTTP_REFERER'])) {
            if (preg_match("/" . $_SERVER['HTTP_HOST'] . "/i", $_SERVER['HTTP_REFERER'])) {
                $options_echappees_audit['referer'] = '';
            } else {
                $options_echappees_audit['referer'] = $_SERVER['HTTP_REFERER'];
            }
        } else {
            $options_echappees_audit['referer'] = '';
        }

        // active page 
        if ($_SERVER['QUERY_STRING'] == "") {
            $options_echappees_audit['pageCourant'] = $_SERVER['PHP_SELF'];
        } else {
            $options_echappees_audit['pageCourant'] = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        }

        // Next up, we want to know what page we're on, use the router class
        $options_echappees_audit['controller'] = $this->CI->router->class;
        $options_echappees_audit['function'] = $this->CI->router->method;


        // We don't need it, but we'll log the URI just in case
        $options_echappees_audit['url'] = uri_string();

        // And write it to the database
        $this->CI->AuditManager->create($options_echappees_audit, $options_non_echappees_audit);
    }

}
