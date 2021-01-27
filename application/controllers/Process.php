<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");

class Process extends CI_Controller {
    function __construct()
	{
		parent::__construct();
                $this->load->model('process_');
		
        }
        
        
        
         function index()
	{
                
                
	}
        
     function cliente(){
        //$linea = strip_tags($_POST['linea']);
        //$id_cliente = strip_tags($_POST['id_cliente']);
      //  $cliente= strip_tags($_POST['cliente']);
        //$empresa= strip_tags($_POST['empresa']);
        //$datos=array('id_cliente'=>$id_cliente,'cliente'=>$cliente, 'empresa'=>$empresa);
        $query=$this->process_->upDateCliente($_POST);
        echo json_encode($query);
        /*
        echo json_encode(array('linea' => $linea,
            'id_cliente' => $id_cliente,
            'cliente' => $cliente,
            'empresa' => $empresa,
            'query' =>$query
                )
        );
         * 
         */
     }    
     
     function leerClienteNuevo(){
         $this->process_->leerClienteNuevo;
     }
     
        
     function getTickets(){
         $this->load->model('tickets_');
         $tickets=array();
         $tickets=$this->tickets_->getTickets($_POST['inicio'], $_POST['final']);
         
         echo json_encode($tickets);
     }
     
     function getPedidosPS(){
         
         $pedidos=array();
         $this->load->model('prestashop_model');
         $pedidos=$this->prestashop_model->getPedidosPS($_POST['inicio'], $_POST['final']);
         
         echo json_encode($pedidos);
     }
     function getPedidosVD(){
         $pedidos=array();
         $this->load->model('directas_model');
         $pedidos=$this->directas_model->getPedidosVD($_POST['inicio'], $_POST['final']);
         
         echo json_encode($pedidos);
     }
     
     
     function getTicketsParaModificar(){
         $this->load->model('tickets_');
         $tickets=array();
         $num=$this->tickets_->getNumTicketsAModificar($_POST['inicio'], $_POST['final']);
         if ($num>2000){
            echo json_encode(array('num'=>$num));
            return;
         }
         $tickets=$this->tickets_->getTicketsAModificar($_POST['inicio'], $_POST['final']);
         echo json_encode($tickets);
     }
     
     
     
     function proveedor(){
        $linea = strip_tags($_POST['linea']);
      //  $id_proveedor = strip_tags($_POST['id_proveedor']);
      //  $datos=array('id_proveedor'=>$id_proveedor,);
        
        $campos=array('id_proveedor', 'id_contable','nombre', 'domicilio', 'cp', 'poblacion', 'provincia', 'pais', 'telefono', 'cif', 'fax', 'email1', 'email2', 'contacto', 'web', 'telefono2', 'movil', 'otros', 'nota', 'fechaAlta', 'fechaModificacion');
        for($k=0;$k<sizeof($campos);$k++){
            $n=strip_tags($_POST[$campos[$k]]);
            $datos[$campos[$k]]=$n;
        }
        
        $query=$this->process_->upDateProveedor($datos);
        
        echo json_encode(array('linea' => $linea,
            //'id_proveedor' => $id_proveedor,
            //'nombre' => $nombre,
            'resultados'=>$datos,
            'query' =>$query
                )
        );
     }  
     
     
     function acreedor(){
        $linea = strip_tags($_POST['linea']);
      //  $id_proveedor = strip_tags($_POST['id_proveedor']);
      //  $datos=array('id_proveedor'=>$id_proveedor,);
        
        $campos=array('id_proveedor', 'id_contable','nombre', 'domicilio', 'cp', 'poblacion', 'provincia', 'pais', 'telefono', 'cif', 'fax', 'email1', 'email2', 'contacto', 'web', 'telefono2', 'movil', 'otros', 'nota', 'fechaAlta', 'fechaModificacion');
        for($k=0;$k<sizeof($campos);$k++){
            $n=strip_tags($_POST[$campos[$k]]);
            $datos[$campos[$k]]=$n;
        }
        
        $query=$this->process_->upDateAcreedor($datos);
        
        echo json_encode(array('linea' => $linea,
            //'id_proveedor' => $id_proveedor,
            //'nombre' => $nombre,
            'resultados'=>$datos,
            'query' =>$query
                )
        );
     }  
        
     function familia(){
         $linea = strip_tags($_POST['linea']);
        $id_familia = strip_tags($_POST['id_familia']);
        $familia= strip_tags($_POST['familia']);
        $datos=array('id_familia'=>$id_familia,'familia'=>$familia);
        $query=$this->process_->upDateFamilia($datos);
        
        echo json_encode(array('linea' => $linea,
            'id_familia' => $id_familia,
            'familia' => $familia,
            'query' =>$query
                )
        );
     }   
        
     function producto(){
        $linea = strip_tags($_POST['linea']);
        $id_producto = strip_tags($_POST['id_producto']);
        $producto= strip_tags($_POST['producto']);
        $familia = strip_tags($_POST['familia']);
        $proveedor = strip_tags($_POST['proveedor']);
        $precio = strip_tags($_POST['precio']);
        
        $datos=array('id_producto'=>$id_producto,'producto'=>$producto,'familia'=>$familia,'proveedor'=>$proveedor,'precio'=>$precio);
        $query=$this->process_->upDateProducto($datos);
        
        $nombreFamilia=$this->process_->getNombreFamilia($familia);
        $nombreProveedor=$this->process_->getNombreProveedor($proveedor);
        
        echo json_encode(array('linea' => $linea,
            'id_producto' => $id_producto,
            'producto' => $producto,
            'familia' => $familia,
            'nombreFamilia' => $nombreFamilia,
            'proveedor' => $proveedor,
            'nombreProveedor' => $nombreProveedor,
            'precio' => $precio,
            'query' =>$query
                
                )
        );
    }    
        
