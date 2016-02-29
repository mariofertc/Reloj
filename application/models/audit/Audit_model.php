<?php

/**
 * Permite realizar operaciones CRUD sobre la tabla "auditoria"
 */
class Audit_Model extends CI_Model {

	public function create($options_echappees_audit, $options_non_echappees_audit){
            //var_dump($options_echappees_audit);
            //var_dump($options_non_echappees_audit);
            
                return $this->db->insert('audit',  array_merge($options_echappees_audit,$options_non_echappees_audit));
                }
}