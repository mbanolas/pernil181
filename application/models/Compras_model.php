<?php
class Compras_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->load->library('excel');
                $this->load->library('exceldrawing');
                $this->load->model('tickets_');
                $this->load->model('clientes_');
                $this->load->model('upload_');
                $this->load->model('productos_');
                
        }
        

        function cancelarTransformacion($id_transformacion){
            $this->db->query("DELETE FROM pe_transformaciones WHERE id_transformacion='$id_transformacion'");
            return $this->db->query("DELETE FROM pe_lineas_transformaciones WHERE id_transformacion='$id_transformacion'");
            
        }
        
        function calculoPreciosCompraTransformaciones($lineas,$patron){
            //comprobamos si los productos producidos pertenecen al mismo grupo y familia
            $grupo="";
            //$familia="";
            foreach($lineas as $k=>$v){
                if($v['cantidad']>0) {
                    $grupoFamilia=$this->productos_->getNombresGrupoFamilia($v['codigo_producto']);
                    if(!$grupo ){
                        $grupo=$grupoFamilia['grupo'];
                        //$familia=$grupoFamilia['familia'];
                    }
                    else{
                        if($grupoFamilia['grupo']!=$grupo ) return false;
                    }
                    }
                }
            
            
            $resultado="<table style='background-color:lightblue;border:2px solid blue;'>";
            $resultado.="<tr>";
                $resultado.="<th style='text-align:left;'>"."Código 13"."</th>";
                $resultado.="<th class='izquierda' style='text-align:left;'>"."Producto"."</th>";
                $resultado.="<th class='izquierda' style='text-align:left;'>".""."</th>";
                $resultado.="<th class='' style='text-align:right;'>"."Cantidad"."</th>";
                
                $resultado.="<th class='izquierda'>"."%_Pérdida"."</th>";
                $resultado.="<th class='izquierda'>"."Cant_Origen"."</th>";
                $resultado.="<th style='text-align:right;'>"."Peso/unidad"."</th>";
                $resultado.="<th style='text-align:right;'>"."Precio_Actual"."</th>";
                $resultado.="<th style='text-align:right;'>"."Precio_Calculado"."</th>";
                $resultado.="<th style='text-align:right;'>"."Fecha Caducidad"."</th>";
                $resultado.="</tr>"; 
                $consumidos=0;
                $producidos=0;
             foreach($lineas as $k=>$v){
                 
                 $precioActual[$k]=$this->productos_->getPrecioCompra($v['codigo_producto']); 
                 $unidad[$k]=$this->productos_->getUnidad($this->productos_->getDatosProducto($v['codigo_producto'])->id);
                 $result=$this->productos_->getDatosProducto($v['codigo_producto']);
                 $pesoUnidad[$k]=$result->peso_real;
                 $unidades_precio[$k]=$result->unidades_precio/1000;
                 $cantidad[$k]=$v['cantidad'];
                 $perdida[$k]=$v['perdida'];
                 $cantidadOrigen[$k]=$cantidad[$k]+$cantidad[$k]*$perdida[$k]/100;
                 if($cantidad[$k]>0) $producidos++;
                 if($cantidad[$k]<0) $consumidos++;
                 $precioNuevo[$k]=0;
                 //si el producto transformado ($cantidad[$k]>0) ytiene unidad Kg, se le asigna el peso = cantidad 
                 if($cantidad[$k]>0 && $unidad[$k]=='Kg') $pesoUnidad[$k]=1000; //el peso de la unidad es 1.000 Kg
                 
             }  
             $coste=0;
             if(true || ($consumidos==1 && $producidos>0)){
                 $totalProducido=0;
                 $totalPeso=0;
                 foreach($lineas as $k=>$v){
                     if($cantidad[$k]<0 ){
                         //se trata de un producto consumido
                         $tipo=1;
                         $precioNuevo[$k]='---';
                         $coste+=-$cantidadOrigen[$k]*$precioActual[$k];
                     }
                     else{
                         //se trata de un producto producido
                         $totalProducido+=$cantidad[$k];
                         $totalPeso+=$cantidad[$k]*$pesoUnidad[$k];
                     }
                 }
                 foreach($lineas as $k=>$v){
                     if($cantidad[$k]>0 ){
                         //se trata de un producto producido
                         if(!$pesoUnidad[$k]) $pesoUnidad[$k]=1;
                         $precioNuevo[$k]=0;
                         if($cantidad[$k]!=0)
                             if($pesoUnidad[$k]*$totalPeso!=0)  //para evitar div /0
                                //$precioNuevo[$k]=round($coste/$cantidad[$k]*($pesoUnidad[$k]*$cantidad[$k]/$totalPeso),0);
                                $precioNuevo[$k]= (($coste*$pesoUnidad[$k]*$cantidadOrigen[$k])/$totalPeso)/$cantidad[$k];
                                //$precioNuevo[$k]=round($coste/$cantidad[$k]*($pesoUnidad[$k]*$cantidad[$k]/$totalPeso),0);
                             else 
                                $precioNuevo[$k]='---'; 
                         }
                 }
                 
             }
             
                
            foreach($lineas as $k=>$v){
                if($patron || $v['cantidad']!=0){
                $color="grey";
                $bold="font-weight:normal;";
                if($v['cantidad']>0) {$color="blue";$bold="font-weight:bold;";}
                if($v['cantidad']<0) {$color="red";}
                $nombre=trim($this->productos_->getDatosProducto($v['codigo_producto'])->nombre);
                $resultado.="<tr style='color:".$color.";'>";
                $resultado.="<td class='codigo_producto_tabla_precios' >".$v['codigo_producto']."</td>";
                $resultado.="<td class='izquierda nombre_tabla_precios'>".$nombre."</td>";
                $resultado.="<td class='izquierda tipo_unidad_tabla_precios' style='text-align:right;'>".$unidad[$k]."</td>";
                //cantidad y unidad
                if($unidad[$k]=='Kg') 
                    $resultado.="<td class='cantidad_tabla_precios' style='text-align:right;'>".number_format($v['cantidad'],3)."</td>";
                else 
                    $resultado.="<td class='cantidad_tabla_precios' style='text-align:right;'>".number_format($v['cantidad'],0)."</td>";
                
                if($unidad[$k]=='Kg')
                    $resultado.="<td class='perdida_tabla_precios' style='text-align:right;'>".number_format($v['perdida'],2)."</td>";
                else 
                   $resultado.="<td class='perdida_tabla_precios' style='text-align:right;'>".""."</td>";

                //cantidadOriginal y unidad
                if($unidad[$k]=='Kg') 
                    $resultado.="<td class='cantidad_origen_tabla_precios' style='text-align:right;'>".number_format($cantidadOrigen[$k],3)."</td>";
                else 
                    $resultado.="<td class='cantidad_origen_tabla_precios' style='text-align:right;'>".''."</td>";
              
                
                //peso unidad
                if($unidad[$k]=='Kg') 
                    $resultado.="<td class='peso_unidad_tabla_precios' style='text-align:right;'>".number_format($pesoUnidad[$k]/1000,3)."</td>";
                else if($cantidad[$k]>0) $resultado.="<td class='peso_unidad_tabla_precios' style='text-align:right;'>".number_format($pesoUnidad[$k]/1000,3)."</td>";
                else $resultado.="<td class='peso_unidad_tabla_precios' style='text-align:right;'>".''."</td>";
                
                //precio actual
                $resultado.="<td class='precioActual' style='text-align:right;'>".number_format($precioActual[$k]/1000,3)."</td>";
                
                //precio calculado
                if($cantidad[$k]<0)
                    $resultado.="<td class='precioNuevo' style='text-align:right;'>".'---'."</td>";
                else{
                    $resultado.="<td class='precioNuevo' style='text-align:right;$bold'>".number_format($precioNuevo[$k]/1000,3)."</td>";
                    $resultado.="<td class='fechaCaducidad' style='text-align:right;$bold'>".$v['fechaCaducidad']."</td>";
                }
                $resultado.="</tr>";   
                
                }  
            }
            $resultado.="</table>";
            $resultado.="<table>";
            $resultado.="<tr><th style='text-align:left;'>Total coste</th><th>". number_format($coste/1000,3)."</th></tr>";
            $resultado.="<tr><th style='text-align:left;'>Total peso producido</th><th>". number_format($totalPeso/1000,3)."</th></tr>";
            $resultado.="</table>";
            
            
            foreach($lineas as $k=>$v){
               // $resultado.="<input type='hidden' value='$precioNuevo[$k]' class='precioNuevo' >";
            }
            //$resultado.=" <br> Registrar precios compra calculados: <input type='checkbox'  id='registrarPrecioNuevo' disabled>";
            return $resultado;
        }
        
        function agrupar_proveedores_acreedores(){
            $sql="DELETE FROM pe_proveedores_acreedores WHERE 1";
        $this->db->query($sql);
         
        $sql="SELECT id_proveedor,nombre_proveedor as nombre FROM pe_proveedores";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id_proveedor=$v->id_proveedor;
            $nombre=$v->nombre;
            $sql="INSERT INTO pe_proveedores_acreedores set id_proveedor_acreedor='$id_proveedor',nombre='$nombre'";
            $this->db->query($sql);
           
        }
        $sql="SELECT id_proveedor,nombre_proveedor as nombre FROM pe_acreedores";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $id_proveedor=$v->id_proveedor*1000;
            $nombre=$v->nombre;
            $sql="INSERT INTO pe_proveedores_acreedores set id_proveedor_acreedor='$id_proveedor',nombre='$nombre'";
            $this->db->query($sql);
           
        }
        }
        
        function grabarVentaDirecta($vendidoA,$id_cliente,$concepto,$fecha,$importeTotal,$costeTotal,$pvpTotal){
            $importeTotal=$importeTotal*100;
            $costeTotal=$costeTotal*100;
            $pvpTotal=$pvpTotal*100;
            $sql="INSERT INTO pe_ventas_directas
                    SET vendido_a='$vendidoA',
                        id_cliente='$id_cliente',
                        concepto='$concepto',
                         fecha='$fecha',
                         importe_total='$importeTotal',
                         coste_total='$costeTotal',
                         pvp_total='$pvpTotal'";   
            
            $this->db->query($sql);
            $sql="SELECT id FROM pe_ventas_directas ORDER BY id DESC LIMIT 1";
            return $this->db->query($sql)->row()->id;
        }
        
        function grabarTransformacion($transformacion,$concepto,$fecha,$id_transformacion,$patron,$loteOrigen,$loteFinal){
            
            $sql="UPDATE pe_transformaciones SET patron='$patron', realizada_por='".$this->session->nombre."', nombre='$transformacion', concepto='$concepto', fecha='$fecha',lote_origen='$loteOrigen',lote_final='$loteFinal'  WHERE id_transformacion='$id_transformacion'";
            $this->db->query($sql);
            //$sql="SELECT id FROM pe_transformaciones ORDER BY $id_transformacion DESC LIMIT 1";
            return $id_transformacion;//$this->db->query($sql)->row()->id;
        }
        
        
        function grabarLineasVentaDirecta($id_venta_directa,$lineas){
            foreach($lineas as $k=>$v){
                 $codigo_producto=$v['codigo_producto'];    
                 $cantidad=$v['cantidad'];
                 //$precio=$v['precio']*100;
                 $importe=$v['importe']*100;  
                 $coste=$v['coste']*100;  
                 $pvp=$v['pvp']*100;  
                 $this->load->model('productos_');
                 
                 $id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);
                 $tipoIva=$this->productos_->getIva($codigo_producto)->valor_iva;
                 $tipoIva*=100;
                 $iva=round($importe-$importe/(1+$tipoIva/10000),0);
                $sql="INSERT INTO pe_lineas_ventas_directas
                    SET id_venta_directa='$id_venta_directa',
                        id_pe_producto='$id_pe_producto',
                        cantidad='$cantidad',
                        importe='$importe',
                        coste='$coste',
                        pvp='$pvp',
                        tipo_iva='$tipoIva',
                        iva='$iva'";    
                $this->db->query($sql);
                
                $this->stocks_model->sumaCantidadStocks($id_pe_producto,-$cantidad*1000,$fecha_caducidad_stock="0000-00-00");
                
            }
            return true;
        }
        
         function grabarLineasAlbaran($id_albaran,$lineas){
            
            foreach($lineas as $k=>$v){
                 $codigo_producto=$v['codigo_producto']; 
                 $cantidad=(float)$v['cantidad'];
                 $cantidad=$cantidad*1000;
                // mensaje('$codigo_producto '.$codigo_producto.' '.$cantidad);

                 $tipoUnidad=$v['tipoUnidad'];
                 $fechaCaducidad=$v['fechaCaducidad'];
                 $this->load->model('productos_');
                 
                 $id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);
              
                 $sql="INSERT INTO pe_lineas_albaranes
                    SET id_albaran='$id_albaran',
                        id_pe_producto='$id_pe_producto',
                        tipoUnidad='$tipoUnidad',    
                        cantidad='$cantidad',
                        fecha_caducidad='$fechaCaducidad'" ;
                        
                $this->db->query($sql);
                $albaran=1;
                $fecha_caducidad_stock=$fechaCaducidad;
                $this->stocks_model->sumaCantidadStocks($id_pe_producto,$cantidad,$fecha_caducidad_stock);

                //$this->upload_->reportarVentasStocks($id_pe_producto,-$cantidad,$fechaCaducidad,$albaran);
                //$this->upload_->reportarVentasStocks_totales($id_pe_producto,-$cantidad,$albaran);
            }
            return ;
        }
        
         function grabarLineasPedidoNuevo($id_albaran,$lineas){
            
            foreach($lineas as $k=>$v){
                 $codigo_producto=$v['codigo_producto']; 
                 $cantidad=(float)$v['cantidad'];
                 $cantidad=$cantidad*1000;

                 $tipoUnidad=$v['tipoUnidad'];
                 // $fechaCaducidad=$v['fechaCaducidad'];
                 $this->load->model('productos_');
                 
                 $id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);
              
                 $sql="INSERT INTO pe_lineas_pedidos
                    SET id_albaran='$id_albaran',
                        id_pe_producto='$id_pe_producto',
                        tipoUnidad='$tipoUnidad',    
                        cantidad='$cantidad'" ;
                        
                $this->db->query($sql);
                
            }
            return true;
        }
        
        function grabarLineasFacturaProveedor($id_factura,$lineas,$proveedor){
            foreach($lineas as $k=>$v){
                $cantidad=$v['cantidad']*1000;  
               // if($cantidad==0) continue;
                 $codigo_producto=$v['codigo_producto'];    
                 
                 $tipoUnidad=$v['tipoUnidad'];
                 $tipoIva=$v['tipoIva']*100;
                 $precio=$v['precio']*100;
                 $descuento=$v['descuento']*1000;
                 $total=$v['importe']*100 ; 
                 $fechaCaducidad=$v['fecha_caducidad'];
                 
                 $this->load->model('productos_');
                 $id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);
              
                 $sql="INSERT INTO pe_lineas_facturas_proveedores
                    SET id_factura='$id_factura',
                        codigo_producto='$id_pe_producto',
                        cantidad='$cantidad',
                        tipoUnidad='$tipoUnidad',
                        fecha_caducidad='$fechaCaducidad',
                        precio='$precio',
                        descuento='$descuento',
                        total='$total',
                        tipoIva='$tipoIva'";  
               
                $this->db->query($sql);
                //en la tabla productos, los precios están multiplicados por 1000
                $precioTablaProductos=$precio*10;
                //si se entra precio compra a través de factura proveedor, se considera que el precio de transformación es 0
                if($tipoUnidad=='Und')
                    $sql="UPDATE pe_productos SET precio_transformacion_unidad='0', precio_transformacion_peso='0',descuento_1_compra='$descuento', precio_ultimo_unidad='$precioTablaProductos',precio_ultimo_peso='0', id_proveedor_web='$proveedor' WHERE id='$id_pe_producto'";
                else
                    $sql="UPDATE pe_productos SET precio_transformacion_unidad='0', precio_transformacion_peso='0',descuento_1_compra='$descuento', precio_ultimo_peso='$precioTablaProductos',precio_ultimo_unidad='0', id_proveedor_web='$proveedor' WHERE id='$id_pe_producto'";
                $this->db->query($sql);
                $precioCompra=$this->productos_->precioCompraFinal($id_pe_producto);
                
                
                $sql="UPDATE pe_productos SET precio_compra='$precioCompra' WHERE id='$id_pe_producto'";
                $this->db->query($sql);
                
                $margen_real_producto=$this->productos_->margen_real_producto($id_pe_producto)*1000;
                
                $sql="UPDATE pe_productos SET margen_real_producto='$margen_real_producto' WHERE id='$id_pe_producto'";
                $this->db->query($sql);
                
                
                $this->productos_->regularizarPrecios($id_pe_producto);
                
                //los cambios o datos de la factura se graban en pe_registro_precios.
                $sql="SELECT * FROM pe_productos WHERE id='$id_pe_producto'";
                $row=$this->db->query($sql)->row_array();
                $this->productos_->grabarCambiosPrecios($row,$id_pe_producto);
                
                
                
                $sql="SELECT * FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'";
                if($this->db->query($sql)->num_rows()>0){
                    $sql="UPDATE pe_stocks SET proveedor='$proveedor', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto' WHERE id_pe_producto='$id_pe_producto' ";
                    $this->db->query($sql);
                    $sql="UPDATE pe_stocks_totales SET proveedor='$proveedor', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto' WHERE id_pe_producto='$id_pe_producto'";
                    $this->db->query($sql);
                }else {
                   $sql="INSERT INTO   pe_stocks SET  proveedor='$proveedor', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto'";
                   $this->db->query($sql); 
                   $sql="INSERT INTO   pe_stocks_totales SET  proveedor='$proveedor', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto'";
                   $this->db->query($sql); 
                }
                
            }
            //una vez grabadas las lineas, calcular el iva pagado
            $resultLineas=$this->db->query("SELECT * FROM pe_lineas_facturas_proveedores WHERE id_factura='".$id_factura."'")->result();
            $bases=array();
            foreach($resultLineas as $k1=>$v1){
                // mensaje('total linea '.$v1->total);
                // mensaje('total linea tipo iva'.$v1->tipoIva);
                if(isset($bases[$v1->tipoIva])) $bases[$v1->tipoIva]+=$v1->total;
                else $bases[$v1->tipoIva]=$v1->total;
                // mensaje('bases '.$v1->tipoIva.' - '.$bases[$v1->tipoIva]);
            }
            $totalIva=0;
            foreach($bases as $k2=>$v2){
                // mensaje($v2);
                // mensaje($k2);
                $totalIva+=$v2*$k2/100/100;
            }
            // mensaje('$totalIva '.$totalIva);
            $this->db->query("UPDATE pe_facturas_proveedores SET total_iva='$totalIva' WHERE id='".$id_factura."'");
            $this->db->query("UPDATE pe_facturas_proveedores SET base=importe-total_iva WHERE id='".$id_factura."'");
            return true;
        }
       
        function grabarLineasTransformacion($id_transformacion,$lineas,$preciosNuevos,$preciosActuales,$patron=0){
            $valores=array();
            $n = strpos($_POST['venta']['preciosCompra'],"Registrar");
            $preciosCompra=substr($_POST['venta']['preciosCompra'],0,$n);
            $mensajeEmail="<strong>Resumen precios actuales y calculados</strong><br><br>".$preciosCompra;
            $preciosCambiados=false;
           
            $salida=array();
            $linea=0;
            foreach($lineas as $k=>$v){
                $cantidad=$v['cantidad']*1000;
                if($patron==0 && $cantidad==0) continue;
                 $codigo_producto=$v['codigo_producto'];    
                 
                 $perdida=floatval($v['perdida'])*1000;
                 $fechaCaducidad= $v['fechaCaducidad'];
                 // mensaje('$fechaCaducidad '.$fechaCaducidad);
                 $precioNuevo= floatval($preciosNuevos[$linea])*1000;
                 $precioActual=floatval($preciosActuales[$linea])*1000;
                 $linea++;
                 $this->load->model('productos_');
                 $id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);
                 
                 //if(is_numeric($precioNuevo) && $precioNuevo!=0){
                 if(is_numeric($precioNuevo) && $precioNuevo!=0){
                    $mensajeEmail.= $this->productos_->updatePrecioCompraTransformacion($codigo_producto,$precioNuevo);
                    $preciosCambiados=true;
                 }
                
                 $sql="INSERT INTO pe_lineas_transformaciones
                    SET id_transformacion='$id_transformacion',
                        id_pe_producto='$id_pe_producto',
                        cantidad='$cantidad',
                        precio='$precioActual',
                        precio_calculado='$precioNuevo',    
                        perdida_peso_por_ciento='$perdida',    
                        fecha_caducidad='$fechaCaducidad'";
                $this->db->query($sql);
               
                $albaran=1;
                  
                $this->load->model('stocks_model');
                if($fechaCaducidad=="") $fechaCaducidad="0000-00-00";
                
               // $this->productos_->regularizarDatosProducto($id_pe_producto);
                $this->productos_->regularizarPrecios($id_pe_producto);
                $cantidad=$cantidad+$cantidad*$perdida/100000;
                if(!$patron){
                    //caso que status_producto=0, poner status_producto=1
                    //solicitado por Sergi 2018-07-05
                    //log_message('INFO','Actualización stocks y activar producto '.$id_pe_producto);
                    $this->db->query("UPDATE pe_productos SET status_producto=1 WHERE id='$id_pe_producto'");
                    
                    $valores[]=$this->stocks_model->sumaCantidadStocks($id_pe_producto,$cantidad,$fechaCaducidad);
                }
            }
           
            
             //envio email
                  if (true) {
                    $this->load->library('email');

                    /*
                    $this->email->from('info@lolivaret.com', host().'- Precios');


                    $host = host();
                    if ($host === "localhost") {
                        $this->email->bcc('mbanolas@gmail.com');
                        $this->email->to('mbanolas@gmail.com');
                    } else {
                        
                        //$this->email->bcc('mbanolas@gmail.com');
                        //$this->email->to('alex@jamonarium.com');
                        //$this->email->cc('carlos@jamonarium.com');
                        $this->email->bcc('mbanolas@gmail.com'); 
                        $this->email->to('carlos@jamonarium.com');
                        //$this->email->to('mbanolas@gmail.com'); 
                        
                    }
                    $this->email->subject('Transformaciones');
                    */
                    $message = "";
                    $message .= "<h3>Informe Precios Transformación</h3>";
                    $message .= "<h4>Comparación precios</h4>";
                    $message .= "Núm Transformacion: $id_transformacion";
                    $nombreUsuario=$this->session->nombre;
                    $message .= "<br>Efectuada por: $nombreUsuario";
                    
                    $fecha= fechaEuropeaSinHora(date('Y-m-d'));
                    $message .= "<br>Fecha: $fecha<br><br>";
                    
                    $message.= $mensajeEmail;
                    if($preciosCambiados){
                        $message .= "<br>Los precios compra calculados<strong> se han introducido en la base de datos Productos</strong>.";
                        $message .= "<br>Si existen otros productos con el mismo código de báscula, sus precios de compra se han actualizado de acuerdo con sus pesos.";
                        $message .= "<br>Por omisión, se ha mantenido la misma tarifa PVP y se actualiza el margen. Celdas color verde.";
                        $message .= "<br>Los valores con celdas en amarillo son los valores que se deberán cambiar en <strong>Productos Editar</strong> según el criterio que se elija ";
                    }
                    else{
                        $message .= "<br>Los precios calculados <strong>NO se han puesto en la base datos Productos</strong>.";
                    }
                    
                    $message .= "<br><br>Fin del informe";

                    enviarEmail($this->email, 'Transformaciones',host().' - Precios',$message,1);

                    /*
                    $this->email->message($message);
                    //$this->email->message('<h3>Códigos PrestaShop NO existentes en Base datos productos</h3>'.$salida1);

                    if ($this->email->send()) {
                        // echo "Mail Sent!";
                    } else
                        echo "Error al enviar email";
                        */
                }
            
            
            return $valores;
        }
       
        
        function getVentaDirecta($id){
            $sql="SELECT v.vendido_a as vendido_a,c.nombre as cliente,v.concepto as concepto,v.importe_total as importe_total, v.coste_total as coste_total, v.pvp_total as pvp_total, v.fecha as fecha   
                    FROM pe_ventas_directas v
                    LEFT JOIN pe_clientes c ON c.id_cliente=v.id_cliente
                    WHERE v.id='$id'";
            $result=$this->db->query($sql)->row();
            $sql="SELECT p.codigo_producto as codigo_producto, p.nombre as nombre,  v.cantidad as cantidad, v.precio as precio, v.importe as importe, v.coste as coste, v.pvp as pvp, v.tipo_iva as tipo_iva, v.iva as iva
                    FROM pe_lineas_ventas_directas v
                    LEFT JOIN pe_productos p ON p.id=v.id_pe_producto
                    WHERE id_venta_directa='$id'
                    ";
                
           
            $result2=$this->db->query($sql)->result();
            $lineas=array();
            foreach($result2 as $k => $v){
                $lineas[]=array('codigo_producto'=>$v->codigo_producto,'nombre'=>$v->nombre, 'cantidad'=>$v->cantidad, 'precio'=>$v->precio, 'importe'=>$v->importe, 'coste'=>$v->coste, 'pvp'=>$v->pvp, 'tipo_iva'=>$v->tipo_iva, 'iva'=>$v->iva) ;
                
            }
            return array('vendidoA'=>$result->vendido_a,'cliente'=>$result->cliente ,'concepto'=>$result->concepto, 'fecha'=>$result->fecha, 'importe_total'=>$result->importe_total, 'coste_total'=>$result->coste_total,'pvp_total'=>$result->pvp_total,'lineas'=>$lineas);
            
        }
        /*
        function getAlbaran($id){
            $sql="SELECT * FROM pe_albaranes WHERE id='$id'";
          
            $id_proveedor=$this->db->query($sql)->row()->id_proveedor;
            
             if($id_proveedor<1000) $tabla='pe_proveedores'; else {$id_proveedor/=1000; $tabla='pe_acreedores';}
            $sql="SELECT pr.nombre_proveedor as nombre_proveedor , pe.nombreArchivoPedido as nombreArchivoPedido,a.fecha as fecha
                    FROM pe_albaranes a
                    LEFT JOIN $tabla pr ON pr.id_proveedor='$id_proveedor'
                    LEFT JOIN pe_pedidos_proveedores pe ON pe.id=a.id_pedido
                    WHERE a.id='$id'";
             
            $result=$this->db->query($sql)->row();
            $sql="SELECT p.codigo_producto as codigo_producto,p.nombre as nombre, v.cantidad as cantidad, v.tipoUnidad
                    FROM pe_lineas_albaranes v
                    LEFT JOIN pe_productos p ON p.id=v.id_pe_producto
                    WHERE id_albaran='$id'
                    ";
                
           
            $result2=$this->db->query($sql)->result();
            $lineas=array();
            foreach($result2 as $k => $v){
                $lineas[]=array('nombre'=>$v->nombre,'codigo_producto'=>$v->codigo_producto ,'cantidad'=>$v->cantidad,'tipoUnidad'=>$v->tipoUnidad) ;
                
            }
            $proveedor=$result->nombre_proveedor;
            if (is_null($proveedor)) $proveedor="---";
            $pedido=$result->nombreArchivoPedido;
            if (is_null($pedido)) $pedido="---";
            
            return array('proveedor'=>$proveedor, 'pedido'=>$pedido, 'fecha'=>$result->fecha,'lineas'=>$lineas);
            
        }
        */
        function getPedido($id){
            $sql="SELECT * FROM pe_pedidos_proveedores WHERE id='$id'";
            $id_proveedor=$this->db->query($sql)->row()->id_proveedor;
            
            
            if($id_proveedor<1000) $tabla='pe_proveedores'; else {$id_proveedor/=1000; $tabla='pe_acreedores';}
            $sql="SELECT pe.numPedido as numPedido, pe.otrosCostes as otrosCostes, pe.importe as importe, pr.nombre_proveedor as nombre_proveedor , pe.nombreArchivoPedido as nombreArchivoPedido,pe.fecha as fecha
                    FROM pe_pedidos_proveedores pe
                    LEFT JOIN $tabla pr ON pr.id_proveedor='$id_proveedor'
                    WHERE pe.id='$id'";
           
                 
            $result=$this->db->query($sql)->row();
            $sql="SELECT p.codigo_producto as codigo_producto,p.nombre as nombre, p.nombre_generico as nombre_generico, v.cantidad as cantidad, v.tipoUnidad as tipoUnidad,v.precio as precio, v.descuento as descuento, v.total as total
                    FROM pe_lineas_pedidos_proveedores v
                    LEFT JOIN pe_productos p ON p.codigo_producto=v.codigo_producto
                    WHERE id_pedido='$id'
                    ";
            // mensaje($sql); 
            $result2=$this->db->query($sql)->result();
            $lineas=array();
            foreach($result2 as $k => $v){
                if($v->tipoUnidad=="Und"){
                    $cantidad=  number_format($v->cantidad/1000,0);
                }
                else{
                    $cantidad=  number_format($v->cantidad/1000,3);
                }
                if($v->descuento==0) $descuento=""; else $descuento=  number_format ($v->descuento/100,2);
                $v->nombre=!$v->nombre_generico?$v->nombre:$v->nombre_generico;
                $lineas[]=array('nombre'=>$v->nombre,'codigo_producto'=>$v->codigo_producto ,'cantidad'=>$cantidad,'tipoUnidad'=>$v->tipoUnidad,'precio'=>  number_format($v->precio/100,2),'descuento'=>$descuento,'total'=>  number_format($v->total/100,2)) ;
                
            }
            $proveedor=$result->nombre_proveedor;
            $numPedido=$result->numPedido;
            $fechaPedido=$result->fecha;
            $otrosCostes=$result->otrosCostes;
            $importe=$result->importe;
            $importe=  number_format($importe/100,2);
            if (is_null($proveedor)) $proveedor="---";
            $pedido=$result->nombreArchivoPedido;
            if (is_null($pedido)) $pedido="---";
            
            return array('fechaPedido'=>$fechaPedido,'importe'=>$importe,'otrosCostes'=>$otrosCostes,'numPedido'=>$numPedido,'proveedor'=>$proveedor, 'pedido'=>$pedido, 'fecha'=>$result->fecha,'lineas'=>$lineas);
            
        }
        
        function getAlbaran($proveedor){
            if (strtolower($this->session->username) == 'pernilall') {
                $sql="SELECT  a.id as id, pr.nombre as nombre_proveedor, a.fecha as fecha, a.num_albaran as num_albaran
                  FROM pe_albaranes a
                  LEFT JOIN pe_proveedores_acreedores pr ON pr.id_proveedor_acreedor=a.id_proveedor
                  WHERE a.id_proveedor= '$proveedor'   
                  ORDER BY a.id DESC";
            }
            else{
                $sql="SELECT  a.id as id, pr.nombre as nombre_proveedor, a.fecha as fecha, a.num_albaran as num_albaran
                  FROM pe_albaranes a
                  LEFT JOIN pe_proveedores_acreedores pr ON pr.id_proveedor_acreedor=a.id_proveedor
                  WHERE a.id_proveedor= '$proveedor' AND fecha>='".strval((date('Y')-1))."-01-01' 
                  ORDER BY a.id DESC";
            }

            $result=$this->db->query($sql)->result();
           
            $options=array();
            $ids=array();
            //$options[0]="Seleccionar un albaran";
            foreach($result as $k=>$v){
                $fecha=  fechaEuropeaSinHora($v->fecha);
                $nombre=substr($v->nombre_proveedor,0,25);
                $num_albaran=substr($v->num_albaran,0,15);
                $options[$k]=$fecha.' Albarán '.$num_albaran;
                $ids[$k]=$v->id;
            }
            return array('options' => $options,'ids'=>$ids);
        } 
        
        function getFactura($proveedor){
            
            if (strtolower($this->session->username) == 'pernilall') {
                $sql="SELECT  f.id as id, f.fecha_pago as fecha_pago,f.fecha as fecha, f.numFactura as numFactura, f.importe as importe  
                FROM pe_facturas_proveedores f  
                LEFT JOIN pe_proveedores_acreedores pr ON pr.id_proveedor_acreedor=f.id_proveedor  
                WHERE f.id_proveedor= '$proveedor'       
                 ORDER BY f.fecha DESC";
            }
            else{
                $sql="SELECT  f.id as id, f.fecha_pago as fecha_pago,f.fecha as fecha, f.numFactura as numFactura, f.importe as importe  
                FROM pe_facturas_proveedores f  
                LEFT JOIN pe_proveedores_acreedores pr ON pr.id_proveedor_acreedor=f.id_proveedor  
                WHERE f.id_proveedor= '$proveedor'  AND f.fecha>='".strval((date('Y')-1))."-01-01' ORDER BY f.fecha DESC";
            }
            
            
            mensaje('getFactura');
            mensaje($sql);
            
            
            
            
            
            
            
            // $sql="SELECT  f.id as id, f.fecha_pago as fecha_pago,f.fecha as fecha, f.numFactura as numFactura, f.importe as importe  
            //     FROM pe_facturas_proveedores f  
            //     LEFT JOIN pe_proveedores_acreedores pr ON pr.id_proveedor_acreedor=f.id_proveedor  
            //     WHERE f.id_proveedor= '$proveedor'       
            //      ORDER BY f.fecha DESC";
            //return $sql;     
            $result=$this->db->query($sql)->result();
           
            $options=array();
            //$options[0]="Seleccionar un albaran";
            foreach($result as $k=>$v){
                $fecha=  fechaEuropeaSinHora($v->fecha);
                $importe=$v->importe/100;
                $numFactura=substr($v->numFactura,0,15);
                $options[$v->id]='Factura '.$fecha.' - '.$numFactura.' - '.$importe ;
            }
            return array('options' => $options);
        } 
        
        function getFacturaProveedor($id){
            $sql="SELECT f.numFactura as numFactura, f.fecha_pago as fecha_pago, pr.nombre as nombre_proveedor , f.num_albaran as num_albaran,f.fecha as fecha, "
                   ." f.otrosCostes as otrosCostes, f.importe as importe,f.tipoIva as tipoIva "
                   ." FROM pe_facturas_proveedores f "
                   ." LEFT JOIN pe_proveedores_acreedores pr ON pr.id_proveedor_acreedor=f.id_proveedor "
                   ." LEFT JOIN pe_albaranes a ON a.num_albaran=f.num_albaran "
                  ."  WHERE f.id='$id'";
            
                 
            $result=$this->db->query($sql)->row();
            $sql="   SELECT p.codigo_producto as codigo_producto, p.nombre as nombre, "
                      ."  v.cantidad as cantidad, "
                      ."  v.tipoUnidad as tipoUnidad, "
                     ."  v.tipoIva as tipoIva, "
                     ."   v.precio as precio, "
                     ."   v.fecha_caducidad as fecha_caducidad, "
                     ."   v.descuento as descuento, "
                     ."   v.total as total "
                     ."   FROM pe_lineas_facturas_proveedores v "
                     ."   LEFT JOIN pe_productos p ON p.id=v.codigo_producto "
                     ."   WHERE id_factura='$id'   ";
                   
            
            $result2=$this->db->query($sql)->result();
            $lineas=array();
            foreach($result2 as $k => $v){
                $lineas[]=array('codigo_producto'=>$v->codigo_producto,'fechaCaducidad'=>$v->fecha_caducidad,'tipoIva'=>$v->tipoIva,'nombre'=>$v->nombre, 'cantidad'=>$v->cantidad,'tipoUnidad'=>$v->tipoUnidad,'precio'=>$v->precio,'descuento'=>$v->descuento,'total'=>$v->total) ;
                
            }
            $numFactura=$result->numFactura;
            $proveedor=$result->nombre_proveedor;
            if (is_null($proveedor)) $proveedor="---";
            $albarn=$result->num_albaran;
            if (is_null($albarn)) $albarn="---";
            
            return array('numFactura'=>$numFactura,'proveedor'=>$proveedor, 'albaran'=>$albarn, 'fecha'=>$result->fecha,'fecha_pago'=>$result->fecha_pago,
                'otrosCostes'=>$result->otrosCostes,'importe'=>$result->importe,'tipoIva'=>$result->tipoIva,
                'lineas'=>$lineas);
            
        }
        
        function getTransformacion($id){
            $sql="SELECT t.id_transformacion as id_transformacion,t.nombre as nombre, t.concepto as concepto,t.fecha as fecha,t.patron as patron, t.lote_origen as lote_origen, t.lote_final as lote_final FROM pe_transformaciones t WHERE t.id='$id'";
          
            $row=$this->db->query($sql)->row();
            $id_transformacion=$row->id_transformacion;
            
            $sql="SELECT l.precio as precio,l.precio_calculado as precioCalculado, p.id as id_pe_producto,p.nombre as nombre,l.perdida_peso_por_ciento as perdida, l.cantidad as cantidad,p.codigo_producto as codigo_producto,l.fecha_caducidad as fecha_caducidad
                    FROM pe_lineas_transformaciones l
                    LEFT JOIN pe_productos p ON p.id=l.id_pe_producto
                    WHERE id_transformacion='$id_transformacion' ORDER BY cantidad, p.codigo_producto
                    ";
                
           
            $result2=$this->db->query($sql)->result();
            $lineas=array();
            foreach($result2 as $k => $v){
                $this->load->model('productos_');
                $tipoUnidad=$this->productos_->getUnidad($v->id_pe_producto);
                
                $fechaCaducidad=fechaEuropeaSinHora($v->fecha_caducidad);
                if($fechaCaducidad=="01/01/1970") $fechaCaducidad="";
                $lineas[]=array('precioCalculado'=>$v->precioCalculado,'precio'=>$v->precio,'perdida'=>$v->perdida,'nombre'=>$v->nombre,'codigo_producto'=>$v->codigo_producto, 'tipoUnidad'=>$tipoUnidad,'cantidad'=>$v->cantidad,'fecha_caducidad'=> $fechaCaducidad) ;
            }
            $fechaMas6Meses=date('Y-m-d', strtotime(' +6 months'));
            return array('fechaMas6Meses'=>$fechaMas6Meses,'id_transformacion'=>$row->id_transformacion,'nombre_transformacion'=>$row->nombre, 'concepto'=>$row->concepto, 'fecha'=>$row->fecha,'patron'=>$row->patron, 'lote_origen'=>$row->lote_origen,'lote_final'=>$row->lote_final,'lineas'=>$lineas);
        }
        
        function getPedidoPrestashop($id){
            $sql="SELECT * FROM pe_orders_prestashop WHERE id='$id'";
            
            if($this->db->query($sql)->num_rows()==0) return 0;
            
            $result=$this->db->query($sql)->row();
            $tipoIvaTransporte=$result->tipo_iva_transporte;
            $sql="SELECT * FROM pe_clientes_jamonarium WHERE id='".$result->customer_id."'";
            $firstname="Cliente sin datos";
            $lastname="";
            $country="";
            if($this->db->query($sql)->num_rows()){
                $rowcliente=$this->db->query($sql)->row();
                $firstname=$rowcliente->firstname;
                $lastname=$rowcliente->lastname;
                $country=$rowcliente->country;
            }
            // mensaje($firstname);
            $sql="SELECT v.es_componente_pack as es_componente_pack,v.es_pack as es_pack, v.valid as valid,p.codigo_producto as codigo_producto, p.nombre as nombre, v.cantidad as cantidad, v.precio as precio,v.tipo_iva as tipo_iva,v.iva as iva,
                    v.importe as importe
                    FROM pe_lineas_orders_prestashop v
                    LEFT JOIN pe_productos p ON p.id=v.id_pe_producto
                    WHERE id_order='$id'
                    ";
            // mensaje('getPedidoPrestashop '.$sql);
           
            $result2=$this->db->query($sql)->result();
            $lineas=array();
            $descuentoTotalCalculado=0;
            $baseTotalCaluculado=0;
            $ivaTotalCalculado=0;
            $importeTotalCalculado=0;
            foreach($result2 as $k => $v){
                $cantidad=$v->cantidad;
                $valid=$v->valid;
                $importe=$v->importe;
                $precio=$v->precio;
                $descuento=0;
                $esPack=$v->es_pack;
                $esComponentePack=$v->es_componente_pack;
                if($cantidad!=0) $descuento=round(($cantidad*$precio-$importe)/$cantidad,2);
                //log_message('INFO', '1 $cantidad = '.$cantidad);
                //log_message('INFO', '1 $precio = '.$precio);
                //log_message('INFO', '1 $importe = '.$importe);
                $c=$cantidad*$precio-$importe;
                //log_message('INFO', '1 $c = '.$c);
                //log_message('INFO', '1 $cantidad*$precio-$importe = ');
                //log_message('INFO', '2 ($cantidad*$precio-$importe)/$cantidad = '.($cantidad*$precio-$importe)/$cantidad);
                //log_message('INFO', '3 round(($cantidad*$precio-$importe)/$cantidad,2) = '.round(($cantidad*$precio-$importe)/$cantidad,2));
                //log_message('INFO', '4 $descuento = '.$descuento);

                $lineas[]=array('esComponentePack'=>$esComponentePack,'esPack'=>$esPack,'valid'=>$valid,'codigo_producto'=>$v->codigo_producto,'nombre'=>$v->nombre, 'cantidad'=>$v->cantidad, 'precio'=>$v->precio,'tipoIva'=>$v->tipo_iva,'iva'=>$v->iva,'importe'=>$v->importe,'descuento'=>$descuento) ;
                $descuentoTotalCalculado+=$descuento;
                $importeTotalCalculado+=$importe;
                $ivaTotalCalculado+=$v->iva;
                
            }
            $baseTotalCaluculado=$importeTotalCalculado-$ivaTotalCalculado;
            
            $totalPrecio=0;
            $baseTotalSuma=0;
            $varAmountSuma=0;
            
            for($i=0;$i<count($lineas);$i++){
                if($lineas[$i]['valid']==-1) 
                        $anulado=true; 
                $precio=$lineas[$i]['precio']*$lineas[$i]['cantidad']/100;
                //alert($lineas[i]['precio'])
                //alert($lineas[i]['tipoIva'])
                $basePrecio=$lineas[$i]['precio']/(100+$lineas[$i]['tipoIva']/100);
                $basePrecio=round($basePrecio*100)/100;
                $baseTotal=$basePrecio*$lineas[$i]['cantidad'];
                
                $tipoIva=$lineas[$i]['tipoIva']/100;
                $infoIvas[$tipoIva]=$tipoIva;
                
                $varAmount=$precio-$baseTotal;
                $varAmount=round($varAmount*100)/100;
                
                $baseTotalSuma+=$baseTotal;
                $varAmountSuma+=$varAmount;
                
                 
                if(!isset($precioIvas[$tipoIva])) $precioIvas[$tipoIva]=0;
                if(is_nan($precioIvas[$tipoIva])) $precioIvas[$tipoIva]=0;
                if(!isset($precioIvas[$tipoIva])) $precioIvas[$tipoIva]=0; 
                $precioIvas[$tipoIva]+=$precio;
                if($lineas[$i]['esPack']==0){
                    
                    $totalPrecio+=$precio;
                }
                }
                $precioIvasTotal=0;
                $baseTotal=0;
                
                $factor=0;
                if($result->total)
                    $factor=($result->total-$result->descuento)/$result->total;
                //grabamos pe_lineas_prestashop con importes, bases e ivas finales afectados por los descuentos
                $sql="UPDATE pe_lineas_orders_prestashop SET importe_con_descuento=round(importe*$factor),"
                        . " base_con_descuento=round(importe/(1+tipo_iva/10000)*$factor),"
                        . " iva_con_descuento=round(importe*$factor)-round(importe/(1+tipo_iva/10000)*$factor)"
                        . " WHERE id_order='$id'";
                
                $this->db->query($sql);
                
                
                if(!isset($precioIvas)) $precioIvas=array();
                foreach($precioIvas as $k=>$v){
                    $precioIvas[$k]=round($v*$factor*100,0)/100;
                    $precioIvasTotal+=$precioIvas[$k];
                    $base[$k]=$precioIvas[$k]/(1+$k/100);
                    $base[$k]=round($base[$k]*100)/100;
                    $baseTotal+=$base[$k];
                }
                $ivasTotal=round(($precioIvasTotal-$baseTotal)*100)/100;
                
                $totalPedido=round(($precioIvasTotal+$result->transporte/1000)*100)/100;
               
                
                if(!isset($infoIvas)) $infoIvas=array();
                if($result->customer_id_group==9) $baseTotal=$precioIvasTotal;
                $baseTransporte=$result->base_transporte;
                $tipoIvaTransporte=$result->tipo_iva_transporte;
            return array('ivaTotalCalculado'=>$ivaTotalCalculado,
                         'importeTotalCalculado'=>$importeTotalCalculado,
                         'descuentoTotalCalculado'=>$descuentoTotalCalculado,
                         'pedido'=>$result->id, 
                         'tipoCliente'=>$result->customer_id_group, 
                         'num_cliente'=>$result->customer_id, 
                         'firstname'=>$firstname, 
                         'lastname'=>$lastname, 
                         'country'=>$country, 
                         'descuento'=>$result->descuento, 
                         'transporte'=>$result->transporte, 
                         'total'=>$result->total,
                         'total_iva'=>$result->total_iva, 
                         'total_base'=>$result->total_base,
                         'fecha'=>$result->fecha,
                         'lineas'=>$lineas,
                         'totalPrecio'=>$totalPrecio,
                         'infoIvas'=>$infoIvas,
                         'precioIvas'=>$precioIvas,
                         'precioIvasTotal'=>$precioIvasTotal,
                         'baseTotal'=>$baseTotal,
                         'ivasTotal'=>$ivasTotal,
                         'totalPedido'=>$totalPedido,
                         'baseTransporte'=>$baseTransporte,
                         'tipoIvaTransporte'=>$tipoIvaTransporte,
                );
            
        }
       
        
        
        function getProveedoresFiltrados($filtro){
            $palabras=explode(" ",trim($filtro));
            $like="";
            foreach($palabras as $k=>$v){
                $palabras[$k]="nombre_proveedor LIKE '%$v%'";
            }
            $like=implode(' and ',$palabras);
            
            $sql="SELECT id_proveedor, nombre_proveedor FROM pe_proveedores WHERE $like ORDER BY nombre_proveedor";
            $result=$this->db->query($sql);
            $optionsProveedores=array();
            $id_proveedores=array();
            $nombre_proveedores=array();
            foreach($result->result() as $k=>$v){
                $id_proveedores[]=$v->id_proveedor;
                $nombre_proveedores[]=$v->nombre_proveedor;
                $optionsProveedores[$v->id_proveedor]=$v->nombre_proveedor;
            }
           return array('options' => $optionsProveedores,'ids' => $id_proveedores,'nombres' => $nombre_proveedores);
        }
        
        
        
        function getAcreedoresFiltrados($filtro){
            $palabras=explode(" ",trim($filtro));
            $like="";
            foreach($palabras as $k=>$v){
                $palabras[$k]="nombre LIKE '%$v%'";
            }
            $like=implode(' and ',$palabras);
            
            $sql="SELECT id_proveedor, nombre_proveedor FROM pe_acreedores WHERE $like ORDER BY nombre";
            $result=$this->db->query($sql);
            $optionsAcreedores=array();
            $id_proveedores=array();
            $nombres=array();
            foreach($result->result() as $k=>$v){
                $id_proveedores[]=$v->id_proveedor;
                $nombres[]=$v->nombre_proveedor;
                $optionsAcreedores[$v->id_proveedor]=$v->nombre_proveedor;
            }
           return array('options' => $optionsAcreedores,'ids' => $id_proveedores,'nombres' => $nombres);
        }
        
        function getProveedoresAcreedoresFiltrados($filtro){
            $palabras=explode(" ",trim($filtro));
            $like="";
            foreach($palabras as $k=>$v){
                $palabras[$k]="nombre_proveedor LIKE '%$v%'";
            }
            $like=implode(' and ',$palabras);
            
            
            $sql="SELECT  a.id_proveedor as id_proveedor, `nombre_proveedor` as nombre  FROM `pe_proveedores` a WHERE status_proveedor=1 AND $like";
            $sql.=" UNION ALL ";
            $sql.=" SELECT  b.id_proveedor*1000 as id_proveedor, `nombre_proveedor` as nombre FROM `pe_acreedores` b WHERE status_acreedor=1 AND $like ";
            $sql.=" ORDER BY trim(nombre)";
            //return $sql;

            $result=$this->db->query($sql);
            $proveedores=array();
            $id_proveedores=array();
            $nombres=array();
            foreach($result->result() as $k=>$v){
                $id_proveedores[]=$v->id_proveedor;
                $nombres[]=$v->nombre;
                $proveedores[]=array('id_proveedor'=>$v->id_proveedor,'nombre'=>$v->nombre);
            }
          //  var_dump($optionsProveedores);
           return array('proveedores'=>$proveedores,'ids' => $id_proveedores,'nombres' => $nombres);
        }
        
        
        
        function getAcreedoresFiltrados_($filtro){
            $palabras=explode(" ",trim($filtro));
            $like="";
            foreach($palabras as $k=>$v){
                $palabras[$k]="nombre LIKE '%$v%'";
            }
            $like=implode(' and ',$palabras);
            
            $sql="SELECT id_proveedor, nombre_proveedor FROM pe_acreedores WHERE $like ORDER BY nombre";
            $result=$this->db->query($sql);
            $optionsAcreedores=array();
            $id_proveedores=array();
            $nombres=array();
            foreach($result->result() as $k=>$v){
                $id_proveedores[]=$v->id_proveedor;
                $nombres[]=$v->nombre_proveedor;
                $optionsAcreedores[$v->id_proveedor]=$v->nombre_proveedor;
            }
           return array('options' => $optionsAcreedores,'ids' => $id_proveedores,'nombres' => $nombres);
        }
        
        function getProductosFiltrados($filtro){
            $palabras=explode(" ",trim($filtro));
            $like="";
            foreach($palabras as $k=>$v){
                $palabras[$k]="nombre LIKE '%$v%'";
            }
            $like=implode(' and ',$palabras);
            $sql="SELECT codigo_producto, nombre FROM pe_productos WHERE $like ORDER BY nombre";
            $result=$this->db->query($sql);
            $optionsProductos=array();
            $codigo_productos=array();
            $nombres=array();
            foreach($result->result() as $k=>$v){
                $codigo_productos[]=$v->codigo_producto;
                $nombres[]=$v->nombre;
                $optionsProductos[$v->codigo_producto]=$v->nombre;
            }
           return array('options' => $optionsProductos,'ids' => $codigo_productos,'nombres' => $nombres);
        }
        
         function getIdProductosFiltrados($filtro){
            $palabras=explode(" ",trim($filtro));
            $like="";
            foreach($palabras as $k=>$v){
                $palabras[$k]="nombre LIKE '%$v%'";
            }
            $like=implode(' and ',$palabras);
            $sql="SELECT id,codigo_producto, nombre FROM pe_productos WHERE $like ORDER BY nombre";
            //log_message('INFO','-----------------------'.$sql);

            $result=$this->db->query($sql);
            $optionsProductos=array();
            $codigo_productos=array();
            $nombres=array();
            $id_pe_producto=array();
            foreach($result->result() as $k=>$v){
                $codigo_productos[]=$v->codigo_producto;
                $nombres[]=$v->nombre;
                $id_pe_producto[]=$v->id;
                $optionsProductos[$v->codigo_producto]=$v->nombre;
            }
           return array('options' => $optionsProductos,'id_pe_producto'=>$id_pe_producto,'ids' => $codigo_productos,'nombres' => $nombres);
        }
        
        function excelPedido() {
       
         $ticketsFactura=$_POST['ticketsFactura'];
         $fechaPedido=$_POST['fechaPedido'];
         $numFactura=$_POST['numPedido'];
         
         //se comprueba si la factura contiene 2 o más tickets iguales
         if(array_unique($ticketsFactura) != $ticketsFactura)
                return false;
         
         //caberera Factura
        if(true){
         
         $ticket = $this->tickets_->getTicketPorNumero($ticketsFactura[0], 'pe_boka');
         $datosCliente=$this->clientes_->getDatosCliente($ticket['cliente']);

         //var_dump($datosCliente);
        
         $this->exceldrawing->setName('Pernil181');
        $this->exceldrawing->setDescription('Pernil181');
        $logo = 'images/pernil181.png'; // Provide path to your logo file
        $this->exceldrawing->setPath($logo);  //setOffsetY has no effect
        $this->exceldrawing->setCoordinates('C2');
        $this->exceldrawing->setHeight(80); // logo height
        $this->exceldrawing->setWorksheet($this->excel->getActiveSheet()); 
        
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setCellValue('H3', "181 Distribucions S.L."); 
        $this->excel->getActiveSheet()->setCellValue('H4', "Pg.Sant Joan 181"); 
        $this->excel->getActiveSheet()->setCellValue('H5', "08037 Barcelona, España"); 
        $this->excel->getActiveSheet()->setCellValue('H6', "www.jamonarium.com"); 
        $this->excel->getActiveSheet()->setCellValue('H7', "info@jamonarium.com"); 
        $this->excel->getActiveSheet()->setCellValue('C9', "181 Distribucions S.L. Inscrita en el R.M.B. Tomo 44061, Folio 146, Hoja B 446064. Inscripción 1ª.  N.I.F B66154964       "); 
        $this->excel->getActiveSheet()->setCellValue('C11', "FECHA"); 
        $this->excel->getActiveSheet()->setCellValue('C12', "RAZON SOCIAL"); 
        $this->excel->getActiveSheet()->setCellValue('C13', "DIRECCION"); 
        $this->excel->getActiveSheet()->setCellValue('C14', "POBLACIÓN"); 
        $this->excel->getActiveSheet()->setCellValue('C15', "PAIS"); 
        $this->excel->getActiveSheet()->setCellValue('H11', "Nº FACTURA"); 
        $this->excel->getActiveSheet()->setCellValue('H12', "CIF"); 
        $this->excel->getActiveSheet()->setCellValue('H13', "Nº CLIENTE"); 
        $this->excel->getActiveSheet()->setCellValue('H14', "C. POSTAL"); 
        $this->excel->getActiveSheet()->setCellValue('H15', "PROVINCIA"); 
        $this->excel->getActiveSheet()->getStyle('H11:H15')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'FCE9DA')));
        $this->excel->getActiveSheet()->getStyle('C11:C15')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'FCE9DA')));
        //control existencia datos cliente (caso que se haya borrado del listado clientes
      
        
        
        if(!isset($datosCliente['empresa'])) $datosCliente['empresa']="";
        
        $fecha =  substr(fechaEuropea($fechaFactura),0,10);  //substr($ticket['fecha'],0,10);
        $this->excel->getActiveSheet()->setCellValue('D11', $fecha); 
        $this->excel->getActiveSheet()->setCellValue('I11', $numFactura); 
        $this->excel->getActiveSheet()->setCellValue('D12', $datosCliente['empresa']); 
        $this->excel->getActiveSheet()->setCellValue('K12', $datosCliente['correo1']); 
        $this->excel->getActiveSheet()->setCellValue('K13', $datosCliente['correo2']); 
        $this->excel->getActiveSheet()->setCellValue('D13', $datosCliente['direccion']); 
        $this->excel->getActiveSheet()->setCellValue('D14', $datosCliente['poblacion']); 
        $this->excel->getActiveSheet()->setCellValue('D15', $datosCliente['pais']); 
        $this->excel->getActiveSheet()->setCellValue('I12', $datosCliente['nif']); 
        $this->excel->getActiveSheet()->setCellValue('I14', $datosCliente['codigoPostal']); 
        $this->excel->getActiveSheet()->setCellValue('I15', $datosCliente['provincia']); 
        
        
        
        
        
        //$this->excel->getActiveSheet()->setCellValue('D13', $ticket['empresa']); 
        $this->excel->getActiveSheet()->setCellValue('I13', $ticket['cliente']); 
        
        $this->excel->getActiveSheet()->getStyle('I13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->getActiveSheet()->setCellValue('C17', "Ref."); 
        $this->excel->getActiveSheet()->setCellValue('D17', "Unidad"); 
        $this->excel->getActiveSheet()->setCellValue('E17', "Descripción"); 
        $this->excel->getActiveSheet()->setCellValue('F17', "IVA"); 
        $this->excel->getActiveSheet()->setCellValue('G17', "Kilos"); 
        $this->excel->getActiveSheet()->setCellValue('H17', "Precio"); 
        $this->excel->getActiveSheet()->setCellValue('I17', "Importe"); 
        $this->excel->getActiveSheet()->getStyle('C17:I17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C17:I17')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->getStyle('C17:I17')->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
        
        $numero = $numFactura;  // $ticket['numero'];
     
        $linea=18;
        $totalFactura=0;
        
        }
         //Hasta aquí encabesado factura
        
        //lectura datos todos los tickets
        if(true){
        $numTickets=array();
        $productosPrecios=array(); 
        $productosPesos=array();
        $productosUnidades=array();
        $productosNombres=array();
        $productosCodigo=array();
        $productosPreciosU=array();
        $productosImportes=array();
        $productosIvas=array();
        //variable que registra si existe un códigos de producto en varias lineas (todos los tickets)
        //para hacer la factura compilados por productos (si varias lineas con el mismo producto o
        //compilado por tickets.
        $compilacionPorProductos=false;
        
        foreach($ticketsFactura as $kt => $ticket){
            $ticket = $this->tickets_->getTicketPorNumero($ticket, 'pe_boka');
            
            $numTickets[]=$ticket['numero'];
            
            foreach ($ticket['unidades_pesos'] as $k => $v) {
                $codProducto=$ticket['codigosProductos'][$k];
                    $precioProducto=$ticket['preciosUnitarios'][$k];
                    $codigoPrecio=$precioProducto*1000+$codProducto;
                    if(!in_array($codigoPrecio,$productosPrecios)){
                            $productosPrecios[]=$codigoPrecio;
                            $key = array_search($codigoPrecio, $productosPrecios);
                            $productosPesos[$key]=0;
                            $productosUnidades[$key]=0;
                            $productosNombres[$key]="";
                            $productosPreciosU[$key]=$precioProducto;
                            $productosCodigo[$key]=$codProducto;
                            $productosImportes[$key]=0;
                    }
                    if (false !== $key = array_search($codigoPrecio, $productosPrecios)) {
                        $compilacionPorProductos=true;
                        $productosNombres[$key]=$ticket['productos'][$k];
                        $productosIvas[$key]=$ticket['tiposIva'][$k];
                        $productosImportes[$key]+=$ticket['precios'][$k];
                        if ($v == "1" || $v=="3" ) $productosUnidades[$key]+=$ticket['unidades'][$k];
                        if ($v == "0" || $v=="4" ) $productosPesos[$key]+=$ticket['pesos'][$k];
                    } else {
                        // do something else
                    }
                
            }
             
        }
        }
         //lectura datos todos los tickets
        
        //aquí empieza salida agrupada por productos
        if($compilacionPorProductos){
        foreach($numTickets as $k=>$v){
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Ticket núm ' . $v);
        if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
        $linea++;
        }
        foreach($productosPrecios as $k=>$v){
            if($productosUnidades[$k] || $productosPesos[$k]){
            $tipoIVA=(int)$productosIvas[$k];    
            $this->excel->getActiveSheet()->setCellValue('C' . $linea,  $productosCodigo[$k]);
            $this->excel->getActiveSheet()->setCellValue('E' . $linea,  $productosNombres[$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea,  $productosPreciosU[$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea,  $tipoIVA);
            
            if (!isset($bruto[$tipoIVA])) $bruto[$tipoIVA]=0;
            
            //$productosImportes[$k]);
            if($productosUnidades[$k]) {
                $this->excel->getActiveSheet()->setCellValue('D' . $linea,  $productosUnidades[$k]);
                $totalFactura+=$productosUnidades[$k]*$productosPreciosU[$k];
                $bruto[$tipoIVA]+=$productosUnidades[$k]*$productosPreciosU[$k];
                $this->excel->getActiveSheet()->setCellValue('I' . $linea,  $productosUnidades[$k]*$productosPreciosU[$k]);
            }
            if($productosPesos[$k]) {
                $this->excel->getActiveSheet()->setCellValue('G' . $linea,  $productosPesos[$k]);
                $totalFactura+=$productosPesos[$k]*$productosPreciosU[$k];
                $bruto[$tipoIVA]+=$productosPesos[$k]*$productosPreciosU[$k];
                $this->excel->getActiveSheet()->setCellValue('I' . $linea,  $productosPesos[$k]*$productosPreciosU[$k]);
            }
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
       
            
            if ($linea % 2 ==0){
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'e2e2e2')
                                )
                            )
                    );
                }
            $linea++;
            }
        }
        
        
        //Anchos columnas
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(18.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(56.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(27.17*.68);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(5);

          
            
        $transporte=$_POST['transporte'];
        $ivaTransporte=21;
        if (!isset($iva[$ivaTransporte])) $iva[$ivaTransporte]=0;
        if (!isset($base[$ivaTransporte])) $base[$ivaTransporte]=0;   
        if (!isset($bruto[$ivaTransporte])) $bruto[$ivaTransporte]=0;                   
        
        
        $bruto[$ivaTransporte]+=$transporte;
        $valorIva=$transporte*$ivaTransporte/100;
        $valorIva=$valorIva/(1+$ivaTransporte/100);
        $valorIva=round($valorIva,2);
        //echo $valorIva;
        $iva[$ivaTransporte]+=$valorIva;
        $valorBase=$transporte-$valorIva;
        //echo $valorBase;
        $base[$ivaTransporte]+=$valorBase;
        
        $pag=(int)(($linea+14)/54);
           $lineasProducto=$linea-14;
           $paginas=(int)($lineasProducto / 38)+1;
           $lineasProductoUltimaPagina= $lineasProducto % 38;
           
            
           //fijar las lineas con datos prodyctos con altura =20
           for($i=18;$i<$linea;$i++)
                $this->excel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
       
           for($i=0;$i<100;$i++){
           if($linea>43+37*$i && $linea<43+37*$i+12){
                for($j=$linea; $j<43+37*$i+12;$j++){
                   $this->excel->getActiveSheet()->getRowDimension($j)->setRowHeight(20);
                   $linea++;
                }
                break;
           }
           }
         
        //    $hasta=42;
        //    for ($k=0;$k<100;$k++){
        //    if ($linea >42*$k) {$hasta=42+$k*37;}
        //    }
        

        $hasta=38;
           for ($k=0;$k<100;$k++){
                if ($linea >38*$k) {$hasta=38+$k*35;}
           }
           
           for ($k=$linea;$k<$hasta;$k++){
               $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(20);
                   $linea++;
           }
           
           
          
          
           $linea++;
           //echo '$i '.$i. '$linea '.$linea. '$lineasProducto '.$lineasProducto.' $lineasProductoUltimaPagina '.$lineasProductoUltimaPagina;
          
    
            
           
            $this->excel->getActiveSheet()->getStyle('C'.($linea).':I'.$linea)->applyFromArray(array('borders' => array(
                    
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            

            $this->excel->getActiveSheet()->mergeCells('G'.$linea.':H'.$linea);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'TOTAL PRODUCTO');
            $this->excel->getActiveSheet()->getStyle('G' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'Transporte');
                
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $_POST['transporte']);
            
            $this->excel->getActiveSheet()->getStyle('G'.($linea-1).':H'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            
          
            $totalFactura+=$_POST['transporte'];          

            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'B.I.');
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, 'TOTAL IVA');
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
                        //$this->excel->getActiveSheet()->getStyle('C19:I19')->getFont()->setBold(true);
            $border=array(
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
                );
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
        
        
            $linea++;
           ksort($iva);
           $totales['base']=0;
              $totales['iva']=0;
              $totales['bruto']=0;
              
            foreach($bruto as $k=>$v){
              if($bruto[$k]>0)  {
                  $base[$k]=$bruto[$k]/(1+$k/100);
                    $totales['base']+= $base[$k];
                  $iva[$k]=$bruto[$k]-$base[$k];
              $totales['iva']+= $iva[$k];
              $totales['bruto']+= $bruto[$k];
              
              
              $this->excel->getActiveSheet()->setCellValue('F' . $linea, $k);
              $this->excel->getActiveSheet()->setCellValue('G' . $linea, $base[$k]);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $iva[$k]);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $bruto[$k]);
             $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border)); 
            }
             $linea++;
            }
           // $this->excel->getActiveSheet()->setCellValue('G' . $linea, $totales['base']);
           //  $this->excel->getActiveSheet()->setCellValue('H' . $linea, $totales['iva']);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totales['bruto']);
              $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         
        //    $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
      
            
            //pie
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'TOTAL FACTURA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');

            $this->excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 17);
            
            $this->excel->getProperties()->setTitle('Factura núm: '.$numFactura);
            $this->excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->excel->getProperties()->getTitle() . '&RPágina &P de &N');
  
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            
           $linea++;
           $linea++;
            $sizeFont=10;
              //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'En cumplimiento de la Ley Orgánica 15/199, de proteción de datos de carácter personal, sus datos facilitados, figuran en un fichero');
            //   $linea++;
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'automatizado y protegido. Estos Datos no serán cedidos absolutamente a nadie y se utilizaran exclusivamente para estsblecer las facturas a');
            //   $linea++;
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'su nombre y para nuestros comunicados dirigidos a ustedes. En cualquier momento, pueden ejercer su derecho a la cancelación de sus datos ');
            //   $linea++; 
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'de nuestro fichero, mediante comunicación por escrito.');
            


            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Informarle que  tratamos la  información que nos  facilita con el  fin de  realizar pedidos  y  gestionar la  facturación de los  productos y servicios  contratados.');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Los  datos  proporcionados se  conservarán  mientras se  mantenga la  relación  comercial o  durante el  tiempo  necesario para  cumplir  con las  obligaciones');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'legales y  atender las posibles  responsabilidades que  pudieran  derivar del  cumplimiento de la  finalidad para la que los  datos fueron  recabados. Los datos ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'no se  cederán a  terceros  salvo en los casos  en que  exista una  obligación  legal. Usted  tiene  derecho a  obtener  información  de si  estamos  tratando sus ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'datos  personales,  por  lo  que   puede  ejercer  sus  derechos  de  acceso,  rectificación,  supresión  y  portabilidad  de  datos  y  oposición  y  limitación  a  su ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'tratamiento ante 181 Distribucions S.L. con CIF B66154964 Dirección Pg. Sant Joan, 181, local. 08037 Barcelona y Correo electrónico: info@jamonarium.com,');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'adjuntando copia de su DNI o documento  equivalente. Asimismo, y  especialmente si considera que no ha obtenido  satisfacción plena en el ejercicio de sus ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'derechos, podrá presentar una reclamación ante la autoridad nacional de control dirigiéndose a estos efectos a la Agencia Española de Protección de Datos, ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'calle Jorge Juan, 6 – 28001 Madrid.');
            // $linea++;

              $lineUltima=$linea-14;
            
              
       //$linea++;$linea++; 
       //configuración impresora
       $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:J'.$linea);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
       $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);
       $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       $this->excel->getActiveSheet()->getPageMargins()->setTop(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setRight(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setLeft(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setBottom(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setHeader(0.3);
       $this->excel->getActiveSheet()->getPageMargins()->setFooter(0.3);
       $this->excel->getActiveSheet()->getPageSetup()->setHorizontalCentered(false);
       $this->excel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

            
        $filename = "Factura $numero.xls"; //save our workbook as this file name
        $registro=$this->facturas_->registrarFactura($numFactura, $fechaFactura,$ticket['cliente'],$filename);
        if (!$registro) return false;
         
        
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //
       
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        //force user to download the Excel file without writing it to server's HD
       // $objWriter->save('php://output');
        $objWriter->save('facturas/'.$filename);
        return $filename;
       
        }
       //aqui termina factura agrupada 
        
        //aqui inicio factura agrupadas por tickets
         else {
        
        
        foreach($ticketsFactura as $kt => $ticket){
             
        //$ticket = $_POST['ticket'];
        $ticket = $this->tickets_->getTicketPorNumero($ticket, 'pe_boka');
        
        
        $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Ticket núm ' . $ticket['numero']);
        if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
        $linea++;
        
      
        foreach ($ticket['unidades_pesos'] as $k => $v) {
            if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
            /*
             * <?php if ($v =="1" || $v=="3" || $v=='2') { 
                            if($v==2) {$ticket['unidades'][$k]=""; $ticket['productos'][$k]=  ucfirst(strtolower($ticket['productos'][$k]));}
                            ?>  
             * 
             */
            
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //productos unidades
            if ($v == "1" || $v=="3" ) {
              $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, $ticket['codigosProductos'][$k].'      ');
            $this->excel->getActiveSheet()->setCellValue('D' . $linea, $ticket['unidades'][$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
            
            $ticket['preciosUnitarios'][$k]=str_replace(",","",$ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['preciosUnitarios'][$k]);
            
            $ticket['precios'][$k]=str_replace(",","",$ticket['precios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['precios'][$k]);
            $totalFactura=$totalFactura+$ticket['precios'][$k];
            
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;
            $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
            if ($ticket['descuentos'][$k] !=0) {
                if ($linea % 2 ==0){
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
                }
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Su ventaja');
                $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $totalFactura=$totalFactura+$ticket['descuentos'][$k];
                $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
                $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }
            }
        }
       
        foreach ($ticket['unidades_pesos'] as $k => $v) {
            if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //productos pesos
            if ($v == "0" || $v=="4" ) { 
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, $ticket['codigosProductos'][$k].'      ');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            
            $ticket['pesos'][$k]=str_replace(",","",$ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $ticket['pesos'][$k]);
            
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
            
            $ticket['preciosUnitarios'][$k]=str_replace(",","",$ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['preciosUnitarios'][$k]);
            
            $ticket['precios'][$k]=str_replace(",","",$ticket['precios'][$k]);
            
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['precios'][$k]);
            $totalFactura=$totalFactura+$ticket['precios'][$k];
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;  
            $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
            if ($ticket['descuentos'][$k] !=0) {
                if ($linea % 2 ==0){
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
                }
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Su ventaja');
                $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
                $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $totalFactura=$totalFactura+$ticket['descuentos'][$k];
                $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
                $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }
            
            }
       }
        
       foreach ($ticket['unidades_pesos'] as $k => $v) {
            if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //productos entregas negativas
            if ($v == "2" ) { 
                $ticket['pesos'][$k]="";$ticket['unidades'][$k]=""; $ticket['productos'][$k]=  ucfirst(strtolower($ticket['productos'][$k]));

            $this->excel->getActiveSheet()->setCellValue('C' . $linea, $ticket['codigosProductos'][$k].'      ');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $ticket['pesos'][$k]=str_replace(",","",$ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
            $ticket['preciosUnitarios'][$k]=str_replace(",","",$ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['preciosUnitarios'][$k]);
            $ticket['precios'][$k]=str_replace(",","",$ticket['precios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['precios'][$k]);
            $totalFactura=$totalFactura+$ticket['precios'][$k];
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;  
            $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
            if ($ticket['descuentos'][$k] !=0) {
                if ($linea % 2 ==0){
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
                }
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Su ventaja');
                $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
                $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $totalFactura=$totalFactura+$ticket['descuentos'][$k];
                $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
                $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }
            
            }
       }
      
       //Si no existen inicializamos variables para cada tipo de iva
       foreach ($ticket['tipoIvasSum'] as $k => $v) {
           $tipoIVA=(int)$v;
           //echo $tipoIVA.'<br />';
           $tipoIva[$tipoIVA]=$tipoIVA;
           if (!isset($base[$tipoIVA])) $base[$tipoIVA]=0;
           if (!isset($iva[$tipoIVA])) $iva[$tipoIVA]=0;
           if (!isset($bruto[$tipoIVA])) $bruto[$tipoIVA]=0;
        }
        //llemos los valores de este ticket y los sumamos a los valores existentes
        foreach ($ticket['tipoIvasSum'] as $k => $v) {
            $tipoIVA=(int)$v;
            $base[$tipoIVA]+=str_replace(",", "", $ticket['netos'][$k]);
            $iva[$tipoIVA]+=str_replace(",", "", $ticket['ivas'][$k]);
            $bruto[$tipoIVA]+=str_replace(",", "", $ticket['brutos'][$k]);
        }
     
        
        
       
         }

        $transporte=$_POST['transporte'];
        $ivaTransporte=21;
        if (!isset($iva[$ivaTransporte])) $iva[$ivaTransporte]=0;
        if (!isset($base[$ivaTransporte])) $base[$ivaTransporte]=0;   
        if (!isset($bruto[$ivaTransporte])) $bruto[$ivaTransporte]=0;                   
        
        
        $bruto[$ivaTransporte]+=$transporte;
        $valorIva=$transporte*$ivaTransporte/100;
        $valorIva=$valorIva/(1+$ivaTransporte/100);
        $valorIva=round($valorIva,2);
        //echo $valorIva;
        $iva[$ivaTransporte]+=$valorIva;
        $valorBase=$transporte-$valorIva;
        //echo $valorBase;
        $base[$ivaTransporte]+=$valorBase;
         
         //Anchos columnas
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(18.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(56.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(27.17*.68);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(5);

            //altura filas
            
           $pag=(int)(($linea+14)/54);
           $lineasProducto=$linea-14;
           $paginas=(int)($lineasProducto / 38)+1;
           $lineasProductoUltimaPagina= $lineasProducto % 38;
           
            
           //fijar las lineas con datos prodyctos con altura =20
           for($i=18;$i<$linea;$i++)
                $this->excel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
       
           for($i=0;$i<100;$i++){
           if($linea>43+37*$i && $linea<43+37*$i+12){
                for($j=$linea; $j<43+37*$i+12;$j++){
                   $this->excel->getActiveSheet()->getRowDimension($j)->setRowHeight(20);
                   $linea++;
                }
                break;
           }
           }
         
        //    $hasta=42;
        //    for ($k=0;$k<100;$k++){
        //    if ($linea >42*$k) {$hasta=42+$k*37;}
        //    }

        $hasta=38;
           for ($k=0;$k<100;$k++){
                if ($linea >38*$k) {$hasta=38+$k*35;}
           }
           
           for ($k=$linea;$k<$hasta;$k++){
               $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(20);
                   $linea++;
           }
           
           
          
          
           $linea++;
           //echo '$i '.$i. '$linea '.$linea. '$lineasProducto '.$lineasProducto.' $lineasProductoUltimaPagina '.$lineasProductoUltimaPagina;
          
    
            
           
            $this->excel->getActiveSheet()->getStyle('C'.($linea).':I'.$linea)->applyFromArray(array('borders' => array(
                    
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            

            $this->excel->getActiveSheet()->mergeCells('G'.$linea.':H'.$linea);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'TOTAL PRODUCTO');
           $this->excel->getActiveSheet()->getStyle('G' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'Transporte');
                
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $_POST['transporte']);
            
            $this->excel->getActiveSheet()->getStyle('G'.($linea-1).':H'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            
          
            $totalFactura+=$_POST['transporte'];          

            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'B.I.');
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, 'TOTAL IVA');
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
                        //$this->excel->getActiveSheet()->getStyle('C19:I19')->getFont()->setBold(true);
            $border=array(
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
                );
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
        
        
            $linea++;
           ksort($iva);
           $totales['base']=0;
              $totales['iva']=0;
              $totales['bruto']=0;
              
            foreach($iva as $k=>$v){
              if($base[$k]>0)  {
              $totales['base']+= $base[$k];
              $totales['iva']+= $iva[$k];
              $totales['bruto']+= $bruto[$k];
              
              
              $this->excel->getActiveSheet()->setCellValue('F' . $linea, $k);
              $this->excel->getActiveSheet()->setCellValue('G' . $linea, $base[$k]);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $iva[$k]);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $bruto[$k]);
             $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border)); 
            }
             $linea++;
            }
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $totales['base']);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $totales['iva']);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totales['bruto']);
              $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         
        //    $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
      
            
            //pie
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'TOTAL FACTURA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');

            $this->excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 17);
            
            $this->excel->getProperties()->setTitle('Factura núm: '.$numFactura);
            $this->excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->excel->getProperties()->getTitle() . '&RPágina &P de &N');
  
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            
           $linea++;
           $linea++;
            $sizeFont=10;
              //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'En cumplimiento de la Ley Orgánica 15/199, de proteción de datos de carácter personal, sus datos facilitados, figuran en un fichero');
            //   $linea++;
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'automatizado y protegido. Estos Datos no serán cedidos absolutamente a nadie y se utilizaran exclusivamente para estsblecer las facturas a');
            //   $linea++;
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'su nombre y para nuestros comunicados dirigidos a ustedes. En cualquier momento, pueden ejercer su derecho a la cancelación de sus datos ');
            //   $linea++; 
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'de nuestro fichero, mediante comunicación por escrito.');
            


            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Informarle que  tratamos la  información que nos  facilita con el  fin de  realizar pedidos  y  gestionar la  facturación de los  productos y servicios  contratados.');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Los  datos  proporcionados se  conservarán  mientras se  mantenga la  relación  comercial o  durante el  tiempo  necesario para  cumplir  con las  obligaciones');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'legales y  atender las posibles  responsabilidades que  pudieran  derivar del  cumplimiento de la  finalidad para la que los  datos fueron  recabados. Los datos ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'no se  cederán a  terceros  salvo en los casos  en que  exista una  obligación  legal. Usted  tiene  derecho a  obtener  información  de si  estamos  tratando sus ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'datos  personales,  por  lo  que   puede  ejercer  sus  derechos  de  acceso,  rectificación,  supresión  y  portabilidad  de  datos  y  oposición  y  limitación  a  su ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'tratamiento ante 181 Distribucions S.L. con CIF B66154964 Dirección Pg. Sant Joan, 181, local. 08037 Barcelona y Correo electrónico: info@jamonarium.com,');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'adjuntando copia de su DNI o documento  equivalente. Asimismo, y  especialmente si considera que no ha obtenido  satisfacción plena en el ejercicio de sus ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'derechos, podrá presentar una reclamación ante la autoridad nacional de control dirigiéndose a estos efectos a la Agencia Española de Protección de Datos, ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'calle Jorge Juan, 6 – 28001 Madrid.');
            // $linea++;
            
              $lineUltima=$linea-14;
            
              
       //$linea++;$linea++; 
       //configuración impresora
       $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:J'.$linea);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
       $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);
       $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       $this->excel->getActiveSheet()->getPageMargins()->setTop(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setRight(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setLeft(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setBottom(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setHeader(0.3);
       $this->excel->getActiveSheet()->getPageMargins()->setFooter(0.3);
       $this->excel->getActiveSheet()->getPageSetup()->setHorizontalCentered(false);
       $this->excel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

       
        $filename = "Factura $numero.xls"; //save our workbook as this file name
        $registro=$this->facturas_->registrarFactura($numFactura, $fechaFactura,$ticket['cliente'],$filename);
        if (!$registro) return false;
         
        
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //
       
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        //force user to download the Excel file without writing it to server's HD
       // $objWriter->save('php://output');
        $objWriter->save('facturas/'.$filename);
        return $filename;
         }
        //aqui termina factura agrupadas por tickets
    }
        
        function getProveedores(){
            $sql="SELECT id_proveedor, nombre_proveedor FROM pe_proveedores ORDER BY nombre_proveedor";
            $result=$this->db->query($sql);
            $id_proveedores=array();
            $nombre_proveedores=array();
            $options=array();
            $options[0]="Seleccionar un proveedor";
            foreach($result->result() as $k=>$v){
                $id_proveedores[]=$v->id_proveedor;
                $nombre_proveedores[]=$v->nombre_proveedor;
                $options[$v->id_proveedor]=$v->nombre_proveedor;
            }
            return array('options' => $options,'ids' => $id_proveedores,'nombres' => $nombre_proveedores);
        }
        function getAcreedores(){
            $sql="SELECT id_proveedor, nombre_proveedor FROM pe_acreedores ORDER BY nombre";
            $result=$this->db->query($sql);
            $id_proveedores=array();
            $nombres=array();
            $options=array();
            $options[0]="Seleccionar un acreedor";
            foreach($result->result() as $k=>$v){
                $id_proveedores[]=$v->id_proveedor;
                $nombres[]=$v->nombre_proveedor;
                $options[$v->id_proveedor]=$v->nombre_proveedor;
            }
            return array('options' => $options,'ids' => $id_proveedores,'nombres' => $nombres);
        }
        
        function getProveedorAcreedor($id_proveedor_acreedor){
            $sql="SELECT nombre FROM pe_proveedores_acreedores WHERE id_proveedor_acreedor='$id_proveedor_acreedor'";
            if($this->db->query($sql)->num_rows()==0) return 'Sin nombre';
            return $this->db->query($sql)->row()->nombre;
        }
        function getProveedoresAcreedores(){
            $sql="SELECT  a.id_proveedor as id_proveedor, `nombre_proveedor` as nombre  FROM `pe_proveedores` a WHERE status_proveedor=1
                    UNION ALL
                    SELECT  b.id_proveedor*1000 as id_proveedor, `nombre_proveedor` as nombre FROM `pe_acreedores` b WHERE status_acreedor=1
                    ORDER BY trim(nombre)";
            
            
            $result=$this->db->query($sql);
            $id_proveedores=array();
            $nombres=array();
            $options=array();
            $options[0]="Seleccionar un proveedor/acreedor";
            foreach($result->result() as $k=>$v){
                $id_proveedores[]=$v->id_proveedor;
                $nombres[]=$v->nombre;
                $options[$v->id_proveedor]=$v->nombre;
            }
            return array('options' => $options,'ids' => $id_proveedores,'nombres' => $nombres);
        }
        
        function getProductos(){
            $sql="SELECT codigo_producto, nombre FROM pe_productos ORDER BY nombre,codigo_producto";
            $result=$this->db->query($sql);
            $options=array();
            $options[0]="Sin código";
            foreach($result->result() as $k=>$v){
                $options[$v->codigo_producto]=$v->nombre.' ('.$v->codigo_producto.')';
            }
            return array('options' => $options);
        }
        
        function getFacturasProveedores(){
            $sql="SELECT  fp.id,nombre_proveedor, fecha, numFactura 
                  FROM pe_facturas_proveedores fp
                  LEFT JOIN pe_proveedores pr ON fp.id_proveedor=pr.id_proveedor
                  ORDER BY nombre_proveedor,fecha DESC, numFactura DESC";
                    
            $result=$this->db->query($sql);
           
            $options=array();
            $options[0]="Seleccionar una factura";
            foreach($result->result() as $k=>$v){
                $fecha=  fechaEuropeaSinHora($v->fecha);
                $nombre=substr($v->nombre_proveedor,0,25);
                $numFactura=substr($v->numFactura,0,15);
                $options[$v->id]=$nombre.' -- '.$numFactura.' -- '.$fecha;
            }
            return array('options' => $options);
        }
        
        function siguientePedido(){
            $sql=" SELECT MAX(numPedido) as numPedido FROM pe_pedidos_proveedores";
            return $this->db->query($sql)->row()->numPedido+1;
        }
        
        function  getDatosFactura($id_factura){
            $sql="SELECT nombre,numFactura,fecha,otrosCostes,importe, fecha_pago"
                  ."  FROM pe_facturas_proveedores  fp "
                  ."  LEFT JOIN pe_proveedores_acreedores pr ON pr.id_proveedor_acreedor=fp.id_proveedor "
                  ."  WHERE fp.id='$id_factura'";
            
            
            $result=$this->db->query($sql)->row();
            $fecha=  fechaEuropeaSinHora($result->fecha);
            $fechaPago=  fechaEuropeaSinHora($result->fecha_pago);
            $nombre=$result->nombre;
            $numFactura=$result->numFactura;
            $otrosCostes=number_format($result->otrosCostes/100,2);
            $totalFactura=number_format($result->importe/100,2);
            
            $sql="SELECT pr.nombre as nombre, pr.codigo_producto as codigo_producto, lf.cantidad as cantidad, lf.tipoUnidad as tipoUnidad,lf.precio as precio,lf.descuento as descuento,lf.total as total "
                    . " FROM pe_lineas_facturas_proveedores lf "
                    . " LEFT JOIN pe_productos pr ON pr.codigo_producto=lf.codigo_producto"
                    . " WHERE id_factura='$id_factura'";
            $result=$this->db->query($sql);
            $lineas=array();
            foreach($result->result() as $k=>$v){
                $cantidad=  number_format($v->cantidad/1000,2);
                $tipoUnidad=$v->tipoUnidad;
                $precio=  number_format($v->precio/100,2);
                $descuento=  number_format($v->descuento/100,2);
                $total=  number_format($v->total/100,2);
                $lineas[]=array('nombre'=>$v->nombre.' ('.$v->codigo_producto.')', 
                      'cantidad'=>$cantidad ,
                    'tipoUnidad'=>$tipoUnidad,
                     'precio'=>$precio ,
                     'descuento'=>$descuento ,
                     'total'=>$total ,
                     'codigo_producto'=>$v->codigo_producto,
                    );
            }
            
            return array('nombre'=>$nombre,'numFactura'=>$numFactura,'fecha'=>$fecha,'fechaPago'=>$fechaPago,
                    'otrosCostes'=>$otrosCostes,'totalFactura'=>$totalFactura, 'lineas'=>$lineas);
        }
        
        function getFacturas($proveedor){
            $sql="SELECT id,numFactura,fecha,importe FROM pe_facturas_proveedores WHERE id_proveedor='$proveedor'";
            $result=$this->db->query($sql);
            $facturas=array();
            foreach($result->result() as $k=>$v){
                $facturas[$v->id]=$v->numFactura.' - '.  number_format($v->importe/100,2).' - '.fechaEuropeaSinHora($v->fecha);
            }
            if(empty($facturas)) $facturas[0]="No existen facturas";
            return $facturas;
        }
        
        function borrarFactura(){
            $id_factura=$_POST['id_factura'];
            
            $sql="DELETE FROM pe_facturas_proveedores WHERE id='$id_factura'";
            $this->db->query($sql);
            $sql="DELETE FROM pe_lineas_facturas_proveedores WHERE id_factura='$id_factura'";
            $this->db->query($sql);
            return true;
        }
        
        
        function getPrecioCompra($codigo_producto,$proveedor){
            if(!$codigo_producto) return array();
            //primero se busca en la tabla de lineas factura para encontrar el último precio facurado
           $sql="SELECT unidades_caja FROM pe_productos WHERE codigo_producto='$codigo_producto'" ;
           $und_caja=$this->db->query($sql)->row()->unidades_caja;
           
           $sql0="SELECT  lf.precio,lf.descuento  FROM pe_facturas_proveedores fpr "
                ." LEFT JOIN pe_lineas_facturas_proveedores lf ON lf.id_factura=fpr.id "
                ."WHERE  fpr.id_proveedor='$proveedor' AND lf.codigo_producto='$codigo_producto' "
                . "ORDER BY fpr.fecha DESC LIMIT 1";
           if($this->db->query($sql0)->num_rows()){
            $result=$this->db->query($sql0)->row();
            $precio_ultimo=$result->precio*10;    //ojo en lineas facturas precio y descuentos están +100 y en productos * 1000
            $descuento=$result->descuento*10;
           }else {$precio_ultimo=0;$descuento=0;}
           if($precio_ultimo || $descuento) {
               return array('und_caja'=>$und_caja,'precio_ultimo'=>$precio_ultimo,'descuento'=>$descuento,'sql'=>$sql0);
           }
           else {
           
           $sql="SELECT  precio_ultimo_unidad, precio_ultimo_peso,descuento_1_compra FROM pe_productos WHERE codigo_producto='$codigo_producto' AND id_proveedor_web='$proveedor'"; 
           if($this->db->query($sql)->num_rows()){
            $result=$this->db->query($sql)->row();
            $precio_ultimo=$result->precio_ultimo_unidad==0?$result->precio_ultimo_peso:$result->precio_ultimo_unidad;
            $descuento=$result->descuento_1_compra;
           }else {$precio_ultimo=0;$descuento=0;}
           return array('und_caja'=>$und_caja, 'precio_ultimo'=>$precio_ultimo,'descuento'=>$descuento,'sql'=>$sql0);
           }
        }
        
        
        function getNombreProducto($codigo_producto){
            if(trim($codigo_producto)   ==="") return "Sin código";
           $sql="SELECT  nombre FROM pe_productos WHERE codigo_producto='$codigo_producto'"; 
           $nombreProducto=$this->db->query($sql)->row()->nombre;
           return $nombreProducto;
        }
        
        function getNombreGenericoProducto($codigo_producto){
            if(trim($codigo_producto)   ==="") return "Sin código";
           $sql="SELECT  nombre,nombre_generico FROM pe_productos WHERE codigo_producto='$codigo_producto'"; 
           $row=$this->db->query($sql)->row();
           $nombreProducto=trim($row->nombre_generico);
           if(!$nombreProducto) $nombreProducto=$row->nombre;
           return $nombreProducto;
        }


         function grabarAlbaran($numAlbaran,$proveedor,$pedido,$fecha){
             $sql="INSERT INTO pe_albaranes SET num_albaran='$numAlbaran',id_proveedor='$proveedor', id_pedido='$pedido', fecha='$fecha'";
            $this->db->query($sql);
            $sql="SELECT id FROM pe_albaranes ORDER BY id DESC limit 1";
            $id_albaran=$this->db->query($sql)->row()->id;
            return $id_albaran;
         }
         
         
         
          function grabarRecibido($pedido,$fecha){
            if($pedido){
                $sql="UPDATE pe_pedidos_proveedores SET fecha_recibido='$fecha' WHERE id='$pedido'";
                $this->db->query($sql);
            }
            return ;
         }
        
        function grabarFactura($proveedor,$numFactura,$fechaFactura,$otrosCostes,$totalFactura){
            
            $sql="INSERT INTO pe_facturas_proveedores SET id_proveedor='$proveedor', numFactura='$numFactura', fecha='$fechaFactura',otrosCostes='$otrosCostes',importe='$totalFactura' ";
            $this->db->query($sql);
            $sql="SELECT id FROM pe_facturas_proveedores ORDER BY id DESC limit 1";
            $id_factura=$this->db->query($sql)->row()->id;
            return $id_factura;
        }
        
        
        
         function grabarFacturaProveedor($proveedor,
                                $num_albaranes,
                                $numFactura,
                                $fecha,
                                $totalFactura,
                                $otrosCostes,
                                $tipoIva){
             
           // $sql="SELECT num_albaran FROM pe_albaranes WHERE id='$albaran'";
              
           
            //$num_albaran= $num_albaranes;
             
            $sql="INSERT INTO pe_facturas_proveedores SET num_albaran='$num_albaranes',id_proveedor='$proveedor', numFactura='$numFactura', fecha='$fecha',otrosCostes='$otrosCostes',importe='$totalFactura',tipoIva='$tipoIva' ";
            
            $this->db->query($sql);
            $sql="SELECT id FROM pe_facturas_proveedores ORDER BY id DESC limit 1";
            $id_factura=$this->db->query($sql)->row()->id;
            return $id_factura;
        }
       
        
        
        function updateFactura($idFactura, $proveedor,$numFactura,$fechaFactura,$otrosCostes,$totalFactura){            
            $sql="UPDATE pe_facturas_proveedores SET numFactura='$numFactura', fecha='$fechaFactura',otrosCostes='$otrosCostes',importe='$totalFactura' WHERE id='$idFactura'";
            $this->db->query($sql);
            return $idFactura;
        }
        
        function grabarLineasFactura($id_factura,$lineas){
             foreach($lineas as $k=>$v){
                 $codigo_producto=$v['codigo_producto'];
                 $cantidad=$v['cantidades']*1000;
                 $tipoUnidad=$v['tiposUnidades'];
                 $precio=$v['precios']*100;
                 $descuento=$v['descuentos']*100;
                 $total=$v['totales']*100;
                 $tipoIva=$v['tipoIva']*100;
                 $sql="INSERT INTO pe_lineas_facturas_proveedores SET id_factura='$id_factura', codigo_producto='$codigo_producto', cantidad='$cantidad',tipoUnidad='$tipoUnidad',precio='$precio',descuento='$descuento',total='$total',tipoIva='$tipoIva'";
                 $this->db->query($sql);
             }
             return $sql;
        }
        
        function deleteLineasFactura($id_factura){
            $sql="DELETE FROM pe_lineas_facturas_proveedores WHERE id_factura='$id_factura'";
            $this->db->query($sql);
        }
        
        function updateLineasFactura($id_factura,$lineas){
            
             foreach($lineas as $k=>$v){
                 $codigo_producto=$v['codigo_producto'];
                 $cantidad=$v['cantidades']*1000;
                 $tipoUnidad=$v['tiposUnidades'];
                 $precio=$v['precios']*100;
                 $descuento=$v['descuentos']*100;
                 $total=$v['totales']*100;
                 $sql="INSERT INTO pe_lineas_facturas_proveedores SET id_factura='$id_factura', codigo_producto='$codigo_producto', cantidad='$cantidad',tipoUnidad='$tipoUnidad',precio='$precio',descuento='$descuento',total='$total'";
                 $this->db->query($sql);
             }
             return $sql;
        }
        
        function getIdProveedor($pedido){
            $sql="SELECT id_proveedor FROM pe_pedidos_proveedores WHERE id='$pedido'";
            return $this->db->query($sql)->row()->id_proveedor;
        }
        
        //funciones para Pedidos
        function getPedidosProveedores(){
            $sql="SELECT  fp.id,nombre_proveedor, fecha, numPedido 
                  FROM pe_pedidos_proveedores fp
                  LEFT JOIN pe_proveedores pr ON fp.id_proveedor=pr.id_proveedor
                  ORDER BY nombre_proveedor,fecha DESC, numPedido DESC";
                    
            $result=$this->db->query($sql);
           
            $options=array();
            $options[0]="Seleccionar un pedido";
            foreach($result->result() as $k=>$v){
                $fecha=  fechaEuropeaSinHora($v->fecha);
                $nombre=substr($v->nombre_proveedor,0,25);
                $numPedido=substr($v->numPedido,0,15);
                $options[$v->id]=$nombre.' -- '.$numPedido.' -- '.$fecha;
            }
            return array('options' => $options);
        } 
        
         function getAlbaranesProveedores(){
            $sql="SELECT  a.id, pr.nombre_proveedor as nombre_proveedor, a.fecha as fecha, a.num_albaran as num_albaran
                  FROM pe_albaranes a
                  LEFT JOIN pe_proveedores pr ON a.id_proveedor=pr.id_proveedor
                  ORDER BY pr.nombre_proveedor,a.fecha DESC, a.num_albaran DESC";
                    
            $result=$this->db->query($sql);
           
            $options=array();
            $options[0]="Seleccionar un albaran";
            foreach($result->result() as $k=>$v){
                $fecha=  fechaEuropeaSinHora($v->fecha);
                $nombre=substr($v->nombre_proveedor,0,25);
                $num_albaran=substr($v->num_albaran,0,15);
                $options[$v->id] ='Albarán '.$fecha.' - '.$num_albaran ;
            }
            return array('options' => $options);
        } 
        
        function getPedidosAcreedores(){
            $sql="SELECT  fp.id,nombre_proveedor, fecha, numPedido 
                  FROM pe_pedidos_proveedores fp
                  LEFT JOIN pe_acreedores pr ON fp.id_proveedor=pr.id_proveedor
                  ORDER BY nombre_proveedor,fecha DESC, numPedido DESC";
                    
            $result=$this->db->query($sql);
           
            $options=array();
            $options[0]="Seleccionar una factura";
            foreach($result->result() as $k=>$v){
                $fecha=  fechaEuropeaSinHora($v->fecha);
                $nombre=substr($v->nombre_proveedor,0,25);
                $numPedido=substr($v->numPedido,0,15);
                $options[$v->id]=$nombre.' -- '.$numPedido.' -- '.$fecha;
            }
            return array('options' => $options);
        } 
        
         function  getDatosAlbaran($id_albaran){
             $this->db->query("SET SQL_BIG_SELECTS=1 ");
            $sql="   SELECT  "
                    . " a.num_albaran as num_albaran,"
                    . " pr.codigo_producto, "
                    . " pr.nombre as nombre, "
                    . " la.cantidad as cantidad, "
                    . " la.fecha_caducidad as fecha_caducidad, "
                    . " la.tipoUnidad as tipoUnidad, "
                    . " lpro.precio as precio, "
                    . " lpro.total as total,"
                    . " lpro.descuento as descuento,"
                    . " iv.valor_iva as tipoIva"
                    . " FROM pe_albaranes a"
                    . " LEFT JOIN pe_lineas_albaranes la ON la.id_albaran=a.id"
                    . " LEFT JOIN pe_productos pr ON pr.id=la.id_pe_producto"
                    . " LEFT JOIN pe_grupos gr ON gr.id_grupo=pr.id_grupo"
                    . " LEFT JOIN pe_ivas iv ON iv.id_iva=gr.id_iva"
                    . " LEFT JOIN pe_lineas_pedidos_proveedores lpro ON lpro.id_pedido=a.id_pedido AND lpro.codigo_producto=pr.codigo_producto"
                    . " WHERE a.id='$id_albaran'       ";
            // mensaje('getDatosAlbaran '.$sql);
            $result=$this->db->query($sql)->result();
            
            $sql="    SELECT a.fecha as fecha,pe.otrosCostes as otrosCostes, pr.nombre as proveedor, pe.nombreArchivoPedido as pedido, "
                    . " pe.importe as total"
                    . " FROM  pe_albaranes a"
                    . " LEFT JOIN pe_pedidos_proveedores pe ON a.id_pedido=pe.id"
                    . " LEFT JOIN pe_proveedores_acreedores pr ON a.id_proveedor=pr.id_proveedor_acreedor"
                    . " WHERE a.id='$id_albaran' ";
            // mensaje('getDatosAlbaran 2'.$sql);
            $result2=$this->db->query($sql)->row();
            $otrosCostes=number_format($result2->otrosCostes/100,2);
            $totalFactura=number_format($result2->total/100,2);
            $proveedor=$result2->proveedor;
            $pedido=$result2->pedido;
            $fecha=$result2->fecha;
            $lineas=array();
            foreach($result as $k=>$v){
                $num_albaran=$v->num_albaran;
                $cantidad=  number_format($v->cantidad/1000,3);
                $tipoUnidad=$v->tipoUnidad;
                $tipoIva=$v->tipoIva;
                $fecha_caducidad=$v->fecha_caducidad;
                if($tipoUnidad=='Und') $cantidad=  number_format($v->cantidad/1000,0);
                if(is_null($v->precio)) $v->precio="10000000";
                if(is_null($v->descuento)) $v->descuento="10000000";
                if($tipoUnidad=='Und') {
                    $precio=number_format($v->precio/100,2);
                }
                else {
                    $precio=number_format($v->precio/100,2);
                }
                $descuento=  number_format($v->descuento/100,2);
               // $precio=  number_format($v->precio/100,2);
                //$descuento=  number_format($v->descuento/100,2);
                $total=  number_format($v->total/100,2);
                
                $lineas[]=array('nombre'=>$v->nombre.' ('.$v->codigo_producto.')', 
                      'num_albaran'=>$num_albaran,
                      'cantidad'=>$cantidad ,
                      'tipoUnidad'=>$tipoUnidad,
                      'fecha_caducidad'=>$fecha_caducidad,
                      'tipoIva'=>$tipoIva,
                     'precio'=>$precio ,
                     'descuento'=>$descuento ,
                     'total'=>$total ,
                     'codigo_producto'=>$v->codigo_producto,
                     'nombreSinCodigo'=>$v->nombre,
                    );
            }
            return array('otrosCostes'=>$otrosCostes,'fecha'=>$fecha,'pedido'=>$pedido,'total'=>$totalFactura,'lineas'=>$lineas,'proveedor'=>$proveedor);
        }

        function  getDatosViewAlbaran($id_albaran){
            $this->db->query("SET SQL_BIG_SELECTS=1 ");
           $sql="   SELECT  "
                   . " a.num_albaran as num_albaran,"
                   . " pr.codigo_producto, "
                   . " pr.nombre as nombre, "
                   . " la.cantidad as cantidad, "
                   . " la.fecha_caducidad as fecha_caducidad, "
                   . " la.tipoUnidad as tipoUnidad, "
                //    . " lpro.precio as precio, "
                //    . " lpro.total as total,"
                //    . " lpro.descuento as descuento,"
                   . " iv.valor_iva as tipoIva"
                   . " FROM pe_albaranes a"
                   . " LEFT JOIN pe_lineas_albaranes la ON la.id_albaran=a.id"
                   . " LEFT JOIN pe_productos pr ON pr.id=la.id_pe_producto"
                   . " LEFT JOIN pe_grupos gr ON gr.id_grupo=pr.id_grupo"
                   . " LEFT JOIN pe_ivas iv ON iv.id_iva=gr.id_iva"
                //    . " LEFT JOIN pe_lineas_pedidos_proveedores lpro ON lpro.id_pedido=a.id_pedido AND lpro.codigo_producto=pr.codigo_producto"
                   . " WHERE a.id='$id_albaran'       ";
        // mensaje('getDatosViewAlbaran '.$sql);
           $result=$this->db->query($sql)->result();
           
           $sql="    SELECT a.fecha as fecha,pe.otrosCostes as otrosCostes, pr.nombre as proveedor, pe.nombreArchivoPedido as pedido, "
                   . " pe.importe as total"
                   . " FROM  pe_albaranes a"
                   . " LEFT JOIN pe_pedidos_proveedores pe ON a.id_pedido=pe.id"
                   . " LEFT JOIN pe_proveedores_acreedores pr ON a.id_proveedor=pr.id_proveedor_acreedor"
                   . " WHERE a.id='$id_albaran' ";
        // mensaje('getDatosViewAlbaran 2'.$sql);
           $result2=$this->db->query($sql)->row();
           $otrosCostes=number_format($result2->otrosCostes/100,2);
           $totalFactura=number_format($result2->total/100,2);
           $proveedor=$result2->proveedor;
           $pedido=$result2->pedido;
           $fecha=$result2->fecha;
           $lineas=array();
           foreach($result as $k=>$v){
               $num_albaran=$v->num_albaran;
               $cantidad=  number_format($v->cantidad/1000,3);
               $tipoUnidad=$v->tipoUnidad;
               $tipoIva=$v->tipoIva;
               $fecha_caducidad=$v->fecha_caducidad;
               if($tipoUnidad=='Und') $cantidad=  number_format($v->cantidad/1000,0);
            //    if(is_null($v->precio)) $v->precio="10000000";
            //    if(is_null($v->descuento)) $v->descuento="10000000";
               if($tipoUnidad=='Und') {
                //    $precio=number_format($v->precio/100,2);
               }
               else {
                //    $precio=number_format($v->precio/100,2);
               }
            //    $descuento=  number_format($v->descuento/100,2);
              
            //    $total=  number_format($v->total/100,2);
               
               $lineas[]=array('nombre'=>$v->nombre.' ('.$v->codigo_producto.')', 
                     'num_albaran'=>$num_albaran,
                     'cantidad'=>$cantidad ,
                     'tipoUnidad'=>$tipoUnidad,
                     'fecha_caducidad'=>$fecha_caducidad,
                     'tipoIva'=>$tipoIva,
                    // 'precio'=>$precio ,
                    // 'descuento'=>$descuento ,
                    // 'total'=>$total ,
                    'codigo_producto'=>$v->codigo_producto,
                    'nombreSinCodigo'=>$v->nombre,
                   );
           }
           return array('otrosCostes'=>$otrosCostes,'fecha'=>$fecha,'pedido'=>$pedido,'total'=>$totalFactura,'lineas'=>$lineas,'proveedor'=>$proveedor);
       }
        
        
        
        function  getDatosPedido($id_pedido){
            $sql="SELECT pr.id as id_proveedor, nombre_proveedor,numPedido,fecha,otrosCostes,importe "
                  ."  FROM pe_pedidos_proveedores  fp "
                  ."  LEFT JOIN pe_proveedores pr ON pr.id_proveedor=fp.id_proveedor "
                  ."  WHERE fp.id='$id_pedido'";
            
            
            $result=$this->db->query($sql)->row();
            $fecha=  fechaEuropeaSinHora($result->fecha);
            $nombre_proveedor=$result->nombre_proveedor;
            $numPedido=$result->numPedido;
            $otrosCostes=number_format($result->otrosCostes/100,2);
            $totalPedido=number_format($result->importe/100,2);
            $id_proveedor=$result->id_proveedor;
            
            $sql="SELECT pr.nombre as nombre, pr.descuento_1_compra as descuento_1_compra, pr.precio_ultimo_peso as precio_ultimo_peso, pr.precio_ultimo_unidad as precio_ultimo_unidad,pr.codigo_producto as codigo_producto, lf.cantidad as cantidad, lf.tipoUnidad as tipoUnidad,lf.precio as precio,lf.descuento as descuento,lf.total as total "
                    . " FROM pe_lineas_pedidos_proveedores lf "
                    . " LEFT JOIN pe_productos pr ON pr.codigo_producto=lf.codigo_producto"
                    . " WHERE id_pedido='$id_pedido'";
            $result=$this->db->query($sql);
            $lineas=array();
            foreach($result->result() as $k=>$v){
                $cantidad=  number_format($v->cantidad/1000,3);
                $tipoUnidad=$v->tipoUnidad;
                if($tipoUnidad=='Und') $cantidad=  number_format($v->cantidad/1000,0);
                if($tipoUnidad=='Und') {
                    $precio=number_format($v->precio_ultimo_unidad/1000,2);
                }
                else {
                    $precio=number_format($v->precio_ultimo_peso/1000,2);
                }
                $descuento=  number_format($v->descuento_1_compra/1000,2);
               // $precio=  number_format($v->precio/100,2);
                //$descuento=  number_format($v->descuento/100,2);
                $total=  number_format($v->total/100,2);
                
                $lineas[]=array('nombre'=>$v->nombre.' ('.$v->codigo_producto.')', 
                      'cantidad'=>$cantidad ,
                      'tipoUnidad'=>$tipoUnidad,
                     'precio'=>$precio ,
                     'descuento'=>$descuento ,
                     'total'=>$total ,
                     'codigo_producto'=>$v->codigo_producto,
                     'nombreSinCodigo'=>$v->nombre,
                    );
            }
            $siguiente=$this->siguientePedido();
            return array('siguiente'=>$siguiente,'id_proveedor'=>$id_proveedor,'nombre'=>$nombre_proveedor,'numPedido'=>$numPedido,'fecha'=>$fecha,
                    'otrosCostes'=>$otrosCostes,'totalPedido'=>$totalPedido, 'lineas'=>$lineas);
        }
         
        
        function getPedidos($proveedor,$tipo=false){

            if (strtolower($this->session->username) == 'pernilall') {
                if($proveedor==0){
                    $sql="SELECT id,numPedido,fecha,importe FROM pe_pedidos_proveedores ORDER BY fecha DESC";
                  }else{
                    $sql="SELECT id,numPedido,fecha,importe FROM pe_pedidos_proveedores WHERE id_proveedor='$proveedor' ORDER BY fecha DESC";
                  }
            }
            else{
                if($proveedor==0){
                    $sql="SELECT id,numPedido,fecha,importe FROM pe_pedidos_proveedores WHERE fecha>='".strval((date('Y')-1))."-01-01' ORDER BY fecha DESC";
                  }else{
                    $sql="SELECT id,numPedido,fecha,importe FROM pe_pedidos_proveedores WHERE id_proveedor='$proveedor' AND fecha>='".strval((date('Y')-1))."-01-01' ORDER BY fecha DESC";
                  }
            }

            // if($proveedor==0){
            //   $sql="SELECT id,numPedido,fecha,importe FROM pe_pedidos_proveedores ORDER BY fecha DESC";
            // }else{
            //   $sql="SELECT id,numPedido,fecha,importe FROM pe_pedidos_proveedores WHERE id_proveedor='$proveedor' ORDER BY fecha DESC";
            // }

            $result=$this->db->query($sql);
            $pedidos=array();
            foreach($result->result() as $k=>$v){
                if($tipo){
                    $pedidos[]=array('fecha'=>fechaEuropeaSinHora($v->fecha), 'id'=>$v->id, 'numPedido'=>$v->numPedido, 'importe'=>number_format($v->importe/100,2));
                }
                else $pedidos[$v->id]=$v->numPedido.' - '.  number_format($v->importe/100,2).' - '.fechaEuropeaSinHora($v->fecha);
            }
            if($tipo){
                return $pedidos;
            }
            else{
                if(empty($pedidos)) $pedidos[0]="No existen pedidos";
                else $pedidos[0]="Seleccionar un pedido";
                return $pedidos;
            }
        }
        
        
        function borrarPedido(){
            $id_pedido=$_POST['id_pedido'];
            
            $sql="DELETE FROM pe_pedidos_proveedores WHERE id='$id_pedido'";
            $this->db->query($sql);
            $sql="DELETE FROM pe_lineas_pedidos_proveedores WHERE id_pedido='$id_pedido'";
            $this->db->query($sql);
            return true;
        }
        
        function grabarPedido($proveedor,$numPedido,$fechaPedido,$otrosCostes=0,$totalPedido=0){
            //si ya existiera el número de pedido, se borra la info del pedido y sus lineas
            $sql="SELECT id FROM pe_pedidos_proveedores WHERE numPedido='$numPedido'";
            if($this->db->query($sql)->num_rows()>0){
                $result=$this->db->query($sql)->result();
                foreach($result as $k=>$v){
                    $id=$v->id;
                    $sql="DELETE FROM pe_pedidos_proveedores WHERE id='$id'";
                    $this->db->query($sql);
                    $sql="DELETE FROM pe_lineas_pedidos_proveedores WHERE id_pedido='$id'";
                    $this->db->query($sql);
                }
            }
            $otrosCostes*=100;
            $totalPedido*=100;
            $sql="INSERT INTO pe_pedidos_proveedores SET id_proveedor='$proveedor', numPedido='$numPedido', fecha='$fechaPedido',otrosCostes='$otrosCostes',importe='$totalPedido' ";
            $this->db->query($sql);
            $sql="SELECT id FROM pe_pedidos_proveedores ORDER BY id DESC limit 1";
            $id_pedido=$this->db->query($sql)->row()->id;
            return $id_pedido;
        }
        
        function updatePedido($idPedido, $proveedor,$numPedido,$fechaPedido,$otrosCostes,$totalPedido){            
            $otrosCostes*=100;
            $totalPedido*=100;
            $sql="UPDATE pe_pedidos_proveedores SET numPedido='$numPedido', fecha='$fechaPedido',otrosCostes='$otrosCostes',importe='$totalPedido' WHERE id='$idPedido'";
            $this->db->query($sql);
            return $idPedido;
        }
        
        function grabarLineasPedido($id_pedido,$lineas){
             foreach($lineas as $k=>$v){
                 $codigo_producto=$v['codigo_producto'];
                 $cantidad=$v['cantidades']*1000;
                 $tipoUnidad=$v['tiposUnidades'];
                 $precio=$v['precios']*100;
                 $descuento=$v['descuentos']*100;
                 $total=$v['totales']*100;
                 $sql="INSERT INTO pe_lineas_pedidos_proveedores SET id_pedido='$id_pedido', codigo_producto='$codigo_producto', cantidad='$cantidad',tipoUnidad='$tipoUnidad',precio='$precio',descuento='$descuento',total='$total'";
                 $this->db->query($sql);
             }
             return $sql;
        }
        
        function deleteLineasPedido($id_pedido){
            $sql="DELETE FROM pe_lineas_pedidos_proveedores WHERE id_pedido='$id_pedido'";
            $this->db->query($sql);
        }
        
        function updateLineasPedido($id_pedido,$lineas){
            
             foreach($lineas as $k=>$v){
                 $codigo_producto=$v['codigo_producto'];
                 $cantidad=$v['cantidades']*1000;
                 $tipoUnidad=$v['tiposUnidades'];
                 $precio=$v['precios']*100;
                 $descuento=$v['descuentos']*100;
                 $total=$v['totales']*100;
                 $sql="INSERT INTO pe_lineas_pedidos_proveedores SET id_pedido='$id_pedido', codigo_producto='$codigo_producto', cantidad='$cantidad',tipoUnidad='$tipoUnidad', precio='$precio',descuento='$descuento',total='$total'";
                 $this->db->query($sql);
             }
             return $sql;
        }
        
        
        
        
        
        
        
        
        //para eliminar
        
        /* public function getFacturaSiguiente(){
            $sql="SELECT id_factura FROM pe_registroFacturas ORDER BY id DESC LIMIT 1;";
              $query=$this->db->query($sql);
            $row = $query->row();
            if($row) 
            {
                $factura=$row->id_factura;
                $prefijo=  substr($factura, 0, 1);
                $numero=substr($factura, 1);
                $numeroSiguiente=$numero+1;
                $numeroSiguiente=  number_format($numeroSiguiente,0,"","");
                return  $prefijo.$numeroSiguiente;
            }
            else {
                $prefijo='B';
                $numeroSiguiente=date("Y")."00001";
                return  $prefijo.$numeroSiguiente;
            }
        } */
        /*
        public function registrarFactura($numFactura, $fechaFactura,$id_cliente, $nombreArchivoFactura){
            $sql="INSERT INTO pe_registroFacturas (id_factura, fecha_factura, id_cliente,nombreArchivoFactura) values('$numFactura', '$fechaFactura','$id_cliente','$nombreArchivoFactura')";
            $query=$this->db->query($sql);
            return $query;
        } 
        */
        /* function comprobarId_factura($numFactura){
            
            $sql="SELECT id_factura FROM pe_registroFacturas WHERE id_factura='$numFactura'";
            $query=$this->db->query($sql);
            
           return $query->num_rows();
        }
        */
        /*function excelFactura() {
       
         $ticketsFactura=$_POST['ticketsFactura'];
         $fechaFactura=$_POST['fechaFactura'];
         $numFactura=$_POST['numFactura'];
         
         //se comprueba si la factura contiene 2 o más tickets iguales
         if(array_unique($ticketsFactura) != $ticketsFactura)
                return false;
         
         //caberera Factura
        if(true){
         
         $ticket = $this->tickets_->getTicketPorNumero($ticketsFactura[0], 'pe_boka');
         $datosCliente=$this->clientes_->getDatosCliente($ticket['cliente']);

         //var_dump($datosCliente);
        
         $this->exceldrawing->setName('Pernil181');
        $this->exceldrawing->setDescription('Pernil181');
        $logo = 'images/pernil181.png'; // Provide path to your logo file
        $this->exceldrawing->setPath($logo);  //setOffsetY has no effect
        $this->exceldrawing->setCoordinates('C2');
        $this->exceldrawing->setHeight(80); // logo height
        $this->exceldrawing->setWorksheet($this->excel->getActiveSheet()); 
        
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setCellValue('H3', "181 Distribucions S.L."); 
        $this->excel->getActiveSheet()->setCellValue('H4', "Pg.Sant Joan 181"); 
        $this->excel->getActiveSheet()->setCellValue('H5', "08037 Barcelona, España"); 
        $this->excel->getActiveSheet()->setCellValue('H6', "www.jamonarium.com"); 
        $this->excel->getActiveSheet()->setCellValue('H7', "info@jamonarium.com"); 
        $this->excel->getActiveSheet()->setCellValue('C9', "181 Distribucions S.L. Inscrita en el R.M.B. Tomo 44061, Folio 146, Hoja B 446064. Inscripción 1ª.  N.I.F B66154964       "); 
        $this->excel->getActiveSheet()->setCellValue('C11', "FECHA"); 
        $this->excel->getActiveSheet()->setCellValue('C12', "RAZON SOCIAL"); 
        $this->excel->getActiveSheet()->setCellValue('C13', "DIRECCION"); 
        $this->excel->getActiveSheet()->setCellValue('C14', "POBLACIÓN"); 
        $this->excel->getActiveSheet()->setCellValue('C15', "PAIS"); 
        $this->excel->getActiveSheet()->setCellValue('H11', "Nº FACTURA"); 
        $this->excel->getActiveSheet()->setCellValue('H12', "CIF"); 
        $this->excel->getActiveSheet()->setCellValue('H13', "Nº CLIENTE"); 
        $this->excel->getActiveSheet()->setCellValue('H14', "C. POSTAL"); 
        $this->excel->getActiveSheet()->setCellValue('H15', "PROVINCIA"); 
        $this->excel->getActiveSheet()->getStyle('H11:H15')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'FCE9DA')));
        $this->excel->getActiveSheet()->getStyle('C11:C15')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'FCE9DA')));
        //control existencia datos cliente (caso que se haya borrado del listado clientes
      
        
        
        if(!isset($datosCliente['empresa'])) $datosCliente['empresa']="";
        
        $fecha =  substr(fechaEuropea($fechaFactura),0,10);  //substr($ticket['fecha'],0,10);
        $this->excel->getActiveSheet()->setCellValue('D11', $fecha); 
        $this->excel->getActiveSheet()->setCellValue('I11', $numFactura); 
        $this->excel->getActiveSheet()->setCellValue('D12', $datosCliente['empresa']); 
        $this->excel->getActiveSheet()->setCellValue('K12', $datosCliente['correo1']); 
        $this->excel->getActiveSheet()->setCellValue('K13', $datosCliente['correo2']); 
        $this->excel->getActiveSheet()->setCellValue('D13', $datosCliente['direccion']); 
        $this->excel->getActiveSheet()->setCellValue('D14', $datosCliente['poblacion']); 
        $this->excel->getActiveSheet()->setCellValue('D15', $datosCliente['pais']); 
        $this->excel->getActiveSheet()->setCellValue('I12', $datosCliente['nif']); 
        $this->excel->getActiveSheet()->setCellValue('I14', $datosCliente['codigoPostal']); 
        $this->excel->getActiveSheet()->setCellValue('I15', $datosCliente['provincia']); 
        
        
        
        
        
        //$this->excel->getActiveSheet()->setCellValue('D13', $ticket['empresa']); 
        $this->excel->getActiveSheet()->setCellValue('I13', $ticket['cliente']); 
        
        $this->excel->getActiveSheet()->getStyle('I13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->getActiveSheet()->setCellValue('C17', "Ref."); 
        $this->excel->getActiveSheet()->setCellValue('D17', "Unidad"); 
        $this->excel->getActiveSheet()->setCellValue('E17', "Descripción"); 
        $this->excel->getActiveSheet()->setCellValue('F17', "IVA"); 
        $this->excel->getActiveSheet()->setCellValue('G17', "Kilos"); 
        $this->excel->getActiveSheet()->setCellValue('H17', "Precio"); 
        $this->excel->getActiveSheet()->setCellValue('I17', "Importe"); 
        $this->excel->getActiveSheet()->getStyle('C17:I17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C17:I17')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->getStyle('C17:I17')->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
        
        $numero = $numFactura;  // $ticket['numero'];
     
        $linea=18;
        $totalFactura=0;
        
        }
         //Hasta aquí encabesado factura
        
        //lectura datos todos los tickets
        if(true){
        $numTickets=array();
        $productosPrecios=array(); 
        $productosPesos=array();
        $productosUnidades=array();
        $productosNombres=array();
        $productosCodigo=array();
        $productosPreciosU=array();
        $productosImportes=array();
        $productosIvas=array();
        //variable que registra si existe un códigos de producto en varias lineas (todos los tickets)
        //para hacer la factura compilados por productos (si varias lineas con el mismo producto o
        //compilado por tickets.
        $compilacionPorProductos=false;
        
        foreach($ticketsFactura as $kt => $ticket){
            $ticket = $this->tickets_->getTicketPorNumero($ticket, 'pe_boka');
            
            $numTickets[]=$ticket['numero'];
            
            foreach ($ticket['unidades_pesos'] as $k => $v) {
                $codProducto=$ticket['codigosProductos'][$k];
                    $precioProducto=$ticket['preciosUnitarios'][$k];
                    $codigoPrecio=$precioProducto*1000+$codProducto;
                    if(!in_array($codigoPrecio,$productosPrecios)){
                            $productosPrecios[]=$codigoPrecio;
                            $key = array_search($codigoPrecio, $productosPrecios);
                            $productosPesos[$key]=0;
                            $productosUnidades[$key]=0;
                            $productosNombres[$key]="";
                            $productosPreciosU[$key]=$precioProducto;
                            $productosCodigo[$key]=$codProducto;
                            $productosImportes[$key]=0;
                    }
                    if (false !== $key = array_search($codigoPrecio, $productosPrecios)) {
                        $compilacionPorProductos=true;
                        $productosNombres[$key]=$ticket['productos'][$k];
                        $productosIvas[$key]=$ticket['tiposIva'][$k];
                        $productosImportes[$key]+=$ticket['precios'][$k];
                        if ($v == "1" || $v=="3" ) $productosUnidades[$key]+=$ticket['unidades'][$k];
                        if ($v == "0" || $v=="4" ) $productosPesos[$key]+=$ticket['pesos'][$k];
                    } else {
                        // do something else
                    }
                
            }
             
        }
        }
         //lectura datos todos los tickets
        
        //aquí empieza salida agrupada por productos
        if($compilacionPorProductos){
        foreach($numTickets as $k=>$v){
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Ticket núm ' . $v);
        if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
        $linea++;
        }
        foreach($productosPrecios as $k=>$v){
            if($productosUnidades[$k] || $productosPesos[$k]){
            $tipoIVA=(int)$productosIvas[$k];    
            $this->excel->getActiveSheet()->setCellValue('C' . $linea,  $productosCodigo[$k]);
            $this->excel->getActiveSheet()->setCellValue('E' . $linea,  $productosNombres[$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea,  $productosPreciosU[$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea,  $tipoIVA);
            
            if (!isset($bruto[$tipoIVA])) $bruto[$tipoIVA]=0;
            
            //$productosImportes[$k]);
            if($productosUnidades[$k]) {
                $this->excel->getActiveSheet()->setCellValue('D' . $linea,  $productosUnidades[$k]);
                $totalFactura+=$productosUnidades[$k]*$productosPreciosU[$k];
                $bruto[$tipoIVA]+=$productosUnidades[$k]*$productosPreciosU[$k];
                $this->excel->getActiveSheet()->setCellValue('I' . $linea,  $productosUnidades[$k]*$productosPreciosU[$k]);
            }
            if($productosPesos[$k]) {
                $this->excel->getActiveSheet()->setCellValue('G' . $linea,  $productosPesos[$k]);
                $totalFactura+=$productosPesos[$k]*$productosPreciosU[$k];
                $bruto[$tipoIVA]+=$productosPesos[$k]*$productosPreciosU[$k];
                $this->excel->getActiveSheet()->setCellValue('I' . $linea,  $productosPesos[$k]*$productosPreciosU[$k]);
            }
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
       
            
            if ($linea % 2 ==0){
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'e2e2e2')
                                )
                            )
                    );
                }
            $linea++;
            }
        }
        
        
        //Anchos columnas
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(18.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(56.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(27.17*.68);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(5);

         
            
        $transporte=$_POST['transporte'];
        $ivaTransporte=21;
        if (!isset($iva[$ivaTransporte])) $iva[$ivaTransporte]=0;
        if (!isset($base[$ivaTransporte])) $base[$ivaTransporte]=0;   
        if (!isset($bruto[$ivaTransporte])) $bruto[$ivaTransporte]=0;                   
        
        
        $bruto[$ivaTransporte]+=$transporte;
        $valorIva=$transporte*$ivaTransporte/100;
        $valorIva=$valorIva/(1+$ivaTransporte/100);
        $valorIva=round($valorIva,2);
        //echo $valorIva;
        $iva[$ivaTransporte]+=$valorIva;
        $valorBase=$transporte-$valorIva;
        //echo $valorBase;
        $base[$ivaTransporte]+=$valorBase;
        
        $pag=(int)(($linea+14)/54);
           $lineasProducto=$linea-14;
           $paginas=(int)($lineasProducto / 38)+1;
           $lineasProductoUltimaPagina= $lineasProducto % 38;
           
            
           //fijar las lineas con datos prodyctos con altura =20
           for($i=18;$i<$linea;$i++)
                $this->excel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
       
           for($i=0;$i<100;$i++){
           if($linea>43+37*$i && $linea<43+37*$i+12){
                for($j=$linea; $j<43+37*$i+12;$j++){
                   $this->excel->getActiveSheet()->getRowDimension($j)->setRowHeight(20);
                   $linea++;
                }
                break;
           }
           }
         
           $hasta=42;
           for ($k=0;$k<100;$k++){
           if ($linea >42*$k) {$hasta=42+$k*37;}
           }
           
           for ($k=$linea;$k<$hasta;$k++){
               $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(20);
                   $linea++;
           }
           
           
          
          
           $linea++;
           //echo '$i '.$i. '$linea '.$linea. '$lineasProducto '.$lineasProducto.' $lineasProductoUltimaPagina '.$lineasProductoUltimaPagina;
          
    
            
           
            $this->excel->getActiveSheet()->getStyle('C'.($linea).':I'.$linea)->applyFromArray(array('borders' => array(
                    
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            

            $this->excel->getActiveSheet()->mergeCells('G'.$linea.':H'.$linea);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'TOTAL PRODUCTO');
           $this->excel->getActiveSheet()->getStyle('G' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'Transporte');
                
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $_POST['transporte']);
            
            $this->excel->getActiveSheet()->getStyle('G'.($linea-1).':H'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            
          
            $totalFactura+=$_POST['transporte'];          

            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'B.I.');
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, 'TOTAL IVA');
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
                        //$this->excel->getActiveSheet()->getStyle('C19:I19')->getFont()->setBold(true);
            $border=array(
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
                );
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
        
        
            $linea++;
           ksort($iva);
           $totales['base']=0;
              $totales['iva']=0;
              $totales['bruto']=0;
              
            foreach($bruto as $k=>$v){
              if($bruto[$k]>0)  {
                  $base[$k]=$bruto[$k]/(1+$k/100);
                    $totales['base']+= $base[$k];
                  $iva[$k]=$bruto[$k]-$base[$k];
              $totales['iva']+= $iva[$k];
              $totales['bruto']+= $bruto[$k];
              
              
              $this->excel->getActiveSheet()->setCellValue('F' . $linea, $k);
              $this->excel->getActiveSheet()->setCellValue('G' . $linea, $base[$k]);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $iva[$k]);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $bruto[$k]);
             $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border)); 
            }
             $linea++;
            }
           // $this->excel->getActiveSheet()->setCellValue('G' . $linea, $totales['base']);
           //  $this->excel->getActiveSheet()->setCellValue('H' . $linea, $totales['iva']);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totales['bruto']);
              $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         
        //    $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
      
            
            //pie
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'TOTAL FACTURA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');

            $this->excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 17);
            
            $this->excel->getProperties()->setTitle('Factura núm: '.$numFactura);
            $this->excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->excel->getProperties()->getTitle() . '&RPágina &P de &N');
  
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            
           $linea++;
           $linea++;
            $sizeFont=10;
              $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
              $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'En cumplimiento de la Ley Orgánica 15/199, de proteción de datos de carácter personal, sus datos facilitados, figuran en un fichero');
              $linea++;
              $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
              $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'automatizado y protegido. Estos Datos no serán cedidos absolutamente a nadie y se utilizaran exclusivamente para estsblecer las facturas a');
              $linea++;
              $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
              $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'su nombre y para nuestros comunicados dirigidos a ustedes. En cualquier momento, pueden ejercer su derecho a la cancelación de sus datos ');
              $linea++; 
              $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
              $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'de nuestro fichero, mediante comunicación por escrito.');
            
              $lineUltima=$linea-14;
            
              
       //$linea++;$linea++; 
       //configuración impresora
       $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:J'.$linea);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
       $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);
       $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       $this->excel->getActiveSheet()->getPageMargins()->setTop(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setRight(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setLeft(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setBottom(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setHeader(0.3);
       $this->excel->getActiveSheet()->getPageMargins()->setFooter(0.3);
       $this->excel->getActiveSheet()->getPageSetup()->setHorizontalCentered(false);
       $this->excel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

          
        
        
        
            
            
            
            
        $filename = "Factura $numero.xls"; //save our workbook as this file name
        $registro=$this->facturas_->registrarFactura($numFactura, $fechaFactura,$ticket['cliente'],$filename);
        if (!$registro) return false;
         
        
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //
       
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        //force user to download the Excel file without writing it to server's HD
       // $objWriter->save('php://output');
        $objWriter->save('facturas/'.$filename);
        return $filename;
       
        }
       //aqui termina factura agrupada 
        
        //aqui inicio factura agrupadas por tickets
         else {
        
        
        foreach($ticketsFactura as $kt => $ticket){
             
        //$ticket = $_POST['ticket'];
        $ticket = $this->tickets_->getTicketPorNumero($ticket, 'pe_boka');
        
        
        $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Ticket núm ' . $ticket['numero']);
        if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
        $linea++;
        
      
        foreach ($ticket['unidades_pesos'] as $k => $v) {
            if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
            
            
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //productos unidades
            if ($v == "1" || $v=="3" ) {
              $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, $ticket['codigosProductos'][$k].'      ');
            $this->excel->getActiveSheet()->setCellValue('D' . $linea, $ticket['unidades'][$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
            
            $ticket['preciosUnitarios'][$k]=str_replace(",","",$ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['preciosUnitarios'][$k]);
            
            $ticket['precios'][$k]=str_replace(",","",$ticket['precios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['precios'][$k]);
            $totalFactura=$totalFactura+$ticket['precios'][$k];
            
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;
            $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
            if ($ticket['descuentos'][$k] !=0) {
                if ($linea % 2 ==0){
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
                }
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Su ventaja');
                $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $totalFactura=$totalFactura+$ticket['descuentos'][$k];
                $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
                $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }
            }
        }
       
        foreach ($ticket['unidades_pesos'] as $k => $v) {
            if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //productos pesos
            if ($v == "0" || $v=="4" ) { 
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, $ticket['codigosProductos'][$k].'      ');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            
            $ticket['pesos'][$k]=str_replace(",","",$ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $ticket['pesos'][$k]);
            
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
            
            $ticket['preciosUnitarios'][$k]=str_replace(",","",$ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['preciosUnitarios'][$k]);
            
            $ticket['precios'][$k]=str_replace(",","",$ticket['precios'][$k]);
            
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['precios'][$k]);
            $totalFactura=$totalFactura+$ticket['precios'][$k];
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;  
            $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
            if ($ticket['descuentos'][$k] !=0) {
                if ($linea % 2 ==0){
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
                }
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Su ventaja');
                $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
                $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $totalFactura=$totalFactura+$ticket['descuentos'][$k];
                $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
                $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }
            
            }
       }
        
       foreach ($ticket['unidades_pesos'] as $k => $v) {
            if ($linea % 2 ==0){
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
            }
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //productos entregas negativas
            if ($v == "2" ) { 
                $ticket['pesos'][$k]="";$ticket['unidades'][$k]=""; $ticket['productos'][$k]=  ucfirst(strtolower($ticket['productos'][$k]));

            $this->excel->getActiveSheet()->setCellValue('C' . $linea, $ticket['codigosProductos'][$k].'      ');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $ticket['pesos'][$k]=str_replace(",","",$ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
            $ticket['preciosUnitarios'][$k]=str_replace(",","",$ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['preciosUnitarios'][$k]);
            $ticket['precios'][$k]=str_replace(",","",$ticket['precios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['precios'][$k]);
            $totalFactura=$totalFactura+$ticket['precios'][$k];
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;  
            $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
            if ($ticket['descuentos'][$k] !=0) {
                if ($linea % 2 ==0){
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e2e2e2')
                            )
                        )
                );
                }
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Su ventaja');
                $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
                $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $totalFactura=$totalFactura+$ticket['descuentos'][$k];
                $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
                $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }
            
            }
       }
      
       //Si no existen inicializamos variables para cada tipo de iva
       foreach ($ticket['tipoIvasSum'] as $k => $v) {
           $tipoIVA=(int)$v;
           //echo $tipoIVA.'<br />';
           $tipoIva[$tipoIVA]=$tipoIVA;
           if (!isset($base[$tipoIVA])) $base[$tipoIVA]=0;
           if (!isset($iva[$tipoIVA])) $iva[$tipoIVA]=0;
           if (!isset($bruto[$tipoIVA])) $bruto[$tipoIVA]=0;
        }
        //llemos los valores de este ticket y los sumamos a los valores existentes
        foreach ($ticket['tipoIvasSum'] as $k => $v) {
            $tipoIVA=(int)$v;
            $base[$tipoIVA]+=str_replace(",", "", $ticket['netos'][$k]);
            $iva[$tipoIVA]+=str_replace(",", "", $ticket['ivas'][$k]);
            $bruto[$tipoIVA]+=str_replace(",", "", $ticket['brutos'][$k]);
        }
     
        
        
       
         }

        $transporte=$_POST['transporte'];
        $ivaTransporte=21;
        if (!isset($iva[$ivaTransporte])) $iva[$ivaTransporte]=0;
        if (!isset($base[$ivaTransporte])) $base[$ivaTransporte]=0;   
        if (!isset($bruto[$ivaTransporte])) $bruto[$ivaTransporte]=0;                   
        
        
        $bruto[$ivaTransporte]+=$transporte;
        $valorIva=$transporte*$ivaTransporte/100;
        $valorIva=$valorIva/(1+$ivaTransporte/100);
        $valorIva=round($valorIva,2);
        //echo $valorIva;
        $iva[$ivaTransporte]+=$valorIva;
        $valorBase=$transporte-$valorIva;
        //echo $valorBase;
        $base[$ivaTransporte]+=$valorBase;
         
         //Anchos columnas
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(18.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(56.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(27.17*.68);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(5);

            //altura filas
            
           $pag=(int)(($linea+14)/54);
           $lineasProducto=$linea-14;
           $paginas=(int)($lineasProducto / 38)+1;
           $lineasProductoUltimaPagina= $lineasProducto % 38;
           
            
           //fijar las lineas con datos prodyctos con altura =20
           for($i=18;$i<$linea;$i++)
                $this->excel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
       
           for($i=0;$i<100;$i++){
           if($linea>43+37*$i && $linea<43+37*$i+12){
                for($j=$linea; $j<43+37*$i+12;$j++){
                   $this->excel->getActiveSheet()->getRowDimension($j)->setRowHeight(20);
                   $linea++;
                }
                break;
           }
           }
         
           $hasta=42;
           for ($k=0;$k<100;$k++){
           if ($linea >42*$k) {$hasta=42+$k*37;}
           }
           
           for ($k=$linea;$k<$hasta;$k++){
               $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(20);
                   $linea++;
           }
           
           
          
          
           $linea++;
           //echo '$i '.$i. '$linea '.$linea. '$lineasProducto '.$lineasProducto.' $lineasProductoUltimaPagina '.$lineasProductoUltimaPagina;
          
    
            
           
            $this->excel->getActiveSheet()->getStyle('C'.($linea).':I'.$linea)->applyFromArray(array('borders' => array(
                    
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            

            $this->excel->getActiveSheet()->mergeCells('G'.$linea.':H'.$linea);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'TOTAL PRODUCTO');
           $this->excel->getActiveSheet()->getStyle('G' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'Transporte');
                
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $_POST['transporte']);
            
            $this->excel->getActiveSheet()->getStyle('G'.($linea-1).':H'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            
          
            $totalFactura+=$_POST['transporte'];          

            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'B.I.');
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, 'TOTAL IVA');
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
                        //$this->excel->getActiveSheet()->getStyle('C19:I19')->getFont()->setBold(true);
            $border=array(
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
                );
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
        
        
            $linea++;
           ksort($iva);
           $totales['base']=0;
              $totales['iva']=0;
              $totales['bruto']=0;
              
            foreach($iva as $k=>$v){
              if($base[$k]>0)  {
              $totales['base']+= $base[$k];
              $totales['iva']+= $iva[$k];
              $totales['bruto']+= $bruto[$k];
              
              
              $this->excel->getActiveSheet()->setCellValue('F' . $linea, $k);
              $this->excel->getActiveSheet()->setCellValue('G' . $linea, $base[$k]);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $iva[$k]);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $bruto[$k]);
             $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border)); 
            }
             $linea++;
            }
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $totales['base']);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $totales['iva']);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totales['bruto']);
              $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         
        //    $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
      
            
            //pie
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'TOTAL FACTURA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');

            $this->excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 17);
            
            $this->excel->getProperties()->setTitle('Factura núm: '.$numFactura);
            $this->excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->excel->getProperties()->getTitle() . '&RPágina &P de &N');
  
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            
           $linea++;
           $linea++;
            $sizeFont=10;
              $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
              $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'En cumplimiento de la Ley Orgánica 15/199, de proteción de datos de carácter personal, sus datos facilitados, figuran en un fichero');
              $linea++;
              $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
              $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'automatizado y protegido. Estos Datos no serán cedidos absolutamente a nadie y se utilizaran exclusivamente para estsblecer las facturas a');
              $linea++;
              $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
              $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'su nombre y para nuestros comunicados dirigidos a ustedes. En cualquier momento, pueden ejercer su derecho a la cancelación de sus datos ');
              $linea++; 
              $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
              $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'de nuestro fichero, mediante comunicación por escrito.');
            
              $lineUltima=$linea-14;
            
              
       //$linea++;$linea++; 
       //configuración impresora
       $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:J'.$linea);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
       $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);
       $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       $this->excel->getActiveSheet()->getPageMargins()->setTop(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setRight(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setLeft(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setBottom(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setHeader(0.3);
       $this->excel->getActiveSheet()->getPageMargins()->setFooter(0.3);
       $this->excel->getActiveSheet()->getPageSetup()->setHorizontalCentered(false);
       $this->excel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

       
        $filename = "Factura $numero.xls"; //save our workbook as this file name
        $registro=$this->facturas_->registrarFactura($numFactura, $fechaFactura,$ticket['cliente'],$filename);
        if (!$registro) return false;
         
        
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //
       
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        //force user to download the Excel file without writing it to server's HD
       // $objWriter->save('php://output');
        $objWriter->save('facturas/'.$filename);
        return $filename;
         }
        //aqui termina factura agrupadas por tickets
        } 
        */
        
}
        
