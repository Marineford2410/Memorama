<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tematicas_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $query = $this->db->get('tematicas');
        return $query->result_array();
    }

}

?>