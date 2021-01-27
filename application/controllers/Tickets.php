<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Tickets extends CI_Controller {

   
	
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros'));
                $this->load->model('tickets_');	
                	
        }
    
    
	public function index()
	{
                
                
    }
    
    function grabarCliente(){
        extract($_POST);
        $resultado=0;
        $sql="SELECT id FROM pe_boka WHERE STYP=1 AND zeis='$fecha' AND RASA='$num_ticket'";
        $num=$this->db->query($sql)->num_rows();
        if($num==1){
            $snr1=$cliente*10+7;
            $sql="UPDATE pe_boka SET SNR2='$cliente',SNR1='$snr1' WHERE STYP=1 AND zeis='$fecha' AND RASA='$num_ticket'";
            $resultado=$this->db->query($sql);
            $resultado=$resultado && $this->db->query("UPDATE pe_tickets SET id_cliente='$cliente' WHERE fecha='$fecha' AND num_ticket='$num_ticket'");
        }
        echo  json_encode($resultado);
    }

    function grabarFormaPago(){
        extract($_POST);
        $resultado=0;
        $sql="SELECT id,bonu FROM pe_boka WHERE STYP=1 AND zeis='$fecha' AND RASA='$num_ticket'";
        $num=$this->db->query($sql)->num_rows();
        if($num==1){
            $bonu=$this->db->query($sql)->row()->bonu;
            $resultado=$this->db->query("UPDATE pe_boka SET PAR1='$id_forma_pago' WHERE bonu='$bonu' and STYP=8 AND zeis='$fecha' AND PAR1!='20'");
            $sql="UPDATE pe_tickets SET id_forma_pago_ticket='$id_forma_pago' WHERE fecha='$fecha' AND num_ticket='$num_ticket'";
            mensaje($sql);
            $resultado2=$this->db->query($sql);
            $resultado=$resultado && $resultado2;
        }
        echo  json_encode($resultado);
    }
        /*
        function getNombreArchivoFactura_($id){
            $this->load->database();
            $sql="SELECT nombreArchivoFactura FROM pe_registroFacturas WHERE id='$id'";
            $nombreArchivoFactura=$this->db->query($sql)->row()->nombreArchivoFactura;
            $nombreArchivoFactura=ucwords('F'.substr($nombreArchivoFactura,strpos($nombreArchivoFactura,'actura')));
            echo  json_encode($nombreArchivoFactura);
        }
         * 
         */
        
        function editar_cliente()
    {
        $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $lastUriSegment = array_pop($uriSegments);
        $row=$this->db->query("SELECT * FROM pe_tickets WHERE id='$lastUriSegment'")->row();
        $dato['fecha']=$row->fecha;
        $dato['num_ticket']=$row->num_ticket;
        $dato['total']=$row->total;
        $dato['forma_pago_ticket']=$row->forma_pago_ticket;
        $dato['id_cliente']=$row->id_cliente;
        
        $result=$this->db->query("SELECT * FROM pe_clientes ORDER BY nombre")->result();
        $clientes[0]="Sin asignar cliente";
        foreach($result as $v){
            $clientes[$v->id_cliente]=$v->nombre;
        }
        $dato['clientes']=$clientes;
        // $dato['ultimaFactura']=$this->db->query("SELECT id_factura FROM pe_registroFacturas ORDER BY id DESC LIMIT 1 ")->row()->id_factura;


        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('editar_cliente', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);

    }

    function editar_forma_pago()
    {
        $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $lastUriSegment = array_pop($uriSegments);
        $sql="SELECT *,forma_pago_ticket FROM pe_tickets t
                LEFT JOIN pe_clientes c ON c.id_cliente=t.id_cliente 
                LEFT JOIN pe_formas_pagos_tickets p ON p.id_forma_pago_ticket=t.id_forma_pago_ticket WHERE t.id='$lastUriSegment'";
        $row=$this->db->query($sql)->row();
        mensaje($sql);
        $dato['fecha']=$row->fecha;
        $dato['num_ticket']=$row->num_ticket;
        // $dato['num_ticket']=$sql;
        $dato['total']=$row->total;
        $dato['id_forma_pago_ticket']=$row->id_forma_pago_ticket;
        $dato['id_cliente']=$row->id_cliente;
        $dato['forma_pago']=$row->forma_pago;
        $dato['nombre_cliente']=$row->nombre;
        $result=$this->db->query("SELECT * FROM pe_formas_pagos_tickets ORDER BY forma_pago")->result();
        $formas_pago[0]="Sin asignar forma pago";
        foreach($result as $v){
            $formas_pago[$v->id_forma_pago_ticket]=$v->forma_pago;
        }
        $dato['formas_pago']=$formas_pago;

        
        // $dato['ultimaFactura']=$this->db->query("SELECT id_factura FROM pe_registroFacturas ORDER BY id DESC LIMIT 1 ")->row()->id_factura;


        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('editar_forma_pago', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);

    }

        public function getDatosTicket(){
            $textoTicket=$this->tickets_->getDatosTicket(); 
            echo  json_encode($textoTicket);
        }
        
        public function getDatosTicketIdTickets(){
            $textoTicket=$this->tickets_->getDatosTicketIdTickets(); 
            echo  json_encode($textoTicket);
        }
        public function getDatosTicketNumFechaTickets(){
            $textoTicket=$this->tickets_->getDatosTicketNumFechaTickets(); 
            echo  json_encode($textoTicket);
        }
        
        
        
         public function getDatosTicket__(){
             $numTicket=$_POST['ticket'];
             
             $ticket=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
             $textoTicket="";
           // $textoTicket="<h3>Ticket núm ".$ticket['numero'].' '.$ticket['fecha']."</h3>";
            $textoTicket.='<div class="row">'.'<div class="col-md-12">'.'<table class="table ticket" >';
            $textoTicket.='<thead>'.'<tr><th colspan="3" class="col-md-12 izquierda">'.$ticket['modo'].'</th></tr>';
            if($ticket['cliente']!==""){
                $textoTicket.='<tr><td colspan="3" class="col-md-12 izquierda ">Núm Cliente: '.$ticket['cliente'].'</td></tr>';
                $textoTicket.='<tr><td colspan="3" class="col-md-12 izquierda ">'.$ticket['nombreCliente'].'</td></tr>';
                $textoTicket.='<tr><td colspan="3" class="col-md-12 izquierda "style=" border-bottom:1px solid black ">'.$ticket['empresa'].'</td></tr>';
            }
                $textoTicket.='<tr >';
                $textoTicket.='<td class="col-md-4 izquierda">Spdto '.$ticket['subDepartamento'] .'</td>';
                $textoTicket.='<td class="col-md-4 centro">Caja '.$ticket['numCaja'] .'</td>';
                $textoTicket.='<td class="col-md-4 ">#'.$ticket['referencia'] .'</td>';
                $textoTicket.='</tr >';
                
                $textoTicket.='<tr >';
                $textoTicket.='<td class="col-md-4 izquierda">'.$ticket['fecha'] .'</td>';
                $textoTicket.='<td class="col-md-4 centro">'.$ticket['numero'] .'/'.$ticket['numCaja'].'</td>';
                $textoTicket.='<td class="col-md-4 ">Depe '.$ticket['dependiente'] .'</td>';
                $textoTicket.='</tr >';
                
                $textoTicket.='</thead>';
                $textoTicket.='</table>';
                $textoTicket.='</div>';
                
                $textoTicket.='<div class="row">';
                if ($ticket['piezas']) {
                    $textoTicket.='<div class="col-md-12">';
                    $textoTicket.='<table class="table ticket" >';
                    $textoTicket.='<thead>';
                    $textoTicket.='<tr style="border:2px solid grey;">';
                    $textoTicket.='<th class="col-md-3">Pza.</th>';
                    $textoTicket.='<th class="col-md-3">I.V.A.</th>';
                    $textoTicket.='<th class="col-md-3">€/Pza</th>';
                    $textoTicket.='<th class="col-md-3">€</th>';
                    $textoTicket.='</tr> ';
                    $textoTicket.='</thead>';
                    $textoTicket.='<tbody>';
                    foreach ($ticket['unidades_pesos'] as $k => $v) {
                        if ($v =="1" || $v=="3" ) { 
                            $textoTicket.='<tr>';
                            $textoTicket.=    '<th colspan="4" class="col-md-12 izquierda">'.$ticket['productos'][$k].'</th>';
                            $textoTicket.='<tr>';
                            
                            $textoTicket.='<tr>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['unidades'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['tiposIva'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['preciosUnitarios'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['precios'][$k].'</td>';
                            $textoTicket.='</tr>';
                            
                            if ($ticket['descuentos'][$k] !=0) {   
                             $textoTicket.='<tr>';
                             $textoTicket.=    '<td colspan="3" class="col-md-3 izquierda">Su ventaja</td>';
                                
                             $textoTicket.=   '<td class="col-md-3">'.$ticket['descuentos'][$k].'</td>';
                            $textoTicket.='</tr>';
                            
                           }
                            
                        }
                    }
                    $textoTicket.='</tbody>';
                    $textoTicket.='</table>';
                    $textoTicket.='</div>';
                }
                if ($ticket['pesados']){
                    $textoTicket.='<div class="col-md-12">';
                    $textoTicket.='<table class="table ticket" >';
                    $textoTicket.='<thead>';
                    $textoTicket.='<tr style="border:2px solid grey;">';
                    $textoTicket.='<th class="col-md-3">Kg</th>';
                    $textoTicket.='<th class="col-md-3">I.V.A.</th>';
                    $textoTicket.='<th class="col-md-3">€/Kg</th>';
                    $textoTicket.='<th class="col-md-3">€</th>';
                    $textoTicket.='</tr> ';
                    $textoTicket.='</thead>';
                    $textoTicket.='<tbody>';
                    foreach ($ticket['unidades_pesos'] as $k => $v) {
                        if ($v =="0" || $v=="4" ) { 
                            $textoTicket.='<tr>';
                            $textoTicket.=    '<th colspan="4" class="col-md-12 izquierda">'.$ticket['productos'][$k].'</th>';
                            $textoTicket.='<tr>';
                            
                            $textoTicket.='<tr>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['unidades'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['tiposIva'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['preciosUnitarios'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['precios'][$k].'</td>';
                            $textoTicket.='</tr>';
                            
                            if ($ticket['descuentos'][$k] !=0) {   
                             $textoTicket.='<tr>';
                             $textoTicket.=    '<td colspan="3" class="col-md-3 izquierda">Su ventaja</td>';
                             $textoTicket.=   '<td class="col-md-3">'.$ticket['descuentos'][$k].'</td>';
                             $textoTicket.='</tr>';
                           }
                            
                        }
                    }
                    $textoTicket.='</tbody>';
                    $textoTicket.='</table>';
                    $textoTicket.='</div>';
                }
                $textoTicket.='</div>'; 
                
                // añadir al final productos las entragas negativas cod 999998   
                if(true){
                $textoTicket.='<div class="row">';
                    $textoTicket.='<div class="col-md-12">';
                        $textoTicket.='<table class="table ticket" >';
                            $textoTicket.='<tbody>';
                                    foreach ($ticket['unidades_pesos'] as $k => $v) { 
                                        if ( $v=='2') { 
                                            $textoTicket.='<tr>';
                                             $textoTicket.=   '<th colspan="4" class="col-md-12 izquierda">'.ucfirst(strtolower($ticket['productos'][$k])).'</th>';
                                            $textoTicket.='</tr>';
                                            $textoTicket.='<tr>';
                                                $textoTicket.='<td class="col-md-3">'.$ticket['tiposIva'][$k].'</td>';
                                                $textoTicket.='<td class="col-md-3">'.$ticket['precios'][$k].'</td>';
                                            $textoTicket.='</tr>';
                                        } 
                                    }
                                $textoTicket.='</tbody>';
                        $textoTicket.='</table> ';   
                    $textoTicket.='</div>';
                   $textoTicket.='</div>';   
                }
                
                $textoTicket.='<div class="row">';
                $textoTicket.='<div class="col-md-12">';
                $textoTicket.='<table class="table ticket" >';
                $textoTicket.='<tbody>';

                $textoTicket.='<tr>';
                $textoTicket.= '<th  class="col-md-3 izquierda ticketTotal">';
                $textoTicket.= $ticket['numPartidasTicket'].' Part';
                $textoTicket.= '</th>';
                $textoTicket.='<th class="col-md-3 centro ticketTotal">';
                $textoTicket.= 'Suma';
                $textoTicket.='</th>';
                $textoTicket.='<th class="col-md-3 centro ticketTotal">';
                $textoTicket.= '€';
                $textoTicket.='</th>';
                $textoTicket.= '<th class="col-md-3" style="font-size: 20px">';
                $textoTicket.= $ticket['totalTicket'];
                $textoTicket.= '</th>';
                $textoTicket.='</tr>';
                
                ksort($ticket['formaPago']);
                
                foreach($ticket['formaPago'] as $k => $v) {
                    $textoTicket.='<tr>';
                    $textoTicket.='<td colspan="2" class="col-md-3 izquierda">';
                    $textoTicket.= $ticket['formaPago'][$k];
                    $textoTicket.='</td>';
                    $textoTicket.='<td class="col-md-3 centro">';
                    $textoTicket.=$ticket['importeFormaPago'][$k]!=""?"€":"" ;
                    $textoTicket.='</td>';
                    $textoTicket.='<td class="col-md-3">';
                    $textoTicket.=$ticket['importeFormaPago'][$k];
                    $textoTicket.='</td>';
                    $textoTicket.='</tr> ';   
                }
                
                $textoTicket.='<tr>';
                $textoTicket.='<td colspan="4" class="col-md-3 izquierda">';
                $textoTicket.='En la suma se incluye';
                $textoTicket.='</td>';
                  
                $textoTicket.='</tr>';
                $textoTicket.='<tr>';
                $textoTicket.='<td class="col-md-3 izquierda">';
                $textoTicket.='</td>';
                $textoTicket.='<td class="col-md-3">';
                $textoTicket.=    'I.V.A.';
                $textoTicket.='</td>';
                $textoTicket.='<td class="col-md-3">';
                $textoTicket.=    'Neto';
                $textoTicket.='</td>';
                $textoTicket.='<td class="col-md-3">';
                $textoTicket.=    'Bruto';
                $textoTicket.='</td>';
                $textoTicket.='</tr>';
                
                foreach ($ticket['tipoIvasSum'] as $k => $v) { 
                    $textoTicket.='<tr>';
                    $textoTicket.='<td colspan="4" class="col-md-3 izquierda">'.$v.'% '.$ticket['textos'][$k] .'</td>';
                    $textoTicket.='</tr>';
                    $textoTicket.='<tr>';
                    $textoTicket.='<td class="col-md-3"></td>';
                    $textoTicket.='<td class="col-md-3">'.$ticket['ivas'][$k].'</td>';
                    $textoTicket.='<td class="col-md-3">'.$ticket['netos'][$k].'</td>';
                    $textoTicket.='<td class="col-md-3">'.$ticket['brutos'][$k].'</td>';
                    $textoTicket.='</tr>';
                }
                
                if ($ticket['sumaIvas']>0){  
                    $textoTicket.='<tr>';
                    $textoTicket.='<td class="col-md-3">Suma</td>';
                    $textoTicket.='<td style="border-top:1px solid black" class="col-md-3">'.number_format($ticket['sumaIvas'],2).'</td>';
                    $textoTicket.='<td class="col-md-3"></td>';
                    $textoTicket.='<td class="col-md-3"></td>';
                    $textoTicket.='</tr>';
                 } 
                
                $textoTicket.='<tr>';
                $textoTicket.='<td colspan="4" class="col-md-3 izquierda">'.$ticket['fechaCierre'].'</td>';
                $textoTicket.='</tr>';
                $textoTicket.='<tr>';
                $textoTicket.='<td colspan="4" class="col-md-3 izquierda">ATES PER '.$ticket['nombreDependiente'].'</td>';
                $textoTicket.='</tr>  ';
                
                $textoTicket.='</tbody>';
                $textoTicket.='</table >';
                $textoTicket.='</div >';
                $textoTicket.='</div >';
                
                $textoTicket.='</div>';
            echo  json_encode($textoTicket);
        }
        
        
        function entradaTicketManual()
        {
            $dato['autor']='Miguel Angel Bañolas';
            $dato=array('error' => ' ' );
            $this->load->view('templates/header.html',$dato);
            $dato['activeMenu']='Tickets';
            $dato['activeSubmenu']='Entrada Manual Datos Tira';
            $this->load->view('templates/top.php',$dato);
            $this->load->view('entradaTicketManual',$dato );
            $this->load->view('templates/footer.html',$dato);
        }
        
        function existenTickets(){
            $resultado = $this->tickets_->existenTickets($_POST['fecha']);
            echo  json_encode($resultado);
        }
        
        function crearTicket(){
            $resultado = $this->tickets_->crearTicket($_POST);
            echo  json_encode($resultado);
        }
        
        public function verTiposIvas(){
            $resultado = $this->tickets_->verTiposIvas();
            echo  json_encode($resultado);
        }
        
        function seleccionarTickets()
        {
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['hoy']=$this->tickets_->getTicketsHoy();
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionTickets',$dato );
                $this->load->view('templates/footer.html',$dato);
                
        }
        
        function prepararBoka2(){
            
            $this->load->model('tickets_');
            $resultado = $this->tickets_->prepararBoka2();
           
            echo  json_encode($resultado);
        }
        
        function prepararCorrecciones(){
            
            $this->load->model('tickets_');
            $resultado = $this->tickets_->prepararCorrecciones();
           
            echo  json_encode($resultado);
        }
        
        function cambiarClientePago(){
            
            $this->load->model('tickets_');
            $resultado = $this->tickets_->cambiarClientePago();
             echo  json_encode($resultado);
        }
        
        function seleccionaTicket(){ 
            $dato['autor']='Miguel Angel Bañolas';
            $this->load->model('tickets_');
            $dato=array('error' => ' ' );
            $dato['titulo']="Seleccionar ticket para mostar y Excel";
            $dato['idMenu']="menuCopiaTicket";
            $dato['segmentos']='tickets/mostrarUnTicket';
            $this->load->view('templates/header.html',$dato);
            $dato['activeMenu']='Tickets';
            $dato['activeSubmenu']='Mostrar UN Ticket';
            $this->load->view('templates/top.php',$dato);
	    $this->load->view('seleccionaTicket',$dato );
            $this->load->view('templates/footer.html',$dato);
        }
        
        function seleccionaTicketEliminar(){ 
            $dato['autor']='Miguel Angel Bañolas';
            $this->load->model('tickets_');
            $dato=array('error' => ' ' );
            $dato['titulo']="Seleccionar ticket para eliminar y Excel";
            $dato['idMenu']="menuCopiaEliminarTicket";
            $dato['segmentos']='tickets/mostrarUnTicketEliminar';
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
	    $this->load->view('seleccionaTicketEliminar',$dato );
            $this->load->view('templates/footer.html',$dato);
        }
        
        function seleccionaTicketCorregir(){ 
            $dato['autor']='Miguel Angel Bañolas';
            $this->load->model('tickets_');
            $dato=array('error' => ' ' );
            $dato['titulo']="Seleccionar ticket para corregir y Excel";
            $dato['idMenu']="menuCopiaCorregirTicket";
            $dato['segmentos']='tickets/mostrarUnTicketCorregir';
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
	    $this->load->view('seleccionaTicketCorregir',$dato );
            $this->load->view('templates/footer.html',$dato);
        }
        
        
        function seleccionaTicketProcesado(){
            $dato['autor']='Miguel Angel Bañolas';
            $this->load->model('tickets_');
            $dato=array('error' => ' ' );
            $dato['titulo']="Seleccionar ticket PROCESADO para mostar y Excel";
            $dato['idMenu']="menuCopiaTicketProcesado";
            $dato['segmentos']='tickets/mostrarUnTicketProcesado';
            $this->load->view('templates/header.html',$dato);
            $dato['activeMenu']='Tickets';
            $dato['activeSubmenu']='UN Ticket Procesado';
            
            $this->load->view('templates/top.php',$dato);
	    $this->load->view('seleccionaTicketProcesado',$dato );
            $this->load->view('templates/footer.html',$dato);
        }
        
        function resumen($desde,$hasta){
            $anoHastaFin=date('Y',$hasta);
            $mesFin=date('')


        }
        
        function cambioPagoCliente(){
            $numTicket= $_POST['ticket'];
                //echo $numTicket;
                mensaje('$numTicket '.$numTicket);
                $dato['ticket']=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
                $this->load->model('listados_');	
                $dato['clientes']=$this->listados_->getNombresClientes();
                $dato['empresas']=$this->listados_->getEmpresasClientes();
                $dato['id_clientes']=$this->listados_->getNumerosClientes();
                $this->load->model('mostrar_model');	
                $dato['formasPago']=$this->mostrar_model->getFormasPago();
                //$dato['botones']=$this->load->view('botonesUnTicketMostrar',$dato,true );;
                $dato['botones']=$this->load->view('botonesUnTicketCambioCliente',$dato,true );;
                $this->load->view('templates/header.html',$dato);
                $dato['activeMenu']='Tickets';
                $dato['activeSubmenu']='Cambio Forma Pago - Cliente UN Ticket';
                
                $this->load->view('templates/top.php',$dato);
		        $this->load->view('mostrarUnTicketCambioPagoCliente',$dato );
                $this->load->view('templates/footer.html',$dato);
        }
        
        
        function seleccionarTicketsCambioPago_1(){
            $dato['autor']='Miguel Angel Bañolas';
            $dato=array('error' => ' ' );
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
	    $this->load->view('seleccionCambioPagoTicket',$dato );
            $this->load->view('templates/footer.html',$dato);
        }
        
        
        
        function recuperarTicket(){
            //var_dump($_POST);
            $this->load->model('tickets_');
            $resultado=$dato['ticket']=$this->tickets_->recuperarTicket();
            
            redirect($this->uri->uri_string());
            
           echo  json_encode($resultado);
        }
        
        
        
        function buscarDatosTicket(){
            $this->load->model('tickets_');
            $resultado = $this->tickets_->buscarDatosTicket();
           // if($resultado->ticket && $resultado->ticket2)
           echo  json_encode($resultado);
        }
        function grabarDatosCaja(){
             $this->load->model('tickets_');
            $resultado = $this->tickets_->grabarDatosCaja();
            
            echo  json_encode($resultado);
        }
        
        function buscarDatosCaja(){
            $this->load->model('listados_');
            
            $resultado=$this->listados_->getDatosVentasBokaCaja($_POST['fecha'],$_POST['fecha']);           // if($resultado->ticket && $resultado->ticket2)
            
            
            $salida=array('categoria'=>$this->session->categoria,'resultado'=>$resultado['totales'],'post'=>$_POST, 'cajaAnterior'=>$resultado['cajaAnterior'],'sqlDV'=>$resultado['sqlDV']);
            echo  json_encode($salida);
        }
        
        
        function grabarDatosTicket(){
            $this->load->model('tickets_');
            $resultado = $this->tickets_->grabarDatosTicket();
           // if($resultado->ticket && $resultado->ticket2)
           echo  json_encode($resultado);
        }
        
        function seleccionarTicketsMostrar_()
        {
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
               // $dato['hoy']=$this->tickets_->getTicketsHoy();
                $dato['segmentos']="tickets/mostrarTicket";
                $dato['idBoton']="mostrarTicket";
                $dato['nombreBoton']="Mostrar Ticket";
                $dato['periodos']=$this->load->view('periodos',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionTicketsMostrar',$dato );
                $this->load->view('templates/footer.html',$dato);
        }
        function seleccionarTicketCambioPago(){
            $dato['autor']='Miguel Angel Bañolas';
            $this->load->model('tickets_');
            $dato=array('error' => ' ' );
            $dato['titulo']="Seleccionar ticket para cambio de cliente o sistema de pago.";
            $dato['idMenu']="menuCambioPagoTickets";
            $dato['segmentos']='tickets/cambioPagoCliente';
            $this->load->view('templates/header.html',$dato);
            
            $dato['activeMenu']='Tickets';
            $dato['activeSubmenu']='Cambio Forma Pago - Cliente UN Ticket';
                
            $this->load->view('templates/top.php',$dato);
	    $this->load->view('seleccionaTicket',$dato );
            $this->load->view('templates/footer.html',$dato);
        }
        function seleccionarTicketsMostrar()
        {
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="tickets/mostrarTicket";
                $dato['idBoton']="mostrarTicket";
                $dato['nombreBoton']="Mostrar Ticket";
                
                //parte derecha del view que carga en el view final mostrado por seleccionTicketsMostrar
                $dato['seleccionPeriodosDerecha']=$this->load->view('seleccionPeriodosDerecha',$dato,true);
                
                //carga el cuadro de selección que es renderizado por seleccionTicketsMostrar
                //con botón: "Mostrar Ticket" -> id="mostrarTicket"
                
                $dato['seleccionPeriodos']=$this->load->view('seleccionPeriodos',$dato,true);
                
                //rederiza el view final seleccionTicketsMostrar que contiene
                //cudro seleccion boton: Buscar Ticket con id=buscarTickets -> funcion buscarTickets 
                //-> js tickets/mostrarTicket
                //parte derecha: boton 
                $this->load->view('templates/header.html',$dato);
                
                $dato['activeMenu']='Tickets';
                $dato['activeSubmenu']='Mostrar Tickets Periodo';
            
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionTicketsMostrar',$dato );
                $this->load->view('templates/footer.html',$dato);
                $this->load->view('myModal',$dato );
        }
        
        function seleccionarTicketsCambiar()
        {
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="tickets/cambiarTicket";
                $dato['idBoton']="cambiarTicket";
                $dato['nombreBoton']="Mostrar Ticket";
                
                //parte derecha del view que carga en el view final mostrado por seleccionTicketsMostrar
                $dato['seleccionPeriodosDerecha']=$this->load->view('seleccionPeriodosDerecha',$dato,true);
                
                //carga el cuadro de selección que es renderizado por seleccionTicketsMostrar
                //con botón: "Mostrar Ticket" -> id="mostrarTicket"
                $dato['seleccionPeriodos']=$this->load->view('seleccionPeriodos',$dato,true);
                
                //rederiza el view final seleccionTicketsMostrar que contiene
                //cudro seleccion boton: Buscar Ticket con id=buscarTickets -> funcion buscarTickets 
                //-> js tickets/mostrarTicket
                //parte derecha: boton 
                $this->load->view('templates/header.html',$dato);
                $dato['activeMenu']='Tickets';
                $dato['activeSubmenu']='Cambio Forma Pago - Cliente Tickets Periodo';
                
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionTicketsCambiar',$dato );
                $this->load->view('templates/footer.html',$dato);
        }
        
        function seleccionarTicketsModificar()
        {       
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                //$dato['hoy']=$this->tickets_->getTicketsHoy();
                $dato['segmentos']="tickets/mostrarTicketModificar";
                $dato['idBoton']="mostrarTicketModificar";
                $dato['nombreBoton']="Mostrar y Modificar Ticket";
                
                //parte derecha del view que carga en el view final mostrado por seleccionTicketsModificar
                $dato['seleccionPeriodosDerecha']=$this->load->view('seleccionPeriodosDerecha',$dato,true);
                
                //rederiza el view final seleccionTicketsModificar que contiene
                //cudro seleccion boton: Buscar Ticket con id=buscarTickets -> funcion buscarTickets 
                //-> js tickets/mostrarTicketModificar
                //parte derecha: boton
                $dato['seleccionPeriodos']=$this->load->view('seleccionPeriodos',$dato,true);
                
                
                $this->load->view('templates/header.html',$dato);
                
                $dato['activeMenu']='Tickets';
                $dato['activeSubmenu']='Seleccionar Tickets Modificar';
                $dato['activeMenu']='Tickets';
                $dato['activeSubmenu']='Tickets Periodo Procesado';
            
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionTicketsModificar',$dato );
                $this->load->view('templates/footer.html',$dato);
        }
        
        function mostrarTicketModificarInicializar(){
            
               //if (!$this->is_logged_in()) redirect('pernil181');
               
               if (isset($_POST['periodo']))
                 $this->session->set_userdata('periodo',$_POST['periodo']);
             if (isset($_POST['inicio']))
                 $this->session->set_userdata('inicio',$_POST['inicio']);
             if (isset($_POST['final']))
                 $this->session->set_userdata('final',$_POST['final']);
             if (isset($_POST['tickets']))
                 $this->session->set_userdata('tickets',$_POST['tickets']);
             if (isset($_POST['ticketsPeriodo'])){
                 unset($_SESSION['ticketsPeriodo']);
                 $this->session->set_userdata('ticketsPeriodo',$_POST['ticketsPeriodo']);
             }
             
              $dato=array('error' => ' ' );
               // $numTicket=$this->session->ticketsPeriodo[$this->session->tickets];//$_POST['tickets'];
                $numTicket=$this->session->tickets;
                // se busca en $this->session->ticketsPeriodo
                foreach($this->session->ticketsPeriodo as $k=>$v)
                    if (strpos($v,$this->session->tickets)===0){
                        $posicion=$k;
                        break;
                    }
                $dato['numTicket']=$numTicket;
                $dato['posicion']=$posicion;
                
                
              $inicio=$_POST['inicio'];
              $final=$_POST['final'];
              
              //selecciona los tickets del periodo que se pueden modificar: metálico, NO cliente
              // y los pone en array session->ticketsPeriodo
              $query="SELECT b.BONU as BONU, a.RASA as RASA, a.ZEIS as ZEIS from pe_boka a "
                    . " left join pe_boka b on a.BONU=b.BONU and (b.STYP=8 and b.PAR1=1) "
                    . "WHERE (a.STYP=1 AND MOD(a.SNR1,10)<7) AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ORDER BY a.ZEIS";
            $query=$this->db->query($query);
            $i=0;
            foreach ($query->result() as $k => $row) {
                if($row->BONU){
                    $_SESSION['ticketsPeriodo'][$i]=$row->RASA.' '.fechaEuropea($row->ZEIS);
                    $i++;
                }
            }
            $dato=array('error' => ' ' );
             
            // calcula las sumas deimportes  e Ivas para boka y boka2
            // y los pone en session ...
             $this->load->model('listados_');
             $dato['campos']=array("BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS");

              $dato['results']=$this->listados_->getBoka($dato['campos'],$inicio,$final);
             $dato['resumenTodos']=$this->listados_->getResumenTodos($dato['results']);

             $dato['results']=$this->listados_->getBoka2($dato['campos'],$inicio,$final);
             $dato['resumenTodos2']=$this->listados_->getResumenTodos($dato['results']);
             
             //var_dump($dato['resumenTodos']);
             //var_dump($dato['resumenTodos2']);
             
             
             
             $diferenciaPeriodoImportes=  array_sum($dato['resumenTodos2']['total'])-array_sum($dato['resumenTodos']['total']);
             $diferenciaPeriodoIvas=array_sum($dato['resumenTodos2']['iva'])-array_sum($dato['resumenTodos']['iva']);
             
             //echo array_sum($dato['resumenTodos2']['total']).'<br />';
             //echo array_sum($dato['resumenTodos']['total']).'<br />';
             //echo $diferenciaPeriodoImportes;
             
             $newdata = array(
                'diferenciaPeriodoImportes'  => $diferenciaPeriodoImportes,
                'diferenciaPeriodoIvas'     => $diferenciaPeriodoIvas,
            );

            $this->session->set_userdata($newdata);

            redirect('tickets/mostrarTicketModificar');
        }
        
        
        function seleccionarTicketsFactura()
        {
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="tickets/prepararFactura";
                $dato['idBoton']="prepararFactura";
                $dato['nombreBoton']="Preparar Factura";
                $dato['periodos']=$this->load->view('periodos',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionTicketsFactura',$dato );
                $this->load->view('templates/footer.html',$dato);
                
        }
        
        function prepararFactura(){
            //var_dump($_POST);
            //var_dump($this->session->all_userdata());
            $ticket=$_POST['tickets'];
            redirect('tickets/ticket/'.$ticket);
        }
        
        function ticket($ticket){
            
            $dato['ticket']=$this->tickets_->getTicketPorNumero($ticket,'pe_boka');
            //var_dump($dato);
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
            $this->load->view('ticket',$dato );
            $this->load->view('botonPrepararFactura',$dato );
            $this->load->view('templates/footer.html',$dato);
        }
        
  
    
     function excelFactura2($boka) {
        $ticket = $_POST['ticket'];
        $ticket = $this->tickets_->getTicketPorNumero($ticket, $boka);

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $procesado="";
        if($boka=='pe_boka2') $procesado='PROCESADO';
        //echo $boka;
        $this->excel->getActiveSheet()->setTitle($ticket['numero'].' '.$procesado);
        //set cell A1 content with some text
        $numero = $ticket['numero'];
        $fecha = $ticket['fecha'];
        $this->excel->getActiveSheet()->setCellValue('A1', "Ticket núm $numero $fecha $procesado");
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        ///       $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->getActiveSheet()->setCellValue('A2', $ticket['modo']);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        //merge cell A1 until D1
        ///       $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $linea = 3;
        if ($ticket['cliente']!=="") {
            $this->excel->getActiveSheet()->setCellValue('A' . $linea, 'Núm Cliente: ' . $ticket['cliente']);
            $this->excel->getActiveSheet()->getStyle('A' . $linea)->getFont()->setSize(10);
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('A' . $linea, $ticket['nombreCliente']);
            $this->excel->getActiveSheet()->getStyle('A' . $linea)->getFont()->setSize(10);
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('A' . $linea, $ticket['empresa']);
            $this->excel->getActiveSheet()->getStyle('A' . $linea)->getFont()->setSize(10);
            $linea++;
        }

        $this->excel->getActiveSheet()->setCellValue('A' . $linea, 'Spdto ' . $ticket['subDepartamento']);
        $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        // $this->excel->getActiveSheet()->mergeCells('A6:D6');
        $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Caja ' . $ticket['numCaja']);
        $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$this->excel->getActiveSheet()->mergeCells('E6:H6');
        $this->excel->getActiveSheet()->setCellValue('H' . $linea, '#' . $ticket['referencia']);
        $this->excel->getActiveSheet()->getStyle('H' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$this->excel->getActiveSheet()->mergeCells('h6:k6');
        $linea++;

        $this->excel->getActiveSheet()->setCellValue('A' . $linea, $ticket['fecha']);
        $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        // $this->excel->getActiveSheet()->mergeCells('A6:D6');
        $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['numero'] . '/' . $ticket['numCaja']);
        $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$this->excel->getActiveSheet()->mergeCells('E6:H6');
        $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'Depe ' . $ticket['dependiente']);
        $this->excel->getActiveSheet()->getStyle('H' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$this->excel->getActiveSheet()->mergeCells('h6:k6');
        $linea++;
        if ($ticket['piezas']) {
            $linea++;
        $this->excel->getActiveSheet()->setCellValue('B' . $linea, 'Kg');
        $this->excel->getActiveSheet()->setCellValue('D' . $linea, 'I.V.A.');
        $this->excel->getActiveSheet()->setCellValue('F' . $linea, '€/kg');
        $this->excel->getActiveSheet()->setCellValue('H' . $linea, '€');
        $this->excel->getActiveSheet()->getStyle('B' . $linea.':F'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('B'.$linea.':H'.$linea)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A' . $linea . ':H' . $linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
            )));
            $linea++;
        foreach ($ticket['unidades_pesos'] as $k => $v) {
            if ($v == "1" || $v=="3") {
            $this->excel->getActiveSheet()->setCellValue('A' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('A'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('B' . $linea, $ticket['unidades'][$k]);
            $this->excel->getActiveSheet()->setCellValue('D' . $linea, $ticket['tiposIva'][$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, $ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['precios'][$k]);
            $this->excel->getActiveSheet()->getStyle('F' . $linea)->getNumberFormat()->setFormatCode('#,##0.00');
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00');

            $this->excel->getActiveSheet()->getStyle('B' . $linea.':H'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;
        }
        if ($ticket['descuentos'][$k] !=0) {
            $this->excel->getActiveSheet()->setCellValue('A' . $linea, 'Su ventaja');
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['descuentos'][$k]);
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00');
            $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;
            
        }
        }
        }
        if ($ticket['pesados']) {
            $linea++;
        $this->excel->getActiveSheet()->setCellValue('B' . $linea, 'Pza.');
        $this->excel->getActiveSheet()->setCellValue('D' . $linea, 'I.V.A.');
        $this->excel->getActiveSheet()->setCellValue('F' . $linea, '€/Pza');
        $this->excel->getActiveSheet()->setCellValue('H' . $linea, '€');
        $this->excel->getActiveSheet()->getStyle('A' . $linea . ':H' . $linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
            )));
            $this->excel->getActiveSheet()->getStyle('B' . $linea.':F'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('B'.$linea.':H'.$linea)->getFont()->setBold(true);
        $linea++;
        foreach ($ticket['unidades_pesos'] as $k => $v) { 
            if ($v == "0" || $v=="4") {
                $this->excel->getActiveSheet()->setCellValue('A' . $linea, $ticket['productos'][$k]);
                $this->excel->getActiveSheet()->getStyle('A'.$linea)->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $linea++;
                $this->excel->getActiveSheet()->setCellValue('B' . $linea, $ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->getStyle('B' . $linea)->getNumberFormat()->setFormatCode('#,##0.000');
                $this->excel->getActiveSheet()->setCellValue('D' . $linea, $ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, $ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->getStyle('F' . $linea)->getNumberFormat()->setFormatCode('#,##0.00');
                $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['precios'][$k]);
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00');
                $this->excel->getActiveSheet()->getStyle('B' . $linea.':H'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }  
        }
        }
        $this->excel->getActiveSheet()->setCellValue('A' . $linea, $ticket['numPartidasTicket'].' Part');
        $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Suma');
        $this->excel->getActiveSheet()->setCellValue('F' . $linea, '€');
        $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['totalTicket']);
        $this->excel->getActiveSheet()->getStyle('H' . $linea)->getFont()->setSize(18);        
        $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00');
        $this->excel->getActiveSheet()->getStyle('A'.$linea.':H'.$linea)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('H' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $linea++;
        ksort($ticket['formaPago']);
        foreach ($ticket['formaPago'] as $k => $v) { 
        $this->excel->getActiveSheet()->setCellValue('A' . $linea, $ticket['formaPago'][$k]);
        $this->excel->getActiveSheet()->setCellValue('F' . $linea, '€');
        $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['importeFormaPago'][$k] );
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00');
        $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('F' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('H' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $linea++;
        }
        $this->excel->getActiveSheet()->setCellValue('A' . $linea, 'En la suma se incluye');
        $linea++;
        $this->excel->getActiveSheet()->setCellValue('D' . $linea, 'I.V.A.');
        $this->excel->getActiveSheet()->setCellValue('F' . $linea, 'Neto');
        $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'Bruto');
        $this->excel->getActiveSheet()->getStyle('D' . $linea.':H'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $linea++;
        foreach ($ticket['tipoIvasSum'] as $k => $v) {
            $this->excel->getActiveSheet()->setCellValue('A' . $linea, $v.'% '.$ticket['textos'][$k]);
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('D' . $linea, $ticket['ivas'][$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, $ticket['netos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['brutos'][$k]);
            $this->excel->getActiveSheet()->getStyle('D' . $linea.':H'. $linea)->getNumberFormat()->setFormatCode('#,##0.00');

            $linea++;
        }
        if ($ticket['sumaIvas']>0){
            $this->excel->getActiveSheet()->setCellValue('A' . $linea, 'Suma');
            $this->excel->getActiveSheet()->setCellValue('D' . $linea, $ticket['sumaIvas']);
        $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getNumberFormat()->setFormatCode('#,##0.00');
        $linea++;
        }
        $this->excel->getActiveSheet()->setCellValue('A' . $linea, $ticket['fechaCierre']);
        $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $linea++;
        $this->excel->getActiveSheet()->setCellValue('A' . $linea, 'ATES PER '.$ticket['nombreDependiente']);
        $this->excel->getActiveSheet()->getStyle('A' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        
        $filename = "Ticket $numero.xls"; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    function seleccionarTicketsDiferencias()
        {
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['hoy']=$this->tickets_->getTicketsHoy();
                $dato['segmentos']="tickets/mostrarTicketDiferencias";
                $dato['idBoton']="compararDiferencias";
                $dato['nombreBoton']="Comparar Diferencias";
                $dato['periodos']=$this->load->view('periodos',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionTicketsFactura',$dato );
                $this->load->view('templates/footer.html',$dato);
                
        }
        
        public function mostrarUnTicket(){
            $numTicket= $_POST['ticket'];
                //echo $numTicket;
                $dato['ticket']=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
                
                $dato['botones']=$this->load->view('botonesUnTicketMostrar',$dato,true );;
                $this->load->view('templates/header.html',$dato);
                $dato['activeMenu']='Tickets';
                $dato['activeSubmenu']='Mostrar UN Ticket';
                $this->load->view('templates/top.php',$dato);
               // $this->load->view('mostrarUnTicketPreciosModificados',$dato );
		$this->load->view('mostrarUnTicket',$dato );
                $this->load->view('templates/footer.html',$dato);
        }
        
        public function mostrarUnTicketEliminar(){
            $numTicket= $_POST['ticket'];
                //echo $numTicket;
                $dato['ticket']=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
                
                $dato['botones']=$this->load->view('botonesUnTicketEliminar',$dato,true );;
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('mostrarUnTicketEliminar',$dato );
                $this->load->view('templates/footer.html',$dato);
        }
        
        public function mostrarUnTicketCorregir(){
            $numTicket= $_POST['ticket'];
                //echo $numTicket;
                $dato['ticket']=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
                
                $dato['botones']=$this->load->view('botonesUnTicketCorregir',$dato,true );;
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('mostrarUnTicketCorregir',$dato );
                $this->load->view('templates/footer.html',$dato);
        }
        
        public function eliminarTicket(){
            $ticket=$_POST['ticket'];
            $fecha=$_POST['fecha'];
            $resultado=$this->tickets_->eliminarTicket($ticket,$fecha);
            echo  json_encode($resultado);
        }
        
        public function mostrarUnTicketProcesado(){
            $numTicket= $_POST['ticket'];
                //echo $numTicket;
                $dato['ticket']=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka2");
                
                $dato['botones']=$this->load->view('botonesUnTicketProcesadoMostrar',$dato,true );;
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('mostrarUnTicketProcesado',$dato );
                $this->load->view('templates/footer.html',$dato);
        }
        
        
        function mostrarTicket()
        {
            
             if (isset($_POST['periodo']))
                 $this->session->set_userdata('periodo',$_POST['periodo']);
             if (isset($_POST['inicio']))
                 $this->session->set_userdata('inicio',$_POST['inicio']);
             if (isset($_POST['final']))
                 $this->session->set_userdata('final',$_POST['final']);
             if (isset($_POST['tickets']))
                 $this->session->set_userdata('tickets',$_POST['tickets']);
             if (isset($_POST['ticketsPeriodo'])){
                 unset($_SESSION['ticketsPeriodo']);
                 $this->session->set_userdata('ticketsPeriodo',$_POST['ticketsPeriodo']);
             }
             
            
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
               // $numTicket=$this->session->ticketsPeriodo[$this->session->tickets];//$_POST['tickets'];
                $numTicket=$this->session->tickets;
                // se busca en $this->session->ticketsPeriodo
                foreach($this->session->ticketsPeriodo as $k=>$v)
                    if (strpos($v,$this->session->tickets)===0){
                        $posicion=$k;
                        break;
                    }
                $dato['numTicket']=$numTicket;
                $dato['posicion']=$posicion;
                //echo $numTicket;
                $dato['ticket']=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
                $dato['botones']=$this->load->view('botonesTicketsMostrar',$dato,true );;

                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('mostrarTicket',$dato );
                $this->load->view('templates/footer.html',$dato);
        } 
        
        function mostrarTicketModificar()
        {
            
            if (isset($_POST['periodo']))
                 $this->session->set_userdata('periodo',$_POST['periodo']);
             if (isset($_POST['inicio']))
                 $this->session->set_userdata('inicio',$_POST['inicio']);
             if (isset($_POST['final']))
                 $this->session->set_userdata('final',$_POST['final']);
             if (isset($_POST['tickets']))
                 $this->session->set_userdata('tickets',$_POST['tickets']);
             if (isset($_POST['ticketsPeriodo'])){
                 unset($_SESSION['ticketsPeriodo']);
                 $this->session->set_userdata('ticketsPeriodo',$_POST['ticketsPeriodo']);
             }
            
            
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
               // $numTicket=$this->session->ticketsPeriodo[$this->session->tickets];//$_POST['tickets'];
                // $numTicket=$this->session->ticketsPeriodo[$this->session->tickets];//$_POST['tickets'];
                $numTicket=$this->session->tickets;
                // se busca en $this->session->ticketsPeriodo
                foreach($this->session->ticketsPeriodo as $k=>$v)
                    if (strpos($v,$this->session->tickets)===0){
                        $posicion=$k;
                        break;
                    }
                $dato['numTicket']=$numTicket;
                $dato['posicion']=$posicion;
                
                $dato['ticket']=$this->tickets_->getTicketPorNumero($dato['numTicket'],"pe_boka");
                $dato['ticket2']=$this->tickets_->getTicketPorNumero($dato['numTicket'],"pe_boka2");
               
               // var_dump($dato['ticket']);
               // var_dump($dato['ticket2']);
                
                $dato['botones']=$this->load->view('botonesTicketsModificar',$dato,true );;
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('mostrarTicketModificar',$dato );
                $this->load->view('templates/footer.html',$dato);
        }   
        
        function mostrarTicketDiferencias()
        {
            
             if (isset($_POST['periodo']))
                 $this->session->set_userdata('periodo',$_POST['periodo']);
             if (isset($_POST['inicio']))
                 $this->session->set_userdata('inicio',$_POST['inicio']);
             if (isset($_POST['final']))
                 $this->session->set_userdata('final',$_POST['final']);
             if (isset($_POST['tickets']))
                 $this->session->set_userdata('tickets',$_POST['tickets']);
             if (isset($_POST['ticketsPeriodo'])){
                 //var_dump($_POST['ticketsPeriodo']);
                 $this->session->set_userdata('ticketsPeriodo',$_POST['ticketsPeriodo']);
             }
                 
            
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $numTicket=$this->session->ticketsPeriodo[$this->session->tickets];//$_POST['tickets'];
                $dato['numTicket']=$numTicket;
                $dato['ticket']=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
                $dato['botones']=$this->load->view('botonesTicketsModificar',$dato,true );;
                
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
               // $this->load->view('botonesSeleccionTicketsModificar',$dato );
		$this->load->view('mostrarTicketModificar',$dato );
               // $this->load->view('botonesSeleccionTicketsModificar',$dato );
                $this->load->view('templates/footer.html',$dato);
        }   
        
        
        
        
        
        
       function pendiente()
        {
            $dato['autor']='Miguel Angel Bañolas';
                $data=array();
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('pendienteTickets', array('error' => ' ' ));
                $this->load->view('templates/footer.html',$dato);
                
        }
        
}

