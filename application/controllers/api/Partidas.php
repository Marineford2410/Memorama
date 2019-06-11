<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Partidas extends REST_Controller { 
    
    public $cartas;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->model('partidas_model');
        $this->load->model('movimientos_model');
    }
    
    public function index_get($id)
    {
        $this->load->model('tematicas_model');
        $data = $this->tematicas_model->get_tematica_by_id($id);
        $this->load->view('partidas_form_view', $data);
    }
    
    private function _fill_insert_data_partida($tematica_id) {
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
    
    private function _fill_insert_data_movimietos($movimiento, $carta, $partida_id) {
        $this->load->helper('date');
        $data = array();
        $data['movimiento'] = $movimiento;
        $data['carta'] = $carta;
        $data['partida_id'] = $partida_id;
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:m:s');
        $data['updated_at'] = date('Y-m-d H:m:s');
        return $data;
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
            if (!$this->partidas_model->is_playing()) {
                $partida = $this->_fill_insert_data_partida($id);
                $insertado = $this->partidas_model->insert($partida);

                if ($insertado) {
                    $this->_generar_baraja($partida['num_cartas'], $partida['tematica_id']);
                    $data['tematica_id'] = $id;
                    $data['cartas'] = $this->cartas;
                    $data['click1'] = NULL;
                    $data['click2'] = NULL;
                    $this->load->view('partida_view', $data);
                }
            } else {
                echo 'Hay una partida iniciada <br>';
            }
        }
    }

    private function _guardar_movimineto($mov, $clck)
    {
        $movimiento = $this->_fill_insert_data_movimietos(
            $mov,
            $this->session->userdata('cartas')[($clck - 1)],
            $this->partidas_model->is_playing()['id']
        );
        
        $this->movimientos_model->insert($movimiento);
    }

    public function juego_post()
    {
        if (!empty($this->session->userdata('cartas'))) {
            if (!isset($_SESSION['click'])) {
                $click1 = $this->post('carta');
                $this->session->set_flashdata('click', $click1);
                $data['tematica_id'] = $this->session->userdata('tematica');
                $data['cartas'] = $this->session->userdata('cartas');
                $data['click1'] = array_key_first($click1) + 1;
                $data['click2'] = NULL;

                $this->_guardar_movimineto(1,  $data['click1']);

                $this->load->view('partida_view', $data);
            }
            else {
                $click2 = $this->post('carta');
                $data['tematica_id'] = $this->session->userdata('tematica');
                $data['cartas'] = $this->session->userdata('cartas');
                $data['click1'] = array_key_first($_SESSION['click']) + 1;
                $data['click2'] = array_key_first($click2) + 1;

                $this->_guardar_movimineto(1,  $data['click2']);
            
                $nueva_baraja = $this->session->userdata('cartas');
                
                $valor1 = $data['click1'] - 1;
                $valor2 = $data['click2'] - 1;
                
                
                if ($nueva_baraja[$valor1] == $nueva_baraja[$valor2]) {
                    $this->_guardar_movimineto(2,  $data['click1']);
                    $s_nuevo = $this->remover($nueva_baraja[$valor1], $nueva_baraja);
                    $s_baraja = array(
                        'cartas' => $s_nuevo,
                        'tematica' => $data['tematica_id']
                    );
                    $this->session->set_userdata($s_baraja);
                }
    
                $this->load->view('partida_view', $data);
            }
        }
        else {
            $this->terminar_juego_post();
        }

        
    }

    function remover ($valor,$arr)
    {
        foreach (array_keys($arr, $valor) as $key) 
        {
            unset($arr[$key]);
        }
        return $arr;
    }

    public function terminar_juego_post(){
        $data['status'] = -1;
        $data['updated_at'] = date('Y-m-d H:m:s');
        
        if (($this->partidas_model->end_partida($data) && ($this->movimientos_model->end_movimientos($data)))) {
            $this->session->sess_destroy();
            $this->load->view('finalizar_juego_view');            
        } 
        else {
            $data['heading'] = '304';
            $data['message'] = 'No se pudo terminar partida';
            $this->load->view('errors/html/error_db', $data);
        }
        


    }

    private function _generar_baraja($num_cartas, $tematica)
    {
        $this->load->model('elementos_model');
        $elementos = $this->elementos_model->get_elements_of_tematica_id($tematica);
        $baraja = array();
        foreach ($elementos as $key => $elemento) {
            array_push($baraja, $elemento['id']);
        }
        $aux = array_rand($baraja, (32 - $num_cartas));
        
        foreach ($baraja as $key => $value) {
            foreach ($aux as $indice => $valor) {
                if ($key == $valor) {
                    unset($baraja[$key]);
                }
            }
        }
        $this->cartas = array_values($baraja);
        $this->cartas = array_merge($this->cartas, $this->cartas);
        shuffle($this->cartas);

        $s_baraja = array(
            'cartas' => $this->cartas,
            'tematica' => $tematica
        );
        
        $this->session->set_userdata($s_baraja);
    }


}

?>