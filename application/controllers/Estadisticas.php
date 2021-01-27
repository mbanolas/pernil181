<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Estadisticas extends CI_Controller {
	 
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros'));
                $this->load->model('tickets_');	
                $this->load->model('caja_');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                    
        }
        
     function evolucionPVP(){
        $this->load->model('stocks_model');	
        $dato=array();
        $dato['productosArray']=$this->stocks_model->getProductosVenta()['productosArray']; 
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('evolucionPVP',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');        
     }   
     
     function evolucionVentas(){
        $this->load->model('stocks_model');	
        $dato=array();
        $dato['productosArray']=$this->stocks_model->getProductos()['productosArray']; 
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('evolucionVentas',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        
     }  
     
     function evolucionStocks(){
        $this->load->model('stocks_model');	
        $dato=array();
        $dato['productosArray']=$this->stocks_model->getProductos(" ","",true)['productosArray']; 
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('evolucionStocks',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        
     }  
     
     function ventasUltimoDia(){
        $this->load->model('stocks_model');	
        $dato=array();
        $dato['ventasUltimoDia']=$this->getVentasUltimoDia(); 
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('ventasUltimoDia',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        
     }   
     
     
     
     function getDatosVentas($id_pe_producto="",$fechaDesde='',$fechaHasta=''){
         if($id_pe_producto=='') $id_pe_producto=$_POST['id_pe_producto'];
         if($fechaDesde=='') $fechaDesde=$_POST['fechaDesde'];
         if($fechaHasta=='') $fechaHasta=$_POST['fechaHasta'];
         $this->load->model('estadisticas_model');
         $datos=$this->estadisticas_model->getDatosVentas($id_pe_producto,$fechaDesde,$fechaHasta);
         echo  json_encode($datos);
         
     }
     
     function getCantidadesVentas($id_pe_producto="",$fechaDesde='',$fechaHasta=''){
         if($id_pe_producto=='') $id_pe_producto=$_POST['id_pe_producto'];
         if($fechaDesde=='') $fechaDesde=$_POST['fechaDesde'];
         if($fechaHasta=='') $fechaHasta=$_POST['fechaHasta'];
         $this->load->model('estadisticas_model');
         $datos=$this->estadisticas_model->getCantidadesVentas($id_pe_producto,$fechaDesde,$fechaHasta);
         echo  json_encode($datos);
         
     }
     
     function getCantidadesStocks($id_pe_producto="",$fechaDesde='',$fechaHasta=''){
         if($id_pe_producto=='') $id_pe_producto=$_POST['id_pe_producto'];
         if($fechaDesde=='') $fechaDesde=$_POST['fechaDesde'];
         if($fechaHasta=='') $fechaHasta=$_POST['fechaHasta'];
         $this->load->model('estadisticas_model');
         $datos=$this->estadisticas_model->getCantidadesStocks($id_pe_producto,$fechaDesde,$fechaHasta);
         echo  json_encode($datos);
         
     }
     
     function getVentasUltimoDia(){
         $this->load->model('estadisticas_model');
         $datos=$this->estadisticas_model->getVentasUltimoDia();
         return $datos;
     }
        
        
        
}