<?php
class Upload_ extends CI_Model
{

    var $db2 = "";
    var $fecha = "";
    var $codigosId_productos = array();
    var $tarifasVentaPrestaShop = array();
    var $comparar = array();
    var $cantidaLinea = array();

    public function __construct()
    {
        $this->load->database();
        $this->load->model('productos_');
        $this->db2 = $this->load->database('pernil181bcn', true);
        ini_set('memory_limit', '512M');
    }

    public function getMessageErrorIvas()
    {
        //revisar si pedidos con ivas erroneos se han registrado
        $message = "";
        if ($this->db->query("SELECT num_order FROM pe_orders_prestashop_revisar_ivas")->num_rows() > 0) {

            $result = $this->db->query("SELECT num_order FROM pe_orders_prestashop_revisar_ivas GROUP BY num_order")->result();

            $message = "";
            $message .= "<h3>Informe PrestaShop</h3>";
            $message .= "<h4>Revisar los siguientes pedidos. Se han encontrado discrepancias en los IVAS aplicados</h4>";
            foreach ($result as $k => $v) {
                $message .= "<h3>" . $v->num_order . '</h3><br>';
            }
            $message .= "<h4>NO se han incorporado NINGUNO de los pedidos de archivo subido.<br>Corregir manualmente los datos de los pedidos señalados y volver a subir el archivo.</h4>";
            $message .= "<br><br>Fin del informe";
            return $message;
        }
        return $message;
    }

    public function setBoka($valores)
    {

        $texto = implode("', '", $valores);
        $sql = "INSERT INTO `pe_boka`(`BONU`, `BONU2`, `STYP`, `ABNU`, `WANU`, `BEN1`, `BEN2`, `SNR1`, `GPTY`, `PNAB`, `WGNU`, `BT10`, `BT12`, `BT20`, `POS1`, `POS4`, `GEW1`, `BT40`, `MWNU`, `MWTY`, `PRUD`, `PAR1`, `PAR2`, `PAR3`, `PAR4`, `PAR5`, `STST`, `PAKT`, `POS2`, `MWUD`, `BT13`, `RANU`, `RATY`, `BT30`, `BT11`, `POS3`, `GEW2`, `SNR2`, `SNR3`, `VART`, `BART`, `KONU`, `RASA`, `ZAPR`, `ZAWI`, `MWSA`, `ZEIS`, `ZEIE`, `ZEIB`, `TEXT`) VALUES "
            . "('" . $texto . "')";
        $r = $this->db->query($sql);
        // mensaje($sql);
        $this->db2->query($sql);

        if ($valores[2] == 1) {
            $this->fecha = substr($valores[46], 0, 10);
        }
        //if($valores[2]==2){
        // $this->actualizar_stocks($valores[2],$valores[7],$valores[16],$valores[21],$valores[14],$this->fecha);
        //}
        return $this->db->affected_rows();
    }

    //pone en todos los registros de un mismo ticket la fecha ZEIS del STYP=1
    public function ponerFechas()
    {
        $sql = "SELECT id,ZEIS FROM pe_boka order by id";
        $query = $this->db->query($sql);
        $fecha = '1970-01-01 00:00:00';
        foreach ($query->result() as $k => $v) {
            if ($v->ZEIS === '1970-01-01 00:00:00') {
                $this->db->query("UPDATE pe_boka SET ZEIS='$fecha' WHERE id='$v->id'");
                $this->db2->query("UPDATE pe_boka SET ZEIS='$fecha' WHERE id='$v->id'");
            } else {
                $fecha = $v->ZEIS;
            }
        }
        return; //'Terminado el poner fechas en pe_boka';
    }

    public function ponerFechasNuevos($ultimoIDAnterior, $ultimoIDActual)
    {
        $sql = "SELECT id,ZEIS FROM pe_boka WHERE id>$ultimoIDAnterior AND id<=$ultimoIDActual ORDER BY id";
        $result = $this->db->query($sql)->result();
        $fecha = '1970-01-01 00:00:00';
        foreach ($result as $k => $v) {
            if ($v->ZEIS === '1970-01-01 00:00:00') {
                // mensaje("UPDATE pe_boka SET ZEIS='$fecha' WHERE id='$v->id'");
                $this->db->query("UPDATE pe_boka SET ZEIS='$fecha' WHERE id='$v->id'");
                $this->db2->query("UPDATE pe_boka SET ZEIS='$fecha' WHERE id='$v->id'");
            } else {
                $fecha = $v->ZEIS;
            }
        }
        $fecha = '1970-01-01 00:00:00';
        $this->db->query("UPDATE `pe_boka` SET `ZEIB`='$fecha' WHERE id>$ultimoIDAnterior AND id<=$ultimoIDActual");
        $this->db2->query("UPDATE `pe_boka` SET `ZEIB`='$fecha' WHERE id>$ultimoIDAnterior AND id<=$ultimoIDActual");

        return; //'Terminado el poner fechas en pe_boka';
    }

    public function getFechasTickets($ultimoIDAnterior, $ultimoIDActual)
    {
        $sql = "SELECT id,zeis FROM pe_boka WHERE id>$ultimoIDAnterior ORDER BY id LIMIT 1";
        $fechaInicio = $this->db->query($sql)->row()->zeis;
        $sql = "SELECT id,zeis FROM pe_boka WHERE id=$ultimoIDActual ORDER BY id LIMIT 1";
        $fechaFinal = $this->db->query($sql)->row()->zeis;
        return array('fechaInicio' => $fechaInicio, 'fechaFinal' => $fechaFinal);
    }

    public function reportTicketsPesoVariasUnidades($fechaInicio, $fechaFinal)
    {
        $sql = "SELECT b.bonu as bonu,b1.rasa as rasa, b.zeis as fecha,  b.gew1 as gew1,b.snr1 as snr1
                FROM pe_boka b
                LEFT JOIN pe_boka b1 ON b.bonu=b1.bonu and b1.STYP=1
                WHERE b.styp=2 AND b.zeis>='$fechaInicio' AND b.zeis<='$fechaFinal' AND b.gew1>0 ";
        $result = $this->db->query($sql)->result();
        $contador = 0;
        $report = '<table style="border-collapse: collapse;padding-left:5px;padding-right:5px;background-color:yellow;">';
        $report .= '<tr>';
        $report .= '<th style="border:1px solid #000000;">' . 'Ticket' . '</td>';
        $report .= '<th style="border:1px solid #000000;">' . 'Fecha' . '</td>';
        $report .= '<th style="border:1px solid #000000;text-align:left">' . 'Producto' . '</td>';
        $report .= '<th style="border:1px solid #000000;">' . 'Código Báscula' . '</td>';
        $report .= '<th style="border:1px solid #000000;">' . 'Peso' . '</td>';
        $report .= '<th style="border:1px solid #000000;">' . 'Código asignado' . '</td>';
        $report .= '<th style="border:1px solid #000000;">' . 'Peso código' . '</td>';
        $report .= '<th style="border:1px solid #000000;">' . '% $obre peso' . '</td>';
        $report .= '<th style="border:1px solid #000000;">' . 'BONU' . '</td>';
        $report .= '</tr>';
        $this->load->model('productos_');
        foreach ($result as $k => $v) {
            $id_producto = $v->snr1;
            $bonu = $v->bonu;
            $sql = "SELECT max(peso_real) as maxPeso FROM pe_productos WHERE id_producto='$id_producto' GROUP BY id_producto";
            $maxPeso = $this->db->query($sql)->row()->maxPeso;
            $sql = "SELECT codigo_producto,nombre FROM pe_productos WHERE id_producto='$id_producto' LIMIT 1";
            $codigo_producto = $this->db->query($sql)->row()->codigo_producto;
            if ($this->productos_->getUnidadCodigoProducto($codigo_producto) == "Kg") continue;
            $nombre = $this->db->query($sql)->row()->nombre;
            if ($v->gew1 > $maxPeso) {
                $sobrepeso = 0;
                if ($maxPeso != 0)
                    $sobrepeso = number_format(($v->gew1 - $maxPeso) / $maxPeso * 100, 2);
                if ($sobrepeso >= 10) {
                    $contador++;
                    $report .= '<tr>';
                    $report .= '<td style="border:1px solid #000000;">' . $v->rasa . '</td>';
                    $report .= '<td style="border:1px solid #000000;">' . $v->fecha . '</td>';
                    $report .= '<td style="border:1px solid #000000; text-align:left">' . $nombre . '</td>';
                    $report .= '<td style="border:1px solid #000000;">' . $id_producto . '</td>';
                    $report .= '<td style="border:1px solid #000000;">' . $v->gew1 . '</td>';
                    $report .= '<td style="border:1px solid #000000;">' . $codigo_producto . '</td>';
                    $report .= '<td style="border:1px solid #000000;">' . $maxPeso . '</td>';
                    $report .= '<td style="border:1px solid #000000;">' . $sobrepeso . '</td>';
                    $report .= '<td style="border:1px solid #000000;">' . $bonu . '</td>';
                    $report .= '</tr>';
                }
            }
        }
        $report .= '</table>';
        if ($contador) return $report;
        return "";
    }

