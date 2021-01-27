<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    exit("<h2>No está permitido el acceso directo a esta URL</h2>");

class Upload extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('upload_');
        $this->informe = "";
        $this->load->library('email');
        $this->load->database();
        ini_set("memory_limit", "1024M");
    }

    function index()
    {
        $dato['autor'] = 'Miguel Angel Bañolas';
        $data = array();
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('upload_form', array('error' => ' '));
        $this->load->view('templates/footer.html', $dato);
    }

    //crea datos de pe_registro_ventas del último Boka subido
    //se separa este script de la subida boka por problemas de tiempo de ejecución
    function registroVentas($ultimoIDAnterior, $ultimoIDActual)
    {
        //se comprueba si existen bokas para subir
        $this->load->model('productos_');
        $this->load->model('stocks_model');

        //if($this->db->query("SELECT * FROM pe_boka_auxiliar")->num_rows()){
        //$row=$this->db->query("SELECT * FROM pe_boka_auxiliar")->row();
        // $ultimoIDAnterior=$row->ultimo_id_anterior;
        // $ultimoIDActual=$row->ultimo_id_actual;
        $sql = "SELECT zeis
            FROM pe_boka b
            WHERE STYP=1 AND id>$ultimoIDAnterior AND id<=$ultimoIDActual group by zeis";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $fechaVenta = $v->zeis;
            $this->db->query("DELETE FROM pe_registro_ventas WHERE fecha_venta='$fechaVenta' and tipo_tienda=1");
        }

        $sql = "SELECT id
                  FROM pe_boka b
                  WHERE STYP=2 AND id>$ultimoIDAnterior AND id<=$ultimoIDActual";
        // echo $sql.'<br >';
        $result = $this->db->query($sql)->result();

        foreach ($result as $k => $v) {
            $idBoka = $v->id;
            $this->productos_->registrarVentaTienda($idBoka,1);
        }
        //poner num_cliente, id_familia, id_ grupo
        $sql = "SELECT SNR2,ZEIS,RASA
                  FROM pe_boka b
                  WHERE STYP=1 AND id>$ultimoIDAnterior AND id<=$ultimoIDActual";
        // mensaje($sql);      
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $cliente = $v->SNR2;
            $fecha_venta = $v->ZEIS;
            $num_ticket = $v->RASA;
            while (strlen($cliente) < 6) {
                $cliente = "0" . $cliente;
            }
            // mensaje("UPDATE pe_registro_ventas SET num_cliente='$cliente' WHERE tipo_tienda=1 AND num_ticket='$num_ticket' AND fecha_venta='$fecha_venta'");
            $this->db->query("UPDATE pe_registro_ventas SET num_cliente='$cliente' WHERE tipo_tienda=1 AND num_ticket='$num_ticket' AND fecha_venta='$fecha_venta'");
        }
        $sql = "SELECT id_pe_producto
                  FROM pe_boka b
                  WHERE STYP=2 AND id>$ultimoIDAnterior AND id<=$ultimoIDActual GROUP BY id_pe_producto";
        // mensaje($sql);       
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $id_pe_producto = $v->id_pe_producto;
            $idGrupo = $this->productos_->getIdGrupo($id_pe_producto);
            $idFamilia = $this->productos_->getIdFamilia($id_pe_producto);
            // mensaje("UPDATE pe_registro_ventas SET grupo='$idGrupo', familia='$idFamilia' WHERE tipo_tienda=1 AND id_pe_producto='$id_pe_producto' AND grupo IS NULL AND familia IS NULL");
            $this->db->query("UPDATE pe_registro_ventas SET grupo='$idGrupo', familia='$idFamilia' WHERE tipo_tienda=1 AND id_pe_producto='$id_pe_producto' AND grupo IS NULL AND familia IS NULL");
        }
        //borramos datos de pe_boka_auxiliar
        /*
            $this->db->query("DELETE FROM pe_boka_auxiliar WHERE 1");
            $dato['autor'] = 'Miguel Angel Bañolas';
            $dato['mensaje']="Finalizados datos último Boka con éxito.";
            $dato['error']=false;
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('registroVentas', $dato);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
            */
    }

    function do_upload()
    {
        //comprobamos si existen datos de Boka pendientes de registrar ventas
        /*
        if($this->db->query("SELECT * FROM pe_boka_auxiliar")->num_rows()){
            $dato['autor'] = 'Miguel Angel Bañolas';
            $dato['mensaje']="Existen datos Boka anteriores pendientes de registrar ventas. Primero Finalizar Boka último.";
            $dato['error']=true;
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('registroVentas', $dato);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
            return;
        }
        */

        //subir Boka
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'txt';
        $config['max_size'] = '1000';

        $this->load->library('upload', $config);


        if (!$this->upload->do_upload()) {
            //$error = array('error' => $this->upload->display_errors());
            $error = array('error' => $this->upload->display_errors(), 'data' => array('orig_name' => $this->upload->data()['file_name']));

            $dato['autor'] = 'Miguel Angel Bañolas';
            $data = array();
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_form', $error);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
        } else {
            $tiempo_inicio = microtime(true);
            $datosArchivo = $this->upload_->getDatos($this->upload->data());

            $tiempo_fin = microtime(true);
            // mensaje( "Upload - getDatos: " . ($tiempo_fin - $tiempo_inicio));

            if (!array_key_exists('exito', $datosArchivo)) {

                $dato['productoNoExistente'] = $datosArchivo['productoNoExistente'];
                $dato['resultado'] = array_key_exists('exito', $datosArchivo) ? "No se ha subido los datos de Boka porque ya existen " : "";
                $dato['upload_data'] = array_key_exists('datosArchivo', $datosArchivo) ? $datosArchivo['datosArchivo'] : "";
                $dato['tickets'] = 0;
                $dato['exito'] = array_key_exists('exito', $datosArchivo) ? $datosArchivo['exito'] : "";
                $dato['codigosSubidos'] = 0;
                $dato['base'] = array();
                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                //$this->load->view('upload_error', $dato);
                $this->load->view('upload_success', $dato);

                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }

            if ($datosArchivo['productoNoExistente']) {
                $dato['productoNoExistente'] = $datosArchivo['productoNoExistente'];
                $dato['resultado'] = "No se ha subido los datos. Error base datos productos";
            } else {
                $dato['upload_data'] = $datosArchivo['datosArchivo'];
                $dato['resultado'] = $datosArchivo['resultado'];
                $dato['linea'] = array_key_exists('linea', $datosArchivo) ? $datosArchivo['linea'] : "";
                $dato['tickets'] = array_key_exists('tickets', $datosArchivo) ? $datosArchivo['tickets'] : "";
                $dato['codigosSubidos'] = array_key_exists('codigosSubidos', $datosArchivo) ? $datosArchivo['codigosSubidos'] : "";
                $dato['base'] = array_key_exists('base', $datosArchivo) ? $datosArchivo['base'] : array();
                $dato['exito'] = $datosArchivo['exito'];
                $dato['iva'] = array_key_exists('iva', $datosArchivo) ? $datosArchivo['iva'] : "";
                $dato['total'] = array_key_exists('total', $datosArchivo) ? $datosArchivo['total'] : "";
                $dato['productoNoExistente'] = $datosArchivo['productoNoExistente'];
                $dato['fecha'] = array_key_exists('fecha', $datosArchivo) ? $datosArchivo['fecha'] : "";
                $dato['resultadoPreciosTarifas'] = array_key_exists('resultadoPreciosTarifas', $datosArchivo) ? $datosArchivo['resultadoPreciosTarifas'] : "";
                $dato['resultadosStocksTotales'] = array_key_exists('resultadosStocksTotales', $datosArchivo) ? $datosArchivo['resultadosStocksTotales'] : "";

                $resumen = '<br />' . 'TOTAL ' . $dato['tickets'] . ' Tickets' . '<br />' . $dato['codigosSubidos'] . '<br>';

                //para no reportar las ventas
                $ventas = '';

                //registra ventas   
                if (isset($datosArchivo['ultimoIDAnterior']) && isset($datosArchivo['ultimoIDActual']))
                    $this->registroVentas($datosArchivo['ultimoIDAnterior'], $datosArchivo['ultimoIDActual']);

                $reportPesoVariasUnidades = "";
                if (array_key_exists('reportPesoVariasUnidades', $datosArchivo)) {
                    if ($datosArchivo['reportPesoVariasUnidades'] != "") {
                        $reportPesoVariasUnidades = '<br><h3 style="color:red;">' . 'Posibles productos vendidos a peso con varias unidades </h3>' . $datosArchivo['reportPesoVariasUnidades'];
                    }
                }

                if (array_key_exists('fecha', $datosArchivo)) {
                    $mensaje = $resumen . '<br>'
                        . '<h3>Comparación precios con diferencias Base Datos productos y Boka' . ' (' . $datosArchivo['fecha'] . ')</h3>'
                        . $datosArchivo['resultadoPreciosTarifas'] . '<br>';
                    $mensaje .= '<br>Fin del informe.';
                    $from = host() . ' - Boka';
                    $subject = 'Informe Boka subido. Precios Boka vs BD productos.';
                    enviarEmail($this->email, $subject, $from, $mensaje, 1);
                }


                if (array_key_exists('fecha', $datosArchivo)) {
                    $mensaje = $resumen . '<br>'
                        . '<h3>Productos con stocks iguales o inferiores al mínimo stock ' . ' (' . $datosArchivo['fecha'] . ')</h3>'
                        . $datosArchivo['resultadosStocksTotales'] . $reportPesoVariasUnidades;
                    $mensaje .= '<br>Fin del informe.';
                    $from = host() . ' - Boka';
                    $subject = 'Informe Boka subido. Stocks.';
                    enviarEmail($this->email, $subject, $from, $mensaje, 5);
                }
                /*
                if (array_key_exists('fecha', $datosArchivo)) {
                    $ventas = '';
                    $asignaciones='<h3>Ningún producto vendido a peso</h3>';
                    if($this->db->query("SELECT * FROM pe_asignacion_productos")->num_rows()>0){
                        $result=$this->db->query("SELECT a.id_producto as id_producto,"
                                . " a.num_productos as num_productos,"
                                . " a.peso_vendido as peso_vendido,"
                                . " a.peso_asignado as peso_asignado,"
                                . " p.codigo_producto as codigo_producto,"
                                . " a.rangos as rangos,"
                                . " p.nombre as nombre FROM pe_asignacion_productos a"
                                . " LEFT JOIN pe_productos p ON p.id=a.id_asignado")->result();
                        $asignaciones='<table >';
                        $asignaciones.="<tr >";
                            $asignaciones.="<th style='border-bottom:1px solid black'>"."Código Boka"."</th>";
                            $asignaciones.="<th style='border-bottom:1px solid black'>"."Peso vendido (g)"."</th>";
                            
                            $asignaciones.="<th style='border-bottom:1px solid black'>Rangos</th>";
                            $asignaciones.="<th style='border-bottom:1px solid black'>"."Peso asignado"."</th>";
                            $asignaciones.="<th style='border-bottom:1px solid black'>"."Código asignado"."</th>";
                            $asignaciones.="<th style='border-bottom:1px solid black'>"."Nombre Producto"."</th>";
                            
                            $asignaciones.="</tr>";
                        
                        foreach($result as $k=>$v){
                            $asignaciones.="<tr>";
                            $asignaciones.="<td style='border-bottom:1px solid black'>".$v->id_producto."</td>";
                            $asignaciones.="<td style='border-bottom:1px solid black'>".$v->peso_vendido."</td>";
                            
                            $asignaciones.="<td style='border-bottom:1px solid black'>".$v->rangos."</td>";
                            $asignaciones.="<td style='border-bottom:1px solid black'>".$v->peso_asignado."</td>";
                            $asignaciones.="<td style='border-bottom:1px solid black'>".$v->codigo_producto."</td>";
                            $asignaciones.="<td style='border-bottom:1px solid black'>".$v->nombre."</td>";
                            
                            $asignaciones.="</tr>";
                        }
                        $asignaciones.="</table>";
                    }
                    
                    $message=$resumen . '<br>'
                       . $ventas . '<h3>Asignación codigos productos vendidos a peso' . ' (' . $datosArchivo['fecha'] . ')</h3>'
                    . $asignaciones;
                    $from=host().' - Boka';
                    $subject='Asignación códigos ventas a peso.';
                    $asignaciones='<h3>Productos vendidos a peso: asignación a códigos unitarios</h3><br>'.$asignaciones.'<br>Fin del informe.';
                    enviarEmail($this->email, $subject,$from,$asignaciones,1);
                
                }
                */
            }

            $dato['autor'] = 'Miguel Angel Bañolas';
            $data = array();
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_success', $dato);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
        }
    }


    function do_upload_prestashop()
    {

        $config['upload_path'] = './uploads/prestashop';
        $config['allowed_types'] = array('csv');
        $config['max_size'] = '6000';

        $this->load->library('upload', $config);

        $dato['resultado'] = "Resultado";
        $dato['upload_data'] = array();
        $dato['codigos'] = array();

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors(), 'data' => array('orig_name' => $this->upload->data()['file_name']));

            $dato['autor'] = 'Miguel Angel Bañolas';
            $data = array();
            $this->load->view('templates/header.html', $dato);

            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_prestashop_form', $error);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
        } else {
            //echo 'leyendo datos<br>';
            $datosArchivo = $this->upload_->getDatosPrestashop($this->upload->data());
            if (!isset($datosArchivo['packSinComponentes'])) $datosArchivo['packSinComponentes'] = array();



            $dato['pedidosSubidos'] = false;
            //informe YA existentes y no se sube archivo (pedido ya existente con el mismo valor de valid)
            //Se informa sobre pedidos ta subidos en pantalla y ventana modal
            //en página upload_prestashop_form
            //se vuelve a solicitar archivo


            if (isset($datosArchivo['pedidoNoExistente'])) {
                $nombreArchivo = $this->upload->data()['orig_name'];



                $message = "";
                $message .= "<h3>Informe PrestaShop</h3>";
                $message .= "<h4 style='color:red;' >NO se ha podido subir NINGÚN pedido del Archivo $nombreArchivo.</h4>";
                $message .= "<h4 style='color:red;' >Motivo: Alguno de los pedidos para eliminar (valid = -1) No existe. </h4>";
                $message .= "<h4 style='color:red;' >(Mismo número pedido y valor de valid)</h4>";

                $message .= "<h4 style='color:red;' >Corregir el contenido del archivo y volverlo a subir.</h4>";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_prestashop_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }

            if (isset($datosArchivo['pedidosYaExistentes'])) {
                $nombreArchivo = $this->upload->data()['orig_name'];

                sort($datosArchivo['pedidosYaExistentes']);
                $salida1 = implode(", ", $datosArchivo['pedidosYaExistentes']);
                $salida1 .= '<br>';

                $message = "";
                $message .= "<h3>Informe PrestaShop</h3>";
                $message .= "<h4 style='color:red;' >NO se ha podido subir NINGÚN pedido del Archivo $nombreArchivo.</h4>";
                $message .= "<h4 style='color:red;' >Motivo: los siguientes pedidos YA ESTAN SUBIDOS: </h4>";
                $message .= "<h4 style='color:red;' >(Mismo número pedido y valor de valid)</h4>";
                $message .= "<h4 style='color:red;' >$salida1</h4>";
                $message .= "<h4 style='color:red;' >Corregir el contenido del archivo y volverlo a subir.</h4>";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_prestashop_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }
            if (isset($datosArchivo['pedidosNoExistentes'])) {
                $nombreArchivo = $this->upload->data()['orig_name'];

                sort($datosArchivo['pedidosNoExistentes']);
                $salida1 = implode(", ", $datosArchivo['pedidosNoExistentes']);
                $salida1 .= '<br>';

                $message = "";
                $message .= "<h3>Informe PrestaShop</h3>";
                $message .= "<h4 style='color:red;' >NO se ha podido RETORNAR NINGÚN pedido del Archivo $nombreArchivo.</h4>";
                $message .= "<h4 style='color:red;' >Motivo: los siguientes pedidos NO EXISTEN: </h4>";
                $message .= "<h4 style='color:red;' >$salida1</h4>";
                $message .= "<h4 style='color:red;' >Corregir el contenido del archivo y volverlo a subir.</h4>";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_prestashop_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }


            //No es un archivo Prestashop 
            //Se informa sobre pedidos ta subidos en pantalla y ventana modal
            //en página upload_prestashop_form
            //se vuelve a solicitar archivo
            if (isset($datosArchivo['noArchivoPrestashop'])) {
                $nombreArchivo = $this->upload->data()['orig_name'];

                $message = "";
                $message .= "<h3>Informe PrestaShop</h3>";
                $message .= "<h4 style='color:red;' >El archivo $nombreArchivo NO se puede subir porque NO es un archivo válido de datos Prestashop.</h4>";
                $message .= "<h4 style='color:red;' >La primera fila debe contener: Order No | Valid | Customer id grup | Product Quantity | Product Ref | Product Name | Product Price - PVP - iva incl | Total discounts | Total products with tax | Total paid tax excl | Total shipping | Total products | Date added | Customer Email | Customer id language | Reference | Delivery Firstname | Delivery Lastname | Delivery country | Shop name | Customer id | Delivery address line 1 | Delivery address line 2 | Delivery postcode | Delivery city </h4>";

                $message .= "<h4 style='color:red;' >Y las columas contener los correspodientes valores.</h4>";
                $message .= "<h4 style='color:red;' >Corregir el contenido del archivo y volverlo a subir.</h4>";



                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_prestashop_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }
            //No es un archivo con extensión válida
            //No se llegará nunca aquí porque se rechaza en el anteior paso noArchivoPrestashop
            if (isset($datosArchivo['noExcel'])) {
                $nombreArchivo = $this->upload->data()['orig_name'];

                $message = "";
                $message .= "<h3>Informe PrestaShop</h3>";
                $message .= "<h4 style='color:red;' >El archivo $nombreArchivo NO se puede subir porque NO es un archivo con extensiones .xls o .xlsx</h4>";
                $message .= "<br>Fin del informe";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_prestashop_form', $dato);
                //$this->load->view('upload_prestashop_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }

            //detectado error iva
            if (isset($datosArchivo['erroresIva'])) {
                $message = $datosArchivo['resultado'];
                //log_message('INFO',$message);
                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                //$dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_prestashop_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }


            //si no encuentra los anteriores errores se muestra pantalla success con los datos de lo subido
            $dato['resultado'] = '';
            $dato['codigos'] = array();
            $dato['nombres'] = '';
            $dato['cantidades'] = '';
            $dato['importes'] = '';
            $dato['productoNoExistente'] = '';
            $dato['pedidos'] = array();
            $dato['validos'] = array();
            $dato['codigosPack'] = array();



            $dato['upload_data'] = $datosArchivo['datosArchivo'];
            $dato['resultado'] = $datosArchivo['resultado'];
            $dato['codigos'] = $datosArchivo['codigos'];
            if (isset($datosArchivo['pedidos']))
                $dato['pedidos'] = $datosArchivo['pedidos'];
            if (isset($datosArchivo['validos']))
                $dato['validos'] = $datosArchivo['validos'];
            $dato['nombres'] = $datosArchivo['nombres'];
            $dato['cantidades'] = $datosArchivo['cantidades'];
            $dato['importes'] = $datosArchivo['importes'];
            if (isset($datosArchivo['codigosPack']))
                $dato['codigosPack'] = $datosArchivo['codigosPack'];
            $dato['productoNoExistente'] = $datosArchivo['productoNoExistente'];
            $dato['packSinComponentes'] = $datosArchivo['packSinComponentes'];
            $dato['resultadoPreciosTarifas'] = $datosArchivo['resultadoPreciosTarifas'];
            $dato['resultadosStocksTotales'] = $datosArchivo['resultadosStocksTotales'];


            if ($datosArchivo['email']) {

                $fecha = substr($datosArchivo['fecha'], 8, 2) . '/' . substr($datosArchivo['fecha'], 5, 2) . '/' . substr($datosArchivo['fecha'], 0, 4);

                if (true) {

                    //chequear pedidos con facturas con ivas incorrectos

                    $from = host() . ' - PrestaShop';

                    $subject = 'Informe Archivo PrestaShop. Comparación PVP con tarifa ventas. ';

                    sort($datosArchivo['codigoNoEncontrados']);
                    $salida1 = implode("<br>", $datosArchivo['codigoNoEncontrados']);


                    $message = "";
                    $message .= "<h3>Informe PrestaShop</h3>";
                    $message .= "<h4>Archivo subido: " . $datosArchivo['datosArchivo']['orig_name'] . "</h4>";

                    if (isset($datosArchivo['errorSubida']) && $datosArchivo['errorSubida'] == 5) {
                        $message .= "<h3>El archivo no se ha podido subir y registrar. <br>Los siguientes productos<br>- NO están dados de alta en la base de datos de productos<br>- o están descatalogados<br>- o tienen el control de stock en No:<br><br>" . $salida1;
                        $message .= "<br><br>Proceder a darlos de alta y volver a cargar el archivo.</h3>";
                    }
                    if (isset($datosArchivo['errorSubida']) && $datosArchivo['errorSubida'] == 6) {
                        $salida2 = implode("<br>", $datosArchivo['pedidosSinFecha']);
                        $message .= "<h3>El archivo no se ha podido subir y registrar. <br>Los siguientes pedidos NO tienen fechas:<br><br>" . $salida2;
                        $message .= "<br><br>Completar las fechas en el archivo y volverlo a subir.</h3>";
                    }
                    if (isset($datosArchivo['errorSubida']) && $datosArchivo['errorSubida'] == 7) {
                        $salida3 = implode("<br>", $datosArchivo['packSinComponentes']);
                        $message .= "<h3>El archivo no se ha podido subir y registrar. <br>Los siguientes Packs NO tienen componentes:<br><br>" . $salida3;
                        $message .= "<br><br>Introducir los componentes del Pack y volverlo a subir.</h3>";
                    }
                    if (!isset($datosArchivo['errorSubida'])) {
                        $message .= '<h3>Productos vendidos a precios diferentes versus tarifa PVP tabla productos</h3>';
                        if (isset($datosArchivo['resultadoPreciosTarifas']['salida']) && $datosArchivo['resultadoPreciosTarifas']['salida'] != "")
                            $message .= $datosArchivo['resultadoPreciosTarifas']['salida'];
                        else {
                            $message .= "Ningún producto encontrado con diferencias precios<br>";
                        }
                    }

                    $message .= "<br>Fin del informe";

                    enviarEmail($this->email, $subject, $from, $message, 1);


                    if (!isset($datosArchivo['errorSubida'])) {
                        $from = host() . ' - PrestaShop';


                        $subject = 'Informe Archivo PrestaShop. Informe stocks mínimos.';
                        $message = "";
                        $message .= "<h3>Informe PrestaShop</h3>";
                        $message .= "<h4>Archivo subido: " . $datosArchivo['datosArchivo']['orig_name'] . "</h4>";

                        $numNoComparados = isset($datosArchivo['resultadoPreciosTarifas']['numNoComparados']) ? $datosArchivo['resultadoPreciosTarifas']['numNoComparados'] : 0;
                        if ($numNoComparados > 0) {
                            $message .= "<br>$numNoComparados productos No comparados por ser Packs y/o clientes grupo 9.<br>";
                        }
                        if (!isset($datosArchivo['errorSubida'])) {
                            $message .= '<h3>Productos con stocks iguales o inferiores al mínimo stock ' . ' (' . $fecha . ')</h3>'
                                . $datosArchivo['resultadosStocksTotales'];
                        }
                        $message .= "<br>Fin del informe";

                        enviarEmail($this->email, $subject, $from, $message, 5);
                    }
                }
            }



            //para el informe las cantidades se ponene en negativo
            foreach ($dato['validos'] as $k => $v) {
                if ($v == -1) {
                    $dato['cantidades'][$k] = -$dato['cantidades'][$k];
                }
            }


            //pagina salida upload_prestashop_success
            $dato['autor'] = 'Miguel Angel Bañolas';
            $data = array();
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_prestashop_success', $dato);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
        }
    }

    function do_upload_tracking()
    {
        $config['upload_path'] = './uploads/tracking';
        $config['allowed_types'] = array('csv');
        $config['max_size'] = '6000';

        $this->load->library('upload', $config);

        $dato['resultado'] = "Resultado";
        $dato['upload_data'] = array();
        $dato['codigos'] = array();

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors(), 'data' => array('orig_name' => $this->upload->data()['file_name']));

            $dato['autor'] = 'Miguel Angel Bañolas';
            $data = array();
            $this->load->view('templates/header.html', $dato);

            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_tracking_form', $error);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
        } else {
            $datosArchivo = $this->upload_->getDatosTracking($this->upload->data());

            if (isset($datosArchivo['noArchivoTracking'])) {
                $nombreArchivo = $this->upload->data()['orig_name'];

                $message = "";
                $message .= "<h3>Informe Tracking</h3>";
                $message .= "<h4 style='color:red;' >El archivo $nombreArchivo NO se puede subir porque NO es un archivo válido de datos Tracking.</h4>";
                $message .= "<h4 style='color:red;' >La primera fila debe contener SOLO la columna: Order No </h4>";
                $message .= "<h4 style='color:red;' >Y la columna contener los correspodientes valores (Núm Pedidos para tracking).</h4>";
                $message .= "<h4 style='color:red;' >Corregir el contenido del archivo y volverlo a subir.</h4>";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_tracking_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }

            if (isset($datosArchivo['pedidosNoValidos'])) {
                $nombreArchivo = $this->upload->data()['orig_name'];

                $message = "";
                $message .= "<h3>Informe Tracking</h3>";
                $message .= "<h4 style='color:red;' >El archivo $nombreArchivo NO se puede subir porque los pedidos NO cumplen los requisitos de Tracking.</h4>";
                if (count($datosArchivo['pedidosSinDatos'])) {
                    $message .= "<h4 style='color:red;' >Los siguientes pedidos NO tienen datos en el sistema</h4>";
                    $message .= "<h4 style='color:red;' >" . implode(", ", $datosArchivo['pedidosSinDatos']) . "</h4>";
                }
                if (count($datosArchivo['pedidosYaTracking'])) {
                    $message .= "<h4 style='color:red;' >Los siguientes pedidos YA están en la lista de tracking (enviados o pendiente de enviar</h4>";
                    $message .= "<h4 style='color:red;' >" . implode(", ", $datosArchivo['pedidosYaTracking']) . "</h4>";
                }
                if (count($datosArchivo['pedidosFaltaDatosEnvioEmail'])) {
                    $message .= "<h4 style='color:red;' >Los siguientes pedidos NO tienen datos suficientes para enviar email</h4>";
                    $message .= "<h4 style='color:red;' >" . implode(", ", $datosArchivo['pedidosFaltaDatosEnvioEmail']) . "</h4>";
                }

                $message .= "<h4 style='color:red;' >NO SE HA SUBIDO NINGUNA INFORMACION DEL ARCHIVO</h4>";
                $message .= "<h4 style='color:red;' >Corregir el contenido del archivo y volverlo a subir.</h4>";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();


                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_tracking_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }

            header('Location: ' . base_url() . '/index.php/envioTrackingPrestashop/listaPendientes');
            return;
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_tracking_success', $dato);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
            return;
        }
    }


    function do_upload_transportes()
    {

        $config['upload_path'] = './uploads/prestashop';
        $config['allowed_types'] = array('xlsx', 'xls');
        $config['max_size'] = '6000';
        //$config['max_width']  = '1024';
        //$config['max_height']  = '768';

        $this->load->library('upload', $config);

        $dato['resultado'] = "Resultado";
        //$dato['productoNoExistente'] =  "";
        $dato['upload_data'] = array();
        $dato['codigos'] = array();

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors(), 'data' => array('orig_name' => $this->upload->data()['file_name']));


            $dato['autor'] = 'Miguel Angel Bañolas';
            $data = array();
            $this->load->view('templates/header.html', $dato);

            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_transportes_form', $error);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
        } else {

            //$datosArchivo = $this->upload_->getDatosTransportes($this->upload->data());

            $dato['autor'] = 'Miguel Angel Bañolas';
            $data = array();
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_prestashop_success', $dato);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
        }
    }

    


    function do_upload_costes_transportes()
    {
        $config['upload_path'] = './uploads/transportes';
        $config['allowed_types'] = array('xlsx', 'xls');
        $config['max_size'] = '6000';

        $this->load->library('upload', $config);

        $dato['resultado'] = "Resultado";
        $dato['upload_data'] = array();
        $dato['codigos'] = array();
        
        if (!$this->upload->do_upload()) {
            $error = array( 'error' => $this->upload->display_errors(), 'data' => array('orig_name' => $this->upload->data()['file_name']));
            $dato['autor'] = 'Miguel Angel Bañolas';
            $data = array();
            //echo $this->upload->data()['file_name'];

            $this->load->view('templates/header.html', $dato);

            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_costes_transportes_form', $error);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
        } else {
            $files=$this->upload->data();
            
            if($files['orig_name']!=$files['file_name']){
                if(is_file($files['full_path'])){
                    unlink($files['full_path']); // delete file
                }   
                $nombreArchivo = $this->upload->data()['orig_name'];

                $message = "";
                $message .= "<h3>Informe Archivo costes transportes</h3>";
                $message .= "<h4 style='color:red;' >El archivo $nombreArchivo YA se ha subido</h4>";
                $message .= "<h4 style='color:red;' >Si estos datos SE DESEAN VOLVER A SUBIR, cambie el nombre del archivo</h4>";
                $message .= "<h4 style='color:red;' >Tened en cuenta que los datos se sumarán a los ya existentes.</h4>";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_tracking_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }



            $datosArchivo = $this->upload_->getDatosCostesTransportes($this->upload->data());
           
            $nombreArchivo = $this->upload->data()['file_name'];



            if (isset($datosArchivo['noArchivoCostesTransportes'])) {
                if(is_file($files['full_path'])){
                    unlink($files['full_path']); // delete file
                } 
                $nombreArchivo = $this->upload->data()['orig_name'];

                $message = "";
                $message .= "<h3>Informe Archivo costes transportes</h3>";
                $message .= "<h4 style='color:red;' >El archivo $nombreArchivo NO se puede subir porque NO es un archivo válido de datos de costes de transportes.</h4>";
                $message .= "<h4 style='color:red;' >La primera fila y columna debe contener Nº  Comanda (si es de DHL), Comanda (si es PAACK) o Customer Reference (si es de TNT)</h4>";
                $message .= "<h4 style='color:red;' >Corregir el contenido del archivo y volver a subir.</h4>";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_tracking_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }

            if (isset($datosArchivo['lineasSinPedidos'])) {
                if(is_file($files['full_path'])){
                    unlink($files['full_path']); // delete file
                } 
                $nombreArchivo = $this->upload->data()['orig_name'];

                $message = "";
                $message .= "<h3>Informe costes transportes</h3>";
                $message .= "<h4 style='color:red;' >El archivo $nombreArchivo NO se puede subir porque existen costes sin asignar a número de pedido.</h4>";
                if (count($datosArchivo['lineasSinPedidos'])) {
                    $message .= "<h4 style='color:red;' >Las siguientes lineas NO tienen número de pedido</h4>";
                    $message .= "<h4 style='color:red;' >" . implode(", ", $datosArchivo['lineasSinPedidos']) . "</h4>";
                }

                $message .= "<h4 style='color:red;' >NO SE HA SUBIDO NINGUNA INFORMACION DEL ARCHIVO</h4>";
                $message .= "<h4 style='color:red;' >Corregir el contenido del archivo y volverlo a subir.</h4>";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                //$dato['adicionales'] = $adicionales;

                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_costes_transportes_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }

            if (isset($datosArchivo['pedidosNoExistentes'])) {
                if(is_file($files['full_path'])){
                    unlink($files['full_path']); // delete file
                } 
                $nombreArchivo = $this->upload->data()['orig_name'];

                $message = "";
                $message .= "<h3>Informe costes transportes</h3>";
                $message .= "<h4 style='color:red;' >El archivo $nombreArchivo NO se puede subir porque contiene pedidos no existentes en prestashop.</h4>";
                if (count($datosArchivo['pedidosNoExistentes'])) {
                    $message .= "<h4 style='color:red;' >Los siguientes pedidos NO existen en prestashop o si existen todas sus lineas tiene valid = 0, es decir en el sistema figura como NO servida </h4>";
                    $message .= "<h4 style='color:red;' >" . implode(", ", $datosArchivo['pedidosNoExistentes']) . "</h4>";
                }

                $message .= "<h4 style='color:red;' >NO SE HA SUBIDO NINGUNA INFORMACION DEL ARCHIVO</h4>";
                $message .= "<h4 style='color:red;' >Corregir el contenido del archivo y volverlo a subir.</h4>";

                $dato['autor'] = 'Miguel Angel Bañolas';
                $data = array();
                $dato['resultado'] = $message;
                $dato['pedidosSubidos'] = true;
                $dato['error'] = $message;
                $dato['data'] = $this->upload->data();
                

                $this->load->view('templates/header.html', $dato);
                $this->load->view('templates/top.php', $dato);
                $this->load->view('upload_costes_transportes_form', $dato);
                $this->load->view('templates/footer.html', $dato);
                $this->load->view('myModal');
                return;
            }





            $nombreArchivo = $this->upload->data()['orig_name'];

            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('upload_costes_transportes_success', $datosArchivo);
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal');
            return;
        }
    }

}
