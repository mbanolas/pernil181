<?php

class Stocks_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    function getCantidad($id_pe_producto){
        $sql="SELECT cantidad FROM pe_stocks_totales WHERE id_pe_producto='$id_pe_producto'";
        if(!$this->db->query($sql)->num_rows()) return 0;
        return $this->db->query($sql)->row()->cantidad;
    }

    function productosStock0oMenor() {
        $sql = "SELECT  pr.id as id,pr.id_producto as id_producto,st.cantidad as cantidad FROM pe_productos pr 
        LEFT JOIN pe_stocks_totales st ON st.id_pe_producto=pr.id
        WHERE pr.status_producto='1' 
            AND pr.control_stock='Sí' 
            AND LCASE(pr.nombre) NOT LIKE '%compra%'
            AND LCASE(pr.nombre) NOT LIKE '%Bandeja%'
            AND LCASE(pr.nombre) NOT LIKE '%plato%'
            AND pr.id_producto!=0 
                ";       
        $result = $this->db->query($sql)->result();
        $cantidades=array();
        foreach($result as $k=>$v){
            if(isset($cantidades[$v->id_producto])) $cantidades[$v->id_producto]+=$v->cantidad;
            else $cantidades[$v->id_producto]=$v->cantidad;
        }
        $id_productos=array();
        foreach($cantidades as $k=>$v){
            // mensaje('$k '.$k);
            // mensaje('$v '.$v);
            if($v<=0) {
                $id_productos[]=$k;
            }
        }
        $where=' id_producto='.implode(' || id_producto=',$id_productos);
        // mensaje($where);
       $sql="SELECT p.codigo_producto as codigo_producto, p.id_producto as id_producto, p.nombre as nombre, s.cantidad as cantidad, p.tipo_unidad as tipo_unidad, p.status_producto as status_producto, p.control_stock as control_stock 
                FROM pe_productos p 
                LEFT JOIN pe_stocks_totales s ON p.id=s.id_pe_producto
                WHERE ($where) AND status_producto=1 AND p.control_stock='Sí' ORDER BY p.id_producto";
        $resultArray=$this->db->query($sql)->result_array();        
        // mensaje($sql);
         return $resultArray;



        $sql = "SELECT p.codigo_producto as codigo_producto, p.id_producto as id_producto, p.nombre as nombre, s.cantidad as cantidad, p.tipo_unidad as tipo_unidad, p.status_producto as status_producto, p.control_stock as control_stock"
                . " FROM pe_productos p"
                . " LEFT JOIN pe_stocks_totales s ON p.id=s.id_pe_producto "
                . " WHERE s.cantidad<=0 AND p.status_producto=1 AND p.control_stock='Sí' "
                . " AND LCASE(p.nombre) NOT LIKE '%compra%' "
                . " AND LCASE(p.nombre) NOT LIKE '%Bandeja%' "
                . " AND LCASE(p.nombre) NOT LIKE '%plato%' "
                . " GROUP BY p.codigo_producto, p.id_producto, p.nombre, s.cantidad, p.tipo_unidad, p.status_producto, p.control_stock"
                . " ORDER BY p.codigo_producto";
        // mensaje('productosStock0oMenor '.$sql);
        $resultArray=$this->db->query($sql)->result_array();
        foreach($resultArray as $k=>$v){
            $num=$this->db->query("SELECT id_producto FROM pe_productos WHERE id_producto='".$v['id_producto']."' AND status_producto=1")->num_rows();
            // mensaje('productosStock0oMenor '."SELECT id_producto FROM pe_productos WHERE id_producto='".$v['id_producto']."' AND status_producto=1");
            // mensaje($v['id_producto'].' '.$num);
            if($num!=1) unset($resultArray[$k]);
        }

        return $resultArray;
    }

    function getActualizacionStocksMinimos() {
        $dato = array();
        $ultimaFechaStocksMinimos = "";
        if ($this->db->query("SELECT * FROM pe_datos_aplicacion ORDER BY id DESC LIMIT 1")->num_rows() > 0)
            $ultimaFechaStocksMinimos = $this->db->query("SELECT * FROM pe_datos_aplicacion ORDER BY id DESC LIMIT 1")->row()->mes_stocks_minimos;
        $hoy = date('Y-m-d');
        $dato['ultimaFechaStocksMinimos'] = "";
        if (substr($hoy, 0, 7) == substr($ultimaFechaStocksMinimos, 0, 7))
            $dato['ultimaFechaStocksMinimos'] = $ultimaFechaStocksMinimos;
        if($ultimaFechaStocksMinimos) { 
            return setlocale(LC_TIME, 'es_ES.UTF-8'); echo (strftime('%A, %e  de %B  de %Y', strtotime($ultimaFechaStocksMinimos)));
        }
        return false;
    }
    
    function deshacerTransformacion($id) {
        //echo '$id '. $id.'<br>';
        $id_transformacion = $this->db->query("SELECT id_transformacion FROM pe_transformaciones WHERE id='$id'")->row()->id_transformacion;

        $sql = "SELECT id,fecha_caducidad,id_pe_producto,cantidad FROM pe_lineas_transformaciones WHERE id_transformacion='$id_transformacion'";

        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $id_pe_producto = $v->id_pe_producto;
            $cantidad = -$v->cantidad;
            $fecha_caducidad_stock = $v->fecha_caducidad;
            $this->sumaCantidadStocks($id_pe_producto, $cantidad, $fecha_caducidad_stock = "0000-00-00");
        }
        $this->db->query("DELETE FROM pe_lineas_transformaciones WHERE id_transformacion='$id_transformacion'");
        $this->db->query("DELETE FROM pe_transformaciones WHERE id_transformacion='$id_transformacion'");
        return;
    }

    function sumaCantidadStocksEmbalajes($id_pe_producto, $cantidad, $tipoTienda) {

        if ($tipoTienda == 1) {
            $sql = "SELECT em.id,lem.codigo_embalaje as codigo_embalaje, lem.cantidad as cantidad_embalaje,lem.tienda,lem.online
                    FROM `pe_embalajes` em
                    LEFT JOIN pe_lineas_embalajes lem ON lem.id_embalajes=em.id
                    WHERE lem.tienda='1' AND em.codigo_producto='$id_pe_producto'";
        }
        if ($tipoTienda == 2) {
            $sql = "SELECT em.id,lem.codigo_embalaje as codigo_embalaje,lem.cantidad as cantidad_embalaje,lem.tienda,lem.online
                    FROM `pe_embalajes` em
                    LEFT JOIN pe_lineas_embalajes lem ON lem.id_embalajes=em.id
                    WHERE lem.online='1' AND em.codigo_producto='$id_pe_producto'";
        }
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $id_pe_producto = $this->productos_->getId_pe_producto($v->codigo_embalaje);
            $cantidad_embalaje = $cantidad * ($v->cantidad_embalaje) / 1000;
            $this->sumaCantidadStocks($id_pe_producto, -$cantidad_embalaje, $fecha_caducidad_stock = "0000-00-00");
        }
    }

    function armonizarStocks($id_pe_producto) {
        //se eliminan todas las anotaciones de stocks con cantidad=0 del producto en cuestion 
        $sql = "DELETE FROM pe_stocks WHERE cantidad=0 AND id_pe_producto='$id_pe_producto'";
        $this->db->query($sql);
        //agrupar cantidades con igual fecha de caducidad
        $sql = "SELECT sum(cantidad) as cantidad,id_pe_producto,fecha_caducidad_stock,proveedor FROM pe_stocks WHERE id_pe_producto='$id_pe_producto' GROUP BY fecha_caducidad_stock,proveedor";
        $result = $this->db->query($sql)->result();
        $this->db->query("DELETE FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'");
        $this->load->model('productos_');
        $activo = $this->productos_->getStatusProducto($id_pe_producto);
        foreach ($result as $k => $v) {
            $sql = "INSERT INTO pe_stocks 
                                  SET cantidad='" . $v->cantidad . "',
                                      codigo_producto='" . $v->id_pe_producto . "',
                                      codigo_bascula='" . $v->id_pe_producto . "',
                                      id_pe_producto='" . $v->id_pe_producto . "',
                                      fecha_caducidad_stock='" . $v->fecha_caducidad_stock . "',
                                      fecha_modificacion_stock='" . date('Y-m-d') . "', 
                                      fecha_entrada='" . date('Y-m-d') . "',
                                      activo='$activo',    
                                      proveedor='" . $v->proveedor . "'";
            $this->db->query($sql);
        }
        $this->armonizarStocksTotales($id_pe_producto);
        return $sql;
    }

    function armonizarStocksTotales($id_pe_producto) {
        //los stocks_totales se ponen en funcion de los stocks con fecha de caducidad
        $sql = "SELECT sum(cantidad) as cantidad FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'";
        $cantidad = $this->db->query($sql)->row()->cantidad;
        $sql = "SELECT proveedor as proveedor FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'"; 
        $proveedor = $this->db->query($sql)->num_rows()?$this->db->query($sql)->row()->proveedor:"";
        $hoy = date("Y-m-d");
        $this->load->model('productos_');
        $valoracion = $this->productos_->getValoracion($id_pe_producto, $cantidad);
        $activo = $this->productos_->getStatusProducto($id_pe_producto);
        $this->db->query("DELETE FROM pe_stocks_totales WHERE id_pe_producto='$id_pe_producto'");
        $sql = "INSERT INTO pe_stocks_totales SET 
                                id_pe_producto='$id_pe_producto',
                                codigo_producto='$id_pe_producto',
                                codigo_bascula='$id_pe_producto', 
                                proveedor='$proveedor',    
                                nombre='$id_pe_producto',
                                cantidad='$cantidad',
                                fecha_modificacion_stock='$hoy',
                                valoracion='$valoracion',
                                activo='$activo'
                                ";
        $this->db->query($sql);
        $stock_total=$cantidad/1000;
        $sql="UPDATE pe_productos SET stock_total='$stock_total' WHERE id='$id_pe_producto'";
        $this->db->query($sql);
        $this->productos_->ponerStockValor($id_pe_producto);
    }

    function getCantidadTotal($id_pe_producto) {
        $sql = "SELECT cantidad FROM pe_stocks_totales WHERE id_pe_producto='$id_pe_producto'";
        if ($this->db->query($sql)->num_rows() > 0)
            return $this->db->query($sql)->row()->cantidad;
        return 0;
    }

    function putValoracion($id_pe_producto, $valoracion) {
        $this->db->query("UPDATE pe_stocks_totales SET valoracion='$valoracion' WHERE id_pe_producto='$id_pe_producto'");
        return true;
    }

    function sumaCantidadStocks($id_pe_producto, $cantidad, $fecha_caducidad_stock = "0000-00-00") {
        //inserta en pe_stocks la cantidad con su fecha de caducidad 
        //ajusta pe_stocks segun fechas de caducidad
        //armoniza pe_stocks (elimona registros con cantidad=0;agrupar cantidades con igual fecha de caducidad
        //armoniza pe_stocks totales segón pe_stocks
        $this->load->model('productos_');
        //log_message('INFO', 'sumaCantidadStocks '.$this->productos_->getControlStock($id_pe_producto));
        if ($this->productos_->getControlStock($id_pe_producto) == 'No')
            return;

        $id_proveedor = $this->productos_->getIdProveedor($id_pe_producto);
        $activo = $this->productos_->getStatusProducto($id_pe_producto);
        $sql = "INSERT INTO pe_stocks SET cantidad='$cantidad', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto',fecha_caducidad_stock='$fecha_caducidad_stock', fecha_modificacion_stock='" . date('Y-m-d') . "', fecha_entrada='" . date('Y-m-d') . "', activo='$activo',  proveedor='$id_proveedor'";
        $this->db->query($sql);

        $this->ajustarFechasCaducidades($id_pe_producto);
        $this->armonizarStocks($id_pe_producto);
        $this->productos_->ponerStockValor($id_pe_producto);
        return true;
    }

    function ajustarFechasCaducidades($id_pe_producto) {
        //los valores negativos los sustituye por otras fechas de caducidad por orden de más antiguo a más nuevp
        $sql = "SELECT id,cantidad, fecha_caducidad_stock FROM pe_stocks WHERE codigo_producto='$id_pe_producto' ORDER BY fecha_caducidad_stock ";
        $result = $this->db->query($sql)->result();

        $next = 0;
        $last = end($result);
        $datos = array();
        foreach ($result as $k => $v) {
            $v->cantidad += $next;
            if ($v == $last) {
                $this->db->query("UPDATE pe_stocks SET cantidad='" . $v->cantidad . "' WHERE id='" . $v->id . "'");
                break;
            }
            if ($v->cantidad < 0) {
                $next = $v->cantidad;
                $v->cantidad = 0;
            } else {
                $next = 0;
            }
            $this->db->query("UPDATE pe_stocks SET cantidad='" . $v->cantidad . "' WHERE id='" . $v->id . "'");
        }
    }

    function getStockTotales(){
        $response = array();
        $sql="SELECT p.codigo_producto as codigo13,p.nombre as nombre, FORMAT(s.cantidad/1000,0) as cantidad  FROM pe_stocks_totales s 
                LEFT JOIN pe_productos p ON p.id=s.id_pe_producto
                WHERE 1
                ORDER BY p.codigo_producto";
        $response=$this->db->query($sql)->result_array();
        return $response;
    }
    
    function getStockTotalesSinPacks(){
        $response = array();
        $sql="SELECT p.codigo_producto as codigo13,p.nombre as nombre, FORMAT(s.cantidad/1000,0) as cantidad , id_pe_producto_pack as pack 
                FROM pe_stocks_totales s 
                LEFT JOIN pe_productos p ON p.id=s.id_pe_producto
                LEFT JOIN pe_packs pk ON pk.id_pe_producto_pack=p.id
                WHERE id_pe_producto_pack IS NULL and p.codigo_producto IS NOT NULL
                ORDER BY p.codigo_producto";
        $response=$this->db->query($sql)->result_array();
        return $response;
    }

    


    function bajarExcelStocksTotales() {
        $dato['stocksTotales'] = $this->getStocksTotales($_POST['buscadores']);
        //return $dato['stocksTotales'];
        $usuario = $_POST['usuario'];

        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);

        $this->excel->getActiveSheet()->setCellValue('A1', "STOCKS TOTALES ");
        $this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
        $hoy = date('d/m/Y');
        $this->excel->getActiveSheet()->setCellValue('A2', "Fecha: $hoy");

        $filaInicial = 4;
        $c = "A";
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][0]);
        $c++;
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][1]);
        $c++;
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][2]);
        $c = "G";
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][3]);
        $c++;
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][4]);
        $c = "F";
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][5]);

        $this->excel->getActiveSheet()->getStyle("A$filaInicial:F$filaInicial")->getFont()->setItalic(true);



        $filaInicial = 5;
        $this->excel->getActiveSheet()->setCellValue("A$filaInicial", "Código 13");
        $this->excel->getActiveSheet()->setCellValue("B$filaInicial", "C. Báscula");
        $this->excel->getActiveSheet()->setCellValue("C$filaInicial", "Producto");
        $this->excel->getActiveSheet()->setCellValue("D$filaInicial", "Grupo");
        $this->excel->getActiveSheet()->setCellValue("E$filaInicial", "Familia");
        $this->excel->getActiveSheet()->setCellValue("F$filaInicial", "Proveedor");
        $this->excel->getActiveSheet()->setCellValue("G$filaInicial", "Cantidad");

        $this->excel->getActiveSheet()->setCellValue("H$filaInicial", "Fecha modificacion");

        $this->excel->getActiveSheet()->setCellValue("I$filaInicial", "Control Stock");
        if ($usuario != 2)
            $this->excel->getActiveSheet()->setCellValue("J$filaInicial", "Valoracion Stock");

        $this->excel->getActiveSheet()->getStyle("G$filaInicial:G$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("I$filaInicial:I$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $this->excel->getActiveSheet()->getStyle("H$filaInicial:H$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:J$filaInicial")->getFont()->setBold(true);


        $this->load->model('productos_');
        foreach ($dato['stocksTotales']['codigos'] as $k => $v) {
            $filaInicial++;
            $fecha = fechaEuropeaSinHora($dato['stocksTotales']['fechas'][$k]);
            $valoracion = number_format($this->productos_->getValoracion($dato['stocksTotales']['id_pe_producto'][$k], $dato['stocksTotales']['cantidades'][$k] / 1000), 2, ",", ".");
            $valoracion = $this->productos_->getValoracion($dato['stocksTotales']['id_pe_producto'][$k], $dato['stocksTotales']['cantidades'][$k] / 1000);

            $this->excel->getActiveSheet()->setCellValue("A$filaInicial", $v);
            $this->excel->getActiveSheet()->setCellValue("B$filaInicial", $dato['stocksTotales']['codigosBascula'][$k] . "   ");
            $this->excel->getActiveSheet()->setCellValue("C$filaInicial", $dato['stocksTotales']['nombres'][$k]);
            $this->excel->getActiveSheet()->setCellValue("D$filaInicial", $dato['stocksTotales']['grupos'][$k]);
            $this->excel->getActiveSheet()->setCellValue("E$filaInicial", $dato['stocksTotales']['familias'][$k]);
            $this->excel->getActiveSheet()->setCellValue("F$filaInicial", $dato['stocksTotales']['nombresProveedores'][$k]);
            $this->excel->getActiveSheet()->setCellValue("G$filaInicial", $dato['stocksTotales']['cantidades'][$k] / 1000);
            $this->excel->getActiveSheet()->setCellValue("H$filaInicial", $fecha);
            //$this->excel->getActiveSheet()->setCellValue("I$filaInicial", $valoracion); 
            $this->excel->getActiveSheet()->setCellValue("I$filaInicial", $dato['stocksTotales']['controlStock'][$k]);
            if ($usuario != 2)
                $this->excel->getActiveSheet()->setCellValue("J$filaInicial", $dato['stocksTotales']['importes'][$k] / 1000);

            $this->excel->getActiveSheet()->getStyle("I$filaInicial")->getNumberFormat()->setFormatCode('0.00');
            $this->excel->getActiveSheet()->getStyle("C$filaInicial:F$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->getStyle("G$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle("I$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->getStyle("H$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        $filaInicial++;
        //$this->excel->getActiveSheet()->getStyle("E$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        // $this->excel->getActiveSheet()->getStyle("A$filaInicial:H$filaInicial")->getFont()->setBold(true);
        // $this->excel->getActiveSheet()->getStyle("A$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(80);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(60);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);



        $hoy = str_replace("/", "_", $hoy);
        $filename = "Stocks totales $hoy.xls";
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
       
        // $directorio = "info_stocks/" . $filename;

        // $objWriter->save($directorio);
        // return $directorio;
        return true;
    }

    function bajarExcelStocks() {
        $dato['stocks'] = $this->getStocks($_POST['buscadores']);
        //return $dato['stocks'];


        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);

        $this->excel->getActiveSheet()->setCellValue('A1', "STOCKS FECHA CADUCIDAD ");
        $this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
        $hoy = date('d/m/Y');
        $this->excel->getActiveSheet()->setCellValue('A2', "Fecha: $hoy");

        $filaInicial = 4;
        $c = "A";
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][0]);
        $c++;
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][1]);
        $c++;
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][2]);
        $c = "G";
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][3]);
        $c++;
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][4]);
        $c = "F";
        $this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][5]);

        $this->excel->getActiveSheet()->getStyle("A$filaInicial:F$filaInicial")->getFont()->setItalic(true);



        $filaInicial = 5;
        $this->excel->getActiveSheet()->setCellValue("A$filaInicial", "Código 13");
        $this->excel->getActiveSheet()->setCellValue("B$filaInicial", "C. Báscula");
        $this->excel->getActiveSheet()->setCellValue("C$filaInicial", "Producto");
        $this->excel->getActiveSheet()->setCellValue("D$filaInicial", "Grupo");
        $this->excel->getActiveSheet()->setCellValue("E$filaInicial", "Familia");
        $this->excel->getActiveSheet()->setCellValue("F$filaInicial", "Proveedor");
        $this->excel->getActiveSheet()->setCellValue("G$filaInicial", "Cantidad");
        $this->excel->getActiveSheet()->setCellValue("H$filaInicial", "Fecha caducidad");
        $this->excel->getActiveSheet()->setCellValue("I$filaInicial", "Control Stock");

        $this->excel->getActiveSheet()->getStyle("G$filaInicial:G$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("H$filaInicial:H$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:I$filaInicial")->getFont()->setBold(true);



        foreach ($dato['stocks']['codigos'] as $k => $v) {
            $filaInicial++;
            $fecha = fechaEuropeaSinHora($dato['stocks']['fechas'][$k]);
            if ($fecha == '01/01/1970')
                $fecha = "";
            $this->excel->getActiveSheet()->setCellValue("A$filaInicial", $v);
            $this->excel->getActiveSheet()->setCellValue("B$filaInicial", $dato['stocks']['codigosBascula'][$k] . "   ");
            $this->excel->getActiveSheet()->setCellValue("C$filaInicial", $dato['stocks']['nombres'][$k]);
            $this->excel->getActiveSheet()->setCellValue("D$filaInicial", $dato['stocks']['grupos'][$k]);
            $this->excel->getActiveSheet()->setCellValue("E$filaInicial", $dato['stocks']['familias'][$k]);
            $this->excel->getActiveSheet()->setCellValue("F$filaInicial", $dato['stocks']['nombresProveedores'][$k]);
            $this->excel->getActiveSheet()->setCellValue("G$filaInicial", $dato['stocks']['cantidades'][$k] / 1000);
            $this->excel->getActiveSheet()->setCellValue("H$filaInicial", $fecha);
            $this->excel->getActiveSheet()->setCellValue("I$filaInicial", $dato['stocks']['controlStock'][$k]);

            $this->excel->getActiveSheet()->getStyle("C$filaInicial:F$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->getStyle("G$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle("H$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        $filaInicial++;
        //$this->excel->getActiveSheet()->getStyle("E$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        // $this->excel->getActiveSheet()->getStyle("A$filaInicial:H$filaInicial")->getFont()->setBold(true);
        // $this->excel->getActiveSheet()->getStyle("A$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(80);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(60);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);



        $hoy = str_replace("/", "_", $hoy);
        $filename = "Stocks fecha caducidad $hoy.xls";
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        //$objWriter->save('php://output');
        $directorio = "info_stocks/" . $filename;
        // mensaje($directorio);
        $objWriter->save($directorio);
        return $directorio;
    }

    function getStocksResumenes() {
        $sql = "SELECT g.nombre_grupo as grupo,"
                . " f.nombre_familia as familia,"
                . " p.tipo_unidad as unidad, "
                . " sum(st.cantidad) as cantidad,"
                . " sum(st.cantidad*p.precio_compra/p.unidades_precio) as importe_anterior, "
                . " sum(p.valoracion) as importe "
                . " FROM pe_stocks_totales st "
                . " LEFT JOIN pe_productos p ON p.id=st.codigo_producto "
                . " LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo "
                . " LEFT JOIN pe_familias f ON p.id_familia=f.id_familia "
                . " WHERE  "
                //   . "  st.cantidad>0 AND "
                . "  p.status_producto=1 "
                . " AND st.control_Stock='Sí' "
                . " GROUP BY g.nombre_grupo, f.nombre_familia, p.tipo_unidad "
                . " ORDER BY g.nombre_grupo,f.nombre_familia, p.tipo_unidad";

        $sql2 = "SELECT sum(st.cantidad*p.precio_compra/p.unidades_precio) as importe_anterior, "
                . "sum(p.valoracion) as importe "
                . " FROM pe_stocks_totales st "
                . " LEFT JOIN pe_productos p ON p.id=st.codigo_producto "
                . " LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo "
                . " LEFT JOIN pe_familias f ON p.id_familia=f.id_familia "
                . " WHERE  "
                //   . "  st.cantidad>0 AND "
                . "  p.status_producto=1 "
                . " AND st.control_Stock='Sí' "
                // . " GROUP BY g.nombre_grupo, f.nombre_familia, p.tipo_unidad "
                // . " ORDER BY g.nombre_grupo,f.nombre_familia, p.tipo_unidad "
                . " ";
        //log_message('INFO', '-------$sql = '.$sql);
        //log_message('INFO', '-------$sql2 = '.$sql2);
        $result = $this->db->query($sql)->result();
        $totalRow = $this->db->query($sql2)->row();
        return array('sql' => $sql, 'result' => $result, 'totalRow' => $totalRow);
    }

    function getStocksTotales($buscadores) {
        $buscadorCodigo13 = $buscadores[0];
        $buscadorCodigoBascula = $buscadores[1];
        $buscadorNombre = $buscadores[2];
        $buscadorCantidad = $buscadores[3];
        $buscadorFechaModificacion = $buscadores[4];
        $buscadorProveedor = $buscadores[5];

        $sql = "SELECT g.nombre_grupo as grupo,"
                . " f.nombre_familia as familia,"
                . " p.tipo_unidad as unidad, "
                . " sum(st.cantidad) as cantidad,"
                . " sum(st.cantidad*p.precio_compra/p.unidades_precio) as importe"
                . " FROM pe_stocks_totales st "
                . " LEFT JOIN pe_productos p ON p.id=st.codigo_producto "
                . " LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo "
                . " LEFT JOIN pe_familias f ON p.id_familia=f.id_familia "
                . " WHERE  "
                //   . "  st.cantidad>0 AND "
                . "  p.status_producto=1 "
                . " GROUP BY g.nombre_grupo, f.nombre_familia, p.tipo_unidad "
                . " ORDER BY g.nombre_grupo,f.nombre_familia, p.tipo_unidad";


        $sql = "SELECT g.nombre_grupo, p.id as id_pe_producto, "
                . " f.nombre_familia, "
                . " p.codigo_producto as codigo_producto, "
                . " p.id_producto as codigoBascula, "
                . " p.nombre as nombre, "
                . " pr.nombre as nombreProveedor, "
                . " st.cantidad as cantidad, "
                . " st.fecha_modificacion_stock as fecha_modificacion, "
                . " st.valoracion as valoracion,"
                . " st.cantidad*p.precio_compra/p.unidades_precio as importe,"
                . " st.control_Stock as controlStock  "
                . " FROM pe_stocks_totales st"
                . " LEFT JOIN pe_productos p ON p.id=st.codigo_producto "
                . " LEFT JOIN pe_proveedores_acreedores pr ON pr.id_proveedor_acreedor=p.id_proveedor_web "
                . " LEFT JOIN pe_familias f ON p.id_familia=f.id_familia "
                . " LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo "
                . " WHERE p.codigo_producto LIKE '%$buscadorCodigo13%' "
                . " AND p.nombre LIKE '%$buscadorNombre%' "
                . " AND pr.nombre LIKE '%$buscadorProveedor%' "
                . " AND p.id_producto LIKE '%$buscadorCodigoBascula%' "
                . " AND st.cantidad LIKE '%$buscadorCantidad%' "
                . " AND st.fecha_modificacion_stock LIKE '%$buscadorFechaModificacion%' "
                . " AND p.status_producto=1 "
                . " AND st.control_Stock='Sí' "
                . " ORDER BY p.codigo_producto";
        //log_message('INFO',$sql);
        //return $sql;
        $result = $this->db->query($sql)->result();
        $id_pe_producto = array();
        $codigos = array();
        $nombres = array();
        $cantidades = array();
        $fechas = array();
        $familias = array();
        $grupos = array();
        $nombresProveedores = array();
        $codigosBascula = array();
        $valoracion = array();
        $importes = array();
        $controlStock = array();
        foreach ($result as $k => $v) {
            $codigos[] = $v->codigo_producto;
            $nombres[] = $v->nombre;
            $cantidades[] = $v->cantidad;
            $fechas[] = $v->fecha_modificacion;
            $familias[] = $v->nombre_familia;
            $grupos[] = $v->nombre_grupo;
            $nombresProveedores[] = $v->nombreProveedor;
            $codigosBascula[] = $v->codigoBascula;
            $valoracion[] = $v->valoracion;
            $importes[] = $v->importe;
            $id_pe_producto[] = $v->id_pe_producto;
            $controlStock[] = $v->controlStock;
        }
        return array('controlStock' => $controlStock, 'id_pe_producto' => $id_pe_producto, 'importes' => $importes, 'valoracion' => $valoracion, 'codigosBascula' => $codigosBascula, 'nombresProveedores' => $nombresProveedores, 'familias' => $familias, 'grupos' => $grupos, 'codigos' => $codigos, 'nombres' => $nombres, 'cantidades' => $cantidades, 'fechas' => $fechas);
    }

    function getStocks($buscadores) {
        $buscadorCodigo13 = $buscadores[0];
        $buscadorCodigoBascula = $buscadores[1];
        $buscadorNombre = $buscadores[2];
        $buscadorCantidad = $buscadores[3];
        $buscadorFechaCaducidad = $buscadores[4];
        $buscadorProveedor = $buscadores[5];




        $sql = "SELECT g.nombre_grupo, "
                . " f.nombre_familia, "
                . " p.codigo_producto as codigo_producto, "
                . " p.id_producto as codigoBascula, "
                . " p.nombre as nombre, "
                . " pr.nombre as nombreProveedor, "
                . " st.cantidad as cantidad, "
                . " st.fecha_caducidad_stock as fecha_caducidad,"
                . " st.control_stock as controlStock"
                . " FROM pe_stocks st"
                . " LEFT JOIN pe_productos p ON p.id=st.codigo_producto "
                . " LEFT JOIN pe_proveedores_acreedores pr ON pr.id_proveedor_acreedor=p.id_proveedor_web "
                . " LEFT JOIN pe_familias f ON p.id_familia=f.id_familia "
                . " LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo "
                . " WHERE p.codigo_producto LIKE '%$buscadorCodigo13%' "
                . " AND p.nombre LIKE '%$buscadorNombre%' "
                . " AND pr.nombre LIKE '%$buscadorProveedor%' "
                . " AND p.id_producto LIKE '%$buscadorCodigoBascula%' "
                . " AND st.cantidad LIKE '%$buscadorCantidad%' "
                . " AND st.fecha_caducidad_stock LIKE '%$buscadorFechaCaducidad%' "
                . " AND p.status_producto=1 "
                . " ORDER BY p.codigo_producto";
        //return $sql;
        $result = $this->db->query($sql)->result();

        $codigos = array();
        $nombres = array();
        $cantidades = array();
        $fechas = array();
        $familias = array();
        $grupos = array();
        $nombresProveedores = array();
        $codigosBascula = array();
        $controlStock = array();
        foreach ($result as $k => $v) {
            $codigos[] = $v->codigo_producto;
            $nombres[] = $v->nombre;
            $cantidades[] = $v->cantidad;
            $fechas[] = $v->fecha_caducidad;
            $familias[] = $v->nombre_familia;
            $grupos[] = $v->nombre_grupo;
            $nombresProveedores[] = $v->nombreProveedor;
            $codigosBascula[] = $v->codigoBascula;
            $controlStock[] = $v->controlStock;
        }
        return array('controlStock' => $controlStock, 'codigosBascula' => $codigosBascula, 'nombresProveedores' => $nombresProveedores, 'familias' => $familias, 'grupos' => $grupos, 'codigos' => $codigos, 'nombres' => $nombres, 'cantidades' => $cantidades, 'fechas' => $fechas);
    }

    function getFormulas($filtro = "") {
        $palabras = explode(" ", trim($filtro));
        $like = "";
        $resultado = array();
        $resultado2 = array();
        foreach ($palabras as $k => $v) {
            $resultado[] = "concat(f.descripcion,p.codigo_producto) LIKE '%$v%'";
        }
        $like = implode(' and ', $resultado);

        $formulas = array();
        $sql = "SELECT f.id as id, f.descripcion as descripcion, f.producto as id_num, f.cantidad, p.codigo_producto as producto 
               FROM pe_formulas f 
               LEFT JOIN pe_productos p ON f.producto=p.id
               WHERE $like ORDER BY descripcion";
        foreach ($this->db->query($sql)->result() as $k => $v) {
            $formula = array(
                'id' => $v->id,
                'descripcion' => $v->descripcion,
                'producto' => $v->producto,
                'cantidad' => $v->cantidad,
            );
            $formulas[] = $formula;
        }
        //preparacion para opciones 
        $optionsFormulas = array();
        $optionsFormulas[0] = "Seleccionar fórmula";
        foreach ($formulas as $k => $v) {
            $id = $v['id'];
            $description = $v['descripcion'];
            $productoFinal = $v['producto'];
            $optionsFormulas[$id] = "$description ($productoFinal)";
        }
        return array('formulas' => $formulas, 'optionsFormulas' => $optionsFormulas, 'sql' => $sql);
    }

    function getNumProductosStoksCeroOMenor() {
        /*
        $sql = "SELECT  pr.id_producto as id_producto  FROM pe_productos pr"
                . " LEFT JOIN pe_stocks_totales st ON st.id_pe_producto=pr.id"
                . " WHERE pr.status_producto='1' AND st.cantidad=0 AND pr.control_stock='Sí'"
                . " AND LCASE(pr.nombre) NOT LIKE '%compra%' "
                . " AND LCASE(pr.nombre) NOT LIKE '%bandeja%' "
                . " AND LCASE(pr.nombre) NOT LIKE '%plato%' "
                . " GROUP BY  pr.id_producto" ;
        */  
        $sql = "SELECT  pr.id as id,pr.id_producto as id_producto,st.cantidad as cantidad FROM pe_productos pr 
                LEFT JOIN pe_stocks_totales st ON st.id_pe_producto=pr.id
                WHERE pr.status_producto='1' AND pr.control_stock='Sí'
                AND pr.id_producto!=0
                AND LCASE(pr.nombre) NOT LIKE '%compra%'
                AND LCASE(pr.nombre) NOT LIKE '%bandeja%' 
                AND LCASE(pr.nombre) NOT LIKE '%plato%'
               ";       
        $result = $this->db->query($sql)->result();
        $cantidades=array();
        foreach($result as $k=>$v){
            if(isset($cantidades[$v->id_producto])) $cantidades[$v->id_producto]+=$v->cantidad;
            else $cantidades[$v->id_producto]=$v->cantidad;
        }
        $numCero=0;
        $numMenorCero=0;
        foreach($cantidades as $k=>$v){
            // mensaje('$k '.$k);
            // mensaje('$v '.$v);
            if($v==0) $numCero++;
            if($v<0) $numMenorCero++;
            // mensaje('$numCero '.$numCero);
            // mensaje('$numMenorCero '.$numMenorCero);
        }
        return array('numCero' => $numCero, 'numMenorCero' => $numMenorCero);
    }
    
    function getProductosVenta($filtro = " ", $id = "", $und = false) {
        $this->load->model('productos_');
        $palabras = explode(" ", trim($filtro));
        $like = "";
        $resultado = array();
        $resultado2 = array();
        foreach ($palabras as $k => $v) {
            $resultado[] = "concat(id_producto, nombre_generico) LIKE '%$v%'";
        }
        $like = implode(' AND ', $resultado);


        $grupo = " ";
        if ($id == 'embalaje')
            $grupo = " AND g.nombre_grupo='Embalajes' ";
        if ($id == 'pack')
            $grupo = " AND g.nombre_grupo='Packs productos' ";
        if ($id == 'producto')
            $grupo = " ";

        $productos = array();
        $sql = "SELECT p.id,codigo_producto,nombre, nombre_generico,tipo_unidad FROM pe_productos p"
                . " LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo "
                . " WHERE status_producto=1 AND id_producto!='0' $grupo AND $like ORDER BY codigo_producto,nombre";
        $sql = "SELECT id_producto FROM pe_productos  WHERE status_producto=1 and id_producto!=0 GROUP BY id_producto";
        $result = $this->db->query($sql)->result();
        $productosArray['0'] = 'Seleccionar un producto';
        foreach ($result as $k => $v) {
            $sql="SELECT id,id_producto,nombre_generico FROM pe_productos WHERE id_producto=$v->id_producto AND status_producto=1 AND $like LIMIT 1";
            $row = $this->db->query($sql)->row();
            $productosArray[$row->id] = $v->id_producto . ' - ' . $row->nombre_generico ;
        }

        // $productosArray['0'] = 'Seleccionar un producto';
        // foreach ($result as $k => $v) {
        //     $esCorrecto = true;
        //     if ($und)
        //         if ($this->productos_->getUnidad($v->id) != 'Und')
        //             $esCorrecto = false;
        //     $v->nombre=!$v->nombre_generico?$v->nombre:$v->nombre_generico;
        //     if ($esCorrecto) {
        //         $producto = array(
        //             'id' => $v->id,
        //             'codigo_producto' => $v->codigo_producto,
        //             'nombre' => $v->nombre,
        //             'tipo_unidad' => $v->tipo_unidad,
        //         );
        //         $productosArray[$v->id] = $v->codigo_producto . ' - ' . $v->nombre ;
        //         $productos[] = $producto;
        //     }
        // }
        // switch ($id) {
        //     case "": {
        //             $texto = 'Seleccionar';
        //             break;
        //         }
        //     case "productoFinal": {
        //             $texto = 'Seleccionar producto final';
        //             break;
        //         }
        //     case "productoFinalEdit": {
        //             $texto = 'Seleccionar producto final';
        //             break;
        //         }
        //     case "componente": {
        //             $texto = 'Seleccionar componente';
        //             break;
        //         }
        //     case "componenteEdit": {
        //             $texto = 'Seleccionar componente';
        //             break;
        //         }
        //     case "producto": {
        //             $texto = 'Seleccionar producto';
        //             break;
        //         }
        //     case "pack": {
        //             $texto = 'Seleccionar pack';
        //             break;
        //         }
        //     default : {
        //             $texto = 'Seleccionar';
        //             break;
        //         }
        // }
        // $optionsProductos[0] = $texto;

        // foreach ($productos as $k => $v) {
        //     $id = $v['id'];
        //     $nombre = $v['nombre'];
        //     $codigo_productoFinal = $v['codigo_producto'];
        //     $optionsProductos[] = "$nombre ($codigo_productoFinal)";
        // }

        return array('productosArray' => $productosArray, 'productos' => $productos, 'sql' => $sql);
    }
    function getProductos($filtro = " ", $id = "", $und = false) {
        $this->load->model('productos_');
        $palabras = explode(" ", trim($filtro));
        $like = "";
        $resultado = array();
        $resultado2 = array();
        foreach ($palabras as $k => $v) {
            $resultado[] = "concat(nombre,codigo_producto) LIKE '%$v%'";
        }
        $like = implode(' AND ', $resultado);
        $grupo = " ";
        if ($id == 'embalaje')
            $grupo = " AND g.nombre_grupo='Embalajes' ";
        if ($id == 'pack')
            $grupo = " AND g.nombre_grupo='Packs productos' ";
        if ($id == 'producto')
            $grupo = " ";

        $productos = array();
        $sql = "SELECT p.id,codigo_producto,nombre, nombre_generico,tipo_unidad FROM pe_productos p"
                . " LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo "
                . " WHERE status_producto=1  $grupo AND $like ORDER BY codigo_producto,nombre";
        $result = $this->db->query($sql)->result();
        $productosArray['0'] = 'Seleccionar un producto';
        foreach ($result as $k => $v) {
            $esCorrecto = true;
            if ($und)
                if ($this->productos_->getUnidad($v->id) != 'Und')
                    $esCorrecto = false;
            $v->nombre=!$v->nombre_generico?$v->nombre:$v->nombre_generico;
            if ($esCorrecto) {
                $producto = array(
                    'id' => $v->id,
                    'codigo_producto' => $v->codigo_producto,
                    'nombre' => $v->nombre,
                    'tipo_unidad' => $v->tipo_unidad,
                );
                $productosArray[$v->id] = $v->codigo_producto . ' - ' . $v->nombre ;
                $productos[] = $producto;
            }
        }
        switch ($id) {
            case "": {
                    $texto = 'Seleccionar';
                    break;
                }
            case "productoFinal": {
                    $texto = 'Seleccionar producto final';
                    break;
                }
            case "productoFinalEdit": {
                    $texto = 'Seleccionar producto final';
                    break;
                }
            case "componente": {
                    $texto = 'Seleccionar componente';
                    break;
                }
            case "componenteEdit": {
                    $texto = 'Seleccionar componente';
                    break;
                }
            case "producto": {
                    $texto = 'Seleccionar producto';
                    break;
                }
            case "pack": {
                    $texto = 'Seleccionar pack';
                    break;
                }
            default : {
                    $texto = 'Seleccionar';
                    break;
                }
        }
        $optionsProductos[0] = $texto;

        foreach ($productos as $k => $v) {
            $id = $v['id'];
            $nombre = $v['nombre'];
            $codigo_productoFinal = $v['codigo_producto'];
            $optionsProductos[] = "$nombre ($codigo_productoFinal)";
        }

        return array('productosArray' => $productosArray, 'productos' => $productos, 'optionsProductos' => $optionsProductos, 'sql' => $sql);
    }

    function getProductosCodigoPre($prefijo){
        $sql="SELECT * FROM pe_productos WHERE SUBSTR(codigo_producto,1,8)='$prefijo'  ORDER BY peso_real ";
        // mensaje('getProductosCodigoPre '.$sql);
        $result=$this->db->query($sql)->result();
        $productos=array();
        foreach ($result as $k => $v) {
            // if($v->id_producto){
                $productos[]=array('codigoProducto'=>$v->codigo_producto,
                                   'codigoBoka'=>$v->id_producto,
                                   'iva'=>$v->iva,
                                   'tipoUnidad'=>$v->tipo_unidad,
                                   'pesoReal'=>number_format($v->peso_real/1000,3),
                                   'anada'=>$v->anada,
                                   'nombre'=>$v->nombre,
                                   'nombreGenerico'=>$v->nombre_generico,
                                   'precioCompra'=>number_format($v->precio_compra/1000,3),
                                   'tarifaVenta'=>number_format($v->tarifa_venta/1000,3),
                                   'statusProducto'=>$v->status_producto
                                );
            // }
        }
        return $productos;
    }

    function getProductosCodigoBod($prefijo){
        $sql="SELECT * FROM pe_productos WHERE SUBSTR(codigo_producto,1,10)='$prefijo'  ORDER BY anada DESC ";
        // mensaje('getProductosCodigoPre '.$sql);
        $result=$this->db->query($sql)->result();
        $productos=array();
        foreach ($result as $k => $v) {
            // if($v->id_producto){
                $productos[]=array('codigoProducto'=>$v->codigo_producto,
                                   'codigoBoka'=>$v->id_producto,
                                   'iva'=>$v->iva,
                                   'tipoUnidad'=>$v->tipo_unidad,
                                   'pesoReal'=>number_format($v->peso_real/1000,3),
                                   'anada'=>$v->anada,
                                   'nombre'=>$v->nombre,
                                   'nombreGenerico'=>$v->nombre_generico,
                                   'precioCompra'=>number_format($v->precio_compra/1000,3),
                                   'tarifaVenta'=>number_format($v->tarifa_venta/1000,3),
                                   'statusProducto'=>$v->status_producto
                                );
            // }
        }
        return $productos;
    }

    function getProductosCompra($filtro = " ") {
        $this->load->model('productos_');
        $palabras = explode(" ", trim($filtro));
        $like = "";
        $resultado = array();
        foreach ($palabras as $k => $v) {
            $resultado[] = "concat(nombre,codigo_producto) LIKE '%$v%'";
        }
        $like = implode(' AND ', $resultado);
       
        $productos = array();
        $sql = "SELECT p.id,codigo_producto,nombre, nombre_generico,tipo_unidad FROM pe_productos p"
                . " WHERE status_producto=1 AND id_producto=0 AND tipo_unidad='Kg' AND $like ORDER BY nombre";
        $result = $this->db->query($sql)->result();
        $productosArray['0'] = 'Seleccionar producto compra';
        // return $productosArray;

        foreach ($result as $k => $v) {             
                $producto = array(
                    'id' => $v->id,
                    'codigo_producto' => $v->codigo_producto,
                    'nombre' => $v->nombre,
                    'tipo_unidad' => $v->tipo_unidad,
                );
                $productosArray[$v->codigo_producto] = $v->nombre . ' (' . $v->codigo_producto . ')';
                $productos[] = $producto;
            }
        return $productosArray;
        
        $optionsProductos[0] = "Seleccionar producto compra";

        foreach ($productos as $k => $v) {
            $id = $v['id'];
            $nombre = $v['nombre'];
            $codigo_productoFinal = $v['codigo_producto'];
            $optionsProductos[] = "$nombre ($codigo_productoFinal)";
        }

        return array('productosArray' => $productosArray, 'productos' => $productos, 'optionsProductos' => $optionsProductos, 'sql' => $sql);
    }

    function getProductosBodega($filtro = " ") {
        $this->load->model('productos_');
        $palabras = explode(" ", trim($filtro));
        $like = "";
        $resultado = array();
        foreach ($palabras as $k => $v) {
            $resultado[] = "concat(nombre,codigo_producto) LIKE '%$v%'";
        }
        $like = implode(' AND ', $resultado);
       
        // mensaje("SELECT id_producto FROM pe_productos WHERE id_grupo=8 AND status_producto=1 AND tipo_unidad='Und' AND $like GROUP BY id_producto ORDER BY nombre");
        $result2=$this->db->query("SELECT id_producto FROM pe_productos WHERE id_grupo=8 AND status_producto=1 AND tipo_unidad='Und' AND $like GROUP BY id_producto")->result();
        foreach($result2 as $k=>$v){
            // mensaje("SELECT codigo_producto,nombre FROM pe_productos WHERE id_producto='".$v->id_producto."' ORDER BY anada DESC LIMIT 1");
            $row=$this->db->query("SELECT codigo_producto,nombre FROM pe_productos WHERE id_producto='".$v->id_producto."' ORDER BY anada DESC LIMIT 1" )->row();
            // mensaje($row->codigo_producto.' '.$row->nombre.' ');
            // $productosArray[$row->codigo_producto]=$row->nombre.' ('.$row->codigo_producto.')';
            $productosBodega[$row->codigo_producto]=$row->nombre.' ('.$row->codigo_producto.')';
        }
        asort($productosBodega);
        array_unshift($productosBodega,"Seleccionar producto bodega" );
        return $productosBodega;
    }

    function getTransformaciones($filtro = " ", $id = "") {
        $palabras = explode(" ", trim($filtro));
        $like = "";
        $resultado = array();
        $resultado2 = array();
        foreach ($palabras as $k => $v) {
            $resultado[] = "concat(nombre,concepto) LIKE '%$v%'";
        }
        $like = implode(' AND ', $resultado);
        $productos = array();
        if (trim($filtro) == "")
            $limit = "";
        else
            $limit = "LIMIT 10";

        //$sql = "SELECT id,concepto,nombre FROM pe_transformaciones WHERE patron='1' AND $like GROUP BY nombre,concepto ORDER BY fecha DESC $limit";
        $sql = "SELECT id,concepto,nombre FROM pe_transformaciones WHERE patron='1' AND $like  ORDER BY fecha DESC $limit";
        // mensaje($sql);
        $result = $this->db->query($sql)->result();
        $transformaciones = array();
        foreach ($result as $k => $v) {
            $transformacion = array(
                'id' => $v->id,
                'concepto' => $v->concepto,
                'nombre' => $v->nombre,
            );
            $transformaciones[] = $transformacion;
        }
        switch ($id) {
            case "": {
                    $texto = 'Seleccionar';
                    break;
                }
            case "productoFinal": {
                    $texto = 'Seleccionar producto final';
                    break;
                }
            case "productoFinalEdit": {
                    $texto = 'Seleccionar producto final';
                    break;
                }
            case "componente": {
                    $texto = 'Seleccionar componente';
                    break;
                }
            case "componenteEdit": {
                    $texto = 'Seleccionar componente';
                    break;
                }
            case "producto": {
                    $texto = 'Seleccionar producto';
                    break;
                }
            case "transformacion": {
                    $texto = 'Seleccionar transformacion';
                    break;
                }
            default : {
                    $texto = 'Seleccionar';
                    break;
                }
        }
        $optionsTransformaciones[0] = $texto;

        foreach ($transformaciones as $k => $v) {
            $id = $v['id'];
            $nombre = $v['nombre'];
            $concepto = $v['concepto'];
            $optionsTransformaciones[$id] = "$nombre ($concepto)";
        }
        return array('transformaciones' => $transformaciones, 'optionsTransformaciones' => $optionsTransformaciones, 'sql' => $sql);
    }

    function getFormula($formula) {
        $sql = "SELECT p.codigo_producto as productoFinal, f.id as formulaId,f.descripcion as formulaDescripcion,f.cantidad as formulaCantidad 
                   FROM pe_formulas f
                   LEFT JOIN pe_productos p ON f.producto=p.id 
                   WHERE f.id='$formula'";
        $result = $this->db->query($sql)->row();
        $sql = "SELECT l.cantidad as componenteCantidad,p.codigo_producto as componentesCodigo_producto, p.nombre as componenteNombre,p.id as componenteId FROM pe_formulas_lineas l
                   LEFT JOIN pe_productos p ON l.id_producto=p.id
                   WHERE l.id_formula='$formula'";
        $componentesId = array();
        $componentesNombre = array();
        $componentesCantidad = array();
        $componentesCodigo_producto = array();
        $lineas = array();
        foreach ($this->db->query($sql)->result() as $k => $v) {
            $lineas[] = array('componenteId' => $v->componenteId,
                'componentesNombre' => $v->componenteNombre,
                'componentesCantidad' => $v->componenteCantidad,
                'componentesCodigo_producto' => $v->componentesCodigo_producto,
            );
            $componentesId[$k] = $v->componenteId;
            $componentesNombre[$k] = $v->componenteNombre;
            $componentesCantidad[$k] = $v->componenteCantidad;
            $componentesCodigo_producto[$k] = $v->componentesCodigo_producto;
        }
        return array('descripcion' => $result->formulaDescripcion,
            'cantidad' => $result->formulaCantidad,
            'productoFinal' => $result->productoFinal,
            'id' => $result->formulaId,
            'componentesId' => $componentesId,
            'componentesNombre' => $componentesNombre,
            'componentesCantidad' => $componentesCantidad,
            'componentesCodigo_producto' => $componentesCodigo_producto,
            'lineas' => $lineas,
        );
    }

    function grabarFormula($descripcion, $productoFinal, $cantidadFormula, $lineas = array()) {
        $cantidadFormula *= 1000;
        $sql = "INSERT INTO pe_formulas SET producto='$productoFinal', descripcion='$descripcion', cantidad='$cantidadFormula' ";
        $this->db->query($sql);
        $sql = "SELECT id FROM pe_formulas ORDER BY id DESC limit 1";
        $id_formula = $this->db->query($sql)->row()->id;
        if ($lineas) {
            foreach ($lineas as $k => $v) {
                $id_producto = $v['componente'];
                $cantidad = $v['cantidadComponente'] *= 1000;
                $sql = "INSERT INTO pe_formulas_lineas SET
                             id_formula='$id_formula', 
                             id_producto='$id_producto',  
                             cantidad='$cantidad' ";
                $this->db->query($sql);
            }
        }
        return $id_formula;
    }

    function grabarModificacionFormula($formulaId, $descripcion, $productoFinal, $cantidadFormula, $lineas = array()) {
        $cantidadFormula *= 1000;
        $sql = "UPDATE pe_formulas SET producto='$productoFinal', descripcion='$descripcion', cantidad='$cantidadFormula' WHERE id='$formulaId'";
        $this->db->query($sql);
        $sql = "DELETE FROM pe_formulas_lineas WHERE id_formula='$formulaId'";
        $this->db->query($sql);
        if ($lineas) {
            foreach ($lineas as $k => $v) {
                $id_producto = $v['componente'];
                $cantidad = $v['cantidadComponente'] *= 1000;
                $sql = "INSERT INTO pe_formulas_lineas SET
                             id_formula='$formulaId', 
                             id_producto='$id_producto',  
                             cantidad='$cantidad' ";
                $this->db->query($sql);
            }
        }
        return $formulaId;
    }

    function registrarInventario($id_pe_producto, $cantidades, $fechasCaducidades) {
        $hoy = date("Y-m-d");
        $sql = "DELETE FROM pe_stocks_totales WHERE id_pe_producto='$id_pe_producto'";
        $this->db->query($sql);
        $sql = "DELETE FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'";
        $this->db->query($sql);
        $cantidadTotal = 0;
        $sql = "SELECT id_proveedor_web,status_producto FROM pe_productos WHERE id='$id_pe_producto'";
        $id_proveedor_acreedor = $this->db->query($sql)->row()->id_proveedor_web;
        $activo = $this->db->query($sql)->row()->status_producto;
        foreach ($cantidades as $k => $v) {
            $v *= 1000;
            if ($v != 0) {
                $cantidadTotal += $v;
                $fechaCaducidad = $fechasCaducidades[$k];
                $sql = "INSERT INTO pe_stocks SET activo='$activo',codigo_bascula='$id_pe_producto', proveedor='$id_proveedor_acreedor',cantidad='$v',id_pe_producto='$id_pe_producto',codigo_producto='$id_pe_producto',fecha_entrada='$hoy',fecha_modificacion_stock='$hoy',fecha_caducidad_stock='$fechaCaducidad' ";
                $this->db->query($sql);
            }
        }

        $this->armonizarStocks($id_pe_producto);
        

        /*
          $sql="INSERT INTO pe_stocks_totales SET activo='$activo',codigo_bascula='$id_pe_producto',proveedor='$id_proveedor_acreedor', cantidad='$cantidadTotal',id_pe_producto='$id_pe_producto',nombre='$id_pe_producto',codigo_producto='$id_pe_producto',fecha_modificacion_stock='$hoy' ";
          $this->db->query($sql);

          //se eliminan todas las anotaciones de stocks con cantidad=0 del producto en cuestion
          $sql="DELETE FROM pe_stocks WHERE cantidad=0 AND id_pe_producto='$id_pe_producto'";
          $this->db->query($sql);
          //agrupar cantidades con igual fecha de caducidad
          $sql="SELECT sum(cantidad) as cantidad,id_pe_producto,fecha_caducidad_stock,proveedor,activo FROM pe_stocks WHERE id_pe_producto='$id_pe_producto' GROUP BY fecha_caducidad_stock,proveedor";
          $result=$this->db->query($sql)->result();
          $this->db->query("DELETE FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'");
          foreach($result as $k=>$v){
          $this->db->query("INSERT INTO pe_stocks
          SET cantidad='".$v->cantidad."',
          activo='".$v->activo."',
          codigo_producto='".$v->id_pe_producto."',
          codigo_bascula='".$v->id_pe_producto."',
          id_pe_producto='".$v->id_pe_producto."',
          fecha_caducidad_stock='".$v->fecha_caducidad_stock."',
          fecha_modificacion_stock='".date('Y-m-d')."',
          fecha_entrada='".date('Y-m-d')."',
          proveedor='".$v->proveedor."'");
          }
         */
    }

    function readInventario($id_pe_producto) {
        $sql = "SELECT sum(cantidad) as cantidad,fecha_caducidad_stock FROM pe_stocks "
                . "WHERE id_pe_producto='$id_pe_producto' AND cantidad!='0' "
                . " GROUP BY fecha_caducidad_stock";
        $result = $this->db->query($sql)->result();
        $sql = "SELECT precio_ultimo_peso,precio_ultimo_unidad FROM pe_productos WHERE id='$id_pe_producto'";
        $row = $this->db->query($sql)->row();
        $this->load->model('productos_');
        $tipoUnidad = $this->productos_->getUnidad($id_pe_producto);
        return array('result' => $result, 'tipoUnidad' => $tipoUnidad);
    }

    function siguienteTransformacion() {
        $sql = " SELECT MAX(id_transformacion) as id_transformacion FROM pe_transformaciones";
        $siguiente = $this->db->query($sql)->row()->id_transformacion + 1;
        $this->db->query("INSERT INTO pe_transformaciones SET id_transformacion='$siguiente'");
        return $siguiente;
    }

    function registroStocksHistorico($fecha_registro, $id_pe_producto, $cantidad, $fecha_caducidad = '0000-00-00') {
        $sql = "";
    }

}
