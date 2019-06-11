<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Partidas_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data) {
        if ($this->db->insert('partidas', $data)) {
            return $this->db->insert_id();
        } else {
            return NULL;
        }
    }

}

?>