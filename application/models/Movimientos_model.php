<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Movimientos_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data) {
        if ($this->db->insert('movimientos', $data)) {
            return $this->db->insert_id();
        } else {
            return NULL;
        }
    }

    public function end_movimientos($data)
    {
        $this->db->where('status', 1);
        return $this->db->update('movimientos', $data);
    }
}

?>