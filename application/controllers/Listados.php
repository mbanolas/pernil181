<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");



class Listados extends CI_Controller {
 
	
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros'));
                $this->load->model('listados_');	}
                
    
	public function index()
	{
            
                
	}
        
        public function bajarExcelProductos(){
            extract($_POST);
            $this->load->model('prestashop_model');
            
            $salida=$this->prestashop_model->bajarExcelProductos($desde,$hasta,$inicio,$final);
            
            echo  json_encode($salida);   
        }
        
        public function bajarExcelProductosTickets(){
            extract($_POST);
            // mensaje('$cabecera '.$cabecera);
            $this->load->model('tickets_');
            
            $salida=$this->tickets_->bajarExcelProductos($cabecera,$titulos,$encabezados,$pies);

            echo  json_encode($salida); 

        }
        



        public function listaProductos()
	{
                $query="SELECT p.id_producto as id_producto, "
                        . "p.nombre as producto, "
                        . "pr.id_proveedor as id_proveedor, pr.nombre as proveedor, f.id_familia as id_familia, f.nombre as familia, p.Precio as precio FROM pe_productos p"
                        . " LEFT JOIN pe_proveedores pr ON pr.id_proveedor=p.id_proveedor "
                        . " LEFT JOIN pe_familias f ON f.id_familia=p.id_familia "
                        . " WHERE 1 GROUP BY p.id_producto";
                $dato['results']=$this->listados_->getSelect($query);
                $dato['proveedores']=$this->listados_->getSelect('SELECT * FROM pe_proveedores');
                $dato['familias']=$this->listados_->getSelect('SELECT * FROM pe_familias');
                $dato['post']=$_POST;
                $dato['autor']='Miguel Angel Bañolas';
                $dato['query']=$query;
                
                $data=array();
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('listado_productos', array('error' => ' ' ));
                $this->load->view('templates/footer.html',$dato);
                
	}
        
        public function listaProveedores()
	{       
                $query="SELECT  * FROM pe_proveedores pr GROUP BY pr.id_proveedor";
                $dato['results']=$this->listados_->getSelect($query);
                $dato['post']=$_POST;
                $dato['autor']='Miguel Angel Bañolas';
                $dato['query']=$query;
                $data=array();
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('listado_proveedores', array('error' => ' ' ));
                $this->load->view('templates/footerContainer.html',$dato);
                
	}
        
        public function listaAcreedores()
	{       
                $query="SELECT  * FROM pe_acreedores pr GROUP BY pr.id_proveedor";
                $dato['results']=$this->listados_->getSelect($query);
                $dato['post']=$_POST;
                $dato['autor']='Miguel Angel Bañolas';
                $dato['query']=$query;
                $data=array();
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('listado_acreedores', array('error' => ' ' ));
                $this->load->view('templates/footer.html',$dato);
                
	}
        
        public function listaFamilias()
	{
                $query="SELECT  "
                        . "f.id_familia as id_familia, f.nombre as familia "
                        . " FROM pe_familias f GROUP BY f.id_familia"
                       ;
                $dato['results']=$this->listados_->getSelect($query);
                $dato['post']=$_POST;
                $dato['autor']='Miguel Angel Bañolas';
                $dato['query']=$query;
                $data=array();
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('listado_familias', array('error' => ' ' ));
                $this->load->view('templates/footer.html',$dato);
                
	}
        
        public function listaClientes()
	{
                $query="SELECT  * FROM pe_clientes  GROUP BY id_cliente" ;
                $dato['results']=$this->listados_->getSelect($query);
                $dato['post']=$_POST;
                $dato['autor']='Miguel Angel Bañolas';
                $dato['query']=$query;
                
                $dato['cabecerasBD']=array('Código Cliente','Nombre Cliente','Dirección','Código Postal','Población','Provincia','País', 'Tienda(0)/web(1)', 'Contacto 1' ,'Teléfono','Correo 1', 'Fecha Alta', 'Código Contable', 'NIF','Descuento General', 'Grupo Precio','Web','Movil 1', 'Varios1', 'Notas', 'Contacto 2', 'Teléfono 2', 'Móvil 2', 'Correo 2', 'Fecha Modificación');
                $dato['camposBD']=array('id_cliente' , 'nombre' , 'direccion' , 'codigoPostal' , 'poblacion' , 'provincia', 'pais','tienda_web' , 'contacto1','telefono1' , 'correo1' ,'fechaAlta' , 'codigoContabilidad' ,    'nif' ,  'dtoGeneral' , 'grupoPrecio' , 'web' , 'movil1' , 'varios1' , 'notas' , 'contacto2' , 'telefono2' , 'movil2' , 'correo2','fechaModificacion' );

                $dato['cabecerasLista']=array('Código Cliente','Nombre Cliente','Dirección','Código Postal','Población','Provincia','País'); //, 'Tienda/web', 'Contacto 1' ,'Teléfono','Correo 1', 'Fecha Alta', 'Código Contable', 'NIF','Descuento General', 'Grupo Precio','Web','Movil 1', 'Varios1', 'Notas', 'Contacto 2', 'Teléfono 2', 'Móvil 2', 'Correo 2', 'Fecha Modificación');
                $dato['camposLista']=array('id_cliente' , 'nombre' , 'direccion' , 'codigoPostal' , 'poblacion' , 'provincia', 'pais'); // ,'tienda_web' , 'contacto1','telefono1' , 'correo1' ,'fechaAlta' , 'codigoContabilidad' ,    'nif' ,  'dtoGeneral' , 'grupoPrecio' , 'web' , 'movil1' , 'varios1' , 'notas' , 'contacto2' , 'telefono2' , 'movil2' , 'correo2','fechaModificacion' );
               
                
                $data=array();
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('listado_clientes', array('error' => ' ' ));
                $this->load->view('templates/footerContainer.html',$dato);
                
	}
        
        public function leerCliente(){
            $resultado=$this->listados_->leerCliente($_POST['id_cliente']);
            
            echo  json_encode($resultado);
        }
        
        public function leerClienteNuevo(){
            $resultado=$this->listados_->leerClienteNuevo();
            
            echo  json_encode($resultado);
        }
        
        
        public function seleccionBoka()
	{
            $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/listaBoka";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Datos Boka";
                $dato['periodosVentas']=$this->load->view('periodosVentas',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $dato['activeMenu']='Ventas Tienda';
                $dato['activeSubmenu']='Listado Datos Boka';
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionBoka',$dato );
                $this->load->view('templates/footer.html',$dato);    
            
            /*
             // guardamos selección en session
             if (isset($_POST['periodo']))
                 $this->session->set_userdata('periodo',$_POST['periodo']);
             if (isset($_POST['inicio']))
                 $this->session->set_userdata('inicio',$_POST['inicio']);
             if (isset($_POST['final']))
                 $this->session->set_userdata('final',$_POST['final']);
             if (isset($_POST['tickets']))
                 $this->session->set_userdata('tickets',$_POST['tickets']);
             
            
             
            
                $dato['autor']='Miguel Angel Bañolas';
                $data=array();
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionBoka', array('error' => ' ' ));
                $this->load->view('templates/footer.html',$dato);
             * 
             */
                
	}
        
        
        public function seleccionVentasProductos()
	{
            
            $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/resumenProductos";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Productos Ventas";
                $dato['agrupar']=true;
                $dato['periodosVentas']=$this->load->view('periodosVentas',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		        $this->load->view('seleccionVentasProductos',$dato );
                $this->load->view('templates/footer.html',$dato);    
                
	}
        
         public function seleccionVentasProductosPS()
	{
            
            $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/resumenProductosPS";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Productos Ventas";
                $dato['agrupar']=true;
                $dato['periodosVentasPS']=$this->load->view('periodosVentasPS',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasProductosPS',$dato );
                $this->load->view('templates/footer.html',$dato);    
                
	}
        
         public function seleccionVentasProductosVD()
	{
            
            $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/resumenProductosVD";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Productos Ventas";
                $dato['agrupar']=true;
                $dato['periodosVentasVD']=$this->load->view('periodosVentasVD',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $dato['activeMenu']='Ventas Directas';
                $dato['activeSubmenu']='Ventas directas: Productos';
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasProductosVD',$dato );
                $this->load->view('templates/footer.html',$dato);    
                
	}
        
         public function seleccionVentasProductosTotales()
	{
            
            $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/resumenProductosTotales";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Productos Ventas";
                $dato['agrupar']=true;
                $dato['periodosVentasTodo']=$this->load->view('periodosVentasTodo',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasProductosTotales',$dato );
                $this->load->view('templates/footer.html',$dato);    
                
	}
        
        
        
        public function seleccionVentasDiferenciasImportes()
	{
             $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/diferenciasDatosVentas";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Diferencias Boka Ventas";
                $dato['periodosVentas']=$this->load->view('periodosVentas',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasDiferenciasImportes',$dato );
                $this->load->view('templates/footer.html',$dato);     
           
	}
        
        
        public function seleccionVentasImportes()
	{       
                
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/datosVentas";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Importes Ventas";
                $dato['periodosVentas']=$this->load->view('periodosVentas',$dato,true);
                $dato['agrupar']=false;
                $this->load->view('templates/header.html',$dato);
                $dato['activeMenu']='Ventas Tienda';
                $dato['activeSubmenu']='Ventas: Importes e IVAs';
                $dato['activeMenu']='Ventas Tienda';
                $dato['activeSubmenu']='Ventas: Importes e IVAs';
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasImportes',$dato );
                $this->load->view('templates/footer.html',$dato);    
	}
        
        public function seleccionVentasImportesPS()
	{       
                
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/datosVentasPS";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Importes Ventas";
                $dato['periodosVentasPS']=$this->load->view('periodosVentasPS',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasImportesPS',$dato );
                $this->load->view('templates/footer.html',$dato);    
	}
        
        public function seleccionVentasImportesOnline()
	{       
                
                $dato['autor']='Miguel Angel Bañolas';
                /*
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/datosVentasPS";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Importes Ventas";
                $dato['periodosVentasPS']=$this->load->view('periodosVentasPS',$dato,true);
                 * 
                 */
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasImportesOnline',$dato );
                $this->load->view('templates/footer.html',$dato);    
	}
       
        
        
        public function seleccionVentasImportesVD()
	{       
                
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/datosVentasVD";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Importes Ventas";
                $dato['periodosVentasVD']=$this->load->view('periodosVentasVD',$dato,true);
                $this->load->view('templates/header.html',$dato);
               // $dato['activeMenu']='Ventas Directas';
               // $dato['activeSubmenu']='Ventas directas: Importes e IVAs';
                
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasImportesVD',$dato );
                $this->load->view('templates/footer.html',$dato);    
	}
        
        public function seleccionVentasImportesTotales()
	{       
                
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/datosVentasTodo";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Importes Ventas";
                $dato['periodosVentasTodo']=$this->load->view('periodosVentasTodo',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $dato['activeMenu']='Ventas Totales';
                $dato['activeSubmenu']='Ventas totales: Importes e IVAs';
                
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasImportesTotales',$dato );
                $this->load->view('templates/footer.html',$dato);    
	}
        
        public function seleccionVentasTickets()
	{       
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/tickets";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Tickets";
                $dato['periodosVentas']=$this->load->view('periodosVentas',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $dato['activeMenu']='Ventas Tienda';
                $dato['activeSubmenu']='Listado Tickets Ventas';
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasTickets',$dato );
                $this->load->view('templates/footer.html',$dato);    
	}
        
        public function seleccionTicketsProcesados()
	{       
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
                $dato['segmentos']="listados/ticketsProcesados";
                $dato['idBoton']="listadoBoka";
                $dato['nombreBoton']="Listado Tickets";
                $dato['periodosVentas']=$this->load->view('periodosVentas',$dato,true);
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionTicketsProcesados',$dato );
                $this->load->view('templates/footer.html',$dato);    
	}
        
        
        public function seleccionVentasImportes_1()
	{
                
                $dato['autor']='Miguel Angel Bañolas';
                $data=array();
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('seleccionVentasImportes', array('error' => ' ' ));
                $this->load->view('templates/footer.html',$dato);
                
	}
        
        
        public function listaBoka()
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
            
             $inicio=$_POST['inicio'];
             $final=$_POST['final'];
            // $query='SELECT "BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS", "ZEIE", "ZEIB", "TEXT" FROM pe_boka '
            //         . ' WHERE (STYP="1" AND ZEIS>"'.$inicio.'") AND  (STYP="1" AND ZEIS<"'.$final.'")';
             
              $dato['campos']=array("BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS", "ZEIE", "ZEIB", "TEXT");
             // $dato['campos']=array("BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS");

             $campos=implode(",",$dato['campos']);
             //$query="SELECT $campos FROM pe_boka WHERE STYP='1'";
             //$dato['results']=$this->listados_->getSelect($query);
             $dato['results']=$this->listados_->getBoka($dato['campos'],$inicio,$final);
             $dato['resumen']=$this->listados_->getResumenTodos($dato['results']);
             
             $dato['inicio']=$inicio;
             $dato['final']=$final;
             //$dato['query']=$query;
             
             $dato['periodoBalanzaTodas']=$_POST['periodoBalanzaTodas'];
             $dato['periodoBalanza1']=$_POST['periodoBalanza1'];
             $dato['periodoBalanza2']=$_POST['periodoBalanza2'];
             $dato['periodoBalanza3']=$_POST['periodoBalanza3'];
             $dato['periodoManuales']=$_POST['periodoManuales'];
             
             $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
                $this->load->view('resumenBokaCabecera',$dato);
		$this->load->view('listaBoka', array('error' => ' ' ));
               // $this->load->view('resumenBoka', array('error' => ' ' ));
                $this->load->view('templates/footer.html',$dato);
         }
         
         
         
          public function resumenDiferenciasBoka()
        {
              
              
              
              
              
              
              
             // guardamos selección en session
             if (isset($_POST['periodo']))
                 $this->session->set_userdata('periodo',$_POST['periodo']);
             if (isset($_POST['inicio']))
                 $this->session->set_userdata('inicio',$_POST['inicio']);
             if (isset($_POST['final']))
                 $this->session->set_userdata('final',$_POST['final']);
             if (isset($_POST['tickets']))
                 $this->session->set_userdata('tickets',$_POST['tickets']);
             //var_dump($_POST);
             $inicio=$_POST['inicio'];
             $final=$_POST['final'];
            // $query='SELECT "BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS", "ZEIE", "ZEIB", "TEXT" FROM pe_boka '
            //         . ' WHERE (STYP="1" AND ZEIS>"'.$inicio.'") AND  (STYP="1" AND ZEIS<"'.$final.'")';
             
              //$dato['campos']=array("BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS", "ZEIE", "ZEIB", "TEXT");
              $dato['campos']=array("BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS");

             $campos=implode(",",$dato['campos']);
             //$query="SELECT $campos FROM pe_boka WHERE STYP='1'";
             //$dato['results']=$this->listados_->getSelect($query);
            
             $dato['results']=$this->listados_->getBoka($dato['campos'],$inicio,$final);
             $dato['resumenTodos']=$this->listados_->getResumenTodos($dato['results']);
             $dato['resumenTarjetas']=$this->listados_->getResumenTarjetas($dato['results']);
             $dato['resumenMetalico']=$this->listados_->getResumenMetalico($dato['results']);
             //var_dump($dato['results']);
             
             $dato['results']=$this->listados_->getBoka2($dato['campos'],$inicio,$final);
             $dato['resumenTodos2']=$this->listados_->getResumenTodos($dato['results']);
             $dato['resumenTarjetas2']=$this->listados_->getResumenTarjetas($dato['results']);
             $dato['resumenMetalico2']=$this->listados_->getResumenMetalico($dato['results']);
             
             
             $dato['inicio']=$inicio;
             $dato['final']=$final;
             $dato['error']=' ';
             //$dato['query']=$query;
             
             $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
                $this->load->view('resumenBokaDiferenciasCabecera',$dato);
                $this->load->view('resumenBokaDiferenciasTarjetas',$dato);
                $this->load->view('resumenBokaDiferenciasMetalico',$dato);
		$this->load->view('resumenBokaDiferenciasTodos',$dato);
                $this->load->view('resumenBokaDiferenciasPie',$dato);
                $this->load->view('templates/footer.html',$dato);
         }
         
        public function datosVentas(){
             
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
            
            $dato['periodoBalanzaTodas']=$_POST['periodoBalanzaTodas'];
            $dato['periodoBalanza1']=$_POST['periodoBalanza1'];
            $dato['periodoBalanza2']=$_POST['periodoBalanza2'];
            $dato['periodoBalanza3']=$_POST['periodoBalanza3'];
            $dato['periodoManuales']=$_POST['periodoManuales'];
            
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
            $dato['resultsTodas']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotal($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTodas1']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotal($dato['inicio'],$dato['final'],'pe_boka',1);
            
            $dato['resultsTodasTotales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotales($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTodasTotales1']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotales($dato['inicio'],$dato['final'],'pe_boka',1);

            $dato['resultsTodasStyp6']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalSTYP6($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTodasStyp61']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalSTYP6($dato['inicio'],$dato['final'],'pe_boka',1);
            
            $dato['resultsTodasTotalesStyp6']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesSTYP6($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTodasTotalesStyp61']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesSTYP6($dato['inicio'],$dato['final'],'pe_boka',1);
          
            $dato['getDatosVentasBokaTotalesFormaPago']=$this->listados_->getDatosVentasBokaTotalesFormaPago($dato['inicio'],$dato['final'],'pe_boka');
            $dato['getDatosVentasBokaTotalesFormaPago1']=$this->listados_->getDatosVentasBokaTotalesFormaPago($dato['inicio'],$dato['final'],'pe_boka',1);

            
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
            $this->load->view('resumenBokaCabecera',$dato);
            $this->load->view('datosVentas',$dato);
            $this->load->view('templates/footer.html',$dato);
            
        } 
        
        
        
        public function datosVentasPS(){
             
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
            

             
            $dato['desde']=$_POST['desde'];
            $dato['hasta']=$_POST['hasta'];
            
            
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            //echo $dato['inicio'].'<br>';
            //echo $dato['final'].'<br>';

            
            $this->load->model('prestashop_model');
            $desde=$dato['desde'];
            $hasta=$dato['hasta'];
            //$dato['resultados']=$this->prestashop_model->getVentasPorTiposIva($dato['desde'],$dato['hasta']);
            $dato['resultados']=$this->prestashop_model->getVentasPorTiposIvaEntreFechas($dato['inicio'],$dato['final']);
            //$dato['transportes']=$this->prestashop_model->getTransportesPorTiposIva($desde,$hasta);
            $dato['transportes']=$this->prestashop_model->getTransportesPorTiposIvaEntreFechas($dato['inicio'],$dato['final']);

           // $dato['resultadosTransportes']=$this->prestashop_model->getResultadosTransportesPorTiposIva($dato['desde'],$dato['hasta']);
            
            
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
            //$this->load->view('resumenBokaCabecera',$dato);
            $this->load->view('datosVentasPS',$dato);
            $this->load->view('templates/footer.html',$dato);
            
        } 
        
        public function datosVentasVD(){
             
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
            
            $dato['desde']=$_POST['desde'];
            $dato['hasta']=$_POST['hasta'];
            
            
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
            
            $this->load->model('directas_model');
            $dato['resultados']=$this->directas_model->getVentasPorTiposIva($dato['desde'],$dato['hasta']);
            //var_dump( $dato['resultados']);
            /*
            $dato['resultsTodas']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotal($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTodas1']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotal($dato['inicio'],$dato['final'],'pe_boka',1);
            
            $dato['resultsTodasTotales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotales($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTodasTotales1']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotales($dato['inicio'],$dato['final'],'pe_boka',1);

            $dato['resultsTodasStyp6']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalSTYP6($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTodasStyp61']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalSTYP6($dato['inicio'],$dato['final'],'pe_boka',1);
            
            $dato['resultsTodasTotalesStyp6']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesSTYP6($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTodasTotalesStyp61']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesSTYP6($dato['inicio'],$dato['final'],'pe_boka',1);
          
            $dato['getDatosVentasBokaTotalesFormaPago']=$this->listados_->getDatosVentasBokaTotalesFormaPago($dato['inicio'],$dato['final'],'pe_boka');
            $dato['getDatosVentasBokaTotalesFormaPago1']=$this->listados_->getDatosVentasBokaTotalesFormaPago($dato['inicio'],$dato['final'],'pe_boka',1);
*/
            
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
            //$this->load->view('resumenBokaCabecera',$dato);
            $this->load->view('datosVentasVD',$dato);
            $this->load->view('templates/footer.html',$dato);
            
        } 
        
         public function datosVentasTodo(){
             
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
            
             
            //$dato['desde']=$_POST['desde'];
            //$dato['hasta']=$_POST['hasta'];
            
            
            //ventas tickets
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            $dato['resultsTodas']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotal($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTodasTotales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotales($dato['inicio'],$dato['final'],'pe_boka');
            
            $tipo_iva=array();
            $base=array();
            $iva=array();
            $total=array();
            $resultadosTienda=array();
            $totalBase=0;
            $totalIva=0;
            $totalTotal=0;
            foreach($dato['resultsTodas'] as $k=>$v){
                if(array_key_exists($v->tipos,$tipo_iva)) $tipo_iva[$v->tipos]+=$v->tipos;
                else $tipo_iva[$v->tipos]=$v->tipos;
                if(array_key_exists($v->tipos,$base)) $base[$v->tipos]+=$v->bases;
                else $base[$v->tipos]=$v->bases;
                if(array_key_exists($v->tipos,$iva)) $iva[$v->tipos]+=$v->ivas;
                else $iva[$v->tipos]=$v->ivas;
                if(array_key_exists($v->tipos,$total)) $total[$v->tipos]+=$v->totales;
                else $total[$v->tipos]=$v->totales;
            }
            foreach($tipo_iva as $k=>$v){
                $resultadosTienda['resultados'][]=array('tipo_iva'=>$v,'base'=>$base[$v],'iva'=>$iva[$v],'total'=>$total[$v]);
                $totalBase+=$base[$v];
                $totalIva+=$iva[$v];
                $totalTotal+=$total[$v];
                
            }
            $resultadosTienda['resultadosTotal']=array('base'=>$totalBase,'iva'=>$totalIva,'total'=>$totalTotal);

           
            /*
            //ventas online
            $this->load->model('prestashop_model');
            $pedidos=$this->prestashop_model->getPedidosPS($_POST['inicio'], $_POST['final']);
            $dato['desde']=$pedidos['primerPedido'];
            $dato['hasta']=$pedidos['ultimoPedido'];
            $resultadosPS=$this->prestashop_model->getVentasPorTiposIva($dato['desde'],$dato['hasta']);
            */
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            //echo $dato['inicio'].'<br>';
            //echo $dato['final'].'<br>';

            
            $this->load->model('prestashop_model');
            //$desde=$dato['desde'];
            //$hasta=$dato['hasta'];
            //$dato['resultados']=$this->prestashop_model->getVentasPorTiposIva($dato['desde'],$dato['hasta']);
            $dato['resultados']=$this->prestashop_model->getVentasPorTiposIvaEntreFechas($dato['inicio'],$dato['final']);
            //$dato['transportes']=$this->prestashop_model->getTransportesPorTiposIva($desde,$hasta);
            $dato['transportes']=$this->prestashop_model->getTransportesPorTiposIvaEntreFechas($dato['inicio'],$dato['final']);
            $resultadosPS=$dato['resultados'];
            $resultadosPST=$dato['transportes'];
            
            
            //ventas Venta Directa
            $this->load->model('directas_model');
            $pedidos=$this->directas_model->getPedidosVD($_POST['inicio'], $_POST['final']);
            $dato['desde']=$pedidos['primerPedido'];
            $dato['hasta']=$pedidos['ultimoPedido'];
            $resultadosVD=$this->directas_model->getVentasPorTiposIva($dato['desde'],$dato['hasta']);
            
            $base=array();
            $iva=array();
            $total=array();
            if(isset($resultadosTienda['resultados']))
                foreach($resultadosTienda['resultados'] as $k=>$v){
               if(!array_key_exists($v['tipo_iva'],$base)) {
                   $base[$v['tipo_iva']]=$v['base'];
               }
               else{
                   $base[$v['tipo_iva']]+=$v['base'];
               }
               if(!array_key_exists($v['tipo_iva'],$iva)) {
                   $iva[$v['tipo_iva']]=$v['iva'];
               }
               else{
                   $iva[$v['tipo_iva']]+=$v['iva'];
               }
               if(!array_key_exists($v['tipo_iva'],$total)) {
                   $total[$v['tipo_iva']]=$v['total'];
               }
               else{
                   $total[$v['tipo_iva']]+=$v['total'];
               }
            }
            if(isset($resultadosVD['resultados']))
                foreach($resultadosVD['resultados'] as $k=>$v){
               if(!array_key_exists($v['tipo_iva'],$base)) {
                   $base[$v['tipo_iva']]=$v['base'];
               }
               else{
                   $base[$v['tipo_iva']]+=$v['base'];
               }
               if(!array_key_exists($v['tipo_iva'],$iva)) {
                   $iva[$v['tipo_iva']]=$v['iva'];
               }
               else{
                   $iva[$v['tipo_iva']]+=$v['iva'];
               }
               if(!array_key_exists($v['tipo_iva'],$total)) {
                   $total[$v['tipo_iva']]=$v['total'];
               }
               else{
                   $total[$v['tipo_iva']]+=$v['total'];
               }
            }
            if(isset($resultadosPS['resultados']))
                foreach($resultadosPS['resultados'] as $k=>$v){
               if(!array_key_exists($v['tipo_iva'],$base)) {
                   $base[$v['tipo_iva']]=$v['base'];
               }
               else{
                   $base[$v['tipo_iva']]+=$v['base'];
               }
               if(!array_key_exists($v['tipo_iva'],$iva)) {
                   $iva[$v['tipo_iva']]=$v['iva'];
               }
               else{
                   $iva[$v['tipo_iva']]+=$v['iva'];
               }
               if(!array_key_exists($v['tipo_iva'],$total)) {
                   $total[$v['tipo_iva']]=$v['total'];
               }
               else{
                   $total[$v['tipo_iva']]+=$v['total'];
               }
            }
            
            if(isset($resultadosPST['resultados']))
                foreach($resultadosPST['resultados'] as $k=>$v){
               if(!array_key_exists($v['tipo_iva_transporte']/10,$base)) {
                   $base[$v['tipo_iva_transporte']/10]=$v['base_transporte']/10;
               }
               else{
                   $base[$v['tipo_iva_transporte']/10]+=$v['base_transporte']/10;
               }
               if(!array_key_exists($v['tipo_iva_transporte']/10,$iva)) {
                   $iva[$v['tipo_iva_transporte']/10]=$v['iva_transporte']/10;
               }
               else{
                   $iva[$v['tipo_iva_transporte']/10]+=$v['iva_transporte']/10;
               }
               if(!array_key_exists($v['tipo_iva_transporte']/10,$total)) {
                   $total[$v['tipo_iva_transporte']/10]=$v['transporte']/10;
               }
               else{
                   $total[$v['tipo_iva_transporte']/10]+=$v['transporte']/10;
               }
            }
            
            
            $totalBase=0;
            $totalIva=0;
            $totalTotal=0;
            $dato['resultados']['resultados']=array();
            foreach($base as $k=>$v){
                $dato['resultados']['resultados'][]=array('tipo_iva'=>$k,'base'=>$base[$k],'iva'=>$iva[$k],'total'=>$total[$k]);
                $totalBase+=$base[$k];
                $totalIva+=$iva[$k];
                $totalTotal+=$total[$k];
            }
            $dato['resultados']['resultadosTotal']=array('base'=>$totalBase,'iva'=>$totalIva,'total'=>$totalTotal);

            
            
            
             //$dato['resultados']=$resultadosTienda;
             
             //var_dump($dato['resultados']);
            
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
            //$this->load->view('resumenBokaCabecera',$dato);
            $this->load->view('datosVentasTodo',$dato);
            $this->load->view('templates/footer.html',$dato);
            
        } 
        
        
          public function tickets(){
            
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
            
            $dato['periodoBalanzaTodas']=$_POST['periodoBalanzaTodas'];
             $dato['periodoBalanza1']=$_POST['periodoBalanza1'];
             $dato['periodoBalanza2']=$_POST['periodoBalanza2'];
             $dato['periodoBalanza3']=$_POST['periodoBalanza3'];
            $dato['periodoManuales']=$_POST['periodoManuales'];
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
            $dato['resultsTickets']=$this->listados_->getDatosTickets($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTicketsTotales']=$this->listados_->getDatosTicketsTotales($dato['inicio'],$dato['final'],'pe_boka');

            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
            $this->load->view('resumenBokaCabecera',$dato);
            $this->load->view('myModalTicket',$dato);
            $this->load->view('datosVentasTickets',$dato);
            $this->load->view('templates/footer.html',$dato);
            
        } 
        
        
        
        
        
        
        
        
         public function ticketsProcesados(){
            
             
            
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
            
            $dato['periodoBalanzaTodas']=$_POST['periodoBalanzaTodas'];
             $dato['periodoBalanza1']=$_POST['periodoBalanza1'];
             $dato['periodoBalanza2']=$_POST['periodoBalanza2'];
             $dato['periodoBalanza3']=$_POST['periodoBalanza3'];
            $dato['periodoManuales']=$_POST['periodoManuales'];
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
             $dato['results']=$this->listados_->prepararBokaMerge($dato['inicio'],$dato['final']);
            
            
            $dato['resultsTickets']=$this->listados_->getDatosTickets($dato['inicio'],$dato['final'],'pe_boka');
            $dato['resultsTicketsTotales']=$this->listados_->getDatosTicketsTotales($dato['inicio'],$dato['final'],'pe_boka');

             $dato['resultsTickets2']=$this->listados_->getDatosTickets($dato['inicio'],$dato['final'],'pe_bokaMerged');
            $dato['resultsTicketsTotales2']=$this->listados_->getDatosTicketsTotales($dato['inicio'],$dato['final'],'pe_bokaMerged');

            
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
            $this->load->view('resumenBokaCabecera',$dato);
            $this->load->view('datosTicketsProcesados',$dato);
            
            $this->load->view('templates/footer.html',$dato);
            
        } 
        
        
        
        
        function interval_date($init,$finish)
{
    //formateamos las fechas a segundos tipo 1374998435
    $diferencia = strtotime($finish) - strtotime($init);
 
    //comprobamos el tiempo que ha pasado en segundos entre las dos fechas
    //floor devuelve el número entero anterior, si es 5.7 devuelve 5
    if($diferencia < 60){
        $tiempo = "Hace " . floor($diferencia) . " segundos";
    }else if($diferencia > 60 && $diferencia < 3600){
        $tiempo = "Hace " . floor($diferencia/60) . " minutos'";
    }else if($diferencia > 3600 && $diferencia < 86400){
        $tiempo = "Hace " . floor($diferencia/3600) . " horas";
    }else if($diferencia > 86400 && $diferencia < 2592000){
        $tiempo = "Hace " . floor($diferencia/86400) . " días";
    }else if($diferencia > 2592000 && $diferencia < 31104000){
        $tiempo = "Hace " . floor($diferencia/2592000) . " meses";
    }else if($diferencia > 31104000){
        $tiempo = "Hace " . floor($diferencia/31104000) . " años";
    }else{
        $tiempo = "Error";
    }
    return $tiempo;
}
 
         
        public function diferenciasDatosVentas(){
            
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
            
            $dato['periodoBalanzaTodas']=$_POST['periodoBalanzaTodas'];
             $dato['periodoBalanza1']=$_POST['periodoBalanza1'];
             $dato['periodoBalanza2']=$_POST['periodoBalanza2'];
             $dato['periodoBalanza3']=$_POST['periodoBalanza3'];
            $dato['periodoManuales']=$_POST['periodoManuales'];
            
             $dato['inicio']=$_POST['inicio'];
             $dato['final']=$_POST['final'];
             
            
            //$t1=(date('d/m/Y  H:s'));
            $dato['results']=$this->listados_->prepararBokaMerge($dato['inicio'],$dato['final']);
            //echo 'Terminado';
            //$t2=(date('d/m/Y  H:s'));
           
            //echo  $this->interval_date($t2,$t1);
            $archivo='pe_boka';
            $dato['resultsTodas']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotal($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTodasTotales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotales($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsNoMetalico']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalNoMetalico($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsNoMetalicoTotales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesNoMetalico($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTarjetas']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTarjetas($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTarjetasTotales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesTarjetas($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsVales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalVales($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsValesTotales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesVales($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsACuenta']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalACuenta($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsACuentaTotales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesACuenta($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTransferencias']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTransferencias($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTransferenciasTotales']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesTransferencias($dato['inicio'],$dato['final'],$archivo);
            
            $archivo='pe_bokaMerged';
            $dato['resultsTodas2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotal($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTodasTotales2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotales($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsNoMetalico2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalNoMetalico($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsNoMetalicoTotales2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesNoMetalico($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTarjetas2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTarjetas($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTarjetasTotales2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesTarjetas($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsVales2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalVales($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsValesTotales2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesVales($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsACuenta2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalACuenta($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsACuentaTotales2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesACuenta($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTransferencias2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTransferencias($dato['inicio'],$dato['final'],$archivo);
            $dato['resultsTransferenciasTotales2']=$this->listados_->getDatosVentasBokaTipoBaseIvaTotalTotalesTransferencias($dato['inicio'],$dato['final'],$archivo);
            
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php',$dato);
            $this->load->view('resumenBokaCabecera',$dato);
            $this->load->view('diferenciaDatosVentas',$dato);
            $this->load->view('diferenciaDatosVentasMetalico',$dato);
            $this->load->view('diferenciaDatosVentasTarjetas',$dato);
            $this->load->view('diferenciaDatosVentasVales',$dato);
            $this->load->view('diferenciaDatosVentasACuenta',$dato);
            $this->load->view('diferenciaDatosVentasTransferencias',$dato);
            $this->load->view('templates/footer.html',$dato);
        } 
        
        public function resumenBoka()
        {
            
             // guardamos selección en session
             if (isset($_POST['periodo']))
                 $this->session->set_userdata('periodo',$_POST['periodo']);
             if (isset($_POST['inicio']))
                 $this->session->set_userdata('inicio',$_POST['inicio']);
             if (isset($_POST['final']))
                 $this->session->set_userdata('final',$_POST['final']);
             if (isset($_POST['tickets']))
                 $this->session->set_userdata('tickets',$_POST['tickets']);
             //var_dump($_POST);
             $inicio=$_POST['inicio'];
             $final=$_POST['final'];
            // $query='SELECT "BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS", "ZEIE", "ZEIB", "TEXT" FROM pe_boka '
            //         . ' WHERE (STYP="1" AND ZEIS>"'.$inicio.'") AND  (STYP="1" AND ZEIS<"'.$final.'")';
             
              //$dato['campos']=array("BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS", "ZEIE", "ZEIB", "TEXT");
              $dato['campos']=array("BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS");

             $campos=implode(",",$dato['campos']);
             //$query="SELECT $campos FROM pe_boka WHERE STYP='1'";
             //$dato['results']=$this->listados_->getSelect($query);
             $dato['results']=$this->listados_->getBoka($dato['campos'],$inicio,$final);
             $dato['resumenTodos']=$this->listados_->getResumenTodos($dato['results']);
             $dato['resumenTarjetas']=$this->listados_->getResumenTarjetas($dato['results']);
             $dato['resumenMetalico']=$this->listados_->getResumenMetalico($dato['results']);
             
             $dato['inicio']=$inicio;
             $dato['final']=$final;
             $dato['error']=' ';
             //$dato['query']=$query;
             
             $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
                $this->load->view('resumenBokaCabecera',$dato);
                $this->load->view('resumenBokaTarjetas',$dato);
                $this->load->view('resumenBokaMetalico',$dato);
		$this->load->view('resumenBokaTodos',$dato);
                $this->load->view('resumenBokaPie',$dato);
                $this->load->view('templates/footer.html',$dato);
         }
         
        public function resumenProductos()
        {   
            
              $agrupar=false;
              if (isset($_POST['agrupar'])) $agrupar=true;
             // guardamos selección en session
             if (isset($_POST['periodo']))
                 $this->session->set_userdata('periodo',$_POST['periodo']);
             if (isset($_POST['inicio']))
                 $this->session->set_userdata('inicio',$_POST['inicio']);
             if (isset($_POST['final']))
                 $this->session->set_userdata('final',$_POST['final']);
             if (isset($_POST['tickets']))
                 $this->session->set_userdata('tickets',$_POST['tickets']);
             
             //var_dump($_POST);
             $inicio=$_POST['inicio'];
             $final=$_POST['final'];
            // $query='SELECT "BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS", "ZEIE", "ZEIB", "TEXT" FROM pe_boka '
            //         . ' WHERE (STYP="1" AND ZEIS>"'.$inicio.'") AND  (STYP="1" AND ZEIS<"'.$final.'")';
             
              $dato['campos']=array("BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS", "ZEIE", "ZEIB", "TEXT");
              $dato['campos']=array("BONU", "BONU2", "STYP", "ABNU", "WANU", "BEN1", "BEN2", "SNR1", "GPTY", "PNAB", "WGNU", "BT10", "BT12", "BT20", "POS1", "POS4", "GEW1", "BT40", "MWNU", "MWTY", "PRUD", "PAR1", "PAR2", "PAR3", "PAR4", "PAR5", "STST", "PAKT", "POS2", "MWUD", "BT13", "RANU", "RATY", "BT30", "BT11", "POS3", "GEW2", "SNR2", "SNR3", "VART", "BART", "KONU", "RASA", "ZAPR", "ZAWI", "MWSA", "ZEIS","SNR1");

             $campos=implode(",",$dato['campos']);

          ///   $dato['results']=$this->listados_->getBoka($dato['campos'],$inicio,$final);
          ///   $dato['resumenProductos']=$this->listados_->getResumenProductos($dato['results']);
             
             $dato['productos']=$this->listados_->getProductos72($inicio,$final,$agrupar);
             
             $dato['productosTotales']=$this->listados_->getProductosTotales72($inicio,$final);
             

             $dato['inicio']=$inicio;
             $dato['final']=$final;
             $dato['error']=' ';
             
            
             $dato['periodoBalanzaTodas']=$_POST['periodoBalanzaTodas'];
             $dato['periodoBalanza1']=$_POST['periodoBalanza1'];
             $dato['periodoBalanza2']=$_POST['periodoBalanza2'];
             $dato['periodoBalanza3']=$_POST['periodoBalanza3'];
             $dato['periodoManuales']=$_POST['periodoManuales'];
            
             
                $this->load->view('templates/header.html',$dato);
                
                $this->load->view('templates/top.php',$dato);
                //$this->load->view('resumenBokaCabecera',$dato);
                $this->load->view('resumenBokaProductosImportes',$dato);
                $this->load->view('templates/footer.html',$dato);
                $this->load->view('myModal',$dato);
         }

         public function resumenProductosPSExcel($inicio,$final,$desde,$hasta,$agrupar)
        {
            if($agrupar==0) $agrupar=false; else $agrupar=true;
            
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
             
             
            
            $dato['desde']=$_POST['desde'];
            $dato['hasta']=$_POST['hasta'];
            
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
            
            $this->load->model('prestashop_model');
            
            //$dato['resumenProductos']=$this->prestashop_model->getVentasPorProducto($dato['desde'],$dato['hasta']); 
            
            $dato['productos']=$this->prestashop_model->getProductos($dato['desde'],$dato['hasta'],$agrupar);
            
            
            $dato['productosTotales']=$this->prestashop_model->getProductosTotales($dato['desde'],$dato['hasta']); 
            
            $this->load->model('productos_');
            //$dato['productos']=$this->productos_->ordenarArray($dato['productos']);
            
             $dato['agrupar']=$agrupar;
             
             return $dato['productos'];
            
         }  
         
          public function resumenProductosExcel($inicio,$final,$agrupar)
        {
            if($agrupar==0) $agrupar=false; else $agrupar=true;
            
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
             
             
            
            //$dato['desde']=$_POST['desde'];
            //$dato['hasta']=$_POST['hasta'];
            
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
            
            $this->load->model('prestashop_model');
            
            //$dato['resumenProductos']=$this->prestashop_model->getVentasPorProducto($dato['desde'],$dato['hasta']); 
            
            
            
            $productos=$this->listados_->getProductos($inicio,$final,$agrupar);
            $dato['productos']=array();
                
            foreach($productos as $k=>$v){
                    $dato['productos'][]=$v;
                }
            
            $this->load->model('productos_');
            //$dato['productos']=$this->productos_->ordenarArray($dato['productos']);
            
             $dato['agrupar']=$agrupar;
             
             return $dato['productos'];
            
         }  
         
         
        public function resumenProductosPS()
        {
            $agrupar=false;
              if (isset($_POST['agrupar'])) $agrupar=true;
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
             
             
            
            $dato['desde']=$_POST['desde'];
            $dato['hasta']=$_POST['hasta'];
            
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
            
            $this->load->model('prestashop_model');
            
            //$dato['resumenProductos']=$this->prestashop_model->getVentasPorProducto($dato['desde'],$dato['hasta']); 
            
            $dato['productos']=$this->prestashop_model->getProductos($dato['desde'],$dato['hasta'],$agrupar);
            //var_dump($dato['productos']);
            $dato['productosTotales']=$this->prestashop_model->getProductosTotales($dato['desde'],$dato['hasta']); 
            
            $this->load->model('productos_');
            //$dato['productos']=$this->productos_->ordenarArray($dato['productos']);
            
             $dato['agrupar']=$agrupar;
              
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
                $this->load->view('resumenPrestaShopCabecera',$dato);
                $this->load->view('resumenPrestaShopProductosImportes',$dato);
                $this->load->view('templates/footer.html',$dato);
                $this->load->view('myModal',$dato);
         }  
         
         
         public function resumenProductosVD()
        {
            $agrupar=false;
              if (isset($_POST['agrupar'])) $agrupar=true;
              
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
             
             
            
            $dato['desde']=$_POST['desde'];
            $dato['hasta']=$_POST['hasta'];
            
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
            
            $this->load->model('directas_model');
            
            //$dato['resumenProductos']=$this->prestashop_model->getVentasPorProducto($dato['desde'],$dato['hasta']); 
            
            $dato['productos']=$this->directas_model->getProductos($dato['desde'],$dato['hasta'],$agrupar); 
            $dato['productosTotales']=$this->directas_model->getProductosTotales($dato['desde'],$dato['hasta']); 

            $this->load->model('productos_');
            
            if(!$agrupar)
                     $dato['productos']=$this->productos_->ordenarArray($dato['productos']);
           //$dato['productos']=$this->productos_->ordenarArray($dato['productos']);

                $dato['agrupar']=$agrupar;
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
                $this->load->view('resumenVentasDirectasCabecera',$dato);
                $this->load->view('resumenVentasDirectasProductosImportes',$dato);
                $this->load->view('templates/footer.html',$dato);
                $this->load->view('myModal',$dato);
         }  
         
          public function resumenProductosVDExcel($desde,$hasta,$agrupar)
        {
            if($agrupar==0) $agrupar=false; else $agrupar=true;
              
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
             
             
            
            $dato['desde']=$_POST['desde'];
            $dato['hasta']=$_POST['hasta'];
            
            
           // $dato['inicio']=$_POST['inicio'];
           // $dato['final']=$_POST['final'];
            
            
            $this->load->model('directas_model');
            
            //$dato['resumenProductos']=$this->prestashop_model->getVentasPorProducto($dato['desde'],$dato['hasta']); 
            
            $dato['productos']=$this->directas_model->getProductos($dato['desde'],$dato['hasta'],$agrupar); 
            $dato['productosTotales']=$this->directas_model->getProductosTotales($dato['desde'],$dato['hasta']); 

            $this->load->model('productos_');
            
            if(!$agrupar)
                     $dato['productos']=$this->productos_->ordenarArray($dato['productos']);
           //$dato['productos']=$this->productos_->ordenarArray($dato['productos']);

                $dato['agrupar']=$agrupar;
               
             return $dato['productos'];
         }  
         
         public function resumenProductosTotales()
        {
             $agrupar=false;
              if (isset($_POST['agrupar'])) $agrupar=true;
              
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
             
             
            
            //$dato['desde']=$_POST['desde'];
            //$dato['hasta']=$_POST['hasta'];
            
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
            
            //ventas online
            $this->load->model('prestashop_model');
            $pedidos=$this->prestashop_model->getPedidosPS($_POST['inicio'], $_POST['final']);
            $dato['desde']=$pedidos['primerPedido'];
            $dato['hasta']=$pedidos['ultimoPedido'];
         
            $productosPS=$this->prestashop_model->getProductos($dato['desde'],$dato['hasta'],$agrupar); 
            $productosTotalesPS=$this->prestashop_model->getProductosTotales($dato['desde'],$dato['hasta']); 

            $this->load->model('directas_model');
            $pedidos=$this->directas_model->getPedidosVD($_POST['inicio'], $_POST['final']);
            $dato['desde']=$pedidos['primerPedido'];
            $dato['hasta']=$pedidos['ultimoPedido'];
         
            $productosVD=$this->directas_model->getProductos($dato['desde'],$dato['hasta'],$agrupar); 
            $productosTotalesVD=$this->directas_model->getProductosTotales($dato['desde'],$dato['hasta']); 
            
            $productosTienda=$this->listados_->getProductos($dato['inicio'],$dato['final'],$agrupar);
            $productosTotalesTienda=$this->listados_->getProductosTotales($dato['inicio'],$dato['final']);
            
            
            
            
            
            
        $dato['productos']=array();
            
            foreach($productosPS as $k=>$v){
                $key=$v['codigo'];
                if(isset($dato['productos'][$key])){
                    $dato['productos'][$key]=array(
                        //'id_producto'=>$v->id_producto,
                        'pedidos'=>$dato['productos'][$key]['pedidos']+$v->pedidos,
                        'nombre'=>$v->nombre,
                        'codigo'=>$v->codigo,
                        'unidades'=>$dato['productos'][$key]['unidades']+$v->unidades,
                        'importe'=>$dato['productos'][$key]['importe']+$v->importe,
                    );
                }
                else {
                    $dato['productos'][$key]=$v;
                    $dato['productos'][$key]['peso']=0;
                }
            }
            
            $this->load->model('productos_');
           
            foreach($productosTienda as $k=>$v){
                $key=$v['codigo'];
                if(isset($dato['productos'][$key])){
                    $dato['productos'][$key]=array(
                        //'id_producto'=>$dato['productos'][$key]['id_producto'],
                        'pedidos'=>$dato['productos'][$key]['pedidos'],
                        'peso'=>$dato['productos'][$key]['peso']+$v['peso'],
                        'nombre'=>$v['nombre'],
                        'codigo'=>$v['codigo'],
                        'unidades'=>$dato['productos'][$key]['unidades']+$v['unidades'],
                        'importe'=>$dato['productos'][$key]['importe']+$v['importe'],
                    );
                }
                else {
                    $dato['productos'][$key]=$v;
                    $dato['productos'][$key]['pedidos']=0;
                    //$id_producto=$this->productos_->getIdProducto($key);
                    //$dato['productos'][$key]['id_producto']=$id_producto;
                }
            }
            
           //  var_dump($productosVD);
            foreach($productosVD as $k=>$v){
               $key=$v['codigo'];
                if(isset($dato['productos'][$key])){
                    $dato['productos'][$key]=array(
                       // 'id_producto'=>$dato['productos'][$key]['id_producto'],
                        'pedidos'=>$dato['productos'][$key]['pedidos']+$v['pedidos'],
                        'peso'=>$dato['productos'][$key]['peso'],
                        'nombre'=>$v['nombre'],
                        'codigo'=>$v['codigo'],
                        'unidades'=>$dato['productos'][$key]['unidades']+$v['unidades'],
                        'importe'=>$dato['productos'][$key]['importe']+$v['importe'],
                    );
                }
                else {
                    $dato['productos'][$key]=$v;
                    $dato['productos'][$key]['peso']=0;
                    //$id_producto=$this->productos_->getIdProducto($key);
                    //$dato['productos'][$key]['id_producto']=$id_producto;
                }
            }
            //var_dump($dato['productos']);
            //if(!$agrupar)
                $dato['productos']=$this->productos_->ordenarArray($dato['productos']);
            
            $totalCodigos=0;
            $totalUnidades=0;
            $totalPesos=0;
            $totalImportes=0;
            foreach($dato['productos'] as $k=>$v){
                $totalCodigos+=1;
                $totalUnidades+=$v['unidades'];
                if(isset($v['peso'])) $totalPesos+=$v['peso'];
                $totalImportes+=$v['importe'];
            }
            $dato['productosTotales']=array('unidades'=>$totalUnidades,'peso'=>$totalPesos,'importe'=>$totalImportes);
            
            
           
            
            
            
            
            
            
          //  var_dump($dato['productosTotales']);
            
                 $dato['agrupar']=$agrupar;
            
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
                $this->load->view('resumenTotalesCabecera',$dato);
                $this->load->view('resumenTotalesProductosImportes',$dato);
                $this->load->view('templates/footer.html',$dato);
         }  
         
          public function resumenProductosTotalesExcel($inicio,$final,$desde,$hasta,$agrupar)
        {
             if($agrupar==0) $agrupar=false; else $agrupar=true;
              
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
             
             
            
            //$dato['desde']=$_POST['desde'];
            //$dato['hasta']=$_POST['hasta'];
            
            
            $dato['inicio']=$_POST['inicio'];
            $dato['final']=$_POST['final'];
            
            
            //ventas online
            $this->load->model('prestashop_model');
            $pedidos=$this->prestashop_model->getPedidosPS($_POST['inicio'], $_POST['final']);
            $dato['desde']=$pedidos['primerPedido'];
            $dato['hasta']=$pedidos['ultimoPedido'];
         
            $productosPS=$this->prestashop_model->getProductos($dato['desde'],$dato['hasta'],$agrupar); 
            $productosTotalesPS=$this->prestashop_model->getProductosTotales($dato['desde'],$dato['hasta']); 

            $this->load->model('directas_model');
            $pedidos=$this->directas_model->getPedidosVD($_POST['inicio'], $_POST['final']);
            $dato['desde']=$pedidos['primerPedido'];
            $dato['hasta']=$pedidos['ultimoPedido'];
         
            $productosVD=$this->directas_model->getProductos($dato['desde'],$dato['hasta'],$agrupar); 
            $productosTotalesVD=$this->directas_model->getProductosTotales($dato['desde'],$dato['hasta']); 
            
            $productosTienda=$this->listados_->getProductos($dato['inicio'],$dato['final'],$agrupar);
            $productosTotalesTienda=$this->listados_->getProductosTotales($dato['inicio'],$dato['final']);
            
            
            
            
            
            
        $dato['productos']=array();
            
            foreach($productosPS as $k=>$v){
                $key=$v['codigo'];
                if(isset($dato['productos'][$key])){
                    $dato['productos'][$key]=array(
                        //'id_producto'=>$v->id_producto,
                        'pedidos'=>$dato['productos'][$key]['pedidos']+$v->pedidos,
                        'nombre'=>$v->nombre,
                        'codigo'=>$v->codigo,
                        'unidades'=>$dato['productos'][$key]['unidades']+$v->unidades,
                        'importe'=>$dato['productos'][$key]['importe']+$v->importe,
                    );
                }
                else {
                    $dato['productos'][$key]=$v;
                    $dato['productos'][$key]['peso']=0;
                }
            }
            
            $this->load->model('productos_');
           
            foreach($productosTienda as $k=>$v){
                $key=$v['codigo'];
                if(isset($dato['productos'][$key])){
                    $dato['productos'][$key]=array(
                        //'id_producto'=>$dato['productos'][$key]['id_producto'],
                        'pedidos'=>$dato['productos'][$key]['pedidos'],
                        'peso'=>$dato['productos'][$key]['peso']+$v['peso'],
                        'nombre'=>$v['nombre'],
                        'codigo'=>$v['codigo'],
                        'unidades'=>$dato['productos'][$key]['unidades']+$v['unidades'],
                        'importe'=>$dato['productos'][$key]['importe']+$v['importe'],
                    );
                }
                else {
                    $dato['productos'][$key]=$v;
                    $dato['productos'][$key]['pedidos']=0;
                    //$id_producto=$this->productos_->getIdProducto($key);
                    //$dato['productos'][$key]['id_producto']=$id_producto;
                }
            }
            
           //  var_dump($productosVD);
            foreach($productosVD as $k=>$v){
               $key=$v['codigo'];
                if(isset($dato['productos'][$key])){
                    $dato['productos'][$key]=array(
                       // 'id_producto'=>$dato['productos'][$key]['id_producto'],
                        'pedidos'=>$dato['productos'][$key]['pedidos']+$v['pedidos'],
                        'peso'=>$dato['productos'][$key]['peso'],
                        'nombre'=>$v['nombre'],
                        'codigo'=>$v['codigo'],
                        'unidades'=>$dato['productos'][$key]['unidades']+$v['unidades'],
                        'importe'=>$dato['productos'][$key]['importe']+$v['importe'],
                    );
                }
                else {
                    $dato['productos'][$key]=$v;
                    $dato['productos'][$key]['peso']=0;
                    //$id_producto=$this->productos_->getIdProducto($key);
                    //$dato['productos'][$key]['id_producto']=$id_producto;
                }
            }
            //var_dump($dato['productos']);
            //if(!$agrupar)
                $dato['productos']=$this->productos_->ordenarArray($dato['productos']);
            
            $totalCodigos=0;
            $totalUnidades=0;
            $totalPesos=0;
            $totalImportes=0;
            foreach($dato['productos'] as $k=>$v){
                $totalCodigos+=1;
                $totalUnidades+=$v['unidades'];
                if(isset($v['peso'])) $totalPesos+=$v['peso'];
                $totalImportes+=$v['importe'];
            }
            $dato['productosTotales']=array('unidades'=>$totalUnidades,'peso'=>$totalPesos,'importe'=>$totalImportes);
            
            
           
            
             $dato['agrupar']=$agrupar;
               
             return $dato['productos'];
            
            
            
            
          
         }  
        
}


