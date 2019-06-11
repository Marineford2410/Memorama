<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Partidas extends REST_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('partidas_model');
    }

    public function index_get($id)
    {
        $this->load->model('tematicas_model');
        $data = $this->tematicas_model->get_tematica_by_id($id);
        $this->load->view('partidas_form_view', $data);
    }


    public function index_post($id)
    {
        $cartas = $this->post('num_cartas');
        
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required|max_length[45]');
        $this->form_validation->set_rules('num_cartas', 'Numero de Cartas', 'required|integer|in_list[8,16,32]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->model('tematicas_model');
            $data = $this->tematicas_model->get_tematica_by_id($id);
            $this->load->view('partidas_form_view', $data);
        }
        else
        {
            //$this->load->view('formsuccess');
            $partida = $this->_fill_insert_data($id);
            $insertado = $this->partidas_model->insert($partida);
            echo "insertado: " . $insertado;
        }
    }

    

    private function _fill_insert_data($tematica_id) {
        $this->load->helper('date');
        $data = array();
        $data['partida'] = $this->post('username');
        $data['tematica_id'] = $tematica_id;
        $data['num_cartas'] = $this->post('num_cartas');
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:m:s');
        $data['updated_at'] = date('Y-m-d H:m:s');
        return $data;
    }

}

?>