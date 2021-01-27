<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Facturas extends CI_Controller {
	
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros'));
                $this->load->model('tickets_');	
                $this->load->model('facturas_');	
                $this->load->library('excel');
                $this->load->library('exceldrawing');
                $this->load->database();
                	
        }
     function index(){
            $dato['autor']='Miguel Angel Bañolas';
            $dato=array('error' => ' ' );
            $dato['hoy']=date('Y-m-d');
            $dato['numFactura']=$this->facturas_->getFacturaSiguiente();
            //echo $dato['numFactura'];
            $this->load->view('templates/header.html',$dato);
            $dato['activeMenu']='Tickets';
            $dato['activeSubmenu']='Generar Factura';
            $this->load->view('templates/top.php',$dato);
            $this->load->view('facturas/introducirTickets',$dato );
            $this->load->view('templates/footer.html',$dato);   
     }   
     
     function datosTicket(){
          $this->load->model('tickets_');
          $ticket=$_POST['ticket'];
          $resultado = $this->tickets_->getImporteCliente($ticket);
          
          echo  json_encode($resultado);
     }
     
     function getTicketsDiaClientes(){
         $this->load->model('tickets_');
         $fecha=$_POST['fecha'];
         $tickets=$this->tickets_->getTicketsDiaClientes($fecha);
         
         echo json_encode($tickets);
     }
     
     function getTicketsDiaFecha(){
         $this->load->model('tickets_');
         $fecha=$_POST['fecha'];
         $fechas=$this->tickets_->getTicketsDiaFecha($fecha);
         
         echo json_encode($fechas);
     }
     
     function getFechasNumTicket(){
         $this->load->model('tickets_');
         $numTicket=$_POST['numTicket'];
         $fechas=$this->tickets_->getFechasNumTicket($numTicket);
         
         echo json_encode($fechas);
     }
     
     
     function getTicketsDiaFechaClientes(){
         $numTicket=$_POST['numTicket'];
         $fechas=$this->tickets_->getTicketsDiaFechaClientes($numTicket);
         
         echo json_encode($fechas);
     }
     
     function comprobarId_factura(){
         $numFactura=$_POST['numFactura'];
         $resultado=$this->facturas_->comprobarId_factura($numFactura);
          
          echo json_encode($resultado);
     }
     
     
     function excelFactura(){
       
      //  $numFactura=$_POST['numFactura'];
      //   $resultado=$this->facturas_->comprobarId_factura($numFactura);
      //  if($resultado==0){
      
         $resultado=$this->facturas_->excelFactura();
        //}
        //else {$resultado=true;}
        echo json_encode($resultado);
     }
     
     
    
     
}