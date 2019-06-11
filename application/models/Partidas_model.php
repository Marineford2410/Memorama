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

    public function is_playing(){
        $this->db->from('partidas');
        $this->db->where('status', 1);
        $query = $this->db->get();

        if (!empty($query->row_array())) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    public function end_partida($data)
    {
        $this->db->where('status', 1);
        return $this->db->update('partidas', $data);
    }

}

?>