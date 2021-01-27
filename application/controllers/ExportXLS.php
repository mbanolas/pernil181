<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class ExportXLS extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        ini_set('max_execution_time', 300);
    }

    public function index() {

        //log_message('INFO','------------------------------paso por generar listas');


        $this->load->helper('maba');
    
        $this->load->library('excel');
        $sql="SELECT p.codigo_producto as codigo_producto,p.nombre as nombre,s.cantidad as cantidad FROM pe_stocks_totales s 
              LEFT JOIN pe_productos p ON p.id=s.id_pe_producto
              WHERE p.status_producto=1  
        ";
        $datos['result']=$this->db->query($sql)->result();
        $this->load->view('prepararExcel', $datos);

}

}

?>
