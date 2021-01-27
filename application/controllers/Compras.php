<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Compras extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('productos_');
        $this->load->library('form_validation');
        //$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->load->model('compras_model');
        $this->load->model('pedidos_model');
        $this->load->model('stocks_model');
    }

    public function facturasProveedoresEntreFechas()
    {
        $dato['autor'] = 'Miguel Angel Bañolas';
        $dato['anys'] = array("Seleccionar año");
        if (strtolower($this->session->username) == 'pernilall') {
            for ($x = date('Y'); $x >= 2014; $x--) {
                $dato['anys'][] = $x;
            }
        }
        else{
            for ($x = date('Y'); $x >= date('Y')-1; $x--) {
                $dato['anys'][] = $x;
            }
        }
        $dato['meses'] = array("Seleccionar mes", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('facturasProveedoresEntreFechas', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);
    }


    public function facturasAcreedoresEntreFechas()
    {
        $dato['autor'] = 'Miguel Angel Bañolas';
        $dato['anys'] = array("Seleccionar año");
        if (strtolower($this->session->username) == 'pernilall') {
            for ($x = date('Y'); $x >= 2014; $x--) {
                $dato['anys'][] = $x;
            }
        }
        else{
            for ($x = date('Y'); $x >= date('Y')-1; $x--) {
                $dato['anys'][] = $x;
            }
        }
        $dato['meses'] = array("Seleccionar mes", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('facturasAcreedoresEntreFechas', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);
    }
    function cancelarTransformacion($id_transformacion)
    {
        $salida = $this->compras_model->cancelarTransformacion($id_transformacion);
        echo json_encode($salida);
    }

    function calculoPreciosCompraTransformaciones()
    {
        $salida = "";
        if (isset($_POST['lineas'])) {
            $salida = $this->compras_model->calculoPreciosCompraTransformaciones($_POST['lineas'], $_POST['patron']);
        }

        echo json_encode($salida);
    }



    function pagoFacturasProveedores()
    {
        $this->load->model('stocks_model');
        $this->load->model('compras_model');
        $this->compras_model->agrupar_proveedores_acreedores();
        //$dato['optionsFormulas']=$this->stocks_model->getFormulas()['optionsFormulas'];

        $dato['proveedoresAcreedores'] = $this->compras_model->getProveedoresAcreedores()['options'];
        //$dato['albaranes']=$this->compras_model->getAlbaranesProveedores()['options'];


        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('pagoFacturaProveedor.php', $dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        $this->load->view('myModalPregunta.php');
    }


    function facturaProveedor()
    {
        $this->load->model('stocks_model');
        $this->load->model('compras_model');
        //  $this->compras_model->agrupar_proveedores_acreedores();
        //$dato['optionsFormulas']=$this->stocks_model->getFormulas()['optionsFormulas'];
        $dato['optionsProductos'] = $this->stocks_model->getProductos()['optionsProductos'];
        $dato['proveedoresAcreedores'] = $this->compras_model->getProveedores()['options'];
        //$dato['albaranes']=$this->compras_model->getAlbaranesProveedores()['options'];


        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('facturaProveedor.php', $dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        $this->load->view('myModalPregunta.php');
    }


    function facturaProveedor_()
    {
        $dato['proveedores'] = $this->compras_model->getProveedores();
        $dato['productos'] = $this->compras_model->getProductos();
        $dato['autor'] = 'Miguel Angel Bañolas';
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('compras/entrarDatosFacturaProveedor', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);
        $this->load->view('myModalConfirmDelete', $dato);
        $this->load->view('myModalConfirmCambios', $dato);
        $this->load->view('myModalVolver', $dato);
    }

    function pedidoProveedorNuevo($file = "")
    {
        //echo 'archivo '.$file;

        if ($file) {

            $path = 'pedidos' . DIRECTORY_SEPARATOR;
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . $path . $file . "");
            header("Content-length: " . filesize($path . $file));
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile($path . $file);
            exit;
        }

        $this->load->model('stocks_model');
        $this->load->model('compras_model');
        $this->compras_model->agrupar_proveedores_acreedores();
        $dato['siguiente'] = $this->compras_model->siguientePedido();
        $dato['optionsProductos'] = $this->stocks_model->getProductos()['optionsProductos'];
        $dato['proveedoresAcreedores'] = $this->compras_model->getProveedoresAcreedores()['options'];
        $dato['pedidos'] = $this->compras_model->getPedidosProveedores()['options'];
        $dato['pedidosAcreedor'] = $this->compras_model->getPedidosAcreedores()['options'];

        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('pedido.php', $dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
        $this->load->view('myModalPregunta.php');
        $this->load->view('myModalPreguntaUnidades.php');
    }

    function getIdProveedor()
    {
        $pedido = $_POST['pedido'];
        $proveedor = $this->compras_model->getIdProveedor($pedido);
        echo json_encode($proveedor);
    }

    function pedidoProveedor()
    {
        //var_dump($_POST);
        if (isset($_POST['pedidoExcel'])) {
            $path = 'pedidos' . DIRECTORY_SEPARATOR;
            $file = $_POST['pedidoExcel'];
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . $path . $file . "");
            header("Content-length: " . filesize($path . $file));
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile($path . $file);
            exit;
        }
        if (isset($_POST['editPedidoExcel'])) {
            $path = 'pedidos' . DIRECTORY_SEPARATOR;
            $file = $_POST['editPedidoExcel'];
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . $path . $file . "");
            header("Content-length: " . filesize($path . $file));
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile($path . $file);
            exit;
        }
        $dato['proveedores'] = $this->compras_model->getProveedores();
        $dato['productos'] = $this->compras_model->getProductos();
        //
        //$dato['optionsProductos']=$this->stocks_model->getProductos()['optionsProductos'];
        $dato['siguiente'] = $this->compras_model->siguientePedido();
        $dato['hoy'] = date('Y-m-d');
        $dato['autor'] = 'Miguel Angel Bañolas';
        $this->load->view('templates/header.html', $dato);
        $dato['activeMenu'] = 'Compras';
        $dato['activeSubmenu'] = 'Pedidos a Proveedores';
        $this->load->view('templates/top.php', $dato);
        $this->load->view('compras/entrarDatosPedidoProveedor', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);
        $this->load->view('myModalConfirmDelete', $dato);
        $this->load->view('myModalConfirmCambios', $dato);
        $this->load->view('myModalVolver', $dato);
    }


    function readFacturaProveedor()
    {
        $dato['facturas'] = $this->compras_model->getFacturasProveedores();
        $dato['autor'] = 'Miguel Angel Bañolas';
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('compras/readFacturaProveedor', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);
        $this->load->view('myModalConfirmDelete', $dato);
        $this->load->view('myModalVolver', $dato);
    }

    function borrarFactura()
    {
        echo json_encode($this->compras_model->borrarFactura());
    }

    function getFacturas()
    {
        $proveedor = $_POST['proveedor'];
        $facturas = $this->compras_model->getFacturas($proveedor);
        echo json_encode($facturas);
    }

    function getFactura()
    {
        $proveedor = $_POST['proveedor'];
        $facturas = $this->compras_model->getFactura($proveedor);
        echo json_encode($facturas);
    }

    function getProveedoresFiltrados()
    {
        $filtro = $_POST['filtro'];
        $proveedores = $this->compras_model->getProveedoresFiltrados($filtro);
        echo json_encode($proveedores);
    }

    function getProveedoresAcreedoresFiltrados()
    {
        $filtro = $_POST['filtro'];
        $proveedores = $this->compras_model->getProveedoresAcreedoresFiltrados($filtro);
        echo json_encode($proveedores);
    }

    function getAcreedoresFiltrados()
    {
        $filtro = $_POST['filtro'];
        $acreedores = $this->compras_model->getAcreedoresFiltrados($filtro);
        echo json_encode($acreedores);
    }

    function getProductosFiltrados()
    {
        $filtro = $_POST['filtro'];
        $productos = $this->compras_model->getProductosFiltrados($filtro);
        echo json_encode($productos);
    }

    function getIdProductosFiltrados()
    {
        $filtro = $_POST['filtro'];
        $productos = $this->compras_model->getIdProductosFiltrados($filtro);
        echo json_encode($productos);
    }


    function getPrecioCompra()
    {
        $codigo_producto = $_POST['producto'];
        $proveedor = $_POST['proveedor'];
        $precioCompra = $this->compras_model->getPrecioCompra($codigo_producto, $proveedor);
        echo json_encode($precioCompra);
    }


    function getNombreProducto()
    {
        $codigo_producto = $_POST['producto'];
        $nombreProducto = $this->compras_model->getNombreProducto($codigo_producto);
        echo json_encode($nombreProducto);
    }

    function grabarFactura()
    {
        $proveedor = $_POST['proveedor'];
        $numFactura = $_POST['numFactura'];
        $fechaFactura = $_POST['fechaFactura'];
        $totalFactura = $_POST['totalFactura'];
        $otros = $_POST['otros'] * 100;
        $id_factura = $this->compras_model->grabarFactura($proveedor, $numFactura, $fechaFactura, $totalFactura, $otros);
        echo json_encode($id_factura);
    }

    function grabarLineasFactura()
    {
        $id_factura = $_POST['id_factura'];
        $codigo_producto = $_POST['codigo_producto'];
        $cantidad = $_POST['cantidad'] * 1000;
        $precio = $_POST['precio'] * 100;
        $descuento = $_POST['descuento'] * 100;
        $total = $_POST['total'] * 100;
        $id_factura = $this->compras_model->grabarLineasFactura($id_factura, $codigo_producto, $cantidad, $precio, $descuento, $total);
        echo json_encode($id_factura);
    }

    function grabarFacturaCompleta()
    {
        $id_factura = $this->compras_model->grabarFactura($_POST['proveedor'], $_POST['numFactura'], $_POST['fechaFactura'], $_POST['otrosCostes'], $_POST['totalFactura']);
        if (isset($_POST['lineas'])) {
            $lineas = $this->compras_model->grabarLineasFactura($id_factura, $_POST['lineas']);
        }
        echo json_encode(true);
    }

    function updateFacturaCompleta()
    {
        $id_factura = $this->compras_model->updateFactura($_POST['idFactura'], $_POST['proveedor'], $_POST['numFactura'], $_POST['fechaFactura'], $_POST['otrosCostes'], $_POST['totalFactura']);
        $this->compras_model->deleteLineasFactura($id_factura);
        if (isset($_POST['lineas'])) {
            $lineas = $this->compras_model->updateLineasFactura($id_factura, $_POST['lineas']);
        }
        echo json_encode(true);
    }

    function registrarPagoFactura()
    {
        $id_factura = $_POST['id_factura'];
        $fecha_pago = $_POST['fecha_pago'];
        $sql = "UPDATE pe_facturas_proveedores SET fecha_pago='$fecha_pago' WHERE id='$id_factura'";
        $this->db->query($sql);
        echo json_encode(true);
    }
    function getDatosFactura()
    {
        $datosFactura = $this->compras_model->getDatosFactura($_POST['id_factura']);
        echo json_encode($datosFactura);
    }

    function download($file)
    {
        $this->load->helper('download');
    }

    //funciones para Pedidos
    function readPedidoProveedor()
    {
        $dato['pedidos'] = $this->compras_model->getPedidosProveedores();
        $dato['autor'] = 'Miguel Angel Bañolas';
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('compras/readPedidoProveedor', $dato);
        $this->load->view('templates/footer.html', $dato);
        $this->load->view('myModal', $dato);
        $this->load->view('myModalConfirmDelete', $dato);
        $this->load->view('myModalVolver', $dato);
    }

    function borrarPedido()
    {
        echo json_encode($this->compras_model->borrarPedido());
    }


    function getPedidos()
    {
        $tipo = false;
        $proveedor = $_POST['proveedor'];
        if (isset($_POST['tipo'])) $tipo = $_POST['tipo'];
        $pedidos = $this->compras_model->getPedidos($proveedor, $tipo);
        echo json_encode($pedidos);
    }

    function grabarPedido()
    {
        $proveedor = $_POST['proveedor'];
        $numPedido = $_POST['numPedido'];
        $fechaPedido = $_POST['fechaPedido'];
        $totalPedido = $_POST['totalPedido'];
        $otros = $_POST['otros'] * 100;
        $id_pedido = $this->compras_model->grabarPedido($proveedor, $numPedido, $fechaPedido, $totalPedido, $otros);
        echo json_encode($id_pedido);
    }

    function grabarLineasPedido()
    {
        $id_pedido = $_POST['id_pedido'];
        $codigo_producto = $_POST['codigo_producto'];
        $cantidad = $_POST['cantidad'] * 1000;
        $tipoUnidad = $_POST['tipoUnidad'];
        $precio = $_POST['precio'] * 100;
        $descuento = $_POST['descuento'] * 100;
        $total = $_POST['total'] * 100;
        $id_pedido = $this->compras_model->grabarLineasPedido($id_pedido, $codigo_producto, $cantidad, $tipoUnidad, $precio, $descuento, $total);
        echo json_encode($id_pedido);
    }

    function grabarPedidoCompleta()
    {
        //echo json_encode($_POST);
        //return;
        $resultado = "";
        $id_pedido = $this->compras_model->grabarPedido($_POST['proveedor'], $_POST['numPedido'], $_POST['fechaPedido'], floatval($_POST['otrosCostes']), floatval($_POST['totalPedido']));
        $_POST['idPedido'] = $id_pedido;

        if (isset($_POST['lineas'])) {
            $lineas = $this->compras_model->grabarLineasPedido($id_pedido, $_POST['lineas']);
            $resultado = $this->pedidos_model->excelPedido();
        }
        echo json_encode($resultado);
    }

    function grabarVentaCompleta()
    {
        $resultado = "";
        $id_venta = $this->compras_model->grabarVentaDirecta($_POST['vendidoA'], $_POST['id_cliente'], $_POST['concepto'], $_POST['fecha'], floatval($_POST['importeTotal']), floatval($_POST['costeTotal']), floatval($_POST['pvpTotal']));
        $_POST['id_venta'] = $id_venta;
        if (isset($_POST['lineas'])) {
            $lineas = $this->compras_model->grabarLineasVentaDirecta($id_venta, $_POST['lineas']);
            //$resultado=$this->pedidos_model->excelPedido();
        }
        echo json_encode($_POST['lineas']);
    }

    function grabarAlbaran()
    {
        $resultado = "";
        $id_albaran = $this->compras_model->grabarAlbaran($_POST['numAlbaran'], $_POST['proveedor'], $_POST['pedido'], $_POST['fecha']);

        $this->compras_model->grabarRecibido($_POST['pedido'], $_POST['fecha']);

        $_POST['id_albaran'] = $id_albaran;
        if (isset($_POST['lineas'])) {
            $lineas = $this->compras_model->grabarLineasAlbaran($id_albaran, $_POST['lineas']);
        }
        echo json_encode(true);
    }

    function grabarPedidoNuevo()
    {
        $resultado = "";
        $id_albaran = $this->compras_model->grabarPedidoNuevo($_POST['proveedor'], $_POST['pedido'], $_POST['fecha']);
        $_POST['id_albaran'] = $id_albaran;
        if (isset($_POST['lineas'])) {
            $lineas = $this->compras_model->grabarLineasPedidoNuevo($id_albaran, $_POST['lineas']);
        }
        echo json_encode($lineas);
    }

    function grabarFacturaProveedor()
    {

        extract($_POST);
        $resultado = "";
        $id_factura_proveedor = $this->compras_model->grabarFacturaProveedor(
            $proveedor,
            $num_albaranes,
            $numFactura,
            $fecha,
            $totalFactura,
            $otrosCostes,
            $tipoIva
        );

        $preciosAnteriores = array();
        $precioCompraAnteriores = array();




        $lineasAgrupadas = array();
        //var_dump($lineas);
        foreach ($lineas as $k => $v) {
            $codigo_producto = $v['codigo_producto'];
            if (array_key_exists($codigo_producto, $lineasAgrupadas)) {
                $cantidad = floatval($v['cantidad']) * 1000;
                $precio = floatval($v['precio']) * 1000;
                $descuento = floatval($v['descuento']) * 100;
                $precio = $precio - $precio * $descuento / 10000;
                $base = $cantidad * $precio / 1000;
                $lineasAgrupadas[$codigo_producto]['base'] += floatval($base);
                $lineasAgrupadas[$codigo_producto]['cantidad'] += floatval($cantidad);

                // echo '1'.' '.$codigo_producto.'-'.$lineasAgrupadas[$codigo_producto]['cantidad'].' '.$lineasAgrupadas[$codigo_producto]['importe'].'/n';
            } else {
                $lineasAgrupadas[$codigo_producto] = $v;
                $cantidad = floatval($v['cantidad']) * 1000;
                $precio = floatval($v['precio']) * 1000;
                $descuento = floatval($v['descuento']) * 100;
                $precio = $precio - $precio * $descuento / 10000;
                $base = $cantidad * $precio / 1000;
                $lineasAgrupadas[$codigo_producto]['base'] = $base;
                $lineasAgrupadas[$codigo_producto]['cantidad'] = $cantidad;

                // echo '0'.' '.$codigo_producto.'-'.$lineasAgrupadas[$codigo_producto]['cantidad'].' '.$lineasAgrupadas[$codigo_producto]['importe'].'/n';
            }
        }

        if (isset($lineasAgrupadas)) {
            foreach ($lineasAgrupadas as $k => $v) {
                $preciosAnteriores[$v['codigo_producto']] = $this->productos_->getPrecios($v['codigo_producto']);
            }
            foreach ($preciosAnteriores as $k => $v) {
                $precioCompraAnteriores[$k] = $v['precio_ultimo_unidad'] == 0 ? $v['precio_ultimo_peso'] : $v['precio_ultimo_unidad'];
            }
        }
        $salida = $this->compras_model->grabarLineasFacturaProveedor($id_factura_proveedor, $lineas, $proveedor);


        //enviar email
        if (true) {
            $this->load->library('email');

            /*
                    $this->email->from('info@lolivaret.com', host().'Precios');

                    $host = host();
                    if ($host === "localhost") {
                        $this->email->bcc('mbanolas@gmail.com');
                        $this->email->to('mbanolas@gmail.com');
                    } else {
                        
                        //$this->email->bcc('mbanolas@gmail.com');
                        //$this->email->to('alex@jamonarium.com');
                        //$this->email->cc('carlos@jamonarium.com');
                        //$this->email->bcc('mbanolas@gmail.com'); 
                        //$this->email->to('carlos@jamonarium.com');
                        $this->email->to('mbanolas@gmail.com'); 
                        
                    }
                    $this->email->subject('Precios Factura Proveedor');
                    */
            $message = "";
            $message .= "<h4>Informe Precios</h4>";
            $message .= "<h3>Comparación precios</h4>";
            $nombreProveedor = $this->compras_model->getProveedorAcreedor($proveedor);
            $message .= "Proveedor: $nombreProveedor";
            $message .= "<br>Núm Factura: $numFactura";
            $fecha = fechaEuropeaSinHora($fecha);
            $message .= "<br>Fecha: $fecha<br>";

            $cambiosPrecios = false;
            foreach ($lineasAgrupadas as $k => $v) {

                $codigo_producto = $v['codigo_producto'];
                $cantidad = $v['cantidad'];
                $tipoUnidad = $v['tipoUnidad'];
                //$precio=$v['precio']*100;
                //$descuento=$v['descuento']*100;
                $base = $v['base'];
                $precio = 0;
                if ($cantidad)
                    $precio = $base / $cantidad;
                //  echo $codigo_producto. '= '.$cantidad.' '.$importe.' '.$precio.'/n';

                $nombreProducto = $this->productos_->getNombre($this->productos_->getId_pe_producto($codigo_producto));
                $message .= "<br><strong>$codigo_producto - $nombreProducto</strong>";

                $preciosNuevos = $this->productos_->getPrecios($codigo_producto);
                $precioCompraNuevos = $preciosNuevos['precio_ultimo_unidad'] == 0 ? $preciosNuevos['precio_ultimo_peso'] : $preciosNuevos['precio_ultimo_unidad'];

                if (number_format($preciosAnteriores[$k]['precioCompra'] / 1000, 3) == number_format($preciosNuevos['precioCompra'] / 1000, 3)) {
                    $tabla = '<table border="1" style="padding-left:5px;padding-right:5px;">';
                    $tabla .= '<tr><th colspan="8" style="padding:0 3px 0 3px;">Sin cambio precio compra</th></tr>';
                    $tabla .= '<tr>'
                        . '<th style="padding:0 3px 0 3px;">Tipo</th>'
                        . '<th style="padding:0 3px 0 3px;">Precio Compra</th>'
                        . '<th style="padding:0 3px 0 3px;"></th>'
                        . '<th style="padding:0 3px 0 3px;">Tarifa PVP</th>'
                        . '<th style="padding:0 3px 0 3px;">Margen %</th>'
                        //  . '<th style="padding:0 3px 0 3px;">Tarifa Prof</th>'
                        //  . '<th style="padding:0 3px 0 3px;">Tarifa Mín</th>'

                        . '</tr>';

                    $tabla .= '<tr>'
                        . '<td style="padding:0 3px 0 3px;">' . $preciosAnteriores[$k]['tipoUnidad'] . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($preciosAnteriores[$k]['precioCompra'] / 1000, 3) . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . ' ' . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($preciosAnteriores[$k]['tarifaVenta'] / 1000, 2) . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($preciosAnteriores[$k]['margenTienda'] / 1000, 2) . '</td>'
                        // . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores[$k]['tarifaProfesional']/1000,2).'</td>'
                        // . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores[$k]['tarifaProfesionalVip']/1000,2).'</td>'

                        . '</tr>';
                    $tabla .= '</table>';
                } else {
                    $cambiosPrecios = true;
                    $tabla = '<table border="1" style="padding-left:5px;padding-right:5px;">';
                    $tabla .= '<tr><th colspan="8" style="padding:0 3px 0 3px;">Precio Compra/Tarifas Ventas ANTERIORES</th></tr>';
                    $tabla .= '<tr>'
                        . '<th style="padding:0 3px 0 3px;">Tipo</th>'
                        . '<th style="padding:0 3px 0 3px;">Precio Compra</th>'
                        . '<th style="padding:0 3px 0 3px;"></th>'
                        . '<th style="padding:0 3px 0 3px;">Tarifa PVP</th>'
                        . '<th style="padding:0 3px 0 3px;">Margen %</th>'
                        //  . '<th style="padding:0 3px 0 3px;">Tarifa Prof</th>'
                        //  . '<th style="padding:0 3px 0 3px;">Tarifa Mín</th>'

                        . '</tr>';

                    $tabla .= '<tr>'
                        . '<td style="padding:0 3px 0 3px;">' . $preciosAnteriores[$k]['tipoUnidad'] . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($preciosAnteriores[$k]['precioCompra'] / 1000, 3) . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . ' ' . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($preciosAnteriores[$k]['tarifaVenta'] / 1000, 2) . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($preciosAnteriores[$k]['margenTienda'] / 1000, 2) . '</td>'
                        //   . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores[$k]['tarifaProfesional']/1000,2).'</td>'
                        //   . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores[$k]['tarifaProfesionalVip']/1000,2).'</td>'

                        . '</tr>';
                    $tabla .= '<tr><th colspan="8" style="padding:0 3px 0 3px;">Precio Compra Nuevo/Tarifas Recalculadas</th></tr>';
                    $tabla .= '<tr>'
                        . '<th style="padding:0 3px 0 3px;">Tipo</th>'
                        . '<th style="padding:0 3px 0 3px;">Precio Compra</th>'
                        . '<th style="padding:0 3px 0 3px;">Dif %</th>'
                        . '<th style="padding:0 3px 0 3px;">Tarifa PVP</th>'
                        . '<th style="padding:0 3px 0 3px;">Margen %</th>'
                        //  . '<th style="padding:0 3px 0 3px;">Tarifa Prof</th>'
                        //  . '<th style="padding:0 3px 0 3px;">Tarifa Mín</th>'

                        . '</tr>';


                    //Manteniendo la misma tarifa venta
                    if ($preciosAnteriores[$k]['precioCompra'])
                        $dif = number_format($precio / ($preciosAnteriores[$k]['precioCompra'] / 1000) * 100 - 100, 2);
                    //if($preciosNuevos['precioCompra'])
                    $margenNuevo = $this->productos_->margen($preciosAnteriores[$k]['tarifaVenta'] / 1000, $precioCompraNuevos / 1000, $preciosNuevos['dto'] / 1000, $preciosNuevos['iva'] / 1000);


                    $tabla .= '<tr>'
                        . '<td style="padding:0 3px 0 3px;">' . $preciosNuevos['tipoUnidad'] . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($precio, 3) . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . $dif . '</td>'
                        . '<td style="padding:0 3px 0 3px;background-color:lightgreen">' . number_format($preciosAnteriores[$k]['tarifaVenta'] / 1000, 2) . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($margenNuevo, 2) . '</td>'
                        //  . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores[$k]['tarifaProfesional']/1000,2).'</td>'
                        //  . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores[$k]['tarifaProfesionalVip']/1000,2).'</td>'
                        . '<td style="padding:0 3px 0 3px;background-color:lightgreen">Manteniendo misma tarifa venta</td>'

                        . '</tr>';

                    //Manteniendo el mismo margen
                    //tarifaVenta($precioCompra,$iva,$beneficio)
                    $tarifaVentaMismoBeneficio = $this->productos_->tarifaVenta($preciosNuevos['precioCompra'] / 1000, $preciosNuevos['iva'] / 1000, $preciosAnteriores[$k]['margenTienda'] / 1000);
                    $tarifaProfesionalesMismoBeneficio = $this->productos_->tarifaProfesional($tarifaVentaMismoBeneficio, $preciosNuevos['descuento_profesionales'] / 1000, $preciosNuevos['iva'] / 1000);
                    $tarifaProfesionalesVipMismoBeneficio = $this->productos_->tarifaProfesional($tarifaVentaMismoBeneficio, $preciosNuevos['descuento_profesionales_vip'] / 1000, $preciosNuevos['iva'] / 1000);

                    $color = "background-color:yellow";
                    if (number_format($preciosAnteriores[$k]['tarifaVenta'] / 1000, 2) == number_format($tarifaVentaMismoBeneficio, 2)) $color = "background-color:lightgreen";
                    $tabla .= '<tr>'
                        . '<td style="padding:0 3px 0 3px;">' . ' ' . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . ' ' . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . ' ' . '</td>'
                        . '<td style="padding:0 3px 0 3px;' . $color . '">' . number_format($tarifaVentaMismoBeneficio, 2) . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($preciosAnteriores[$k]['margenTienda'] / 1000, 2) . '</td>'
                        //  . '<td style="padding:0 3px 0 3px;">'.number_format($tarifaProfesionalesMismoBeneficio,2).'</td>'
                        //  . '<td style="padding:0 3px 0 3px;">'.number_format($tarifaProfesionalesVipMismoBeneficio,2).'</td>'
                        . '<td style="padding:0 3px 0 3px;' . $color . '">Manteniendo mismo margen de beneficio</td>'
                        . '</tr>';
                    //tarifa nueva con beneficfio recomendado
                    if (!$preciosNuevos['beneficioRecomendado']) $preciosNuevos['beneficioRecomendado'] = 35000;
                    $tarifaVentaBeneficioRecomendado = $this->productos_->tarifaVenta($preciosNuevos['precioCompra'] / 1000, $preciosNuevos['iva'] / 1000, $preciosNuevos['beneficioRecomendado'] / 1000);
                    $tarifaProfesionalesBeneficioRecomendado = $this->productos_->tarifaProfesional($tarifaVentaBeneficioRecomendado, $preciosNuevos['descuento_profesionales'] / 1000, $preciosNuevos['iva'] / 1000);
                    $tarifaProfesionalesVipBeneficioRecomendado = $this->productos_->tarifaProfesional($tarifaVentaBeneficioRecomendado, $preciosNuevos['descuento_profesionales_vip'] / 1000, $preciosNuevos['iva'] / 1000);

                    $color = "background-color:yellow";
                    if (number_format($preciosAnteriores[$k]['tarifaVenta'] / 1000, 2) == number_format($tarifaVentaBeneficioRecomendado, 2)) $color = "background-color:lightgreen";

                    $tabla .= '<tr>'
                        . '<td style="padding:0 3px 0 3px;">' . ' ' . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . ' ' . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . ' ' . '</td>'
                        . '<td style="padding:0 3px 0 3px;' . $color . '">' . number_format($tarifaVentaBeneficioRecomendado, 2) . '</td>'
                        . '<td style="padding:0 3px 0 3px;">' . number_format($preciosNuevos['beneficioRecomendado'] / 1000, 2) . '</td>'
                        //   . '<td style="padding:0 3px 0 3px;">'.number_format($tarifaProfesionalesBeneficioRecomendado,2).'</td>'
                        //   . '<td style="padding:0 3px 0 3px;">'.number_format($tarifaProfesionalesVipBeneficioRecomendado,2).'</td>'
                        . '<td style="padding:0 3px 0 3px;' . $color . '">Tarifa venta PVP con beneficio recomendado</td>'
                        . '</tr>';

                    $tabla .= '</table>';
                }
                $message .= $tabla;
                $message .= '<br>';
            }


            if ($cambiosPrecios) {
                $message .= "<br>Por omisión, se ha mantenido la misma tarifa PVP y se actualiza el margen. Celdas color verde.";
                $message .= "<br>Los valores con celdas en amarillo son los valores que se deberán cambiar en <strong>Productos Editar</strong> según el criterio que se elija ";
            }
            $message .= "<br><br>Fin del informe";

            enviarEmail($this->email, 'Precios Factura Proveedor', host() . ' - Precios', $message, 1);
            /*
                    $this->email->message($message);
                    //$this->email->message('<h3>Códigos PrestaShop NO existentes en Base datos productos</h3>'.$salida1);

                    if ($this->email->send()) {
                        // echo "Mail Sent!";
                    } else
                        echo "Error al enviar email";
                    */
        }

        echo json_encode($salida);
    }

    function grabarTransformacion()
    {
        // echo json_encode($_POST['venta']['transformacion']);
        // return;
        $resultado = "";
        //echo $_POST['preciosCompre']
        $id_transformacion = $this->compras_model->grabarTransformacion($_POST['venta']['transformacion'], $_POST['venta']['concepto'], $_POST['venta']['fecha'], $_POST['venta']['id_transformacion'], $_POST['venta']['patron'], $_POST['venta']['lote_origen'], $_POST['venta']['lote_final']);
        $patron = $_POST['venta']['patron'];
        $_POST['id_transformacion'] = $id_transformacion;
        $lineas = "";
        if (isset($_POST['venta']['lineas'])) {
            $lineas = $this->compras_model->grabarLineasTransformacion($id_transformacion, $_POST['venta']['lineas'], $_POST['preciosNuevos'], $_POST['preciosActuales'], $patron);
        }
        echo json_encode($lineas);
    }



    function getVentaDirecta()
    {
        $id = $_POST['id'];
        $datos = $this->compras_model->getVentaDirecta($id);

        echo json_encode($datos);
    }

    function getAlbaran()
    {
        $id_proveedor = $_POST['proveedor'];
        $datos = $this->compras_model->getAlbaran($id_proveedor);

        echo json_encode($datos);
    }

    function getPedido()
    {
        $id = $_POST['id'];
        $datos = $this->compras_model->getPedido($id);

        echo json_encode($datos);
    }

    function getAlbaranesProveedores()
    {
        $id = $_POST['id'];
        $datos = $this->compras_model->getAlbaranesProveedor($id);

        echo json_encode($datos);
    }

    function getFacturaProveedor()
    {
        $id = $_POST['id'];
        $datos = $this->compras_model->getFacturaProveedor($id);

        echo json_encode($datos);
    }

    function getPedidoPrestashop()
    {
        $id = $_POST['id'];
        $datos = $this->compras_model->getPedidoPrestashop($id);
        echo json_encode($datos);
    }




    function updatePedidoCompleta()
    {
        $id_pedido = $this->compras_model->updatePedido($_POST['idPedido'], $_POST['proveedor'], $_POST['numPedido'], $_POST['fechaPedido'], $_POST['otrosCostes'], $_POST['totalPedido']);
        $this->compras_model->deleteLineasPedido($id_pedido);
        if (isset($_POST['lineas'])) {
            $lineas = $this->compras_model->updateLineasPedido($id_pedido, $_POST['lineas']);
            $resultado = $this->pedidos_model->excelPedido();
        }
        echo json_encode($resultado);
    }

    function getDatosPedido()
    {
        $datosPedido = $this->compras_model->getDatosPedido($_POST['id_pedido']);
        echo json_encode($datosPedido);
    }

    function getDatosAlbaran()
    {
        $datosAlbaran = $this->compras_model->getDatosAlbaran($_POST['id_albaran']);
        echo json_encode($datosAlbaran);
    }

    function getDatosViewAlbaran()
    {
        $datosAlbaran = $this->compras_model->getDatosViewAlbaran($_POST['id_albaran']);
        echo json_encode($datosAlbaran);
    }

    function getTransformacion()
    {
        $transformacion = $this->compras_model->getTransformacion($_POST['id']);
        echo json_encode($transformacion);
    }




    //para eliminar    
    function entrarDatos()
    {
        $dato['codigos_productos'] = $this->productos_->getCodigos();
        $dato['codigos_productos_nombre'] = $this->productos_->getCodigosNombre();

        $dato['codigos_productos_em'] = $this->productos_->getCodigosEstudiosMercado();
        $dato['codigos_productos_em_nombre'] = $this->productos_->getCodigosEstudiosMercadoNombre();

        $dato['autor'] = 'Miguel Angel Bañolas';
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('estudios/entrarDatos', $dato);
        $this->load->view('templates/footer.html', $dato);
    }

    function getDatosProducto()
    {
        $codigo_producto = $_POST['codigo_producto'];

        if (!$codigo_producto) {
            echo json_encode($codigo_producto);
            return false;
        }

        $datos = $this->productos_->getDatosProducto($codigo_producto);
        $iva = $this->productos_->getIva($codigo_producto);
        $datosMercado = $this->productos_->getDatosProductoEstudioMercado($codigo_producto);

        echo json_encode(array('datos' => $datos, 'iva' => $iva, 'datosMercado' => $datosMercado));
    }


    function getSiguienteCodigoEM()
    {
        $siguienteCodigoEM = $this->productos_->getSiguienteCodigoEM();
        if (!$siguienteCodigoEM) $siguienteCodigoEM = "EM00000000000";

        $actual = substr($siguienteCodigoEM, 2);

        $actual = $actual + 1;
        while (strlen($actual) < 11) $actual = "0" . $actual;

        $siguienteCodigoEM = "EM" . $actual;

        echo json_encode($siguienteCodigoEM);
    }

    function grabarDatosEstudiosMercado()
    {
        $result = $this->productos_->grabarDatosEstudiosMercado();
        echo json_encode($result);
    }
}
