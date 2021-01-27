<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Clientes extends CI_Controller {
	 
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros'));
                $this->load->model('clientes_');
        }
        
    function getClientesFiltro(){
         $filtro=$_POST['filtro'];
         $id=$_POST['id'];
         $optionsClientes=$this->clientes_->getClientesFiltro($filtro)['optionsClientes'];
         echo json_encode($optionsClientes);
         
     }   
     
     function getDatosCliente($id_cliente){
        echo json_encode($this->clientes_->getDatosCliente($id_cliente));

     }

     function getDatosClientePrestashop($numCliente){
        echo json_encode($this->clientes_->getDatosClientePrestashop($numCliente));
     }
        
        
        
        
}