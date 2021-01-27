<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Mostrar extends CI_Controller {
	
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros'));
                $this->load->model('tickets_');	
                $this->load->model('caja_');
                $this->load->model('listados_');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                $this->load->model('mostrar_model');
                    
        }
        
        function getTickets(){
         
         $tickets=array();
         $tickets=$this->mostrar_model->getTickets($_POST['inicio'], $_POST['final']);
         
         echo json_encode($tickets);
        }
        
        
        function mostrarTicketMostrar($ticket){
                
                //$dato['autor']='Miguel Angel Bañolas';
                //$dato['ticket']=$ticket;
                $ticketsPeriodo=$this->mostrar_model->getTicketsPeriodo();
                
                $datosTickets=$this->mostrar_model->getDatosTicket($ticket);
                
                $datos['ticket']=$datosTickets['ticket'];
                $datos['ticket2']=$datosTickets['ticket2'];
                $datos['ticketsPeriodo']=$ticketsPeriodo;
                $datos['primero']=1;
                $datos['posicion']=$ticket;
                $datos['ultimo']=sizeof($ticketsPeriodo);
                $datos['totalNumTickets']=sizeof($ticketsPeriodo);
                $this->load->view('templates/header.html');
                
                $this->load->view('templates/top.php');
                
                $this->load->view('botonesTicketsMostrarNavegar',$datos );
		$this->load->view('mostrarTicketMostrar',$datos );
                $this->load->view('templates/footer.html');
        }
        
        function mostrarTicketCambiar($ticket){
                //$dato['autor']='Miguel Angel Bañolas';
                //$dato['ticket']=$ticket;
                $ticketsPeriodo=$this->mostrar_model->getTicketsPeriodo();
                $datosTickets=$this->mostrar_model->getDatosTicket($ticket);
                $datos['formasPago']=$this->mostrar_model->getFormasPago();
                $datos['id_clientes']=$this->listados_->getNumerosClientes();
                $datos['clientes']=$this->listados_->getNombresClientes();
                
                $datos['ticket']=$datosTickets['ticket'];
                //$datos['ticket2']=$datosTickets['ticket2'];
                $datos['ticketsPeriodo']=$ticketsPeriodo;
                $datos['primero']=1;
                $datos['posicion']=$ticket;
                $datos['ultimo']=sizeof($ticketsPeriodo);
                $datos['totalNumTickets']=sizeof($ticketsPeriodo);
                
                //$datos['contenido']=$this->load->view('mostrarTicketConversion',$datos );
               // $dato['botones']=$this->load->view('botonesTicketsModificarNavegar',$dato,true );
                $this->load->view('templates/header.html');
                $this->load->view('templates/top.php');
                $this->load->view('botonesTicketsCambiarNavegar',$datos );
		$this->load->view('mostrarTicketCambiar',$datos );
                $this->load->view('templates/footer.html');
        }
        
        function mostrarTicket(){
                $ticket=$_SESSION['ticketActual'];
                
                //$dato['autor']='Miguel Angel Bañolas';
                //$dato['ticket']=$ticket;
                $ticketsPeriodo=$this->mostrar_model->getTicketsPeriodo();
                $datosTickets=$this->mostrar_model->getDatosTicket($ticket);
                $datos['formasPago']=$this->mostrar_model->getFormasPago();
                $datos['id_clientes']=$this->listados_->getNumerosClientes();
                $datos['clientes']=$this->listados_->getNombresClientes();
                $datos['ticket']=$datosTickets['ticket'];
                //$datos['ticket2']=$datosTickets['ticket2'];
                $datos['ticketsPeriodo']=$ticketsPeriodo;
                $datos['primero']=1;
                $datos['posicion']=$ticket;
                $datos['ultimo']=sizeof($ticketsPeriodo);
                $datos['totalNumTickets']=sizeof($ticketsPeriodo);
                
                //$datos['contenido']=$this->load->view('mostrarTicketConversion',$datos );
               // $dato['botones']=$this->load->view('botonesTicketsModificarNavegar',$dato,true );
                $this->load->view('templates/header.html');
                $dato['activeMenu']='Tickets';
                $dato['activeSubmenu']='Cambio Forma Pago - Cliente Tickets Periodo';
                $this->load->view('templates/top.php',$dato);
                $this->load->view('botonesTicketsCambiarNavegar',$datos );
		$this->load->view('mostrarTicketCambiar',$datos );
                $this->load->view('templates/footer.html');
        }
        
        
        function mostrarTicketMostrarAjax(){
                $ticket=$_POST['numTicket'];
                //$dato['autor']='Miguel Angel Bañolas';
                //$dato['ticket']=$ticket;
                $ticketsPeriodo=$this->mostrar_model->getTicketsPeriodo();
                $datosTickets=$this->mostrar_model->getDatosTicket($ticket);
                $datos['ticket']=$datosTickets['ticket'];
                $datos['ticket2']=$datosTickets['ticket2'];
                $datos['ticketsPeriodo']=$ticketsPeriodo;
                $datos['primero']=1;
                $datos['posicion']=$ticket;
                $datos['ultimo']=sizeof($ticketsPeriodo);
                $datos['totalNumTickets']=sizeof($ticketsPeriodo);
                
		$salida=$this->load->view('mostrarTicketMostrarAjax',$datos );
                
                
                echo json_encode(utf8_encode($salida));
        }
        
        function ajax(){
            $ticket=$_POST['ticket'];
            $_SESSION['ticketActual']=$ticket;
            echo json_encode(utf8_encode( $ticket));
        }
        
        function mostrarTicketCambiarAjax(){
                $ticket=$_POST['numTicket'];
                //$dato['autor']='Miguel Angel Bañolas';
                //$dato['ticket']=$ticket;
                $ticketsPeriodo=$this->mostrar_model->getTicketsPeriodo();
                $datosTickets=$this->mostrar_model->getDatosTicket($ticket);
                $datos['id_clientes']=$this->listados_->getNumerosClientes();
                $datos['clientes']=$this->listados_->getNombresClientes();
                $datos['formasPago']=$this->mostrar_model->getFormasPago();
                $datos['ticket']=$datosTickets['ticket'];
                $datos['ticket2']=$datosTickets['ticket2'];
                $datos['ticketsPeriodo']=$ticketsPeriodo;
                $datos['primero']=1;
                $datos['posicion']=$ticket;
                $datos['ultimo']=sizeof($ticketsPeriodo);
                $datos['totalNumTickets']=sizeof($ticketsPeriodo);
                
		$salida=$this->load->view('mostrarTicketCambiarAjax',$datos );
                
                
                
                echo json_encode(utf8_encode( $salida));
        }
        
        
        
        
        function getDatosTicket($ticket){
            $this->mostrar_model->getDatosTicket($ticket);
        }
        
        
       
        
        
}