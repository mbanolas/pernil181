<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Estudios extends CI_Controller {
	 
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
                $this->load->model('productos_');	
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                    
        }
        
    function entrarDatos() {
            $dato['codigos_productos']=$this->productos_->getCodigos();
            $dato['codigos_productos_nombre']=$this->productos_->getCodigosNombre();
            
            $dato['codigos_productos_em']=$this->productos_->getCodigosEstudiosMercado();
            $dato['codigos_productos_em_nombre']=$this->productos_->getCodigosEstudiosMercadoNombre();
            
            $dato['autor'] = 'Miguel Angel Bañolas';
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('estudios/entrarDatos', $dato);
            $this->load->view('templates/footer.html', $dato);
        }
     
        function getDatosProducto(){
            $codigo_producto=$_POST['codigo_producto'];
            
            if (!$codigo_producto) {echo json_encode($codigo_producto); return false;}
            
             $datos=$this->productos_->getDatosProducto($codigo_producto);
             $iva=$this->productos_->getIva($codigo_producto);
             $datosMercado=$this->productos_->getDatosProductoEstudioMercado($codigo_producto);
            
            echo json_encode(array('datos'=>$datos,'iva'=>$iva,'datosMercado'=>$datosMercado));
        }
        
        
        function getSiguienteCodigoEM(){
            $siguienteCodigoEM=$this->productos_->getSiguienteCodigoEM();
            if(!$siguienteCodigoEM) $siguienteCodigoEM="EM00000000000";
            
            $actual=substr($siguienteCodigoEM,2);
           
            $actual=$actual+1;
            while(strlen($actual)<11) $actual="0".$actual;
            
            $siguienteCodigoEM="EM".$actual;
           
            echo json_encode($siguienteCodigoEM);
        }
       
        function grabarDatosEstudiosMercado(){
            $result=$this->productos_->grabarDatosEstudiosMercado();
            echo json_encode($result);
        }
    
        
}