     function productoNuevo(){
         
        //$linea = strip_tags($_POST['linea']);
        $id_producto = strip_tags($_POST['id_producto']);
        $producto= strip_tags($_POST['producto']);
        $familia = strip_tags($_POST['familia']);
        $proveedor = strip_tags($_POST['proveedor']);
        $precio = strip_tags($_POST['precio']);
        
        $datos=array('id_producto'=>$id_producto,'producto'=>$producto,'familia'=>$familia,'proveedor'=>$proveedor,'precio'=>$precio);
        
        $datos['salida']=$this->process_->altaProducto($datos);
        
        echo json_encode($datos);
        
    }
    
     function familiaNuevo(){
         
        //$linea = strip_tags($_POST['linea']);
        $id_familia = strip_tags($_POST['id_familia']);
        $familia= strip_tags($_POST['familia']);
        
        
        $datos=array('id_familia'=>$id_familia,'familia'=>$familia,);
        
        $datos['salida']=$this->process_->altaFamilia($datos);
        
        echo json_encode($datos);
        
    }
    
     function clienteNuevo(){
         
        
        
        
        
        
        $datos['salida']=$this->process_->altaCliente($_POST);
        
        echo json_encode($datos);
        
    }
    
     function proveedorNuevo(){
         
        //$id_proveedor = strip_tags($_POST['id_proveedor']);
        //$nombre= strip_tags($_POST['nombre']);
        //$datos=array('id_proveedor'=>$id_proveedor,'nombre'=>$nombre,);
        
         $campos=array('id_proveedor', 'id_contable','nombre', 'domicilio', 'cp', 'poblacion', 'provincia', 'pais', 'telefono', 'cif', 'fax', 'email1', 'email2', 'contacto', 'web', 'telefono2', 'movil', 'otros', 'nota', 'fechaAlta', 'fechaModificacion');
        for($k=0;$k<sizeof($campos);$k++){
            $n=strip_tags($_POST[$campos[$k]]);
            $datos[$campos[$k]]=$n;
        }
         
        $datos['salida']=$this->process_->altaProveedor($datos);
        
        echo json_encode($datos);
        
    }
    
    function acreedorNuevo(){
         
        //$id_proveedor = strip_tags($_POST['id_proveedor']);
        //$nombre= strip_tags($_POST['nombre']);
        //$datos=array('id_proveedor'=>$id_proveedor,'nombre'=>$nombre,);
        
         $campos=array('id_proveedor', 'id_contable','nombre', 'domicilio', 'cp', 'poblacion', 'provincia', 'pais', 'telefono', 'cif', 'fax', 'email1', 'email2', 'contacto', 'web', 'telefono2', 'movil', 'otros', 'nota', 'fechaAlta', 'fechaModificacion');
        for($k=0;$k<sizeof($campos);$k++){
            $n=strip_tags($_POST[$campos[$k]]);
            $datos[$campos[$k]]=$n;
        }
         
        $datos['salida']=$this->process_->altaAcreedor($datos);
        
        echo json_encode($datos);
        
    }
    
     function productoEliminar(){
        $linea = strip_tags($_POST['linea']);
        $id_producto = strip_tags($_POST['id_producto']);
        
        $datos=array('id_producto'=>$id_producto);
        $query=$this->process_->eliminarProducto($datos);
        
        
        
        echo json_encode(array(
            'id_producto' => $id_producto,
                'linea'=>$linea
                
                )
        );
    }  
    
     function familiaEliminar(){
      //  $linea = strip_tags($_POST['linea']);
        $id_familia = strip_tags($_POST['id_familiaEliminar']);
        
        $datos=array('id_familia'=>$id_familia);
        $query=$this->process_->eliminarFamilia($datos);
        
        
        echo json_encode(array(
            'id_familia' => $id_familia,
               // 'linea'=>$linea
                
                )
        );
        
    }
    
     function proveedorEliminar(){
        //$linea = strip_tags($_POST['linea']);
        $id_proveedor = strip_tags($_POST['id_proveedorEliminar']);
        
        $datos=array('id_proveedor'=>$id_proveedor);
        $query=$this->process_->eliminarProveedor($datos);
        
        
        
        echo json_encode(array(
            'id_proveedor' => $id_proveedor,
               // 'linea'=>$linea
                
                )
        );
    }
    
    function acreedorEliminar(){
        //$linea = strip_tags($_POST['linea']);
        $id_proveedor = strip_tags($_POST['id_proveedorEliminar']);
        
        $datos=array('id_proveedor'=>$id_proveedor);
        $query=$this->process_->eliminarAcreedor($datos);
        
        
        
        echo json_encode(array(
            'id_proveedor' => $id_proveedor,
               // 'linea'=>$linea
                
                )
        );
    }
    
    
    
     function clienteEliminar(){
        //$linea = strip_tags($_POST['linea']);
        $id_cliente = strip_tags($_POST['id_cliente']);
        
       
        $query=$this->process_->eliminarCliente($id_cliente);
        
        echo json_encode($query);
         
    }
  
}
