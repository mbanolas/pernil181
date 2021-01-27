<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    exit("<h2>No está permitido el acceso directo a esta URL</h2>");

class Ventas extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('maba');
        $this->load->library('email');
        $this->load->library('email');

        $this->load->model('productos_');


        $this->load->library('grocery_CRUD');

        $this->grocery_crud->set_theme('bootstrap');
        $this->grocery_crud->unset_bootstrap();
        $this->grocery_crud->unset_jquery();
        $this->grocery_crud->set_language("spanish");




        $this->load->helper('cookie');
        $this->load->helper('maba');
        //set_time_limit ( 0 ) ;
        // ini_set("memory_limit", "1024M");
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);
                   
    }

    // https://stackoverflow.com/questions/16791810/callback-column-not-working-for-connected-table-in-grocery-crud
    function unique_field_name($field_name)
    {
        return 's' . substr(md5($field_name), 0, 8); //This s is because is better for a string to begin with a letter and not with a number
    }

    function numLineasAnalisisVentas($inicio = "", $final = "", $tienda = ""){
        $numLineas=$this->db->query("SELECT id FROM pe_registro_ventas WHERE fecha_venta>='$inicio' AND fecha_venta<='$final' AND tipo_tienda='$tienda'")->num_rows();
        echo json_encode($numLineas);
    }

    // entrada manual precio transporte pagado para prestashop
    // es para anotar los precios de transporte pagados en ventas tienda 
    function entradaTransporte(){
        $this->load->model('stocks_model');
         $this->load->model('compras_model');
         $this->compras_model->agrupar_proveedores_acreedores();
         //$dato['optionsFormulas']=$this->stocks_model->getFormulas()['optionsFormulas'];
         $dato['optionsProductos']=$this->stocks_model->getProductos()['optionsProductos'];
         $dato['proveedoresAcreedores']=$this->compras_model->getProveedoresAcreedores()['options'];
         $dato['pedidos']=$this->compras_model->getPedidosProveedores()['options'];
         $dato['activeMenu']='Stocks';
         $dato['activeSubmenu']="Albaranes";
         if($this->session->categoria!=1){
            enviarEmail($this->email, 'Entrada Transporte',host().' - Pernil181','Sesión iniciada por: <br>Usuario: '.$this->session->nombre,3);
         }
        $this->load->view('templates/header.html',$dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('entradaTransporte.php',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
    }

    function grabarDatosTransporteClientes(){
        $baseTransporteCliente=$_POST['baseTransporteCliente']*1000;
        $iva=$_POST['iva']*1000;
        $total=$_POST['total']*1000;
        $id=$_POST['id'];
        $observaciones=$_POST['observaciones'];
        $sql="UPDATE pe_orders_prestashop SET 
                                        base_transporte='$baseTransporteCliente',
                                        iva_transporte='$iva',
                                        transporte='$total',
                                        observaciones='$observaciones',
                                        tipo_iva_transporte='$iva',
                                        diferencia_base_factura_base_tarifa=$baseTransporteCliente-base_factura,
                                        diferencia_base_transporte_base_factura=$baseTransporteCliente/10-base_factura
                                WHERE id='$id'
                              ";
        mensaje('grabarDatosTransporteClientes '.$sql);
        echo json_encode($this->db->query($sql));

                    
    }

    function getPedido($pedido=""){
        $pedido=$_POST['pedido'];
        $sql="SELECT * FROM pe_orders_prestashop WHERE pedido='$pedido'";
        mensaje($sql);
        if($this->db->query($sql)->num_rows()==0){
            echo json_encode(0);
            return;
        }
        else {   
            $rowPedido=$this->db->query($sql)->row();
            echo json_encode($rowPedido);
        }
    }

    function getTicketPedido(){
        
        $pedido=$_POST['pedido'];
        $ticket=$_POST['ticket'];
        $sql="SELECT * FROM pe_boka WHERE RASA='$ticket' AND STYP='1'";
        mensaje($sql);
        if($this->db->query($sql)->num_rows()==0){
            echo json_encode(array('resultado'=>"0"));
            return;
        }
        else {   
            $resultTickets=$this->db->query($sql)->result();
            $sql="SELECT * FROM pe_orders_prestashop WHERE pedido='$pedido'";
            if($this->db->query($sql)->num_rows()==0){
                echo json_encode(array('resultado'=>"1"));
                return;
            }
            else {   
                $rowPedido=$this->db->query($sql)->row();
                $rowTicket="";
                foreach($resultTickets as $k=>$v){
                    if(abs(strtotime($v->ZEIS)-strtotime($rowPedido->fecha))<150*24*60*60){   //sólo considerar los tickes de hace o despues de 150 días
                        $rowTicket=$v;
                    }
                }
                if(!$rowTicket){
                    $ticketsCercanos=$this->ticketsCercanosTransporte($rowPedido->fecha);
                    echo json_encode(array('resultado'=>"2",'ticketsCercanos'=>$ticketsCercanos));
                    return;
                }
                $bonu=$rowTicket->BONU;
                $zeis=$rowTicket->ZEIS;
                $sql="SELECT * FROM pe_boka WHERE BONU='$bonu' AND ZEIS='$zeis' AND STYP='2' AND SNR1='999'";
                mensaje($sql);
                if($this->db->query($sql)->num_rows()==0){
                    mensaje('ninguno con ytramsporte');
                    $ticketsCercanos=$this->ticketsCercanosTransporte($rowPedido->fecha);
                    echo json_encode(array('resultado'=>"2",'ticketsCercanos'=>$ticketsCercanos));
                    return;
                }
                $rowTicket=$this->db->query($sql)->row();
                echo json_encode(array('rowTicket'=>$rowTicket,'rowPedido'=>$rowPedido,'resultado'=>"10"));
            }
        }

    }

    function ticketsCercanosTransporte($fechaPedido){
        $antes=date("Y-m-d",strtotime($fechaPedido)-30*24*60*60);
        $despues=date("Y-m-d",strtotime($fechaPedido)+30*24*60*60);
        $sql="SELECT BONU FROM pe_boka WHERE ZEIS>='$antes' AND ZEIS<='$despues' AND STYP='2' AND SNR1='999'";
        mensaje($sql);
        if($this->db->query($sql)->num_rows()==0){
            return "<br> Ninguno antes o después de un mes a la fecha del pedido prestashop";
        }
        $result=$this->db->query($sql)->result();
        $ticketsCercanos=array();
        foreach($result as $k=>$v){
            $bonu=$v->BONU;
            $row=$this->db->query("SELECT RASA,ZEIS FROM pe_boka WHERE STYP=1 AND BONU='$bonu'")->row();
            $ticketsCercanos[]='Ticket: '.$row->RASA.'  -  Fecha'.fechaEuropea($row->ZEIS);
        }
        mensaje('count($ticketsCercanos) '.count($ticketsCercanos));
        if(count($ticketsCercanos)>0){
            return implode("<br> ",$ticketsCercanos);
        }
        else {
            return "<br> Ninguno antes o despues de un mes a la fecha pedido prestashop";
        }

    }



    function analisisVentas($inicio = "", $final = "", $tienda = "")
    {

        // actualizacion fecha ventas prestashop por cambio criterio entrada pedidos
        // $result=$this->db->query("SELECT * FROM pe_orders_prestashop WHERE pedido>24499")->result();
        // foreach($result as $k=>$v){
        //     $fecha=$v->fecha;
        //     $pedido=$v->pedido;
        //     $fecha_venta=$fecha;
        //     $fecha_local=fechaEuropea($fecha);
        //     // mensaje($fecha_venta);
        //     // mensaje($fecha_local);
        //     $sql="UPDATE pe_registro_ventas SET
        //                  fecha_venta='$fecha_venta',
        //                  fecha_local='$fecha_local'
        //                  WHERE tipo_tienda='2' AND num_ticket='$pedido'
        //     ";
        //     // mensaje($sql);
        //     // mensaje($this->db->query($sql));

        // }

        if(!$inicio) $inicio='2018-02-23';
        if(!$final) $final=date('Y-m-d');
        if($inicio<'2018-02-23') $inicio='2018-02-23';
        $_SESSION['inicio'] = $inicio;
        $_SESSION['final'] = $final;


        $this->grocery_crud->set_table('pe_registro_ventas');

        $this->grocery_crud->order_by('fecha_venta', 'DESC');
        $this->grocery_crud->set_subject('Análisis Ventas');

        $where = "";
        if (!$tienda)  $where = " tipo_tienda=true ";
        else $where = " tipo_tienda='$tienda' ";
        if ($inicio && $final) {
            $finalDia = $final . ' 23:59';
            $where .= "AND fecha_venta >= '$inicio' AND fecha_venta <='$finalDia'";
        }

        if ($where) $this->grocery_crud->where($where);

        $sql="SELECT * FROM pe_registro_ventas WHERE $where ORDER BY fecha_venta DESC";

        //$this->grocery_crud->field_without_sorter('precio_compra');

        $this->grocery_crud->unset_delete();
        $this->grocery_crud->unset_edit();
        $this->grocery_crud->display_as('tipo_tienda', 'T/P');
        switch ($tienda) {
            case 1:
                $this->grocery_crud->display_as('cantidad', 'Und');
                $this->grocery_crud->display_as('peso', 'Kg');
                $this->grocery_crud->display_as('num_ticket', 'Núm ticket');
                break;
            case 2:
                $this->grocery_crud->display_as('cantidad', 'Und');
                $this->grocery_crud->display_as('num_ticket', 'Núm pedido');
                break;
            default:
                $this->grocery_crud->display_as('cantidad', 'Und o Kg');
                $this->grocery_crud->display_as('num_ticket', 'Núm ticket / Núm pedido');
                break;
        }

        $this->grocery_crud->display_as('id_pe_producto', 'Producto');
        $this->grocery_crud->display_as('codigo_producto', 'Código 13');
        $this->grocery_crud->display_as('beneficio_producto', 'Beneficio (%)');
        $this->grocery_crud->display_as('beneficio_producto_embalaje', 'Beneficio Embalaje Inc. (%)');
        $this->grocery_crud->display_as('beneficio_producto_embalaje_transporte', 'Beneficio Embalaje+Transporte Inc.(%)');
        $this->grocery_crud->display_as('beneficio_absoluto', 'Beneficio Absoluto Venta (€) ');
        $this->grocery_crud->display_as('tipo_iva', 'Tipo IVA (%)');
        $this->grocery_crud->display_as('pvp_neto', 'PVP/und o Kg (€)');
        if ($tienda == 2)  $this->grocery_crud->display_as('pvp_neto', 'PVP/und (€)');
        $this->grocery_crud->display_as('transporte', 'Transporte/und (€)');
        $this->grocery_crud->display_as('precio_compra', 'Precio compra (€)');
        $this->grocery_crud->display_as('tarifa_venta', 'Tarifa venta (€)');
        $this->grocery_crud->display_as('precio_embalaje', 'Precio embalaje (€)');
        $this->grocery_crud->display_as('num_cliente', 'Núm cliente');
        $this->grocery_crud->display_as('ingresado', 'Total ingresado producto (€)');


        $this->grocery_crud->set_relation('id_pe_producto', 'pe_productos', 'nombre');
        $this->grocery_crud->set_relation('codigo_producto', 'pe_productos', 'codigo_producto');
        $this->grocery_crud->set_relation('grupo', 'pe_grupos', 'nombre_grupo');
        $this->grocery_crud->set_relation('familia', 'pe_familias', 'nombre_familia');
        
        if ($tienda == 2) {
            //https://stackoverflow.com/questions/16791810/callback-column-not-working-for-connected-table-in-grocery-crud
            $this->grocery_crud->set_relation('num_cliente', 'pe_orders_prestashop', 'customer_id');
            $this->grocery_crud->callback_column($this->unique_field_name('num_cliente'), array($this, '_callback_num_cliente_prestashop'));
        }




        $this->grocery_crud->callback_column('cantidad', array($this, '_callback_cantidad'));
        $this->grocery_crud->callback_column('peso', array($this, '_callback_peso'));
        $this->grocery_crud->callback_column('tipo_tienda', array($this, '_callback_tipo_tienda'));
        $this->grocery_crud->callback_column('precio_compra', array($this, '_callback_miles_3'));

        $this->grocery_crud->callback_column('tarifa_venta', array($this, '_callback_miles_3'));
        $this->grocery_crud->callback_column('pvp_neto', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('grupo', array($this, '_callback_grupo'));
        $this->grocery_crud->callback_column('familia', array($this, '_callback_familia'));

        $this->grocery_crud->callback_column('precio_embalaje', array($this, '_callback_miles_3'));
        
        $this->grocery_crud->callback_column('transporte', array($this, '_callback_miles_3'));
        $this->grocery_crud->callback_column('tipo_iva', array($this, '_callback_entero_2'));
        $this->grocery_crud->callback_column('ingresado', array($this, '_callback_cientos_2'));
        $this->grocery_crud->callback_column('total_transporte', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('total_sin_descuento', array($this, '_callback_cientos_2'));
        $this->grocery_crud->callback_column('descuento', array($this, '_callback_cientos_2'));


        $this->grocery_crud->callback_column('beneficio_producto', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('beneficio_producto_embalaje', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('beneficio_producto_embalaje_transporte', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('beneficio_absoluto', array($this, '_callback_beneficio_absoluto'));
        if ($tienda == 1) {
            $this->grocery_crud->callback_column('num_cliente', array($this, '_callback_num_cliente_tienda'));
        }
        if ($tienda == 2) { }

        $datos = array(
            'fecha_venta',
            'num_ticket',
            'tipo_tienda_letra',
            'num_cliente',
            'codigo_producto',
            'grupo',
            'familia',
            'id_pe_producto',
            'precio_compra',
            'tarifa_venta',
            //  'peso_real',
            'precio_embalaje',
            'cantidad',
            'peso',
            'pvp_neto',

            'tipo_iva',
            'transporte',
            'beneficio_producto',
            'beneficio_producto_embalaje',
            //  'beneficio_producto_embalaje_transporte',
            'total_transporte',
            'ingresado',
            'beneficio_absoluto'
        );

        if ($tienda) unset($datos['2']);  //tipo_tienda_letra
        if ($tienda == 1) unset($datos['15']);  //transporte NO si es tienda 
        if ($tienda == 1) unset($datos['18']);  //total_transporte NO si es tienda   
        //if($tienda==1) unset($datos['3']);  //num_cliente NO si es tienda                 
        if ($tienda == 2) unset($datos['12']);  //peso NO si es tienda prestashop                   
        $this->grocery_crud->columns($datos);

        //para exportar a Excel TODA la tabla, columnas seleccionadas
        if (false && $this->uri->segment(6) == 'export') {
            $query = $this->db->query("select * from pe_registro_ventas");
            // mensaje($query);
            $string = [];
            foreach ($query->result_array() as $row) {
                    $string = array_keys($row);
                }

            if (($key = array_search('pvp_nuevo', $string)) !== false) {
                unset($string[$key]);
            }
            if (($key = array_search('pvp', $string)) !== false) {
                unset($string[$key]);
            }
            $this->grocery_crud->columns($string);
        }


        $output = $this->grocery_crud->render();

        $output = (array)$output;
        $output['col_bootstrap'] = 12;
        $output['tienda'] = $tienda;
        $output['inicio'] = $inicio;
        $output['finalDia'] = $finalDia;
        $hoy = date("d/m/Y");
        if ($tienda == 1) {
            $output['tituloRango'] = 'Productos Ventas tienda TODOS (dede el 26/02/2018 hasta ' . $hoy . ')';
            if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Productos Venta tienda entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        }
        if ($tienda == 2) {
            $output['tituloRango'] = 'Productos Ventas Prestashop TODOS (dede el 26/02/2018 hasta ' . $hoy . ')';
            if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Productos Venta Prestashop entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        }
        if (!$tienda) {
            $output['tituloRango'] = 'Productos Ventas TODOS (dede el 26/02/2018 hasta ' . $hoy . ')';
            if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Productos Venta entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        }
        $output = (object)$output;
        $this->_table_output_analisis($output, "Análisis Ventas...");
    }

    function variablesTablaTienda(){
        $fecha_venta=$_POST['fecha_venta'];
        $num_ticket=$_POST['num_ticket'];
        $num_cliente=$_POST['num_cliente'];
        $codigo_producto=$_POST['codigo_producto'];
        $grupo    =$_POST['grupo'];
        $familia=$_POST['familia'];
        $producto=$_POST['producto'];
        $tipo_iva=$_POST['tipo_iva'];
        $sql="UPDATE pe_variables_tabla_tienda SET
        num_ticket='$num_ticket',
        fecha_venta='$fecha_venta',
        num_cliente='$num_cliente',
        codigo_producto='$codigo_producto',
        grupo='$grupo',
        familia='$familia',
        producto='$producto',
        tipo_iva='$tipo_iva'
        where id=1
        ";
        mensaje($sql);
        echo  json_encode($this->db->query($sql));
    }

    function leerVariablesTablaTienda()
    {
        $sql = "SELECT 
                        fecha_venta,
                        num_ticket,
                        num_cliente,
                        codigo_producto,
                        grupo,
                        familia,
                        producto,
                        tipo_iva
                FROM pe_variables_tabla_tienda WHERE id=1";
        $row = $this->db->query($sql)->row();
        echo  json_encode($row);
    }



    function analisisVentasNum($pedido="", $tienda = "")
    {

        // actualizacion fecha ventas prestashop por cambio criterio entrada pedidos
        // $result=$this->db->query("SELECT * FROM pe_orders_prestashop WHERE pedido>24499")->result();
        // foreach($result as $k=>$v){
        //     $fecha=$v->fecha;
        //     $pedido=$v->pedido;
        //     $fecha_venta=$fecha;
        //     $fecha_local=fechaEuropea($fecha);
        //     // mensaje($fecha_venta);
        //     // mensaje($fecha_local);
        //     $sql="UPDATE pe_registro_ventas SET
        //                  fecha_venta='$fecha_venta',
        //                  fecha_local='$fecha_local'
        //                  WHERE tipo_tienda='2' AND num_ticket='$pedido'
        //     ";
        //     // mensaje($sql);
        //     // mensaje($this->db->query($sql));

        // }

        // if(!$inicio) $inicio='2018-02-23';
        // if(!$final) $final=date('Y-m-d');
        // if($inicio<'2018-02-23') $inicio='2018-02-23';
        // $_SESSION['inicio'] = $inicio;
        // $_SESSION['final'] = $final;
        $_SESSIO['pedido']=$pedido;

        $this->grocery_crud->set_table('pe_registro_ventas');

        $this->grocery_crud->order_by('fecha_venta', 'DESC');
        $this->grocery_crud->set_subject('Análisis Ventas');

        $where = "";
        if (!$tienda)  $where = " tipo_tienda=true ";
        else $where = " tipo_tienda='$tienda' ";
        // if ($inicio && $final) {
        //     $finalDia = $final . ' 23:59';
        //     $where .= "AND fecha_venta >= '$inicio' AND fecha_venta <='$finalDia'";
        // }
        $where.=" AND num_ticket='$pedido' ";
        if(strtolower($this->session->username)!='pernilall'){
            $fecha_min=date('Y')-5;
            $where .=" AND fecha_venta>='$fecha_min'";
        }

        if ($where) $this->grocery_crud->where($where);


        //$this->grocery_crud->field_without_sorter('precio_compra');

        $this->grocery_crud->unset_delete();
        $this->grocery_crud->unset_edit();
        $this->grocery_crud->display_as('tipo_tienda', 'T/P');
        switch ($tienda) {
            case 1:
                $this->grocery_crud->display_as('cantidad', 'Und');
                $this->grocery_crud->display_as('peso', 'Kg');
                $this->grocery_crud->display_as('num_ticket', 'Núm ticket');
                break;
            case 2:
                $this->grocery_crud->display_as('cantidad', 'Und');
                $this->grocery_crud->display_as('num_ticket', 'Núm pedido');
                break;
            default:
                $this->grocery_crud->display_as('cantidad', 'Und o Kg');
                $this->grocery_crud->display_as('num_ticket', 'Núm ticket / Núm pedido');
                break;
        }

        $this->grocery_crud->display_as('id_pe_producto', 'Producto');
        $this->grocery_crud->display_as('codigo_producto', 'Código 13');
        $this->grocery_crud->display_as('beneficio_producto', 'Beneficio (%)');
        $this->grocery_crud->display_as('beneficio_producto_embalaje', 'Beneficio Embalaje Inc. (%)');
        $this->grocery_crud->display_as('beneficio_producto_embalaje_transporte', 'Beneficio Embalaje+Transporte Inc.(%)');
        $this->grocery_crud->display_as('beneficio_absoluto', 'Beneficio Absoluto Venta (€) ');
        $this->grocery_crud->display_as('tipo_iva', 'Tipo IVA (%)');
        $this->grocery_crud->display_as('pvp_neto', 'PVP/und o Kg (€)');
        if ($tienda == 2)  $this->grocery_crud->display_as('pvp_neto', 'PVP/und (€)');
        $this->grocery_crud->display_as('transporte', 'Transporte/und (€)');
        $this->grocery_crud->display_as('precio_compra', 'Precio compra (€)');
        $this->grocery_crud->display_as('tarifa_venta', 'Tarifa venta (€)');
        $this->grocery_crud->display_as('precio_embalaje', 'Precio embalaje (€)');
        $this->grocery_crud->display_as('num_cliente', 'Núm cliente');
        $this->grocery_crud->display_as('ingresado', 'Total ingresado producto (€)');


        $this->grocery_crud->set_relation('id_pe_producto', 'pe_productos', 'nombre');
        $this->grocery_crud->set_relation('codigo_producto', 'pe_productos', 'codigo_producto');
        $this->grocery_crud->set_relation('grupo', 'pe_grupos', 'nombre_grupo');
        $this->grocery_crud->set_relation('familia', 'pe_familias', 'nombre_familia');
        
        if ($tienda == 2) {
            //https://stackoverflow.com/questions/16791810/callback-column-not-working-for-connected-table-in-grocery-crud
            $this->grocery_crud->set_relation('num_cliente', 'pe_orders_prestashop', 'customer_id');
            $this->grocery_crud->callback_column($this->unique_field_name('num_cliente'), array($this, '_callback_num_cliente_prestashop'));
        }




        $this->grocery_crud->callback_column('cantidad', array($this, '_callback_cantidad'));
        $this->grocery_crud->callback_column('peso', array($this, '_callback_peso'));
        $this->grocery_crud->callback_column('tipo_tienda', array($this, '_callback_tipo_tienda'));
        $this->grocery_crud->callback_column('precio_compra', array($this, '_callback_miles_3'));

        $this->grocery_crud->callback_column('tarifa_venta', array($this, '_callback_miles_3'));
        $this->grocery_crud->callback_column('pvp_neto', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('grupo', array($this, '_callback_grupo'));
        $this->grocery_crud->callback_column('familia', array($this, '_callback_familia'));

        $this->grocery_crud->callback_column('precio_embalaje', array($this, '_callback_miles_3'));
        
        $this->grocery_crud->callback_column('transporte', array($this, '_callback_miles_3'));
        $this->grocery_crud->callback_column('tipo_iva', array($this, '_callback_entero_2'));
        $this->grocery_crud->callback_column('ingresado', array($this, '_callback_cientos_2'));
        $this->grocery_crud->callback_column('total_transporte', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('total_sin_descuento', array($this, '_callback_cientos_2'));
        $this->grocery_crud->callback_column('descuento', array($this, '_callback_cientos_2'));


        $this->grocery_crud->callback_column('beneficio_producto', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('beneficio_producto_embalaje', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('beneficio_producto_embalaje_transporte', array($this, '_callback_miles_2'));
        $this->grocery_crud->callback_column('beneficio_absoluto', array($this, '_callback_beneficio_absoluto'));
        if ($tienda == 1) {
            $this->grocery_crud->callback_column('num_cliente', array($this, '_callback_num_cliente_tienda'));
        }
        if ($tienda == 2) { }

        $datos = array(
            'fecha_venta',
            'num_ticket',
            'tipo_tienda_letra',
            'num_cliente',
            'codigo_producto',
            'grupo',
            'familia',
            'id_pe_producto',
            'precio_compra',
            'tarifa_venta',
            //  'peso_real',
            'precio_embalaje',
            'cantidad',
            'peso',
            'pvp_neto',

            'tipo_iva',
            'transporte',
            'beneficio_producto',
            'beneficio_producto_embalaje',
            //  'beneficio_producto_embalaje_transporte',
            'total_transporte',
            'ingresado',
            'beneficio_absoluto'
        );

        if ($tienda) unset($datos['2']);  //tipo_tienda_letra
        if ($tienda == 1) unset($datos['15']);  //transporte NO si es tienda 
        if ($tienda == 1) unset($datos['18']);  //total_transporte NO si es tienda   
        //if($tienda==1) unset($datos['3']);  //num_cliente NO si es tienda                 
        if ($tienda == 2) unset($datos['12']);  //peso NO si es tienda prestashop                   
        $this->grocery_crud->columns($datos);

        //para exportar a Excel TODA la tabla, columnas seleccionadas
        if ($this->uri->segment(6) == 'export') {
            $query = $this->db->query("select * from pe_registro_ventas");
            // mensaje($query);
            $string = [];
            foreach ($query->result_array() as $row) {
                    $string = array_keys($row);
                }

            if (($key = array_search('pvp_nuevo', $string)) !== false) {
                unset($string[$key]);
            }
            if (($key = array_search('pvp', $string)) !== false) {
                unset($string[$key]);
            }
            $this->grocery_crud->columns($string);
        }


        $output = $this->grocery_crud->render();

        $output = (array)$output;
        $output['col_bootstrap'] = 12;
        $output['tienda'] = $tienda;
        $output['sql'] = $sql;
        $hoy = date("d/m/Y");
        if ($tienda == 1) {
            $output['tituloRango'] = 'Productos Ventas tienda TODOS (dede el 26/02/2018 hasta ' . $hoy . ')';
            // if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Productos Venta tienda entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        }
        if ($tienda == 2) {
            $output['tituloRango'] = 'Productos Ventas Prestashop Pedido  ' . $pedido;
        }
        if (!$tienda) {
            $output['tituloRango'] = 'Productos Ventas TODOS (dede el 26/02/2018 hasta ' . $hoy . ')';
            // if ($inicio != "1970-01-01" && $final != "3000-01-01") $output['tituloRango'] = 'Productos Venta entre ' . fechaEuropeaSinHora($inicio) . " y " . fechaEuropeaSinHora($final);
        }
        $output = (object)$output;
        $this->_table_output_analisis($output, "Análisis Ventas...");
    }

    public function _callback_num_cliente_tienda($value, $row)
    {
        return '<a href="#" class="cliente_tienda">' . $value . '</a>';
        return $value;
    }
    public function _callback_num_cliente_prestashop($value, $row)
    {
        //pendiente de hacer crear acción sobre click en el núm del cliente
        return '<a href="#" class="cliente_prestashop">' . $value . '</a>';

        return $value;
    }

    public function _callback_miles_2($value, $row)
    {
        $value = number_format($value / 1000, 2, ",", "");
        return $value;
    }
    public function _callback_centenas_2($value, $row)
    {
        $value = number_format($value / 100, 2, ",", "");
        return $value;
    }
    public function _callback_miles_3($value, $row)
    {
        $value = number_format($value / 1000, 3, ",", "");
        return $value;
    }
    public function _callback_grupo($value, $row)
    {
        return $value;
        $id_pe_producto = $value;
        $id_grupo = $this->db->query("SELECT id_grupo FROM pe_productos WHERE id='" . $value . "'")->row()->id_grupo;
        return $this->db->query("SELECT nombre_grupo FROM pe_grupos WHERE id_grupo='$id_grupo'")->row()->nombre_grupo;
    }
    public function _callback_familia($value, $row)
    {
        return $value;
        return $this->db->query("SELECT nombre_familia FROM pe_pamilias WHERE id_familia='$value'")->row()->nombre_familia;
    }



    public function _callback_cantidad($value, $row)
    {
        if ($row->peso != 0 || $value == 0) {
            $value = "";
            //$value=$value/1000; //number_format($value/1000,3,",","");
            return $value;
        }
        return $value;
    }
    public function _callback_peso($value, $row)
    {
        if ($row->peso != 0) {
            // $value=$row->peso/1000;
            $value = number_format($value / 1000, 3, ",", "");
            return $value;
        }
        return "";
    }

    public function _callback_pvp_neto($value, $row)
    {
        //return ($row->total_sin_descuento+$row->descuento)/$row->cantidad;
        if ($row->descuento != 0) {

            $value = ($row->total_sin_descuento + $row->descuento) / $row->cantidad;
            $value = number_format($value / 100, 2, ",", "");
            return $value;
        }
        return number_format($row->pvp_nuevo / 100, 2, ",", "");
    }


    public function _callback_beneficio_absoluto($value, $row)
    {
        if ($value) {
            $value = number_format($value / 1000, 3, ",", "");
            return $value;
        }
        return '---';
    }

    public function _callback_tipo_tienda($value, $row)
    {
        if ($value == 1) return "T";
        if ($value == 2) return "P";
        return $value;
    }
    public function _callback_cientos_2($value, $row)
    {
        $value = number_format($value / 100, 2, ",", "");
        return $value;
    }
    public function _callback_entero_2($value, $row)
    {
        $value = number_format($value / 100, 0, ",", "");
        return $value;
    }





    function _table_output_analisis($output = null, $table = "")
    {

        $this->load->view('templates/headerGrocery.php', $output);

        $this->load->view('templates/top.php');

        $this->load->view('table_template_analisis.php', $output);
        $this->load->view('templates/footer.html');
        $this->load->view('myModalPrestashop');
        $this->load->view('myModalTicket');
        $this->load->view('myModal');
    }


    function resumenAnalisis()
    {
        $this->load->view('templates/header.html');

        $this->load->view('templates/top.php');

        $this->load->view('resumenAnalisis.php');
        $this->load->view('templates/footer.html');
        $this->load->view('myModalPrestashop');
        $this->load->view('myModalTicket');
        $this->load->view('myModal');
    }

    function getAnalisisVentas($desde = "", $hasta = "")
    {
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $this->load->model('ventas_model');
        $resultado = $this->ventas_model->getAnalisisVentas($desde, $hasta);
        echo json_encode($resultado);
    }

    

    public function productosTotales()
    {
        $sql = $_POST['sql'];
        $row = $this->db->query($sql)->row_array();
        //$row['pvp_neto']=number_format($row['pvp_neto']/1000,2,",",".");
        $row['ingresado'] = number_format($row['ingresado'] / 100, 2, ",", ".");
        $row['cantidad'] = $row['cantidad'] == 0 ? "" : number_format($row['cantidad'], 0, ",", ".");
        $row['peso'] = number_format($row['peso'] / 1000, 3, ",", ".");
        $row['transporte'] = number_format($row['transporte'] / 1000, 2, ",", ".");
        $row['total_transporte'] = number_format($row['total_transporte'] / 1000, 2, ",", ".");
        $row['beneficio_producto'] = number_format($row['beneficio_producto'] / 1000, 2, ",", ".");
        $row['beneficio_producto_embalaje'] = number_format($row['beneficio_producto_embalaje'] / 1000, 2, ",", ".");
        $row['beneficio_producto_embalaje_transporte'] = number_format($row['beneficio_producto_embalaje_transporte'] / 1000, 2, ",", ".");
        $row['beneficio_absoluto'] = number_format($row['beneficio_absoluto'] / 1000, 3, ",", ".");
        $row['lineas'] = $row['lineas'];

        echo  json_encode($row);
    }

    public function exportarExcelTienda(){
        $fecha_venta=trim($_POST['fecha_venta']);
        $fecha_venta=str_replace(" ","",$fecha_venta);
        $fecha_venta=str_replace("/","-",$fecha_venta);
        if(preg_match('/\d{4}/', $fecha_venta)) $fecha_venta=$fecha_venta;


        $num_ticket=$_POST['num_ticket'];
        $num_cliente=$_POST['num_cliente'];
        $codigo_producto=$_POST['codigo_producto'];
        $grupo=$_POST['grupo'];
        $familia=$_POST['familia'];
        $producto=$_POST['producto'];
        $tipo_iva=$_POST['tipo_iva'];
        $inicio=$_POST['inicio'];
        $finalDia=$_POST['finalDia'];
        $tituloCabecera=$_POST['tituloCabecera'];
        $tituloPie=$_POST['tituloPie'];
        $tienda=$_POST['tienda'];
        $titulares=$_POST['titulares'];

        $datos['num_ticket']=$num_ticket;
        $datos['num_cliente']=$num_cliente;
        $datos['codigo_producto']=$codigo_producto;
        $datos['grupo']=$grupo;
        $datos['familia']=$familia;
        $datos['producto']=$producto;
        $datos['tipo_iva']=$tipo_iva;
        $datos['inicio']=$inicio;
        $datos['finalDia']=$finalDia;
        $datos['tituloCabecera']=$tituloCabecera;
        $datos['tituloPie']=$tituloPie;
        $datos['tienda']=$tienda;
        $datos['titulares']=$titulares;


        mensaje($titulares);

        
        $sql="SELECT r.*,p.codigo_producto, p.nombre,g.nombre_grupo,f.nombre_familia  FROM pe_registro_ventas r
        LEFT JOIN pe_productos p ON r.id_pe_producto=p.id
        LEFT JOIN pe_grupos g ON r.grupo=g.id
        LEFT JOIN pe_familias f ON r.familia=f.id
        WHERE fecha_venta>='$inicio' AND fecha_venta<='$finalDia'
        AND r.tipo_tienda= '$tienda'
        AND p.codigo_producto like '%$codigo_producto%'
        AND p.nombre like '%$producto%'
        AND r.num_ticket like '%$num_ticket%'
        AND r.num_cliente like '%$num_cliente%'
        AND g.nombre_grupo like '%$grupo%'
        AND f.nombre_familia like '%$familia%'
        AND r.tipo_iva like '%$tipo_iva%'
        AND r.fecha_venta like '%$fecha_venta%'
        
        ";
        
        // SELECT * FROM pe_registro_ventas WHERE  tipo_tienda='1' AND fecha_venta >= '2020-09-01' AND fecha_venta <='2021-01-01 23:59' ORDER BY fecha_venta DESC
        
        $datos['result'] = $this->db->query($sql)->result();
        mensaje($sql);
        $this->load->library('excel');

        $ahora = date('d/m/Y H:i:s');
        if ($this->session->categoria != 1) {
            enviarEmail($this->email, 'Exportación datos productos ', host() . ' - Pernil181', 'Bajado por: <br>Usuario: ' . $this->session->nombre . '<br>Fecha: ' . $ahora.'<br>'.$sql, 3);
        }
        $this->load->view('prepararExcelResumenVentas', $datos);


    }
}
