<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL..</h2>");




class Stocks extends CI_Controller {
	 
    function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros_helper'));
                $this->load->library('form_validation');
                $this->load->model('stocks_model');
                $this->load->model('clientes_');
                $this->load->library('grocery_CRUD');
                $this->load->helper('maba');
    
        $this->load->library('excel');
        }
       
        
    function stocksMinimosExcel(){
        //echo $_POST['lineas'];
        $lineas=json_decode($_POST['lineas'],true);
        $datos['lineas']=$lineas;
        $this->load->view('stocksMinimosExcel',$datos);
    }    
        
    function stocksMinimos(){
        $dato=array();
        $ultimaFechaStocksMinimos="";
        if($this->db->query("SELECT * FROM pe_datos_aplicacion ORDER BY id DESC LIMIT 1")->num_rows()>0)
            $ultimaFechaStocksMinimos=$this->db->query("SELECT * FROM pe_datos_aplicacion ORDER BY id DESC LIMIT 1")->row()->mes_stocks_minimos;
        $hoy=date('Y-m-d');
        $dato['ultimaFechaStocksMinimos']="";
        if(substr($hoy,0,7)==substr($ultimaFechaStocksMinimos,0,7)) 
            $dato['ultimaFechaStocksMinimos']=$ultimaFechaStocksMinimos;
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('stocksMinimosLoad', $dato);
        $this->load->view('templates/footer.html');
        
        return;
    }    
    
    
    function stocksMinimosCalculo(){

        //eliminamos apóstrofes, si los hubiera de nombre producto
        $result=$this->db->query("SELECT id,nombre, nombre_generico FROM pe_productos ")->result();
        foreach($result as $v){
            if(strpos("_".$v->nombre,"'")) {
                $nombre=str_replace("'","\´",$v->nombre);
                $nombre=substr($nombre,1);
                $this->db->query("UPDATE pe_productos SET nombre='".$nombre."'  WHERE id='".$v->id."'");
            }
            if(strpos("_".$v->nombre_generico,"'")) {
                $nombre_generico=str_replace("'","\´",$v->nombre_generico);
                $nombre_generico=substr($nombre_generico,1);
                $this->db->query("UPDATE pe_productos SET nombre_generico='".$nombre_generico."'  WHERE id='".$v->id."'");
            }
        }
        
        $sql = "SET SQL_BIG_SELECTS=1";
        $this->db->query($sql);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);

        $ventasMensuales = array();

        /*
         * Criterios
            El 2º  y 3º trimestre ajustarla a la media de los tres meses anteriores.
            El 4º trimestre tomar los datos del último trimestre del año anterior.
            El 1º trimestre utilizar la media del tercer trimestre del año anterior.        
         */
        $mesHoy=date('m');
        switch($mesHoy){
            case $mesHoy>=4 && $mesHoy<=9:     
                $inicio=(date("Y")) . '-' . (date("m")-3) . '-' . '01';
                $final=(date("Y")) . '-' . (date("m")) . '-' . '01';
                $numMeses=3;
                break;
            case $mesHoy>=10 && $mesHoy<=12:
                $inicio=(date("Y")-1) . '-' . '10' . '-' . '01';
                $final=(date("Y")) . '-' .'01'  . '-' . '01';
                $numMeses=3;
                break;
            case $mesHoy>=1 && $mesHoy<=3:
                $inicio=(date("Y")-1) . '-' . '07' . '-' . '01';
                $final=(date("Y")-1) . '-' .'10'  . '-' . '01';
                $numMeses=3;
                break;
            default:
                $inicio=date('Y-m-d');
                $final=date('Y-m-d');
                $numMeses=0;
        }
        
        
        $fecha = $inicio;//(date("Y") - 1) . '-' . date("m") . '-' . '01';
        $cabMes = array();
        for ($i = 1; $i < 4; $i++) {
            $inicio = $fecha;
            $cabMes[$i] = date('m/Y', strtotime($inicio));

            $final = strtotime('+1 month', strtotime($fecha));
            $final = date('Y-m-d', $final);

            $sql = "SELECT id_pe_producto, "
                    . " p.codigo_producto as codigo_producto, "
                    . " p.nombre as nombre, "
                    . " p.peso_real as peso_real,"
                    . " sum(POS1) as cantidad,"
                    . " sum(GEW1) as peso "
                    . " FROM pe_boka b"
                    . " LEFT JOIN pe_productos p ON p.id=b.id_pe_producto"
                    . " WHERE STYP=2 "
                    . " AND ZEIS>='$inicio' "
                    . " AND ZEIS<'$final'"
                    . " GROUP BY id_pe_producto"
                    . " ORDER BY p.codigo_producto";
//echo $sql.'<br>';
            $result = $this->db->query($sql)->result();

            $sql = "SELECT l.id_pe_producto as id_pe_producto, "
                    . " p.codigo_producto as codigo_producto, "
                    . " p.nombre as nombre, "
                    . " sum(l.cantidad) as cantidad"
                    . " FROM pe_orders_prestashop o "
                    . " LEFT JOIN pe_lineas_orders_prestashop l ON o.id=l.id_order "
                    . " LEFT JOIN pe_productos p ON p.id=l.id_pe_producto"
                    . " WHERE o.fecha>='$inicio' "
                    . " AND o.fecha<'$final'"
                    . " GROUP BY l.id_pe_producto"
                    . " ORDER BY p.codigo_producto";
            $resultP = $this->db->query($sql)->result();
// echo $sql.'<br>';

            foreach ($result as $k => $v) {
//echo $v->id_pe_producto.' ';
                if (!array_key_exists($v->id_pe_producto, $ventasMensuales)) {
                    $ventasMensuales[$v->id_pe_producto] = array();
                    $datosProducto[$v->id_pe_producto] = array();
                }
                $ventasMes = $v->peso;
                if ($v->peso == 0)
                    $ventasMes = $v->cantidad;
                else {
//$peso_real=$this->productos_->getPesoReal($v->id_pe_producto);
                    if ($v->peso_real == 0) {
//producto vendido a peso pero que no tiene asignado peso_real en pe_productos
//echo $v->peso,' '.$v->id_pe_producto.' / ';
                        $ventasMes = 1;
                    } else {
                        $ventasMes = ceil($v->peso / $v->peso_real);
                    }
                }
                $ventasMensuales[$v->id_pe_producto][$i] = $ventasMes;
                $nombre=$v->nombre;
                $nombre=str_replace(',',' ',$nombre);
                $nombre=str_replace('"',' ',$nombre);
                $datosProducto[$v->id_pe_producto] = array('codigo_producto' => $v->codigo_producto, 'nombre' => $nombre);
            }
            foreach ($resultP as $k => $v) {
//echo $v->id_pe_producto.' ';
                if (!array_key_exists($v->id_pe_producto, $ventasMensuales)) {
                    $ventasMensuales[$v->id_pe_producto] = array();
                    $datosProducto[$v->id_pe_producto] = array();
                }
                $ventasMes = $v->cantidad;
                if (isset($ventasMensuales[$v->id_pe_producto][$i]))
                    $ventasMensuales[$v->id_pe_producto][$i] += $ventasMes;
                else {
                    $ventasMensuales[$v->id_pe_producto][$i] = $ventasMes;
                    $nombre=$v->nombre;
                $nombre=str_replace(',',' ',$nombre);
                $nombre=str_replace('"',' ',$nombre);
                $datosProducto[$v->id_pe_producto] = array('codigo_producto' => $v->codigo_producto, 'nombre' => $nombre);
                }
            }



            $fecha = $final;
        }

