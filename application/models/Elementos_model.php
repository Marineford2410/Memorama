<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Elementos_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_elements_of_tematica_id($id)
    {
        $this->db->select('id, nombre');
        $this->db->from('elementos');
        $this->db->where('tematica_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

}

?>