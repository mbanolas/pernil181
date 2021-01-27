<?php
class Productos_ extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        //verifica si el código Boka es único
        //si único devuelve id tapla pe_productos
        function esCodigoBokaUnico($id_producto){
            $sql="SELECT id FROM pe_productos WHERE id_producto='$id_producto' AND status_producto='1'";
            if($this->db->query($sql)->num_rows()==1) return $this->db->query($sql)->row()->id;
            return false;
        }

        function getControlStock($id_pe_producto){
            //log_message('INFO',"SELECT control_stock FROM pe_productos WHERE id='$id_pe_producto'");
            // mensaje("SELECT control_stock FROM pe_productos WHERE id='$id_pe_producto'");
            return $this->db->query("SELECT control_stock FROM pe_productos WHERE id='$id_pe_producto'")->row()->control_stock;

        }
        
        function grabarPe_pack($id_pe_producto_pack,$totalPrecio_compra,$totalTarifa_ventaPack,$totalTarifa_venta,$margen,$margenPack){
                
            $sql="SELECT id_pe_producto_pack FROM pe_packs WHERE id_pe_producto_pack='$id_pe_producto_pack'";
            if($this->db->query($sql)->num_rows()==0){
                $sql="INSERT INTO pe_packs SET "
                        . " id_pe_producto_pack='$id_pe_producto_pack', "
                        . " precio_pack='$totalPrecio_compra', "
                        . " pvp_tienda='$totalTarifa_venta', "
                        . " pvp_pack='$totalTarifa_ventaPack', "
                        . " margen_pack='$margenPack', "
                        . " margen_tienda='$margen', "
                        . " nombre='$id_pe_producto_pack'";
                $this->db->query($sql);
            }
            else {
                //log_message('info','==============================================================');
                //log_message('info','$id_pe_producto_pack '.$id_pe_producto_pack);
                //log_message('info','$totalPrecio_compra '.$totalPrecio_compra);
                //log_message('info','$totalTarifa_ventaPack '.$totalTarifa_ventaPack);
                //log_message('info','$totalTarifa_venta '.$totalTarifa_venta);
                
                $sql="UPDATE pe_packs SET "
                        . " id_pe_producto_pack='$id_pe_producto_pack', "
                        . " precio_pack='$totalPrecio_compra', "
                        . " pvp_tienda='$totalTarifa_venta', "
                        . " pvp_pack='$totalTarifa_ventaPack', "
                        . " margen_pack='$margenPack', "
                        . " margen_tienda='$margen', "
                        . " nombre='$id_pe_producto_pack' "
                        . " WHERE id_pe_producto_pack='$id_pe_producto_pack'";
                //log_message('INFO',$sql);
                $this->db->query($sql);
            }
           
            return $sql;
        }
        
        function grabarPe_lineas_pack($id_pe_producto_pack,$codigos,$cantidades,$descuentos){
            $sql="SELECT id FROM pe_packs WHERE id_pe_producto_pack='$id_pe_producto_pack'";
            $id=$this->db->query($sql)->row()->id;
            
            $this->db->query("DELETE FROM pe_lineas_packs WHERE id_pack='$id'");
            foreach($codigos as $k=>$v){
                $cantidad=$cantidades[$k]*1000;
                $descuento=$descuentos[$k]*1000;
                $sql="INSERT INTO pe_lineas_packs SET "
                        . " id_pack='$id', "
                        . " codigo_producto='$v', "
                        . " cantidad='".$cantidad."', "
                        . " descuento='".$descuento."' ";
                $this->db->query($sql);
            }
            
            return $sql;
        }
        
        
        //asignar id de tabla de producto a una entrada de SNR1 y GEW1
        function asignarProducto($snr1,$gew1,$cantidad){
            $gew1=abs($gew1);
            $sql="SELECT  id,codigo_producto,peso_real,id_producto FROM  pe_productos WHERE id_producto='$snr1' AND status_producto=1  ORDER BY peso_real,anada";
            $numSnr1=$this->db->query($sql)->num_rows();
            //un solo SNR1 activo -> asignación directa
            if($numSnr1==1){
                $row=$this->db->query($sql)->row();
                $tipoUnidad=$this->getUnidad($row->id);
                if($tipoUnidad=="Kg") $cantidad=$gew1/1000;
                return array('cantidad'=>$cantidad,'sql'=>$sql,'id'=>$row->id,'codigo_producto'=>$row->codigo_producto);
            }
            //varios SNR1 activos, con GEW1=0 -> se trata de un vino
            // se asigna la añada más vieja
            if($numSnr1>1 && $gew1==0){
                $row=$this->db->query($sql)->row();
                return array('cantidad'=>$cantidad,'sql'=>$sql,'id'=>$row->id,'codigo_producto'=>$row->codigo_producto);
            }
            
            
           if(false){
            if($numSnr1>1 && $gew1!=0){
                $result=$this->db->query($sql)->result();
                $idAsignado=0;
               
                foreach($result as $k=>$v){
                    $peso_real=$v->peso_real;
                    if($idAsignado==0 && $peso_real!=0) $idAsignado=$v->id;
                    if($peso_real!=0 && $peso_real<=$gew1) {
                        $idAsignado=$v->id;
                       
                       // echo $peso_real.' '.$gew1.' '.'<br>';
                       // break;
                    }
                }
                if($peso_real<=$gew1){
                    $encontrado=true;
                }
                else{
                   $encontrado=false;
                }
                //echo 'encontrado '.$encontrado.'<br>';
                $cantidad=1;
                
                //if($encontrado) {
                //    $resultado=$this->calcularCantidades($snr1,$gew1,$cantidad);
                //    $idAsignado=$resultado['idAsignado'];
                //    $cantidad=$resultado['cantidad'];
                //    }
                    
                return array('cantidad'=>$cantidad,'sql'=>$sql,'id'=>$idAsignado,'codigo_producto'=>$this->getCodigoProducto($idAsignado));
            }
           }
           
          if(true){  
             if($numSnr1>1 && $gew1!=0){
                //log_message('INFO','Aplicando asignación producto por peso '.$numSnr1.' '.$snr1.' '.$gew1);
                //log_message('INFO','Aplicando asignación producto por peso $sql '.$sql); 
                $result=$this->db->query($sql)->result();
                $idAsignado=0;
                
                //log_message('INFO','peso_venta $gew1 '.$gew1); 
                foreach($result as $k=>$v){
                    $peso_real=$v->peso_real;
                    //log_message('INFO','peso_venta $gew1 '.$gew1); 
                    //log_message('INFO','peso_real '.$peso_real); 
                    if($idAsignado==0 && $peso_real>=$gew1){
                        $idAsignado=$v->id;
                        //log_message('INFO','id_pe_producto por peso inferior o igual '.$idAsignado); 
                        break;
                    }
                    $peso_realSiguiente=$peso_real;
                    if(isset($result[$k+1]))
                        $peso_realSiguiente=$result[$k+1]->peso_real;
                    if($peso_real<$gew1 && $peso_realSiguiente>=$gew1){
                        //log_message('INFO','peso_real '.$peso_real); 
                        //log_message('INFO','peso_realSiguiente '.$peso_realSiguiente); 
                        //log_message('INFO','peso_venta $gew1 '.$gew1); 
                        if(abs($gew1-$peso_real) < abs($gew1-$peso_realSiguiente)){
                            $idAsignado=$v->id;
                        }
                        else $idAsignado=$result[$k+1]->id;
                        break;
                    }
                    if($peso_real<=$gew1){
                        $idAsignado=$v->id;
                    }
                } 
                $rangos=array();
                foreach($result as $k=>$v){
                   $rangos[]=$v->peso_real; 
                }
                $rangosTexto=implode (', ', $rangos);
                $peso_asignado=$this->db->query("SELECT peso_real FROM pe_productos WHERE id='$idAsignado'")->row()->peso_real;
                $this->db->query("INSERT INTO pe_asignacion_productos SET "
                        . " id_producto='$snr1',"
                        . " num_productos='$numSnr1',"
                        . " peso_vendido='$gew1',"
                        . " id_asignado='$idAsignado',"
                        . " peso_asignado='$peso_asignado',"
                        . " rangos='$rangosTexto' ");
                //log_message('INFO','id_pe_producto final'.$idAsignado); 
                if($peso_real<=$gew1){
                    $encontrado=true;
                }
                else{
                   $encontrado=false;
                }
                //echo 'encontrado '.$encontrado.'<br>';
                $cantidad=1;
                
                //if($encontrado) {
                //    $resultado=$this->calcularCantidades($snr1,$gew1,$cantidad);
                //    $idAsignado=$resultado['idAsignado'];
                //    $cantidad=$resultado['cantidad'];
                //    }
                
                return array('cantidad'=>$cantidad,'sql'=>$sql,'id'=>$idAsignado,'codigo_producto'=>$this->getCodigoProducto($idAsignado));
            }
          }
          
            return array('cantidad'=>$cantidad,'sql'=>$sql,'id'=>0,'codigo_producto'=>0);

        }
        
        function getDatosProductoId($id_pe_prodiucto){
            return 'Hola';
        }
        
        function calculoMargenProducto($precio_compra,$tarifa_venta,$iva){
            //log_message('INFO', $precio_compra.' '.$tarifa_venta.' '.$iva);
            //recibe datos x1000 
            // devuelve margen x1000
            if($tarifa_venta==0) return 0;
           
            $margen=(100*$tarifa_venta-$precio_compra*(100+$iva/1000))/$tarifa_venta;
            //log_message('INFO', $margen);
            return number_format($margen*1000,0,".","");
        }
        
        
        function calcularCantidades($snr1,$gew1,$cantidad){
            $sql="SELECT  avg(peso_real) as promedio FROM  pe_productos WHERE id_producto='$snr1' AND status_producto=1  ORDER BY peso_real,anada";
            $promedio=$this->db->query($sql)->row()->promedio;
            
            $sql="SELECT  id,codigo_producto,peso_real,id_producto FROM  pe_productos WHERE id_producto='$snr1' AND status_producto=1  ORDER BY peso_real,anada";
            $numSnr1=$this->db->query($sql)->num_rows();
            $idAsignado=0;
            foreach($this->db->query($sql)->result() as $k=>$v){
                $peso_real=$v->peso_real;
                if($idAsignado==0 && $peso_real!=0) $idAsignado=$v->id;
                if($peso_real!=0 && $peso_real<=$promedio) $idAsignado=$v->id;
            }
            $cantidad=round($gew1/$promedio); 
            return array('idAsignado'=>$idAsignado,'cantidad'=>$cantidad);
        }
        
        
        function activarProducto($id_pe_producto){
            $this->db->update('pe_productos',array('status_producto' => '1',"fecha_modificacion" => date('Y-m-d')),array('id' => $id_pe_producto));
            $id=$this->esPack($id_pe_producto);
            if($id)
                $this->db->query("UPDATE pe_packs SET activo=1 WHERE id='$id'");
            
            
            $id_proveedor_web=$this->db->query("SELECT id_proveedor_web FROM pe_productos WHERE id='$id_pe_producto'")->row()->id_proveedor_web;
            $actvo=$this->db->query("SELECT id_proveedor_web,status_producto FROM pe_productos WHERE id='$id_pe_producto'")->row()->status_producto;


            $sql="SELECT * FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()>0){
                $sql="UPDATE pe_stocks SET activo='$actvo',proveedor='$id_proveedor_web', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto' WHERE id_pe_producto='$id_pe_producto'";
                $this->db->query($sql);
            }else {
               //no se inserta porque no existe (stock=0)
            }

            $sql="SELECT * FROM pe_stocks_totales WHERE id_pe_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()>0){
                $sql="UPDATE pe_stocks_totales SET activo='$actvo',proveedor='$id_proveedor_web', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto' WHERE id_pe_producto='$id_pe_producto'";
                $this->db->query($sql);
            }else {
               $sql="INSERT INTO  pe_stocks_totales SET  activo='$actvo',proveedor='$id_proveedor_web', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto'";
               $this->db->query($sql); 
            }
            
            $this->db->query("UPDATE pe_embalajes SET activo='$actvo' WHERE codigo_producto='$id_pe_producto'");

            return;
        }
        
        function esPack($id_pe_producto){
            $sql="SELECT * FROM pe_packs WHERE id_pe_producto_pack='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return 0;
            else 
            return $this->db->query("SELECT * FROM pe_packs WHERE id_pe_producto_pack='$id_pe_producto'")->row()->id;
        }
        
        function desactivarProducto($id_pe_producto){
            $this->db->update('pe_productos',array('status_producto' => '0',"fecha_modificacion" => date('Y-m-d')),array('id' => $id_pe_producto));
            $id=$this->esPack($id_pe_producto);
            if($id)
                $this->db->query("UPDATE pe_packs SET activo=0 WHERE id='$id'");
                
                
            $id_proveedor_web=$this->db->query("SELECT id_proveedor_web FROM pe_productos WHERE id='$id_pe_producto'")->row()->id_proveedor_web;
            $row=$this->db->query("SELECT id_proveedor_web,status_producto FROM pe_productos WHERE id='$id_pe_producto'")->row();
            $activo=$row->status_producto;
            // mensaje("SELECT id_proveedor_web,status_producto FROM pe_productos WHERE id='$id_pe_producto'");
            // mensaje($activo);

            $sql="SELECT * FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()>0){
                $sql="UPDATE pe_stocks SET activo='$activo',proveedor='$id_proveedor_web', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto' WHERE id_pe_producto='$id_pe_producto'";
                $this->db->query($sql);
            }else {
               //no se inserta porque no existe (stock=0)
            }

            $sql="SELECT * FROM pe_stocks_totales WHERE id_pe_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()>0){
                $sql="UPDATE pe_stocks_totales SET activo='$activo',proveedor='$id_proveedor_web', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto' WHERE id_pe_producto='$id_pe_producto'";
                $this->db->query($sql);
            }else {
               $sql="INSERT INTO  pe_stocks_totales SET  activo='$activo',proveedor='$id_proveedor_web', codigo_producto='$id_pe_producto', codigo_bascula='$id_pe_producto', id_pe_producto='$id_pe_producto'";
               $this->db->query($sql); 
            }
            
            $this->db->query("UPDATE pe_embalajes SET activo='$activo' WHERE codigo_producto='$id_pe_producto'");

            //al desactiva productos los stocks se ponen a cero - solicitado por Sergi
            $this->db->query("UPDATE pe_stocks_totales SET cantidad='0' WHERE id_pe_producto='$id_pe_producto'");
            $this->db->query("DELETE FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'");
            
           return;
        }
        
        function getNombreFamilia($codigo_producto){
            $sql="SELECT f.nombre_familia FROM pe_productos p LEFT JOIN pe_familias f ON f.id_familia=p.id_familia WHERE p.codigo_producto='$codigo_producto'";
            if($this->db->query($sql)->num_rows()){
                return $this->db->query($sql)->row()->nombre_familia;
            }    
            return ""; 
        }
        
        function getNombreGrupo($codigo_producto){
            $sql="SELECT g.nombre_grupo FROM pe_productos p LEFT JOIN pe_grupos g ON g.id_grupo=p.id_grupo WHERE p.codigo_producto='$codigo_producto'";
           if($this->db->query($sql)->num_rows()){
                return $this->db->query($sql)->row()->nombre_grupo;
            }    
            return ""; 
        }
        
        function getFamilias($id_grupo){
            $result=$this->db->query("SELECT gf.id_familia as familia,nombre_familia as nombre FROM pe_grupos_familias gf LEFT JOIN pe_familias fa ON gf.id_familia=fa.id_familia  WHERE gf.id_grupo='$id_grupo' ORDER BY nombre_familia")->result();
            $familias=array();
            foreach($result as $k=>$v){
                $familias[]=array($v->familia,$v->nombre);
            }            
            return $familias;
        }
            function regularizarDatosProducto($primary_key){
            //echo $primary_key;
            $sql="SELECT p.id as id, "
                . "p.codigo_producto as codigo_producto, "
                . "p.nombre as nombre, "
                . "precio_ultimo_unidad as precio_ultimo_unidad, "
                . "precio_ultimo_peso as precio_ultimo_peso, "
                . "descuento_1_compra as dto, "
                . "peso_real/1000 as peso, "
                . "descuento_profesionales as descuento_profesionales, "
                . "descuento_profesionales_vip as descuento_profesionales_vip, "
                . "tarifa_venta_unidad as tarifa_venta_unidad, "
                . "tarifa_venta_peso as tarifa_venta_peso, "
                . "p.iva as iva, "
                . "valor_iva as tipo_iva "
                . "FROM pe_productos p "
                . " LEFT JOIN pe_grupos gr ON p.id_grupo=gr.id_grupo "
                . " LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva "
                . " WHERE p.id='$primary_key'";
            $row=$this->db->query($sql)->row();
            $iva=$row->tipo_iva*1000;
            $precio_compra=$this->productos_->getPrecioCompraFinal($row->codigo_producto);
            
            $precio_transformacion=$this->productos_->precioTransformacionFinal($row->id);
            $tarifa_venta=$this->productos_->tarifaVentaFinal($row->id);
            $tipo_unidad="---";
            if($row->precio_ultimo_unidad && $row->tarifa_venta_unidad && !$row->precio_ultimo_peso && !$row->tarifa_venta_peso) 
                $tipo_unidad="Und";
            if(!$row->precio_ultimo_unidad && !$row->tarifa_venta_unidad && $row->precio_ultimo_peso && $row->tarifa_venta_peso) 
                $tipo_unidad="Kg";
            
            $tarifa_profesionales=$this->calculoTarifaProfesionales($primary_key);
            $tarifa_profesionales_vip=$this->calculoTarifaProfesionalesVip($primary_key);
            $margen_venta_profesionales=$this->calculoMargenProducto($precio_compra,$tarifa_profesionales,0);
            $margen_venta_profesionales_vip=$this->calculoMargenProducto($precio_compra,$tarifa_profesionales_vip,0);

            /*
            $tarifa_profesionales=(100-$row->descuento_profesionales/1000)*($tarifa_venta/(100+$iva/100000)); 
            $tarifa_profesionales_vip=(100-$row->descuento_profesionales_vip/1000)*($tarifa_venta/(100+$iva/100000)); 
            $margen_profesionales=0;
            if($tarifa_profesionales!=0)
                $margen_profesionales=($tarifa_profesionales-$precio_compra)*100000/$tarifa_profesionales;
            $margen_profesionales_vip=0;
            if($tarifa_profesionales_vip!=0)
                $margen_profesionales_vip=($tarifa_profesionales_vip-$precio_compra)*100000/$tarifa_profesionales_vip;
            */
            
            
            $margen_real_producto=$this->productos_->calculoMargenProducto($precio_compra,$tarifa_venta,$iva);
            $sql="UPDATE pe_productos SET "
                    . " precio_compra='".$precio_compra."', "
                    . " margen_real_producto='".$margen_real_producto."', "
                    . " tipo_unidad='".$tipo_unidad."', "
                    . " tarifa_venta='".$tarifa_venta."',"
                    . " tarifa_profesionales='".$tarifa_profesionales."',"
                    . " margen_venta_profesionales='".$margen_venta_profesionales."',"
                    . " tarifa_profesionales_vip='".$tarifa_profesionales_vip."',"
                    . " margen_venta_profesionales_vip='".$margen_venta_profesionales_vip."' "
                    
                    . " WHERE id='".$row->id."'";
            //echo $sql;
            //log_message('INFO','regularizarDatosProducto '.$sql);
            $query=$this->db->query($sql);
            
            //copiar datos catálogos en otros idiomas
            $sql="SELECT * FROM pe_productos WHERE id='$primary_key'";
            $row=$this->db->query($sql)->row();
            
            // $cat_nombre=$row->cat_nombre;
            // $cat_nombre_en=$row->cat_nombre_en;
            // $cat_nombre_fr=$row->cat_nombre_fr;
            // if($row->cat_nombre=="") $cat_nombre=$row->nombre;
            // if($row->cat_nombre_en=="") $cat_nombre_en=$row->nombre;
            // if($row->cat_nombre_fr=="") $cat_nombre_fr=$row->nombre;
            
            // $cat_url_producto=$row->cat_url_producto;
            // $cat_url_producto_en=$row->cat_url_producto_en;
            // $cat_url_producto_fr=$row->cat_url_producto_fr;
            // if($row->cat_url_producto=="") $cat_url_producto=$row->url_imagen_portada;
            // if($row->cat_url_producto_en=="") $cat_url_producto_en=$row->url_imagen_portada;
            // if($row->cat_url_producto_fr=="") $cat_url_producto_fr=$row->url_imagen_portada;
            
            // $cat_marca=$row->cat_marca;
            // $cat_marca_en=$row->cat_marca_en;
            // $cat_marca_fr=$row->cat_marca_fr;
            // if($row->cat_marca_en=="") $cat_marca_en=$cat_marca;
            // if($row->cat_marca_fr=="") $cat_marca_fr=$cat_marca;
            
            // $cat_origen=$row->cat_origen;
            // $cat_origen_en=$row->cat_origen_en;
            // $cat_origen_fr=$row->cat_origen_fr;
            // if($row->cat_origen_en=="") $cat_origen_en=$cat_origen;
            // if($row->cat_origen_fr=="") $cat_origen_fr=$cat_origen;
            
            // $cat_tipo_de_uva=$row->cat_tipo_de_uva;
            // $cat_tipo_de_uva_en=$row->cat_tipo_de_uva_en;
            // $cat_tipo_de_uva_fr=$row->cat_tipo_de_uva_fr;
            // if($row->cat_tipo_de_uva_en=="") $cat_tipo_de_uva_en=$cat_tipo_de_uva;
            // if($row->cat_tipo_de_uva_fr=="") $cat_tipo_de_uva_fr=$cat_tipo_de_uva;
            
            // $cat_referencia=$row->codigo_producto;
            // $cat_referencia_en=$row->cat_referencia_en;
            // $cat_referencia_fr=$row->cat_referencia_fr;
            // if($row->cat_referencia=="") $cat_referencia=$row->codigo_producto;
            // if($row->cat_referencia_en=="") $cat_referencia_en=$row->codigo_producto;
            // if($row->cat_referencia_fr=="") $cat_referencia_fr=$row->codigo_producto;
            
            
            // $sql="UPDATE pe_productos SET "
            //         . " cat_nombre='$cat_nombre', "
            //         . " cat_nombre_en='$cat_nombre_en',"
            //         . " cat_nombre_fr='$cat_nombre_fr', "
                    
            //         . " cat_url_producto='$cat_url_producto', "
            //         . " cat_url_producto_en='$cat_url_producto_en',"
            //         . " cat_url_producto_fr='$cat_url_producto_fr', "    
                   
            //         . " cat_marca_en='$cat_marca_en',"
            //         . " cat_marca_fr='$cat_marca_fr', "
               
            //         . " cat_origen_en='$cat_origen_en',"
            //         . " cat_origen_fr='$cat_origen_fr', "
                
            //         . " cat_tipo_de_uva_en='$cat_tipo_de_uva_en',"
            //         . " cat_tipo_de_uva_fr='$cat_tipo_de_uva_fr', "
                    
            //         . " cat_referencia='$cat_referencia', "
            //         . " cat_referencia_en='$cat_referencia_en',"
            //         . " cat_referencia_fr='$cat_referencia_fr' "
                  
            //         . " WHERE id='$primary_key'";
            // $this->db->query($sql);
        }
        
        function regularizarStocks($primary_key){
            
            $sql="SELECT p.status_producto as activo, p.id_proveedor_web as id_proveedor_web FROM pe_stocks s LEFT JOIN pe_productos p ON p.id=s.id_pe_producto
                     WHERE id_pe_producto='$primary_key'";
            
            if($this->db->query($sql)->num_rows()>0){
                $row=$this->db->query($sql)->row();
                $activo=$row->activo;
                $id_proveedor_web=$row->id_proveedor_web;
                $sql="UPDATE pe_stocks SET activo='$activo',proveedor='$id_proveedor_web', codigo_producto='$primary_key', codigo_bascula='$primary_key', id_pe_producto='$primary_key' WHERE id_pe_producto='$primary_key'";
                $this->db->query($sql);
            }else {
               //no se inserta porque no existe (stock=0)
            }
        
            $sql="SELECT p.status_producto as activo, p.id_proveedor_web as id_proveedor_web FROM pe_stocks_totales s
                        LEFT JOIN pe_productos p ON p.id=s.id_pe_producto
                         WHERE id_pe_producto='$primary_key'";
            if($this->db->query($sql)->num_rows()>0){
                $row=$this->db->query($sql)->row();
                $activo=$row->activo;
                $id_proveedor_web=$row->id_proveedor_web;
               
                $sql="UPDATE pe_stocks_totales SET activo='$activo',proveedor='$id_proveedor_web', codigo_producto='$primary_key', codigo_bascula='$primary_key', id_pe_producto='$primary_key' WHERE id_pe_producto='$primary_key'";
                
                $this->db->query($sql);
            }else {
                $sql="SELECT status_producto,id_proveedor_web FROM pe_productos WHERE id='$primary_key'";
                $row=$this->db->query($sql)->row();
                $activo=$row->status_producto;
                $id_proveedor_web=$row->id_proveedor_web;
                $hoy=date('Y-m-d');
               $sql="INSERT INTO  pe_stocks_totales SET  activo='$activo',proveedor='$id_proveedor_web', cantidad='0', fecha_modificacion_stock='$hoy',  codigo_producto='$primary_key', codigo_bascula='$primary_key', id_pe_producto='$primary_key'";
                
               $this->db->query($sql); 
            }
            
            //si status_producto=0 -> descatalogado, se pone stock=0
            
            
            
        }
         function ponerNombresGenericosTablaConversiones(){
             $sql="SELECT * FROM pe_conversiones";
             $result=$this->db->query($sql)->result();
             foreach($result as $k=>$v){
                $row=$this->db->query("SELECT nombre, nombre_generico FROM pe_productos WHERE id_producto='".$v->id_codigo_inicio."' LIMIT 1")->row();
                 $codigo_producto_inicio=$row->nombre_generico==''?$row->nombre:$row->nombre_generico;
                 $id=$v->id;
                 $this->db->query("UPDATE pe_conversiones SET codigo_producto_inicio='$codigo_producto_inicio' WHERE id='$id'");
                
                 $row=$this->db->query("SELECT nombre, nombre_generico FROM pe_productos WHERE id_producto='".$v->id_codigo_final."' LIMIT 1")->row();
                 $codigo_producto_final=$row->nombre_generico==''?$row->nombre:$row->nombre_generico;
                
                 $this->db->query("UPDATE pe_conversiones SET codigo_producto_final='$codigo_producto_final' WHERE id='$id'");
             }
         }


        //poner en tbla productos el stock total y su valoracion
        function ponerStockValor($id){
            $sql="SELECT cantidad,valoracion FROM pe_stocks_totales WHERE id_pe_producto='$id'";
            if($this->db->query($sql)->num_rows()==0) {
                $sql="SELECT id, codigo_producto, nombre FROM pe_productos WHERE id='$id'";
                if($this->db->query($sql)->num_rows()==1){
                    $this->db->query("UPDATE pe_productos SET stock_total='999999', valoracion='999999'");
                }
                return false;
            }
            $row=$this->db->query($sql)->row();
            $cantidad=$row->cantidad/1000;
            $this->db->query("UPDATE pe_productos SET stock_total='$cantidad', valoracion=precio_compra*$cantidad/1000 WHERE id='$id'");
            return true;
        }

        //pone los precios i tarifas en todos los que tienen mismo código báscula
        function regularizarPrecios($primary_key){
            //update packs que contienen este producto
            $this->regularizarPacks($primary_key);
            
            $sql="SELECT * FROM pe_productos WHERE id='$primary_key'";
            
            $row=$this->db->query($sql)->row();
            if($row->id_producto==0 || $row->id_producto==10000) return;

            $peso_real=$row->peso_real;
           //if($row->id_proveedor_web==92) return; //si el proveedor es Perni1 181 (transformación, NO se regularizan precios, ni tarifas.
            
            if ($peso_real==0) return;
            $descuento_1_compra=$row->descuento_1_compra;
            $ratioPrecioUnidad=$row->precio_ultimo_unidad/$peso_real*1000;
            $ratioPrecioPeso=$row->precio_ultimo_peso/$peso_real*1000;
            $ratioTarifaUnidad=$row->tarifa_venta_unidad/$peso_real*1000;
            $ratioTarifaPeso=$row->tarifa_venta_peso/$peso_real*1000;
            $ratioTarifaVenta=$row->tarifa_venta/$peso_real*1000;
            $ratioTarifaProfesionales=$row->tarifa_profesionales/$peso_real*1000;
            $ratioTarifaProfesionalesVip=$row->tarifa_profesionales_vip/$peso_real*1000;
            $ratioPrecioCompra=$row->precio_compra/$peso_real*1000;
            $ratioPrecioTransformacionUnidad=$row->precio_transformacion_unidad/$peso_real*1000;
            $ratioPrecioTransformacionPeso=$row->precio_transformacion_peso/$peso_real*1000;
            $margen_venta_profesionales=$row->margen_venta_profesionales;
            $margen_venta_profesionales_vip=$row->margen_venta_profesionales_vip;
            $id=$row->id*1000;
            $id_proveedor_web=$row->id_proveedor_web;
            //$this->db->query("UPDATE pe_productos SET descuento_1_compra='$ratioPrecioUnidad' WHERE id='$primary_key'");
            $codigo_bascula=$row->id_producto;
            $sql="SELECT * FROM pe_productos WHERE id_producto='$codigo_bascula'";
            if($this->db->query($sql)->num_rows()==1) return;
            $result=$this->db->query($sql)->result();
            foreach($result as $k=>$v){
                //update packs que contienen este producto
                $this->regularizarPacks($v->id);
                
                
                $peso_real=$v->peso_real;
                $id=$v->id;
                if($peso_real!=0){
                   
                    
                    //$this->db->query("UPDATE pe_productos SET id_proveedor_web='$id_proveedor_web', descuento_1_compra='$ratioPrecioUnidad' WHERE id='$id'");
                    $precio_ultimo_unidad=$ratioPrecioUnidad*$peso_real/1000;
                    $precio_ultimo_peso=$ratioPrecioPeso*$peso_real/1000;
                    $precio_transformacion_unidad=$ratioPrecioTransformacionUnidad*$peso_real/1000;
                    $precio_transformacion_peso=$ratioPrecioTransformacionPeso*$peso_real/1000;
                    $precio_compra=$ratioPrecioCompra*$peso_real/1000;
                    $tarifa_venta_unidad=$ratioTarifaUnidad*$peso_real/1000;
                    $tarifa_venta_peso=$ratioTarifaPeso*$peso_real/1000;
                    $tarifa_venta=$ratioTarifaVenta*$peso_real/1000;
                    $tarifa_profesionales=$ratioTarifaProfesionales*$peso_real/1000;
                    $tarifa_profesionales_vip=$ratioTarifaProfesionalesVip*$peso_real/1000;
                    $sql="UPDATE pe_productos SET precio_transformacion_unidad='$precio_transformacion_unidad',"
                            . " precio_transformacion_peso='$precio_transformacion_peso',"
                            . " tarifa_profesionales_vip='$tarifa_profesionales_vip' ,"
                            . " tarifa_profesionales='$tarifa_profesionales' ,"
                            . " descuento_1_compra='$descuento_1_compra', "
                            . " id_proveedor_web='$id_proveedor_web',"
                            . " precio_ultimo_unidad='$precio_ultimo_unidad', "
                            . " precio_ultimo_peso='$precio_ultimo_peso', "
                            . " tarifa_venta='$tarifa_venta',"
                            . " tarifa_venta_unidad='$tarifa_venta_unidad',"
                            . " tarifa_venta_peso='$tarifa_venta_peso',"
                            . " precio_compra='$precio_compra'  "
                            . " WHERE id='$id'";
                    $this->db->query($sql);
                    
                    //registra valores en registro precios
                    $hoy=date('Y-m-d');
                    $sql="INSERT INTO pe_registro_precios SET id_pe_producto='$id', "
                            . " precio_unidad='$precio_ultimo_unidad',"
                            . " precio_peso='$precio_ultimo_peso',"
                            . " tarifa_unidad='$tarifa_venta_unidad',"
                            . " tarifa_peso='$tarifa_venta_peso',"
                            . " fecha ='$hoy',"
                            . " id_proveedor= '0',"
                            . " descuento='$descuento_1_compra',"
                            . " tipo_iva='0'";
                    $this->db->query($sql);    
                    //log_message('INFO',$sql);
                    
                    $margen_real_producto=$this->margen_real_producto($id)*1000;
                    $sql="UPDATE pe_productos SET margen_venta_profesionales_vip='$margen_venta_profesionales_vip',margen_venta_profesionales='$margen_venta_profesionales',descuento_1_compra='$descuento_1_compra',  margen_real_producto='$margen_real_producto'  WHERE id='$id'";
                    $this->db->query($sql);
                }
                
                $margenEmbalajeTienda=$this->calculoMargenTienda($id);
                $margenEmbalajeOnline=$this->calculoMargenOnline($id);
                //log_message('INFO', 'id_pe_producto '.$id);
                //log_message('INFO', 'margenEmbalajeTienda '.$margenEmbalajeTienda);
                //log_message('INFO', 'margenEmbalajeOnline '.$margenEmbalajeOnline);
                
            }
           return;    
        }
        
        function eliminarProducto($id_pe_producto){
            return $this->db->query("DELETE FROM pe_productos WHERE id='$id_pe_producto'");
        }


        function checkPosibilityToEliminate($id_pe_producto){
            $sql="SELECT id,id_producto,codigo_producto, nombre FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return "El producto NO existe";
            $row=$this->db->query($sql)->row();
            $id=$id_pe_producto;
            $codigo_producto=$row->codigo_producto;
            $nombre=$row->nombre;
            $id_producto=$row->id_producto;
            $producto=" (".$codigo_producto." - ".$id_producto." - ".$nombre.")";
            $resultado=array('id'=>$id,'eliminar'=>true,'texto'=>"¿Desea eliminar el producto ".$producto."?");
            //return $resultado;
            if($this->db->query("SELECT cantidad FROM pe_stocks_totales WHERE id_pe_producto='$id'")->num_rows()!=0)
                if($this->db->query("SELECT cantidad FROM pe_stocks_totales WHERE id_pe_producto='$id'")->row()->cantidad!=0) $resultado=array('id'=>$id,'eliminar'=>false,'texto'=>"Existe stock del producto".$producto.". <span><strong>No se puede eliminar.</strong></span>");
            
            if($this->db->query("SELECT sum(cantidad) as cantidad FROM pe_stocks WHERE codigo_producto='$id'")->num_rows()!=0)
                if($this->db->query("SELECT sum(cantidad) as cantidad FROM pe_stocks WHERE codigo_producto='$id'")->row()->cantidad!=0) $resultado=array('id'=>$id,'eliminar'=>false,'texto'=>"Existe stock del producto".$producto.". <strong>No se puede eliminar.</strong>");
            
                if($this->db->query("SELECT id FROM pe_boka WHERE id_pe_producto='$id'")->num_rows()!=0) $resultado=array('id'=>$id,'eliminar'=>false,'texto'=>"Código utilizado en Boka".$producto.". <strong>No se puede eliminar.</strong>");
            if($this->db->query("SELECT id FROM pe_lineas_orders_prestashop WHERE id_pe_producto='$id'")->num_rows()!=0) $resultado=array('id'=>$id,'eliminar'=>false,'texto'=>"Código utilizado en Prestashop".$producto.". <strong>No se puede eliminar.</strong>");
            if($this->db->query("SELECT id FROM pe_registro_ventas WHERE id_pe_producto='$id'")->num_rows()!=0) $resultado=array('id'=>$id,'eliminar'=>false,'texto'=>"Código utilizado en Registro Ventas".$producto.". <strong>No se puede eliminar.</strong>");
            return $resultado;
        }
        
        function grabarCambiosPrecios($post_array,$primary_key){
            //se mantiene un histórico de precios/proveedor PVP
            $tipo_iva=$this->getIva($post_array['codigo_producto'])->valor_iva;
            $datos=array(
               'precio_unidad' =>$post_array['precio_ultimo_unidad'],
               'precio_peso' =>$post_array['precio_ultimo_peso'],
               'id_pe_producto' =>$primary_key,
               'tarifa_unidad' =>$post_array['tarifa_venta_unidad'],
               'tarifa_peso' =>$post_array['tarifa_venta_peso'],
               'id_proveedor' =>$post_array['id_proveedor_web'],
               'descuento' =>$post_array['descuento_1_compra'],
               'fecha' =>date('Y-m-d'),
               'tipo_iva' => $tipo_iva
               );
           $this->db->insert('pe_registro_precios', $datos);
        }
        
        function getTicketFecha($idBoka,$db=1){
            // echo $idBoka.'<br>';
            // echo $db.'<br>';
            $this->load->model('ventas_model');
            $sql="SELECT BONU,ZEIS FROM pe_boka WHERE id='$idBoka'";
            if($db==1){
                $row=$this->db->query($sql)->row();
            }else{
                mensaje($sql);
                $row=$this->db2->query($sql)->row();
            }
            $bonu=$row->BONU;
            $zeis=$row->ZEIS;
            $sql="SELECT RASA, ZEIS FROM pe_boka WHERE BONU='$bonu' AND ZEIS='$zeis' AND STYP=1";
            mensaje($sql);
            if($db==1){
                $row=$this->db->query($sql)->row();
            }else{
                mensaje($sql);
                $row=$this->db2->query($sql)->row();
            }
            return array('numTicket'=>$row->RASA,'fecha'=>$row->ZEIS);
        }

        function getCliente($idBoka,$db=1){
            $this->load->model('ventas_model');
            $sql="SELECT BONU,ZEIS FROM pe_boka WHERE id='$idBoka'";
            if($db==1){
                $row=$this->db->query($sql)->row();
            }else{
                $row=$this->db2->query($sql)->row();
            }
            $bonu=$row->BONU;
            $zeis=$row->ZEIS;
            $sql="SELECT SNR1,SNR2, ZEIS FROM pe_boka WHERE BONU='$bonu' AND ZEIS='$zeis' AND STYP=1";
            // mensaje('gelCliente '.$sql);
            $row=$this->db->query($sql)->row();
            //$num_cliente=substr($row->SNR1,0,strlen($row->SNR1)-1);
            return $row->SNR2;
        }
        
        function getIdGrupo($id_pe_producto){
            $idGrupo=0;
            $sql="SELECT id_grupo FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return $idGrupo;  
            $idGrupo= $this->db->query($sql)->row()->id_grupo;   
            return $idGrupo;  
        }
        function getIdFamilia($id_pe_producto){
            $idFamilia=0;
            $sql="SELECT id_familia FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return $idFamilia;  
            $idFamilia= $this->db->query($sql)->row()->id_familia;   
            return $idFamilia;  
        }

        //registrar ventas tienda
        function registrarVentaTienda($idBoka,$db=1){
            $this->load->model('ventas_model');
            $sql="SELECT id_pe_producto, BT10,BT12,BT13,BT20,BT30,BT11,POS1,GEW1,MWSA FROM pe_boka WHERE id='$idBoka'";
            if($db==1){
                $row=$this->db->query($sql)->row();
            }
            else{
                $row=$this->db2->query($sql)->row();
            }
            $codigo_producto=$this->getCodigoProducto($row->id_pe_producto);
            if($codigo_producto==0){
                mensaje('idBoka '.$idBoka);
                mensaje('$codigo_producto '.$codigo_producto);
                mensaje('=======================================');
                return 0;
            }
            $precioCompra=$this->getPrecioCompra($codigo_producto);
            // mensaje('$precioCompra '.$precioCompra);
            //log_message('INFO','TARIFA VENTE --------- $row->id_pe_producto '.$row->id_pe_producto);
            $tarifaVenta=$this->getTarifaVenta($row->id_pe_producto);
            // mensaje('$tarifaVenta '.$tarifaVenta);
            
            $idGrupo=$this->getIdGrupo($row->id_pe_producto);
            $idFamilia=$this->getIdFamilia($row->id_pe_producto);
            
            // $idGrupo=null;
            // $idFamilia=null;
            $tipoTienda=1; //tienda
            $tipoTiendaLetra='T';
            $precioEmbalaje=$this->getPrecioEmbalajeTienda($row->id_pe_producto);
            $cantidad=$row->POS1;
            $peso=$row->GEW1;
            
            if($peso){
                //log_message('INFO',$peso.'  '.$codigo_producto);
                $pesoProducto=$this->getPesoProducto($codigo_producto);
                // mensaje('pesoProducto '.$pesoProducto);
                if($pesoProducto){
                    $precioCompra=$precioCompra/$pesoProducto*1000;
                    $tarifaVenta=$tarifaVenta/$pesoProducto*1000; 
                    // mensaje('precioCompra '.$precioCompra);  
                    // mensaje('tarifaVenta '.$tarifaVenta);   
                }  
                $cantidad=0;
            }
           
            $pvp=$row->BT10;
            $importes=$this->ventas_model->getImportes($row->BT10,$row->BT12,$row->BT20+$row->BT11,$row->BT30,$row->POS1,$row->GEW1,$row->BT13);

            $pvpConDescuento=$cantidad?($importes['totalSinDescuento']+$importes['descuento'])*10/$cantidad:0;

            
            $pvp=$pvp*10;
            $tipoIva=$row->MWSA;
            // $ticket=$this->getTicketFecha($idBoka,2);
            $ticket=$this->getTicketFecha($idBoka,1);
        
            // $cliente=$this->getCliente($idBoka,2);
            $cliente=$this->getCliente($idBoka,1);
            while(strlen($cliente)<6){
                $cliente="0".$cliente;
            }
            
            // $cliente=null;
            $numTicket=$ticket['numTicket'];
            $fecha=$ticket['fecha'];
            $fechaLocal=substr ( $fecha , 0,10);
            $fechaLocal=substr ( $fechaLocal , 8,2)."/".substr ( $fechaLocal , 5,2)."/".substr ( $fechaLocal , 0,4);
            $transporte=0;
            $unidad=$peso?$peso/1000:$cantidad;
            $pvp_neto=$unidad?($importes['totalSinDescuento']+$importes['descuento'])*10/$unidad:0;
            $beneficio_producto=$this->calculoMargenProducto($precioCompra,$pvp_neto,$tipoIva*10);
            $beneficio_producto_embalaje=$this->calculoMargenProducto($precioCompra+$precioEmbalaje,$pvp_neto,$tipoIva*10);
            $beneficio_producto_embalaje_transporte=$this->calculoMargenProducto($precioCompra+$precioEmbalaje+$transporte/1.21,$pvp_neto+$transporte/1.21,$tipoIva*10);
            $unidad=$peso?$peso/1000:$cantidad;
            $beneficio_absoluto=$unidad*($pvp_neto*100/(100+$tipoIva/100)-$precioCompra-$precioEmbalaje);
            
            $datos=array('fecha_venta'=>$fecha,
                        'fecha_local'=>$fechaLocal,
                         'num_ticket'=>$numTicket,
                         'tipo_tienda'=>$tipoTienda,
                         'num_cliente'=>$cliente,
                         'tipo_tienda_letra'=>$tipoTiendaLetra,
                         'codigo_producto'=>$row->id_pe_producto,
                         'id_pe_producto'=>$row->id_pe_producto,
                         'precio_compra'=>$precioCompra,
                         'tarifa_venta'=>$tarifaVenta,
                         'precio_embalaje'=>$precioEmbalaje,
                         'cantidad'=>$cantidad,
                         'peso'=>$peso,
                         'grupo'=>$idGrupo,
                         'familia'=>$idFamilia,
                         'pvp'=>$pvp,
                         'pvp_neto'=>$pvp_neto,
                         'pvp_nuevo'=>$importes['precioUnitario'],
                         'ingresado'=>$importes['totalSinDescuento']+$importes['descuento'],
                         'total_sin_descuento'=>$importes['totalSinDescuento'],
                         'descuento'=>$importes['descuento'],
                         'tipo_iva'=>$tipoIva,
                         'beneficio_producto'=>$beneficio_producto,
                         'beneficio_producto_embalaje'=>$beneficio_producto_embalaje,
                         'beneficio_producto_embalaje_transporte'=>$beneficio_producto_embalaje_transporte,
                         'beneficio_absoluto'=>$beneficio_absoluto
                        );
                        
            if($db==1){
                $this->db->insert('pe_registro_ventas', $datos);
            }else {
                $this->db2->insert('pe_registro_ventas', $datos);
            }
            return 1;
        }
        
        function upDateVentaTienda($idBoka){
            $this->load->model('ventas_model');
            $sql="SELECT * FROM pe_registro_venta WHERE fecha_venta>='2019-02-26' AND fecha_venta<'2019-02-27'";
            $result=$this->db->query($sql)->result();
            foreach($result as $k=>$v){
                $zeis=$v->fecha_venta;
                $id_pe_producto=$v->codigo_producto;
                $POS1=$v->cantidad;
                $GEW1=$v->peso;
                $row=$this->db->query("SELECT id_pe_producto, BT10,BT12,BT20,BT30,POS1,GEW1,MWSA FROM pe_boka WHERE (GEW1='$GEW1' AND POS1=1) OR (GEW1='$0' AND POS1='$POS1')")->row();
                echo $numRows=$this->db->query("SELECT id_pe_producto, BT10,BT12,BT20,BT30,POS1,GEW1,MWSA FROM pe_boka WHERE (GEW1='$GEW1' AND POS1=1) OR (GEW1='$0' AND POS1='$POS1')")->num_rows();
            }

            $sql="SELECT id_pe_producto, BT10,BT12,BT20,BT30,POS1,GEW1,MWSA FROM pe_boka WHERE id='$idBoka'";
            $row=$this->db->query($sql)->row();
            $codigo_producto=$this->getCodigoProducto($row->id_pe_producto);
            // mensaje('$codigo_producto '.$codigo_producto);
            $precioCompra=$this->getPrecioCompra($codigo_producto);
            // mensaje('$precioCompra '.$precioCompra);
            //log_message('INFO','TARIFA VENTE --------- $row->id_pe_producto '.$row->id_pe_producto);
            $tarifaVenta=$this->getTarifaVenta($row->id_pe_producto);
            // mensaje('$tarifaVenta '.$tarifaVenta);
            /*
            $idGrupo=$this->getIdGrupo($row->id_pe_producto);
            $idFamilia=$this->getIdFamilia($row->id_pe_producto);
            */
            $idGrupo=null;
            $idFamilia=null;
            $tipoTienda=1; //tienda
            $tipoTiendaLetra='T';
            $precioEmbalaje=$this->getPrecioEmbalajeTienda($row->id_pe_producto);
            $cantidad=$row->POS1;
            $peso=$row->GEW1;
            
            if($peso){
                //log_message('INFO',$peso.'  '.$codigo_producto);
                $pesoProducto=$this->getPesoProducto($codigo_producto);
                // mensaje('pesoProducto '.$pesoProducto);
                if($pesoProducto){
                    $precioCompra=$precioCompra/$pesoProducto*1000;
                    $tarifaVenta=$tarifaVenta/$pesoProducto*1000; 
                    // mensaje('precioCompra '.$precioCompra);  
                    // mensaje('tarifaVenta '.$tarifaVenta);   
                }  
                $cantidad=0;
            }
           
            $pvp=$row->BT10;
            $importes=$this->ventas_model->getImportes($row->BT10,$row->BT12,$row->BT20,$row->BT30,$row->POS1,$row->GEW1);

            $pvpConDescuento=$cantidad?($importes['totalSinDescuento']+$importes['descuento'])*10/$cantidad:0;

            
            $pvp=$pvp*10;
            $tipoIva=$row->MWSA;
            $ticket=$this->getTicketFecha($idBoka);
            /*
            $cliente=$this->getCliente($idBoka);
            while(strlen($cliente)<6){
                $cliente="0".$cliente;
            }
            */
            $cliente=null;
            $numTicket=$ticket['numTicket'];
            $fecha=$ticket['fecha'];
            $fechaLocal=substr ( $fecha , 0,10);
            $fechaLocal=substr ( $fechaLocal , 8,2)."/".substr ( $fechaLocal , 5,2)."/".substr ( $fechaLocal , 0,4);
            $transporte=0;
            $unidad=$peso?$peso/1000:$cantidad;
            $pvp_neto=$unidad?($importes['totalSinDescuento']+$importes['descuento'])*10/$unidad:0;
            $beneficio_producto=$this->calculoMargenProducto($precioCompra,$pvp_neto,$tipoIva*10);
            $beneficio_producto_embalaje=$this->calculoMargenProducto($precioCompra+$precioEmbalaje,$pvp_neto,$tipoIva*10);
            $beneficio_producto_embalaje_transporte=$this->calculoMargenProducto($precioCompra+$precioEmbalaje+$transporte/1.21,$pvp_neto+$transporte/1.21,$tipoIva*10);
            $unidad=$peso?$peso/1000:$cantidad;
            $beneficio_absoluto=$unidad*($pvp_neto*100/(100+$tipoIva/100)-$precioCompra-$precioEmbalaje);
            
            $datos=array('fecha_venta'=>$fecha,
                        'fecha_local'=>$fechaLocal,
                         'num_ticket'=>$numTicket,
                         'tipo_tienda'=>$tipoTienda,
                         'num_cliente'=>$cliente,
                         'tipo_tienda_letra'=>$tipoTiendaLetra,
                         'codigo_producto'=>$row->id_pe_producto,
                         'id_pe_producto'=>$row->id_pe_producto,
                         'precio_compra'=>$precioCompra,
                         'tarifa_venta'=>$tarifaVenta,
                         'precio_embalaje'=>$precioEmbalaje,
                         'cantidad'=>$cantidad,
                         'peso'=>$peso,
                         'grupo'=>$idGrupo,
                         'familia'=>$idFamilia,
                         'pvp'=>$pvp,
                         'pvp_neto'=>$pvp_neto,
                         'pvp_nuevo'=>$importes['precioUnitario'],
                         'ingresado'=>$importes['totalSinDescuento']+$importes['descuento'],
                         'total_sin_descuento'=>$importes['totalSinDescuento'],
                         'descuento'=>$importes['descuento'],
                         'tipo_iva'=>$tipoIva,
                         'beneficio_producto'=>$beneficio_producto,
                         'beneficio_producto_embalaje'=>$beneficio_producto_embalaje,
                         'beneficio_producto_embalaje_transporte'=>$beneficio_producto_embalaje_transporte,
                         'beneficio_absoluto'=>$beneficio_absoluto
                        );
            $this->db->insert('pe_registro_ventas', $datos);
        }

        //registrar ventas prestashop
        function registrarVentaPrestashop($vd){
            //if($vd['pedido']==17353)
            //   var_dump($vd);
            extract($vd);
            if($valid!=1) return;
            //echo $valid.'<br>';
            $fechaLocal=substr ( $fecha , 0,10);
            $fechaLocal=substr ( $fechaLocal , 8,2)."/".substr ( $fechaLocal , 5,2)."/".substr ( $fechaLocal , 0,4);
            $codigo_producto=$codigo;
            $precioCompra=$this->getPrecioCompra($codigo_producto);
            $id_pe_producto=$this->getId_pe_producto($codigo_producto);
            $idGrupo=$this->getIdGrupo($id_pe_producto);
            $idFamilia=$this->getIdFamilia($id_pe_producto);
            $tarifaVenta=$this->getTarifaVenta($id_pe_producto);
            $tipoTienda=2; //Prestashop
            $tipoTiendaLetra='P';
            $precioEmbalaje=$this->getPrecioEmbalajeOnline($id_pe_producto);
            $peso=0;
            $tipoIva=$tipo_iva;
            $totalReal=$total-$descuento;
            $factor=$total==0?1:$totalReal/$total;
            $pvp=$importe*$factor;
            $pvpUnidad=$pvp/$cantidad;
            $transporteProducto=$totalReal==0?0:$transporte/1000/$totalReal*$pvpUnidad;
            
            
            $beneficio_producto=$this->productos_-> calculoMargenProducto($precioCompra,$pvpUnidad*1000,$tipoIva*1000);
            
        
            
            $beneficio_producto_embalaje=$this->productos_->calculoMargenProducto($precioCompra+$precioEmbalaje,$pvpUnidad*1000,$tipoIva*1000);
            $beneficio_producto_embalaje_transporte=$this->productos_->calculoMargenProducto($precioCompra+$precioEmbalaje+$transporteProducto/1.21,$pvpUnidad*1000+$transporteProducto/1.21,$tipoIva*1000);
            
            //si es Pack -> beneficio absoluto=0 y cantidad=0 // se gestiona a través de sus componentes
            if($this->esPack($id_pe_producto)){ $beneficio_absoluto=0; $cantidad=0;}
            else $beneficio_absoluto=$pvp?$pvp*1000*100/(100+$tipoIva)-$cantidad*($precioCompra+$precioEmbalaje):0;
            $cantidad=$valid==-1?-$cantidad:$cantidad;
            $pvp=round($pvpUnidad*1000,0);
            $transporte=round($transporteProducto*1000,0);
            $datos=array('fecha_venta'=>$fecha,
                         'fecha_local'=>$fechaLocal,
                         'num_ticket'=>$pedido,
                         'num_cliente'=>$pedido,
                         'tipo_tienda'=>$tipoTienda,
                         'tipo_tienda_letra'=>$tipoTiendaLetra,
                         'codigo_producto'=>$id_pe_producto,
                         'id_pe_producto'=>$id_pe_producto,
                         'grupo'=>$idGrupo,
                         'familia'=>$idFamilia,
                         'precio_compra'=>$precioCompra,
                         'tarifa_venta'=>$tarifaVenta,
                         'precio_embalaje'=>$precioEmbalaje,
                         'cantidad'=>$cantidad,
                         'peso'=>$peso,
                         'pvp'=>$pvp,
                         'pvp_neto'=>$pvp,
                         'ingresado'=>$pvp*$cantidad/10,
                         'total_transporte'=>$transporte*$cantidad,
                         'tipo_iva'=>$tipoIva*100==0?'0000':strval($tipoIva*100),
                         'transporte'=>$transporte,
                         'beneficio_producto'=>$beneficio_producto,
                         'beneficio_producto_embalaje'=>$beneficio_producto_embalaje,
                         'beneficio_producto_embalaje_transporte'=>$beneficio_producto_embalaje_transporte,
                         'beneficio_absoluto'=>$beneficio_absoluto
                        );
            /*
            if($esPack)
                $datos=array('fecha'=>$fecha,
                         'num_ticket'=>$pedido,
                         'tipo_tienda'=>$tipoTienda,
                         'id_pe_producto'=>$id_pe_producto,
                         'precio_compra'=>"",
                         'tarifa_venta'=>"",
                         'precio_embalaje'=>$precioEmbalaje,
                         'cantidad'=>"",
                         'peso'=>"",
                         'pvp'=>"",
                         'tipo_iva'=>"",
                         'transporte'=>"",
                         'beneficio_producto'=>"",
                         'beneficio_producto_embalaje'=>"",
                         'beneficio_producto_embalaje_transporte'=>""
            
                        );
                */
           // echo "var_dump(datos); <br>";
           // var_dump($datos);
            $this->db->insert('pe_registro_ventas', $datos);
        }
        
        function bajarExcelProductos(){
           
          
            $result=$this->getProductos($_POST['buscadores']); 
           
           //return $result;
          
            
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
            
        $this->excel->getActiveSheet()->setCellValue('A1', "DATOS PRODUCTOS  "); 
        $this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
        $hoy=date('d/m/Y');
        $this->excel->getActiveSheet()->setCellValue('A2', "Fecha: $hoy"); 
        
        $filaInicial=4;
        $c="A";$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][0]); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][1]); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][2]); 
        $c="F";$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][3]); 
        $c="H";$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][4]); 
        $c="I";$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][5]); 
        $c="J";$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][6]); 
        $c="G";$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][7]);
        $c="K";$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $_POST['buscadores'][8]); 
        
        
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:K$filaInicial")->getFont()->setItalic(true);
        
        $filaInicial=5;
        $c="A";$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Código 13"); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Código Báscula  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Producto"); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Grupo"); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Familia"); 
        
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Peso (Kg)  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Tipo Unidad  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Proveedor"); 

        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Precio Compra Unidad  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Precio Compra Kg  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Precio Compra Final en Tienda  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Descuento Compra  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "PVP Unidad  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "PVP Kg  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Tipo IVA  "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Margen %  "); 
        
        
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "beneficio_recomendado "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "descuento_profesionales "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "tarifa_profesionales "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "descuento_profesionales_vip "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "tarifa_profesionales_vip "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "url_producto "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "url_imagen_portada "); 
        
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Nombre "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Marca "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Referencia "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Url producto"); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Origen "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Raza "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Curado "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Pesos "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Añada "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Formato "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Unidades Caja "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Ecológica "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Tipo de uva "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Volumen "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Variedades "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Descripción "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Tarifa "); 
        $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", "Catálogo Unidad"); 
        
        
        
        
        
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:AN$filaInicial")->getFont()->setBold(true);

        
        
       foreach($result as $k=>$v){
            $filaInicial++;
            $c="A";$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->codigo13); 
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->codigoBascula);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", trim($v->nombreProducto));
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->nombreGrupo);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->nombreFamilia);
            
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->peso?number_format($v->peso/1000,3):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->tipoUnidad);

            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->nombreProveedor);

            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->precioUnidad?number_format($v->precioUnidad/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->precioPeso?number_format($v->precioPeso/1000,2):"");
           $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->precio_compra?number_format($v->precio_compra/1000,2):"");

            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->descuento?number_format($v->descuento/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->tarifaUnidad?number_format($v->tarifaUnidad/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->tarifaPeso?number_format($v->tarifaPeso/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->tipoIva?number_format($v->tipoIva,0):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->margen?number_format($v->margen/1000,2):"");
            
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->beneficio_recomendado?number_format($v->beneficio_recomendado/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->descuento_profesionales?number_format($v->descuento_profesionales/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->tarifa_profesionales?number_format($v->tarifa_profesionales/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->descuento_profesionales_vip?number_format($v->descuento_profesionales_vip/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->tarifa_profesionales_vip?number_format($v->tarifa_profesionales_vip/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->url_producto);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->url_imagen_portada);
            
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_nombre);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_marca);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_referencia);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_url_producto);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_origen);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_raza);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_curado);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_pesos);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_anada);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_formato);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_unidades_caja?number_format($v->cat_unidades_caja/1000,0):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_ecologica);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_tipo_de_uva);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_volumen);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_variedades);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_descripcion);
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_tarifa?number_format($v->cat_tarifa/1000,2):"");
            $c++;$this->excel->getActiveSheet()->setCellValue("$c$filaInicial", $v->cat_unidad);

            
            
       }
       
        $this->excel->getActiveSheet()->getStyle("B5:B$filaInicial")->getNumberFormat()->setFormatCode('0  ');
        $this->excel->getActiveSheet()->getStyle("N5:N$filaInicial")->getNumberFormat()->setFormatCode('0  ');
        $this->excel->getActiveSheet()->getStyle("F5:F$filaInicial")->getNumberFormat()->setFormatCode('#,##0.000  ');
        $this->excel->getActiveSheet()->getStyle("K5:K$filaInicial")->getNumberFormat()->setFormatCode('#,##0.000  ');
        $this->excel->getActiveSheet()->getStyle("L5:T$filaInicial")->getNumberFormat()->setFormatCode('#,##0.00  ');
        $this->excel->getActiveSheet()->getStyle("AN5:AN$filaInicial")->getNumberFormat()->setFormatCode('#,##0.00  ');
        $this->excel->getActiveSheet()->getStyle("AH5:AH$filaInicial")->getNumberFormat()->setFormatCode('#,##0  ');
        $this->excel->getActiveSheet()->getStyle("I5:J$filaInicial")->getNumberFormat()->setFormatCode('#,##0.000  ');
        $this->excel->getActiveSheet()->getStyle("F4:F$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("H4:H$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->getActiveSheet()->getStyle("I4:N$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("B4:B$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("G4:G$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("AB4:AF$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle("AJ4:AL$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $filaInicial++;
       
        for($col = 'A'; $col !== 'AN'; $col++) {
                $this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        
        
        
        $hoy=str_replace("/","_",$hoy);
        $filename = "Productos $hoy.xls";
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
           
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        //force user to download the Excel file without writing it to server's HD
        //$objWriter->save('php://output');

        $objWriter->save('productos/'.$filename);
        
        return   'productos/'.$filename;  
        
           // return $tabla;
        }
        
        function getProductos($buscadores=array()){
            
            if($this->session->categoria <2){
                $buscadorCodigo13=$buscadores[0];
                $buscadorCodigoBascula=$buscadores[1];
                $buscadorNombre=$buscadores[2];
                $buscadorPeso=$buscadores[3];
                $buscadorUnidad=$buscadores[4];
                $buscadorPrecio=$buscadores[5];
                //$buscadorDescuento=$buscadores[6];
                $buscadorProveedor=$buscadores[6];
                $buscadorPVP=$buscadores[7];
                $buscadorMargen=$buscadores[8];
                $buscadorMarca=trim($buscadores[9]);
                if($buscadorMarca=="")
                    $buscadorMarca="";
                else
                    $buscadorMarca=" AND ma.marca LIKE '%$buscadorMarca%' ";

                 $buscador11=$buscadores[10];
                  $buscador12=$buscadores[11];
                   $buscador13=$buscadores[12];
                    $buscador14=$buscadores[13];
                     $buscador15=$buscadores[14];
                      $buscador16=$buscadores[15];
                      $buscador17=$buscadores[16];
                       $buscadorBeneficioRecomendado="";
            };
            if($this->session->categoria ==4){
                $buscadorCodigo13=$buscadores[0];
                $buscadorCodigoBascula=$buscadores[1];
                $buscadorNombre=$buscadores[2];
                $buscadorPeso=$buscadores[3];
                $buscadorUnidad=$buscadores[4];
                $buscadorPrecio=$buscadores[5];
                //$buscadorDescuento=$buscadores[6];
                $buscadorProveedor=$buscadores[6];
                $buscadorPVP=$buscadores[7];
                $buscadorMargen=$buscadores[8];
                $buscadorBeneficioRecomendado=$buscadores[9];
                $buscadorMarca="";

                 $buscador11="";
                  $buscador12="";
                   $buscador13="";
                    $buscador14="";
                     $buscador15="";
                      $buscador16="";
                      $buscador17=$buscadores[10];
            };
            
            $sql="SELECT pro.nombre as nombreProveedor,"
                    . " pr.nombre as nombreProducto,"
                    . " pr.codigo_producto as codigo13,"
                    . " pr.id_producto as codigoBascula,"
                    . " fa.nombre_familia as nombreFamilia,"
                    . " gr.nombre_grupo as nombreGrupo,"
                    . " pr.peso_real as peso,"
                    . " pr.tipo_unidad as tipoUnidad,"
                    . " pr.precio_ultimo_unidad as precioUnidad,"
                    . " pr.precio_ultimo_peso as precioPeso,"
                    . " pr.precio_compra as precio_compra,"
                    . " pr.descuento_1_compra as descuento,"
                    . " pr.tarifa_venta_unidad as tarifaUnidad,"
                    . " pr.tarifa_venta_peso as tarifaPeso,"
                    . " pr.tarifa_venta as tarifa_venta,"
                    . " pro.nombre as nombreProveedor,"
                    . " iv.valor_iva as tipoIva,"
                    . " pr.margen_real_producto as margen, "
                    
                    . " pr.unidades_precio as unidades_precio, "
                    . " pr.beneficio_recomendado as beneficio_recomendado, "
                    . " pr.descuento_profesionales as descuento_profesionales, "
                    . " pr.tarifa_profesionales as tarifa_profesionales, "
                    . " pr.margen_venta_profesionales as margen_venta_profesionales, "
                    . " pr.margen_venta_profesionales_vip as margen_venta_profesionales_vip, "
                    . " pr.descuento_profesionales_vip as descuento_profesionales_vip, "
                    . " pr.tarifa_profesionales_vip as tarifa_profesionales_vip, "
                    . " pr.url_producto as url_producto, "
                    . " pr.url_imagen_portada as url_imagen_portada, "
                    
                    . " pr.cat_nombre as cat_nombre, "
                    . " ma.marca as cat_marca, "
                    . " pr.cat_referencia as cat_referencia, "
                    . " pr.cat_url_producto as cat_url_producto, "
                    . " pr.cat_origen as cat_origen, "
                    . " pr.cat_raza as cat_raza, "
                    . " pr.cat_curado as cat_curado, "
                    . " pr.cat_pesos as cat_pesos, "
                    . " pr.cat_anada as cat_anada, "
                    . " pr.cat_formato as cat_formato, "
                    . " pr.cat_unidades_caja as cat_unidades_caja, "
                    . " pr.cat_ecologica as cat_ecologica, "
                    . " pr.cat_tipo_de_uva as cat_tipo_de_uva, "
                    . " pr.cat_volumen as cat_volumen, "
                    . " pr.cat_variedades as cat_variedades, "
                    . " pr.cat_descripcion as cat_descripcion, "
                    . " pr.cat_tarifa as cat_tarifa, "
                    . " pr.cat_unidad as cat_unidad "
                    
                    
                    . " FROM pe_productos pr"
                    . " LEFT JOIN pe_proveedores_acreedores pro ON  pr.id_proveedor_web=pro.id_proveedor_acreedor"
                    . " LEFT JOIN pe_familias fa ON pr.id_familia=fa.id_familia"
                    . " LEFT JOIN pe_grupos gr ON pr.id_grupo=gr.id_grupo"
                    . " LEFT JOIN pe_ivas iv ON iv.id_iva=gr.id_iva"
                    . " LEFT JOIN pe_marcas ma ON ma.id=pr.cat_marca";
                 
                /*
                    . " WHERE pr.codigo_producto LIKE '%$buscadorCodigo13%' "
                    . " AND pr.id_producto LIKE '%$buscadorCodigoBascula%' "
                    . " AND pr.nombre LIKE '%$buscadorNombre%' "
                    . " AND pr.peso_real LIKE '%$buscadorPeso%' "  
                    . " AND pr.precio_compra LIKE '%$buscadorPrecio%' "  
                    . " AND pr.tipo_unidad LIKE '%$buscadorUnidad%' "  
                    . " AND pr.beneficio_recomendado LIKE '%$buscadorBeneficioRecomendado%' "  
                    . " AND pro.nombre LIKE '%$buscadorProveedor%' "
                    . " AND pr.tarifa_venta LIKE '%$buscadorPVP%' "
                    .  $buscadorMarca
              
                    . " AND pr.descuento_profesionales LIKE '%$buscador11%' "             
                   . " AND pr.tarifa_profesionales LIKE '%$buscador12%' "
                    . " AND pr.margen_venta_profesionales LIKE '%$buscador13%' "
                      . " AND pr.descuento_profesionales_vip LIKE '%$buscador14%' "             
                   . " AND pr.tarifa_profesionales_vip LIKE '%$buscador15%' "
                    . " AND pr.margen_venta_profesionales_vip LIKE '%$buscador16%' "
                  . " AND pr.url_imagen_portada LIKE '%$buscador17%' "
                   
                 
                    
                    . " ORDER BY pr.codigo_producto";
                 
                 */
            $where=array();
            if($buscadorCodigo13) $where[]= "pr.codigo_producto LIKE '%$buscadorCodigo13%' ";
            if($buscadorCodigoBascula) $where[]= "pr.id_producto LIKE '%$buscadorCodigoBascula%'  ";
            if($buscadorNombre) $where[]= "pr.nombre LIKE '%$buscadorNombre%'  ";
            if($buscadorPeso) $where[]= "pr.peso_real LIKE '%$buscadorPeso%'  ";
            if($buscadorPrecio) $where[]= "pr.precio_compra LIKE '%$buscadorPrecio%' ";
            if($buscadorUnidad) $where[]= "pr.tipo_unidad LIKE '%$buscadorUnidad%'  ";
            if($buscadorBeneficioRecomendado) $where[]= "pr.beneficio_recomendado LIKE '%$buscadorBeneficioRecomendado%'  ";
            if($buscadorProveedor) $where[]= "pro.nombre LIKE '%$buscadorProveedor%'  ";
            if($buscadorPVP) $where[]= "pr.tarifa_venta LIKE '%$buscadorPVP%'  ";
            if($buscador11) $where[]= "pr.descuento_profesionales LIKE '%$buscador11%' ";
            if($buscador12) $where[]= "pr.tarifa_profesionales LIKE '%$buscador12%'  ";
            if($buscador13) $where[]= "pr.margen_venta_profesionales LIKE '%$buscador13%'  ";
            if($buscador14) $where[]= "pr.descuento_profesionales_vip LIKE '%$buscador14%' ";
            if($buscador15) $where[]= "pr.tarifa_profesionales_vip LIKE '%$buscador15%'  ";
            if($buscador16) $where[]= "pr.margen_venta_profesionales_vip LIKE '%$buscador16%'  ";
            if($buscador17) $where[]= "pr.url_imagen_portada LIKE '%$buscador17%'  ";
            if($buscadorMarca) $where[]= "ma.marca LIKE '%$buscadorMarca%'  ";
            
            if (!empty($where)) {
                $sql.=" WHERE ";
                $sql.=implode(' AND ',$where);
            }
            $sql.=" ORDER BY pr.codigo_producto";
        /*
            $where=" WHERE pr.codigo_producto LIKE '%$buscadorCodigo13%' "
                    . " AND pr.id_producto LIKE '%$buscadorCodigoBascula%' "
                    . " AND pr.nombre LIKE '%$buscadorNombre%' "
                    . " AND pr.peso_real LIKE '%$buscadorPeso%' "  
                    . " AND pr.precio_compra LIKE '%$buscadorPrecio%' "  
                    . " AND pr.tipo_unidad LIKE '%$buscadorUnidad%' "  
                    . " AND pr.beneficio_recomendado LIKE '%$buscadorBeneficioRecomendado%' "  
                    . " AND pro.nombre LIKE '%$buscadorProveedor%' "
                    . " AND pr.tarifa_venta LIKE '%$buscadorPVP%' "
                    .  $buscadorMarca
              
                    . " AND pr.descuento_profesionales LIKE '%$buscador11%' "             
                   . " AND pr.tarifa_profesionales LIKE '%$buscador12%' "
                    . " AND pr.margen_venta_profesionales LIKE '%$buscador13%' "
                      . " AND pr.descuento_profesionales_vip LIKE '%$buscador14%' "             
                   . " AND pr.tarifa_profesionales_vip LIKE '%$buscador15%' "
                    . " AND pr.margen_venta_profesionales_vip LIKE '%$buscador16%' "
                  . " AND pr.url_imagen_portada LIKE '%$buscador17%' ";
            */
            
           
          // echo $where;
            //log_message('INFO',$sql);
            
            return $this->db->query($sql)->result();
        }
        
        function codigoBasculaToCodigo13($codigoBascula,$peso){
           // echo 'codigoBascula '.$codigoBascula.'  peso '.$peso;
           // echo '<br>';
            $sql="SELECT id_producto, codigo_producto,peso_real FROM pe_productos WHERE id_producto='$codigoBascula'";
            if($this->db->query($sql)->num_rows()==1) 
                return $this->db->query($sql)->row()->codigo_producto;
            else{
                $gew=abs($peso);
                $result=$this->db->query($sql)->result();
                   $codigoAsignado=0;
                   foreach($result as $k=>$v){
                       $codigo_producto=$v->codigo_producto;
                       $peso_real=$v->peso_real;
                       if($codigoAsignado==0 && $peso_real!=0) $codigoAsignado=$codigo_producto;
                       if($peso_real!=0 && $peso_real<=$gew) $codigoAsignado=$codigo_producto;
                   }
                 return $codigoAsignado;
            }
        }
        
        function ordenarArray($array){
            usort($array, 'sortByOption');
            return $array;
        }
        
        function getTarifaVenta($id_pe_producto){
            $tarifaVenta=0;
            $sql="SELECT tarifa_venta FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return $tarifaVenta;
            $tarifaVenta=$this->db->query($sql)->row()->tarifa_venta;
            return $tarifaVenta;
        }
        
        function calculoTarifaVenta($id_pe_producto){
            return $this->getCostePVP($id_pe_producto)['PVP'];
        }
        
        function getCostePVP($id_pe_producto){
            $sql="SELECT tipo_unidad,precio_compra,tarifa_venta FROM pe_productos WHERE id='$id_pe_producto'";
            $row=$this->db->query($sql)->row();
            return array('coste'=>floatval($row->precio_compra)/1000,'PVP'=>floatval($row->tarifa_venta)/1000,'tipoUnidad'=>$row->tipo_unidad);
            /*
            //$tipoUnidad=$this->getUnidad($id_pe_producto);
            $sql="SELECT precio_ultimo_unidad,precio_ultimo_peso,tarifa_venta_unidad,tarifa_venta_peso FROM pe_productos WHERE id='$id_pe_producto'";
            $row=$this->db->query($sql)->row();
            if($tipoUnidad=="Und") return array('coste'=>floatval($row->precio_ultimo_unidad/1000),'PVP'=>$row->tarifa_venta_unidad/1000,'tipoUnidad'=> $tipoUnidad);
            if($tipoUnidad=="Kg") return array('coste'=>floatval($row->precio_ultimo_peso/1000),'PVP'=>$row->tarifa_venta_peso/1000,'tipoUnidad'=> $tipoUnidad);
            return array('coste'=>0,'PVP'=>0,'tipoUnidad'=> '--');
            */
        }
        
        function esActivoCodigoBoka($id_producto){
            $sql="SELECT status_producto FROM pe_productos WHERE id='$id_producto'";
            if($this->db->query($sql)->num_rows()>0)
                return $this->db->query($sql)->row()->status_producto;
            else {
                return 0;
            }
        }
        
        function getStatusProducto($id_pe_producto){
            $sql="SELECT status_producto FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()>0)
                return $this->db->query($sql)->row()->status_producto;
            else {
                return 0;
            }
        }
        
        function getValoracion($id_pe_producto,$cantidad){
            $valor=$this->getCostePVP($id_pe_producto);
            $valoracion=$valor['coste']*$cantidad;
            return $valoracion;
        }
        
         function getUnidadesPrecio($primary_key){
             return $this->db->query("SELECT unidades_precio FROM pe_productos WHERE id='$primary_key'")->row()->unidades_precio;
         }
        
        function existe($codigo_producto){
            $sql="SELECT id,codigo_producto FROM pe_productos WHERE codigo_producto='$codigo_producto'";  
            if ($this->db->query($sql)->num_rows()==0) return false;
            return true;
        }
        function existeActivoConControlStock($codigo_producto){
            $sql="SELECT id FROM pe_productos WHERE codigo_producto='$codigo_producto' AND status_producto='1'";  
            if ($this->db->query($sql)->num_rows()==0) return false;
            if($this->isPack($codigo_producto)) return true;
            if($this->getControlStock($this->db->query($sql)->row()->id)=='No') return false;
            return true;
        }
        
        function getUnidad($id_pe_producto){
            $tipoUnidad="--";
            $sql="SELECT precio_ultimo_peso,precio_ultimo_unidad FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return $tipoUnidad;
            $row=$this->db->query($sql)->row();
            $precio_ultimo_peso=$row->precio_ultimo_peso;
            $precio_ultimo_unidad=$row->precio_ultimo_unidad;
            if($precio_ultimo_peso!=0 && $precio_ultimo_unidad!=0) return $tipoUnidad="---";
            if($precio_ultimo_peso==0 && $precio_ultimo_unidad==0) return $tipoUnidad="---";
            if($precio_ultimo_peso!=0 ) return $tipoUnidad="Kg"; 
            if($precio_ultimo_unidad!=0) return $tipoUnidad="Und"; 
            return $tipoUnidad;
        }
        
        function calculoUnidad($id_pe_producto){
            $tipoUnidad="--";
            $sql="SELECT precio_ultimo_peso,precio_ultimo_unidad FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return $tipoUnidad;
            $row=$this->db->query($sql)->row();
            $precio_ultimo_peso=$row->precio_ultimo_peso;
            $precio_ultimo_unidad=$row->precio_ultimo_unidad;
            if($precio_ultimo_peso!=0 && $precio_ultimo_unidad!=0) return $tipoUnidad="---";
            if($precio_ultimo_peso==0 && $precio_ultimo_unidad==0) return $tipoUnidad="---";
            if($precio_ultimo_peso!=0 ) return $tipoUnidad="Kg"; 
            if($precio_ultimo_unidad!=0) return $tipoUnidad="Und"; 
            return $tipoUnidad;
        }
        
        function getImagen($id_pe_producto){
            $imagen= base_url()."images/pernil1812.png";
             $sql="SELECT url_imagen_portada as imagen FROM pe_productos WHERE id='$id_pe_producto'";
             if($this->db->query($sql)->num_rows()==0) return $imagen;
             $imagen=$this->db->query($sql)->row()->imagen;
             if ($imagen=="") $imagen= base_url()."images/pernil1812.png";
             return $imagen;
        }
        
        function getUnidadCodigoProducto($codigo_producto){
            $tipoUnidad="--";
            $sql="SELECT precio_ultimo_peso,precio_ultimo_unidad FROM pe_productos WHERE codigo_producto='$codigo_producto'";
            if($this->db->query($sql)->num_rows()==0) return $tipoUnidad;
            $row=$this->db->query($sql)->row();
            $precio_ultimo_peso=$row->precio_ultimo_peso;
            $precio_ultimo_unidad=$row->precio_ultimo_unidad;
            if($precio_ultimo_peso!=0 && $precio_ultimo_unidad!=0) return $tipoUnidad="---";
            if($precio_ultimo_peso==0 && $precio_ultimo_unidad==0) return $tipoUnidad="---";
            if($precio_ultimo_peso!=0) return $tipoUnidad="Kg"; 
            if($precio_ultimo_unidad!=0) return $tipoUnidad="Und"; 
            return $tipoUnidad;
        }
        
        function getIdProveedor($id_pe_producto){
           $sql="SELECT id_proveedor_web FROM pe_productos WHERE id='$id_pe_producto'";
           if($this->db->query($sql)->num_rows()==1)
                return $this->db->query($sql)->row()->id_proveedor_web;
           else
                return 0;
        }
        
        function calculoTarifaProfesionales($id_pe_producto){
            $sql="SELECT * FROM pe_productos WHERE id='$id_pe_producto' ";
            $row=$this->db->query($sql)->row();
            $descuento_profesionales=$row->descuento_profesionales;
            $tarifa_venta=$this->productos_->tarifaVentaFinal($id_pe_producto);
            $iva=$this->getIvaId($id_pe_producto);
            $base=$tarifa_venta/(1+$iva/100000);
            $tarifa_profesionales=$base-$base*$descuento_profesionales/100000;
            return $tarifa_profesionales;
        }
        
        function calculoTarifaProfesionalesVip($id_pe_producto){
            $sql="SELECT * FROM pe_productos WHERE id='$id_pe_producto' ";
            $row=$this->db->query($sql)->row();
            $descuento_profesionales_vip=$row->descuento_profesionales_vip;
            $tarifa_venta=$this->productos_->tarifaVentaFinal($id_pe_producto);
            $iva=$this->getIvaId($id_pe_producto);
            $base=$tarifa_venta/(1+$iva/100000);
            $tarifa_profesionales_vip=$base-$base*$descuento_profesionales_vip/100000;
            return $tarifa_profesionales_vip;
        }
        
        function getTarifaProfesionales($id_pe_producto){
           $sql="SELECT tarifa_profesionales FROM pe_productos WHERE id='$id_pe_producto'";
           if($this->db->query($sql)->num_rows()==1)
                return $this->db->query($sql)->row()->tarifa_profesionales;
           else
                return 0;
        }
        
        function getDatosCompraProducto($id_pe_producto){
            $sql="SELECT iv.valor_iva as tipoIva, p.unidades_caja ,p.descuento_1_compra as descuento_1_compra,
                    p.precio_compra as precio_compra, p.unidades_precio as unidades_precio,p.precio_ultimo_peso,p.precio_ultimo_unidad,pr1.id_proveedor_acreedor as id_proveedor_1,
                    pr1.nombre as nombre_proveedor_1, 
                    p.precio_unidad_2,p.precio_peso_2, pr2.nombre as nombre_proveedor_2,pr2.id_proveedor_acreedor as id_proveedor_2,
                    p.precio_unidad_3,p.precio_peso_3, pr3.nombre as nombre_proveedor_3,pr3.id_proveedor_acreedor as id_proveedor_3
                    FROM pe_productos p
                    LEFT JOIN pe_grupos as gr ON p.id_grupo=gr.id_grupo
                    LEFT JOIN pe_ivas as iv ON gr.id_iva=iv.id_iva
                    LEFT JOIN pe_proveedores_acreedores as pr1 ON pr1.id_proveedor_acreedor=id_proveedor_web
                    LEFT JOIN pe_proveedores_acreedores as pr2 ON pr2.id_proveedor_acreedor=id_proveedor_2
                    LEFT JOIN pe_proveedores_acreedores as pr3 ON pr3.id_proveedor_acreedor=id_proveedor_3
                    WHERE p.id='$id_pe_producto'";
            
            //log_message('INFO', $sql);
            $row=$this->db->query($sql)->row();
            $precio_ultimo_peso=$row->precio_ultimo_peso;
            $precio_ultimo_unidad=$row->precio_ultimo_unidad;
            $precio_unidad_2=$row->precio_unidad_2;
            $precio_peso_2=$row->precio_peso_2;
            $precio_unidad_3=$row->precio_unidad_3;
            $precio_peso_3=$row->precio_peso_3;
            $ultimo_proveedor=$row->nombre_proveedor_1;
            $unidades_caja=$row->unidades_caja;
            $tipoIva=$row->tipoIva;
            if($precio_ultimo_peso>0) {
                $tipoUnidad="Kg";
                $precio=$precio_ultimo_peso/1000;
                $precio_2=$precio_peso_2/1000;
                $precio_3=$precio_peso_3/1000;
            } else {
                $tipoUnidad="Und";
                $precio=$precio_ultimo_unidad/1000;
                $precio_2=$precio_unidad_2/1000;
                $precio_3=$precio_unidad_3/1000;
            };
            $precio=  number_format($precio,3);
            $precio_compra=$row->precio_compra/1000;
            $descuento=  $row->descuento_1_compra;
            $descuento=number_format($descuento/1000,2);
            return array('tipoIva'=>$tipoIva,
                          'tipoUnidad'=>$tipoUnidad,
                          'unidades_caja'=>$unidades_caja/1000,
                          'precio'=>$precio,
                          'descuento'=>$descuento,
                          'id_proveedor_1'=>$row->id_proveedor_1,
                          'nombre_proveedor_1'=>$row->nombre_proveedor_1,
                          'unidades_precio'=>$row->unidades_precio/1000,
                          'id_proveedor_2'=>$row->id_proveedor_2,
                          'nombre_proveedor_2'=>$row->nombre_proveedor_2,
                          'precio_2'=>$precio_2,
                          'precio_compra'=>$precio_compra,
                          'id_proveedor_3'=>$row->id_proveedor_3,
                          'nombre_proveedor_3'=>$row->nombre_proveedor_3,
                          'precio_3'=>$precio_3,
                );
        }
        
        
        function grabarDatosEstudiosMercado(){
            extract($_POST);
            
            $set="codigo_producto='$codigo_producto',
                    nombre='$nombre',
                    iva='$iva',    
                    tarifa_venta_peso1='$pvp1p',
                    tarifa_venta_peso2='$pvp2p',
                    tarifa_venta_peso3='$pvp3p',
                    tarifa_venta_unidad1='$pvp1u',
                    tarifa_venta_unidad2='$pvp2u',
                    tarifa_venta_unidad3='$pvp3u',
                    pvp1='$pvp1',    
                    pvp2='$pvp2',
                    pvp3='$pvp3',
                    fecha_1='$fecha1',   
                        fecha_2='$fecha2',   
                            fecha_3='$fecha3',   
                    url_web1='$web1',
                    url_web2='$web2',
                    url_web3='$web3',
                    peso1='$peso1',
                    peso2='$peso2',
                    peso3='$peso3',
                    precio_compra1='$precio_compra1',
                    precio_compra2='$precio_compra2',
                    precio_compra3='$precio_compra3'";
                    
            $sql="INSERT INTO pe_productos_mercado SET $set ON DUPLICATE KEY UPDATE $set";
            $this->db->query($sql); 
            return $sql;
        }
        
        function updatePrecioCompraTransformacion($codigo_producto,$precioNuevo){
            
                    $id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto); 
                    $nombreProducto=$this->productos_->getNombre($id_pe_producto);
                    $message ="<br><strong>$codigo_producto - $nombreProducto</strong>";  
                    $preciosAnteriores=$this->productos_->getPrecios($codigo_producto);
                    
                    $preciosNuevos=$this->productos_->getPrecios($codigo_producto);
                    //$precioCompraNuevos=$preciosNuevos['precio_ultimo_unidad']==0?$preciosNuevos['precio_ultimo_peso']:$preciosNuevos['precio_ultimo_unidad'];

                    
                    $tabla='<table border="1" style="padding-left:5px;padding-right:5px;">';
                    $tabla.='<tr><th colspan="8" style="padding:0 3px 0 3px;">Precio Compra/Tarifas Ventas ANTERIORES</th></tr>';
                    $tabla.='<tr>'
                            . '<th style="padding:0 3px 0 3px;">Tipo</th>'
                            . '<th style="padding:0 3px 0 3px;">Precio Compra</th>'
                            . '<th style="padding:0 3px 0 3px;"></th>'
                            . '<th style="padding:0 3px 0 3px;">Tarifa PVP</th>'
                            . '<th style="padding:0 3px 0 3px;">Margen %</th>'
                          //  . '<th style="padding:0 3px 0 3px;">Tarifa Prof</th>'
                          //  . '<th style="padding:0 3px 0 3px;">Tarifa Mín</th>'
                           
                            . '</tr>';
                       
                    $tabla.='<tr>'
                            . '<td style="padding:0 3px 0 3px;">'.$preciosAnteriores['tipoUnidad'].'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores['precioCompra']/1000,3).'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.' '.'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores['tarifaVenta']/1000,2).'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores['margenTienda']/1000,2).'</td>'
                          //  . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores['tarifaProfesional']/1000,2).'</td>'
                          //  . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores['tarifaProfesionalVip']/1000,2).'</td>'
                            
                            . '</tr>';
                    $tabla.='<tr><th colspan="8" style="padding:0 3px 0 3px;">Precio Compra Nuevo/Tarifas Recalculadas</th></tr>';
                    $tabla.='<tr>'
                            . '<th style="padding:0 3px 0 3px;">Tipo</th>'
                            . '<th style="padding:0 3px 0 3px;">Precio Compra</th>'
                            . '<th style="padding:0 3px 0 3px;">Dif %</th>'
                            . '<th style="padding:0 3px 0 3px;">Tarifa PVP</th>'
                            . '<th style="padding:0 3px 0 3px;">Margen %</th>'
                          //  . '<th style="padding:0 3px 0 3px;">Tarifa Prof</th>'
                          //  . '<th style="padding:0 3px 0 3px;">Tarifa Mín</th>'
                            
                            . '</tr>';
                 
            
                $und=$this->getUnidadCodigoProducto($codigo_producto);
               // $id_proveedor_web=92;  //Pernil 181 (Transformaciones)
                $descuento_1_compra=0;
                if($und=="Und"){
                    $sql="UPDATE pe_productos SET descuento_1_compra='$descuento_1_compra', precio_transformacion_unidad='$precioNuevo',precio_compra='$precioNuevo' WHERE codigo_producto='$codigo_producto'";
                    $this->db->query($sql);
                }
                if($und=="Kg"){
                    $sql="UPDATE pe_productos SET descuento_1_compra='$descuento_1_compra', precio_transformacion_peso='$precioNuevo',precio_compra='$precioNuevo' WHERE codigo_producto='$codigo_producto'";
                    $this->db->query($sql);
                }
                
                $precioCompra=$this->getPrecioCompraFinal($codigo_producto);
                $precioTransformacion=$this->getPrecioTransformacion($codigo_producto);
                
                $sql="UPDATE pe_productos SET precio_compra='$precioCompra',precio_transformacion='$precioTransformacion' WHERE codigo_producto='$codigo_producto'";
                    $this->db->query($sql);
                
                //$this->regularizarDatosProducto($id_pe_producto);
                
                //$this->setMargenPrecioCompraTarifaVenta($this->getId_pe_producto($codigo_producto));
                
                $preciosNuevos=$this->productos_->getPrecios($codigo_producto);
                 //Manteniendo la misma tarifa venta
                    if($preciosAnteriores['precioCompra'])
                            $dif=number_format($preciosNuevos['precioCompra']/$preciosAnteriores['precioCompra']*100-100,2);
                    //if($preciosNuevos['precioCompra'])
                    $margenNuevo=$this->productos_->margen($preciosAnteriores['tarifaVenta']/1000,$preciosNuevos['precioCompra']/1000,0,$preciosNuevos['iva']/1000);
                   
                    $sql="UPDATE pe_productos SET margen_real_producto='".number_format($margenNuevo*1000,2,".","")."' WHERE codigo_producto='$codigo_producto'";
                    $this->db->query($sql);
                    
                    
                    $tabla.='<tr>'
                            . '<td style="padding:0 3px 0 3px;">'.$preciosNuevos['tipoUnidad'].'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.number_format($preciosNuevos['precioCompra']/1000,3).'</td>'
                            . '<td style="padding:0 3px 0 3px;">'. $dif .'</td>'
                            . '<td style="padding:0 3px 0 3px;background-color:lightgreen">'.number_format($preciosAnteriores['tarifaVenta']/1000,2).'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.number_format($margenNuevo,2).'</td>'
                        //    . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores['tarifaProfesional']/1000,2).'</td>'
                        //    . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores['tarifaProfesionalVip']/1000,2).'</td>'
                            . '<td style="padding:0 3px 0 3px;background-color:lightgreen">Manteniendo misma tarifa venta</td>'
                            
                            . '</tr>'; 
                    
                     //Manteniendo el mismo margen
                    //tarifaVenta($precioCompra,$iva,$beneficio)
                    $tarifaVentaMismoBeneficio=$this->productos_->tarifaVenta($preciosNuevos['precioCompra']/1000,$preciosNuevos['iva']/1000,$preciosAnteriores['margenTienda']/1000);
                    $tarifaProfesionalesMismoBeneficio=$this->productos_->tarifaProfesional($tarifaVentaMismoBeneficio,$preciosNuevos['descuento_profesionales']/1000,$preciosNuevos['iva']/1000);
                    $tarifaProfesionalesVipMismoBeneficio=$this->productos_->tarifaProfesional($tarifaVentaMismoBeneficio,$preciosNuevos['descuento_profesionales_vip']/1000,$preciosNuevos['iva']/1000);
                   
                    $color="background-color:yellow";
                    if(number_format($preciosAnteriores['tarifaVenta']/1000,2)==number_format($tarifaVentaMismoBeneficio,2)) $color="background-color:lightgreen";
                    $tabla.='<tr>'
                            . '<td style="padding:0 3px 0 3px;">'.' '.'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.' '.'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.' '.'</td>'
                            . '<td style="padding:0 3px 0 3px;'.$color.'">'.number_format($tarifaVentaMismoBeneficio,2).'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.number_format($preciosAnteriores['margenTienda']/1000,2).'</td>'
                          //  . '<td style="padding:0 3px 0 3px;">'.number_format($tarifaProfesionalesMismoBeneficio,2).'</td>'
                          //  . '<td style="padding:0 3px 0 3px;">'.number_format($tarifaProfesionalesVipMismoBeneficio,2).'</td>'
                            . '<td style="padding:0 3px 0 3px;'.$color.'">Manteniendo mismo margen de beneficio</td>'
                            . '</tr>';
                    //tarifa nueva con beneficfio recomendado
                    if(!$preciosNuevos['beneficioRecomendado']) $preciosNuevos['beneficioRecomendado']=35000;
                    $tarifaVentaBeneficioRecomendado=$this->productos_->tarifaVenta($preciosNuevos['precioCompra']/1000,$preciosNuevos['iva']/1000,$preciosNuevos['beneficioRecomendado']/1000);
                    $tarifaProfesionalesBeneficioRecomendado=$this->productos_->tarifaProfesional($tarifaVentaBeneficioRecomendado,$preciosNuevos['descuento_profesionales']/1000,$preciosNuevos['iva']/1000);
                    $tarifaProfesionalesVipBeneficioRecomendado=$this->productos_->tarifaProfesional($tarifaVentaBeneficioRecomendado,$preciosNuevos['descuento_profesionales_vip']/1000,$preciosNuevos['iva']/1000);
                   
                    $color="background-color:yellow";
                    if(number_format($preciosAnteriores['tarifaVenta']/1000,2)==number_format($tarifaVentaBeneficioRecomendado,2)) $color="background-color:lightgreen";
                    
                    $tabla.='<tr>'
                            . '<td style="padding:0 3px 0 3px;">'.' '.'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.' '.'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.' '.'</td>'
                            . '<td style="padding:0 3px 0 3px;'.$color.'">'.number_format($tarifaVentaBeneficioRecomendado,2).'</td>'
                            . '<td style="padding:0 3px 0 3px;">'.number_format($preciosNuevos['beneficioRecomendado']/1000,2).'</td>'
                          //  . '<td style="padding:0 3px 0 3px;">'.number_format($tarifaProfesionalesBeneficioRecomendado,2).'</td>'
                          //  . '<td style="padding:0 3px 0 3px;">'.number_format($tarifaProfesionalesVipBeneficioRecomendado,2).'</td>'
                            . '<td style="padding:0 3px 0 3px;'.$color.'">Tarifa venta PVP con beneficio recomendado</td>'
                            . '</tr>';
                    
                    $tabla.='</table>'; 
                    
                    $message .=$tabla;
                    $message .='<br>';
                         
            
                return $message;;
        }
        
        function tarifaVenta($precioCompra,$iva,$beneficio){
            if ((100-$beneficio)==0) return 'Infinity';
            return $precioCompra*(100+$iva)/(100-$beneficio);
        }
        function getIdProducto($codigo_producto){
            $sql="SELECT id_producto FROM pe_productos 
                    WHERE codigo_producto='$codigo_producto'";
            $result=$this->db->query($sql);
            return $result->row()->id_producto;
        }
        function getBeneficioRecomendado($codigo_producto){
            $sql="SELECT beneficio_recomendado FROM pe_productos 
                    WHERE codigo_producto='$codigo_producto'";
            $result=$this->db->query($sql);
            return $result->row()->beneficio_recomendado;
        }
        function getDatosProducto($codigo_producto){
            $sql="SELECT * FROM pe_productos 
                    WHERE codigo_producto='$codigo_producto'";
            //log_message('INFO','getDatosProducto '.$sql);
            $result=$this->db->query($sql);
            return $result->row();
        }
        function getDatosProductoPrecioCompra($codigo_producto){
            $precioCompra=0;
            $sql="SELECT * FROM pe_productos 
                    WHERE codigo_producto='$codigo_producto'";
            if($this->db->query($sql)->num_rows()==0){
                return $precioCompra;
            } 
            $precioCompra=$this->db->query($sql)->row()->precio_compra;
            return $precioCompra;
        }
        
        function getFullProducto($id_pe_producto){
            if($id_pe_producto=="") return;
            $sql="SELECT p.*  FROM pe_productos p 
                    -- LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo
                    -- LEFT JOIN pe_familias f ON p.id_familia=f.id_familia
                    -- LEFT JOIN pe_users u ON u.id=p.modificado_por
                    -- LEFT JOIN pe_proveedores pr ON pr.id_proveedor=p.id_proveedor_web
                    WHERE p.id='$id_pe_producto'";
            return $this->db->query($sql)->row_array();        
        }

        function getFamiliasOpciones(){
            $sql="SELECT * FROM pe_familias";
            $result=$this->db->query($sql)->result();
            $opciones="";
            foreach($result as $k=>$v){
                $opciones.='<option value="'.$v->id.'">'.$v->nombre_familia.'</option>';
            }
            return $opciones;
        }
        function getGruposOpciones(){
            $sql="SELECT * FROM pe_grupos";
            $result=$this->db->query($sql)->result();
            $opciones="";
            foreach($result as $k=>$v){
                $opciones.='<option value="'.$v->id.'">'.$v->nombre_grupo.'</option>';
            }
            return $opciones;
        }


        function getProducto($id_pe_producto,$status_producto=1){
            if($id_pe_producto=="") return;
            $sql="SELECT * FROM pe_productos p 
                    WHERE id='$id_pe_producto' AND status_producto=$status_producto";
            //log_message('INFO',$sql);
            $row=$this->db->query($sql)->row();
            return array(
                'codigo_producto'=>$row->codigo_producto,
                'codigo_bascula'=>$row->id_producto,
                'nombre'=>$row->nombre,
                'nombre_generico'=>$row->nombre_generico,
                'codigo_ean'=>$row->codigo_ean,
                'tipo_unidad'=>$row->tipo_unidad,
                'precio_compra'=>$row->precio_compra,
                'tarifa_venta'=>$row->tarifa_venta,
                'iva'=>$row->iva,
                'unidades_precio'=>$row->unidades_precio,
                
            );
        }
        
        function isPack($codigo_producto){
            return $this->db->query("SELECT * FROM pe_productos WHERE codigo_producto='$codigo_producto' AND id_familia='54'")->num_rows();
        }
        
        function isEmbalaje($id){
            //devuelve codigo_producto si es embalaje o "" si no lo es
            $embalaje="";
            if($this->db->query("SELECT id_grupo,codigo_producto FROM pe_productos WHERE id='$id'")->row()->id_grupo==19)
                $embalaje=$this->db->query("SELECT id_grupo,codigo_producto FROM pe_productos WHERE id='$id'")->row()->codigo_producto;
            return $embalaje;
        }
        
        function getDatosPeEmbalajes($id){
            $sql="SELECT * FROM pe_embalajes WHERE id='$id' ";
            $row=$this->db->query($sql)->row();
            return array(
                
                'precio_embalaje_tienda'=>$row->precio_embalaje_tienda,
                'precio_embalaje_online'=>$row->precio_embalaje_online,
                'margen_tienda'=>$row->margen_tienda,
                'margen_online'=>$row->margen_online,
            );
        }
        
        function getComponentesPack($codigo_producto){
            $id_pe_producto_pack=$this->productos_->getId_pe_producto($codigo_producto);
            
            if($this->db->query("SELECT id FROM pe_packs WHERE id_pe_producto_pack='$id_pe_producto_pack'")->num_rows()==0) return false;
            
            $id_Pack=$this->db->query("SELECT id FROM pe_packs WHERE id_pe_producto_pack='$id_pe_producto_pack'")->row()->id;
            $datosPack=$this->productos_->getDatosPePacks($id_Pack);
            $pvpPack=$datosPack['pvp_pack'];
           
            $sql="SELECT * FROM pe_lineas_packs WHERE id_pack='$id_Pack'";
            //if(!$this->db->query($sql)->num_rows()) return array();
            $result=$this->db->query($sql)->result();
            $componentes=array();
            $tarifasVentas=array();
            $totalVenta=0;
            foreach($result as $k=>$v){
                //echo $v->codigo_producto.'<br>';
                $id_pe_producto=$this->getId_pe_producto($v->codigo_producto);
                $tarifaVenta=$this->getTarifaVenta($id_pe_producto);
                
                //echo '$tarifaVenta componente '.$v->codigo_producto.'  '.$tarifaVenta.'<br>';
                //echo 'cantidad componente '.$v->codigo_producto.'  '.$v->cantidad.'<br>';
                //$tarifaVenta*=$v->cantidad/1000;
                //echo '$tarifaVenta componente '.$v->codigo_producto.'  '.$tarifaVenta.'<br>';
                
                $tarifaVenta=($tarifaVenta-$tarifaVenta*$v->descuento/100000);
                //echo '$tarifaVenta*cantidad - descuento componente A'.$v->codigo_producto.'  '.$tarifaVenta.'<br>';
                $tarifaVenta=number_format($tarifaVenta/1000,2);
                
                //echo '$tarifaVenta*cantidad - descuento componente round'.$v->codigo_producto.'  '.$tarifaVenta.'<br>';
                
                $totalVenta+=$v->cantidad*$tarifaVenta;
                $componentes[]=array('codigo_producto'=>$v->codigo_producto,'tarifaVenta'=>$tarifaVenta,'cantidad'=>$v->cantidad);
            }
            $totalVenta/=1000;
            $totalVenta=intval(round($totalVenta));
            $factor=$totalVenta/$pvpPack;
           // echo '$totalVenta '.$totalVenta.'<br>';
           // echo '$pvpPack '.$pvpPack.'<br>';
           // echo '$factor '.$factor.'<br>';
            
            foreach($componentes as $k=>$v){
                //$componentes[$k]['tarifaVenta']=$componentes[$k]['tarifaVenta']*$factor;
            }
            return $componentes;
            
        }
        
        function getDatosPePacks($id){
            $sql="SELECT * FROM pe_packs WHERE id='$id' ";
            //echo $sql.'<br>';
            $row=$this->db->query($sql)->row();
            return array(
                //'id'=>$row->id,
                'precio_pack'=>$row->precio_pack,
                'pvp_pack'=>$row->pvp_pack,
                'margen_pack'=>$row->margen_pack,
            );
        }
        
        function grabarPe_embalajes($id_pe_producto){
            $sql="SELECT codigo_producto FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0){
                $sql="INSERT INTO pe_embalajes SET "
                        . " codigo_producto='$id_pe_producto', "
                        . " codigo_bascula='$id_pe_producto', "
                        . " nombre='$id_pe_producto'";
            }
            else {
                $sql="UPDATE pe_embalajes SET "
                        . " codigo_producto='$id_pe_producto', "
                        . " codigo_bascula='$id_pe_producto', "
                        . " nombre='$id_pe_producto' WHERE codigo_producto='$id_pe_producto'";
            }
            $this->db->query($sql);
            return $sql;
        }
        
       
        
        function grabarPe_lineas_embalajes($id_pe_producto,$codigos,$cantidades,$tiendas,$onlines){
            $sql="SELECT id FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
            $id=$this->db->query($sql)->row()->id;
            
            $this->db->query("DELETE FROM pe_lineas_embalajes WHERE id_embalajes='$id'");
            foreach($codigos as $k=>$v){
                $cantidad=$cantidades[$k]*1000;
                $sql="INSERT INTO pe_lineas_embalajes SET "
                        . " id_embalajes='$id', "
                        . " codigo_embalaje='$v', "
                        . " cantidad='".$cantidad."', "
                        . " tienda='".$tiendas[$k]."', "
                        . " online='".$onlines[$k]."'"; 
                $this->db->query($sql);
            }
            
            return $sql;
        }
        
        function ponerCostesEmbalajes($id_producto,$embalajesTienda,$embalajesOnline){
            //log_message('INFO', 'id_producto '.$id_producto);
            $idsProducto=$this->getIdsProductos($id_producto);
            foreach($idsProducto as $k=>$v){
                $codigo_producto=$v['id'];
                $margenTienda=$this->calculoMargenTienda($codigo_producto);
                $margenOnline=$this->calculoMargenOnline($codigo_producto);
                //log_message('INFO', 'id_pe_producto '.$v['id']);
                //log_message('INFO', 'embalajesTienda '.$embalajesTienda);
                //log_message('INFO', 'embalajesOnline '.$embalajesOnline);
                $sql="UPDATE pe_embalajes SET margen_online='$margenOnline', margen_tienda='$margenTienda', precio_embalaje_tienda='$embalajesTienda', precio_embalaje_online='$embalajesOnline' WHERE codigo_producto='$codigo_producto'";
               //log_message('INFO', 'sql '.$sql);
                $this->db->query($sql);
                
            }
        } 
        
        function regularizarPacks($primary_key){
            $codigo_producto=$this->getCodigoProducto($primary_key);
            $sql="SELECT id_pack FROM pe_lineas_packs WHERE codigo_producto='$codigo_producto'";
            if($this->db->query($sql)->num_rows()==0) return;
            $result=$this->db->query($sql)->result();
            foreach($result as $k=>$v){
                 $id=$v->id_pack;
                 //log_message('INFO', '-------PACK id_pack '.$v->id_pack);
                 $this->calculoPrecioPack($v->id_pack);
            }
        }
        
        
        function calculoPrecioPack($id) {
            $sql = "SELECT * FROM pe_lineas_packs WHERE id_pack='$id'";
            $result = $this->db->query($sql)->result();
            $precio_compra = 0;
            $tarifaPVPTienda=0;
            $tarifaPVPPack=0;
            $tarifaTienda=0;
            $margenTienda=0;
            $margenPack=0;
            foreach ($result as $k => $v) {
                    $precio = $this->getPrecioCompra($v->codigo_producto);
                    $precio_compra += $precio * $v->cantidad/1000 ;
                    $id_pe_producto=$this->getId_pe_producto($v->codigo_producto);
                    $iva=$this->getIvaId($id_pe_producto);
                    $precio_con_iva=$precio+$precio*$iva/100000;
                    $descuento=$v->descuento/1000;
                    
                    $tarifaTienda=$this->getTarifaVenta($id_pe_producto);
                    $tarifaPack=$tarifaTienda-$tarifaTienda*$descuento/100;
                    
                    $tarifaPVPTienda += round($tarifaTienda*$v->cantidad/1000/1000,2)*1000 ;
                    $tarifaPVPPack += round($tarifaPack*$v->cantidad/1000/1000,2)*1000 ;
                    
                    $margenTienda += ($tarifaTienda-$precio_con_iva)*$v->cantidad/1000;
                    $margenPack += ($tarifaPack-$precio_con_iva)*$v->cantidad/1000;
                    
            }
            $margenProductoTienda=$margenTienda/$tarifaPVPTienda*100000;
            $margenProductoPack=$margenPack/$tarifaPVPPack*100000;
            
           // log_message('INFO', '-------------precio '.$precio_compra);
           // log_message('INFO', '-------------tarifaPVPTienda '.$tarifaPVPTienda);
           // log_message('INFO', '-------------margenProductoTienda '.$margenProductoTienda);
           // log_message('INFO', '-------------tarifaPVPPack '.$tarifaPVPPack);
           // log_message('INFO', '-------------margenProductoPack '.$margenProductoPack);
            //Se graba en pe_packs
            $sql="UPDATE pe_packs SET precio_pack='$precio_compra',"
                    . " pvp_tienda='$tarifaPVPTienda', "
                    . " margen_tienda='$margenProductoTienda',"
                    . " pvp_pack='$tarifaPVPPack',"
                    . " margen_pack='$margenProductoPack'"
                    . " WHERE id='$id'";
            $this->db->query($sql);
            //Se graba en pe_productos
            //Obtener id_pe_producto_pack
            $sql="SELECT id_pe_producto_pack FROM pe_packs WHERE id='$id'";
            $id_pe_producto_pack=$this->db->query($sql)->row()->id_pe_producto_pack;
            $sql="UPDATE pe_productos SET precio_ultimo_unidad='$precio_compra',"
                    . " precio_compra='$precio_compra', "
                    . " tarifa_venta_unidad='$tarifaPVPPack',"
                    . " tarifa_venta='$tarifaPVPPack',"
                    . " margen_real_producto='$margenProductoPack'"
                    . " WHERE id='$id_pe_producto_pack'";
            $this->db->query($sql);
            
            return $costes=array('precio'=>$precio_compra,
                                  'tarifaPVPTienda'=>$tarifaPVPTienda, 
                                   'margenProductoTienda'=>$margenProductoTienda,
                                    'tarifaPVPPack'=>$tarifaPVPPack,
                                    'margenProductoPack'=>$margenProductoPack,
                );
    }
        
        
        
        function calculoPrecioEmbalajeTienda($id_pe_producto) {
            if($id_pe_producto=='') return 0;
            $sql="SELECT id FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
            //log_message('INFO', 'calculoPrecioEmbalajeTienda '. $sql);
            $id=$this->db->query($sql)->row()->id; 
             
            $sql = "SELECT * FROM pe_lineas_embalajes WHERE id_embalajes='$id'";
            $result = $this->db->query($sql)->result();
            $coste = 0;
            foreach ($result as $k => $v) {
                if ($v->tienda) {
                    //$codigo_producto=$this->productos_->getCodigoProducto($v->codigo_embalaje);
                    //echo $v->codigo_embalaje.'<br>';
                    $precio = $this->getPrecioCompra($v->codigo_embalaje);
                    $coste += $precio * $v->cantidad/1000 ;
                }
            }
            return $coste;
    }
    
    

        function calculoPrecioEmbalajeOnline($id_pe_producto) {
            if($id_pe_producto=='') return 0;
            $sql="SELECT id FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
                $id=$this->db->query($sql)->row()->id; 
                $sql="SELECT * FROM pe_lineas_embalajes WHERE id_embalajes='$id'";
                $result=$this->db->query($sql)->result();
                $coste=0;
                foreach($result as $k=>$v){
                    if($v->online) {
                        //$codigo_producto=$this->productos_->getCodigoProducto($v->codigo_embalaje);
                        $precio=$this->getPrecioCompra($v->codigo_embalaje);
                        $coste+=$precio*$v->cantidad/1000;
                    }
                }
                return $coste;        
        }
        
        function getPrecioEmbalajeTienda($id_pe_producto){
            $sql="SELECT precio_embalaje_tienda FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return 0;
            return $this->db->query($sql)->row()->precio_embalaje_tienda;
        }
        
        function getPrecioEmbalajeOnline($id_pe_producto){
            $sql="SELECT precio_embalaje_online FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return 0;
            return $this->db->query($sql)->row()->precio_embalaje_online;
        }
        
        function calculoMargenTienda($id_pe_producto) {
            $sql="SELECT id FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==0) return false;
            $id=$this->db->query($sql)->row()->id;
            $codigo_producto=$this->productos_->getCodigoProducto($id_pe_producto);
            $precio=$this->productos_->getPrecioCompraFinal($codigo_producto);
            //log_message('INFO',"calculoMargenTienda precio ".$precio);
            $costeEmbalajes=$this->productos_->getPrecioEmbalajeTienda($id_pe_producto);
            //log_message('INFO',"calculoMargenTienda costeEmbalajes ".$costeEmbalajes);
            $tarifaVenta=$this->productos_->getTarifaVenta($id_pe_producto);
            //log_message('INFO',"calculoMargenTienda tarifaVenta ".$tarifaVenta);
            $iva=$this->productos_->getIvaId($id_pe_producto);
            //log_message('INFO',"calculoMargenTienda iva ".$iva);
            $costeTotal=$precio+$costeEmbalajes;
            //log_message('INFO',"calculoMargenTienda costeTotal ".$costeTotal);
            $margen=$this->productos_->calculoMargenProducto($costeTotal,$tarifaVenta,$iva);
            //log_message('INFO',"calculoMargenTienda margen ".$margen);
            
            $sql="SELECT * FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()>0){
                $this->db->query("UPDATE pe_embalajes SET margen_tienda='$margen' WHERE codigo_producto='$id_pe_producto'");
            }
            return $margen;
    }
    
    function getIdPeProductoEmbalaje($id){
        $sql="SELECT codigo_producto FROM pe_embalajes WHERE id='$id'";
        $id_pe_producto= $this->db->query($sql)->row()->codigo_producto;
        return $id_pe_producto;
    }
    
    function getIdPePack($id){
        $sql="SELECT id_pe_producto_pack FROM pe_packs WHERE id='$id'";
        $id_pe_producto= $this->db->query($sql)->row()->id_pe_producto_pack;
        return $id_pe_producto;
    }
    
    function calculoMargenOnline($id_pe_producto) {
        $sql="SELECT id FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
        if($this->db->query($sql)->num_rows()==0) return false;
        $id=$this->db->query($sql)->row()->id;
        //return "<span class='derecha' style='width:100%;text-align:right;display:block;color:black'>$id_pe_producto</span>";
        //echo $row.'<br>';
        $codigo_producto=$this->productos_->getCodigoProducto($id_pe_producto);
        $precio=$this->productos_->getPrecioCompraFinal($codigo_producto);
        $costeEmbalajes=$this->productos_->getPrecioEmbalajeOnline($id_pe_producto);
        $tarifaVenta=$this->productos_->getTarifaVenta($id_pe_producto);
        $iva=$this->productos_->getIvaId($id_pe_producto);
        $costeTotal=$precio+$costeEmbalajes;
        $margen=$this->productos_->calculoMargenProducto($costeTotal,$tarifaVenta,$iva);
        
        $sql="SELECT * FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()>0){
                $this->db->query("UPDATE pe_embalajes SET margen_online='$margen' WHERE codigo_producto='$id_pe_producto'");
            }
            
        return $margen;
    }
    
        function eliminarEmbalaje($id_pe_producto){
            $id_embalajes=0;
            $id_producto=$this->db->query("SELECT id_producto FROM pe_productos WHERE id='$id_pe_producto'")->row()->id_producto;
            $result=$this->db->query("SELECT id FROM pe_productos WHERE id_producto='$id_producto'")->result();
            foreach($result as $k=>$v){
                $id=$v->id;
                if($this->db->query("SELECT id FROM pe_embalajes WHERE codigo_producto='$id'")->num_rows()>0){
                    $id_embalajes=$this->db->query("SELECT id FROM pe_embalajes WHERE codigo_producto='$id'")->row()->id;
                    $this->db->query("DELETE FROM pe_lineas_embalajes WHERE id_embalajes='$id_embalajes'");
                    $this->db->query("DELETE FROM pe_embalajes WHERE id='$id_embalajes'");
                }
            }
            
        }
        
        function eliminarPack($id_pe_producto){
            return true;
            $id_embalajes=0;
            $id_producto=$this->db->query("SELECT id_producto FROM pe_productos WHERE id='$id_pe_producto'")->row()->id_producto;
            $result=$this->db->query("SELECT id FROM pe_productos WHERE id_producto='$id_producto'")->result();
            foreach($result as $k=>$v){
                $id=$v->id;
                if($this->db->query("SELECT id FROM pe_embalajes WHERE codigo_producto='$id'")->num_rows()>0){
                    $id_embalajes=$this->db->query("SELECT id FROM pe_embalajes WHERE codigo_producto='$id'")->row()->id;
                    $this->db->query("DELETE FROM pe_lineas_embalajes WHERE id_embalajes='$id_embalajes'");
                    $this->db->query("DELETE FROM pe_embalajes WHERE id='$id_embalajes'");
                }
            }
            
        }
        
        
        function registrarEmbalaje($id_pe_producto,$codigos,$cantidades,$tiendas,$onlines){
            $sql=$this->grabarPe_embalajes($id_pe_producto);
            $sql=$this->grabarPe_lineas_embalajes($id_pe_producto,$codigos,$cantidades,$tiendas,$onlines);
            //
            $this->setPrecioEmbalajeTienda($id_pe_producto);
            $this->setPrecioEmbalajeOnline($id_pe_producto);
            $this->setMargenTienda($id_pe_producto);
            $this->setMargenOnline($id_pe_producto);
            $sql=$this->regularizarCostesEmbalajes($id_pe_producto,$codigos,$cantidades,$tiendas,$onlines);
            return $sql;
        }
        
        function registrarPack($id_pe_producto_pack,$codigos,$cantidades,$descuentos,$totalPrecio_compra,$totalTarifa_ventaPack,$totalTarifa_venta,$margen,$margenPack){
                //log_message('info','==============================================================');
                //log_message('info','$id_pe_producto_pack '.$id_pe_producto_pack);
                //log_message('info','$totalPrecio_compra '.$totalPrecio_compra);
                //log_message('info','$totalTarifa_ventaPack '.$totalTarifa_ventaPack);
                //log_message('info','$totalTarifa_venta '.$totalTarifa_venta);
            
            $sql1=$this->grabarPe_pack($id_pe_producto_pack,$totalPrecio_compra,$totalTarifa_ventaPack,$totalTarifa_venta,$margen,$margenPack);
            $sql2=$this->grabarPe_lineas_pack($id_pe_producto_pack,$codigos,$cantidades,$descuentos);
          
            $this->setPrecioCompraPack($id_pe_producto_pack,$totalPrecio_compra);
            $this->setTarifaVentaPack($id_pe_producto_pack,$totalTarifa_ventaPack,$margenPack) ;
            
            
            /*
             * $this->setMargenesPack($id_pe_producto_pack,$margenPack);

            log_message( 'INFO',$totalTarifa_ventaPack);
            $this->setPrecioCompraPack($id_pe_producto_pack,$totalPrecio_compra);
            $this->setTarifaVentaPack($id_pe_producto_pack,$totalTarifa_ventaPack) ;
            $r=$this->setTarifaVentaPackProfesionales($id_pe_producto_pack,$totalTarifa_ventaPack) ;
            $this->setMargenesPack($id_pe_producto_pack,$codigos,$descuentos);
//
            
            $this-> setPrecioEmbalajeTienda($id_pe_producto);
            $this->setPrecioEmbalajeOnline($id_pe_producto);
            $this->setMargenTienda($id_pe_producto);
            $this->setMargenOnline($id_pe_producto);
            $sql=$this->regularizarCostesEmbalajes($id_pe_producto,$codigos,$cantidades,$tiendas,$onlines);
            */
             return $sql1;
        }

        
        function regularizarCostesEmbalajes($id_pe_producto,$codigos,$cantidades,$tiendas,$onlines){
            $sql="SELECT id_producto FROM pe_productos WHERE id='$id_pe_producto'";
            $codigo_bascula=$this->db->query($sql)->row()->id_producto;
            if($codigo_bascula==0) return;
            $sql="SELECT id FROM pe_productos WHERE id_producto='$codigo_bascula'";
            
            
            if($this->db->query($sql)->num_rows()<=1) return;
            //log_message('INFO', 'regularizarCostesEmbalajes '.$sql );
            $result=$this->db->query($sql)->result();
            foreach($result as $k=>$v){
                $id_pe_producto=$v->id;
                //log_message('INFO', 'regularizarCostesEmbalajes '.$id_pe_producto );
                $this->grabarPe_embalajes($id_pe_producto);
                $this->grabarPe_lineas_embalajes($id_pe_producto,$codigos,$cantidades,$tiendas,$onlines);
                $this->setPrecioEmbalajeTienda($id_pe_producto);
                $this->setPrecioEmbalajeOnline($id_pe_producto);
                $this->setMargenTienda($id_pe_producto);
                $this->setMargenOnline($id_pe_producto);
            }
        }
        
        
        function getEmbalajes($id_pe_producto){
            $codigo_producto=$this->getCodigoProducto($id_pe_producto);
            $sql="SELECT id FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()==1){
                $id_embalajes=$this->db->query("SELECT id FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'")->row()->id;
                $result=$this->db->query("SELECT * FROM pe_lineas_embalajes WHERE id_embalajes='$id_embalajes'")->result();
                $lineas=array();
                foreach($result as $k=>$v){
                    $id_pe_producto=$this->getId_pe_producto($v->codigo_embalaje);
                    $precio=$this->getPrecioCompra($v->codigo_embalaje);
                    $unidades_precio=$this->getUnidadesPrecio($id_pe_producto);
                    $precio=$precio/1000;
                    $nombre=$this->getNombre($id_pe_producto);
                    $tipo_unidad=$this->getUnidad($id_pe_producto);
                    $lineas[]=array('tipo_unidad'=>$tipo_unidad,'nombre'=>$nombre,'precio'=>$precio,'codigo_embalaje'=>$v->codigo_embalaje,'cantidad'=>$v->cantidad,'tienda'=>$v->tienda,'online'=>$v->online);
                }
                return $lineas;
            }
            else return false;
        }
        
         function getProductosPack($id_pe_producto_pack){
            $codigo_producto=$this->getCodigoProducto($id_pe_producto_pack);
            $sql="SELECT id FROM pe_packs WHERE id_pe_producto_pack='$id_pe_producto_pack'";
            if($this->db->query($sql)->num_rows()==1){
                $id_pack=$this->db->query("SELECT id FROM pe_packs WHERE id_pe_producto_pack='$id_pe_producto_pack'")->row()->id;
                $result=$this->db->query("SELECT * FROM pe_lineas_packs WHERE id_pack='$id_pack'")->result();
                $lineas=array();
                foreach($result as $k=>$v){
                    $id_pe_producto=$this->getId_pe_producto($v->codigo_producto);
                    $precio=$this->getPrecioCompra($v->codigo_producto);
                    $iva=$this->getIvaId($id_pe_producto);
                    $pvp=$this->getTarifaVenta($id_pe_producto);
                    $precio=$precio/1000;
                    $nombre=$this->getNombre($id_pe_producto);
                    $descuento=$v->descuento;
                    $codigo_producto=$v->codigo_producto;
                    $lineas[]=array('iva'=>$iva,'pvp'=>$pvp,'nombre'=>$nombre,'precio'=>$precio,'codigo_producto'=>$v->codigo_producto,'cantidad'=>$v->cantidad,'descuento'=>$descuento);
                }
                return $lineas;
            }
            else return false;
        }
       
        function getBaseIva($bruto,$tipoiva){
            $iva=$bruto*$tipoiva/(100+$tipoiva);
            $base=$bruto*100/(100+$tipoiva);
            return array('base'=>$base,'iva'=>$iva);
        }
        
        function getNombresGrupoFamilia($codigo_producto){
            $sql="SELECT g.nombre_grupo as grupo, f.nombre_familia as familia FROM pe_productos p"
                    . " LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo "
                    . " LEFT JOIN pe_familias f ON p.id_familia=f.id_familia "
                    . " WHERE p.codigo_producto='$codigo_producto'";
            $row=$this->db->query($sql)->row();
            return array('grupo'=>$row->grupo, 'familia'=>$row->familia);
        }
        /*
        function getDatosProductos(){
            $sql="SELECT * FROM pe_productos 
                    WHERE codigo_producto='$codigo_producto'";
            $result=$this->db->query($sql);
            return $result->row();
        }
        */
        function getPrecioTransformacion($codigo_producto){
            //log_message('INFO',$codigo_producto);
            $row=$this->getDatosProducto($codigo_producto);
            $precioCompra=$row->precio_transformacion_unidad;
            if(!$precioCompra) $precioCompra=$row->precio_transformacion_peso;
            return $precioCompra;
        }
        
        function getPrecioCompra_($codigo_producto){
            $row=$this->getDatosProducto($codigo_producto);
            $precioCompra=$row->precio_ultimo_unidad;
            if(!$precioCompra) $precioCompra=$row->precio_ultimo_peso;
            return $precioCompra;
        }
        
        function getPrecioCompra($codigo_producto){
            $precioCompra=$this->getDatosProductoPrecioCompra($codigo_producto);
            //$precioCompra=$row->precio_compra;
            return $precioCompra;
        }
        function getPesoProducto($codigo_producto){
            $row=$this->getDatosProducto($codigo_producto);
            $pesoProducto=$row->peso_real;
            return $pesoProducto;
        }
        function getPesoReal($id_pe_producto){
            //echo $id_pe_producto.' ';
            if($this->db->query("SELECT peso_real FROM pe_productos WHERE id='$id_pe_producto'")->num_rows()==0) return 0;
            return $this->db->query("SELECT peso_real FROM pe_productos WHERE id='$id_pe_producto'")->row()->peso_real;
        }
        
        function getPrecioCompraFinal($codigo_producto){
            $precioCompra=$this->getPrecioTransformacion($codigo_producto);
            if(!$precioCompra) {
                $row=$this->getDatosProducto($codigo_producto);
                $precioCompra=$row->precio_ultimo_unidad;
                if(!$precioCompra) $precioCompra=$row->precio_ultimo_peso;
                $precioCompra=$precioCompra-$precioCompra*$row->descuento_1_compra/100000;
                $precioCompra/=$row->unidades_precio/1000;
            }
            return $precioCompra;
        }
        
        function calculoPrecioCompraFinal($codigo_producto){
            $precioCompra=$this->getPrecioTransformacion($codigo_producto);
            if(!$precioCompra) {
                $row=$this->getDatosProducto($codigo_producto);
                $precioCompra=$row->precio_ultimo_unidad;
                if(!$precioCompra) $precioCompra=$row->precio_ultimo_peso;
                $precioCompra=$precioCompra-$precioCompra*$row->descuento_1_compra/100000;
            }
            return $precioCompra;  //precioCompra * 1000
        }
        
        function getDatosProductoEstudioMercado($codigo_producto){
            $sql="SELECT * FROM pe_productos_mercado WHERE codigo_producto='$codigo_producto'";
            $result=$this->db->query($sql);
            return $result->row();
        }
        
        function getIva($codigo_producto){
            $sql="SELECT i.valor_iva  as valor_iva FROM pe_productos p
                    LEFT JOIN pe_grupos g ON p.id_grupo=g.id_grupo
                    LEFT JOIN pe_ivas i ON i.id_iva=g.id_iva
                    WHERE codigo_producto='$codigo_producto'";
           // log_message('INFO', 'getIva -----------------'.$sql);
            $result=$this->db->query($sql);
            return $result->row();
        }
        
        function getId_pe_producto($codigo_producto){
            $sql="SELECT id FROM pe_productos WHERE codigo_producto='$codigo_producto'";
            return $this->db->query($sql)->row()->id;
        }
        
        function getPrecios($codigo_producto){
            $sql="SELECT * FROM pe_productos WHERE codigo_producto='$codigo_producto'";
            $row=$this->db->query($sql)->row();
                
            return array(
                'tipoUnidad'=>$row->tipo_unidad,
                'precioCompra'=>$row->precio_compra,
                'precio_ultimo_unidad'=>$row->precio_ultimo_unidad,
                'precio_ultimo_peso'=>$row->precio_ultimo_peso,
                'tarifaVenta'=>$row->tarifa_venta,
                'dto'=>$row->descuento_1_compra,
                'margenTienda'=>$row->margen_real_producto,
                'tarifaProfesional'=>$row->tarifa_profesionales,
                'tarifaProfesionalVip'=>$row->tarifa_profesionales_vip,
                'descuento_profesionales'=>$row->descuento_profesionales,
                'descuento_profesionales_vip'=>$row->descuento_profesionales_vip,
                'beneficioRecomendado'=>$row->beneficio_recomendado,
                'iva'=>$row->iva,
                
            );
        }
        function margen($tarifaVenta,$precioCompra,$dto,$iva){
            if(!$tarifaVenta) return "";
            $precioCompra=$precioCompra-$precioCompra*$dto/100;
            return (100*$tarifaVenta-$precioCompra*(100+$iva))/$tarifaVenta;
        }
        function tarifaProfesional($tarifaPVP,$descuento,$iva){
            $base=$tarifaPVP/(1+$iva/100);
            return $base-$base*$descuento/100;
        }
        
        function getCodigos(){
            $sql="SELECT codigo_producto, nombre_web, peso_real FROM pe_productos ORDER BY codigo_producto";
            $result=$this->db->query($sql);  
            $codigos=array();
            
            foreach($result->result() as $k=>$v){
                if(strlen($v->codigo_producto)==13){
                $peso=$v->peso_real;
                if($peso) {
                    $peso=$peso/1000;
                    $peso=" (".$peso.' Kg)';
                }else $peso="";
                if(!$v->nombre_web) $v->nombre_web="SIN NOMBRE ASIGNADO";
                $codigos[$v->codigo_producto]=$v->codigo_producto.' - '.$v->nombre_web.$peso;
                
                }
            }
            return $codigos;
        }
        
        function getProductoPesos($id_pe_producto){
            $codigo_bascula=$this->getCodigoBascula($id_pe_producto);
            if($codigo_bascula!=0){
                $sql="SELECT id as id_pe_producto, codigo_producto, nombre, peso_real,id_producto FROM pe_productos WHERE id_producto='$codigo_bascula' AND status_producto=1 ORDER BY peso_real";
            }
            else {
                $sql="SELECT id as id_pe_producto, codigo_producto, nombre, peso_real,id_producto FROM pe_productos WHERE id='$id_pe_producto' AND status_producto=1 ORDER BY peso_real";
            }
            
            $result=$this->db->query($sql);  
            $codigos=array();
            
            foreach($result->result() as $k=>$v){
                $tipoUnidad=$this->getUnidad($v->id_pe_producto);
                $linea=array('id_pe_producto'=>$v->id_pe_producto,
                             'codigo_producto'=>$v->codigo_producto,
                             'nombre'=>$v->nombre,
                             'peso_real'=>$v->peso_real,
                             'codigo_bascula'=>$v->id_producto,
                             'tipoUnidad'=>$tipoUnidad);
                $codigos[]=$linea;
                }
            
            return $codigos;
        }
        
        function getCodigoBascula($id_pe_producto){
            $sql="SELECT id_producto FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()===1) return $this->db->query($sql)->row()->id_producto;
            return "";
        }
        
        function getIvaId($id_pe_producto){
            $sql="SELECT iva FROM pe_productos WHERE id='$id_pe_producto'";
            return $this->db->query($sql)->row()->iva;
        }
        
        function getNombre($id_pe_producto){
            $sql="SELECT nombre FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()===1) return $this->db->query($sql)->row()->nombre;
            return "";
        }
        
        function getIdsProductos($id_producto){
            $sql="SELECT id FROM pe_productos WHERE id_producto='$id_producto'";
            $idsProductos=array();
            if($this->db->query($sql)->num_rows()==0) return $idsProductos;
            $idsProductos=$this->db->query($sql)->result_array();
            return $idsProductos;
        }
        
        function getCodigoProducto($id_pe_producto){
            $sql="SELECT codigo_producto FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()===1) return $this->db->query($sql)->row()->codigo_producto;
            return "";
        }
        
        function getProveedorWeb($id_pe_producto){
            $sql="SELECT id_proveedor_web FROM pe_productos WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()===1) return $this->db->query($sql)->row()->id_proveedor_web;
            return "";
        }
        
        function getInfoCodigoBascula($id_producto){
            $sql="SELECT p.codigo_producto, p.nombre, p.id,peso_real,p.status_producto, s.cantidad FROM pe_productos p 
                  LEFT JOIN pe_stocks_totales as s ON s.id_pe_producto=p.id
                  WHERE status_producto=1 AND id_producto='$id_producto' ORDER BY codigo_producto";
            $result=$this->db->query($sql)->result();
            $codigos_producto=array();
            $tipoUnidad=array();
            $pesos=array();
            $nombres=array();
            $precio=array();
            $pvp=array();
            $proveedor=array();
            $status=array();
            $cantidad=array();
            foreach($result as $k=>$v){
                $codigos_producto[]=$v->codigo_producto;
                $nombres[]=$v->nombre;
                $tipoUnidad[]=$this->getUnidad($v->id);
                $pesos[]=$v->peso_real/1000;
                $costePVP=$this->getCostePVP($v->id);
                $precio[]= $costePVP['coste'];
                $pvp[]= $costePVP['PVP'];
                $proveedor[]=$this->getNombreProveedorWeb($v->id);
                $status[]=$v->status_producto;
                $id_pe_producto=$v->id;
                $cantidad[]=$v->cantidad/1000;
            }
            
            $fechaHasta=date('Y-m-d');
            $fechaDesde=date('Y-m-d',strtotime ( '-1 year' , strtotime ( $fechaHasta ) )) ;
            //$this->load->model('estadisticas_model');
            //$datos=$this->estadisticas_model->getCantidadesVentas($id_pe_producto,$fechaDesde,$fechaHasta);
     
            
            return array('codigos_producto'=>$codigos_producto,
                'tipoUnidad'=>$tipoUnidad,
                'pesos'=>$pesos,
                'nombres'=>$nombres,
                'proveedor'=>$proveedor,
                'precio'=>$precio,
                'pvp'=>$pvp,
                'status'=>$status,
                'cantidad'=>$cantidad,
               // 'datos'=>$datos,
                );
            
        }
        
        function getNombreProveedorWeb($id_pe_producto){
            $sql="SELECT pa.nombre as proveedor 
                  FROM pe_productos pr
                  LEFT JOIN pe_proveedores_acreedores pa ON pa.id_proveedor_acreedor=pr.id_proveedor_web
                  WHERE id='$id_pe_producto'";
            if($this->db->query($sql)->num_rows()===1) return $this->db->query($sql)->row()->proveedor;
            return "";
        }
        
        
        
        
        
        function crearTablaCodigosProductos(){
            $sql="SELECT id_producto, codigo_producto, nombre_web FROM pe_productos WHERE 1";
            $result=$this->db->query($sql);
            $sql="DELETE FROM `pe_productos_codigos_estudio` WHERE 1";
            $this->db->query($sql);
            foreach($result->result() as $k=>$v){
                $id_producto=$v->id_producto;
                $codigo_producto=$v->codigo_producto;
                $nombre_web=$v->nombre_web;
                $sql="INSERT INTO pe_productos_codigos_estudio 
                    SET id_producto='$id_producto',
                        codigo_producto='$codigo_producto',
                            nombre_web='$nombre_web'";
                $this->db->query($sql);
            }
        }
        
        
        function getCodigosNombre(){
            $sql="SELECT codigo_producto, nombre_web, peso_real FROM pe_productos ORDER BY nombre_web";
            $result=$this->db->query($sql);  
            $codigos=array();
            
            foreach($result->result() as $k=>$v){
                if(strlen($v->codigo_producto)==13){
                $peso=$v->peso_real;
                if($peso) {
                    $peso=$peso/1000;
                    $peso=" (".$peso.' Kg)';
                }else $peso="";
                if(!$v->nombre_web) $v->nombre_web="SIN NOMBRE ASIGNADO";
                $codigos[$v->codigo_producto]=$v->nombre_web.$peso.' - '.$v->codigo_producto;//$v->codigo_producto.' '.$v->nombre_web.$peso;
                
                }
            }
            
            return $codigos;
        }
        
        function getCodigosEstudiosMercado(){
            $sql="SELECT codigo_producto, nombre, peso_real FROM pe_productos_mercado WHERE LEFT(codigo_producto,2)='EM' ORDER BY codigo_producto";
            $result=$this->db->query($sql);  
            $codigos=array();
            
            foreach($result->result() as $k=>$v){
                if(strlen($v->codigo_producto)==13){
                $peso=$v->peso_real;
                if($peso) {
                    $peso=$peso/1000;
                    $peso=" (".$peso.' Kg)';
                }else $peso="";
                if(!$v->nombre) $v->nombre="SIN NOMBRE ASIGNADO";
                $codigos[$v->codigo_producto]=$v->codigo_producto.' - '.$v->nombre.$peso;
                }
            }
            return $codigos;
        }
        
        function getCodigosEstudiosMercadoNombre(){
            $sql="SELECT codigo_producto, nombre, peso_real FROM pe_productos_mercado WHERE LEFT(codigo_producto,2)='EM' ORDER BY codigo_producto";
            $result=$this->db->query($sql);  
            $codigos=array();
            
            foreach($result->result() as $k=>$v){
                if(strlen($v->codigo_producto)==13){
                $peso=$v->peso_real;
                if($peso) {
                    $peso=$peso/1000;
                    $peso=" (".$peso.' Kg)';
                }else $peso="";
                if(!$v->nombre) $v->nombre="SIN NOMBRE ASIGNADO";
                $codigos[$v->codigo_producto]=$v->nombre.$peso.' - '.$v->codigo_producto;//$v->codigo_producto.' '.$v->nombre.$peso;
                }
            }
            return $codigos;
        }
        
        function getSiguienteCodigoEM(){
           $sql="SELECT codigo_producto FROM pe_productos_mercado WHERE LEFT(codigo_producto,2)='EM' ORDER BY codigo_producto DESC LIMIT 1";
           $result=$this->db->query($sql); 
           if($result->num_rows()>0) $result=$result->row()->codigo_producto; else $result=0;
           return $result;
        }
        
        
        function beneficioProducto($pvp,$precioCompra,$iva){
            if (!$precioCompra) return 0;
            $beneficio=(100*$pvp-$precioCompra*(100+$iva))/$pvp*100;
            return $beneficio;
        }
        
       
        function getMargenRealProducto($id_pe_producto){
            $sql="SELECT precio_ultimo_unidad as unidad, "
                . "precio_ultimo_peso as kg, "
                . "descuento_1_compra as dto, "
                . "peso_real/1000 as peso, "
                . "tarifa_venta_unidad as bruto_unidad, "
                . "tarifa_venta_peso as bruto_peso, "
                . "valor_iva as iva "
                . "FROM pe_productos p "
                . " LEFT JOIN pe_grupos gr ON p.id_grupo=gr.id_grupo "
                . " LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva"
                . " WHERE p.id='$id_pe_producto'";
        $query=$this->db->query($sql);
        $unidad=$query->row()->unidad;
        $kg=$query->row()->kg;
        $dto=$query->row()->dto;
        $iva=$query->row()->iva;
        $peso=$query->row()->peso;
        if($unidad) $precio=$unidad; else $precio=$kg;
     
        $precio=$precio-$precio*$dto/100000;
       
        $bruto_unidad=$query->row()->bruto_unidad;
        $bruto_peso=$query->row()->bruto_peso;
        
        if($unidad) $pvp=$bruto_unidad; else $pvp=$bruto_peso;
        $pvp=$pvp/1000;
        $precio=$precio/1000;
        
        if($pvp) $margen_real_producto=(100*$pvp-$precio*(100+$iva))/$pvp;
        else $margen_real_producto="---";
        
            return $margen_real_producto*1000; //$margen_real_producto*1000;
        }
        
        function calculoMargenRealProducto($id_pe_producto){
            $sql="SELECT precio_compra, precio_ultimo_unidad as unidad, "
                . "precio_ultimo_peso as kg, "
                . "descuento_1_compra as dto, "
                . "peso_real/1000 as peso, "
                . "tarifa_venta_unidad as bruto_unidad, "
                . "tarifa_venta_peso as bruto_peso, "
                . "tarifa_venta, "
                . "valor_iva as iva "
                . "FROM pe_productos p "
                . " LEFT JOIN pe_grupos gr ON p.id_grupo=gr.id_grupo "
                . " LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva"
                . " WHERE p.id='$id_pe_producto'";
        $query=$this->db->query($sql);
        $unidad=$query->row()->unidad;
        $kg=$query->row()->kg;
        $dto=$query->row()->dto;
        $iva=$query->row()->iva;
        $peso=$query->row()->peso;

        if($unidad) $precio=$unidad; else $precio=$kg;
        $precio=$query->row()->precio_compra;    
        $precio=$precio-$precio*$dto/100000;
       
        $bruto_unidad=$query->row()->bruto_unidad;
        $bruto_peso=$query->row()->bruto_peso;
        
        if($unidad) $pvp=$bruto_unidad; else $pvp=$bruto_peso;
        $pvp=$query->row()->tarifa_venta;
        $pvp=$pvp/1000;
        $precio=$precio/1000;
        
        if($pvp) $margen_real_producto=(100*$pvp-$precio*(100+$iva))/$pvp;
        else $margen_real_producto="---";
        
            return $margen_real_producto*1000; //$margen_real_producto*1000;
        }
        
        function precioCompraFinal($id_pe_producto){
        
        $sql="SELECT precio_ultimo_unidad as unidad, "
                . "precio_ultimo_peso as kg, "
                . "descuento_1_compra as dto "
                . "FROM pe_productos p "
                . " WHERE p.id='$id_pe_producto'";
        $query=$this->db->query($sql);
        $unidad=$query->row()->unidad;
        $kg=$query->row()->kg;
        $dto=$query->row()->dto;
        if($unidad) $precio=$unidad; else $precio=$kg;
       // echo $precio. '   ';
        $precio=$precio-$precio*$dto/100000;
        //echo $dto. '   ';
        //echo $precio. '<br>   ';
        
        return $precio;
    }
    
    function setMargenPrecioCompraTarifaVenta($id_pe_producto){
           $margen_real_producto=$this->productos_->getMargenRealProducto($id_pe_producto);
            $precio_compra=$this->productos_->precioCompraFinal($id_pe_producto);
            $tarifa_venta=$this->productos_->tarifaVentaFinal($id_pe_producto);
            $sql="UPDATE pe_productos SET "
                    . " precio_compra='".$precio_compra."', "
                    . " margen_real_producto='".$margen_real_producto."', "
                    . " tarifa_venta='".$tarifa_venta."' WHERE id='".$id_pe_producto."'";
            $query=$this->db->query($sql);
    }
    
    
    function precioTransformacionFinal($id_pe_producto){
        
        $sql="SELECT precio_transformacion_unidad as unidad, "
                . "precio_transformacion_peso as kg "
               
                . "FROM pe_productos p "
                . " WHERE p.id='$id_pe_producto'";
        $query=$this->db->query($sql);
        $unidad=$query->row()->unidad;
        $kg=$query->row()->kg;
        
        if($unidad) $precio=$unidad; else $precio=$kg;
        return $precio;
    }
    
    function tarifaVentaFinal($id_pe_producto){
        $sql="SELECT tarifa_venta_unidad as unidad, "
                . "tarifa_venta_peso as kg "
               
                . "FROM pe_productos p "
                . " WHERE p.id='$id_pe_producto'";
        $query=$this->db->query($sql);
        $unidad=$query->row()->unidad;
        $kg=$query->row()->kg;
        
        if($unidad) $tarifa=$unidad; else $tarifa=$kg;
        return $tarifa;
    }
        
        function margen_real_producto($id_pe_producto){
        
        $sql="SELECT precio_ultimo_unidad as unidad, "
                . "precio_ultimo_peso as kg, "
                . "descuento_1_compra as dto, "
                . "precio_compra as precio, "
                . "peso_real/1000 as peso, "
                . "tarifa_venta_unidad as bruto_unidad, "
                . "tarifa_venta_peso as bruto_peso, "
                . "tarifa_venta, "
                . "valor_iva as iva "
                . "FROM pe_productos p "
                . " LEFT JOIN pe_grupos gr ON p.id_grupo=gr.id_grupo "
                . " LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva"
                . " WHERE p.id='$id_pe_producto'";
        $query=$this->db->query($sql);
        
        $unidad=$query->row()->unidad;
        $kg=$query->row()->kg;
        $dto=$query->row()->dto;
        $peso=$query->row()->peso;
        if($unidad) $precio=$unidad; else $precio=$kg;
        
        $precio=$query->row()->precio;
        $iva=$query->row()->iva;
     
        //$precio=$precio-$precio*$dto/100000;
       
        $bruto_unidad=$query->row()->bruto_unidad;
        $bruto_peso=$query->row()->bruto_peso;
        
        if($unidad) $pvp=$bruto_unidad; else $pvp=$bruto_peso;
        $pvp=$query->row()->tarifa_venta;
        $margen_real_producto=$this->margen($pvp/1000,$precio/1000,0,$iva); //(100*$pvp-$precio*(100+$iva))/$pvp;  //se utiliza como precio precio_compra que ya incluye el descuento
       
        return $margen_real_producto;
    }
        
        
        function precioCompra($pvp,$beneficio,$iva){
            $precioC=(100-$beneficio)*$pvp/(100+$iva);
            return $precioC*1000000;
        }
        
       public function actualizarProductosMercado(){
           $sql="SELECT codigo_producto FROM pe_productos_mercado WHERE LEFT(codigo_producto,2)!='EM'";
           $result=$this->db->query($sql);
           foreach($result->result() as $k=>$v){
               $sql="SELECT codigo_producto, 
                   nombre_web,
                   valor_iva, 
                   precio_ultimo_unidad, 
                   precio_ultimo_peso,peso_real, 
                   tarifa_venta_unidad,tarifa_venta_peso, 
                   descuento_1_compra, nombre_web  
                   FROM pe_productos p 
                LEFT JOIN pe_grupos g on p.id_grupo=g.id_grupo 
                LEFT JOIN pe_ivas i on g.id_iva=i.id_iva
                WHERE codigo_producto='$v->codigo_producto'
                ";
                $result=$this->db->query($sql)->row();
                $iva=$result->valor_iva;
                if ($result->tarifa_venta_unidad){
                    //se trata de un producto por unidades
                    $pvp0=$result->tarifa_venta_unidad;
                    $tipoPrecio=1;
                    $precio_compra0=$result->precio_ultimo_unidad-$result->precio_ultimo_unidad*$result->descuento_1_compra/10000;
                }else {
                    //se trata de un producto por peso
                    $pvp0=$result->tarifa_venta_peso * $result->peso_real/1000;
                    $precio_compra0=$result->precio_ultimo_peso * $result->peso_real/1000;
                    $precio_compra0=$precio_compra0-$precio_compra0*$result->descuento_1_compra/10000;
                    $tipoPrecio=0;
                }
                $beneficio0=$this->beneficioProducto($pvp0/100, $precio_compra0/1000000, $iva);
                
                $sql1="UPDATE pe_productos_mercado SET 
                        iva='$result->valor_iva', 
                        tipo_precio='$tipoPrecio',    
                        nombre='$result->nombre_web',
                        tarifa_venta_peso0= '$result->tarifa_venta_peso',
                        tarifa_venta_unidad0= '$result->tarifa_venta_unidad', 
                        precio_ultimo_peso0= '$result->precio_ultimo_peso', 
                        precio_ultimo_unidad0= '$result->precio_ultimo_unidad', 
                        pvp0='$pvp0',
                        precio_compra0='$precio_compra0',  
                        beneficio0= '$beneficio0'   
                        WHERE codigo_producto='$result->codigo_producto'";
                    $this->db->query($sql1);
           }
           
        }
        
        public function getCodigoEan(){
            $id_producto=$_POST['codigoBascula'];
            $sql="SELECT codigo_ean,id_grupo,id_familia FROM pe_productos WHERE id_producto='$id_producto' GROUP BY id_producto";
            $this->db->query($sql)->num_rows();
            if($this->db->query($sql)->num_rows()){
                $codigoEan=$this->db->query($sql)->row()->codigo_ean;
                $id_grupo=$this->db->query($sql)->row()->id_grupo;
                $id_familia=$this->db->query($sql)->row()->id_familia;
            }
            else {$codigoEan="";
            $id_grupo="";
            $id_familia="";
            }
            return array('codigoEan'=>$codigoEan,
                    'id_grupo'=>$id_grupo,
                    'id_familia'=>$id_familia
                    );
        }
                 
       public function actualizarProductosMercado2(){
            $sql="SELECT codigo_producto, valor_iva, precio_ultimo_unidad, precio_ultimo_peso,peso_real, tarifa_venta_unidad,tarifa_venta_peso, descuento_1_compra, nombre_web  FROM pe_productos p 
                LEFT JOIN pe_grupos g on p.id_grupo=g.id_grupo 
                LEFT JOIN pe_ivas i on g.id_iva=i.id_iva";
            $result=$this->db->query($sql);
            
            foreach($result->result() as $k=>$v){
                //echo $v->codigo_producto.'<br />';
               
                if($v->codigo_producto){
                 $iva=$v->valor_iva;   
                 $codigo_producto=$v->codigo_producto;
                $sql="SELECT codigo_producto FROM pe_productos_mercado WHERE codigo_producto='$codigo_producto'";
                if($this->db->query($sql)->num_rows()){
                    $sql1="UPDATE pe_productos_mercado SET iva='$v->valor_iva' WHERE codigo_producto='$codigo_producto'";
                    $this->db->query($sql1);
                }else{
                    $sql1="INSERT INTO pe_productos_mercado SET codigo_producto='$v->codigo_producto', iva='$iva'";
                    $this->db->query($sql1);
                }
                if ($v->tarifa_venta_unidad){
                    //se trata de un producto por unidades
                    $pvp0=$v->tarifa_venta_unidad;
                    $tipoPrecio=1;
                    $precio_compra0=$v->precio_ultimo_unidad-$v->precio_ultimo_unidad*$v->descuento_1_compra/10000;
                }else {
                    //se trata de un producto por peso
                    $pvp0=$v->tarifa_venta_peso * $v->peso_real/1000;
                    $precio_compra0=$v->precio_ultimo_peso * $v->peso_real/1000;
                    $precio_compra0=$precio_compra0-$precio_compra0*$v->descuento_1_compra/10000;
                    $tipoPrecio=0;
                }
                
                
                
                
                
                
                //echo $pvp0.' '.$precio_compra0.' '.$iva;
                
                $beneficio0=$this->beneficioProducto($pvp0/100, $precio_compra0/1000000, $iva);
                //echo $beneficio0.'<br />';
                $sql2="UPDATE pe_productos_mercado SET tipo_precio='$tipoPrecio', nombre='$v->nombre_web' , pvp0='$pvp0', precio_compra0='$precio_compra0', beneficio0='$beneficio0' WHERE codigo_producto='$codigo_producto'";
                $this->db->query($sql2);
                
                
                }
            }
            
            $sql="SELECT codigo_producto, 
                    tarifa_venta_unidad1,tarifa_venta_peso1,
                    tarifa_venta_unidad2,tarifa_venta_peso2,
                    tarifa_venta_unidad3,tarifa_venta_peso3,
                    peso_real,iva FROM pe_productos_mercado";
            $result=$this->db->query($sql);
            
            foreach($result->result() as $k=>$v){
                $codigo_producto=$v->codigo_producto;
                $iva=$v->iva;
               
                if($codigo_producto){
                if ($v->tarifa_venta_unidad1){
                    //se trata de un producto por unidades
                    $pvp1=$v->tarifa_venta_unidad1;
                    $precio_compra1=$this->precioCompra($pvp1/100,30,$iva);
                }else {
                    //se trata de un producto por peso
                    $pvp1=$v->tarifa_venta_peso1 * $v->peso_real/1000;
                    $precio_compra1=$this->precioCompra($pvp1/100,30,$iva);
                }
                if ($v->tarifa_venta_unidad2){
                    //se trata de un producto por unidades
                    $pvp2=$v->tarifa_venta_unidad2;
                    $precio_compra2=$this->precioCompra($pvp2/100,30,$iva);
                }else {
                    //se trata de un producto por peso
                    $pvp2=$v->tarifa_venta_peso2 * $v->peso_real/1000;
                    $precio_compra2=$this->precioCompra($pvp2/100,30,$iva);
                }
                if ($v->tarifa_venta_unidad3){
                    //se trata de un producto por unidades
                    $pvp3=$v->tarifa_venta_unidad3;
                    $precio_compra3=$this->precioCompra($pvp3/100,30,$iva);
                }else {
                    //se trata de un producto por peso
                    $pvp3=$v->tarifa_venta_peso3 * $v->peso_real/1000;
                    $precio_compra3=$this->precioCompra($pvp3/100,30,$iva);
                }
                
                $sql2="UPDATE pe_productos_mercado SET pvp1='$pvp1',  precio_compra1='$precio_compra1',pvp2='$pvp2',  precio_compra2='$precio_compra2',pvp3='$pvp3',  precio_compra3='$precio_compra3' WHERE codigo_producto='$codigo_producto'";
                $this->db->query($sql2);
                }
            }
            
            
            
        }  
        
       public function setTipoUnidad($id_pe_producto){
           $tipoUnidad=$this->calculoUnidad($id_pe_producto);
           return $this->db->query("UPDATE pe_productos SET tipo_unidad='$tipoUnidad' WHERE id='$id_pe_producto'");
       } 
        
       public function setPrecioCompra($id_pe_producto) {
           $codigo_producto=$this->getCodigoProducto($id_pe_producto);
           $precioCompra=$this->calculoPrecioCompraFinal($codigo_producto);
           return $this->db->query("UPDATE pe_productos SET precio_compra='$precioCompra' WHERE id='$id_pe_producto'");
       }
       public function setTarifaVenta($id_pe_producto){
           $tarifaVenta=$this->calculoTarifaVenta($id_pe_producto);
           $tarifaVenta*=1000;
           return $this->db->query("UPDATE pe_productos SET tarifa_venta='$tarifaVenta' WHERE id='$id_pe_producto'");
       }
       
       public function setPrecioCompraPack($id_pe_producto,$valor) {
           //$codigo_producto=$this->getCodigoProducto($id_pe_producto);
           //$precioCompra=$this->calculoPrecioCompraFinal($codigo_producto);
           return $this->db->query("UPDATE pe_productos SET tipo_unidad='Und', precio_ultimo_peso='',precio_ultimo_unidad='$valor',precio_compra='$valor' WHERE id='$id_pe_producto'");
       }
       public function setTarifaVentaPack($id_pe_producto_pack,$totalTarifa_ventaPack,$margenPack){
           //$tarifaVenta=$this->calculoTarifaVenta($id_pe_producto);
           //$tarifaVenta*=1000;
           //log_message('INFO', '$id_pe_producto_pack '.$id_pe_producto_pack);
           //log_message('INFO', '$totalTarifa_ventaPack '.$totalTarifa_ventaPack);
           //log_message('INFO', '$margenPack '.$margenPack);
           
           $r=$this->db->query("UPDATE pe_productos SET tarifa_venta_unidad='$totalTarifa_ventaPack', tarifa_venta='$totalTarifa_ventaPack' WHERE id='$id_pe_producto_pack'");
           
           
           $row=$this->db->query("SELECT descuento_profesionales,descuento_profesionales_vip FROM pe_productos WHERE id='$id_pe_producto_pack'")->row();
           $descuento_profesionales=$row->descuento_profesionales/1000;
           $descuento_profesionales_vip=$row->descuento_profesionales_vip/1000;
           //log_message('INFO', '$descuento_profesionales '.$descuento_profesionales);
           //log_message('INFO', '$descuento_profesionales_vip '.$descuento_profesionales_vip);
           
           $tarifa_profesionales=intval(round($totalTarifa_ventaPack-$totalTarifa_ventaPack*$descuento_profesionales/100));
           $tarifa_profesionales_vip=intval(round($totalTarifa_ventaPack-$totalTarifa_ventaPack*$descuento_profesionales_vip/100));
           //log_message('INFO', '$tarifa_profesionales '.$tarifa_profesionales);
           //log_message('INFO', '$tarifa_profesionales_vip '.$tarifa_profesionales_vip);
           
           $this->db->query("UPDATE pe_productos SET tarifa_profesionales='$tarifa_profesionales', tarifa_profesionales_vip='$tarifa_profesionales_vip' WHERE id='$id_pe_producto_pack'");
           //margen_profesionales=(margenPack(%)-$descuento_profesionales(%))/(100-$descuento_profesionales
           $margen_venta_profesionales=($margenPack-$descuento_profesionales*1000)/(1000000-$descuento_profesionales*1000)*1000000;
           $margen_venta_profesionales_vip=($margenPack-$descuento_profesionales_vip*1000)/(1000000-$descuento_profesionales_vip*1000)*1000000;
           
           //log_message('INFO', '//////////////////////////////////////////////////');
           //log_message('INFO', '$margenPack '.$margenPack);
           //log_message('INFO', '$descuento_profesionales '.$descuento_profesionales*1000);
           //log_message('INFO', '$margen_venta_profesiones '.$margen_venta_profesionales);
           //log_message('INFO', '//////////////////////////////////////////////////');
           
           $sql="UPDATE pe_productos SET margen_real_producto='$margenPack', margen_venta_profesionales ='$margen_venta_profesionales',margen_venta_profesionales_vip ='$margen_venta_profesionales_vip' WHERE id='$id_pe_producto_pack'";
           $r=$this->db->query($sql);
           return $r;
           
       }
       
        public function setTarifaVentaPackProfesionales($id_pe_producto,$valor){
            $sql="UPDATE pe_productos SET tarifa_profesionales ='$valor' WHERE id='$id_pe_producto'";
            $r=$this->db->query($sql);
            //log_message('INFO',$sql);
            return $r.' '.$sql;
        }
       
       
       /*
       public function setMargenesPack($id_pe_producto_pack,$margenPack){
           $tarifa=0;
           $tarifaSinIva=0;
           $precio=0;
           $tarifaSinIvaPack=0;
           $tarifaPack=0;
           foreach($codigos as $k=>$v){
               $codigo_producto=$v;
                $sql="SELECT precio_compra as precio, "
                . "descuento_profesionales, "
                . "descuento_profesionales_vip, "
                . "tarifa_venta_unidad as tarifa, "
                . "valor_iva as iva "
                . "FROM pe_productos p "
                . " LEFT JOIN pe_grupos gr ON p.id_grupo=gr.id_grupo "
                . " LEFT JOIN pe_ivas i ON gr.id_iva=i.id_iva"
                . " WHERE p.codigo_producto='$codigo_producto'";
            $row=$this->db->query($sql)->row();
            $tarifa_venta=$row->tarifa; 
            $tarifa_venta_pack=$tarifa_venta-$tarifa_venta*$descuentos[$k]/100;
            $iva=$row->iva/100;
            $tarifa+=$tarifa_venta; 
            $tarifaPack+=$tarifa_venta_pack;
            $tarifaSinIva+=$tarifa_venta/(1+$iva);
            $tarifaSinIvaPack+=$tarifa_venta_pack/(1+$iva);
            $precio+=$row->precio;
            $descuentoProfesionales=$row->descuento_profesionales;
            $descuentoProfesionalesVip=$row->descuento_profesionales_vip;
           }
           $margenTienda=($tarifaSinIva-$precio)*100000/$tarifaSinIva;
           $margenPack=($tarifaSinIvaPack-$precio)*100000/$tarifaSinIvaPack;
           $tarifaProfesionales=$tarifaPack-$tarifaPack*$descuentoProfesionales/100000;
           $tarifaProfesionalesVip=$tarifaPack-$tarifaPack*$descuentoProfesionalesVip/100000;
          
           $tarifaProfesionalesSinIvaPack=$tarifaSinIvaPack-$tarifaSinIvaPack*$descuentoProfesionales/100000;;
           $tarifaProfesionalesVipSinIvaPack=$tarifaSinIvaPack-$tarifaSinIvaPack*$descuentoProfesionalesVip/100000;;
           
           $margenVentaProfesionales=($tarifaProfesionalesSinIvaPack-$precio)*100000/$tarifaProfesionalesSinIvaPack;
           $margenVentaProfesionalesVip=($tarifaProfesionalesVipSinIvaPack-$precio)*100000/$tarifaProfesionalesVipSinIvaPack;

           //log_message('INFO','$descuentoProfesionales '.$descuentoProfesionales);
           //log_message('INFO','$descuentoProfesionalesVip '.$descuentoProfesionalesVip);
           //log_message('INFO','$tarifa '.$tarifa);
           //log_message('INFO','$tarifaProfesionales '.$tarifaProfesionales);
           //log_message('INFO','$tarifaProfesionalesVip '.$tarifaProfesionalesVip);
           $this->db->query("UPDATE pe_packs SET pvp_pack='$tarifaPack', pvp_tienda='$tarifa', margen_pack='$margenPack', margen_tienda='$margenTienda' WHERE id_pe_producto_pack='$id_pe_producto_pack'");
           return $this->db->query("UPDATE pe_productos SET margen_venta_profesionales_vip='$margenVentaProfesionalesVip',margen_venta_profesionales='$margenVentaProfesionales',tarifa_profesionales_vip='$tarifaProfesionalesVip',tarifa_profesionales='$tarifaProfesionales',margen_real_producto='$margenPack' WHERE id='$id_pe_producto_pack'");
       }
       */
       
       public function setMargenRealProducto($id_pe_producto){
           $margenRealProducto=$this->calculoMargenRealProducto($id_pe_producto);
           return $this->db->query("UPDATE pe_productos SET margen_real_producto='$margenRealProducto' WHERE id='$id_pe_producto'");
       }
       public function setMargenTienda($id_pe_producto){
           $margenTienda=$this->calculoMargenTienda($id_pe_producto);
           //log_message('INFO', $id_pe_producto.' '.$margenTienda);
           //log_message('INFO', $id_pe_producto.' '."UPDATE pe_embalajes SET margen_tienda='$margenTienda' WHERE codigo_producto='$id_pe_producto'");
           return $this->db->query("UPDATE pe_embalajes SET margen_tienda='$margenTienda' WHERE codigo_producto='$id_pe_producto'");
       }
       public function setMargenOnline($id_pe_producto){
           $margenOnline=$this->calculoMargenOnline($id_pe_producto);
           return $this->db->query("UPDATE pe_embalajes SET margen_online='$margenOnline' WHERE codigo_producto='$id_pe_producto'");
       }
       
       public function setPrecioEmbalajeTienda($id_pe_producto){
           $precioEmbalajeTienda=$this->calculoPrecioEmbalajeTienda($id_pe_producto);
           return $this->db->query("UPDATE pe_embalajes SET precio_embalaje_tienda='$precioEmbalajeTienda' WHERE codigo_producto='$id_pe_producto'");
       }
       public function setPrecioEmbalajeOnline($id_pe_producto){
           $precioEmbalajeOnline=$this->calculoPrecioEmbalajeOnline($id_pe_producto);
           return $this->db->query("UPDATE pe_embalajes SET precio_embalaje_online='$precioEmbalajeOnline' WHERE codigo_producto='$id_pe_producto'");
       }
               
}
        
