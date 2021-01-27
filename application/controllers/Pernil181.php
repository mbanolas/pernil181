<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pernil181 extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $dato['autor'] = 'Miguel Angel Bañolas';
        $dato['host']=host();
        $dato['tituloAplicacion']=tituloAplicacion();
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/cabecera', $dato);
        $this->load->view('pernil181', array('error' => ' '));
        $this->load->view('templates/footer.html', $dato);
    }
}