//ksort($ventasMensuales);
        $this->load->model('productos_');

        $lineas = array();
        $linea = array();

        $linea[] = 'Código 13';
        $linea[] = 'Producto';
        for ($i = 1; $i < 4; $i++) {
            $linea[] = $cabMes[$i];
        }
        $linea[] = 'Venta Promedio 15 días';
        $lineas[] = $linea;

        foreach ($ventasMensuales as $k => $v) {
            $linea = array();
            $codigo_producto = $datosProducto[$k]['codigo_producto'];  //$this->productos_->getCodigoProducto($k);
            $nombre = $datosProducto[$k]['nombre']; //$this->productos_->getNombre($k);
            if ($codigo_producto == "")
                continue;
            $linea[] = $codigo_producto;
            $linea[] = $nombre;
            $suma = 0;
            for ($i = 1; $i < 4; $i++) {
                if (isset($v[$i]))
                    $c = $v[$i];
                else
                    $c = 0;
                $linea[] = $c;
                $suma += $c;
            }
            $promedio = ceil($suma / 6);
            $linea[] = $promedio;
            $lineas[] = $linea;
            $stock_minimo = $promedio * 1000;
            $this->db->query("UPDATE pe_productos SET stock_minimo='$stock_minimo' WHERE id='$k'");
        }

        $dato = array();
        $dato['lineas'] = $lineas;
        $hoy=date('Y-m-d');
        log_message('INFO', "INSERT INTO pe_datos_aplicacion SET mes_stocks_minimos='$hoy'");
        $this->db->query("INSERT INTO pe_datos_aplicacion SET mes_stocks_minimos='$hoy'");
        
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $dato['mes_stocks_minimos']=strtoupper(strftime('%B %Y'));

        foreach ($lineas as $k => $v) {
            $ordenado[$v[0]] = $v;
        }
        ksort($ordenado);
        $dato['lineas'] = $ordenado;

        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('stocksMinimos', $dato);
        $this->load->view('templates/footer.html');
        return;
    }    
        
    function productosStock0oMenor(){
        $this->load->library('excel');

        $this->load->helper('maba');
        //$this->load->library('drawing');
        
        $hoja = 0;
        
        $datos['productos']=$this->stocks_model->productosStock0oMenor();
      
       
        $this->load->view('productosStock0oMenor',$datos);
       
        
    }    
    function deshacerTransformacion($id){
            //echo $id.'<br>';
            $this->stocks_model->deshacerTransformacion($id);
            
            $this->load->helper('url');
            redirect('gestionTablas/transformaciones');
    }    
        
    function datosCodigo13($codigo_producto){
        
        $this->load->model('productos_');
        $id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);
        $codigo_bascula=$this->productos_->getCodigoBascula($id_pe_producto);
        $cantidad=$this->stocks_model->getCantidadTotal($id_pe_producto);
        $tipoUnidad=$this->productos_->getUnidad($id_pe_producto);
        $nombre=$this->productos_->getNombre($id_pe_producto);
        $imagen=$this->productos_->getImagen($id_pe_producto);
        $fechaHasta=date('Y-m-d');
        $fechaDesde=date('Y-m-d',strtotime ( '-1 year' , strtotime ( $fechaHasta ) )) ;
        $this->load->model('estadisticas_model');
        $datos=$this->estadisticas_model->getCantidadesVentas($id_pe_producto,$fechaDesde,$fechaHasta);
        if($tipoUnidad=='Kg')
            $cantidad= number_format ($cantidad/1000,3);
        if($tipoUnidad=='Und')
            $cantidad= number_format ($cantidad/1000,0);
        
        echo  json_encode(array('codigo_bascula'=>$codigo_bascula,'datos'=>$datos,'fechaDesde'=>$fechaDesde,'fechaHasta'=>$fechaHasta,'imagen'=>$imagen,'nombre'=>$nombre,'tipoUnidad'=>$tipoUnidad,'cantidad'=>$cantidad));
    }
    
    function depuracion(){
        $this->load->model('productos_');
        //poner stocks fecha caducidad con la activo = status_producto
        $sql="SELECT * FROM pe_stocks WHERE 1";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id_pe_producto=$v->id_pe_producto;
            $activo=$this->productos_->getStatusProducto($id_pe_producto);
            $this->db->query("UPDATE pe_stocks SET activo='$activo' WHERE id_pe_producto='$id_pe_producto'");
        }
        
        //depuración stocks_totales con info pe_stocks
        $this->db->query("DELETE FROM pe_stocks_totales WHERE 1");
        $result=$this->db->query("SELECT * FROM pe_productos WHERE 1")->result();
        $hoy=date("Y-m-d");
        foreach($result as $k=>$v){
            $id_pe_producto=$v->id;
            $codigo_bascula=$v->id_producto;
            $proveedor=$v->id_proveedor_web;
            $activo=$v->status_producto;
            $cantidad=$this->db->query("SELECT sum(cantidad) as cantidad FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'")->row()->cantidad;
            $valoracion=$this->productos_->getValoracion($id_pe_producto,$cantidad);
            //echo $valoracion.'<br>';
            $ultimaFecha=$hoy;
            if ($this->db->query("SELECT fecha_modificacion_stock as fecha FROM pe_stocks WHERE id_pe_producto='$id_pe_producto' ORDER BY fecha_modificacion_stock DESC")->num_rows()>0){
               $ultimaFecha= $this->db->query("SELECT fecha_modificacion_stock as fecha FROM pe_stocks WHERE id_pe_producto='$id_pe_producto' ORDER BY fecha_modificacion_stock DESC")->row()->fecha;
            }
            $this->db->query("INSERT INTO pe_stocks_totales 
                                     SET cantidad='$cantidad',
                                         codigo_producto='$id_pe_producto',
                                         codigo_bascula='$id_pe_producto',
                                         proveedor='$proveedor',
                                         fecha_modificacion_stock='$ultimaFecha',
                                         id_pe_producto='$id_pe_producto',
                                         nombre='$id_pe_producto',    
                                         activo='$activo', 
                                         valoracion='$valoracion'
                                         ");    
        }           
        
         //comprobacion códigos productos existen en stocks_totales
        $sql="SELECT * FROM pe_productos WHERE status_producto=1";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $id_proveedor_web=$v->id_proveedor_web;
            if($this->db->query("SELECT cantidad FROM pe_stocks_totales WHERE codigo_producto='$id'")->num_rows()==0){
               echo $v->codigo_producto.' '.$v->id_producto.' '.$v->status_producto.' '.$v->nombre.'<br>';
               $sql="INSERT INTO   pe_stocks SET  cantidad=0, proveedor='$id_proveedor_web', codigo_producto='$id', codigo_bascula='$id', id_pe_producto='$id'";
               $this->db->query($sql); 
               $sql="INSERT INTO   pe_stocks_totales SET cantidad=0, proveedor='$id_proveedor_web', codigo_producto='$id', codigo_bascula='$id', id_pe_producto='$id'";
               $this->db->query($sql); 
            }
        }
        
        //eliminar info en stocks con cantidad 0
        $sql="SELECT id,cantidad FROM pe_stocks WHERE 1";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            if($v->cantidad==0)
                $this->db->query("DELETE FROM pe_stocks WHERE id='$id'");
        }
        
        
  
        $dato=array();
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('depuracion',$dato);
        $this->load->view('templates/footer.html');
    }
    
    function bajarExcelStocksTotales(){
        $resultado=$this->stocks_model->bajarExcelStocksTotales();
        echo  json_encode(true);
    }  

    function exportXLS(){
        $this->load->library('excel');
        $sql="SELECT p.codigo_producto as codigo_producto,p.nombre as nombre,s.cantidad as cantidad FROM pe_stocks_totales s 
              LEFT JOIN pe_productos p ON p.id=s.id_pe_producto
              WHERE p.status_producto=1  
        ";
        $datos['result']=$this->db->query($sql)->result();
        $this->load->view('prepararExcel', $datos);
    }

    function exportExcel($codigo_producto="",$id_producto="",$producto="",$stock="",$fecha="",$proveedor="",$valor){
        $datos['codigo_producto']=$codigo_producto;
        $datos['id_producto']=$id_producto;
        $producto=str_replace("%20"," ",$producto);
        $datos['producto']=$producto;
        $datos['stock']=$stock;
        $datos['fecha']=$fecha;
        $proveedor=str_replace("%20"," ",$proveedor);
        $datos['proveedor']=$proveedor;
        $datos['valor']=$valor;
   
        
        // mensaje('paso por exportExcel ');
        // mensaje('$codigo_producto '.$codigo_producto);
        // mensaje('$id_producto '.$id_producto);
        // mensaje('$producto '.$producto);
        // mensaje('$stock '.$stock);
        // mensaje('$fecha '.$fecha);
        // mensaje('$proveedor '.$proveedor);
        if(preg_match("/([0-9]{1,2}-){2}[0-9]{4}/", $fecha)){
            $my_array  = preg_split("/-/", $fecha);
            $fecha=$my_array[2].'-'.$my_array[1].'-'.$my_array[0];
        }
        if(preg_match("/([0-9]{1,2}-){1}[0-9]{4}/", $fecha)){
            $my_array  = preg_split("/-/", $fecha);
            $fecha=$my_array[1].'-'.$my_array[0];
        }
        
        
        $sql="SELECT 
                p.codigo_producto as codigo_producto,
                p.id_producto as id_producto,
                g.nombre_grupo,
                f.nombre_familia,
                p.nombre as nombre,
                s.cantidad/1000 as cantidad,
                s.fecha_modificacion_stock,
                s.control_stock,
                -- FORMAT(s.valoracion/1000,2) as valoracion,
                p.valoracion as valoracion,
                pr.nombre_proveedor as nombre_proveedor 
              FROM pe_stocks_totales s 
              LEFT JOIN pe_productos p ON p.id=s.id_pe_producto
              LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo
              LEFT JOIN pe_familias f ON p.id_familia=f.id_familia
              LEFT JOIN pe_proveedores pr ON p.id_proveedor_web=pr.id_proveedor
              WHERE p.status_producto=1 AND p.control_stock='Sí' ";    
         $sql.= $codigo_producto!="_"?" AND p.codigo_producto like '%$codigo_producto%'":"";
         $sql.= $id_producto!="_"?" AND p.id_producto like '%$id_producto%'":"";
         $sql.= $producto!="_"?" AND p.nombre like '%$producto%'":"";
         $sql.= $stock!="_"?" AND s.cantidad like '%$stock%'":"";
         $sql.= $fecha!="_"?" AND s.fecha_modificacion_stock like '%$fecha%'":"";
         $sql.= $proveedor!="_"?" AND pr.nombre_proveedor like '%$proveedor%'":"";
         $sql.= " ORDER BY p.codigo_producto ";
		
        $datos['result']=$this->db->query($sql)->result();
        $this->load->library('email');
        $ahora=date('d/m/Y H:i:s');
        if($this->session->categoria!=1){
            enviarEmail($this->email, 'Exportación datos stocks ',host().' - Pernil181','Bajado por: <br>Usuario: '.$this->session->nombre.'<br>Fecha: '.$ahora,3);
        }

        $this->load->view('prepararExcel', $datos);

    }
    
    function exportCSV(){
        $this->load->library('email');
        // get data 
        $resultado=$this->stocks_model->getStockTotalesSinPacks();
        
        // file name 
        $filename = 'stocks_totales_'.date('Ymd').'.csv'; 
        header("Content-Description: File Transfer"); 
        header("Content-Disposition: attachment; filename=$filename"); 
        header("Content-Type: application/csv; ");
        // file creation 
        $file = fopen('php://output', 'w');

        $header = array(iconv('UTF-8', 'CP1252',"Código 13"),"Producto","Cantidad"); 
        fputcsv($file, $header,";");
        foreach ($resultado as $key=>$line){ 
            // $line['codigo13']=(string)$line['codigo13'];
            // while(strlen($line['codigo13']<13)) $line['codigo13']='0'.$line['codigo13'];
            // $line['codigo13']='="' . $line['codigo13'] . '"';
            // $line['codigo13']='=("' . $line['codigo13'] . '")';
            $line['cantidad']=$line['cantidad']==""?0:$line['cantidad'];
            $line['cantidad']=str_replace(",","",$line['cantidad']);
            $line['nombre']=iconv('UTF-8', 'CP1252', $line['nombre']);
          fputcsv($file,$line,";"); 
        }
        fclose($file); 
        
        // 3 -> envio a maba
        $ahora=date('d/m/Y H:i:s');
        if($this->session->categoria!=1){
            enviarEmail($this->email, 'Stocks para presta (CSV)',host().' - Pernil181','Bajado por: <br>Usuario: '.$this->session->nombre.'<br>Fecha: '.$ahora,3);
        }
        exit; 
    } 
        
    
    function bajarExcelStocks(){
        $resultado=$this->stocks_model->bajarExcelStocks();
        echo  json_encode($resultado);
    }
    
     
    function inventarios(){
         //$dato['optionsFormulas']=$this->stocks_model->getFormulas()['optionsFormulas'];
         $dato['optionsProductos']=$this->stocks_model->getProductos()['optionsProductos'];
         
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('inventarios.php',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        $this->load->view('myModalConfirm.php');
     } 
     
     function stocksResumenes(){
         //$dato['optionsFormulas']=$this->stocks_model->getFormulas()['optionsFormulas'];
         //$dato['optionsProductos']=$this->stocks_model->getProductos()['optionsProductos'];
         $dato['resumen']=$this->stocks_model->getStocksResumenes();
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('stocksResumenes.php',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        $this->load->view('myModalConfirm.php');
     } 
     
     
     
     
    function ventaDirecta(){
         //$dato['optionsFormulas']=$this->stocks_model->getFormulas()['optionsFormulas'];
         $dato['optionsProductos']=$this->stocks_model->getProductos()['optionsProductos'];
         $dato['clientes']=$this->clientes_->getClientes();
         $dato['activeMenu']='Stocks';
         $dato['activeSubmenu']="Venta directa";
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('ventaDirecta.php',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        $this->load->view('myModalConfirm.php');
     } 
     
    function transformaciones(){
        $dato['optionsTransformaciones_'] = $this->stocks_model->getTransformaciones()['optionsTransformaciones'];
        $dato['optionsProductos'] = $this->stocks_model->getProductos()['optionsProductos'];
        $dato['siguiente']=$this->stocks_model->siguienteTransformacion();

        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('transformaciones.php', $dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModalExito.php');
        $this->load->view('myModal.php');
        $this->load->view('myModal2.php');
        $this->load->view('myModalConfirmRegistroPrecio.php');
        $this->load->view('myModalConfirmTransformacion.php');
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
        $dato['hola']='Hola';
        $this->load->view('templates/header.html',$dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('entradaTransporte.php',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
    }


     function albaran(){
         $this->load->model('stocks_model');
         $this->load->model('compras_model');
         $this->compras_model->agrupar_proveedores_acreedores();
         //$dato['optionsFormulas']=$this->stocks_model->getFormulas()['optionsFormulas'];
         $dato['optionsProductos']=$this->stocks_model->getProductos()['optionsProductos'];
         $dato['proveedoresAcreedores']=$this->compras_model->getProveedoresAcreedores()['options'];
         $dato['pedidos']=$this->compras_model->getPedidosProveedores()['options'];
         $dato['activeMenu']='Stocks';
         $dato['activeSubmenu']="Albaranes";
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('albaran.php',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        $this->load->view('myModalPregunta.php');
        $this->load->view('myModalPreguntaCaducado.php');
     } 
     
      function producciones(){
        $dato['activeMenu']='Stocks';
        $dato['activeSubmenu']="Entrada producción";
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('templates/footer.html');
     } 
     
     
     
     function _column_cantidad_right_align($value,$row){
            $value/=1000;
            if(intval($value)!=$value) $value=  number_format ($value,3);
            return "<span style=\"width:80%;text-align:right;display:block;\">".$value."</span>";
        // return $value=wordwrap($row->cantidad,50,"",true);
        }
  
     function _column_id_pe_producto_left_align($value,$row){
            return "<span style=\"width:80%;text-align:left;display:block;\">".$value."</span>";
        }   
        
     
     function _column_nombre_left_align($value, $row) {
        return "<span style=\"width:120%;text-align:left;display:block;\">" . $value . "</span>";
    }

    function _column_codigo_producto_left_align($value, $row) {
        $sql = "SELECT codigo_producto FROM pe_productos WHERE id='$value'";
        $value = $this->db->query($sql)->row()->codigo_producto;
        return "<span style=\"width:10%;text-align:right;display:block;\">" . $value . "</span>";
    }

    function stocks_before_venta($post_array) {
        $post_array['cantidad'] = -$post_array['cantidad'];
        return $post_array;
        return true;
    }

    function updateActivo(){
        $sql="UPDATE pe_stocks_totales st
                        LEFT JOIN pe_productos pr ON
                            st.id_pe_producto = pr.id
                        set
                            st.activo = pr.status_producto";
                 $this->db->query($sql);
                 
                 $sql="UPDATE pe_stocks st
                        LEFT JOIN pe_productos pr ON
                            st.id_pe_producto = pr.id
                        set
                            st.activo = pr.status_producto";
                 $this->db->query($sql);
    }
    
    function stocks_insert($post_array,$primay_key){
         
      //log_message('debug', 'SQL: '.$this->db->last_query());
          
         extract($post_array);
         //buscamos id_producto (código báscula)
        // $this->db->select('id_producto');
        // $this->db->from('pe_productos');
        // $this->db->where('codigo_producto', $codigo_producto);
           $sql="SELECT codigo_producto,nombre FROM pe_productos WHERE id='$id_pe_producto'";
           $codigo_producto=$this->db->query($sql)->row()->codigo_producto;
           $nombre=$this->db->query($sql)->row()->nombre;
           
           

         $stock_insert=array(
                 'fecha_entrada'=>fechaEuropeaToBaseDatos($fecha_entrada),
                 'fecha_modificacion_stock'=>fechaEuropeaToBaseDatos($fecha_entrada),
                 'id_pe_producto'=>$id_pe_producto,
                 'codigo_producto'=>$id_pe_producto,
                 'cantidad'=>$cantidad,
                 'fecha_caducidad_stock'=>fechaEuropeaToBaseDatos($fecha_caducidad),
                 );
         $this->db->insert('pe_stocks',$stock_insert);
         
         //actualizamos stocks totales
         $sql="SELECT id,id_pe_producto,cantidad,codigo_producto FROM pe_stocks_totales WHERE id_pe_producto='$id_pe_producto'";
         if($this->db->query($sql)->num_rows()>0)
             {
             $cantidad_actual=$this->db->query($sql)->row()->cantidad;
             $id=$this->db->query($sql)->row()->id;
             $stock_update_totales=array(
                 'fecha_modificacion_stock'=>fechaEuropeaToBaseDatos($fecha_entrada),
                 'cantidad'=>$cantidad+$cantidad_actual,
             );
             $this->db->where('id', $id);
             $this->db->update('pe_stocks_totales',$stock_update_totales); 
             
             $productos_update=array(
                 'stock_total'=>$stock_update_totales['cantidad'],
             );

             $this->db->where('id_pe_producto', $id);
             $this->db->update('pe_productos',$productos_update); 
             
         }
         else {
             $stock_insert_totales=array(
                 'fecha_modificacion'=>fechaEuropeaToBaseDatos($fecha_entrada),
                 'id_pe_producto'=>$id_pe_producto,
                 'codigo_producto'=>$id_pe_producto,
                 'cantidad'=>$cantidad,
                 'nombre'=>$id_pe_producto,
             );
             $this->db->insert('pe_stocks_totales',$stock_insert_totales);  
             $productos_update=array(
                'stock_total'=>$stock_insert_totales['cantidad'],
            );
             $this->db->where('id_pe_producto', $stock_insert_totales['id_pe_producto']);
             $this->db->update('pe_productos',$productos_update); 

         }
         
         
        return true;
     }
     
     function stocks_inventarios($post_array,$primay_key){
         extract($post_array);
         //buscamos id_producto (código báscula)
        // $this->db->select('id_producto');
        // $this->db->from('pe_productos');
        // $this->db->where('codigo_producto', $codigo_producto);
           $sql="SELECT id_producto FROM pe_productos WHERE id='$id_pe_producto'";
           $id_producto=$this->db->query($sql)->row()->id_producto;
         

         
         $this->db->where('id_pe_producto', $id_pe_producto);
         $this->db->delete('pe_stocks');
        
         
         $stock_insert=array(
                 'fecha_inventario'=>fechaEuropeaToBaseDatos($fecha_inventario),
                 'fecha_modificacion_stock'=>fechaEuropeaToBaseDatos($fecha_inventario),
                 'id_pe_producto'=>$id_pe_producto,
                 'id_producto'=>$id_producto,
                 'cantidad'=>$cantidad,
                 'fecha_caducidad_stock'=>fechaEuropeaToBaseDatos($fecha_caducidad),
                 );
         $this->db->insert('pe_stocks',$stock_insert);
        return true;
     }
     
     function validation_cantidad($value){
         if ($value==0){
            $this->form_validation->set_message('validation_cantidad', 'La cantidad NO puede ser 0');
            return false;
         }
         else{
             return true;
         }
     }
     function validation_codigo_producto($value){
         if ($value==0){
            $this->form_validation->set_message('validation_id_producto', 'Debe seleccionar un producto');
            return false;
         }
         else{
             return true;
         }
     }
     
     function validation_fecha_caducidad($value){
         if ($value && fechaEuropeaToBaseDatos($value)<=date('Y-m-d')){
            $this->form_validation->set_message('validation_fecha_caducidad', 'Este producto ESTA caducado. No se puede entrar.'.$value);
            return false;
         }
         else{
             return true;
         }
     }
     
     function fecha_callback($value){
         if($value=="0000-00-00") return "";
         return fechaEuropeaSinHora($value);
     }
     
     function read_producto($value){
         $sql="SELECT nombre, codigo_producto FROM pe_productos WHERE id='$value'";
         $result=$this->db->query($sql)->row();
         return $result->nombre.' ('.$result->codigo_producto.')';
     }
     
     
     function formulas(){
         $dato['optionsFormulas']=$this->stocks_model->getFormulas()['optionsFormulas'];
       //  $dato['optionsProductos']=$this->stocks_model->getProductos()['optionsProductos'];
         $dato['activeMenu']='Stocks';
         $dato['activeSubmenu']="Mantenimiento fórmulas";
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('formulas.php',$dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        $this->load->view('myModalConfirm.php');
     }
     
     function grabarFormula(){
         $resultado="";
         extract($_POST);
         $id_formula=$this->stocks_model->grabarFormula($descripcion,$productoFinal,$cantidadFormula,$lineas);
         
         echo json_encode($id_formula);
     }   
     
     function grabarModificacionFormula(){
         $resultado="";
         extract($_POST);
         $id_formula=$this->stocks_model->grabarModificacionFormula($formulaId,$descripcion,$productoFinal,$cantidadFormula,$lineas);
         
         echo json_encode($id_formula);
     }   
     
  
     function _table_output($output = null, $table="") {
        $this->load->view('templates/header.php', $output);
        $dato['activeMenu']='Stocks';
        $dato['activeSubmenu']=$table;
        $this->load->view('templates/top.php',$dato);
        $this->load->view('table_template.php', $output);
        $this->load->view('templates/footer.html');
    }
    
    function _table_output_stocks($output = null, $table="") {
        $this->load->view('templates/header.php', $output);
        $dato['activeMenu']='Stocks';
        $dato['activeSubmenu']=$table;
        $this->load->view('templates/top.php',$dato);
        $this->load->view('table_template_stocks.php', $output);
        $this->load->view('templates/footer.html');
    }
    
    function _table_output_stocks_totales($output = null, $table="") {
        $this->load->view('templates/header.php', $output);
        $dato['activeMenu']='Stocks';
        $dato['activeSubmenu']=$table;
        $this->load->view('templates/top.php',$dato);
        $this->load->view('table_template_stocks.php', $output);
        $this->load->view('templates/footer.html');
    }
     
    function getFormula(){
        $formula=$_POST['formula'];
        echo json_encode($this->stocks_model->getFormula($formula));
    } 
    
    function getFormulasFiltro(){
         $filtro=$_POST['filtro'];
         $optionsFormulas=$this->stocks_model->getFormulas($filtro)['optionsFormulas'];
         echo json_encode($optionsFormulas);
     }
     
     function getProductosFiltro(){
         $filtro=$_POST['filtro'];
         $id=$_POST['id'];
         $resultadoFiltro=$this->stocks_model->getProductosVenta($filtro,$id);
         
         //$sql=$this->stocks_model->getProductos($filtro)['sql'];
         echo json_encode($resultadoFiltro);
         
     }
     function getProductosCodigoPre(){
        $prefijo=$_POST['preCodigo'];
        $resultado=$this->stocks_model->getProductosCodigoPre($prefijo);
        echo json_encode($resultado);
     }

     function getProductosCodigoBod(){
        $prefijo=$_POST['preCodigo'];
        $resultado=$this->stocks_model->getProductosCodigoBod($prefijo);
        echo json_encode($resultado);
     }

     function getProductosCompraFiltro(){
        $filtro=$_POST['filtro'];
        $resultadoFiltro=$this->stocks_model->getProductosCompra($filtro);
        
        //$sql=$this->stocks_model->getProductos($filtro)['sql'];
        echo json_encode($resultadoFiltro);
        
    }
    
    function getProductosBodegaFiltro(){
        $filtro=$_POST['filtro'];
        $resultadoFiltro=$this->stocks_model->getProductosBodega($filtro);
        
        //$sql=$this->stocks_model->getProductos($filtro)['sql'];
        echo json_encode($resultadoFiltro);
        
    }
    
     
     function getTransformacionesFiltro(){
         $filtro=$_POST['filtro'];
         $id=$_POST['id'];
         $optionsTransformaciones=$this->stocks_model->getTransformaciones($filtro,$id)['optionsTransformaciones'];
         echo json_encode($optionsTransformaciones);
     }
     
     function registrarInventario(){
         $this->stocks_model->registrarInventario($_POST['id_pe_producto'],$_POST['cantidades'],$_POST['fechasCaducidades']);
         echo json_encode($_POST);
     }
     
     function readInventario(){
         $result=$this->stocks_model->readInventario($_POST['id_pe_producto']);
         echo json_encode($result);
     }
     
     function verificacion(){
        //verificación entradas de pe_stock_totales para todo pe_productos
        $datos['productosNoExistentesEnStocksTotales']=array();
        $sql="SELECT * FROM pe_productos";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            if($this->db->query("SELECT * FROM pe_stocks_totales WHERE id_pe_producto='".$v->id."'")->num_rows()==0){
                $datos['productosNoExistentesEnStocksTotales'][]= $v->id.' '.$v->codigo_producto.' '.$v->nombre.'<br>';
            }
        }
        //verificacion que todos los productos de pe_stock_totales corresponden a un pe_productos
        $datos['productosNoExistentesEnProductos']=array();
        $sql="SELECT * FROM pe_stocks_totales";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            if($this->db->query("SELECT * FROM pe_productos WHERE id='".$v->id_pe_producto."'")->num_rows()==0){
                $datos['productosNoExistentesEnProductos'][]= $v->id.' '.$v->codigo_producto.' '.$v->nombre.'<br>';
            }
        }
        
        $datos['productosNoExistentesEnProductos2']=array();
        $sql="SELECT * FROM pe_stocks";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            if($this->db->query("SELECT * FROM pe_productos WHERE id='".$v->id_pe_producto."'")->num_rows()==0){
                $datos['productosNoExistentesEnProductos2'][]= $v->id.' '.$v->codigo_producto.' '.$v->nombre.'<br>';
            }
        }
        
        $datos['sumaStocks']=$this->db->query("SELECT sum(cantidad) as total FROM pe_stocks")->row()->total;
        $datos['sumaStocksTotales']=$this->db->query("SELECT sum(cantidad) as total FROM pe_stocks_totales")->row()->total;
        $datos['productosConDiferencias']=array();
        if(true ){//|| $datos['sumaStocks']!=$datos['sumaStocksTotales']){
            $sql="SELECT * FROM pe_productos";
            $result=$this->db->query($sql)->result();
            foreach($result as $k=>$v){
                $total1=$this->db->query("SELECT sum(cantidad) as total FROM pe_stocks WHERE id_pe_producto='".$v->id."'")->row()->total;
                if(is_null($total1)) $total1=0;
                $total2=$this->db->query("SELECT sum(cantidad) as total FROM pe_stocks_totales WHERE id_pe_producto='".$v->id."'")->row()->total;
                if($total1!=$total2) {
                    $datos['productosConDiferencias'][]='id= '.$v->id.' Codigo13= '.$v->codigo_producto.' Nombre= '.$v->nombre.' pe_stocks= '.$total1.' pe_stocks_totales= '.$total2;
                    echo $total1.' '.$total2.'<br>';
                    
                }
            }
        }
        
        $total1=$this->db->query("SELECT sum(cantidad) as total FROM pe_stocks WHERE id_pe_producto='478'")->row()->total;
        $total2=$this->db->query("SELECT sum(cantidad) as total FROM pe_stocks_totales WHERE id_pe_producto='478'")->row()->total;
       // echo $total1.' '.$total2;
        $dato=array();
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php',$dato);
        $this->load->view('verificacionStocks',$datos);
        $this->load->view('templates/footer.html');
     }
        
}
?>