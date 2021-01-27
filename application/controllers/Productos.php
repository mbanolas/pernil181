<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Productos extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('productos_');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->load->library('grocery_CRUD');
        $this->load->library('excel');

        

    }


    function variablesTabla()
    {
        // $this->db->query("DELETE FROM tabla_productos WHERE 1");
        // $this->db->query("ALTER TABLE tabla_productos AUTO_INCREMENT = 1");
        foreach ($_POST as $k => $v) {
            mensaje('desde foreach ' . $k . ' ' . $v);
        }
        mensaje(implode($_POST));
        // mensaje($_POST['columna_3']);
        $resultado = true;
        if (isset($_POST['paginas'])) {
            $paginas = $_POST['paginas'];
            $resultado = $this->db->query("UPDATE tabla_productos SET paginas='$paginas'");
        }
        if (isset($_POST['pagina'])) {
            $pagina = $_POST['pagina'];
            $resultado = $this->db->query("UPDATE tabla_productos SET pagina='$pagina'");
        }
        if (isset($_POST['buscar'])) {
            $buscar = $_POST['buscar'];
            $resultado = $this->db->query("UPDATE tabla_productos SET buscar='$buscar'");
        }
        for ($i = 1; $i < 12; $i++) {
            if (isset($_POST['columna_' . $i])) {
                $campo = 'columna_' . $i;
                $valor = $_POST[$campo];
                $resultado = $this->db->query("UPDATE tabla_productos SET $campo='$valor'");
            }
        }
        echo  json_encode($resultado);
    }

    function getVariablesTabla()
    {
        $row = $this->db->query("SELECT * FROM tabla_productos WHERE id=1")->row();
        return $row;
    }

    function codigo13Valido()
    {
        $codigo13 = $_POST['codigo13'];
        $id = $_POST['id'];
        if ($codigo13 == "") {
            echo  json_encode("es nulo");
            return;
        }
        $sql = "SELECT codigo_producto FROM pe_productos WHERE codigo_producto='$codigo13'";
        // mensaje($sql);
        $num_rows = $this->db->query($sql)->num_rows();
        // mensaje($num_rows);
        if ($num_rows == 0 && $id == 0) {
            $resultado = true;
            echo  json_encode("");
            return;
        }
        if ($num_rows == 0 && $id != 0) {
            $resultado = false;
            echo  json_encode("Código inexistente, pero debería existir");
            return;
        }
        if ($num_rows == 1 && $id != 0) {
            $resultado = true;
            echo  json_encode("");
            return;
        }
        if ($num_rows >= 1 && $id == 0) {
            $resultado = false;
            echo  json_encode("Código existente, no válido para nuevo producto ");
            return;
        }
        if ($num_rows > 1) {
            $resultado = false;
            echo  json_encode('Código repetido');
            return;
        }
        $resultado = false;
        echo  json_encode('caso no previsto ' . $id . ' ' . $codigo13);
    }

    // graba datos producto desde productosSpeedy via ajax 
    function grabarProducto()
    {
        $resultado = "";
        $estructura = $this->estructuraDatos();
        $sets = array();
        $id = $_POST['id'];
        foreach ($_POST as $k0 => $v0) {
            if ($k0 == 'id') continue;
            // mensaje('datos POST '.$k0.' '.$v0);
            foreach ($estructura as $k => $v) {
                // mensaje('pos 1 '.$v['campo'].' '.$k0. ' '.($v['campo']==$k0));

                if ($v['campo'] == $k0) {
                    if ($v['tipo'] == 'date') {
                        $v0 = fechaBD($v0);
                        // mensaje('fecha '.$v['campo']."='".$v0."'");
                        $sets[] = $v['campo'] . "='" . $v0 . "'";
                        continue;
                    }
                    $factor = 0;
                    if (array_key_exists('factor', $v) == 1) {
                        $factor = $v['factor'];
                    }
                    if ($factor != 0) {
                        // mensaje('$v0 antes '.$v0.' '.$factor);
                        $v0 = str_replace(",", "", $v0);
                        // mensaje('$v0 factor'.$v0.' '.$factor);
                        // mensaje('numero con factor'.' '.$v['campo']."='".$v0*$factor."'");
                        $sets[] = $v['campo'] . "='" . $v0 * $factor . "'";
                    } else {
                        // mensaje('otro sin factor '.$v['campo']."='".$v0."'");
                        if ($v['campo'] == 'nombre_producto') $v['campo'] = 'nombre';
                        $sets[] = $v['campo'] . "='" . $v0 . "'";
                    }
                }
            }
        }
        $set = implode(", ", $sets);

        if ($id == 0) {
            $sql = "INSERT INTO pe_productos SET " . $set;
            mensaje($sql);
            $resultado = $this->db->query($sql);
            $row = $this->db->query("SELECT * FROM pe_productos ORDER BY id DESC LIMIT 1")->row();
            $id_nuevo = $row->id;
            $control_stock = $row->control_stock;
            $id_proveedor_web = $row->id_proveedor_web;
            $hoy = date('Y-m-d');
            if ($_POST['tipo_unidad'] == "Kg") {
                $tarifa = 'tarifa_venta_peso="' . ($_POST['tarifa_venta'] * 1000) . '"';
            } else {
                $tarifa = 'tarifa_venta_unidad="' . ($_POST['tarifa_venta'] * 1000) . '"';
            }
            $this->db->query("UPDATE pe_productos SET $tarifa, fecha_alta='$hoy', fecha_modificacion='$hoy' WHERE id='$id_nuevo'");
            // como es producto nuevo se incluye en pe_stocks totales
            $this->db->query("INSERT INTO pe_stocks_totales 
                                     SET cantidad='0',
                                         codigo_producto='$id_nuevo',
                                         codigo_bascula='$id_nuevo',
                                         proveedor='$id_proveedor_web',
                                         fecha_modificacion_stock='$hoy',
                                         id_pe_producto='$id_nuevo',
                                         nombre='$id_nuevo',    
                                         activo='0', 
                                         control_stock='$control_stock',
                                         valoracion='0'
                                         ");
        } else {
            $set .= ', modificado_por="' . $this->session->id . '" ';
            $sql = "UPDATE pe_productos SET " . $set . " WHERE id='$id'";
            $resultado = $this->db->query($sql);
            if ($_POST['tipo_unidad'] == "Kg") {
                $tarifa = 'tarifa_venta_peso="' . ($_POST['tarifa_venta'] * 1000) . '"';
            } else {
                $tarifa = 'tarifa_venta_unidad="' . ($_POST['tarifa_venta'] * 1000) . '"';
            }
            $sql = "UPDATE pe_productos SET " . $tarifa . " WHERE id='$id'";
            $resultado = $this->db->query($sql);

            $sql = "SELECT * FROM pe_stocks_totales WHERE codigo_producto='$id'";
            $num_rows = $this->db->query($sql)->num_rows();
            $this->productos_->regularizarDatosProducto($id);
            $this->productos_->regularizarPrecios($id);
            if ($num_rows != 1) {
                $this->db->query("DELETE FROM pe_stocls_totales WHERE fecha_modificacion_stock IS NULL AND codigo_producto='$id'");
                $sql = "SELECT * FROM pe_stocks_totales WHERE codigo_producto='$id'";
                $num_rows = $this->db->query($sql)->num_rows();
                if ($num_rows != 1) {
                    $resultado = 0;
                }
            } else {
                $id_stocks_totales = $this->db->query($sql)->row()->id;
                $row = $this->db->query("SELECT * FROM pe_productos WHERE id='$id'")->row();
                $resultado = $this->db->query("UPDATE pe_stocks_totales SET proveedor='" . $row->id_proveedor_web . "', control_stock='" . $row->control_stock . "' WHERE id='" . $id_stocks_totales . "'");
            }
        }

        // mensaje($resultado);
        echo  json_encode(array('resultado' => $resultado, 'codigo_producto' => $_POST['codigo_producto'], 'nombre' => $_POST['nombre']));
    }

    function exportExcel($codigo_producto = "", $id_producto = "", $producto = "", $peso_real = "", $tipo_unidad = "", $precio_compra = "", $tarifa_venta = "", $proveedor = "", $margen_real_producto = "", $stock_total = "", $valoracion = "", $vacio4 = "", $vacio5 = "")
    {
        $row = $this->db->query("SELECT * FROM tabla_productos limit 1")->row();
        if ($row) {
            $codigo_producto = $row->columna_1;
            $id_producto = $row->columna_2;
            $producto = str_replace("%20", " ", $row->columna_3);
            $producto = $producto;
            $peso_real = $row->columna_4;;
            $tipo_unidad = $row->columna_5;

            $precio_compra = str_replace(',', '', $row->columna_6);

            $proveedor = str_replace("%20", " ", $row->columna_7);
            $proveedor = $proveedor;

            $tarifa_venta = str_replace(',', '', $row->columna_8);

            $margen_real_producto = str_replace(',', '', $row->columna_9);

            $stock_total = str_replace(',', '', $row->columna_10);

            $valoracion = str_replace(',', '', $row->columna_11);
        }
        mensaje('codigo_producto: ' . $codigo_producto);

        $datos['codigo_producto'] = $codigo_producto;
        $datos['id_producto'] = $id_producto;
        $producto = str_replace("%20", " ", $producto);
        $datos['producto'] = $producto;
        $datos['peso_real'] = $peso_real;
        $datos['tipo_unidad'] = $tipo_unidad;
        $proveedor = str_replace("%20", " ", $proveedor);
        $datos['proveedor'] = $proveedor;
        $datos['precio_compra'] = $row->columna_6;
        $datos['tarifa_venta'] = $row->columna_8;
        $datos['margen_real_producto'] = $row->columna_9;
        $datos['stock_total'] = $row->columna_10;
        $datos['valoracion'] = $row->columna_11;




        $sql = "SELECT 
                p.id as id,
                p.codigo_producto as codigo_producto,
                p.id_producto as id_producto,
                p.nombre as nombre,
                FORMAT(p.peso_real/1000,2) as peso_real,
                p.tipo_unidad, 
                g.nombre_grupo,
                f.nombre_familia,
                FORMAT(p.precio_compra/1000,2) as precio_compra,
                tipo_unidad as tipo_unidad,
                pr.nombre_proveedor as nombre_proveedor,
                FORMAT(p.tarifa_venta/1000,2) as tarifa_venta,
                p.control_stock,
                p.stock_total,
                FORMAT(p.valoracion,2) as valoracion,
                FORMAT(p.margen_real_producto/1000,2) as margen,
                p.url_imagen_portada
              FROM pe_productos p 
              LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo
              LEFT JOIN pe_familias f ON p.id_familia=f.id_familia
              LEFT JOIN pe_proveedores pr ON p.id_proveedor_web=pr.id_proveedor  
              WHERE p.status_producto=1";
        $sql .= $codigo_producto != "_" ? " AND p.codigo_producto like '%$codigo_producto%'" : "";
        $sql .= $id_producto != "_" ? " AND p.id_producto like '%$id_producto%'" : "";
        $sql .= $producto != "_" ? " AND p.nombre like '%$producto%'" : "";
        $sql .= $peso_real != "_" ? " AND p.peso_real like '%$peso_real%'" : "";
        $sql .= $tipo_unidad != "_" ? " AND p.tipo_unidad like '%$tipo_unidad%'" : "";
        $sql .= $precio_compra != "_" ? " AND p.precio_compra like '%$precio_compra%'" : "";
        $sql .= $proveedor != "_" ? " AND pr.nombre_proveedor like '%$proveedor%'" : "";
        $sql .= $tarifa_venta != "_" ? " AND p.tarifa_venta like '%$tarifa_venta%'" : "";
        $sql .= $margen_real_producto != "_" ? " AND p.margen_real_producto like '$margen_real_producto%'" : "";
        $sql .= $stock_total != "_" ? " AND p.stock_total like '$stock_total%'" : "";
        $sql .= $valoracion != "_" ? " AND p.valoracion like '$valoracion%'" : "";

        $sql .= " ORDER BY p.codigo_producto";

        // mensaje($sql);
        $datos['result'] = $this->db->query($sql)->result();

        $this->load->library('email');
        $ahora = date('d/m/Y H:i:s');
        if ($this->session->categoria != 1) {
            enviarEmail($this->email, 'Exportación datos productos ', host() . ' - Pernil181', 'Bajado por: <br>Usuario: ' . $this->session->nombre . '<br>Fecha: ' . $ahora.'<br>'.$sql, 3);
        }
        $this->load->view('prepararExcelProductos', $datos);
    }

    function eliminarProducto($id)
    {
        $resultado = "hola";
        $this->load->model('productos_');
        $resultado = $this->productos_->eliminarProducto($id);
        $this->load->helper('url');

        //redirect('gestionTablasProductos/productos');

        echo  json_encode($resultado);
    }

    function checkPosibilityToEliminate($id_pe_producto)
    {
        $resultado = $this->productos_->checkPosibilityToEliminate($id_pe_producto);
        echo  json_encode($resultado);
    }

    function embalajes($producto = "")
    {
        $this->load->model('stocks_model');
        $dato = array();
        $dato['producto'] = $producto;
        // $dato['optionsProductos'] = $this->stocks_model->getProductos()['optionsProductos'];
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('embalajes.php', $dato);
        $this->load->view('templates/footer.html');

        $this->load->view('myModal.php');
    }

    function packs($producto = "")
    {
        $this->load->model('stocks_model');
        $dato = array();
        $dato['producto'] = $producto;
        // $dato['optionsProductos'] = $this->stocks_model->getProductos()['optionsProductos'];
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('packs.php', $dato);
        $this->load->view('templates/footer.html');

        $this->load->view('myModal.php');
    }

    function getDatosEmbalajes()
    {
        $id = $_POST['id'];
        $id_pe_producto = $this->productos_->getIdPeProductoEmbalaje($id);
        $datos = $this->productos_->getProducto($id_pe_producto);
        $datosPeEmbalajes = $this->productos_->getDatosPeEmbalajes($id);
        $embalajes = $this->productos_->getEmbalajes($id_pe_producto);
        //echo  json_encode($id_pe_producto);
        echo  json_encode(array('datos' => $datos, 'embalajes' => $embalajes, 'datosPeEmbalajes' => $datosPeEmbalajes));
    }

    function getDatosPacks()
    {
        $id = $_POST['id'];
        $id_pe_producto = $this->productos_->getIdPePack($id);
        $datos = $this->productos_->getProducto($id_pe_producto); //datos codigo pack
        $datosPePacks = $this->productos_->getDatosPePacks($id);
        $packs = $this->productos_->getProductosPack($id_pe_producto);
        //echo  json_encode($id_pe_producto);
        echo  json_encode(array('datos' => $datos, 'packs' => $packs, 'datosPePacks' => $datosPePacks));
    }

    function getProducto()
    {
        $id_pe_producto = $_POST['id_pe_producto'];
        $datos = $this->productos_->getProducto($id_pe_producto);
        $embalajes = $this->productos_->getEmbalajes($id_pe_producto);
        echo  json_encode(array('datos' => $datos, 'embalajes' => $embalajes));
    }

    function getProductosPack()
    {
        $id_pe_producto_pack = $_POST['id_pe_producto_pack'];
        $datos = $this->productos_->getProducto($id_pe_producto_pack);
        $productosPack = $this->productos_->getProductosPack($id_pe_producto_pack);
        echo  json_encode(array('datos' => $datos, 'productosPack' => $productosPack));
    }

    function getIdPeProductoEmbalaje()
    {
        $id = $_POST['id'];
        $id_pe_producto = $this->productos_->getIdPeProductoEmbalaje($id);
        echo  json_encode($id_pe_producto);
    }

    function getIdPePack()
    {
        $id = $_POST['id'];
        $id_pe_producto = $this->productos_->getIdPePack($id);
        echo  json_encode($id_pe_producto);
    }

    function getCodigoProducto($id_pe_producto)
    {
        $codigo_producto = $this->db->query("SELECT codigo_producto FROM pe_productos WHERE id='$id_pe_producto'")->row()->codigo_producto;
        return $codigo_producto;
    }

    function registrarEmbalaje()
    {
        $id_pe_producto = $_POST['id_pe_producto'];
        if (!array_key_exists('codigos', $_POST)) {
            $resultado = $this->productos_->eliminarEmbalaje($id_pe_producto);
        } else {
            $codigos = $_POST['codigos'];
            $cantidades = $_POST['cantidades'];
            $tiendas = $_POST['tiendas'];
            $onlines = $_POST['onlines'];
            $resultado = $this->productos_->registrarEmbalaje($id_pe_producto, $codigos, $cantidades, $tiendas, $onlines);
        }
        echo  json_encode($resultado);
    }

    function registrarPack()
    {
        $id_pe_producto_pack = $_POST['id_pe_producto_pack'];
        $totalPrecio_compra = $_POST['totalPrecio_compra'];
        $totalTarifa_ventaPack = $_POST['totalTarifa_ventaPack'];
        $totalTarifa_venta = intval(round($_POST['totalTarifa_venta']));



        if (!array_key_exists('codigos', $_POST)) {
            $resultado = $this->productos_->eliminarPack($id_pe_producto_pack);
        } else {
            $codigos = $_POST['codigos'];
            $cantidades = $_POST['cantidades'];
            $descuentos = $_POST['descuentos'];
            $margenPack = $_POST['margenPack'];
            $margen = $_POST['margen'];
            log_message('info', '$id_pe_producto_pack ' . $id_pe_producto_pack);
            log_message('info', '$totalPrecio_compra ' . $totalPrecio_compra);
            log_message('info', '$totalTarifa_ventaPack ' . $totalTarifa_ventaPack);
            log_message('info', '$totalTarifa_venta ' . $totalTarifa_venta);

            $resultado = $this->productos_->registrarPack($id_pe_producto_pack, $codigos, $cantidades, $descuentos, $totalPrecio_compra, $totalTarifa_ventaPack, $totalTarifa_venta, $margen, $margenPack);
        }
        echo  json_encode($resultado);
    }

    function bajarExcelProductos()
    {
        $this->load->model('productos_');
        $tabla = $this->productos_->bajarExcelProductos();

        echo  json_encode($tabla);
    }

    function getProductoPesos($id_pe_producto)
    {
        $this->load->model('productos_');
        $codigos = $this->productos_->getProductoPesos($id_pe_producto);

        echo  json_encode($codigos);
    }

    function getIva()
    {
        $grupo = $_POST['grupo'];
        $sql = "SELECT valor_iva FROM pe_grupos gr 
                        LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva
                        WHERE gr.id_grupo='$grupo'";
        if ($this->db->query($sql)->num_rows() == 1)
            $iva = $this->db->query($sql)->row()->valor_iva;
        else $iva = 0;
        echo  json_encode($iva);
    }

    function getFamilias($id_grupo = 0)
    {
        if (isset($_POST['grupo'])) $id_grupo = $_POST['grupo'];
        else $id_grupo = 0;
        $this->load->model('productos_');
        $familias = $this->productos_->getFamilias($id_grupo);

        echo  json_encode($familias);
    }

    function activarProducto($id_pe_producto)
    {
        $this->load->model('productos_');
        $this->productos_->activarProducto($id_pe_producto);
        $this->load->helper('url');
        redirect('gestionTablasProductos/productosDescatalogados');
    }

    function desactivarProducto($id_pe_producto)
    {
        $this->load->model('productos_');
        $this->productos_->desactivarProducto($id_pe_producto);
        $this->load->helper('url');
        redirect('gestionTablasProductos/productos');
    }

    function getUnidad($id_pe_producto)
    {
        $this->load->model('productos_');
        $tipoUnidad = $this->productos_->getUnidad($id_pe_producto);
        echo  json_encode($tipoUnidad);
    }

    function getUnidadCodigoProducto($codigo_producto)
    {
        $this->load->model('productos_');
        $tipoUnidad = $this->productos_->getUnidadCodigoProducto($codigo_producto);
        echo  json_encode($tipoUnidad);
    }

    function getInfoCodigoBascula($id_producto)
    {
        $this->load->model('productos_');
        $infoCodigoBascula = $this->productos_->getInfoCodigoBascula($id_producto);
        echo  json_encode($infoCodigoBascula);
    }

    function getPrecio($id_pe_producto, $proveedor, $tipoUnidad)
    {
        $this->load->model('productos_');
        $datosPrecio = $this->productos_->getPrecio($id_pe_producto, $proveedor, $tipoUnidad);
        echo  json_encode($datosPrecio);
    }

    function getCostePVP($id_pe_producto)
    {
        $this->load->model('productos_');
        $CostePVP = $this->productos_->getCostePVP($id_pe_producto);
        echo  json_encode($CostePVP);
    }

    function getDatosCompraProducto($id_pe_producto)
    {
        $this->load->model('productos_');
        $tipoUnidad = $this->productos_->getDatosCompraProducto($id_pe_producto);
        echo  json_encode($tipoUnidad);
    }


    function getCodigoEan()
    {
        $codigoBascula = $_POST['codigoBascula'];
        $this->load->model('productos_');
        $codigoEan = $this->productos_->getCodigoEan();
        echo  json_encode($codigoEan);
    }

    public function _outputProductos($output = null, $table)
    {
        $datos['autor'] = 'Miguel Angel Bañolas';
        $datos['titulo'] = 'Pernil 181';

        // $this->load->view('templates/header.html');
        $this->load->view('templates/headerGrocery', $output);

        $this->load->view('templates/top.php', $datos);
        $this->load->view('outputBD.php', $output);
        $this->load->view('myModal.php');
        $datos['pie'] = '';
        $this->load->view('templates/footer.html');
    }

    function estructuraDatos()
    {
        $dato['estructura'] = array();
        $dato['estructura'][] = array(
            'campo' => 'codigo_producto',
            'texto' => 'Código 13',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'editar' => false,
            // 'mostrar'=>false,
            'ancho' => '40'
        );
        $dato['estructura'][] = array(
            'campo' => 'id_producto',
            'texto' => 'Código Boka',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'nombre',
            'texto' => 'Nombre producto',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'nombre_generico',
            'texto' => 'Nombre genérico',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'codigo_ean',
            'texto' => 'Código EAN',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );

        $dato['estructura'][] = array(
            'campo' => 'id_grupo',
            'texto' => 'Grupo',
            'tipo' => 'seleccion',
            'color' => 'black',
            'seleccion' => array('tabla' => 'pe_grupos', 'indice' => 'id_grupo', 'valor' => 'nombre_grupo'),
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'id_familia',
            'texto' => 'Familia',
            'tipo' => 'seleccion',
            'color' => 'black',
            'seleccion' => array('tabla' => 'pe_familias', 'indice' => 'id_familia', 'valor' => 'nombre_familia'),
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'peso_real',
            'texto' => 'Peso real (Kg)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'anada',
            'texto' => 'Añada',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'stock_minimo',
            'texto' => 'Stock mínimo',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 0,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'control_stock',
            'texto' => 'Control stock',
            'tipo' => 'seleccion',
            'color' => 'black',
            'requerido' => true,
            'seleccion' => array('tabla' => 'pe_si_no', 'indice' => 'indice', 'valor' => 'valor'),
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'fecha_alta',
            'texto' => 'Fecha alta',
            'tipo' => 'date',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'fecha_modificacion',
            'texto' => 'Fecha modificacion',
            'tipo' => 'date',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'modificado_por',
            'texto' => 'Modificado por',
            'tipo' => 'seleccion',
            'color' => 'black',
            'requerido' => true,
            'seleccion' => array('tabla' => 'pe_users', 'indice' => 'id', 'valor' => 'nombre'),
            'editar' => false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'unidades_caja',
            'texto' => 'Unidades caja/compra',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 0,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'id_proveedor_web',
            'texto' => 'Proveedor',
            'tipo' => 'seleccion',
            'color' => 'black',
            'seleccion' => array('tabla' => 'pe_proveedores', 'indice' => 'id_proveedor', 'valor' => 'nombre_proveedor'),
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'tipo_unidad',
            'texto' => 'Tipo unidad',
            'tipo' => 'seleccion',
            'color' => 'black',
            'seleccion' => array('tabla' => 'pe_tipo_unidades', 'indice' => 'indice', 'valor' => 'valor'),
            'requerido' => true,
            'editar' => false,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'precio_ultimo_unidad',
            'texto' => 'Precio Compra (€/unidad compra)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'precio_ultimo_peso',
            'texto' => 'Precio Compra (€/Kg compra)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'descuento_1_compra',
            'texto' => 'Descuento compra (%)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'precio_transformacion_unidad',
            'texto' => 'Precio transformación (€/unidad)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            // 'editar'=>false,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'precio_transformacion_peso',
            'texto' => 'Precio transformación (€/Kg)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            // 'editar'=>false,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'precio_compra',
            'texto' => 'Precio Compra Final en Tienda',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => true,
            'editar' => false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'unidades_precio',
            'texto' => 'Unidades Precio',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 0,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'tarifa_venta',
            'texto' => 'Tarifa PVP',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'beneficio_recomendado',
            'texto' => 'Beneficio recomendado (%)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'margen_real_producto',
            'texto' => 'Margen (%)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => true,
            'editar' => false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'stock_total',
            'texto' => 'Total unidades stock',
            'tipo' => 'number',
            'factor' => 1,
            'decimales' => 0,
            'color' => 'black',
            'requerido' => true,
            'editar' => false,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'valoracion',
            'texto' => 'Valor stock precio compra actual',
            'tipo' => 'number',
            'factor' => 1,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => true,
            'editar' => false,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'iva',
            'texto' => 'IVA',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => false,
            'editar' => false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'url_producto',
            'texto' => 'Url producto',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'url_imagen_portada',
            'texto' => 'Imagen Producto',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'notas',
            'texto' => 'Notas',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );


        return $dato['estructura'];
    }
    function estructuraDatos2()
    {
        $dato['estructura'] = array();
        $dato['estructura'][] = array(
            'campo' => 'codigo_producto',
            'texto' => 'Código 13',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'editar' => false,
            // 'mostrar'=>false,
            'ancho' => '40'
        );
        $dato['estructura'][] = array(
            'campo' => 'id_producto',
            'texto' => 'Código Boka',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'nombre',
            'texto' => 'Nombre producto',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'nombre_generico',
            'texto' => 'Nombre genérico',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'codigo_ean',
            'texto' => 'Código EAN',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );

        $dato['estructura'][] = array(
            'campo' => 'id_grupo',
            'texto' => 'Grupo',
            'tipo' => 'seleccion',
            'color' => 'black',
            'seleccion' => array('tabla' => 'pe_grupos', 'indice' => 'id_grupo', 'valor' => 'nombre_grupo'),
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'id_familia',
            'texto' => 'Familia',
            'tipo' => 'seleccion',
            'color' => 'black',
            'seleccion' => array('tabla' => 'pe_familias', 'indice' => 'id_familia', 'valor' => 'nombre_familia'),
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'peso_real',
            'texto' => 'Peso real (Kg)',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 3,
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'anada',
            'texto' => 'Añada',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'stock_minimo',
            'texto' => 'Stock mínimo',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 0,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'control_stock',
            'texto' => 'Control stock',
            'tipo' => 'seleccion',
            'color' => 'black',
            'requerido' => true,
            'seleccion' => array('tabla' => 'pe_si_no', 'indice' => 'indice', 'valor' => 'valor'),
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'fecha_alta',
            'texto' => 'Fecha alta',
            'tipo' => 'date',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'fecha_modificacion',
            'texto' => 'Fecha modificacion',
            'tipo' => 'date',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'modificado_por',
            'texto' => 'Modificado por',
            'tipo' => 'seleccion',
            'color' => 'black',
            'requerido' => true,
            'seleccion' => array('tabla' => 'pe_users', 'indice' => 'id', 'valor' => 'nombre'),
            'editar' => false,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'unidades_caja',
            'texto' => 'Unidades caja/compra',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 0,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'id_proveedor_web',
            'texto' => 'Proveedor',
            'tipo' => 'seleccion',
            'color' => 'black',
            'seleccion' => array('tabla' => 'pe_proveedores', 'indice' => 'id_proveedor', 'valor' => 'nombre_proveedor'),
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'tipo_unidad',
            'texto' => 'Tipo unidad',
            'tipo' => 'seleccion',
            'color' => 'black',
            'seleccion' => array('tabla' => 'pe_tipo_unidades', 'indice' => 'indice', 'valor' => 'valor'),
            'requerido' => true,
            'editar' => false,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        // $dato['estructura'][] = array(
        //     'campo' => 'precio_ultimo_unidad',
        //     'texto' => 'Precio Compra (€/unidad compra)',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 3,
        //     'color' => 'black',
        //     'requerido' => true,
        //     'ancho' => '30'
        // );
        // $dato['estructura'][] = array(
        //     'campo' => 'precio_ultimo_peso',
        //     'texto' => 'Precio Compra (€/Kg compra)',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 3,
        //     'color' => 'black',
        //     'requerido' => true,
        //     'ancho' => '30'
        // );
        // $dato['estructura'][] = array(
        //     'campo' => 'descuento_1_compra',
        //     'texto' => 'Descuento compra (%)',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 3,
        //     'color' => 'black',
        //     'requerido' => true,
        //     'ancho' => '30'
        // );
        // $dato['estructura'][] = array(
        //     'campo' => 'precio_transformacion_unidad',
        //     'texto' => 'Precio transformación (€/unidad)',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 3,
        //     'color' => 'black',
        //     'requerido' => true,
        //     // 'editar'=>false,
        //     // 'mostrar'=>false,
        //     'ancho' => '30'
        // );
        // $dato['estructura'][] = array(
        //     'campo' => 'precio_transformacion_peso',
        //     'texto' => 'Precio transformación (€/Kg)',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 3,
        //     'color' => 'black',
        //     'requerido' => true,
        //     // 'editar'=>false,
        //     // 'mostrar'=>false,
        //     'ancho' => '30'
        // );
        // $dato['estructura'][] = array(
        //     'campo' => 'precio_compra',
        //     'texto' => 'Precio Compra Final en Tienda',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 3,
        //     'color' => 'black',
        //     'requerido' => true,
        //     'editar'=>false,
        //     'ancho' => '30'
        // );
        // $dato['estructura'][] = array(
        //     'campo' => 'unidades_precio',
        //     'texto' => 'Unidades Precio',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 0,
        //     'color' => 'black',
        //     'requerido' => true,
        //     'ancho' => '30'
        // );
        $dato['estructura'][] = array(
            'campo' => 'tarifa_venta',
            'texto' => 'Tarifa PVP',
            'tipo' => 'number',
            'factor' => 1000,
            'decimales' => 2,
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        // $dato['estructura'][] = array(
        //     'campo' => 'beneficio_recomendado',
        //     'texto' => 'Beneficio recomendado (%)',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 2,
        //     'color' => 'black',
        //     'requerido' => true,
        //     'ancho' => '30'
        // );
        // $dato['estructura'][] = array(
        //     'campo' => 'margen_real_producto',
        //     'texto' => 'Margen (%)',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 2,
        //     'color' => 'black',
        //     'requerido' => true,
        //     'editar'=>false,
        //     'ancho' => '30'
        // );
        $dato['estructura'][] = array(
            'campo' => 'stock_total',
            'texto' => 'Total unidades stock',
            'tipo' => 'number',
            'factor' => 1,
            'decimales' => 0,
            'color' => 'black',
            'requerido' => true,
            'editar' => false,
            // 'mostrar'=>false,
            'ancho' => '30'
        );
        // $dato['estructura'][] = array(
        //     'campo' => 'valoracion',
        //     'texto' => 'Valor stock precio compra actual',
        //     'tipo' => 'number',
        //     'factor' => 1,
        //     'decimales' => 2,
        //     'color' => 'black',
        //     'requerido' => true,
        //     'editar'=>false,
        //     // 'mostrar'=>false,
        //     'ancho' => '30'
        // );
        // $dato['estructura'][] = array(
        //     'campo' => 'iva',
        //     'texto' => 'IVA',
        //     'tipo' => 'number',
        //     'factor' => 1000,
        //     'decimales' => 2,
        //     'color' => 'black',
        //     'requerido' => false,
        //     'editar'=>false,
        //     'ancho' => '30'
        // );
        $dato['estructura'][] = array(
            'campo' => 'url_producto',
            'texto' => 'Url producto',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'url_imagen_portada',
            'texto' => 'Imagen Producto',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => true,
            'ancho' => '30'
        );
        $dato['estructura'][] = array(
            'campo' => 'notas',
            'texto' => 'Notas',
            'tipo' => 'text',
            'color' => 'black',
            'requerido' => false,
            'ancho' => '30'
        );


        return $dato['estructura'];
    }

    function getVerProducto()
    {
        $id_pe_producto = $_POST['id_pe_producto'];
        $datos = $this->productos_->getFullProducto($id_pe_producto);


        $dato['estructura'] = $this->estructuraDatos();

        $dato['renderVer'] = '';
        $dato['renderVer'] .= '<form >';
        foreach ($dato['estructura'] as $k => $v) {
            if ($v['tipo'] == 'date') $datos[$v['campo']] = fechaEuropea($datos[$v['campo']]);
            if ($v['tipo'] == 'number') $datos[$v['campo']] = number_format($datos[$v['campo']] / $v['factor'], $v['decimales']);

            $dato['renderVer'] .= '<div class="form-group row">';
            $dato['renderVer'] .= '<label for="input' . ucfirst($v['campo']) . '" class="col-sm-4 col-form-label-sm">' . $v['texto'];
            if ($v['requerido']) $dato['renderVer'] .= '<span class="requerido">*</span>';
            $dato['renderVer'] .= ':</label>';

            $dato['renderVer'] .= '<div class="">';
            switch ($v['tipo']) {
                case 'date':
                case 'number':
                case 'text':
                    $dato['renderVer'] .= '<input type="text" readonly class="form-control-plaintext"  value="' . $datos[$v['campo']] . '">';
                    if ($v['campo'] == "url_imagen_portada") {
                        $dato['renderVer'] .= '<label  class="col-sm-4 col-form-label-sm"></label> ';
                        $dato['renderVer'] .= '<img src="' . $datos['url_imagen_portada'] . '" height="250" width="250" ></img>';
                    }

                    break;
                case 'seleccion':
                    $sql = "SELECT " . $v['seleccion']['valor'] . " FROM " . $v['seleccion']['tabla'] . " WHERE " . $v['seleccion']['indice'] . "='" . $datos[$v['campo']] . "'";
                    $row = $this->db->query($sql)->row_array();
                    $dato['renderVer'] .= '<input type="text" readonly class="form-control-plaintext"  value="' . $row[$v['seleccion']['valor']] . '">';
                    break;
                default:
            }


            // $dato['renderVer'] .= '<img src="'.$datos['url_imagen_portada'].'"  ></img>';
            $dato['renderVer'] .= '</div>';
            $dato['renderVer'] .= '</div>';
        }


        $dato['renderVer'] .= '</form>';
        echo  json_encode($dato['renderVer']);
    }
    function getVer2Producto()
    {
        $id_pe_producto = $_POST['id_pe_producto'];
        $datos = $this->productos_->getFullProducto($id_pe_producto);


        $dato['estructura'] = $this->estructuraDatos2();

        $dato['renderVer'] = '';
        $dato['renderVer'] .= '<form >';
        foreach ($dato['estructura'] as $k => $v) {
            if ($v['tipo'] == 'date') $datos[$v['campo']] = fechaEuropea($datos[$v['campo']]);
            if ($v['tipo'] == 'number') $datos[$v['campo']] = number_format($datos[$v['campo']] / $v['factor'], $v['decimales']);

            $dato['renderVer'] .= '<div class="form-group row">';
            $dato['renderVer'] .= '<label for="input' . ucfirst($v['campo']) . '" class="col-sm-4 col-form-label-sm">' . $v['texto'];
            if ($v['requerido']) $dato['renderVer'] .= '<span class="requerido">*</span>';
            $dato['renderVer'] .= ':</label>';

            $dato['renderVer'] .= '<div class="">';
            switch ($v['tipo']) {
                case 'date':
                case 'number':
                case 'text':
                    $dato['renderVer'] .= '<input type="text" readonly class="form-control-plaintext"  value="' . $datos[$v['campo']] . '">';
                    if ($v['campo'] == "url_imagen_portada") {
                        $dato['renderVer'] .= '<label  class="col-sm-4 col-form-label-sm"></label> ';
                        $dato['renderVer'] .= '<img src="' . $datos['url_imagen_portada'] . '" height="250" width="250" ></img>';
                    }

                    break;
                case 'seleccion':
                    $sql = "SELECT " . $v['seleccion']['valor'] . " FROM " . $v['seleccion']['tabla'] . " WHERE " . $v['seleccion']['indice'] . "='" . $datos[$v['campo']] . "'";
                    $row = $this->db->query($sql)->row_array();
                    $dato['renderVer'] .= '<input type="text" readonly class="form-control-plaintext"  value="' . $row[$v['seleccion']['valor']] . '">';
                    break;
                default:
            }


            // $dato['renderVer'] .= '<img src="'.$datos['url_imagen_portada'].'"  ></img>';
            $dato['renderVer'] .= '</div>';
            $dato['renderVer'] .= '</div>';
        }


        $dato['renderVer'] .= '</form>';
        echo  json_encode($dato['renderVer']);
    }

    function getEditarProducto()
    {
        // $this->load->database();
        $id_pe_producto = $_POST['id_pe_producto'];
        $datos = $this->productos_->getFullProducto($id_pe_producto);
        $dato['estructura'] = $this->estructuraDatos();

        $dato['renderVer'] = '';
        foreach ($dato['estructura'] as $k => $v) {
            if ($id_pe_producto == 0) {
                switch ($v['tipo']) {
                    case 'text':
                        $datos[$v['campo']] = "";
                        break;
                    case 'number':
                        $datos[$v['campo']] = 0;
                        break;
                    case 'date':
                        $datos[$v['campo']] = date("Y-m-d");
                        break;
                    case 'seleccion':
                        $datos[$v['campo']] = 0;
                        break;
                    default:
                        $datos[$v['campo']] = "";
                }
            }

            $disabled = "";
            $requerido = "";
            // mensaje($v['campo']);
            // mensaje(array_key_exists('mostrar',$v));
            if (array_key_exists('mostrar', $v) == 1) continue;
            if (array_key_exists('editar', $v) == 1) $disabled = 'disabled';
            if (array_key_exists('requerido', $v) == 1) $requerido = 'requerido="' . $v['requerido'] . '"';
            if ($id_pe_producto == 0 && $v['campo'] == 'codigo_producto') $disabled = "";
            if ($v['tipo'] == 'date') $datos[$v['campo']] = fechaEuropea($datos[$v['campo']]);
            if ($v['tipo'] == 'number') {
                // if($id_pe_producto==0){
                //     $datos[$v['campo']]="0";
                //     $datos[$v['campo']] = number_format($datos[$v['campo']], $v['decimales']);
                // }else{
                $datos[$v['campo']] = number_format($datos[$v['campo']] / $v['factor'], $v['decimales']);
                // }
            }

            $dato['renderVer'] .= '<form>';
            $dato['renderVer'] .= ' <div class="form-group row">';
            $dato['renderVer'] .= '<label for="input' . ucfirst($v['campo']) . '" class="col-sm-4 col-form-label">' . $v['texto'];
            if ($v['requerido']) $dato['renderVer'] .= '<span class="requerido">*</span>';
            $dato['renderVer'] .= ':</label>';
            $dato['renderVer'] .= '<div class="col-sm-8">';
            switch ($v['tipo']) {
                case 'date':
                case 'number':
                    // if($id_pe_producto==0){
                    //     $dato['renderVer'] .= '<input type="text" '. $disabled.' '.$requerido.' class="form-control " name="' . $v['campo'] . '"  value="0">';
                    // }else{
                    $dato['renderVer'] .= '<input type="text" ' . $disabled . ' ' . $requerido . ' class="form-control " name="' . $v['campo'] . '"  value="' . $datos[$v['campo']] . '">';
                    // }
                    break;
                case 'text':
                    // if($id_pe_producto==0){
                    //     $dato['renderVer'] .= '<input type="text" '. $disabled.' '.$requerido.' class="form-control " name="' . $v['campo'] . '"  value="">';
                    // }else{
                    $dato['renderVer'] .= '<input type="text" ' . $disabled . ' ' . $requerido . ' class="form-control " name="' . $v['campo'] . '"  value="' . $datos[$v['campo']] . '">';
                    // }
                    break;
                case 'seleccion':
                    $sql = "SELECT " . $v['seleccion']['indice'] . ", " . $v['seleccion']['valor'] . " FROM " . $v['seleccion']['tabla'] . " ORDER BY " . $v['seleccion']['valor'];
                    $result = $this->db->query($sql)->result_array();
                    $dato['renderVer'] .= '<select ' . $disabled . ' class="form-control custom-select custom-select-lg mb-3" name="' . $v['campo'] . '" >';
                    $dato['renderVer'] .= '<option value="0">Seleccionar opción</option>';
                    foreach ($result as $k1 => $v1) {
                        $indice = $v1[$v['seleccion']['indice']];
                        $valor = $v1[$v['seleccion']['valor']];
                        $selected = "";
                        if ($datos[$v['campo']] == $indice) $selected = "selected";
                        $dato['renderVer'] .= "<option $selected value='$indice'>$valor </option>";
                    }
                    $dato['renderVer'] .= '</select>';

                    break;
                default:
            }
            $dato['renderVer'] .= '</div>';
            $dato['renderVer'] .= '</div>';
        }

        $dato['renderVer'] .= '<form>';
        $dato['renderVer'] .= '<div class="errorVerificacion"></div>';


        echo  json_encode($dato['renderVer']);
    }
    function getEditar2Producto()
    {
        // $this->load->database();
        $id_pe_producto = $_POST['id_pe_producto'];
        $datos = $this->productos_->getFullProducto($id_pe_producto);
        $dato['estructura'] = $this->estructuraDatos2();

        $dato['renderVer'] = '';
        foreach ($dato['estructura'] as $k => $v) {
            if ($id_pe_producto == 0) {
                switch ($v['tipo']) {
                    case 'text':
                        $datos[$v['campo']] = "";
                        break;
                    case 'number':
                        $datos[$v['campo']] = 0;
                        break;
                    case 'date':
                        $datos[$v['campo']] = date("Y-m-d");
                        break;
                    case 'seleccion':
                        $datos[$v['campo']] = 0;
                        break;
                    default:
                        $datos[$v['campo']] = "";
                }
            }

            $disabled = "";
            $requerido = "";
            // mensaje($v['campo']);
            // mensaje(array_key_exists('mostrar',$v));
            if (array_key_exists('mostrar', $v) == 1) continue;
            if (array_key_exists('editar', $v) == 1) $disabled = 'disabled';
            if (array_key_exists('requerido', $v) == 1) $requerido = 'requerido="' . $v['requerido'] . '"';
            if ($id_pe_producto == 0 && $v['campo'] == 'codigo_producto') $disabled = "";
            if ($v['tipo'] == 'date') $datos[$v['campo']] = fechaEuropea($datos[$v['campo']]);
            if ($v['tipo'] == 'number') {
                // if($id_pe_producto==0){
                //     $datos[$v['campo']]="0";
                //     $datos[$v['campo']] = number_format($datos[$v['campo']], $v['decimales']);
                // }else{
                $datos[$v['campo']] = number_format($datos[$v['campo']] / $v['factor'], $v['decimales']);
                // }
            }

            $dato['renderVer'] .= '<form>';
            $dato['renderVer'] .= ' <div class="form-group row">';
            $dato['renderVer'] .= '<label for="input' . ucfirst($v['campo']) . '" class="col-sm-4 col-form-label">' . $v['texto'];
            if ($v['requerido']) $dato['renderVer'] .= '<span class="requerido">*</span>';
            $dato['renderVer'] .= ':</label>';
            $dato['renderVer'] .= '<div class="col-sm-8">';
            switch ($v['tipo']) {
                case 'date':
                case 'number':
                    // if($id_pe_producto==0){
                    //     $dato['renderVer'] .= '<input type="text" '. $disabled.' '.$requerido.' class="form-control " name="' . $v['campo'] . '"  value="0">';
                    // }else{
                    $dato['renderVer'] .= '<input type="text" ' . $disabled . ' ' . $requerido . ' class="form-control " name="' . $v['campo'] . '"  value="' . $datos[$v['campo']] . '">';
                    // }
                    break;
                case 'text':
                    // if($id_pe_producto==0){
                    //     $dato['renderVer'] .= '<input type="text" '. $disabled.' '.$requerido.' class="form-control " name="' . $v['campo'] . '"  value="">';
                    // }else{
                    $dato['renderVer'] .= '<input type="text" ' . $disabled . ' ' . $requerido . ' class="form-control " name="' . $v['campo'] . '"  value="' . $datos[$v['campo']] . '">';
                    // }
                    break;
                case 'seleccion':
                    $sql = "SELECT " . $v['seleccion']['indice'] . ", " . $v['seleccion']['valor'] . " FROM " . $v['seleccion']['tabla'];
                    $result = $this->db->query($sql)->result_array();
                    $dato['renderVer'] .= '<select ' . $disabled . ' class="form-control custom-select custom-select-lg mb-3" name="' . $v['campo'] . '" >';
                    $dato['renderVer'] .= '<option value="0">Seleccionar opción</option>';
                    foreach ($result as $k1 => $v1) {
                        $indice = $v1[$v['seleccion']['indice']];
                        $valor = $v1[$v['seleccion']['valor']];
                        $selected = "";
                        if ($datos[$v['campo']] == $indice) $selected = "selected";
                        $dato['renderVer'] .= "<option $selected value='$indice'>$valor </option>";
                    }
                    $dato['renderVer'] .= '</select>';

                    break;
                default:
            }
            $dato['renderVer'] .= '</div>';
            $dato['renderVer'] .= '</div>';
        }

        $dato['renderVer'] .= '<form>';
        $dato['renderVer'] .= '<div class="errorVerificacion"></div>';


        echo  json_encode($dato['renderVer']);
    }

    function cambiar_status_producto()
    {
        $id = $_POST['id'];
        $status_producto = $this->db->query("SELECT status_producto FROM pe_productos WHERE id='$id'")->row()->status_producto;
        $status_producto = 1 - $status_producto;
        $resultado = $this->db->query("UPDATE pe_productos SET status_producto='$status_producto' WHERE id='$id'");
        echo  json_encode($resultado);
    }

    public function productosSpeedy($status_producto = 1)
    {
        

        $dato = array();

        //lee datos última entrada
        $row = $this->getVariablesTabla();
        $dato['paginas'] = $row->paginas;
        $dato['pagina'] = $row->pagina;
        $dato['buscar'] = $row->buscar;
        $dato['columna_1'] = $row->columna_1;
        $dato['columna_2'] = $row->columna_2;
        $dato['columna_3'] = $row->columna_3;
        $dato['columna_4'] = $row->columna_4;
        $dato['columna_5'] = $row->columna_5;
        $dato['columna_6'] = $row->columna_6;
        $dato['columna_7'] = $row->columna_7;
        $dato['columna_8'] = $row->columna_8;
        $dato['columna_9'] = $row->columna_9;
        $dato['columna_10'] = $row->columna_10;
        $dato['columna_11'] = $row->columna_11;


        $dato['estructura'] = $this->estructuraDatos();
        $dato['status_producto'] = $status_producto;



        $dato['productos'] = $this->db->query("SELECT p.*, pr.nombre_proveedor as proveedor FROM pe_productos p
                                                 LEFT JOIN pe_proveedores pr ON p.id_proveedor_web=pr.id_proveedor
                                                 WHERE status_producto='$status_producto'
                                                 ORDER BY p.codigo_producto")->result();
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('productosSpeedy.php', $dato);
        $this->load->view('templates/footer.html');

        $this->load->view('myModal.php');
        $this->load->view('myModalPregunta.php');
        $this->load->view('myModalProducto.php');
    }

    public function productos_Speedy($status_producto = 1)
    {
        $dato = array();
        $dato['estructura'] = $this->estructuraDatos2();
        $dato['status_producto'] = $status_producto;


        $dato['productos'] = $this->db->query("SELECT p.*, pr.nombre_proveedor as proveedor FROM pe_productos p
                                                 LEFT JOIN pe_proveedores pr ON p.id_proveedor_web=pr.id_proveedor
                                                 WHERE status_producto='$status_producto'
                                                 ORDER BY p.codigo_producto")->result();
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('productos_Speedy.php', $dato);
        $this->load->view('templates/footer.html');

        $this->load->view('myModal.php');
        $this->load->view('myModalPregunta.php');
        $this->load->view('myModalProducto.php');
    }


    public function productos()
    {
        

        $crud = new grocery_CRUD();

        $crud->unset_bootstrap();
        $crud->unset_jquery();
        $crud->set_theme('bootstrap');

        $crud->set_table('pe_productos');
        $crud->set_lang_string('delete_error_message', 'My Own Error Message Here!');
        $crud->callback_delete(array($this, '_delete'));
        //$crud->callback_before_delete(array($this,'_producto_before_delete'));


        $output = $crud->render();
        $this->_outputProductos($output, 'Productos');
    }

    public function _delete($primary_key)
    {
        $this->db->update('pe_productos', array('notas' => 'borrado'), array('id' => $primary_key));
        return false;
    }

    function insertProductosPeso()
    {
        $result = $this->db->query("SHOW FIELDS FROM pe_productos")->result();

        extract($_POST);
        $textoError = "";
        $error = false;
        $titulo = "Información";
        if ($this->existeCodigoProducto($codigoProducto)) {
            $error = true;
            $textoError = "NO SE PUEDE CREAR el producto " . $codigoProducto . " porque YA existe";
            echo  json_encode(array('titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
            return;
        }
        $row = $this->db->query("SELECT * FROM pe_productos WHERE codigo_producto='$codigoProductoOriginal'")->row_array();
        $set = "";
        unset($row['id']);
        $row['codigo_producto'] = $codigoProducto;
        // $row['cat_referencia']=$codigoProducto;
        // $row['cat_referencia_en']=$codigoProducto;
        // $row['cat_referencia_fr']=$codigoProducto;

        // $row['cat_nombre']=$nombre;
        // $row['cat_nombre_en']=$nombre;
        // $row['cat_nombre_fr']=$nombre;
        // $row['cat_url_producto']=$row['url_imagen_portada'];
        // $row['cat_url_producto_en']=$row['url_imagen_portada'];
        // $row['cat_url_producto_fr']=$row['url_imagen_portada'];
        $row['url_imagen_portada_excel'] == $row['url_imagen_portada'];
        $row['id_producto'] = $idProducto;
        $row['nombre'] = $nombre;
        $row['nombre_generico'] = $nombreGenerico;
        $row['precio_compra'] = $precioCompra;
        $row['precio_compra_excel'] = $precioCompra;
        $row['precio_ultimo_unidad'] = $precioCompra;
        $row['precio_ultimo_peso'] = 0;
        $row['unidades_precio'] = 1000;
        $row['tarifa_venta'] = $tarifaVenta;
        $row['tarifa_venta_excel'] = $tarifaVenta;
        $row['tarifa_venta_unidad'] = $tarifaVenta;
        $row['tarifa_venta_peso'] = 0;
        $row['margen_real_producto'] = $beneficioProducto;
        $row['margen_real_producto_excel'] = $beneficioProducto;
        $row['iva'] = $iva;

        $row['anada'] = isset($anada) ? $anada : $row['anada'];
        $row['peso_real'] = isset($pesoReal) ? $pesoReal : $row['peso_real'];

        $row['fecha_caducidad'] = '1970-01-010';
        $hoy = date("Y-m-d");
        $row['fecha_modificacion'] = $hoy;
        $row['fecha_proveedor_2'] = $hoy;
        $row['fecha_proveedor_3'] = $hoy;
        $row['fecha_alta'] = $hoy;
        $row['modificado_por'] = $_SESSION['id'];
        $row['stock_minimo'] = 1000;
        $row['stock_total'] = 0;
        $row['unidades_stock'] = 0;
        $row['valoracion'] = 0;
        $row['control_stock'] = 'Sí';
        $row['tipo_unidad'] = 'Und';
        $row['tipo_unidad_mostrar'] = 0;
        $row['descuento_profesionales'] = 0;
        $row['tarifa_profesionales'] = $tarifaVenta;
        $row['margen_venta_profesionales'] = $beneficioProducto;
        $row['descuento_profesionales_vip'] = 0;
        $row['tarifa_profesionales_vip'] = $tarifaVenta;
        $row['margen_venta_profesionales_vip'] = $beneficioProducto;
        // Los campos int que tengan '' se pone 0
        $result = $this->db->query("SHOW FIELDS FROM pe_productos")->result();
        foreach ($result as $k => $v) {
            if ($k && strpos($v->Type, 'int') && trim($row[$v->Field]) == '')
                $row[$v->Field] = 0;
        }


        foreach ($row as $k => $v) {
            $set .= "$k = '$v', ";
        }
        $set = substr(trim($set), 0, -1);

        $sql = "INSERT INTO pe_productos SET " . $set;
        // mensaje('insertar producto '.$sql);
        if (!$this->db->query($sql)) {
            $textoError = "NO SE HA PODIDO CREAR el producto " . $codigoProducto . " ERROR AL INSERTAR. INFORMAR Administrador";
            $error = true;
        };
        $this->load->library('email');
        $ahora = date('d/m/Y H:i:s');
        enviarEmail($this->email, 'Insertado productos peso', host() . ' - Pernil181', 'Realizada por: ' . $this->session->nombre . '<br>Fecha: ' . $ahora, 3);
        echo  json_encode(array('sql' => $sql, 'titulo' => $titulo, 'error' => $error, 'textoError' => $textoError));
    }

    function existeCodigoProducto($codigoProducto)
    {
        return $this->db->query("SELECT codigo_producto FROM pe_productos WHERE codigo_producto='$codigoProducto'")->num_rows();
    }
}
