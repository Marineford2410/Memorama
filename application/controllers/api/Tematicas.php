<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Tematicas extends REST_Controller { 

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tematicas_model');
    }

    public function get_all_get()
    {
        $data['tematicas'] = $this->tematicas_model->get_all();
        // print_r($data);

        if ($data) {
            // $this->response($data, REST_Controller::HTTP_OK); 
            $this->load->view('tematicas_view', $data);

        } else {
            $this->response(NULL, REST_Controller::HTTP_NOT_FOUND); 
        }

    }

}

?>