   public function ponerDatosTablaTickets($ultimoIDAnterior, $ultimoIDActual)
    {
        $sql = "SELECT bonu, zeis,BT20,RASA,SNR2
            FROM pe_boka
            WHERE STYP=1 AND id>$ultimoIDAnterior AND id<=$ultimoIDActual ORDER BY id ";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $bonu = $v->bonu;
            $fecha = $v->zeis;
            $total = $v->BT20;
            $num_ticket = $v->RASA;
            $id_cliente = $v->SNR2;
            $sql = "INSERT INTO pe_tickets 
               SET bonu='$bonu',
                   fecha='$fecha',
                   total='$total',
                   num_ticket='$num_ticket',
                   id_cliente='$id_cliente'
                   ";
            $this->db->query($sql);
            $this->db2->query($sql);
            $sql = "SELECT PAR1 FROM pe_boka WHERE bonu='$bonu' AND zeis='$fecha' AND STYP=8";
            $result = $this->db->query($sql)->result();
            $id_forma_pago_ticket = 20;
            foreach ($result as $k => $v) {
                if ($v->PAR1 != '20') {
                    $id_forma_pago_ticket = $v->PAR1;
                }
            }

            $this->db->query("UPDATE pe_tickets SET id_forma_pago_ticket='$id_forma_pago_ticket' WHERE bonu='$bonu' AND fecha='$fecha'");
            $this->db2->query("UPDATE pe_tickets SET id_forma_pago_ticket='$id_forma_pago_ticket' WHERE bonu='$bonu' AND fecha='$fecha'");
        }
        return; //'Terminado el poner datos en pe_tickets';
    }

    public function productosVendidos($ultimoIDAnterior, $ultimoIDActual, $reportar = true)
    {
        $sql = "SELECT id,SNR1,POS1,GEW1,BONU,ZEIS
                  FROM pe_boka b
                  WHERE STYP=2 AND id>$ultimoIDAnterior AND id<=$ultimoIDActual";
        // echo $sql.'<br >';
        $result = $this->db->query($sql)->result();
        $this->load->model('productos_');
        $this->load->model('stocks_model');
        foreach ($result as $k => $v) {
            $idBoka = $v->id;
            $snr1 = $v->SNR1;
            $bonu = $v->BONU;
            $zeis = $v->ZEIS;
            $gew1 = $v->GEW1;
            $cantidad = $v->POS1;
            $asignacion = $this->productos_->asignarProducto($snr1, $gew1, $cantidad);
            $id_pe_producto = $asignacion['id'];
            $this->codigosId_productos[] = array($idBoka, $id_pe_producto);
            $cantidad = -$asignacion['cantidad'] * 1000;
            $idBoka = $v->id;

            //poner id_pe_producto en pe_boka
            $this->db->query("UPDATE pe_boka SET id_pe_producto='$id_pe_producto' WHERE id='$idBoka'");
            $this->db2->query("UPDATE pe_boka SET id_pe_producto='$id_pe_producto' WHERE id='$idBoka'");

            $this->db->query("UPDATE pe_boka SET id_pe_producto='$id_pe_producto' WHERE STYP='202' AND BONU='$bonu' AND SNR1='$snr1' AND ZEIS='$zeis'");
            $this->db2->query("UPDATE pe_boka SET id_pe_producto='$id_pe_producto' WHERE STYP='202' AND BONU='$bonu' AND SNR1='$snr1' AND ZEIS='$zeis'");


            $this->stocks_model->sumaCantidadStocks($id_pe_producto, $cantidad, $fecha_caducidad_stock = "1970-01-01");
            $tipoTienda = 1;  //tienda
            $this->stocks_model->sumaCantidadStocksEmbalajes($id_pe_producto, -$cantidad, $tipoTienda);

            //registra la venta con los precios, tarifa, embalajes del momento
            //$this->productos_->registrarVentaTienda($idBoka);
        }
        //poner num_cliente, id_familia, id_ grupo
        /*
            $sql="SELECT SNR2,ZEIS,RASA
                  FROM pe_boka b
                  WHERE STYP=1 AND id>$ultimoIDAnterior AND id<=$ultimoIDActual"; 
            // mensaje($sql);      
            $result=$this->db->query($sql)->result();      
            foreach($result as $k=>$v){
                $cliente=$v->SNR2;
                $fecha_venta=$v->ZEIS;
                $num_ticket=$v->RASA;
                while(strlen($cliente)<6){
                    $cliente="0".$cliente;
                }
                // mensaje("UPDATE pe_registro_ventas SET num_cliente='$cliente' WHERE tipo_tienda=1 AND num_ticket='$num_ticket' AND fecha_venta='$fecha_venta'");
                $this->db->query("UPDATE pe_registro_ventas SET num_cliente='$cliente' WHERE tipo_tienda=1 AND num_ticket='$num_ticket' AND fecha_venta='$fecha_venta'");
            }
            $sql="SELECT id_pe_producto
                  FROM pe_boka b
                  WHERE STYP=2 AND id>$ultimoIDAnterior AND id<=$ultimoIDActual GROUP BY id_pe_producto"; 
            // mensaje($sql);       
            $result=$this->db->query($sql)->result();      
            foreach($result as $k=>$v){
                $id_pe_producto=$v->id_pe_producto;
                $idGrupo=$this->productos_->getIdGrupo($id_pe_producto);
                $idFamilia=$this->productos_->getIdFamilia($id_pe_producto);
                // mensaje("UPDATE pe_registro_ventas SET grupo='$idGrupo', familia='$idFamilia' WHERE tipo_tienda=1 AND id_pe_producto='$id_pe_producto' AND grupo IS NULL AND familia IS NULL");
                $this->db->query("UPDATE pe_registro_ventas SET grupo='$idGrupo', familia='$idFamilia' WHERE tipo_tienda=1 AND id_pe_producto='$id_pe_producto' AND grupo IS NULL AND familia IS NULL");
            }
            */
    }

    public function informeBeneficiosVentas()
    {

        $salida = '<table style="border-collapse: collapse;padding-left:5px;padding-right:5px;">';
        $salida .= '<tr>';
        $salida .=  "<th style='text-align:left; padding-left:5px;padding-right:5px;'>Nombre</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Cód báscula</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Cód 13</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Part</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Precio Unidad</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Precio Peso</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Dto %</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>PVP Unidad</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>PVP Peso</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Peso BD</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>PVP Peso calculado</th>";
        $salida .=  "<th>-----</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Peso Boka</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Precio Boka</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Beneficio</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>Beneficio %</th>";
        $salida .=  '</tr>';
        $datosInforme = array();
        $datosInformeNum = array();
        $datosInformeNombre = array();
        $datosInformeId_producto = array();
        $datosInformeTarifa_venta_unidad = array();
        $datosInformeTarifa_venta_peso = array();
        $datosInformePeso_real = array();
        $datosInformePVPpesoCalculado = array();
        $datosInformeGEW1 = array();
        $datosInformeBT10 = array();

        foreach ($this->codigosId_productos as $k => $v) {
            $id = $v[0];
            $id_pe_producto = $v[1];
            $sql = "SELECT SNR1,POS1,GEW1,BT10,MWSA FROM pe_boka WHERE STYP=2 AND id='$id'";

            $datosBoka = $this->db->query($sql)->row();
            $SNR1 = $datosBoka->SNR1;
            $POS1 = $datosBoka->POS1;
            $GEW1 = $datosBoka->GEW1;
            $MWSA = $datosBoka->MWSA;

            if (!$GEW1) $GEW1 = "";

            $BT10 =  number_format($datosBoka->BT10 / 100, 2);
            $MWSA =  number_format($datosBoka->MWSA / 100, 2);

            $sql = "SELECT codigo_producto, id_producto,precio_ultimo_unidad,descuento_1_compra,precio_ultimo_peso,tarifa_venta_unidad, tarifa_venta_peso, nombre,peso_real
                      FROM pe_productos
                      WHERE id='$id_pe_producto'";

            $datosBDproductos = $this->db->query($sql)->row();
            $nombre = $datosBDproductos->nombre;
            $id_producto = $datosBDproductos->id_producto;
            $codigo_producto = $datosBDproductos->codigo_producto;

            $tarifa_venta_unidad = number_format($datosBDproductos->tarifa_venta_unidad / 1000, 2);
            //if($tarifa_venta_unidad=="0.00") $tarifa_venta_unidad="";

            $tarifa_venta_peso = number_format($datosBDproductos->tarifa_venta_peso / 1000, 2);
            //if($tarifa_venta_peso=="0.00") $tarifa_venta_peso="";

            $descuento_1_compra = number_format($datosBDproductos->precio_ultimo_peso / 1000, 2);
            //if($descuento_1_compra=="0.00") $descuento_1_compra="";

            $precio_ultimo_peso = number_format($datosBDproductos->descuento_1_compra / 1000, 2);
            //if($precio_ultimo_peso=="0.00") $precio_ultimo_peso="";

            $precio_ultimo_unidad = number_format($datosBDproductos->precio_ultimo_unidad / 1000, 2);
            //if($precio_ultimo_unidad=="0.00") $precio_ultimo_unidad="";



            $peso_real = $datosBDproductos->peso_real;
            if ($datosBDproductos->peso_real != 0) {
                $PVPpesoCalculado = $datosBDproductos->tarifa_venta_unidad * 100000 / $datosBDproductos->peso_real;
                $PVPpesoCalculado =  number_format($PVPpesoCalculado / 100000, 2);
                //if($PVPpesoCalculado=="0.00") $PVPpesoCalculado="";
            } else $PVPpesoCalculado = "";


            $precio_unidad = $precio_ultimo_unidad * (1 - $descuento_1_compra / 100);
            $precio_peso = $precio_ultimo_peso * (1 - $descuento_1_compra / 100);
            if ($GEW1 == "") {
                $beneficio_por_ciento = 0;
                if ($tarifa_venta_unidad != 0)
                    $beneficio_por_ciento = (100 * $tarifa_venta_unidad - $precio_unidad * (100 + $MWSA)) / $tarifa_venta_unidad;
                $beneficio_por_ciento = number_format($beneficio_por_ciento, 2);
            } else {
                if ($precio_unidad != 0) {
                    $PVP = $GEW1 * $BT10 / 1000;
                    $beneficio_por_ciento = (100 * $PVP - $precio_unidad * (100 + $MWSA)) / $PVP;
                    $beneficio_por_ciento = number_format($beneficio_por_ciento, 2);
                } else {
                    $beneficio_por_ciento = "";
                }
            }
            /*
                if($tarifa_venta_unidad!=$BT10 ){
                    if($tarifa_venta_peso!=$BT10 ){
                        if($id_producto!=999998 && $id_producto!=999){
                            if(number_format($BT10*$peso_real/1000,2)!=$tarifa_venta_unidad ){
                                if(number_format($BT10*$peso_real/1000,2)!=$tarifa_venta_peso ){
                */
            $datosInforme[] = array(
                'nombre' => $nombre,
                'id_producto' => $id_producto,
                'codigo_producto' => $codigo_producto,

                'tarifa_venta_unidad' => $tarifa_venta_unidad,
                'tarifa_venta_peso' => $tarifa_venta_peso,
                'peso_real' => $peso_real,
                'PVPpesoCalculado' => $PVPpesoCalculado,
                'GEW1' => $GEW1,
                'BT10' => $BT10,
            );
            if (isset($datosInformeNum[$codigo_producto])) {
                $datosInformeNum[$codigo_producto] += $POS1;
            } else {
                $datosInformeNum[$codigo_producto] = $POS1;
            }
            $datosInformeNombre[$codigo_producto] = $nombre;
            $datosInformeId_producto[$codigo_producto] = $id_producto;
            $datosInformePrecio_ultimo_unidad[$codigo_producto] = $precio_ultimo_unidad;
            $datosInformePrecio_ultimo_peso[$codigo_producto] = $precio_ultimo_peso;
            $datosInformeDescuento_1_compra[$codigo_producto] = $descuento_1_compra;
            $datosInformeTarifa_venta_unidad[$codigo_producto] = $tarifa_venta_unidad;
            $datosInformeTarifa_venta_peso[$codigo_producto] = $tarifa_venta_peso;
            $datosInformePeso_real[$codigo_producto] = $peso_real;
            $datosInformePVPpesoCalculado[$codigo_producto] = $PVPpesoCalculado;
            $datosInformeGEW1[$codigo_producto] = $GEW1;
            $datosInformeBT10[$codigo_producto] = $BT10;
            $datosInformeMWSA[$codigo_producto] = $MWSA;
            $datosInformeBeneficio_por_ciento[$codigo_producto] = floatval($beneficio_por_ciento);
        }

        ksort($datosInformeNombre);
        $beneficioTotal = 0;
        $beneficioTotal_por_ciento = 0;
        $lineas = 0;
        foreach ($datosInformeNombre as $k => $v) {
            // mensaje($k.' '.$datosInformeNum[$k].' '.$datosInformeBT10[$k].' '.$datosInformeBeneficio_por_ciento[$k]);
            $beneficio = $datosInformeNum[$k] * $datosInformeBT10[$k] * $datosInformeBeneficio_por_ciento[$k] / 100;
            $beneficio =  number_format($beneficio, 2);
            $beneficioTotal += $beneficio;
            $beneficioTotal_por_ciento += ($datosInformeBeneficio_por_ciento[$k]);
            $lineas++;

            $color = "";
            if ($datosInformeBeneficio_por_ciento[$k] < 35) $color = " color:red; ";


            $salida .=  '<tr>';
            $salida .=  "<td style='padding-left:5px;padding-right:5px;text-align:left;'>$datosInformeNombre[$k]</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformeId_producto[$k]</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$k</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformeNum[$k]</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformePrecio_ultimo_unidad[$k]</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformePrecio_ultimo_peso[$k]</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformeDescuento_1_compra[$k]</td>";

            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformeTarifa_venta_unidad[$k]</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformeTarifa_venta_peso[$k]</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformePeso_real[$k]</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformePVPpesoCalculado[$k]</td>";
            $salida .=  "<td>-----</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformeGEW1[$k]</td>";
            $salida .=  "<td style='padding-left:5px;padding-right:5px;'>$datosInformeBT10[$k]</td>";
            $salida .=  "<th style='padding-left:5px;padding-right:5px;'>$beneficio</th>";
            $salida .=  "<th style='padding-left:5px;padding-right:5px; $color'>$datosInformeBeneficio_por_ciento[$k]</td>";
            $salida .=  '</tr>';
        }
        $beneficioTotal =  number_format($beneficioTotal, 2);
        $beneficioTotal_por_ciento = 0;
        if ($lineas != 0)
            $beneficioTotal_por_ciento = number_format($beneficioTotal_por_ciento / $lineas, 2);

        $salida .=  '<tr>';
        $salida .=  "<th style='padding-left:5px;padding-right:5px;text-align:left;'>TOTAL aproximado</th>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td>-----</td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<td style='padding-left:5px;padding-right:5px;'></td>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>$beneficioTotal</th>";
        $salida .=  "<th style='padding-left:5px;padding-right:5px;'>$beneficioTotal_por_ciento</td>";
        $salida .=  '</tr>';



        $salida .=  '</table>';
        return $salida;
    }

    public function comprobacionStocksTotalesPrestaShop()
    {

        $this->load->model('productos_');
        $codigosVendidos = array();
        foreach ($this->codigosId_productos as $k => $v) {
            $id_pe_producto = $v;
            $codigosVendidos[] = $id_pe_producto;
        }
        $codigosVendidos = array_unique($codigosVendidos);
        sort($codigosVendidos);

        $salida = '<table style="border-collapse: collapse;padding-left:5px;padding-right:5px;">';
        $salida .= '<tr>';
        $salida .=  "<th style='text-align:left; border:1px solid #000000;padding-left:5px;padding-right:5px;'>Código 13</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;'>Cód báscula</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px; text-align:left;'>Nombre</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right;'>Stock mínimo</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right;'>Stock actual</th>";
        // $salida.=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:center;'>Unidad</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:left;'>Proveedor</th>";
        $salida .=  '</tr>';

        $lineas = array();
        foreach ($codigosVendidos as $k => $v) {
            //echo '$codigosVendidos sin repición'.$v.'<br>';
            $sql = "SELECT st.cantidad as stock,"
                . " pr.stock_minimo as stockMinimo,"
                . " pr.nombre as nombreProducto,"
                . " pro.nombre as nombreProveedor,"
                . " pr.id_producto as codigoBascula,"
                . " pr.codigo_producto as codigo13"
                . " FROM pe_stocks_totales st"
                . " LEFT JOIN pe_productos pr ON pr.id=st.codigo_producto"
                . " LEFT JOIN pe_proveedores_acreedores pro ON pro.id_proveedor_acreedor=pr.id_proveedor_web"
                . " WHERE st.codigo_producto='$v' AND st.cantidad<=pr.stock_minimo"
                . " AND (LEFT(pr.codigo_producto,2)='01'"
                . " OR  LEFT(pr.codigo_producto,2)='02'"
                . " OR  LEFT(pr.codigo_producto,2)='03'"
                . " OR  LEFT(pr.codigo_producto,2)='04'"
                . " OR  LEFT(pr.codigo_producto,2)='06'"
                . " OR  LEFT(pr.codigo_producto,2)='07'"
                . " OR  LEFT(pr.codigo_producto,2)='08'"
                . ") ";

            if ($this->db->query($sql)->num_rows() == 0) continue;
            $row = $this->db->query($sql)->row();
            $tipoUnidad = $this->productos_->getUnidad($v);
            $nombreProducto = $row->nombreProducto;
            $nombreProveedor = $row->nombreProveedor;
            $codigoBascula = $row->codigoBascula;
            $codigo13 = $row->codigo13;
            $stockMinimo = $row->stockMinimo / 1000;
            $stock = $row->stock / 1000;
            if ($tipoUnidad == "Und") {
                $stockMinimo = number_format($stockMinimo, 0);
                $stock = number_format($stock, 0);
            } else {
                $stockMinimo = number_format($stockMinimo, 3);
                $stock = number_format($stock, 3);
            }
            $lineas[] = array(
                'codigo13' => $codigo13,
                'codigoBascula' => $codigoBascula,
                'nombreProducto' => $nombreProducto,
                'stockMinimo' => $stockMinimo,
                'stock' => $stock,
                'tipoUnidad' => $tipoUnidad,
                'nombreProveedor' => $nombreProveedor,
            );
        }
        usort($lineas, function ($a, $b) {
            return $a['codigo13'] - $b['codigo13'];
        });

        foreach ($lineas as $k => $v) {
            $codigo13 = $v['codigo13'];
            $codigoBascula = $v['codigoBascula'];
            $nombreProducto = $v['nombreProducto'];
            $stockMinimo = $v['stockMinimo'];
            $stock = $v['stock'];
            $tipoUnidad = $v['tipoUnidad'];
            $nombreProveedor = $v['nombreProveedor'];

            $stockTU = $stock . ' ' . substr($tipoUnidad, 0, 2);
            $salida .=  '<tr>';
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;'>$codigo13</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:center;'>$codigoBascula</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:left;'>$nombreProducto</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right;'>$stockMinimo</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right;'>$stockTU</td>";
            //$salida.=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right;text-align:center;'>$tipoUnidad</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:left;'>$nombreProveedor</td>";
            $salida .=  '</tr>';
        }
        $salida .=  '</table>';
        return $salida;
    }

    public function comprobacionStocksTotales()
    {
        $this->load->model('productos_');
        $codigosVendidos = array();
        foreach ($this->codigosId_productos as $k => $v) {
            $id_pe_producto = $v[1];
            $codigosVendidos[] = $id_pe_producto;
        }

        $codigosVendidos = array_unique($codigosVendidos);

        if (!count($codigosVendidos)) return '<br><h3>Nada a reportar.</h3>';
        $salida = '<table  style="border-collapse: collapse;padding-left:5px;padding-right:5px;">';
        $salida .= '<tr>';
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:left;'>Proveedor</th>";
        $salida .=  "<th style='text-align:left; border:1px solid #000000;padding-left:5px;padding-right:5px;'>Código 13</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;'>Cód báscula</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px; text-align:left;'>Nombre</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right;'>Stock mínimo</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right;'>Stock actual</th>";
        // $salida.=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:center;'>Unidad</th>";

        $salida .=  '</tr>';

        $lineas = array();
        foreach ($codigosVendidos as $k => $v) {
            // echo '$codigosVendidos sin repición'.$v.'<br>';
            $sql = "SELECT st.cantidad as stock,"
                . " pr.stock_minimo as stockMinimo,"
                . " pr.nombre as nombreProducto,"
                . " pro.nombre as nombreProveedor,"
                . " pr.id_producto as codigoBascula,"
                . " pr.codigo_producto as codigo13"
                . " FROM pe_stocks_totales st"
                . " LEFT JOIN pe_productos pr ON pr.id=st.codigo_producto"
                . " LEFT JOIN pe_proveedores_acreedores pro ON pro.id_proveedor_acreedor=pr.id_proveedor_web"
                . " WHERE st.codigo_producto='$v' AND st.cantidad<=pr.stock_minimo "
                . " AND (LEFT(pr.codigo_producto,2)='01'"
                . " OR  LEFT(pr.codigo_producto,2)='02'"
                . " OR  LEFT(pr.codigo_producto,2)='03'"
                . " OR  LEFT(pr.codigo_producto,2)='04'"
                . " OR  LEFT(pr.codigo_producto,2)='06'"
                . " OR  LEFT(pr.codigo_producto,2)='07'"
                . " OR  LEFT(pr.codigo_producto,2)='08'"
                . ") ";


            if ($this->db->query($sql)->num_rows() == 0) continue;
            $row = $this->db->query($sql)->row();
            $tipoUnidad = $this->productos_->getUnidad($v);
            $nombreProducto = $row->nombreProducto;
            $nombreProveedor = $row->nombreProveedor;
            $codigoBascula = $row->codigoBascula;
            $codigo13 = $row->codigo13;
            $stockMinimo = $row->stockMinimo / 1000;
            $stock = $row->stock / 1000;
            if ($tipoUnidad == "Und") {
                $stockMinimo = number_format($stockMinimo, 0);
                $stock = number_format($stock, 0);
            } else {
                $stockMinimo = number_format($stockMinimo, 3);
                $stock = number_format($stock, 3);
            }
            $lineas[] = array(
                'codigo13' => $codigo13,
                'codigoBascula' => $codigoBascula,
                'nombreProducto' => $nombreProducto,
                'stockMinimo' => $stockMinimo,
                'stock' => $stock,
                'tipoUnidad' => $tipoUnidad,
                'nombreProveedor' => $nombreProveedor,
            );
        }
        usort($lineas, function ($a, $b) {
            return $a['nombreProveedor'] >= $b['nombreProveedor'];
        });
        if (!count($lineas)) return '<br><h3>Nada a reportar.</h3>';
        foreach ($lineas as $k => $v) {
            $codigo13 = $v['codigo13'];
            $codigoBascula = $v['codigoBascula'];
            $nombreProducto = $v['nombreProducto'];
            $stockMinimo = $v['stockMinimo'];
            $stock = $v['stock'];
            $tipoUnidad = $v['tipoUnidad'];
            $nombreProveedor = $v['nombreProveedor'];

            $stockTU = $stock . " " . substr($tipoUnidad, 0, 2);
            $salida .=  '<tr>';
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:left;'>$nombreProveedor</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;'>$codigo13</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:center;'>$codigoBascula</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:left;'>$nombreProducto</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right;'>$stockMinimo</td>";
            $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right;'>$stockTU</td>";
            // $salida.=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:center;'>$tipoUnidad</td>";
            $salida .=  '</tr>';
        }
        $salida .=  '</table>';


        return $salida;
    }

    public function getNumTicketBalanza($BONU)
    {
        $row = $this->db->query("SELECT RASA,WANU FROM pe_boka WHERE STYP=1 AND BONU='$BONU' ")->row();

        return $row->RASA . '/' . $row->WANU;
    }

    public function getNombreCliente($BONU)
    {
        $sql = "SELECT b.SNR2 as SNR2,c.nombre as nombre FROM pe_boka b "
            . " LEFT JOIN pe_clientes c ON c.id_cliente=b.SNR2 "
            . " WHERE b.BONU='$BONU'";

        if ($this->db->query($sql)->num_rows() == 0) return "";
        $row = $this->db->query($sql)->row();
        if ($row->SNR2 == 0) return "";
        return $row->nombre . ' (' . $row->SNR2 . ')';
    }

    public function comprobacionPreciosTarifas()
    {

        $encontrados = false;
        $salida = '<table style="border-collapse: collapse;padding-left:5px;padding-right:5px;">';
        $salida .= '<tr>';
        $salida .=  "<th style='border:1px solid #000000;background-color:#fff9c4;padding-left:5px;padding-right:5px;text-align:center;'>Código 13</th>";
        $salida .=  "<th style='border:1px solid #000000;background-color:#fff9c4;padding-left:5px;padding-right:5px;text-align:center;'>Cód báscula</th>";
        $salida .=  "<th style='text-align:left; border:1px solid #000000;background-color:#fff9c4;padding-left:5px;padding-right:5px;text-align:left'>Nombre</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'>PVP Unidad</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'>PVP Peso</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'>Peso BD</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'>PVP Peso calculado</th>";
        //$salida.=  "<th>-----</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;border-left:4px solid #000000;padding-right:5px;text-align:right'>Peso Boka</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right'>Precio Boka</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right'>Núm Ticket/Básc</th>";
        $salida .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right'>Cliente</th>";
        $salida .=  '</tr>';

        $datos_array = $this->codigosId_productos;
        //var_dump($datos_array);
        $codigo_productos = array();
        foreach ($datos_array as $k => $v) {
            $codigo_productos[$this->productos_->getCodigoProducto($v[1])] = $v;
        }
        ksort($codigo_productos);

        foreach ($codigo_productos as $k => $v) {
            $id = $v[0];
            $id_pe_producto = $v[1];

            $sql = "SELECT BONU, SNR1,POS1,GEW1,BT10 FROM pe_boka WHERE STYP=2 AND id='$id'";

            $datosBoka = $this->db->query($sql)->row();
            $SNR1 = $datosBoka->SNR1;
            $POS1 = $datosBoka->POS1;
            $GEW1 = $datosBoka->GEW1;
            $BONU = $datosBoka->BONU;
            $numTicketBalanza = $this->getNumTicketBalanza($BONU);
            $cliente = $this->getNombreCliente($BONU);
            if (!$GEW1) $GEW1 = "";
            $BT10 =  number_format($datosBoka->BT10 / 100, 2);

            $sql = "SELECT codigo_producto, id_producto,tarifa_venta_unidad, tarifa_venta_peso, nombre,peso_real
                      FROM pe_productos
                      WHERE id='$id_pe_producto' ORDER BY codigo_producto";
            //log_message('INFO','_____________________________$sql '.$sql);      
            $datosBDproductos = $this->db->query($sql)->row();
            $nombre = $datosBDproductos->nombre;
            $id_producto = $datosBDproductos->id_producto;
            $codigo_producto = $datosBDproductos->codigo_producto;
            $tarifa_venta_unidad = number_format($datosBDproductos->tarifa_venta_unidad / 1000, 2);
            //nMABA if($tarifa_venta_unidad=="0.00") $tarifa_venta_unidad="";
            $tarifa_venta_peso = number_format($datosBDproductos->tarifa_venta_peso / 1000, 2);
            if ($tarifa_venta_peso == "0.00") $tarifa_venta_peso = "";
            $peso_real = $datosBDproductos->peso_real;
            if ($datosBDproductos->peso_real != 0) {
                $PVPpesoCalculado = $datosBDproductos->tarifa_venta_unidad * 100000 / $datosBDproductos->peso_real;
                $PVPpesoCalculado =  number_format($PVPpesoCalculado / 100000, 2);
                //if($PVPpesoCalculado=="0.00") $PVPpesoCalculado="";
            } else $PVPpesoCalculado = "";

            if ($tarifa_venta_unidad != $BT10) {
                if ($tarifa_venta_peso != $BT10) {
                    if ($id_producto != 999998 && $id_producto != 999997 && $id_producto != 999) {
                        if (number_format($BT10 * $peso_real / 1000, 2) != $tarifa_venta_unidad) {
                            if (number_format($BT10 * $peso_real / 1000, 2) != $tarifa_venta_peso) {
                                //log_message('INFO','________________________$PVPpesoCalculado '.$PVPpesoCalculado);
                                //log_message('INFO','________________________$BT10 '.$BT10);
                                if (abs(floatval($PVPpesoCalculado) - $BT10) > 0.02) {
                                    $peso_real = $peso_real == 0 ? "" : number_format($peso_real / 1000, 3);
                                    $GEW1 = $GEW1 == 0 ? "" : number_format($GEW1 / 1000, 3);

                                    if (!$GEW1) {
                                        $tarifa_venta_peso = "";
                                        $peso_real = "";
                                        $PVPpesoCalculado = "";
                                    }
                                    $encontrados = true;
                                    $salida .=  '<tr>';
                                    $salida .=  "<td style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:center'>$codigo_producto</td>";
                                    $salida .=  "<td style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:center'>$id_producto</td>";
                                    $salida .=  "<td style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:left;'>$nombre</td>";
                                    if ($GEW1) {
                                        $salida .=  "<td style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'>$tarifa_venta_unidad</td>";
                                    } else {
                                        $salida .=  "<td style='color: #333FFF;padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'><strong>$tarifa_venta_unidad</strong></td>";
                                    }
                                    $salida .=  "<td style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'>$tarifa_venta_peso</td>";
                                    $salida .=  "<td style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'>$peso_real</td>";
                                    $salida .=  "<td style='color: #333FFF;padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'><strong>$PVPpesoCalculado</strong></td>";
                                    //$salida.=  "<td>-----</td>";
                                    $salida .=  "<td style='padding-left:5px;border:1px solid #000000;border-left:4px solid #000000;padding-right:5px;text-align:right'>$GEW1</td>";
                                    $salida .=  "<td style='color: #333FFF;padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right'><strong>$BT10</strong></td>";
                                    $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right'>$numTicketBalanza</td>";
                                    $salida .=  "<td style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right'>$cliente</td>";
                                    $salida .=  '</tr>';
                                }
                            }
                        }
                    }
                }
            }
        }


        $salida .=  '</table>';
        if (!$encontrados) return '<h3>Nada a reportar.</h3>';
        return $salida;
    }

    public function putBaseEIva()
    {
        $result = $this->db->query('SELECT * FROM pe_datos_prestashop WHERE valid!=-1')->result();
        foreach ($result as $k => $v) {
            $id_order = $v->order_no;
            $sql = "SELECT id_order,sum(iva) as iva, sum(importe-iva) as importe_sin_iva,sum(precio*cantidad) as bruto,sum(precio*cantidad-importe) as descuento, sum(importe) as importe FROM pe_lineas_orders_prestashop  WHERE id_order='$id_order'";
            //echo $sql.'<br>';
            $row = $this->db->query($sql)->row();
            $total_base = $row->importe_sin_iva;
            $total_iva = $row->iva;
            if ($v->customer_id_grup == 9) {
                $total_base = $row->importe;
                $total_iva = 0;
            }
            //$descuento=$row->descuento;
            $sql = "UPDATE pe_orders_prestashop SET total_base='$total_base' ,total_iva='$total_iva' WHERE id='$id_order'";
            $this->db->query($sql);
        }
    }

    public function putBaseEIvaTransporte()
    {
        $result = $this->db->query('SELECT * FROM pe_datos_prestashop WHERE valid!=-1 ')->result();
        foreach ($result as $k => $v) {
            $id_order = $v->order_no;
            $transporte = $v->total_shipping;
            $total_products = $v->total_products;
            $descuento = $v->total_discounts;
            $ivaTransporte = 0.21;
            $sql = "SELECT * FROM pe_orders_prestashop WHERE id='$id_order'";
            $row = $this->db->query($sql)->row();
            $total = $row->total;
            $transporte = $row->transporte;
            $tipoIvaTransporte = $row->tipo_iva_transporte;
            if ($v->valid == 0) {
                $transporte = 0;
                $total_products = 0;
                $tipoIvaTransporte = 0;
            }
            $transporte /= 1000;
            // mensaje($row->customer_id_group);
            // mensaje($row->total_pedido);
            // mensaje($row->total_base);
            // mensaje($v->cliente_sin_iva);
            if ($v->cliente_sin_iva == 1) {
                $base_transporte = $transporte;
                $tipoIvaTransporte = 0;
            } else {
                $base_transporte = round($transporte / (1 + $ivaTransporte) * 100) / 100;
            }
            $iva_transporte = $transporte - $base_transporte;
            $base_transporte *= 1000;
            $iva_transporte *= 1000;
            $total_pedido = $transporte + $total_products - $descuento;
            $sql = "UPDATE pe_orders_prestashop SET tipo_iva_transporte='$tipoIvaTransporte', total_pedido='$total_pedido', base_transporte='$base_transporte' ,iva_transporte='$iva_transporte' WHERE id='$id_order'";
            $this->db->query($sql);
        }
    }

    public function comprobacionPreciosTarifasPrestaShop()
    {
        $cabeceraTabla = '<table style="border-collapse: collapse;padding-left:5px;padding-right:5px;">';
        $cabeceraTabla .= '<tr>';
        $cabeceraTabla .=  "<th style='border:1px solid #000000;background-color:#fff9c4;padding-left:5px;padding-right:5px;text-align:center;'>Código 13</th>";
        $cabeceraTabla .=  "<th style='text-align:left; border:1px solid #000000;background-color:#fff9c4;padding-left:5px;padding-right:5px;text-align:left'>Nombre</th>";
        $cabeceraTabla .=  "<th style='padding-left:5px;border:1px solid #000000;background-color:#fff9c4;padding-right:5px;text-align:right'>PVP Unidad</th>";
        $cabeceraTabla .=  "<th style='padding-left:5px;border:1px solid #000000;border-left:4px solid #000000;border-left:4px solid #000000;padding-right:5px;text-align:right'>Precio PrestaShop</th>";
        $cabeceraTabla .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right'>Diferencia (%)</th>";
        $cabeceraTabla .=  "<th style='padding-left:5px;border:1px solid #000000;padding-right:5px;text-align:right'>Núm Pedido</th>";

        $cabeceraTabla .=  '</tr>';

        $numNoComparados = 0;

        $salida = "";
        $result = $this->db->query('SELECT * FROM pe_datos_prestashop WHERE valid!=-1')->result();
        foreach ($result as $k => $v) {

            $iva = "";
            $precioVenta = $v->product_price;
            $id_pe_producto = $this->productos_->getId_pe_producto($v->product_ref);
            $tarifaPVP = $this->productos_->getTarifaVenta($id_pe_producto);
            $observaciones = "";
            if ($v->componente_pack == 1) $observaciones .= "Componente Pack.";
            if ($v->customer_id_grup == 9) {
                //los pedidos intercomunitarios NO se reporan diferencias precios (Alex 29/06/2017)
                continue;
                $iva = $this->productos_->getIvaId($id_pe_producto);
                $tarifaPVP = (100 * ($tarifaPVP / (100 + $iva / 1000)));
                $tarifaPVP = intval($tarifaPVP);
                $observaciones .= " Cliente grupo 9. PVP Unidad SIN IVA";
            }
            $tarifaPVP = number_format($tarifaPVP / 1000, 2, ".", "");
            $precioVenta = number_format($precioVenta / 1000, 2, ".", "");
            if ($precioVenta != $tarifaPVP) {
                $dif = 0;
                if ($tarifaPVP != 0) $dif = ($tarifaPVP - $precioVenta) / $tarifaPVP * 100;

                $dif = -$dif;
                $resaltado = "";
                if (abs($dif) > 1 && $dif > 0) {
                    $resaltado = "style='font-weight: bold; color:blue;'";
                }
                if (abs($dif) > 1 && $dif < 0) {
                    $resaltado = "style='font-weight: bold; color:red;'";
                }

                $dif = number_format($dif, 2, ".", "");
                $salida .=  '<tr ' . $resaltado . ' >';
                $salida .=  "<td style='border:1px solid #000000;background-color:#fff9c4;padding-left:5px;padding-right:5px;text-align:center'>$v->product_ref</td>";
                $salida .=  "<td style='border:1px solid #000000;background-color:#fff9c4;padding-left:5px;padding-right:5px;text-align:left;'>$v->product_name</td>";
                $salida .=  "<td style='border:1px solid #000000;background-color:#fff9c4;padding-left:5px;padding-right:5px;text-align:right'>$tarifaPVP</td>";
                $salida .=  "<td style='border:1px solid #000000;border-left:4px solid #000000;padding-left:5px;padding-right:5px;text-align:right'>$precioVenta</td>";
                $salida .=  "<td style='border:1px solid #000000;padding-left:5px;padding-right:5px;text-align:right'>$dif</td>";
                $salida .=  "<td style='border:1px solid #000000;padding-left:5px;padding-right:5px;text-align:right'>$v->order_no</td>";
                $salida .=  "<td style='border:1px solid #000000;padding-left:5px;padding-right:5px;text-align:left'>$observaciones</td>";
                $salida .=  '</tr>';
            }
        }
        if ($salida) {
            $salida = $cabeceraTabla . $salida . '</table>';
        }
        return array('salida' => $salida, 'numNoComparados' => $numNoComparados);
    }

    public function getDatos($datosArchivo)
    {

        //maba solo para pruebas 
        //$this->db->query("DELETE FROM pe_boka WHERE zeis>='2018-10-01'");


        //borra registros de pe_asignacion_productos
        // mensaje('Upload_ getDatos');
        $r = $this->db->query("DELETE FROM pe_asignacion_productos WHERE 1");
        if (!$r) exit("<h2>Problema pe_asignacion_productos. Informar</h2>");

        $this->codigosId_productos = array();
        $resultadosStocksTotales = array();
        
        $ultimoIDAnterior =  100000;
        $ultimoIDActual =   5000000;

        $contextOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        );
        $context = stream_context_create($contextOptions);
        //$file = fopen(base_url() . "uploads/" . $datosArchivo['raw_name'] . $datosArchivo['file_ext'], "r",false,$context) or exit("No se puede abrir el archivo");
        $file = fopen(base_url() . "uploads/" . $datosArchivo['raw_name'] . $datosArchivo['file_ext'], "r") or exit("No se puede abrir el archivo");
        $linea = fgets($file);
        fclose($file);

        //verificamos si la linea es de Boka
        $resultado = "";
        $exito = true;

        //verificamos si el código de la primera linea existe. Si sí, es que el archivo ya se ha subido
        $tiempo_inicio = microtime(true);

        if (true) {
            $codigo = substr($linea, 0, 7);
            $fecha_archivo = substr($linea, 287, 10);
            $fecha_archivo = substr($fecha_archivo, 6, 4) . '-' . substr($fecha_archivo, 3, 2) . '-' . substr($fecha_archivo, 0, 2);
            $query = "SELECT BONU,LEFT(ZEIS,10) FROM pe_boka WHERE BONU='$codigo' AND LEFT(ZEIS,10)='$fecha_archivo'";
            if ($this->db->query($query)->num_rows() > 0) {
                $resultado = "Este archivo YA se ha subido anteriormente.";
                $exito = false;
                return array(
                    'datosArchivo' => $datosArchivo,
                    'productoNoExistente' => '',
                    'resultado' => $resultado,
                    'exito' => $exito,
                );
            }
        }
        $tiempo_fin = microtime(true);
        // mensaje( "Verificar archivo subido: " . ($tiempo_fin - $tiempo_inicio));



        $tickets = 0;
        $base[1] = 0;
        $base[2] = 0;
        $base[3] = 0;
        $iva[1] = 0;
        $iva[2] = 0;
        $iva[3] = 0;
        $total[1] = 0;
        $total[2] = 0;
        $total[3] = 0;
        $codigosSubidos = "";
        $inicioFecha = "";
        $resultadoPreciosTarifas = "";
        $informeBeneficiosVentas = "";
        $reportPesoVariasUnidades = "";

        if ($resultado == "") {

            //vemos último id de boka
            $sql = "SELECT id FROM pe_boka ORDER BY id DESC LIMIT 1";
            $ultimoIDAnterior = $this->db->query($sql)->row()->id;



            $resultado = "El archivo se ha incorporado a la base de datos Boka";

            $inicios = array(0, 7, 9, 14, 19, 21, 26, 31, 40, 45, 50, 55, 65, 75, 85, 90, 95, 105, 115, 120, 125, 130, 135, 140, 145, 150, 155, 160, 165, 170, 175, 185, 190, 195, 205, 215, 220, 230, 239, 248, 253, 258, 263, 272, 277, 282, 287, 307, 327, 347);
            $finales = array(7, 2, 5,  5,  2,  5,  5,  9,  5,  5,  5, 10, 10, 10,  5,  5, 10,  10,   5,   5,   5,   5,   5,   5,      5,   5,   5,     5,   5,   5,  10,   5,      5,  10,  10,   5,  10,   9,   9,   5,   5,   5,   9,   5,   5,   5,  20,  20,  20,  20);

            $file = fopen(base_url() . "uploads/" . $datosArchivo['raw_name'] . $datosArchivo['file_ext'], "r", false, $context) or exit("No se puede abrir el archivo");
            $lineas = array();
            while (!feof($file)) {
                $lineas[] = fgets($file);
            }


            //incopora informacion a  la base de datos
            //chequeamos si TODOS por productos están en la base de datos de productos
            $ver = array();
            foreach ($lineas as $k => $v) {
                foreach ($inicios as $k2 => $v2) {
                    $ver[$k][] = substr($v, $v2, $finales[$k2]);
                }
                if ($ver[$k][2] == 2) { //echo $ver[$k][2].' '.$ver[$k][7].'<br >';
                    $sql = "SELECT id_producto FROM pe_productos WHERE id_producto='" . $ver[$k][7] . "' AND status_producto=1";
                    if ($this->db->query($sql)->num_rows() == 0) {
                        return array('productoNoExistente' => $ver[$k][7]);
                    }
                }
            }


            $valores = array();
            foreach ($lineas as $k => $v) {
                foreach ($inicios as $k2 => $v2) {
                    $valores[$k][] = substr($v, $v2, $finales[$k2]);
                }
                // mensaje($valores[$k][46]);
                $valores[$k][46] = date_format(new DateTime($valores[$k][46]), 'Y-m-d H:i:s');
                $valores[$k][47] = date_format(new DateTime($valores[$k][47]), 'Y-m-d H:i:s');
                $valores[$k][48] = date_format(new DateTime('1970-01-01 00:00:00'), 'Y-m-d H:i:s');
                $this->setBoka($valores[$k]);
            }

            $sql = "SELECT id FROM pe_boka ORDER BY id DESC LIMIT 1";
            $ultimoIDActual = $this->db->query($sql)->row()->id;
            // mensaje($ultimoIDAnterior);
            // mensaje($ultimoIDActual);
            //pone las fechas en base datos para STYP != 1
            //facilita la búsqueda de la información
            $hoy = date('Y-m-d');
            $this->db->query("DELETE FROM pe_boka_auxiliar WHERE 1");
            $this->db->query("INSERT INTO pe_boka_auxiliar SET ultimo_id_anterior='$ultimoIDAnterior', ultimo_id_actual='$ultimoIDActual', fecha='$hoy'");


            $tiempo_inicio = microtime(true);

            $this->ponerFechasNuevos($ultimoIDAnterior, $ultimoIDActual);

            $tiempo_fin = microtime(true);
            // mensaje( "ponerFechasNuevos: " . ($tiempo_fin - $tiempo_inicio));

            //return array('primero'=>$ultimoIDAnterior,'ultimo'=>$ultimoIDActual);

            $tickets = 0;
            $base[1] = 0;
            $base[2] = 0;
            $base[3] = 0;
            $base[4] = 0;
            $iva[1] = 0;
            $iva[2] = 0;
            $iva[3] = 0;
            $iva[4] = 0;
            $total[1] = 0;
            $total[2] = 0;
            $total[3] = 0;
            $total[4] = 0;
            $codigosSubidos = array();
            foreach ($lineas as $k => $v) {
                if (substr($v, 9, 5) == 1) {
                    $tickets++;

                    $codigosSubidos[] =  substr($v, 0, 7) . ' ' . substr($v, 287);
                }
                if (substr($v, 9, 5) == 6) {
                    $tipo = intval(substr($v, 115, 5));
                    //echo intval(substr($v, 0, 10)).'<br>';
                    //echo $tipo.'<br>';
                    $base[$tipo] += intval(substr($v, 55, 10));
                    $iva[$tipo] += intval(substr($v, 65, 10));
                    $total[$tipo] += intval(substr($v, 75, 10));
                }
            }
            if (sizeof($codigosSubidos) > 0) {
                $inicio = min($codigosSubidos);
                $inicioTicket = substr($inicio, 0, strpos($inicio, " "));
                $inicioFecha = substr($inicio, strpos($inicio, " ") + 1, 10);
                $inicioFecha = date('d/m/Y', strtotime($inicioFecha));
                $inicioHora = substr($inicio, strpos($inicio, " ") + 11);

                $final = max($codigosSubidos);
                $finalTicket = substr($final, 0, strpos($final, " "));
                $finalFecha = substr($final, strpos($final, " ") + 1, 10);
                $finalFecha = date('d/m/Y', strtotime($finalFecha));
                $finalHora = substr($final, strpos($final, " ") + 11);


                $inicio = min($codigosSubidos);
                $codigosSubidos = "Desde $inicioTicket ($inicioFecha $inicioHora)  hasta $finalTicket ($finalFecha $finalHora)";
                // mensaje($codigosSubidos);

                $tiempo_inicio = microtime(true);
                $this->ponerId_pe_producto($ultimoIDAnterior, $ultimoIDActual);

                $tiempo_fin = microtime(true);
                // mensaje( "ponerId_pe_producto: " . ($tiempo_fin - $tiempo_inicio));

                $tiempo_inicio = microtime(true);
                $this->ponerDatosTablaTickets($ultimoIDAnterior, $ultimoIDActual);
                $tiempo_fin = microtime(true);
                // mensaje( "ponerDatosTablaTickets: " . ($tiempo_fin - $tiempo_inicio));



                // mensaje('productosVendidos');
                $tiempo_inicio = microtime(true);
                $this->productosVendidos($ultimoIDAnterior, $ultimoIDActual);
                $tiempo_fin = microtime(true);
                // mensaje( "productosVendidos: " . ($tiempo_fin - $tiempo_inicio));

                $tiempo_inicio = microtime(true);
                $resultadoPreciosTarifas = $this->comprobacionPreciosTarifas();
                $tiempo_fin = microtime(true);
                // mensaje( "comprobacionPreciosTarifas: " . ($tiempo_fin - $tiempo_inicio));

                $tiempo_inicio = microtime(true);
                $informeBeneficiosVentas = $this->informeBeneficiosVentas();
                $tiempo_fin = microtime(true);
                // mensaje( "informeBeneficiosVentas: " . ($tiempo_fin - $tiempo_inicio));

                $fechas = $this->getFechasTickets($ultimoIDAnterior, $ultimoIDActual);
                $desde = $fechas['fechaInicio'];
                $hasta = $fechas['fechaFinal'];
                $tiempo_inicio = microtime(true);
                $reportPesoVariasUnidades = $this->reportTicketsPesoVariasUnidades($desde, $hasta);
                $tiempo_fin = microtime(true);
                // mensaje( "reportTicketsPesoVariasUnidades: " . ($tiempo_fin - $tiempo_inicio));
            } else {
                $codigosSubidos = "---";
            }
        }

        $tiempo_inicio = microtime(true);
        $resultadosStocksTotales = $this->comprobacionStocksTotales();
        $tiempo_fin = microtime(true);
        // mensaje( "comprobacionStocksTotales: " . ($tiempo_fin - $tiempo_inicio));

        return array(
            'datosArchivo' => $datosArchivo,
            'primero' => $ultimoIDActual,
            'ultimo' => $ultimoIDActual,
            'fecha' => $inicioFecha,
            'resultado' => $resultado,
            'exito' => $exito,
            'linea' => $linea,
            'tickets' => $tickets,
            'codigosSubidos' => $codigosSubidos,
            'base' => $base,
            'iva' => $iva,
            'total' => $total,
            'productoNoExistente' => '',
            'resultadoPreciosTarifas' => $resultadoPreciosTarifas,
            'informeBeneficiosVentas' => $informeBeneficiosVentas,
            'resultadosStocksTotales' => $resultadosStocksTotales,
            'reportPesoVariasUnidades' => $reportPesoVariasUnidades,
            'ultimoIDAnterior' => $ultimoIDAnterior,
            'ultimoIDActual' => $ultimoIDActual,
        );
    }

    public function ponerId_pe_producto($ultimoIDAnterior, $ultimoIDActual)
    {
        //para incluir en boks el id_pe_producto
        // set_time_limit(0);
        $sql = "SELECT id,SNR1,GEW1 FROM pe_boka WHERE STYP=2  AND id>$ultimoIDAnterior AND id<=$ultimoIDActual";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $SNR1 = $v->SNR1;
            // echo $SNR1.' ';
            $id = $v->id;
            $GEW1 = $v->GEW1;
            $sql = "SELECT * FROM pe_productos WHERE id_producto='$SNR1' ORDER BY peso_real ";
            if ($this->db->query($sql)->num_rows() === 1) {
                $id_pe_producto = $this->db->query($sql)->row()->id;
            } else {
                $result2 = $this->db->query($sql)->result();
                $id_pe_producto = 0;
                foreach ($result2 as $k2 => $v2) {
                    if ($id_pe_producto == 0) $id_pe_producto = $v2->id;
                    if ($v2->peso_real < $GEW1) $id_pe_producto = $v2->id;
                }
            }
            $sql = "UPDATE pe_boka SET id_pe_producto='$id_pe_producto' WHERE id='$id'";
            $this->db->query($sql);
        }
    }

    public function validateDateY4($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    public function validateDateY2($date)
    {
        $d = DateTime::createFromFormat('y-m-d', $date);
        return $d && $d->format('y-m-d') === $date;
    }

    public function convertirFechaUnix($unix)
    {
        
        date_default_timezone_set('Europe/Madrid');
        $diferencia = 0;

        $diferencia = 0.0833333333;
        if ($unix < 42456.0833333333) $diferencia = 0.041666666666;  //invierno
        if ($unix >= 42456.0833333333 && $unix < 42673.125) $diferencia = 0.0833333333; //verano
        if ($unix >= 42673.125 && $unix < 42820.0833333333) $diferencia = 0.041666666666;  //invierno
        if ($unix >= 42820.0833333333 && $unix < 43037.125) $diferencia = 0.0833333333;
        if ($unix >= 43037.125 && $unix < 43184.0833333333) $diferencia = 0.041666666666;  //invierno
        if ($unix >= 43184.0833333333 && $unix < 43401.125) $diferencia = 0.0833333333;
        if ($unix >= 43401.125 && $unix < 43555.0833333333) $diferencia = 0.041666666666;  //invierno
        if ($unix >= 43555.0833333333 && $unix < 43765.125) $diferencia = 0.0833333333;
        if ($unix >= 43765.125 && $unix < 43919.0833333333) $diferencia = 0.041666666666;  //invierno
        if ($unix >= 43919.0833333333 && $unix < 44129.125) $diferencia = 0.0833333333;
        if ($unix >= 44129.125) $diferencia = 0.041666666666;  //invierno

        $fecha = $unix - $diferencia;
        $fecha = date('Y-m-d H:i', ($fecha - 25569) * 86400);
        return $fecha;
    }

    public function analisisTransporteDescuento($row)
    {
        if ($row[2] == 9 || $row[8] == $row[11]) { //es intercomunitario. No tiene iva
            //descuento = transporte, se considera transporte 0 y descuento 0 caso pedido 21085 16 abril 2019
            if ($row[7] == $row[10]) {
                $row[7] = 0;
                $row[10] = 0;
            }
            return $row; //es intercomunitario. No tiene iva
        }

        if ($row[1] == 0) return $row;  //no es valid
        if ($row[7] == 0) return $row;  //no tay descuento
        if ($row[8] == 0) return $row;  //Total products with tax =0
        $totalPaidTaxExcl = round($row[11] - round(($row[7] - $row[10]) / $row[8], 2) * $row[11], 2);
        // totalPaidTaxExcl = totalProducts sin tax - (descuento - transporte)/ productos con tax * total product sin tax
        if ($totalPaidTaxExcl == $row[9]) {
            $row[7] = $row[7] - $row[10];
            $row[10] = 0;
            return $row;
        }
        // echo "SI(I8<>0;SI(C8=9;J8;+REDONDEAR(REDONDEAR((I8-H8)/I8*L8;2)+REDONDEAR(K8/1,21;2);2));J8)";

        $totalPaidTaxExcl = round(round(($row[8] - $row[7]) / $row[8] * $row[11], 2) + round($row[10] / 1.21, 2), 2);
        if (abs($totalPaidTaxExcl - $row[9]) < 0.02) {
            return $row;
        }
        //se registra como factura erronea en el iva
        //pero se procesa con los datos disponibles con ivas correctos
        $num_order = $row[0];
        $row[7] = $row[7] - $row[10];
        $row[10] = 0;
        $this->db->query("INSERT INTO pe_orders_prestashop_revisar_ivas SET num_order='$num_order'");
        return $row;
    }


    public function getDatosTracking($datosArchivo)
    {
        $this->load->model('prestashop_model');
        $this->load->library('excel');

        $inputFileName = 'uploads/tracking/' . $datosArchivo['raw_name'] . $datosArchivo['file_ext'];

        //comprobar que el archivo sea Excel
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            $resultado = "NO es un archivo de importación de PrestaShop ($e)";
            $error = 3;
            return array('noExcel' => '', 'datosArchivo' => $datosArchivo);
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        // mensaje('hola'.$highestRow);
        $highestColumn = $sheet->getHighestColumn();
        // mensaje('hola'.$highestColumn);



        ini_set('max_execution_time', 300);

        $resultRow=array();
        $fp = fopen($inputFileName, "r");
        while ($data = fgetcsv($fp, 10000,";")) {            
            foreach ($data as $k=>$row) {
                $data[$k]=str_replace(",",".",utf8_encode($data[$k]));      
              }
            $resultRow[]=$data;
        }
        // anterior
        $primeraFila = $sheet->rangeToArray('A1:B1', null, true, false);

        $primeraFila[0]=$resultRow[0];
        //Comprobar que la primera columna tenga los titulos adecuados

        if (!($primeraFila[0][0] == 'Order No' && $highestColumn == 'A')) {
            $resultado = "NO es un archivo de importación de PrestaShop";
            $error = 4;
            return array('noArchivoTracking' => true, 'datosArchivo' => $datosArchivo, 'titulos' => $primeraFila[0]);
        }

        $pedidosSinDatos = array();
        $pedidosYaTracking = array();
        $pedidoParaSubir = array();
        $pedidosFaltaDatosEnvioEmail = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
            $rowData[0] = $resultRow[$row-1];
            if (!$rowData[0][0]) continue;
            $pedido = $rowData[0][0];
            $pedidoParaSubir[] = $pedido;
            //se comprueba si existe el pedido
            $sql = "SELECT id,customer_email,customer_id_language,reference,delivery_firstname,delivery_lastname,delivery_country,shop_name FROM pe_orders_prestashop WHERE id='$pedido'";
            if ($this->db->query($sql)->num_rows() == 0) {
                $pedidosSinDatos[] = $pedido;
            } else {
                $fila = $this->db->query($sql)->row();
                if (
                    !$fila->customer_email
                    || !$fila->customer_id_language
                    || !$fila->reference
                    || !$fila->delivery_firstname
                    || !$fila->delivery_lastname
                    || !$fila->delivery_country
                    || !$fila->shop_name
                ) {
                    $pedidosFaltaDatosEnvioEmail[] = $pedido;
                }
            }
            if ($this->db->query("SELECT id FROM pe_email_tracking WHERE num_pedido='$pedido'")->num_rows() > 0) {
                $pedidosYaTracking[] = $pedido;
            }
        }
        if (count($pedidosSinDatos) > 0 || count($pedidosYaTracking) > 0 || count($pedidosFaltaDatosEnvioEmail) > 0) {
            return array('pedidosNoValidos' => true, 'pedidosSinDatos' => $pedidosSinDatos, 'pedidosYaTracking' => $pedidosYaTracking, 'pedidosFaltaDatosEnvioEmail' => $pedidosFaltaDatosEnvioEmail);
        }
        foreach ($pedidoParaSubir as $k => $v) {
            $row = $this->db->query("SELECT * FROM pe_orders_prestashop WHERE id='$v'")->row();
            $customer_email = $row->customer_email;
            $shop_name = $row->shop_name;
            $reference = $row->reference;
            $delivery_firstname = $row->delivery_firstname;
            $delivery_lastname = $row->delivery_lastname;
            $delivery_country = $row->delivery_country;
            $customer_id_language = $row->customer_id_language;
            $this->db->query("INSERT INTO pe_email_tracking SET num_pedido='$v',
                                                                    customer_email='$customer_email',
                                                                    reference='$reference',
                                                                    delivery_firstname='$delivery_firstname',
                                                                    delivery_lastname='$delivery_lastname',
                                                                    delivery_country='$delivery_country',
                                                                    customer_id_language='$customer_id_language',
                                                                    shop_name='$shop_name'");
        }
    }


    public function getDatosCostesTransportes($datosArchivo)
    {
        $this->load->model('prestashop_model');
        $this->load->library('excel');

        $inputFileName = 'uploads/transportes/' . $datosArchivo['raw_name'] . $datosArchivo['file_ext'];

        //comprobar que el archivo sea Excel
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            $resultado = "NO es un archivo de importación de PrestaShop ($e)";
            $error = 3;
            return array('noExcel' => '', 'datosArchivo' => $datosArchivo);
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        // mensaje('hola'.$highestRow);
        $highestColumn = $sheet->getHighestColumn();
        // mensaje('hola'.$highestColumn);



        //ini_set('max_execution_time', 300);

        $primeraFila = $sheet->rangeToArray('A1:AB1', null, true, false);

        //Comprobar que la primera columna tenga los titulos adecuados
        // mensaje($primeraFila[0][0]);
        // mensaje('Nº  Comanda');
        // mensaje($primeraFila[0][0]=='Nº  Comanda');
        // mensaje($primeraFila[0][6]);
        // mensaje('Total');
        // mensaje(ord('º'));
        // mensaje(ord('°'));
        // mensaje($primeraFila[0][6]=='Total');
        if (!(($primeraFila[0][0] == 'Customer Reference' && $primeraFila[0][23] == 'TOTAL') || ($primeraFila[0][0] == 'Referencia' && $primeraFila[0][3] == 'Importe') || ($primeraFila[0][0] == 'Núm Comanda' && $primeraFila[0][6] == 'Total') || ($primeraFila[0][0] == 'Nº  Comanda' && $primeraFila[0][6] == 'Total') || ($primeraFila[0][0] == 'Comanda' && $primeraFila[0][3] == 'Total'))) {
            $resultado = "NO es un archivo de costes de transtportes";
            $error = 4;
            return array('noArchivoCostesTransportes' => true, 'datosArchivo' => $datosArchivo, 'titulos' => $primeraFila[0]);
        }
        $transportista = "";
        if ($primeraFila[0][0] == 'Customer Reference') $transportista = "TNT";
        if ($primeraFila[0][0] == 'Nº  Comanda') $transportista = "DHL";
        if ($primeraFila[0][0] == 'Núm Comanda') $transportista = "DHL";
        if ($primeraFila[0][0] == 'Comanda') $transportista = "PAACK";
        if ($primeraFila[0][0] == 'Referencia') $transportista = "SEUR";
        mensaje($primeraFila[0][0]);
        mensaje($transportista);

        $lineasSinPedidos = array();
        $pedidosNoExistentes = array();
        $pedidosConCostesTransporte = array();
        $pedidosSinCostesTransporte = array();
        $costesYasubidos = array();
        $costesTransportes = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);

            if (!$rowData[0][0] || !is_numeric($rowData[0][0])) {
                $lineasSinPedidos[] = $row;
                continue;
            }

            $pedido = "";
            if ($transportista == "DHL") $pedido = $rowData[0][0];
            if ($transportista == "TNT") $pedido = substr($rowData[0][0], 3);
            if ($transportista == "PAACK") $pedido = $rowData[0][0];
            if ($transportista == "SEUR") $pedido = substr($rowData[0][0], 3);
            // mensaje($transportista);
            // mensaje($pedido);

            //se comprueba si existe el pedido con productos valid=1
            $sql = "SELECT o.id FROM pe_orders_prestashop o 
                    LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id
                    WHERE o.id='$pedido' and l.valid=1";

            $sql = "SELECT o.id FROM pe_orders_prestashop o 
            WHERE o.id='$pedido'";


            if ($this->db->query($sql)->num_rows() == 0) {
                $pedidosNoExistentes[] = $pedido;
                continue;
            }
            /*
            //se comprueba si el pedido YA tiene costes de transporte
            $sql = "SELECT id FROM pe_orders_prestashop WHERE id='$pedido' AND base_factura!=0";
            if ($adicionales==0 && $this->db->query($sql)->num_rows() == 1) {
                $pedidosConCostesTransporte[]=$pedido;
                continue;
            }

            //se comprueba si el pedido NO tiene costes de transporte
            $sql = "SELECT id FROM pe_orders_prestashop WHERE id='$pedido' AND base_factura=0";
            if ($adicionales==1 && $this->db->query($sql)->num_rows() == 1) {
                $pedidosSinCostesTransporte[]=$pedido;
                continue;
            }
            */
            $peso_transporte = 0;
            $bultos_transporte = 0;
            $base_factura = 0;
            $base_tarifa = 0;
            $base_suplementos = 0;
            $suplementos = "";
            $delivery_country_transporte = "";
            $post_code = "";
            $suple_fsf = 0;
            $suple_it1 = 0;
            $suple_res = 0;
            $suple_ess = 0;
            $suple_rwa = 0;
            $suple_oa = 0;
            $suple_cl2 = 0;
            $suple_sns = 0;
            $suple_rpf = 0;
            $suple_fie = 0;
            $suple_nec = 0;
            $suple_e28 = 0;
            $suple_rpd = 0;
            $suple_cl9 = 0;
            $suple_mam = 0;
            $suple_aduana = 0;




            if ($transportista == 'TNT') {
                $peso_transporte = $rowData[0][4];
                $bultos_transporte = $rowData[0][5];
                $delivery_country_transporte = $rowData[0][2];
                $post_code = $rowData[0][1];

                $base_tarifa = $rowData[0][6];
                $base_factura = $rowData[0][23];
                $suple_fsf = $rowData[0][7];
                $suple_it1 = $rowData[0][8];
                $suple_res = $rowData[0][9];
                $suple_ess = $rowData[0][10];
                $suple_rwa = $rowData[0][11];
                $suple_oa = $rowData[0][12];
                $suple_cl2 = $rowData[0][13];
                $suple_sns = $rowData[0][14];
                $suple_rpf = $rowData[0][15];
                $suple_fie = $rowData[0][16];
                $suple_nec = $rowData[0][17];
                $suple_e28 = $rowData[0][18];
                $suple_rpd = $rowData[0][19];
                $suple_cl9 = $rowData[0][20];
                $suple_mam = $rowData[0][21];
                $suple_aduana = $rowData[0][22];
                // mensaje('$suple_aduana '.$suple_aduana);

                if ($suple_fsf) $suplementos .= 'FSF ';
                if ($suple_it1) $suplementos .= 'IT1 ';
                if ($suple_res) $suplementos .= 'RES ';
                if ($suple_ess) $suplementos .= 'ESS ';
                if ($suple_rwa) $suplementos .= 'RWA ';
                if ($suple_oa)  $suplementos .= 'OA ';
                if ($suple_cl2) $suplementos .= 'CL2 ';
                if ($suple_sns) $suplementos .= 'SNS ';
                if ($suple_rpf) $suplementos .= 'RPF ';
                if ($suple_fie) $suplementos .= 'FIE ';
                if ($suple_nec) $suplementos .= 'NEC ';
                if ($suple_e28) $suplementos .= 'E28 ';
                if ($suple_rpd) $suplementos .= 'RPD ';
                if ($suple_cl9) $suplementos .= 'CL9 ';
                if ($suple_mam) $suplementos .= 'MAM ';
                if ($suple_aduana) $suplementos .= 'A ';
                // mensaje('$suplementos '.$suplementos);

                for ($i = 7; $i < 23; $i++) {
                    $base_suplementos += $rowData[0][$i];
                }
            }
            if ($transportista == 'DHL') {
                $base_factura = $rowData[0][4];
                $peso_transporte = $rowData[0][3];
                $delivery_country_transporte = $rowData[0][2];
                $post_code = $rowData[0][1];
                $base_tarifa = $base_factura;
            }
            if ($transportista == 'PAACK') {
                $base_factura = $rowData[0][1];
                $base_tarifa = $base_factura;
            }
            if ($transportista == 'SEUR') {
                $base_factura = $rowData[0][3];
                $base_tarifa = $base_factura;
                $peso_transporte = $rowData[0][2];
            }

            // mensaje($transportista.' '.$coste);
            $costesTransportes[] = array(
                'pedido' => $pedido,
                'delivery_country_transporte' => $delivery_country_transporte,
                'post_code' => $post_code,
                'peso_transporte' => $peso_transporte,
                'bultos_transporte' => $bultos_transporte,
                'base_tarifa' => $base_tarifa,
                'base_factura' => $base_factura,
                'base_suplementos' => $base_suplementos,
                'suple_fsf' => $suple_fsf,
                'suple_it1' => $suple_it1,
                'suple_res' => $suple_res,
                'suple_ess' => $suple_ess,
                'suple_rwa' => $suple_rwa,
                'suple_oa' => $suple_oa,
                'suple_cl2' => $suple_cl2,
                'suple_sns' => $suple_sns,
                'suple_rpf' => $suple_rpf,
                'suple_fie' => $suple_fie,
                'suple_nec' => $suple_nec,
                'suple_e28' => $suple_e28,
                'suple_rpd' => $suple_rpd,
                'suple_cl9' => $suple_cl9,
                'suple_mam' => $suple_mam,
                'suple_aduana' => $suple_aduana,
                'suplementos' => $suplementos,
            );
        }

        if (count($lineasSinPedidos) > 0) {
            return array('lineasSinPedidos' => $lineasSinPedidos);
        }
        if (count($pedidosNoExistentes) > 0) {
            return array('pedidosNoExistentes' => $pedidosNoExistentes);
        }

        /*
        if (count($pedidosConCostesTransporte) > 0 ) {
            return array('pedidosConCostesTransporte' => $pedidosConCostesTransporte);
        }

        if (count($pedidosSinCostesTransporte) > 0 ) {
            //return array('pedidosSinCostesTransporte' => $pedidosSinCostesTransporte);
        }
*/

        if (count($costesTransportes) > 0) {
            foreach ($costesTransportes as $k => $v) {
                $pedido = $v['pedido'];
                $peso_transporte = $v['peso_transporte'] * 100;
                $bultos_transporte = $v['bultos_transporte'] * 100;

                $base_factura = $v['base_factura'] * 100;
                $base_tarifa = $v['base_tarifa'] * 100;
                $base_suplementos = $v['base_suplementos'] * 100;
                $suple_fsf = $v['suple_fsf'] * 100;
                $suple_it1 = $v['suple_it1'] * 100;
                $suple_res = $v['suple_res'] * 100;
                $suple_ess = $v['suple_ess'] * 100;
                $suple_rwa = $v['suple_rwa'] * 100;
                $suple_oa = $v['suple_oa'] * 100;
                $suple_cl2 = $v['suple_cl2'] * 100;
                $suple_sns = $v['suple_sns'] * 100;
                $suple_rpf = $v['suple_rpf'] * 100;
                $suple_fie = $v['suple_fie'] * 100;
                $suple_nec = $v['suple_nec'] * 100;
                $suple_e28 = $v['suple_e28'] * 100;
                $suple_rpd = $v['suple_rpd'] * 100;
                $suple_cl9 = $v['suple_cl9'] * 100;
                $suple_mam = $v['suple_mam'] * 100;
                $suple_aduana = $v['suple_aduana'] * 100;
                $suplementos = $v['suplementos'];
                $delivery_country_transporte = $v['delivery_country_transporte'];
                $post_code = $v['post_code'];

                // mensaje('base_suplementos '.$base_suplementos);
                // mensaje('coste '.$coste);
                // $sql="UPDATE pe_orders_prestashop set nombre_transportista='$transportista', base_factura='$base_factura',base_suplementos='$base_suplementos' WHERE id='$pedido'";
                // if($adicionales==1)
                $sql = "UPDATE pe_orders_prestashop 
                    set nombre_transportista=concat(nombre_transportista,' ','$transportista'),
                    base_tarifa=base_tarifa+'$base_tarifa',
                    base_factura=base_factura+'$base_factura', 
                    peso_transporte=peso_transporte+'$peso_transporte',
                    bultos_transporte=bultos_transporte+'$bultos_transporte',
                    
                    base_transporte_segun_precios=0,
                    base_suplementos=base_suplementos+'$base_suplementos',
                    suple_fsf=suple_fsf+'$suple_fsf',
                    suple_it1=suple_it1+'$suple_it1',
                    suple_res=suple_res+'$suple_res',
                    suple_ess=suple_ess+'$suple_ess',
                    suple_rwa=suple_rwa+'$suple_rwa',
                    suple_oa=suple_oa+'$suple_oa',
                    suple_cl2=suple_cl2+'$suple_cl2',
                    suple_sns=suple_sns+'$suple_sns',
                    suple_rpf=suple_rpf+'$suple_rpf',
                    suple_fie=suple_fie+'$suple_fie',
                    suple_nec=suple_nec+'$suple_nec',
                    suple_e28=suple_e28+'$suple_e28',
                    suple_rpd=suple_rpd+'$suple_rpd',
                    suple_cl9=suple_cl9+'$suple_cl9',
                    suple_mam=suple_mam+'$suple_mam',
                    suple_aduana=suple_aduana+'$suple_aduana',
                    suplementos=concat(suplementos,' ','$suplementos'),
                    delivery_country_transporte=concat(delivery_country_transporte,' ','$delivery_country_transporte'),
                    post_code=concat(post_code,' ','$post_code')

                    WHERE id='$pedido'";

                // mensaje($sql);
                $this->db->query($sql);

                $sql = "UPDATE pe_orders_prestashop 
                    SET 
                    suple_dif=base_factura-base_tarifa-base_suplementos,
                    diferencia_base_transporte_base_factura=base_transporte/10-base_factura,
                    diferencia_base_factura_base_tarifa=base_transporte-base_factura
                    WHERE id='$pedido'";

                $this->db->query($sql);
            }
        }

        return array('archivoSubido' => $datosArchivo['raw_name'] . $datosArchivo['file_ext'], 'transportista' => $transportista, 'costesTransportes' => $costesTransportes);


        /*
        if (count($pedidosSinDatos) > 0 || count($pedidosYaTracking) > 0 || count($pedidosFaltaDatosEnvioEmail) > 0) {
            return array('pedidosNoValidos' => true, 'pedidosSinDatos' => $pedidosSinDatos, 'pedidosYaTracking' => $pedidosYaTracking, 'pedidosFaltaDatosEnvioEmail' => $pedidosFaltaDatosEnvioEmail);
        }
        foreach ($pedidoParaSubir as $k => $v) {
            $row = $this->db->query("SELECT * FROM pe_orders_prestashop WHERE id='$v'")->row();
            $customer_email = $row->customer_email;
            $shop_name = $row->shop_name;
            $reference = $row->reference;
            $delivery_firstname = $row->delivery_firstname;
            $delivery_lastname = $row->delivery_lastname;
            $delivery_country = $row->delivery_country;
            $customer_id_language = $row->customer_id_language;
            $this->db->query("INSERT INTO pe_email_tracking SET num_pedido='$v',
                                                                    customer_email='$customer_email',
                                                                    reference='$reference',
                                                                    delivery_firstname='$delivery_firstname',
                                                                    delivery_lastname='$delivery_lastname',
                                                                    delivery_country='$delivery_country',
                                                                    customer_id_language='$customer_id_language',
                                                                    shop_name='$shop_name'");
        }
        */
    }


    public function getDatosPrestashop($datosArchivo)
    {

        //se eliminan los datos de pe_orders_prestashop_revisar_ivas
        $this->db->query("DELETE FROM `pe_orders_prestashop_revisar_ivas` WHERE 1");

        $this->load->model('prestashop_model');
        $this->load->library('excel');
        $codigos = array();
        $pedidos = array();
        $validos = array();
        $nombres = array();
        $cantidades = array();
        $importes = array();
        $transportes = array();
        $codigosPack = array();
        $tarifasVenta = array();
        $error = 0;

        $inputFileName = 'uploads/prestashop/' . $datosArchivo['raw_name'] . $datosArchivo['file_ext'];
        //comprobar que el archivo sea Excel
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            $resultado = "NO es un archivo de importación de PrestaShop ($e)";
            $error = 3;
            return array('noExcel' => '', 'datosArchivo' => $datosArchivo);
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        ini_set('max_execution_time', 300);

        $resultRow=array();
        $fp = fopen($inputFileName, "r");
        while ($data = fgetcsv($fp, 10000,";")) {            
            foreach ($data as $k=>$row) {
                $data[$k]=str_replace(",",".",utf8_encode($data[$k]));      
              }
            $resultRow[]=$data;
        }
    
        //anterior
        $primeraFila = $sheet->rangeToArray('A1:U1', null, true, false);

        $primeraFila[0]=$resultRow[0];
  
        //Comprobar que la primera columna tenga los titulos adecuados
        if (true) {
            if (!($primeraFila[0][0] == 'Order No'
                && $primeraFila[0][1] == 'Valid'
                && $primeraFila[0][2] == 'Customer id grup'
                && $primeraFila[0][3] == 'Product Quantity'
                && $primeraFila[0][4] == 'Product Ref'
                && $primeraFila[0][5] == 'Product Name'
                && $primeraFila[0][6] == 'Product Price - PVP - iva incl'
                && $primeraFila[0][7] == 'Total discounts'
                && $primeraFila[0][8] == 'Total products with tax'
                && $primeraFila[0][9] == 'Total paid tax excl'
                && $primeraFila[0][10] == 'Total shipping'
                && $primeraFila[0][11] == 'Total products'
                && $primeraFila[0][12] == 'Date added'
                && $primeraFila[0][13] == 'Customer Email'
                && $primeraFila[0][14] == 'Customer id language'
                && $primeraFila[0][15] == 'Reference'
                && $primeraFila[0][16] == 'Delivery Firstname'
                && $primeraFila[0][17] == 'Delivery Lastname'
                && $primeraFila[0][18] == 'Delivery country'
                && $primeraFila[0][19] == 'Shop name'
                && $primeraFila[0][20] == 'Customer id'
				&& $primeraFila[0][21] == 'Delivery address line 1'
                && $primeraFila[0][22] == 'Delivery address line 2'
                && $primeraFila[0][23] == 'Delivery postcode'
                && $primeraFila[0][24] == 'Delivery city')) {
                $resultado = "NO es un archivo de importación de PrestaShop";
                $error = 4;
                return array('noArchivoPrestashop' => true, 'datosArchivo' => $datosArchivo, 'titulos' => $primeraFila[0]);
            }
        }


        //  Loop through each row of the worksheet in turn
        $codigoEncontrados = array();
        $codigoNoEncontrados = array();
        $pedidosSinFecha = array();
        $pedidosYaExistentes = array();
        $pedidosNoExistentes = array();
        $packSinComponentes = array();
        $datosValidos = array();
        $this->load->model('productos_');

        //se comprueba si los pedidos ya existen
        //chequeamos si hay más pedidos con el mismo número, pero distinto valor de valid
        if (true) {
            for ($row = 2; $row <= $highestRow; $row++) {

                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
                $rowData[0] = $resultRow[$row-1];
                // print_r($rowData);
                // echo '<br>';  
                if (!$rowData[0][0]) continue;

                $pedido1 = $rowData[0][0];
                $valid = $rowData[0][1];

                //solo para recomponer prestashop
                //$this->db->query("DELETE FROM pe_lineas_orders_prestashop WHERE id_order='$pedido1' AND valid='$valid'");

                $sql = "SELECT o.id, o.fecha "
                    . " FROM pe_orders_prestashop o"
                    . " LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id"
                    . " WHERE o.id='$pedido1' AND l.valid='$valid'";
                $n = $this->db->query($sql)->num_rows();
                if ($n > 0) {
                    if (!in_array($pedido1, $pedidosYaExistentes))
                        $pedidosYaExistentes[] = $pedido1;
                }
                if ($valid == -1) {
                    $sql = "SELECT o.id, fecha "
                        . " FROM pe_orders_prestashop o"
                        . " LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id"
                        . " WHERE o.id='$pedido1'";
                    $n = $this->db->query($sql)->num_rows();
                    if ($n == 0) {
                        if (!in_array($pedido1, $pedidosNoExistentes))
                            $pedidosNoExistentes[] = $pedido1;
                    }
                }
            }
            if (count($pedidosNoExistentes))
                return array('pedidosNoExistentes' => $pedidosNoExistentes, 'datosArchivo' => $datosArchivo);

            if (count($pedidosYaExistentes))
                return array('pedidosYaExistentes' => $pedidosYaExistentes, 'datosArchivo' => $datosArchivo);
        }
        //tabla provisional para gestionar entradas
        $this->db->empty_table('pe_datos_prestashop');

        for ($row = 2; $row <= $highestRow; $row++) {

            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
            $rowData[0] = $resultRow[$row-1];
            //análisi si el transporte está incluido o no en el descuento
            if (!$rowData[0][0]) continue;
            $rowData[0] = $this->analisisTransporteDescuento($rowData[0]);

            foreach ($rowData as $k => $v) {
                // $salida[]= $v[0].' '.$v[2].'  '.$v[3];
                // if($v[1]){   //si $v[1]=0, no se considera
                $valid = $v[1];
                $codigo_producto = $v[4];
                mensaje($v[4]);
                
                //verificación código
                if (strlen($codigo_producto) != 13 && substr($codigo_producto, 0, 1) != 0) $codigo_producto = '0' . $codigo_producto;

                $codigo_producto = trim($codigo_producto);
                mensaje($codigo_producto);
                if (!$this->productos_->existeActivoConControlStock($codigo_producto)) {
                    if (!in_array($codigo_producto, $codigoNoEncontrados)) {
                        $codigoInf = $codigo_producto ? $codigo_producto : 'sin código';
                        $codigoNoEncontrados[] = 'Código producto: ' . $codigoInf . ' , en el pedido: ' . $v[0];
                    }
                } else {

                    $esPack = $this->productos_->isPack($codigo_producto);

                    if (true) {
                        if (true) {
                            $pedido = $v[0];
                            if (!$v[12] || $v[12] == '0000-00-00 00:00:00') $pedidosSinFecha[] = $v[0];
                            $codigoEncontrados[] = $codigo_producto;
                            $codigos[] = $codigo_producto;
                            $nombre = $v[5];

                            $cantidad = $v[3];
                            $transporte = $v[10] * 1000;
                            if ($esPack) $nombre .= ' (' . $cantidad . ' Packs)';
                            $nombres[] = $nombre;
                            $cantidades[] = $cantidad;
                            $tarifaVenta = $v[6];
                            $tarifaPVPReal = $tarifaVenta;
                            $codigosPack[] = $esPack;

                            // if($v[8]!=0) $tarifaPVPReal=$tarifaVenta-$tarifaVenta*$v[7]/$v[8];

                            $comparar = true;

                            $iva = $this->productos_->getIva(trim($codigo_producto))->valor_iva;
                            //considerar los productos que no tienen iva 
                            //$v[2]==9 intercomunitarios o $v[8]==$v[11] => Total products with tax = Total products => IVA = 0

                            //$v[8]= total product with tax $v[11]= total product
                            $clienteSinIVA = ($v[8] == $v[11]) ? 1 : 0;
                            if ($clienteSinIVA && !$esPack) {
                                //producto internacional la tarifaVenta NO incluye el IVA, se le amade para comparar precios
                                $iva = 0;
                                $comparar = false;
                                //$tarifaVenta=round($tarifaVenta+$tarifaVenta*$iva/100,2);
                            }
                        }
                        if (true) {
                            $tarifasVenta[] = $tarifaVenta;
                            $pedidos[] = $pedido;

                            $validos[] = $valid;

                            $importe = round($cantidad * $tarifaPVPReal, 2);
                            if ($valid == 0 || $valid == -1) {
                                $importe = 0;
                                $transporte = 0;
                            }

                            if ($esPack) $importe = 0;
                            $importes[] = $importe;
                            $transportes[] = $transporte;
                            $fecha=$v[12];
                            mensaje('pedido: '.$v[0]);
                            mensaje('$v[12]: '.$v[12]);
                            // $fecha = $this->convertirFechaUnix($v[12]);
                            // $fecha=fechaBD($fecha);
                            mensaje('$fecha: '.$fecha);
                            $data = array(
                                'order_no' => $v[0],
                                'valid' => $v[1],
                                'customer_id_grup' => $v[2],
                                'cliente_sin_iva' => $clienteSinIVA,
                                'product_quantity' => $v[3] * 1000,
                                'product_ref' => $codigo_producto,
                                'product_name' => $v[5],
                                'product_price' => $v[6] * 1000,
                                'total_discounts' => $v[7] * 1000,
                                'total_products' => $v[8] * 1000,
                                'total_shipping' => $v[10] * 1000,
                                'date_added' => $fecha,
                                'componente_pack' => 0,
                            );

                            $this->db->insert('pe_datos_prestashop', $data);

                            if ($esPack) {
                                $v[6] = 0;
                            }

                            $tipoCliente = $v[2];
                            $clienteSinIVA = ($v[8] == $v[11]) ? 1 : 0;
                            if ($clienteSinIVA) $iva = 0;
                            $datosValidos[] = array(
                                'esPack' => $esPack,
                                'esComponentePack' => 0,
                                'pedido' => $v[0],
                                'valid' => $v[1],
                                'tipoCliente' => $tipoCliente,
                                'clienteSinIVA' => $clienteSinIVA,
                                'descuento' => $v[7],
                                'precio' => $v[6],
                                'total' => $v[8],
                                'transporte' => $transporte,
                                'cantidad' => $cantidad,
                                'tipo_iva' => $iva,
                                'importe' => $importe,
                                'codigo' => $codigo_producto,
                                'tarifaPVPVenta' => $tarifaVenta,
                                'tarifaPVPReal' => $tarifaPVPReal,
                                'comparar' => $comparar,
                                'fecha' => $fecha,
                                'customer_email' => $v[13],
                                'customer_id_language' => $v[14],
                                'reference' => $v[15],
                                'delivery_firstname' => $v[16],
                                'delivery_lastname' => $v[17],
                                'delivery_country' => $v[18],
                                'shop_name' => $v[19],
                                'customer_id' => $v[20],
                                'delivery_address_line_1'=>$v[21],
                                'delivery_address_line_2'=>$v[22],
                                'delivery_postcode'=>$v[23],
                                'delivery_city'=>$v[24],
                            );
                        }
                        /*
                               && $primeraFila[0][13]=='Customer Email'
                    && $primeraFila[0][14]=='Customer id language'
                    && $primeraFila[0][15]=='Reference'
                    && $primeraFila[0][16]=='Delivery Firstname'
                    && $primeraFila[0][17]=='Delivery Lastname'
                    && $primeraFila[0][18]=='Delivery country'
                    && $primeraFila[0][19]=='Shop name'
                                */
                        if ($esPack) {

                            $datosPack = $this->productos_->getDatosProducto($codigo_producto);

                            $cantidadPack = $cantidad;
                            $tarifa_venta = round($datosPack->tarifa_venta / 1000, 2) * 1000;
                            $pvpPack = $tarifa_venta * $cantidadPack;
                            $tarifaPVPRealPack = $tarifaPVPReal * 1000;
                            $factor = $tarifaPVPRealPack / $tarifa_venta;
                            $cantidad = 0;
                            /*
                                    echo '$cantidadPack '.$cantidadPack.'<br>';
                                    echo '$datosPack->tarifa_venta '.$datosPack->tarifa_venta.'<br>';
                                    echo '$tarifaPVPRealPack '.$tarifaPVPRealPack.'<br>';
                                    echo '$factor '.$factor.'<br>';
                                    echo '--------------------------------------<br>';
                                    */
                            $componentes = $this->productos_->getComponentesPack($codigo_producto);
                            if (!$componentes) {
                                $packSinComponentes[] = $codigo_producto;
                                continue;
                            }

                            $totalImportes = 0;
                            foreach ($componentes as $kc => $vc) {

                                $codigo_producto = $vc['codigo_producto'];
                                $codigoEncontrados[] = $codigo_producto;
                                $codigos[] = $codigo_producto;
                                $nombre = $this->productos_->getNombre($this->productos_->getId_pe_producto($vc['codigo_producto']));
                                $nombres[] = '------ ' . $nombre;

                                $tarifaVenta = $vc['tarifaVenta'];
                                //$tarifaVenta=round($tarifaVenta,2);
                                // echo '$tarifaVenta '.$tarifaVenta;

                                $tarifasVenta[] = $tarifaVenta;
                                $validos[] = $valid;
                                $pedidos[] = $pedido;
                                $cantidad = $vc['cantidad'] / 1000 * $cantidadPack;
                                $cantidades[] = $cantidad;
                                $importe = round($cantidad * $tarifaVenta * $factor, 2);
                                if ($valid == 0) {
                                    $importe = 0; //pedido pendiente pago ($valid==0)
                                    $transporte = 0;
                                }
                                $importes[] = $importe;
                                $totalImportes += $importe;
                                $codigosPack[] = false;
                                $iva = 0;
                                if (!$clienteSinIVA)
                                    $iva = $this->productos_->getIva(trim($codigo_producto))->valor_iva;
                                $data = array(
                                    'order_no' => $pedido,
                                    'valid' => $valid,
                                    'customer_id_grup' => $v[2],
                                    'product_quantity' => $cantidad * 1000,
                                    'cliente_sin_iva' => $clienteSinIVA,
                                    'product_ref' => $codigo_producto,
                                    'product_name' => $nombre,
                                    'product_price' => $tarifaVenta * 1000,
                                    'total_discounts' => intval($v[7]) * 1000,
                                    'total_products' => $tarifaPVPRealPack,
                                    'total_shipping' => intval($v[10]) * 1000,
                                    'date_added' => $fecha,
                                    'componente_pack' => 1,
                                );
                                //  echo '<br>'.$data['order_no'].'  '.$data['product_name'].' - '.$data['componente_pack'];
                                $this->db->insert('pe_datos_prestashop', $data);

                                // echo '<br>descuento '.$v[7];

                                $descuento = 0;  //$tarifaVenta*intval($v[7])/100*$factor;

                                $datosValidos[] = array(
                                    'esPack' => 0,
                                    'esComponentePack' => 1,
                                    'pedido' => $v[0],
                                    'valid' => $v[1],
                                    'tipoCliente' => $v[2],
                                    'clienteSinIVA' => $clienteSinIVA,
                                    'descuento' => $descuento,
                                    'precio' => round(1 * $tarifaVenta * $factor, 2),
                                    'total' => $v[8],
                                    'transporte' => $transporte,
                                    'cantidad' => $cantidad,
                                    'tipo_iva' => $iva,
                                    'importe' => $importe,
                                    'codigo' => $codigo_producto,
                                    'tarifaPVPVenta' => $tarifaPVPReal,
                                    'tarifaPVPReal' => $tarifaPVPReal,
                                    'comparar' => $comparar,
                                    'fecha' => $fecha,
                                    'customer_email' => $v[13],
                                    'customer_id_language' => $v[14],
                                    'reference' => $v[15],
                                    'delivery_firstname' => $v[16],
                                    'delivery_lastname' => $v[17],
                                    'delivery_country' => $v[18],
                                    'shop_name' => $v[19],
                                    'customer_id' => $v[20],
                                    'delivery_address_line_1'=>$v[21],
                                'delivery_address_line_2'=>$v[22],
                                'delivery_postcode'=>$v[23],
                                'delivery_city'=>$v[24],
                                );
                            }
                        }
                    }
                }
            }
        }

        //$analisisDatos=$this->prestashop_model->analisisDatos();

        $this->codigosId_productos = array();
        $this->tarifasVentaPrestaShop = array();
        $this->comparar = array();

        if (count($codigoNoEncontrados) > 0) {
            $codigos = implode('<br > ', $codigoNoEncontrados);
            $resultado = "<span style='color:red;'>El archivo no se ha podido subir y registrar. <br>Los siguientes productos<br>- NO están dados de alta en la base de datos de productos<br>- o están descatalogados<br>- o tienen el control de stock en No:<br>
                <br> $codigos <br /><br>Introducirlo en la base de datos y volver a cargar el archivo de PrestaShop</span>";
            return array('resultadosStocksTotales' => '', 'email' => true, 'codigoNoEncontrados' => $codigoNoEncontrados, 'packSinComponentes' => $packSinComponentes, 'productoNoExistente' => $codigo_producto, 'fecha' => $fecha, 'fechaArchivo' => '', 'datosArchivo' => $datosArchivo, 'resultado' => $resultado, 'codigos' => array(), 'nombres' => array(), 'cantidades' => array(), 'importes' => array(), 'resultadoPreciosTarifas' => '', 'tablaImportes' => '', 'error' => 5, 'errorSubida' => 5);
        }

        if (count($packSinComponentes) > 0) {
            $codigos = implode('<br > ', $packSinComponentes);
            $resultado = "<span style='color:red;'>No se ha registrado el archivo. <br />No se han encontrado la composición de los Packs de códigos <br> $codigos <br />Introducirlos en la base de datos Pack productos y volver a cargar el archivo de PrestaShop</span>";
            return array('resultadosStocksTotales' => '', 'email' => true, 'codigoNoEncontrados' => $codigoNoEncontrados, 'packSinComponentes' => $packSinComponentes, 'productoNoExistente' => $codigo_producto, 'fecha' => $fecha, 'fechaArchivo' => '', 'datosArchivo' => $datosArchivo, 'resultado' => $resultado, 'codigos' => array(), 'nombres' => array(), 'cantidades' => array(), 'importes' => array(), 'resultadoPreciosTarifas' => '', 'tablaImportes' => '', 'error' => 5, 'errorSubida' => 7);
        }

        if (count($pedidosSinFecha) > 0) {
            $pedidos = implode('<br > ', $pedidosSinFecha);
            $resultado = "<span style='color:red;'>No se ha registrado el archivo. <br />Los siguientes pedidos NO tienen fecha <br> $pedidos <br />Introducirlas en el archivo de PrestaShop y volverlo a subir</span>";
            return array('resultadosStocksTotales' => '', 'email' => true, 'codigoNoEncontrados' => array(), 'productoNoExistente' => '', 'fecha' => $fecha, 'fechaArchivo' => '', 'datosArchivo' => $datosArchivo, 'resultado' => $resultado, 'codigos' => array(), 'pedidosSinFecha' => $pedidosSinFecha, 'nombres' => array(), 'cantidades' => array(), 'importes' => array(), 'resultadoPreciosTarifas' => '', 'tablaImportes' => '', 'error' => 5, 'errorSubida' => 6);
        }
        $message = $this->getMessageErrorIvas();
        if ($message != "") {
            $resultado = $message;
 // se elimina este return para evitar el error en report facturas con descuentos ver email enviado a Alex el 08/07/2020			
 //           return array('erroresIva' => true, 'resultadosStocksTotales' => '', 'email' => true, 'codigoNoEncontrados' => array(), 'productoNoExistente' => '', 'fecha' => $fecha, 'fechaArchivo' => '', 'datosArchivo' => $datosArchivo, 'resultado' => $resultado, 'codigos' => array(), 'pedidosSinFecha' => '', 'nombres' => array(), 'cantidades' => array(), 'importes' => array(), 'resultadoPreciosTarifas' => '', 'tablaImportes' => '', 'error' => 5, 'errorSubida' => 6);
        }

        // var_dump($datosValidos);

        //insertamos los datos
        //var_dump($datosValidos);
        $this->load->model('stocks_model');


        //var_dump($datosValidos) ;

        //var_dump($datosValidos);
        foreach ($datosValidos as $kd => $vd) {

            extract($vd);
            // mensaje('clienteSinIVA '.$clienteSinIVA);
            $tVenta = $tarifaPVPVenta * 100;
            $tReal = $tarifaPVPReal * 100;

            $id_pe_producto = $this->productos_->getId_pe_producto($codigo);
            $this->codigosId_productos[] = $id_pe_producto;
            $this->tarifasVentaPrestaShop[] = $tarifaPVPVenta;
            $this->comparar[] = $comparar;



            if ($valid == 1) {
                $sql = "SELECT id,id_pe_producto,cantidad FROM pe_lineas_orders_prestashop WHERE id_order='$pedido' AND valid='0'";
                if ($this->db->query($sql)->num_rows() > 0) {
                    //el pedido tenía lineas con valid = 0, con cantidades retenidas
                    //se reponen las cantidades,
                    $result = $this->db->query($sql)->result();
                    foreach ($result as $kr => $vr) {
                        $cantidad1 = $vr->cantidad;
                        $id_pe_producto1 = $vr->id_pe_producto;
                        $id1 = $vr->id;
                        $this->stocks_model->sumaCantidadStocks($id_pe_producto1, +$cantidad1 * 1000, $fecha_caducidad_stock = "0000-00-00");
                        $this->db->query("DELETE FROM pe_lineas_orders_prestashop WHERE id='$id1'");
                    }
                }
            }

            //pedidos dados de baja 
            if ($valid == -1) {

                //$this->db->query("DELETE FROM pe_datos_prestashop  WHERE order_no= '$pedido'");
                //echo '$cantidad '.$cantidad.'<br>';
                //echo $pedido.' '.$codigo.' '.$cantidad.'<br>';
                $resultLineas = $this->prestashop_model->getPedidoActualLineas($pedido, $codigo, $cantidad);


                if ($resultLineas == 0) {
                    $resultado = "<span style='color:red;'>El pedido $pedido, con valid = -1 <br />No se han encontrad y por lo tanto NO se puede anular. Revisar el archivo de retorno y volverlo a subir</span>";

                    return array('pedidoNoExistente' => true, 'resultadosStocksTotales' => '', 'email' => true, 'codigoNoEncontrados' => array(), 'productoNoExistente' => '', 'fecha' => $fecha, 'fechaArchivo' => '', 'datosArchivo' => $datosArchivo, 'resultado' => $resultado, 'codigos' => array(), 'pedidosSinFecha' => '', 'nombres' => array(), 'cantidades' => array(), 'importes' => array(), 'resultadoPreciosTarifas' => '', 'tablaImportes' => '', 'error' => 5, 'errorSubida' => 6);
                }

                if (count($resultLineas) == 0) {
                    //si no exixte, no se hace nada
                    continue;
                }
                foreach ($resultLineas as $k => $v) {

                    $id = $v->id;
                    if ($v->valid == 0) {
                        //echo 'v->valid==0 <br>';
                        //se restituye stock
                        //$cantidad2=$v->cantidad;
                        //echo '$cantidad '.$cantidad.'<br>';
                        //echo '$v->cantidad '.$v->cantidad.'<br>';
                        $cantidad = $v->cantidad;
                        if ($vd['esPack'] == 1) $cantidad = 0;
                        //$this->stocks_model->sumaCantidadStocks($id_pe_producto,+$cantidad*1000,$fecha_caducidad_stock="0000-00-00");
                        //se anota pedido como -1 anulado

                        //echo "DELETE FROM pe_lineas_orders_prestashop  WHERE id= '$id' <br>";
                        $this->db->query("DELETE FROM pe_lineas_orders_prestashop  WHERE id= '$id'");
                    }


                    if ($v->valid == 1) {
                        //producto en pedido vendido, y ahora anulado
                        //$cantidad=$resultLineas['cantidad'];
                        if ($vd['esPack'] == 1) $cantidad = 0;
                        //restitución cantidad
                        $this->stocks_model->sumaCantidadStocks($id_pe_producto, +$cantidad * 1000, $fecha_caducidad_stock = "0000-00-00");
                        //se anota pedido como -1 anulado y cantidad =0 
                        //$id=$resultLineas['id'];
                        $this->db->query("DELETE FROM pe_lineas_orders_prestashop  WHERE id= '$id'");
                        $this->db->query("DELETE FROM pe_orders_prestashop WHERE id= '$pedido'");
                    }
                }
                // acciones para eliminar
                //continue;
            }

            //var_dump($vd);
            //echo '<br>';

            if ($vd['esPack'] == 1) $cantidad = 0;
            //ajusta stocks productos pedido
            if ($valid == 0 || $valid == 1) {
                $this->stocks_model->sumaCantidadStocks($id_pe_producto, -$cantidad * 1000, $fecha_caducidad_stock = "0000-00-00");
                $tipoTienda = 2;  //prestashop
                $this->stocks_model->sumaCantidadStocksEmbalajes($id_pe_producto, $cantidad * 1000, $tipoTienda);
            }
            if ($valid == -1) {
                $this->stocks_model->sumaCantidadStocks($id_pe_producto, +$cantidad * 1000, $fecha_caducidad_stock = "0000-00-00");
                $tipoTienda = 2;  //prestashop
                $this->stocks_model->sumaCantidadStocksEmbalajes($id_pe_producto, -$cantidad * 1000, $tipoTienda);
            }

            $this->grabarOrdersPrestashop($id_pe_producto, $vd, $fecha);

            //registra la venta con los precios, tarifa, embalajes del momento
            //var_dump($vd);
            $this->productos_->registrarVentaPrestashop($vd);
        }

        //ajustar suma transporte-producto con transporte-pedido
        $pedidosReg = array();
        foreach ($datosValidos as $kd => $vd) {
            $pedidosReg[$vd['pedido']] = $vd['pedido'];
        }
        foreach ($pedidosReg as $kd => $vd) {
            $transporte = $this->db->query("SELECT transporte FROM pe_orders_prestashop WHERE id='$vd'")->row()->transporte;
            $sumaTransporte = $this->db->query("SELECT sum(total_transporte) as suma_transporte FROM pe_registro_ventas WHERE num_ticket='$vd' and tipo_tienda=2")->row()->suma_transporte;
            if ($transporte != $sumaTransporte) {
                $diferencia = $sumaTransporte - $transporte;
                if ($this->db->query("SELECT id,total_transporte FROM pe_registro_ventas WHERE num_ticket='$vd' and tipo_tienda=2 ORDER BY total_transporte DESC LIMIT 1")->num_rows()) {
                    $id = $this->db->query("SELECT id,total_transporte FROM pe_registro_ventas WHERE num_ticket='$vd' and tipo_tienda=2 ORDER BY total_transporte DESC LIMIT 1")->row()->id;
                    $this->db->query("UPDATE pe_registro_ventas SET total_transporte=total_transporte-$diferencia WHERE id='$id'");
                }
            }
        }




        $this->load->model('prestashop_model');

        $sql = "SELECT min(order_no) as desde, max(order_no) as hasta FROM pe_datos_prestashop ";
        //$row=$this->db->query($sql)->row();

        // $resultadoImportes=$this->prestashop_model->getVentasPorTiposIva($row->desde,$row->hasta);

        $this->putBaseEIva();
        $this->putBaseEIvaTransporte();
        $this->grabarBasesTotales();


        $resultadoPreciosTarifas = $this->comprobacionPreciosTarifasPrestaShop();
        $resultadosStocksTotales = $this->comprobacionStocksTotalesPrestaShop();
        //var_dump($resultadoPreciosTarifas);

        $resultado = "Archivo Prestashop incorporado";

        //igualar total_productos de pe_ordes _prestashop com ingresado en prodyctos pe_registro_ventas
        foreach ($pedidosReg as $kd => $vd) {
            $sql = "SELECT total_producto FROM pe_orders_prestashop WHERE id='$vd'";
            $totalProducto = $this->db->query($sql)->row()->total_producto;
            $sumaProducto = $this->db->query("SELECT sum(ingresado) as suma_ingresado FROM pe_registro_ventas WHERE num_ticket='$vd' and tipo_tienda=2")->row()->suma_ingresado;
            if ($totalProducto != $sumaProducto) {
                $diferencia = $sumaProducto - $totalProducto;
                if ($this->db->query("SELECT id,ingresado FROM pe_registro_ventas WHERE num_ticket='$vd' and tipo_tienda=2 ORDER BY ingresado DESC LIMIT 1")->num_rows()) {
                    $id = $this->db->query("SELECT id,ingresado FROM pe_registro_ventas WHERE num_ticket='$vd' and tipo_tienda=2 ORDER BY ingresado DESC LIMIT 1")->row()->id;
                    $this->db->query("UPDATE pe_registro_ventas SET ingresado=ingresado-$diferencia WHERE id='$id'");
                }
            }
        }

        if (!isset($fechaArchivo)) $fechaArchivo = date('d-m-Y');
        return array('resultadosStocksTotales' => $resultadosStocksTotales, 'email' => true, 'codigoEncontrados' => $codigoEncontrados, 'codigoNoEncontrados' => $codigoNoEncontrados, 'productoNoExistente' => '', 'fecha' => $fecha, 'fechaArchivo' => $fechaArchivo, 'datosArchivo' => $datosArchivo, 'resultado' => $resultado, 'codigosPack' => $codigosPack, 'codigos' => $codigos, 'pedidos' => $pedidos, 'validos' => $validos, 'nombres' => $nombres, 'cantidades' => $cantidades, 'importes' => $importes, 'resultadoPreciosTarifas' => $resultadoPreciosTarifas, 'error' => $error,);
    }

    public function getDatosTransportes($datosArchivo)
    {

        //MABA Para eliminar MABA
        //$this->db->query("DELETE FROM `pe_lineas_orders_prestashop` WHERE `id_order`>=15528");
        //$this->db->query("DELETE FROM `pe_orders_prestashop` WHERE id>=15528");
        //MABA fin 

        $this->load->model('prestashop_model');
        $this->load->library('excel');
        $codigos = array();
        $pedidos = array();
        $validos = array();
        $nombres = array();
        $cantidades = array();
        $importes = array();
        $transportes = array();
        $codigosPack = array();
        $tarifasVenta = array();
        $error = 0;

        $inputFileName = 'uploads/prestashop/' . $datosArchivo['raw_name'] . $datosArchivo['file_ext'];
        //comprobar que el archivo sea Excel
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            $resultado = "NO es un archivo de importación de PrestaShop ($e)";
            $error = 3;
            return array('noExcel' => '', 'datosArchivo' => $datosArchivo);
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();



        ini_set('max_execution_time', 300);

        $primeraFila = $sheet->rangeToArray(
            'A1:K1',
            null,
            true,
            false
        );

        //Comprobar que la primera columna tenga los titulos adecuados
        if (true) {
            if (!($primeraFila[0][0] == 'Order No' && $primeraFila[0][1] == 'Valid'
                && $primeraFila[0][2] == 'Customer id grup'
                && $primeraFila[0][3] == 'Product Quantity'
                && $primeraFila[0][4] == 'Product Ref'
                && $primeraFila[0][5] == 'Product Name'
                && $primeraFila[0][6] == 'Product Price - PVP - iva incl'
                && $primeraFila[0][7] == 'Total discounts'
                && $primeraFila[0][8] == 'Total products with tax'
                && $primeraFila[0][9] == 'Total shipping'
                && $primeraFila[0][10] == 'Date added')) {
                $resultado = "NO es un archivo de importación de PrestaShop";
                $error = 4;
                return array('noArchivoPrestashop' => true, 'datosArchivo' => $datosArchivo);
            }
        }



        //  Loop through each row of the worksheet in turn
        $codigoEncontrados = array();
        $codigoNoEncontrados = array();
        $pedidosSinFecha = array();
        $pedidosYaExistentes = array();
        $datosValidos = array();
        $this->load->model('productos_');

        //se comprueba si los pedidos ya existen
        //chequeamos si hay más pedidos con el mismo número, pero distinto valor de valid
        /*
            if(true){
            for ($row = 2; $row <= $highestRow; $row++){ 
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);
                
                $pedido1=$rowData[0][0];
                $valid=$rowData[0][1];
                $sql="SELECT o.id, fecha "
                        . " FROM pe_orders_prestashop o"
                        . " LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id"
                        . " WHERE o.id='$pedido1' AND l.valid='$valid'";
                $n=$this->db->query($sql)->num_rows();
                if($n>0){
                        if(!in_array($pedido1,$pedidosYaExistentes))
                            $pedidosYaExistentes[]=$pedido1;
                }
                
            }
            if(count($pedidosYaExistentes)) 
                return array('pedidosYaExistentes'=>$pedidosYaExistentes,'datosArchivo'=>$datosArchivo);
            }
            */
        //tabla provisional para gestionar entradas
        $this->db->empty_table('pe_datos_transportes');

        for ($row = 2; $row <= $highestRow; $row++) {

            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);

            // var_dump($rowData);
            foreach ($rowData as $k => $v) {
                // $salida[]= $v[0].' '.$v[2].'  '.$v[3];
                // if($v[1]){   //si $v[1]=0, no se considera

                $data = array(
                    'id_order' => $v[0],
                    'descuento' => $v[7] * 100,
                    'total' => $v[8] * 100,
                    'transporte' => $v[9] * 100,
                );

                $this->db->insert('pe_datos_transportes', $data);
            }
        }
        $sql = "SELECT * FROM pe_datos_transportes WHERE 1 GROUP BY id_order";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $id = $v->id_order;
            $transporte = $v->transporte * 10;
            $sql = "UPDATE pe_orders_prestashop SET transporte='$transporte' WHERE id='$id'";
            $this->db->query($sql);
        }
    }


    function grabarOrdersPrestashop($id_pe_producto, $v, $fecha)
    {
        //echo $v['clienteSinIVA'];
        //echo '.----------------------------------';
        extract($v);
        // mensaje($fecha);
        // mensaje($customer_email);
        // mensaje('clienteSinIVA '.$v['clienteSinIVA']);
        // mensaje('clienteSinIVA '.$clienteSinIVA);
        // echo '$pedido = '.$pedido.' $valid = '.$valid.'<br>';
        // echo 'pedido '.$pedido.'<br>';
        //  echo 'tipo_iva '.$tipo_iva.'<br>';
        $sql = "SELECT * FROM pe_orders_prestashop WHERE id='$pedido'";
        if ($this->db->query($sql)->num_rows() > 0) {
            $this->db->query("DELETE FROM pe_orders_prestashop WHERE id='$pedido'");
        }
        $sql = "SELECT * FROM pe_orders_prestashop WHERE id='$pedido'";
        if ($this->db->query($sql)->num_rows() == 0) {
            if ($valid <= 0) {
                $descuento = 0;
                $total = 0;
            }
            $transporte_original_igual_descuento = 0;
            if ($descuento * 1000 == $transporte) {
                $transporte_original_igual_descuento = $transporte;
                $descuento = 0;
                $transporte = 0;
            }
            $descuento = $descuento * 100;
            $total = $total * 100;
            // echo '<br><br>'.$total.'<br>';
            //echo $pedido.' '.$clienteSinIVA.'  ';
            $tipoIvaTransporte = $clienteSinIVA == 1 ? 0 : 21000;
            //echo $tipoIvaTransporte.'  <br>';
            $delivery_firstname = str_replace("'", "´", $delivery_firstname);
            $delivery_lastname = str_replace("'", "´", $delivery_lastname);
            $delivery_country = str_replace("'", "´", $delivery_country);
            $customer_id_language = str_replace("'", "´", $customer_id_language);
			$delivery_address_line_1 = str_replace("'", "´", $delivery_address_line_1);
            $delivery_address_line_2 = str_replace("'", "´", $delivery_address_line_2);
            $delivery_postcode = str_replace("'", "´", $delivery_postcode);
            $delivery_city = str_replace("'", "´", $delivery_city);
            $sql = "INSERT INTO pe_orders_prestashop
                    SET id='$pedido',
                        pedido='$pedido',
                        customer_id_group='$tipoCliente',
                        tipo_iva_transporte='$tipoIvaTransporte',
                        descuento='$descuento',
                        fecha='$fecha', 
                        total='$total',"
                . " transporte='$transporte',"
                . " transporte_original_igual_descuento='$transporte_original_igual_descuento',
                            customer_email='$customer_email',
                            customer_id_language='$customer_id_language',
                            reference='$reference',
                            delivery_firstname='$delivery_firstname',
                            delivery_lastname='$delivery_lastname',
                            delivery_country='$delivery_country',
                            shop_name='$shop_name',
                            customer_id='$customer_id',
                            delivery_address_line_1='$delivery_address_line_1',
                            delivery_address_line_2='$delivery_address_line_2',
                            delivery_postcode='$delivery_postcode',
                            delivery_city='$delivery_city'
                            ";
            //echo $sql;            
            $this->db->query($sql);
            //ponemos datos en tabla clientes jamonarium
            $set = " pe_clientes_jamonarium SET id='$customer_id', firstname='$delivery_firstname', lastname='$delivery_lastname', email='$customer_email', id_lang='$customer_id_language', shop_name='$shop_name', country='$delivery_country' ";
            if ($this->db->query("SELECT id FROM pe_clientes_jamonarium WHERE id='$customer_id'")->num_rows() == 1) {
                $this->db->query("UPDATE " . $set . " WHERE id='$customer_id'");
            } else {
                $this->db->query("INSERT INTO " . $set);
            }
        }

        $precio *= 100;
        $tipo_iva *= 100;
        $importe *= 100;
        $iva = round($importe - $importe / (1 + $tipo_iva / 10000), 0);
        if ($valid <= 0) {
            $precio = 0;
            $importe = 0;
            $iva = 0;
        }
        $sql = "INSERT INTO pe_lineas_orders_prestashop
                        SET id_order='$pedido',
                            valid='$valid',
                            id_pe_producto='$id_pe_producto',
                            cantidad='$cantidad',
                            precio='$precio',
                            tipo_iva='$tipo_iva',
                            importe='$importe',
                            iva='$iva',
                            es_pack='$esPack',
                            es_componente_pack='$esComponentePack'

                            ";
        /*
                            'customer_email'=>$v[13],
                                        'customer_id_language'=>$v[14],
                                        'reference'=>$v[15],
                                        'delivery_firstname'=>$v[16],
                                        'delivery_lastname'=>$v[17],
                                        'delivery_country'=>$v[18],
                                        'shop_name'=>$v[19], 
                            */
        $this->db->query($sql);
    }

    function grabarBasesTotales($pedido = 0)
    {
        $this->load->model('compras_model');
        $sql = "SELECT order_no FROM pe_datos_prestashop WHERE 1 GROUP BY order_no";
        $result = $this->db->query($sql)->result();
        foreach ($result as $k => $v) {
            $pedido = $v->order_no;

            $r = $this->compras_model->getPedidoPrestashop($pedido);
            /*
                    $total_producto=$r['precioIvasTotal']*100;
                    $total_base=$r['baseTotal']*100;
                    $total_iva=$r['ivasTotal']*100;
                    $total_pedido=$r['totalPedido']*1000;
                    */
            $total_producto = 0;
            $total_base = 0;
            $total_iva = 0;
            $sql = "SELECT sum(importe_con_descuento) as total_producto, sum(base_con_descuento) as total_base, sum(iva_con_descuento) as total_iva FROM pe_lineas_orders_prestashop WHERE id_order='$pedido' ";

            if ($this->db->query($sql)->num_rows() != 0) {
                $row = $this->db->query($sql)->row();
                $total_producto = $row->total_producto;
                $total_base = $row->total_base;
                $total_iva = $row->total_iva;
            }
            if (is_null($total_producto)) $total_producto = 0;
            if (is_null($total_base)) $total_base = 0;
            if (is_null($total_iva)) $total_iva = 0;


            $sql = "UPDATE pe_orders_prestashop SET total_pedido=$total_producto*10+transporte, total_producto='$total_producto', total_base='$total_base', total_iva='$total_iva' WHERE id='$pedido'";


            $this->db->query($sql);
        }
    }
}
