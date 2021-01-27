<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    exit("<h2>No está permitido el acceso directo a esta URL</h2>");

class Pruebas extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->database();
        $this->db2 = $this->load->database('pernil181bcn', TRUE);
        $this->load->model('productos_');
        $this->load->model('stocks_model');

        //set_time_limit ( 0 ) ;
        ini_set("memory_limit","100024M");
    }

    function getRasa($bonu="",$db="",$zeis=""){
          if($db=="") $db=1;
          $base=$db==1?$this->db:$this->db2;
          if(!$bonu) return 0;
          if($zeis=="" || substr($zeis,4)=="1970"){
            //   No se aporta zeis o es no válida
            $num=$base->query("SELECT count(*) FROM pe_boka WHERE bonu='$bonu'")->num_rows();
            if($num!=1) {
                return array();
                } else{
                    $row=$base->query("SELECT * FROM pe_boka WHERE bonu='$bonu' LIMIT 1")->row();
                    if($row->STYP==1) return array('rasa'=>$row->RASA,'zeis'=>$row->ZEIS,pe_tickets=>$row->BT20);
                    else{
                        $max=0;
                        do{
                            $id=$row->id;
                            $id--;
                            $max++;
                            if($max>500) return array();
                            $row=$base->query("SELECT * FROM pe_boka WHERE  id='$id'")->row();              
                        } while($row->STYP != 1);
                        return array('rasa'=>$row->RASA,'zeis'=>$row->ZEIS,'bt20'=>$row->BT20);
                    }
                }
            }
            else{
                $num=$base->query("SELECT count(*) FROM pe_boka WHERE bonu='$bonu' and zeis='$zeis'")->num_rows();
                if($num!=1) {
                    return array();
                    } else{
                        $row=$base->query("SELECT * FROM pe_boka WHERE bonu='$bonu' and zeis='$zeis' LIMIT 1")->row();
                        echo "SELECT * FROM pe_boka WHERE bonu='$bonu' and zeis='$zeis' LIMIT 1".'<br>';
                        if($row->STYP==1) return array('rasa'=>$row->RASA,'zeis'=>$row->ZEIS,'bt20'=>$row->BT20);
                        else{
                            $max=0;
                            do{
                                $id=$row->id;
                                $id--;
                                $max++;
                                $max++;
                                if($max>500) return array();
                                $row=$base->query("SELECT * FROM pe_boka WHERE  id='$id'")->row();
                                echo "SELECT * FROM pe_boka WHERE   id='$id'".'<br>';
                            } while($row->STYP != 1);
                            return array('rasa'=>$row->RASA,'zeis'=>$row->ZEIS,'bt20'=>$row->BT20);
                        }
                    }
                }
            }
        

    

    function crearRegistrosVentas2(){


        $sql="SELECT * FROM pe_tickets WHERE fecha>='2019-03' and fecha<='2019-04'";
        $pernil181=$this->db->query($sql)->result();
        foreach($pernil181 as $k=>$v){
            $sql="SELECT * FROM pe_tickets WHERE fecha='".$v->fecha."' AND num_ticket='".$v->num_ticket."'";
            $pernil181bcn=$this->db2->query($sql)->row();
            if($pernil181bcn->total!=$v->total){
                echo 'conversion '.$v->num_ticket.' '.$v->fecha.'<br>';
            }
        }

        return;

        $sql="SELECT id, bonu, styp,rasa,zeis,id_pe_producto FROM `pe_boka` WHERE zeis like '%1970-%'";
        $pernil181bcn=$this->db2->query($sql)->result();
        foreach($pernil181bcn as $k=>$v){
            
            echo implode(", ",$this->getRasa($v->bonu,2,$v->zeis)).'<br>';
        }


        return;

        $sql="SELECT id, bonu, styp,rasa,zeis,id_pe_producto FROM `pe_boka` WHERE zeis like '%1970-%'";
        $pernil181bcn=$this->db2->query($sql)->result();
        foreach($pernil181bcn as $k=>$v){
            echo $k.' En pernil181bcn    id='.$v->id.' bonu='.$v->bonu.' styp='.$v->styp.' id_pe_producto='.$v->id_pe_producto.' rasa='.$v->rasa.' zeis='.$v->zeis.'<br>';
            if($v->styp==2 && $v->id_pe_producto==0) echo '+++++++++++++++++++++++++++++++++++FALTA INDICAR PRODUCTO '.'<br>';
            $bonu=$v->bonu;
            $id=$v->id;
            $sql="SELECT id, bonu, styp,zeis,rasa FROM `pe_boka` WHERE bonu='$bonu' and styp=1";
            $numTicketsPosibles=$this->db->query($sql)->num_rows();
            echo 'Núm de tickets posibles: '.$numTicketsPosibles.'<br>';
            if($numTicketsPosibles==0) return;
            $pernil181=$this->db->query($sql)->result();
            $distanciaMenor=100000;
            $zeis="";
            $rasa="";
            foreach($pernil181 as $k1=>$v1){
                    echo '------- posibles zeis: '.$v1->id,' '.$v1->zeis.'<br>';
                    if(abs($v1->id-$id)<$distanciaMenor){
                        $zeis=$v1->zeis;
                        $rasa=$v1->rasa;
                        $distanciaMenor=abs($v1->id-$id);
                    }
            }
            echo '===== seleccionada '.$zeis.'<br>';
            echo '===== TICKET '.$rasa.'<br>';

            $sql="UPDATE pe_boka SET zeis='$zeis' WHERE id='".$v->id."'";
            echo $sql.'<br>';

        }

        return;

        foreach($pernil181bcn as $k=>$v){
            $bonu=$v->bonu;
            $id=$v->id;
            $sql="SELECT id, bonu, styp,zeis FROM `pe_boka` WHERE bonu='$bonu'";
            $pernil181=$this->db->query($sql)->result();
            $distanciaMenor=100000;
            $zeis="";
            foreach($pernil181 as $k1=>$v1){
                if(abs($v1->id-$id)<$distanciaMenor){
                    $zeis=$v1->zeis;
                    $distanciaMenor=abs($v1->id-$id);
                    echo 'distancia ='.$distanciaMenor.'<br>';
                }
            }
            if($zeis!=""){
                echo 'En pernil181bcn    id='.$v->id.' bonu='.$v->bonu.' styp='.$v->styp.' rasa='.$v->rasa.' zeis='.$v->zeis.'<br>';
                if($v->styp==1){
                    echo 'Núm ticket :'.$v->rasa .'==================<br>';
                }else{
                    $sql="SELECT rasa FROM pe_boka WHERE bonu='".$v->bonu."' and styp=1 and zeis='$zeis'";
                    if($this->db2->query($sql)->num_rows()==1)
                        echo 'Núm ticket :'.$this->db2->query($sql)->row()->rasa.'<br>';
                    else
                        echo 'Núm ticket NO ENCONTRADO<br>';

                }
                echo 'En pernil181 '.$zeis.'<br>';
            }else{
                echo 'En pernil181bcn    id='.$v->id.' bonu='.$v->bonu.' styp='.$v->styp.' rasa='.$v->rasa.' zeis='.$v->zeis.'<br>';
                echo 'En pernil181 NO ENCONTRADO'.'<br>';
            }
            echo '------------------------------<br>';
        }

        return;



        $sql="SELECT id, bonu, styp,zeis FROM `pe_boka` WHERE styp=1 and id>=600000 ";
        $pernil181=$this->db->query($sql)->result();
        foreach($pernil181 as $k=>$v){
            // echo 'En pernil181    '.$v->id.' '.$v->bonu.' '.$v->styp.' '.$v->zeis.'<br>';

            $sql="SELECT id, bonu, styp,zeis FROM `pe_boka` WHERE styp=1 and bonu='".$v->bonu."' and zeis='".$v->zeis."'";
            // echo $this->db2->query($sql)->num_rows();
            if($this->db2->query($sql)->num_rows()==0){
                echo 'En pernil181    '.$v->id.' '.$v->bonu.' '.$v->styp.' '.$v->zeis.'<br>';
                echo 'En pernil181bcn  NO EXISTE<br>';
                echo '------------------------------<br>';
            
            }else{
                $pernil181bcn=$this->db2->query($sql)->row();
                if(($v->id)!=($pernil181bcn->id)){
                    echo 'En pernil181    '.$v->id.' '.$v->bonu.' '.$v->styp.' '.$v->zeis.'<br>';
                    echo 'En pernil181bcn '.$pernil181bcn->id.' '.$pernil181bcn->bonu.' '.$pernil181bcn->styp.' '.$pernil181bcn->zeis.'<br>';
                    echo '------------------------------<br>';
                }
            }
    
        }

        return;

        //numeros NO correlativos
        $sql="SELECT id, bonu, styp,zeis FROM `pe_boka` WHERE id>=610381 ";
        $pernil181=$this->db2->query($sql)->result_array();
        $idAnterior=0;
        foreach($pernil181 as $k=>$v){
            if($v['id']!=$idAnterior+1){
                echo $v['id'].' '.$v['bonu'].' '.$v['styp'].' '.$v['zeis'].'<br>';
                $idAnterior=$v['id'];
            }else{
                $idAnterior++;
            }

        }
        return;

        $sql="SELECT id, bonu, styp,zeis FROM `pe_boka` WHERE id>=610381 ";
        $pernil181=$this->db->query($sql)->result_array();
        $pernil181bcn=$this->db2->query($sql)->result_array();
        foreach($pernil181 as $k=>$v){
            if($v['bonu']!=$pernil181bcn[$k]['bonu']){
                echo $v['id'].' '.$v['bonu'].' '.$v['styp'].' '.$v['zeis'].'<br>';
                echo $pernil181bcn[$k]['id'].' '.$pernil181bcn[$k]['bonu'].' '.$pernil181bcn[$k]['styp'].' '.$pernil181bcn[$k]['zeis'].'<br>';
                echo '------------------------------<br>';
            }
        }


        return

        $sql="SELECT id FROM pe_boka WHERE zeis like '%1970-%' ";
        $pernil181bcn=$this->db2->query($sql)->result_array();
        foreach($pernil181bcn as $k=>$v){
            $id=$v['id'];
            $row=$this->db->query("SELECT * FROM pe_boka WHERE id='$id'")->row_array();
            $sets=array();
            foreach($row as $k1=>$v1){
                // echo $k1.'<br>';
                // echo $v1.'<br>';
                // echo " $k1='".$v1."'".'<br>';
                $sets[]=" $k1='".$v1."'";
            }
            $set=implode(", ", $sets);
            $sql="UPDATE pe_boka SET $set WHERE id='$id'";
            echo $sql;
            $this->db2->query($sql);
        }

        return;


        $sql="SELECT id, zeis FROM pe_boka";
        $pernil181=$this->db->query($sql)->result_array();
        $pernil181bcn=$this->db->query($sql)->result_array();
        foreach($pernil181 as $k=>$v){
            echo $k.' '.$v['id'].' '.$pernil181bcn[$k]['id'].'<br>';
            if($v['id']!=$pernil181bcn[$k]['id']) break;
            
        }
        return;
        // $inicio=472056;
        // $final=480000;
        
        // $sql="SELECT RASA, ZEIS, BT20 FROM pe_boka WHERE STYP=1 AND id>=$inicio AND id<=979282";
        // $result1=$this->db->query($sql)->result();
        // $result2=$this->db2->query($sql)->result_array();
        // foreach($result1 as $k=>$v){
        //     if(array_key_exists($k, $result2) && ($v->BT20 != $result2[$k]['BT20'])) {
        //     echo $v->RASA.' '.$v->ZEIS.' '.$v->BT20 .'<br>';
        //     echo $result2[$k]['RASA'].' '.$result2[$k]['ZEIS'].' '.$result2[$k]['BT20'] .'<br>';
        //     echo '________Diferencia  '.($v->BT20-$result2[$k]['BT20']).'<br>';
        //     }
        // }


        // $sql="SELECT * FROM pe_boka where id>=610382 and zeis='1970-01-01 00:00:00'";
        // $result=$this->db2->query($sql)->result();
        // foreach($result as $k=>$v){
        //     $id=$v->id;
        //     $sql="SELECT * FROM pe_boka WHERE id='$id'";
        //     $row=$this->db->query($sql)->row_array();
        //     $sets=array();
        //     foreach($row as $k=>$v){
        //         // if($k=='TEXT') continue;
        //         $sets[]=$k.'="'.$v.'"';
        //     }
        //     $set=implode(", ",$sets);
        //     $sql="UPDATE pe_boka SET ".$set." WHERE id='$id'";  
        //     echo $id.'  '.$sql.'<br>';
        //     $this->db2->query($sql);
        // }    

        // return;



        $inicio=472056;
        $final=480000;

        $inicio=479994;
        $final=479994+30000;

        $inicio=509993;
        $final=509993+30000;
        
        $inicio=539992;
        $final=539992+30000;

        $inicio=569983;
        $final=569983+30000;

        $inicio=599980;
        $final=599980+30000;

        $inicio=610382;
        $final=610399;
        
        $inicio=610399;
        $final=610399+30000;

        $ultimo=679291;

        // $this->db2->query("DELETE FROM pe_registro_ventas");
        $sql="SELECT * FROM pe_boka WHERE STYP=2 AND id>$inicio AND id<$final";
        $result=$this->db2->query($sql)->result();
        foreach($result as $k=>$v){
            $resultado=$this->productos_->registrarVentaTienda($v->id,2);
            echo $resultado.' '.$v->id.' '.$v->ZEIS.'<br>';
        }    

        
    }




    function pruebaUsoBD(){
        $tiempo_inicio = microtime(true);

        $sql="SELECT * FROM pe_boka WHERE 1 LIMIT 70000";
        $result=$this->db->query($sql)->result();

        foreach($result as $k=>$v){
            if($v->STYP==2){
                $id=$v->id_pe_producto;
                $sql="SELECT nombre from pe_productos WHERE id='$id'";
                if($this->db->query($sql)->num_rows()){
                    $nombre=$this->db->query($sql)->row()->nombre;
                    $this->db->query("INSERT INTO pe_prueba_bd SET id_pe_producto='$id', nombre='$nombre'");
                    
                }
                
                
            }
            
        }
        $tiempo_fin = microtime(true);
        
        $dato['fecha']='';
        $dato['tiempoEmpleado']=$tiempo_fin - $tiempo_inicio;
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('pruebaUsoBD', $dato);
        $this->load->view('templates/footer.html');
    }

    function pruebasA__() {

        $sql="SELECT * FROM pe_orders_prestashop WHERE shop_name!=''";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $customer_id=$v->customer_id;
            $delivery_firstname=$v->delivery_firstname;
            $delivery_lastname=$v->delivery_lastname;
            $customer_email=$v->customer_email;
            $customer_id_language=$v->customer_id_language;
            $shop_name=$v->shop_name;
            $delivery_country=$v->delivery_country;

            $set=" pe_clientes_jamonarium SET id='$customer_id', firstname='$delivery_firstname', lastname='$delivery_lastname', email='$customer_email', id_lang='$customer_id_language', shop_name='$shop_name', country='$delivery_country' ";
            if($this->db->query("SELECT id FROM pe_clientes_jamonarium WHERE id='$customer_id'")->num_rows()==1){
                $this->db->query("UPDATE ".$set." WHERE id='$customer_id'");
            }
            else {
                $this->db->query("INSERT INTO ".$set);
            }

        }

        $dato['fecha']='';
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('pruebaA', $dato);
        $this->load->view('templates/footer.html');
        return;







        ini_set('memory_limit','-1');
        $dato['no_resueltos']=array();
        $sql="SELECT * FROM pe_boka WHERE STYP=2 AND id_pe_producto='0'";
        $result=$this->db2->query($sql)->result();
        foreach($result as $k=>$v){
            //echo $v->id.' '.$v->SNR1.' '.$v->id_pe_producto.'<br>';
            if(!$v->id_pe_producto){
                //echo $v->id.' '.$v->SNR1.'<br>';
                $SNR1=$v->SNR1;
                $id=$v->id;
                $row=$this->db->query("SELECT * FROM pe_boka WHERE id='$id'")->row();
                if($row->SNR1==$SNR1){
                    $id_pe_producto=$row->id_pe_producto;
                    echo $row->id.' '.$row->SNR1.' '.$row->id_pe_producto.'<br>';
                    $this->db2->query("UPDATE pe_boka SET id_pe_producto='$id_pe_producto' WHERE id='$id'");
                }else{
                  //  $dato['no_resueltos'][]= $id.' '.$SNR1.' '.$row->SNR1.'<br>';
                  echo $id.' '.$SNR1.' '.$row->SNR1.'<br>';
                }

            }
        }
        $dato['saludo']="hola";
        /*
        $result=$this->db->query("SELECT * FROM pe_stocks_totales WHERE 1")->result();
        foreach($result as $k=>$v){
            $id=$v->id_pe_producto;
            $this->productos_->ponerStockValor($id);
        }
       */
      /*
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('pruebaA', $dato);
        $this->load->view('templates/footer.html');
        return;
        */
    }


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
            echo $idBoka.'<br>';
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


    function pruebasA(){
        
        $result=$this->db->query("SELECT id, codigo_producto, nombre, nombre_generico FROM pe_productos WHERE nombre like '%Guijuelo%' OR nombre_generico like '%Guijuelo%'")->result();
        foreach($result as $k=>$v){
            $id=$v->id;
            $nombre=$v->nombre;
            $nombre_generico=$v->nombre_generico;
            echo $v->codigo_producto.'<br>';
            echo $nombre.'<br>';
            echo $nombre_generico.'<br>';
            $nombre=str_replace("Salamanca","",$nombre);
            $nombre_generico=str_replace("Salamanca","",$nombre_generico);
            $nombre=str_replace("Guijuelo","Salamanca",$nombre);
            $nombre_generico=str_replace("Guijuelo","Salamanca",$nombre_generico);

            echo $nombre.'<br>';
            echo $nombre_generico.'<br>'.'<br>';

            $this->db->query("UPDATE pe_productos SET nombre='$nombre', nombre_generico='$nombre_generico' WHERE id='$id'");
        }
        echo 'FINALIZADO';
        return;
    }

    function pruebasB(){
        $sql="SELECT * FROM pe_orders_prestashop";
        $result=$this->db->query($sql)->result();

        $sql="DELETE FROM pe_tabla_orders_prestashop WHERE 1";
        $this->db->query($sql);

        foreach($result as $k=>$v){
            $id=$v->id;
            $customer_id_group=$v->customer_id_group;
            $customer_id=$v->customer_id;
            $tipo_iva_transporte=$v->tipo_iva_transporte;
            $descuento=$v->descuento/100;
            $total_base=$v->total_base/100;
            $total_iva=$v->total_iva/100;
            $total=$v->total/100;
            $total_producto=$v->total_producto/100;
            $base_transporte=$v->base_transporte/100;
            $iva_transporte=$v->iva_transporte/100;
            $transporte=$v->transporte/100;;
            $transporte_original_igual_descuento=$v->transporte_original_igual_descuento/100;;
            $total_pedido=$v->total_pedido/100;;
            $fecha=$v->fecha;

            $sql="INSERT INTO pe_tabla_orders_prestashop SET  
                id='$id',
                customer_id_group='$customer_id_group',
                customer_id='$customer_id',
                tipo_iva_transporte='$tipo_iva_transporte',
                total='$total',
                descuento='$descuento',
                total_base='$total_base',
                total_iva='$total_iva',
                total_producto='$total_producto',
                base_transporte='$base_transporte',
                iva_transporte='$iva_transporte',
                transporte='$transporte',
                transporte_original_igual_descuento='$transporte_original_igual_descuento',
                total_pedido='$total_pedido',
                fecha='$fecha'
                
            ";
            $this->db->query($sql);
        }

/*
        $sql="SELECT bonu,fecha FROM pe_tickets";
        $result=$this->db->query($sql)->result();
        foreach($result as $k=>$v){
            $bonu=$v->bonu;
            $zeis=$v->fecha;
             $sql="SELECT PAR1 FROM pe_boka WHERE bonu='$bonu' AND zeis='$zeis' AND STYP=8";
             $result=$this->db->query("$sql")->result();
             $id_forma_pago_ticket=20;
             foreach($result as $k1=>$v1){
                if($v1->PAR1!=20) $id_forma_pago_ticket=$v1->PAR1;
              }
              $this->db->query("UPDATE pe_tickets SET id_forma_pago_ticket='$id_forma_pago_ticket' WHERE bonu='$bonu' AND fecha='$zeis'"); 
            }
*/
$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaB',$dato);
$this->load->view('templates/footer.html');
}

function generarRegistrosVentas($id_order){
    
    $this->db->query("DELETE FROM pe_registro_ventas WHERE tipo_tienda=2 and num_ticket='$id_order'");

    $sql="SELECT total_producto,transporte,fecha FROM pe_orders_prestashop where id='$id_order'";
    $row=$this->db->query($sql)->row();
    $total_producto=$row->total_producto;
    $transporte=$row->transporte;
    $factorTransporte=$total_producto?$transporte/$total_producto:1;
    $fecha=$row->fecha;
    $sql="SELECT * FROM pe_lineas_orders_prestashop where id_order='$id_order'";
    $result=$this->db->query($sql)->result();
    foreach($result as $k=>$v){
        if($v->valid!=1) continue;
        $id_pe_producto=$v->id_pe_producto;
        $row=$this->db->query("SELECT *  FROM pe_productos WHERE id='$id_pe_producto'")->row();
        $embalaje=0;
        if($this->db->query("SELECT *  FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'")->num_rows())
            $embalaje=$this->db->query("SELECT *  FROM pe_embalajes WHERE codigo_producto='$id_pe_producto'")->row()->precio_embalaje_online/$v->cantidad;
        

        $datos=array(
            'fecha_venta'=>$fecha,
            'num_ticket'=>$id_order,
            'num_cliente'=>$id_order,
            'tipo_tienda'=>2,
            'tipo_tienda_letra'=>'P',
            'codigo_producto'=>$v->id_pe_producto,
            'grupo'=>$row->id_grupo,
            'familia'=>$row->id_familia,
            'id_pe_producto'=>$v->id_pe_producto,
            'precio_compra'=>$row->precio_compra,
            'tarifa_venta'=>$row->tarifa_venta,
            'precio_embalaje'=>$embalaje,
            'cantidad'=>$v->es_pack?0:$v->cantidad,
            'pvp_neto'=>$v->importe_con_descuento*10/$v->cantidad,
            'tipo_iva'=>$v->tipo_iva,
            'transporte'=>$factorTransporte*$v->importe_con_descuento/$v->cantidad,
            'total_transporte'=>$factorTransporte*$v->importe_con_descuento,
            'ingresado'=>$v->importe_con_descuento,
            'beneficio_producto'=>$v->es_pack?0:$this->productos_->calculoMargenProducto($row->precio_compra,$v->importe_con_descuento*10/$v->cantidad,$v->tipo_iva*10),
            'beneficio_producto_embalaje'=>$v->es_pack?0:$this->productos_->calculoMargenProducto($row->precio_compra+$embalaje,$v->importe_con_descuento*10/$v->cantidad,$v->tipo_iva*10),
            'beneficio_producto_embalaje_transporte'=>$v->es_pack?0:0, 
            'beneficio_absoluto'=>$v->es_pack?0:($v->importe_con_descuento*10)*100/(100+$v->tipo_iva/100)-$row->precio_compra*$v->cantidad,
        );
        //calculoMargenProducto($precio_compra,$tarifa_venta,$iva)
        
        // mensaje('precio compra '.$row->precio_compra);
        // mensaje('precio venta unitario: '.$v->importe_con_descuento*10/$v->cantidad);
        // mensaje('iva: '.$v->tipo_iva*10);
        // mensaje('beneficio '.$this->productos_->calculoMargenProducto($row->precio_compra,$v->importe_con_descuento*10/$v->cantidad,$v->tipo_iva*10));
        $this->db->query($this->db->insert_string('pe_registro_ventas', $datos));

    }
    //$total=$this->db->query("SELECT sum(importe_con_descuento) as total FROM pe_lineas_orders_prestashop where id_order='$id_order'")->row()->total;;


}

function pruebasC(){


    $dato=array();
    $this->load->view('templates/header.html', $dato);
    $this->load->view('templates/top.php',$dato);
    $this->load->view('pruebaC',$dato);
    $this->load->view('templates/footer.html');

//     echo "diferencias pe_tickets y pe_registro_ventas dia 2019-05-02".'<br>';
//     $sql="SELECT num_ticket,total,fecha FROM pe_tickets WHERE fecha>='2018-05-01' and fecha<'2018-06-01' ";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $numTicket=$v->num_ticket;
//         $total=$v->total;
//         $fecha=$v->fecha;
//         $sql2="SELECT sum(ingresado) as ingresado FROM pe_registro_ventas WHERE num_ticket='$numTicket' and fecha_venta='$fecha' and tipo_tienda=1 ";
//         if($this->db->query($sql2)->num_rows()==0){
//             echo $numTicket.' '.$total.'<br>';
//         }else{
//             $row=$this->db->query($sql2)->row();
//             echo $numTicket.' '.$total.' '.$row->ingresado.' '.($row->ingresado-$total).'<br>';;
//         }
//     }
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;

//     echo "comparación tickets com regristro ventas<br>";
//     $sql="SELECT num_ticket, total, fecha FROM pe_tickets WHERE  `fecha`>='2019-01-31' and `fecha`<'2019-02-01' ";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $numTicket=$v->num_ticket;
//         $total=$v->total;
//         $fecha=$v->fecha;
//         $sql="SELECT num_ticket, sum(ingresado) as ingresado FROM pe_registro_ventas WHERE  num_ticket='$numTicket' and fecha_venta='$fecha' and tipo_tienda=1 GROUP BY num_ticket";
//         $numRows=$this->db->query($sql)->num_rows();
//         if($numRows){
//             $row=$this->db->query($sql)->row();
//             echo $numTicket.' '.$total.' '.$row->ingresado.'<br>';
//         }
//         else {
//             echo $numTicket.' '.$total.'<br>';
//         }
//     }
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;



//     echo "NO coincide Transporte pe_orders_prestashop con sum(total_transporte) en pe_registo_ventas <br>";
//     $sql="SELECT * FROM pe_orders_prestashop WHERE fecha>='2018-02-26' ";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $transporte=$v->transporte;
//         $pedido=$v->id;
//         $fecha=$v->fecha;
//         $sql="SELECT sum(total_transporte) as totalTransporte FROM pe_registro_ventas WHERE num_ticket=$pedido and tipo_tienda=2";
//         $row=$this->db->query($sql)->row();
//         if($transporte!=intval($row->totalTransporte)) {
//             echo 'pedido - fecha<br>';
//             echo $pedido.' '.$fecha.'<br>';
//             echo $transporte.'<br>';
//             echo intval($row->totalTransporte).'<br>';
//             $diferencia=intval($row->totalTransporte)-$transporte;
//             echo abs($diferencia).'<br>';
//             if(abs($diferencia)<45){
//                 $sql="SELECT id, total_transporte as totalTransporte FROM pe_registro_ventas WHERE num_ticket=$pedido and tipo_tienda=2 ORDER BY total_transporte DESC LIMIT 1";
//                 $row=$this->db->query($sql)->row();
//                 $id=$row->id;
//                 $this->db->query("UPDATE pe_registro_ventas SET total_transporte=total_transporte-$diferencia WHERE id=$id");
//             }
//         }

//     }
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;



	
// 	echo "NO existe pedido en pe_orders_prestashop <br>";
//     $sql="SELECT num_ticket FROM pe_registro_ventas WHERE tipo_tienda=2 group by num_ticket order by num_ticket";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
        
//         $pedido=$v->num_ticket;
//         $sql="SELECT id  FROM pe_orders_prestashop WHERE id=$pedido ";
//         $row=$this->db->query($sql)->row();
//             echo $pedido.' '.$row->id;
//             echo '<br>';
        

//     }
// 	$sql="SELECT sum(ingresado) as ingresado FROM pe_registro_ventas WHERE tipo_tienda=2 and fecha_venta>='2018-02-26'";
// 	$row=$this->db->query($sql)->row();
// 	echo $row->ingresado.'<br>';
		
// 	$sql="SELECT sum(total_producto) as totalProducto FROM pe_orders_prestashop WHERE  fecha>='2018-02-26'";
// 	$row=$this->db->query($sql)->row();
// 	echo $row->totalProducto.'<br>';		
	
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;

	
	
	
//     echo "NO coincide Total producto pe_orders_prestashop con sum(ingresado) en pe_registo_ventas <br>";
//     $sql="SELECT * FROM pe_orders_prestashop WHERE fecha>='2018-02-26' ";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $totalProducto=$v->total_producto;
//         $pedido=$v->id;
//         $fecha=$v->fecha;
//         $sql="SELECT sum(ingresado) as ingresado FROM pe_registro_ventas WHERE num_ticket=$pedido and tipo_tienda=2";
//         $row=$this->db->query($sql)->row();
//         if($totalProducto!=intval($row->ingresado)) {
//             echo 'pedido - fecha<br>';
//             echo $pedido.' '.$fecha.'<br>';
//             echo $totalProducto.'<br>';
//             echo intval($row->ingresado).'<br>';
//         }

//     }
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;

//     /*
//     $sql="SELECT l.valid as valid,id_order as pedido FROM pe_lineas_orders_prestashop l
//          LEFT JOIN pe_orders_prestashop o ON l.id_order=o.id 
//          WHERE  o.fecha>='2018-02-23' ";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         if($v->valid!=1){
//             $pedido=$v->pedido;
//             $this->db->query("DELETE FROM pe_registro_ventas WHERE num_ticket='$pedido' and tipo_tienda=2");
//         }
// }
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;
// */
//     $sql="SELECT * FROM pe_lineas_orders_prestashop l
//          LEFT JOIN pe_orders_prestashop o ON l.id_order=o.id 
//          WHERE  o.fecha>='2018-02-23' ";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $cantidad=$v->cantidad;
//         $ingresado=$v->importe_con_descuento;
//         $numTicket=$v->id_order;
//         $codigoProducto=$v->id_pe_producto;
//         $sql="UPDATE pe_registro_ventas SET ingresado='$ingresado' WHERE num_ticket=$numTicket and tipo_tienda=2 and codigo_producto=$codigoProducto and cantidad=$cantidad";
//         $this->db->query($sql);
//     }

//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;





//     $sql="SELECT * FROM pe_orders_prestashop WHERE fecha>='2018-01-01' ";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $pedido=$v->id; 
//         //echo '<br>'.$pedido.'<br><br>';
//         $totalProductos=$v->total_producto;
//         $sql="SELECT sum(importe_con_descuento) as importe FROM pe_lineas_orders_prestashop WHERE id_order=$pedido and valid=1";
//         if($this->db->query($sql)->num_rows()==0) {
//             echo "-------------ver pedido no componentes".$pedido.'<br>';
//             continue;
//         }
//         $totalComponentes=$this->db->query($sql)->row()->importe;
//         //echo $totalProductos.'<br>';
//         //echo $totalComponentes.'<br>';
//         if(intval($totalProductos)!=intval($totalComponentes)){
//         echo $pedido.'<br>';
//         echo $totalProductos.'<br>';
//         echo $totalComponentes.'<br>';
//         echo 'diferencia en orders '.($totalComponentes-$totalProductos).'<br>'.'<br>';
//         }
       

//         }        


// /*
//     $sql="SELECT * FROM pe_boka WHERE STYP=2 AND zeis>='2019-02-26' AND zeis<'2019-03-01'";
//             $result=$this->db->query($sql)->result();
//             foreach($result as $k=>$v){
//                 $fecha_venta=$v->ZEIS;
//                 $codigo_producto=$v->id_pe_producto;
//                 $cantidad=$v->POS1;
//                 $peso=$v->GEW1;
//                // $row=$this->db->query("SELECT id_pe_producto, BT10,BT12,BT20,BT30,POS1,GEW1,MWSA FROM pe_boka WHERE (GEW1='$GEW1' AND POS1=1) OR (GEW1='$0' AND POS1='$POS1') AND ZEIS='$zeis' AND STYP=2")->row();
//                $numRows=$this->db->query("SELECT * FROM pe_registro_ventas WHERE ((peso='$peso' and cantidad=1) or (peso=0 AND cantidad='$cantidad')) and  codigo_producto='$codigo_producto' AND fecha_venta='$fecha_venta' ")->num_rows();
//                 echo $fecha_venta.' '.$codigo_producto.' ',$numRows.'<br>';
                
//             }
// */
//     /*
//     $sql="SELECT id FROM pe_orders_prestashop WHERE fecha>='2018-02-23'";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $this->generarRegistrosVentas($v->id);
//         echo $v->id.' - ';
//     }
// */

//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;

//     //update pe_registro_venta tipo_tienda=2 desde pe_lienea_order_prestashop
//     $sql="SELECT id_order, id_pe_producto, cantidad, importe_con_descuento FROM pe_lineas_orders_prestashop where id_order=18534";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $ingresado=$v->importe_con_descuento;
//         $num_ticket=$v->id_order;
//         $id_pe_producto=$v->id_pe_producto;
//         $cantidad=$v->cantidad;
//         $sql="UPDATE pe_registro_ventas SET ingresado='$ingresado' WHERE tipo_tienda=2 and num_ticket='$num_ticket' and id_pe_producto='$id_pe_producto' and cantidad='$cantidad'";
//         $this->db->query($sql);
//     }

//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;


//     //detectar inscripciones con valid!=1
//     $sql="SELECT num_ticket,cantidad,codigo_producto FROM pe_registro_ventas where tipo_tienda=2 and pvp!=0 and num_ticket=18534";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $sql="SELECT id,num_ticket FROM pe_registro_ventas where tipo_tienda=2 and pvp=0 and cantidad='".$v->cantidad."' and codigo_producto='".$v->codigo_producto."' LIMIT 1";
//         if(!$this->db->query($sql)->num_rows()) continue;
//         echo $this->db->query($sql)->row()->id;
//         echo ' - ';
//         echo $v->codigo_producto;
//         echo ' - ';
//         echo $v->cantidad;
//         echo ' - ';
//         echo ' <br> ';
//         $id= $this->db->query($sql)->row()->id;
//         $this->db->query("DELETE FROM pe_registro_ventas WHERE id='$id'");

//     }

//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;



//     //si es pack ->beneficio_absoluto=0, cantidad=0
//     $sql="UPDATE pe_registro_ventas SET cantidad=0, beneficio_absoluto=0 WHERE tipo_tienda=2 and grupo=25 and familia=54";
//     $this->db->query($sql);

//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;


//     //poner beneficios a productos tienda con peso!=0
//     $sql="SELECT id,id_pe_producto, precio_compra, pvp_neto, peso,tipo_iva FROM pe_registro_ventas where tipo_tienda=1 and peso!=0 ";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $beneficio=0;
//         $beneficioAbsoluto=0;
//         // mensaje($v->pvp_neto);
//         // mensaje($v->precio_compra);
//         // mensaje($v->tipo_iva);
//         // mensaje($v->peso);
       

//         if($v->pvp_neto!=0){
//             $beneficio=1000*(100*$v->pvp_neto-$v->precio_compra*(100+$v->tipo_iva/100))/$v->pvp_neto;
//             $beneficioAbsoluto=$v->peso/1000*(100*$v->pvp_neto/(100+$v->tipo_iva/100)-$v->precio_compra);
            
//         }
//         // mensaje($beneficio);
//         // mensaje($beneficioAbsoluto);
//         $this->db->query("UPDATE pe_registro_ventas SET beneficio_absoluto='$beneficioAbsoluto', beneficio_producto='$beneficio', beneficio_producto_embalaje='$beneficio', beneficio_producto_embalaje_transporte='$beneficio' WHERE id='".$v->id."'");
//     }
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;


    
//     //poner precio_compra a los productos tienda con peso
//     $sql="SELECT id_pe_producto FROM pe_registro_ventas where tipo_tienda=1 and peso!=0 GROUP BY id_pe_producto";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $row=$this->db->query("SELECT precio_compra,peso_real,tarifa_venta FROM pe_productos WHERE id='".$v->id_pe_producto."'")->row();
//         if(!$row->peso_real){
//             echo $v->id_pe_producto.' ';
//             $row->peso_real=1;
//         }
//         $precio_compra=$row->precio_compra/($row->peso_real/1000);
//         $tarifa_venta=$row->tarifa_venta/($row->peso_real/1000);
//         $this->db->query("UPDATE pe_registro_ventas SET precio_compra='$precio_compra', tarifa_venta='$tarifa_venta' WHERE id_pe_producto='".$v->id_pe_producto."'");
//     }   
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;



//     //poner id_grupo, id_familia
//     //no destructivo, se puede hacer cuantas veces se quiera
//     $sql="SELECT id_pe_producto FROM pe_registro_ventas GROUP BY id_pe_producto";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $row=$this->db->query("SELECT id_grupo,id_familia FROM pe_productos WHERE id='".$v->id_pe_producto."'")->row();
//         $id_grupo=$row->id_grupo;
//         $id_familia=$row->id_familia;
//         $this->db->query("UPDATE pe_registro_ventas SET grupo='$id_grupo', familia='$id_familia' WHERE id_pe_producto='".$v->id_pe_producto."'");
//     }   
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;

//     //coloca peso_real a los productos vendidos a peso (peso!=0)
//     //no destructivo, se puede hacer cuantas veces se quiera
//     $sql="SELECT id_pe_producto FROM pe_registro_ventas where tipo_tienda=1 and peso!=0 group by id_pe_producto";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $id=$v->id_pe_producto;
//         $pesoReal=$this->db->query("SELECT peso_real FROM pe_productos WHERE id='$id'")->row()->peso_real;
//         $this->db->query("UPDATE pe_registro_ventas SET peso_real='$pesoReal' WHERE id_pe_producto='$id'");
//     }
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;

//     //coloca pvp correcto en ventas con descuento en ventas tienda
//     $this->load->model('ventas_model');
//     $fechaInicio='2019-01-15';
//     $fachaFinal='2020-01-01'; 
//     $sql="SELECT BONU, id_pe_producto,SNR1,ZEIS,BT10,BT12,BT20,BT30,POS1,GEW1 FROM pe_boka WHERE STYP=2 AND ZEIS>='$fechaInicio' AND ZEIS<='$fachaFinal' ";
//     echo $sql.'<br>';
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $BONU=$v->BONU;
//         $ZEIS=$v->ZEIS;
//         $id_pe_producto=$v->id_pe_producto;
//         $pos1=$v->POS1;
//         $peso=$v->GEW1;
//         $BT10=$v->BT10;
//         $BT12=$v->BT10;
//         $BT20=$v->BT20;
//         $BT30=$v->BT30;
//         $POS1=$v->POS1;
//         $GEW1=$v->GEW1;

//         $sql="SELECT RASA FROM pe_boka WHERE BONU='$BONU' AND ZEIS='$ZEIS' AND STYP=1";
//         //echo $sql.'<br>';
//         $num_ticket=$this->db->query($sql)->row()->RASA;
//         $sql="SELECT * FROM pe_registro_ventas WHERE fecha_venta='$ZEIS' AND num_ticket='$num_ticket' AND id_pe_producto='$id_pe_producto' and cantidad='$pos1' AND peso='$peso'";
//         //echo $sql.'<br>';
//         $n=$this->db->query($sql)->num_rows();
//         echo $n.' ';
//         if(!$n==0) {
//             $result=$this->db->query($sql)->result();
//             $cantidad=$peso==0?$pos1:$peso/1000;
//              //echo '<br> -original boka-------- '.$num_ticket.' '.$ZEIS.' '.$pos1.' '.$peso.'<br>'; 
//             $importes=$this->ventas_model->getImportes($BT10,$BT12,$BT20,$BT30,$POS1,$GEW1);
//             $row=$this->db->query($sql." LIMIT 1")->row();
//             $importes['totalSinDescuento']=$importes['totalSinDescuento']==0?$row->pvp:$importes['totalSinDescuento'];
//             $pvpConDescuento=($importes['totalSinDescuento']+$importes['descuento'])*10/$cantidad;
//             $precioCompra=$row->precio_compra;
//             $tipoIva=$row->tipo_iva;
//             $precioEmbalaje=$row->precio_embalaje;
//             $transporte=$row->transporte;

//             $beneficio_producto=$this->productos_->calculoMargenProducto($precioCompra,$pvpConDescuento,$tipoIva*10);
//             $beneficio_producto_embalaje=$this->productos_->calculoMargenProducto($precioCompra+$precioEmbalaje,$pvpConDescuento,$tipoIva*10);
//             $beneficio_producto_embalaje_transporte=$this->productos_->calculoMargenProducto($precioCompra+$precioEmbalaje+$transporte/1.21,$pvpConDescuento+$transporte/1.21,$tipoIva*10);  
//             $beneficio_absoluto=$pvpConDescuento?$cantidad*($pvpConDescuento*100/(100+$tipoIva/100)-$precioCompra-$precioEmbalaje):0;
             
                
//             foreach($result as $k1=>$v1){
//                 $id=$v1->id;
                
//                 $this->db->query("UPDATE pe_registro_ventas 
//                                     SET pvp_nuevo='".$importes['precioUnitario']."',
//                                     total_sin_descuento='".$importes['totalSinDescuento']."',
//                                     descuento='".$importes['descuento']."',
//                                     beneficio_producto='".$beneficio_producto."',
//                                     beneficio_producto_embalaje='".$beneficio_producto_embalaje."',
//                                     beneficio_producto_embalaje_transporte='".$beneficio_producto_embalaje_transporte."',
//                                     beneficio_absoluto='".$beneficio_absoluto."',
//                                     pvp_neto='".$pvpConDescuento."'
//                                      WHERE id='$id'");
//                 //echo '<br> --resumen_ventas------ '.$num_ticket.' '.$fecha.' '.$cantidad.' '.$peso;
//             }
//             //echo '<br>';
//         }
//     }

//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;


    





    


    

    
    
//      //elimina registros de venta prestashop que NO sean válidos (valid!=1) 
//      //no destructivo, se puede hacer cuantas veces se quiera
//     $sql="SELECT id, num_ticket FROM pe_registro_ventas where tipo_tienda=2";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $id=$v->id;
//         $num_ticket=$v->num_ticket;
//         $result=$this->db->query("SELECT valid FROM pe_lineas_orders_prestashop WHERE id_order='$num_ticket'")->result();
//         foreach($result as $k1=>$v1){
//             if($v1->valid!=1)
//                 $this->db->query("DELETE FROM pe_registro_ventas WHERE id='$id'");
//         }      
//     }
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;




//     //coloca peso_real a los productos vendidos a peso (peso!=0)
//     //no destructivo, se puede hacer cuantas veces se quiera
//     $sql="SELECT id_pe_producto FROM pe_registro_ventas where tipo_tienda=1 and peso!=0 group by id_pe_producto";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $id=$v->id_pe_producto;
//         $pesoReal=$this->db->query("SELECT peso_real FROM pe_productos WHERE id='$id'")->row()->peso_real;
//         $this->db->query("UPDATE pe_registro_ventas SET peso_real='$pesoReal' WHERE id_pe_producto='$id'");
//     }
//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;

//     //coloca pvp correcto en ventas con descuento en ventas tienda
//     $this->load->model('ventas_model');
//     $sql="SELECT BONU, id_pe_producto,SNR1,ZEIS,BT10,BT12,BT20,BT30,POS1,GEW1 FROM pe_boka WHERE STYP=2 AND ZEIS>='2019-01-12' ";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $BONU=$v->BONU;
//         $ZEIS=$v->ZEIS;
//         $id_pe_producto=$v->id_pe_producto;
//         $pos1=$v->POS1;
//         $peso=$v->GEW1;
//         $BT10=$v->BT10;
//         $BT12=$v->BT10;
//         $BT20=$v->BT20;
//         $BT30=$v->BT30;
//         $POS1=$v->POS1;
//         $GEW1=$v->GEW1;

//         $sql="SELECT RASA FROM pe_boka WHERE BONU='$BONU' AND ZEIS='$ZEIS' AND STYP=1";
//         $num_ticket=$this->db->query($sql)->row()->RASA;
//         $sql="SELECT * FROM pe_registro_ventas WHERE fecha_venta='$ZEIS' AND num_ticket='$num_ticket' AND id_pe_producto='$id_pe_producto' and cantidad='$pos1' AND peso='$peso'";
//         $n=$this->db->query($sql)->num_rows();
//         echo $n.' ';
//         if(!$n==0) {
//             $result=$this->db->query($sql)->result();
//             $cantidad=$peso==0?$pos1:$peso/1000;
            
//             //echo '<br> -original boka-------- '.$num_ticket.' '.$ZEIS.' '.$pos1.' '.$peso.'<br>'; 
//             $importes=$this->ventas_model->getImportes($BT10,$BT12,$BT20,$BT30,$POS1,$GEW1);
//             $pvpConDescuento=($importes['totalSinDescuento']+$importes['descuento'])*10/$cantidad;
//             $row=$this->db->query($sql." LIMIT 1")->row();
//             $precioCompra=$row->precio_compra;
//             $tipoIva=$row->tipo_iva;
//             $precioEmbalaje=$row->precio_embalaje;
//             $transporte=$row->transporte;

//             $beneficio_producto=$this->productos_->calculoMargenProducto($precioCompra,$pvpConDescuento,$tipoIva*10);
//             $beneficio_producto_embalaje=$this->productos_->calculoMargenProducto($precioCompra+$precioEmbalaje,$pvpConDescuento,$tipoIva*10);
//             $beneficio_producto_embalaje_transporte=$this->productos_->calculoMargenProducto($precioCompra+$precioEmbalaje+$transporte/1.21,$pvpConDescuento+$transporte/1.21,$tipoIva*10);  
//             $beneficio_absoluto=$pvpConDescuento?$cantidad*($pvpConDescuento*100/(100+$tipoIva/100)-$precioCompra-$precioEmbalaje):0;
             
                
//             foreach($result as $k1=>$v1){
//                 $id=$v1->id;
//                 $this->db->query("UPDATE pe_registro_ventas 
//                                     SET pvp_nuevo='".$importes['precioUnitario']."',
//                                     total_sin_descuento='".$importes['totalSinDescuento']."',
//                                     descuento='".$importes['descuento']."',
//                                     beneficio_producto='".$beneficio_producto."',
//                                     beneficio_producto_embalaje='".$beneficio_producto_embalaje."',
//                                     beneficio_producto_embalaje_transporte='".$beneficio_producto_embalaje_transporte."',
//                                     beneficio_absoluto='".$beneficio_absoluto."'
                                    
//                                      WHERE id='$id'");
//                 //echo '<br> --resumen_ventas------ '.$num_ticket.' '.$fecha.' '.$cantidad.' '.$peso;
//             }
//             //echo '<br>';
//         }
//     }

//     $dato=array();
//     $this->load->view('templates/header.html', $dato);
//     $this->load->view('templates/top.php',$dato);
//     $this->load->view('pruebaC',$dato);
//     $this->load->view('templates/footer.html');
//     return;


// //                return array('precioUnitario'=>$precioUnitario, 'totalSinDescuento'=>$totalSinDescuento,'descuento'=>$descuento);

//     /*
//     $sql="SELECT num_ticket,id_pe_producto FROM pe_registro_ventas WHERE tipo_tienda=1 LIMIT 10";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $sql="SELECT BONU,ZEIS FROM pe_boka WHERE STYP=1 AND RASA='".$v->num_ticket."' AND ZEIS>='2018-02-26'";
//         $result1=$this->db->query($sql)->result();
//             foreach($result1 as $k1=> $v1){
//                 $zeis=$v1->ZEIS;
//                 $bonu=$v1->BONU;
//                 $id_pe_producto=$v->id_pe_producto;
//                 $sql="SELECT SNR1 FROM pe_boka WHERE STYP=2 AND bonu='$bonu' AND zeis='$zeis' AND id_pe_producto='$id_pe_producto'";
//                 $result2=$this->db->query($sql)->result();
//                 foreach($result2 as $k=>$v2){
//                     echo $v->num_ticket.' '.$v1->BONU.' '.$v1->ZEIS.' '.$v2->SNR1.'<br> ';
//                 }
//             }
//         }
    
// */



//   /*  
//     $sql="SELECT BONU,ZEIS,SNR1 FROM pe_boka WHERE STYP=202 GROUP BY BONU,ZEIS,SNR1 ORDER BY zeis";
//     $result=$this->db->query($sql)->result();
//     foreach($result as $k=>$v){
//         $sql="SELECT id_pe_producto as id_pe_producto FROM pe_boka WHERE STYP=2 AND BONU='".$v->BONU."' AND SNR1='".$v->SNR1."' AND ZEIS='".$v->ZEIS."'";
//         $result2=$this->db->query($sql)->result();
//         foreach($result2 as $k2=>$v2){
//             $sql="UPDATE pe_boka SET id_pe_producto='".$v2->id_pe_producto."' WHERE STYP=202 AND BONU='".$v->BONU."' AND SNR1='".$v->SNR1."' AND ZEIS='".$v->ZEIS."'";
//             // mensaje($sql);
//             $this->db->query($sql);
//             //echo $row->num.' '.$v->BONU.' '.$v->ZEIS.'<br>';
//         }
//     }
// */
//     /*
// //analisis tickets 04/01/2019
// $sql="SELECT BONU,RASA FROM `pe_boka` WHERE zeis >='2019-01-04' and zeis <'2019-01-05' and STYP=1";
// $result=$this->db->query($sql)->result();
// foreach($result as $k=>$v){
//     $sql="SELECT * FROM `pe_boka` WHERE zeis >='2019-01-04' and zeis <'2019-01-05' and BONU='".$v->BONU."' AND STYP=1";
//     $total=$this->db->query($sql)->row()->BT20;
//     $sql="SELECT sum(BT20) as suma FROM `pe_boka` WHERE zeis >='2019-01-04' and zeis <'2019-01-05' and BONU='".$v->BONU."' AND STYP=2";
//     $suma=$this->db->query($sql)->row()->suma;
//     $sql="SELECT sum(BT30) as suma2 FROM `pe_boka` WHERE zeis >='2019-01-04' and zeis <'2019-01-05' and BONU='".$v->BONU."' AND STYP=202";
//     $suma2=$this->db->query($sql)->row()->suma2;
//     if($total!=($suma+$suma2)) echo $total.' '.$suma.' '.$v->BONU.' '.$v->RASA,'<br>';
// }
// */





}

function pruebasC_(){
    
    //$this->db->query("UPDATE pe_registro_ventas SET num_cliente='0' where tipo_tienda=1 ");
    $sql="SELECT SNR2,RASA,ZEIS FROM pe_boka WHERE STYP=1 AND ZEIS>='2019-02-20' and snr2!=0 LIMIT 100";
    $result=$this->db->query($sql)->result();
    foreach($result as $k=>$v){
        $fecha_venta=$v->ZEIS;
        $num_ticket=$v->RASA;
        $num_cliente=$v->SNR2;
        echo $fecha_venta.' '.$num_ticket;
        $this->db->query("UPDATE pe_registro_ventas SET num_cliente='$num_cliente' where tipo_tienda=1 and num_ticket='$num_ticket' and fecha_venta='$fecha_venta'");
    }
    $dato=array();
    $this->load->view('templates/header.html', $dato);
    $this->load->view('templates/top.php',$dato);
    $this->load->view('pruebaC',$dato);
    $this->load->view('templates/footer.html');
    return;
}

    
function pruebasD(){

$dato=array();
echo MD5('sergis');
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaD',$dato);
$this->load->view('templates/footer.html');
}
function pruebasE(){


$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaE',$dato);
$this->load->view('templates/footer.html');
}

function pruebasF(){


$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaF',$dato);
$this->load->view('templates/footer.html');
}

function pruebasG(){


$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaG',$dato);
$this->load->view('templates/footer.html');
}

function pruebasH(){



$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaH',$dato);
$this->load->view('templates/footer.html');
}



function pruebasI(){


$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaI',$dato);
$this->load->view('templates/footer.html');
}

function pruebasJ(){


$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaI',$dato);
$this->load->view('templates/footer.html');
}

function pruebasK(){

$sql="SELECT b.bonu as bonu,b1.rasa as rasa, b.zeis as fecha,  b.gew1 as gew1,b.snr1 as snr1
FROM pe_boka b
LEFT JOIN pe_boka b1 ON b.bonu=b1.bonu and b1.STYP=1
WHERE b.styp=2 AND left(b.zeis,10)>='2016-10-01' AND b.gew1>0 ";
$result=$this->db->query($sql)->result();
echo '<table>';
    echo '<tr>';
        echo '<th>'.'Ticket'.'</td>';
            echo '<th>'.'Fecha'.'</td>';
            echo '<th style="text-align:left">'.'Producto'.'</td>';
            echo '<th>'.'Código Báscula'.'</td>';
            echo '<th>'.'Peso'.'</td>';
            echo '<th>'.'Código asignado'.'</td>';
            echo '<th>'.'Peso código'.'</td>';
            echo '<th>'.'% $obre peso'.'</td>';
            echo '<th>'.'BONU'.'</td>';
            echo '</tr>';
    foreach($result as $k=>$v){
    $id_producto=$v->snr1;
    $bonu=$v->bonu;
    $sql="SELECT max(peso_real) as maxPeso,codigo_producto,nombre FROM pe_productos WHERE id_producto='$id_producto' GROUP BY id_producto";
    $maxPeso=$this->db->query($sql)->row()->maxPeso;
    $codigo_producto=$this->db->query($sql)->row()->codigo_producto;
    $nombre=$this->db->query($sql)->row()->nombre;
    if($v->gew1>$maxPeso){
    $sobrepeso=0;
    if($maxPeso!=0) 
    $sobrepeso= number_format(($v->gew1-$maxPeso)/$maxPeso*100,2);
    if($sobrepeso>=10){
    echo '<tr>';
        echo '<td>'.$v->rasa.'</td>';
        echo '<td>'.$v->fecha.'</td>';
        echo '<td style="text-align:left">'.$nombre.'</td>';
        echo '<td>'.$id_producto.'</td>';
        echo '<td>'.$v->gew1.'</td>';
        echo '<td>'.$codigo_producto.'</td>';
        echo '<td>'.$maxPeso.'</td>';
        echo '<td>'.$sobrepeso.'</td>';
        echo '<td>'.$bonu.'</td>';
        echo '</tr>';
    }
    }
    }
    echo '</table>';


$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaK',$dato);
$this->load->view('templates/footer.html');
}

function pruebasL(){
$this->load->model('productos_');
$sql="SELECT id, id_pe_producto, snr1, POS1, gew1,zeis FROM  pe_boka WHERE STYP=2 AND ZEIS>='2017-01-01'";
foreach($this->db->query($sql)->result() as $k=>$v){
$snr1=$v->snr1;
$gew1=$v->gew1;
$id=$v->id;
$asignacion=$this->productos_->asignarProducto($v->snr1,$gew1,$v->POS1);
$id_pe_producto=$asignacion['id'];
$this->db->query("UPDATE pe_boka SET id_pe_producto='$id_pe_producto' WHERE id='$id'");
}








$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaL',$dato);
$this->load->view('templates/footer.html');
}


function pruebasM(){
$codigo_producto='0101050000000';
$this->load->model('stocks_model');
$this->load->model('productos_');
$id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);

$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaL',$dato);
$this->load->view('templates/footer.html');
}

function poner($codigo_producto,$cantidad,$fecha_caducidad_stock){
$id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);
$proveedor=$this->productos_->getIdProveedor($id_pe_producto);
$hoy=date('Y-m-d');

//creación stocks 
$this->db->query("INSERT INTO pe_stocks SET codigo_producto='$id_pe_producto',"
. "  codigo_bascula='$id_pe_producto',"
. "  id_pe_producto='$id_pe_producto',"
. "  proveedor='$proveedor',"
. "  cantidad='$cantidad',"
. "  fecha_entrada='$hoy',"
. "  fecha_caducidad_stock='$fecha_caducidad_stock',"
. "  fecha_modificacion_stock='$hoy',"
. "  activo=1 ");
}
function pruebasN(){

$codigo_producto='0101050000000';
$this->load->model('stocks_model');
$this->load->model('productos_');
$id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);
$this->db->query("DELETE FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'");
// echo "DELETE FROM pe_stocks WHERE id_pe_producto='$id_pe_producto'";
$cantidad='3';
$fecha_caducidad_stock='2017-01-30';
$this->poner($codigo_producto,$cantidad,$fecha_caducidad_stock);
$cantidad='0';
$fecha_caducidad_stock='2017-02-27';
$this->poner($codigo_producto,$cantidad,$fecha_caducidad_stock);
$cantidad='14';
$fecha_caducidad_stock='2017-02-27';
$this->poner($codigo_producto,$cantidad,$fecha_caducidad_stock);
$cantidad='3';
$fecha_caducidad_stock='2017-03-30';
$this->poner($codigo_producto,$cantidad,$fecha_caducidad_stock);
$cantidad='-5';
$fecha_caducidad_stock='0000-00-00';
$this->poner($codigo_producto,$cantidad,$fecha_caducidad_stock);

$sql="select id,cantidad, fecha_caducidad_stock FROM pe_stocks WHERE codigo_producto='$id_pe_producto' ORDER BY fecha_caducidad_stock ";
$result=$this->db->query($sql)->result();
$dato['salida1']=array();
foreach($result as $k=>$v){
$dato['salida1'][]=array('fecha_caducidad_stock'=>$v->fecha_caducidad_stock,'cantidad'=>$v->cantidad,'id'=>$v->id);
}

$next=0;
$last=end($result);
foreach($result as $k=>$v){
$v->cantidad+=$next;
if($v==$last) {
$dato['salida2'][]=array('fecha_caducidad_stock'=>$v->fecha_caducidad_stock,'cantidad'=>$v->cantidad,'id'=>$v->id);
break;
}
if($v->cantidad<0){
$next=$v->cantidad;
$v->cantidad=0;
}else{
$next=0;
}
$dato['salida2'][]=array('fecha_caducidad_stock'=>$v->fecha_caducidad_stock,'cantidad'=>$v->cantidad,'id'=>$v->id);
}


$this->stocks_model->armonizarStocks($id_pe_producto);
$this->stocks_model->armonizarStocksTotales($id_pe_producto);

//$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaN',$dato);
$this->load->view('templates/footer.html');
}
/*
* rp = rp.replace(/&aacute;/g, 'á');
rp = rp.replace(/&eacute;/g, 'é');
rp = rp.replace(/&iacute;/g, 'í');
rp = rp.replace(/&oacute;/g, 'ó');
rp = rp.replace(/&uacute;/g, 'ú');
rp = rp.replace(/&ntilde;/g, 'ñ');
rp = rp.replace(/&uuml;/g, 'ü');
//
rp = rp.replace(/&Aacute;/g, 'Á');
rp = rp.replace(/&Eacute;/g, 'É');
rp = rp.replace(/&Iacute;/g, 'Í');
rp = rp.replace(/&Oacute;/g, 'Ó');
rp = rp.replace(/&Uacute;/g, 'Ú');
rp = rp.replace(/&Ñtilde;/g, 'Ñ');
rp = rp.replace(/&Üuml;/g, 'Ü');
*/


function replaceSpecialCharacters($str){

$str=strip_tags($str);
$str=str_replace('&aacute;','á',$str);
$str=str_replace('&eacute;','é',$str);
$str = str_replace('&iacute;', 'í',$str);
$str = str_replace('&oacute;', 'ó',$str);
$str = str_replace('&uacute;', 'ú',$str);
$str = str_replace('&ntilde;', 'ñ',$str);
$str = str_replace('&uuml;', 'ü',$str);

$str=str_replace('&Aacute;','Á',$str);
$str=str_replace('&Eacute;','É',$str);
$str = str_replace('&Iacute;', 'Í',$str);
$str = str_replace('&Oacute;', 'Ó',$str);
$str = str_replace('&Uacute;', 'Ú',$str);
$str = str_replace('&Ntilde;', 'Ñ',$str);
$str = str_replace('&Uuml;', 'Ü',$str);
$str = str_replace('&quot;', '"',$str);

return trim($str);

}


function pruebasO(){

$cat_nombre=array();
$cat_nombre[]="Jamón Ibérico Bellota 100% Summum DO Huelva - Pata Negra";
$cat_nombre[]="Jamón Ibérico Bellota 100% Summum DO Huelva - Pata Negra";
$cat_nombre[]="Jamón Ibérico Bellota 100% Summum DO Huelva - Pata Negra";
$cat_nombre[]="Jamón Ibérico Bellota 100% Summum DO Huelva - Pata Negra";
$cat_nombre[]="Jamón Ibérico Bellota 100% Summum DO Huelva - Pata Negra";

$cat_imagen=array();
$cat_imagen[]='http://www.jamonarium.com/2349-cart_default/jamon-iberico-bellota-gran-reserva-2013-extremadura-salamancaentero.jpg';
$cat_imagen[]='http://www.jamonarium.com/2349-cart_default/jamon-iberico-bellota-gran-reserva-2013-extremadura-salamancaentero.jpg';
$cat_imagen[]='http://www.jamonarium.com/2349-cart_default/jamon-iberico-bellota-gran-reserva-2013-extremadura-salamancaentero.jpg';
$cat_imagen[]='http://www.jamonarium.com/2349-cart_default/jamon-iberico-bellota-gran-reserva-2013-extremadura-salamancaentero.jpg';
$cat_imagen[]='http://www.jamonarium.com/2349-cart_default/jamon-iberico-bellota-gran-reserva-2013-extremadura-salamancaentero.jpg';



$cat_referencia=array();
$cat_referencia[]="0700000000000";
$cat_referencia[]="0700000000000";
$cat_referencia[]="0700000000000";
$cat_referencia[]="0700000000000";
$cat_referencia[]="0700000000000";

$cat_origen=array();
$cat_origen[]="DO Huelva/Jabugo";
$cat_origen[]="DO Huelva/Jabugo";
$cat_origen[]="";
$cat_origen[]="DO Huelva/Jabugo";

$cat_raza=array();
$cat_raza[]="Bellota Ibérico 50%";
$cat_raza[]="";
$cat_raza[]="Bellota Ibérico 50%";
$cat_raza[]="Bellota Ibérico 50%";

$cat_curado=array();
$cat_curado[]="+36 meses";

$cat_pesos=array();
$cat_pesos[]="7-8 Kg";

$cat_anada=array();
$cat_anada[]="2014";

$cat_formato=array();
$cat_formato[]="";
$cat_formato[]="";
$cat_formato[]="RO150";
$cat_formato[]="RO150";

$cat_unidades_caja=array();
$cat_unidades_caja[]="";
$cat_unidades_caja[]="";
$cat_unidades_caja[]="12";
$cat_unidades_caja[]="24";


$cat_ecologica=array();
$cat_ecologica[]="Sí";
$cat_ecologica[]="";
$cat_ecologica[]="Sí";
$cat_ecologica[]="Sí";

$cat_tipo_de_uva=array();
$cat_tipo_de_uva[]="";
$cat_tipo_de_uva[]="Tempranillo";
$cat_tipo_de_uva[]="";
$cat_tipo_de_uva[]="Tempranillo";

$cat_volumen=array();
$cat_volumen[]="";
$cat_volumen[]="750 mL";
$cat_volumen[]="";
$cat_volumen[]="750 mL";

$cat_variedades=array();
$cat_variedades[]="";

$cat_descripcion=array();
$cat_descripcion[]="Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de";
$cat_descripcion[]="";
$cat_descripcion[]="Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de";
$cat_descripcion[]="";

$cat_tarifa=array();
$cat_tarifa[]="41";
$cat_tarifa[]="41";
$cat_tarifa[]="41";
$cat_tarifa[]="41";
$cat_tarifa[]="141";

$cat_unidad=array();
$cat_unidad[]="€/Kg";
$cat_unidad[]="€/Unidad";
$cat_unidad[]="€/Kg";
$cat_unidad[]="€/Kg";
$cat_unidad[]="€/Unidad";


// Se carga la libreria fpdf
$this->load->library('pdf');

$pdf = new Pdf();

$pdf->SetAutoPageBreak(false);
$sql="SELECT * FROM pe_marcas WHERE id='1'";
$row=$this->db->query($sql)->row();
extract((array)$row);

foreach($cat_imagen as $k=>$v){
if($k%4==0){
//cabecera
if(true){
$pdf->AddPage('L','A4');
$margenIzq=10;
$margenSup=10;
$marco=0;
$pdf->SetFont('Times','B',22);
//$pdf->Cell(40,10,'Hello World!');
$pdf->Image($mapa,$margenIzq,$margenSup,40,0,'PNG');
$pdf->Image($imagen,$margenIzq+50,$margenSup,50,0,'JPG');
//$pdf->Image('http://www.jamonarium.com/2349-cart_default/jamon-iberico-bellota-gran-reserva-2013-extremadura-salamancaentero.jpg',$margenIzq+50,$margenSup,40,0,'JPG');

$pdf->setY($margenSup);
$pdf->Cell(120);
$pdf->Cell(0,6,iconv('UTF-8', 'windows-1252', $marca),$marco,2,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,8,iconv('UTF-8', 'windows-1252', $sub_titulo),$marco,2,'L');
$pdf->Cell(0,1,"",$marco,2,'L');
$pdf->SetFont('Arial','',8);
$descripcion_marca=$this->replaceSpecialCharacters($descripcion_marca);
//$descripcionMarca='Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición,...';
$pdf->MultiCell(0,4,iconv('UTF-8', 'windows-1252', $descripcion_marca),$marco,'L');
$y=52;
$pdf->Line($margenIzq,$y,297-$margenIzq,$y);
$espacio=10;
$anchoColumna=62;
}
}

$y=52;
//$pdf->Rect($margenIzq+$espacio*$k+$anchoColumna*$k,$y+5,$anchoColumna,40);
$pdf->Image($cat_imagen[$k],$margenIzq+$espacio*($k%4)+$anchoColumna*($k%4),$y+5,40,0,'JPG');

$yc=$pdf->getY();
$pdf->setY($y+48);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->MultiCell($anchoColumna,2,'',$marco,'L');
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell($anchoColumna,4,iconv('UTF-8', 'windows-1252', $cat_nombre[$k]),$marco,'L');
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->MultiCell($anchoColumna,2,'',$marco,'L');

$tamañoLetra=9;
if($cat_referencia[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(16,3,"Referencia",0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_referencia[$k]),0,0,'L');
}

if(isset($cat_origen[$k]) && $cat_origen[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(10,3,"Origen",0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_origen[$k]),0,0,'L');
}

if(isset($cat_raza[$k]) && $cat_raza[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(8,3,"Raza",0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_raza[$k]),0,0,'L');
}

if(isset($cat_curado[$k]) && $cat_curado[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(13,3,iconv('UTF-8', 'windows-1252', "Curación"),0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_curado[$k]),0,0,'L');
}

if(isset($cat_pesos[$k]) && $cat_pesos[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(9,3,iconv('UTF-8', 'windows-1252', "Pesos"),0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_pesos[$k]),0,0,'L');
}

if(isset($cat_anada[$k]) && $cat_anada[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(9,3,iconv('UTF-8', 'windows-1252', "Añada"),0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_anada[$k]),0,0,'L');
}

if(isset($cat_formato[$k]) && $cat_formato[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(12,3,iconv('UTF-8', 'windows-1252', "Formato"),0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_formato[$k]),0,0,'L');
}
if(isset($cat_unidades_caja[$k]) && $cat_unidades_caja[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(20,3,iconv('UTF-8', 'windows-1252', "Unidades caja"),0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_unidades_caja[$k]),0,0,'L');
}

if(isset($cat_ecologica[$k]) && $cat_ecologica[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(14,3,iconv('UTF-8', 'windows-1252', "Ecológica"),0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_ecologica[$k]),0,0,'L');
}

if(isset($cat_tipo_de_uva[$k]) && $cat_tipo_de_uva[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(17,3,iconv('UTF-8', 'windows-1252', "Tipo de uva"),0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_tipo_de_uva[$k]),0,0,'L');
}

if(isset($cat_volumen[$k]) && $cat_volumen[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->SetFont('Arial','U',$tamañoLetra);
$pdf->Cell(13,3,iconv('UTF-8', 'windows-1252', "Volumen"),0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->Cell(0,3,": ".iconv('UTF-8', 'windows-1252', $cat_volumen[$k]),0,0,'L');
}


if(isset($cat_descripcion[$k]) && $cat_descripcion[$k]!=""){
$yc=$pdf->getY();
$pdf->setY($yc+4);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
//$pdf->SetFont('Arial','U',$tamañoLetra);
//$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Ecológica"),0,0,'L');
$pdf->SetFont('Arial','',$tamañoLetra);
$pdf->MultiCell($anchoColumna,3,'',$marco,'L');
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
$pdf->MultiCell($anchoColumna,4,iconv('UTF-8', 'windows-1252', $cat_descripcion[$k]),0,'L');
}

if(isset($cat_tarifa[$k]) && $cat_tarifa[$k]!=""){
$yc=$pdf->getY();
$pdf->setY(195);
$pdf->setX($margenSup+$espacio*($k%4)+$anchoColumna*($k%4));
//$pdf->SetFont('Arial','B',$tamañoLetra);
//$pdf->Cell(8,3,iconv('UTF-8', 'windows-1252', "Volumen"),0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,3,iconv('UTF-8', 'windows-1252', 'Tarifa: '.$cat_tarifa[$k].' '.$cat_unidad[$k]),0,0,'L');
}

}



$pdf->Output();


$dato=array();
$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('pruebaO',$dato);
$this->load->view('templates/footer.html');
}

function pruebasP(){

$imagenes=array();
$codigo_productos=array();
$nombres=array();
$paginas=array();

$sql="SELECT codigo_producto, nombre,url_producto, url_imagen_portada FROM pe_productos WHERE status_producto=1 ORDER BY codigo_producto LIMIT 5";
$result=$this->db->query($sql)->result();
foreach ($result as $k=>$v){
$imagenes[]=$v->url_imagen_portada;
$paginas[]=$v->url_producto;
$codigo_productos[]=$v->codigo_producto;
$nombres[]=$v->nombre;
}





$dato=array();
$dato['imagenes']=$imagenes;
$dato['codigo_productos']=$codigo_productos;
$dato['nombres']=$nombres;
$dato['paginas']=$paginas;

$this->load->view('templates/header.html', $dato);
//  $this->load->view('templates/top.php',$dato);
$this->load->view('pruebaP',$dato);
$this->load->view('templates/footer.html');
}

function lecturaPrestashop(){

$this->load->library('excel');

$inputFileName = 'uploads/prestashop/ventas unitats prestashop 25-05-16.xlsx';

try {
$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);
} catch (Exception $e) {
die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();

//  Loop through each row of the worksheet in turn
for ($row = 2; $row <= $highestRow; $row++){ 
//  Read a row of data into an array
$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
NULL,
TRUE,
FALSE);
foreach($rowData as $k=>$v){
echo '0'.$v[0].' '.$v[2].'  '.$v[3].'<br >';
}
//  Insert row data array into your database of choice here
}



$dato['activeMenu']='Pruebas';
$dato['activeSubmenu']="lecturaPrestashop";

$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);

$this->load->view('lecturaPrestashop.php',$dato);


$this->load->view('templates/footer.html');
}

function verificarProductosBokaEnProductos(){
$fechaInicio="2016-01-01";
$sql="SELECT count(b.bonu) as num, b.bonu,b.snr1 as snr1,p.id_producto FROM pe_boka b
LEFT JOIN pe_productos p ON b.snr1=p.id_producto
WHERE b.STYP=2 AND LEFT(b.zeis,10)>='$fechaInicio' GROUP BY b.snr1";
$dato['productos']=array();
foreach($this->db->query($sql)->result() as $k=>$v){
$dato['productos'][]=array($v->num,$v->id_producto,$v->snr1);
}
$sql="SELECT count(b.bonu) as num, b.bonu,b.snr1 as snr1 FROM pe_boka b
WHERE b.STYP=2 AND LEFT(b.zeis,10)>='$fechaInicio' AND gew1=0 GROUP BY b.snr1";
$result=$this->db->query($sql)->result();
$dato['productos2']=array();
foreach($result as $k=>$v){
$snr1=$v->snr1;
$sql="SELECT count(*) as numbascula FROM pe_productos WHERE id_producto='$snr1'";
$result1=$this->db->query($sql)->result();
foreach($result1 as $k1=>$v1){
if($v1->numbascula>1)
$dato['productos2'][]=array($v->num,$v->snr1,$v1->numbascula);
}
}


$dato['activeMenu']='Pruebas';
$dato['activeSubmenu']="Verificar Productos Boka en Productos";

$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);

$this->load->view('verificarProductosBokaEnProductos.php',$dato);


$this->load->view('templates/footer.html');


}


function borrarDia2016_03_10(){
// $sql="DELETE FROM pe_boka WHERE id>264797"; //LEFT(ZEIS,10)='2016-05-07'";
//$this->db->delete('pe_boka',array('id >'=>'266971')); 
$sql="DELETE FROM pe_boka WHERE zeis >='2016-03-10' and zeis<'2016-03-11'";
$this->db->query($sql);
$this->db->delete('pe_productos',array('codigo_producto'=>'ninguno')); 

$this->db->select('nombre');
$this->db->where(array('id_producto >'=>50));
$this->db->order_by('nombre','ASC');
$query=$this->db->get('pe_productos');
foreach($query->result() as $k=>$v){
// echo $v->nombre;
}
echo '<br />'.'<br />';
$this->db->select(array('id_producto','nombre'));
$this->db->where(array('id_producto'=>3));
$this->db->order_by('nombre','ASC');
$this->db->limit(100);
$query=$this->db->get('pe_productos');
foreach($query->result() as $k=>$v){
echo $v->id_producto.$v->nombre;echo'<br />';
}


$dato['activeMenu']='Pruebas';
$dato['activeSubmenu']="borrarDia2016_03_10";

$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('templates/footer.html');

}

function borrarDia2016_05_24(){
// $sql="DELETE FROM pe_boka WHERE id>264797"; //LEFT(ZEIS,10)='2016-05-07'";
$this->db->delete('pe_boka',array('id >'=>'266971')); 
$this->db->delete('pe_productos',array('codigo_producto'=>'ninguno')); 

$this->db->select('nombre');
$this->db->where(array('id_producto >'=>50));
$this->db->order_by('nombre','ASC');
$query=$this->db->get('pe_productos');
foreach($query->result() as $k=>$v){
// echo $v->nombre;
}
echo '<br />'.'<br />';
$this->db->select(array('id_producto','nombre'));
$this->db->where(array('id_producto'=>3));
$this->db->order_by('nombre','ASC');
$this->db->limit(100);
$query=$this->db->get('pe_productos');
foreach($query->result() as $k=>$v){
echo $v->id_producto.$v->nombre;echo'<br />';
}


$dato['activeMenu']='Pruebas';
$dato['activeSubmenu']="borrarDia2016_05_24";

$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('templates/footer.html');

}

function borrarDia2016_06_11(){
$sql="DELETE FROM pe_boka WHERE left(zeis,10)='2016-06-11'";
$this->db->query($sql);

$dato['hecho']=$sql;

$dato['activeMenu']='Pruebas';
$dato['activeSubmenu']="borrarDia2016_06_11";

$this->load->view('templates/header.html', $dato);
$this->load->view('templates/top.php',$dato);
$this->load->view('templates/hecho.php',$dato);
$this->load->view('templates/footer.html');

}

function index()
{
$dato['activeMenu']='Pruebas';
$this->load->view('templates/header.html', $dato);

$this->load->view('menuMulti.php');
$this->load->view('templates/footer.html');

/*
$dato['activeMenu']='Pruebas';
$dato['activeSubmenu']="Para pruebas";

$this->load->view('templates/header.html', $dato);
//$this->load->view('templates/top.php',$dato);

// $this->load->view('inicio.php',$dato);

$this->load->view('templates/footer.html');
*/




// $sql="UPDATE `pe_productos` SET `margen_real_producto`=(100*100*`tarifa_venta_unidad`-(100*`tarifa_venta_unidad`-`descuento_1_compra`*`tarifa_venta_unidad`)(100+1000))/(100*`tarifa_venta_unidad`) WHERE 1 ";






/*
echo '1 '.md5('presi').'  <br />';
echo '2 '.md5('gestor').'  <br />';
echo '3 '.md5('pat').'  <br />';
echo 'Terminado';
exit();                
*/        

}


function beneficio(){
$sql="SELECT codigo_producto,nombre, precio_ultimo_unidad,precio_ultimo_peso,tarifa_venta_peso,tarifa_venta_unidad,iva FROM pe_productos order by codigo_producto";
$result=$this->db->query($sql);
foreach($result->result() as $k=>$v){
$codigo_producto=$v->codigo_producto;
$precio_ultimo_unidad=$v->precio_ultimo_unidad;
$precio_ultimo_peso=$v->precio_ultimo_peso;
$tarifa_venta_unidad=$v->tarifa_venta_unidad;
$tarifa_venta_peso=$v->tarifa_venta_peso;
$iva=$v->iva;
$nombre=  str_replace('"','´',$v->nombre);
$nombre=  str_replace("'","´",$nombre);
$sql="SELECT codigo_producto FROM pe_productos_mercado WHERE codigo_producto='$codigo_producto'";
$set="SET codigo_producto='$codigo_producto', nombre='$nombre', iva='$iva', precio_ultimo_unidad='$precio_ultimo_unidad', precio_ultimo_peso='$precio_ultimo_peso', tarifa_venta_unidad='$tarifa_venta_unidad', tarifa_venta_peso='$tarifa_venta_peso'";
if($codigo_producto){
if($this->db->query($sql)->num_rows()){
$sql="UPDATE pe_productos_mercado $set WHERE codigo_producto='$codigo_producto'";
} else {
$sql="INSERT INTO pe_productos_mercado $set ";
}
$this->db->query($sql);
}
}
}


function bonusConMasDe2FormasPago(){
$this->load->database();
$sql="SELECT bonu FROM pe_boka WHERE zeis>'2014-12-31' GROUP BY bonu";
$query=$this->db->query($sql);
foreach($query->result() as $k=>$v){
$sql="SELECT bonu,par1 FROM pe_boka WHERE bonu='$v->bonu' and STYP=8 GROUP BY par1";
$query=$this->db->query($sql);
$num=$query->num_rows();
if ($num<2) {

$sql="SELECT bonu,rasa FROM pe_boka WHERE bonu='$v->bonu' and STYP=1";
$query=$this->db->query($sql);
foreach($query->result() as $k1=>$v1)

echo $v1->bonu.'   '.$num.'  '.$v1->rasa.'<br />';
};
}

}

function creacionSTYP6(){
$this->load->database();
$sql="DELETE FROM `pe_bokaStyp6` WHERE 1";
$query=$this->db->query($sql);
$sql="SELECT BONU as bonu FROM `pe_boka` WHERE STYP=1 and ZEIS<'2015-01-01'";
$query=$this->db->query($sql);

foreach($query->result() as $k=>$v){
$bonu=$v->bonu;
$sql="SELECT sum(bt20)+sum(bt30) as importe, mwsa as tipoIva, zeis FROM `pe_boka` WHERE STYP=6 AND bonu='$bonu' and ZEIS<'2015-01-01' GROUP BY MWSA";
$queryVer=$this->db->query($sql);
if($queryVer->num_rows()==0)
{
//echo $bonu.'<br />';
$sql="SELECT sum(bt20)+sum(bt30) as importe, mwsa as tipoIva, zeis FROM `pe_boka` WHERE STYP=2 AND bonu='$bonu' and ZEIS<'2014-04-18' GROUP BY MWSA";
$query2=$this->db->query($sql);
foreach($query2->result() as $k1=>$v1){
$importe1=$v1->importe;
$tipoIva=$v1->tipoIva;
$base1=round($importe1/(1+$tipoIva/10000));
$ivaProd1=$importe1-$base1;
$fecha=$v1->zeis;

// echo $bonu.' '.$fecha.' '.$importe1.' '.$tipoIva.' '.$base1.' '.$ivaProd1.'<br />';
//STYP = 6 
$sql="INSERT INTO `pe_bokaStyp6` SET "
. "`BONU`='$bonu',"
. "`BONU2`='0',"
. "`STYP`='6',"
. "`ABNU`='0',"
. "`WANU`='0',"
. "`BEN1`='0',"
. "`BEN2`='0',"
. "`SNR1`='0',"
. "`GPTY`='0',"
. "`PNAB`='0',"
. "`WGNU`='0',"
. "`BT10`='$base1',"
. "`BT12`='$ivaProd1',"
. "`BT20`='$importe1',"
. "`POS1`='0',"
. "`POS4`='0',"
. "`GEW1`='0',"
. "`BT40`='0',"
. "`MWNU`='3',"
. "`MWTY`='1',"
. "`PRUD`='0',"
. "`PAR1`='0',"
. "`PAR2`='0',"
. "`PAR3`='0',"
. "`PAR4`='0',"
. "`PAR5`='0',"
. "`STST`='0',"
. "`PAKT`='0',"
. "`POS2`='0',"
. "`MWUD`='0',"
. "`BT13`='0',"
. "`RANU`='0',"
. "`RATY`='0',"
. "`BT30`='0',"
. "`BT11`='0',"
. "`POS3`='0',"
. "`GEW2`='0',"
. "`SNR2`='0',"
. "`SNR3`='0',"
. "`VART`='0',"
. "`BART`='0',"
. "`KONU`='0',"
. "`RASA`='0',"
. "`ZAPR`='0',"
. "`ZAWI`='0',"
. "`MWSA`='$tipoIva',"
. "`ZEIS`='$fecha',"
. "`ZEIE`='1970-01-01 00:00:00',"
. "`ZEIB`='0000-00-00 00:00:00',"
. "`TEXT`=''";
$result=$this->db->query($sql);    

}
}

//análisis de la informacion de STYP=8, si no existe se crea
$sql="SELECT sum(bt10) as importe, zeis FROM `pe_boka` WHERE STYP=8 AND bonu='$bonu'  ";
$query8=$this->db->query($sql);
$suma8=$query8->row()->importe;
echo '<br />'.$bonu.' ---> ';

if(!$suma8){
echo $bonu.' '.$suma8.'<br />';
$sql="SELECT bt20 as importe, zeis as fecha FROM `pe_boka` WHERE STYP=1 AND bonu='$bonu' and ZEIS<'2015-01-01' ";
$query2=$this->db->query($sql);
$metalico=$query2->row()->importe;

//STYP = 8 efectivo
if($metalico){
$fecha=$query2->row()->fecha;
$sql="INSERT INTO `pe_bokaStyp6` SET "
. "`BONU`='$bonu',"
. "`BONU2`='0',"
. "`STYP`='8',"
. "`ABNU`='0',"
. "`WANU`='0',"
. "`BEN1`='0',"
. "`BEN2`='0',"
. "`SNR1`='0',"
. "`GPTY`='0',"
. "`PNAB`='0',"
. "`WGNU`='0',"
. "`BT10`='$metalico',"
. "`BT12`='0',"
. "`BT20`='0',"
. "`POS1`='0',"
. "`POS4`='0',"
. "`GEW1`='0',"
. "`BT40`='0',"
. "`MWNU`='0',"
. "`MWTY`='0',"
. "`PRUD`='0',"
. "`PAR1`='1',"
. "`PAR2`='0',"
. "`PAR3`='0',"
. "`PAR4`='0',"
. "`PAR5`='0',"
. "`STST`='0',"
. "`PAKT`='0',"
. "`POS2`='0',"
. "`MWUD`='0',"
. "`BT13`='0',"
. "`RANU`='0',"
. "`RATY`='0',"
. "`BT30`='0',"
. "`BT11`='$metalico',"
. "`POS3`='0',"
. "`GEW2`='0',"
. "`SNR2`='0',"
. "`SNR3`='0',"
. "`VART`='0',"
. "`BART`='0',"
. "`KONU`='0',"
. "`RASA`='0',"
. "`ZAPR`='0',"
. "`ZAWI`='0',"
. "`MWSA`='0',"
. "`ZEIS`='$fecha 00:00:10',"
. "`ZEIE`='1970-01-01 00:00:00',"
. "`ZEIB`='0000-00-00 00:00:00',"
. "`TEXT`=''";
$result=$this->db->query($sql);  

$sql="INSERT INTO `pe_bokaStyp6` SET "
. "`BONU`='$bonu',"
. "`BONU2`='0',"
. "`STYP`='8',"
. "`ABNU`='0',"
. "`WANU`='0',"
. "`BEN1`='0',"
. "`BEN2`='0',"
. "`SNR1`='0',"
. "`GPTY`='0',"
. "`PNAB`='0',"
. "`WGNU`='0',"
. "`BT10`='0',"
. "`BT12`='0',"
. "`BT20`='0',"
. "`POS1`='0',"
. "`POS4`='0',"
. "`GEW1`='0',"
. "`BT40`='0',"
. "`MWNU`='0',"
. "`MWTY`='0',"
. "`PRUD`='0',"
. "`PAR1`='20',"
. "`PAR2`='0',"
. "`PAR3`='0',"
. "`PAR4`='0',"
. "`PAR5`='0',"
. "`STST`='0',"
. "`PAKT`='0',"
. "`POS2`='0',"
. "`MWUD`='0',"
. "`BT13`='0',"
. "`RANU`='0',"
. "`RATY`='0',"
. "`BT30`='0',"
. "`BT11`='0',"
. "`POS3`='0',"
. "`GEW2`='0',"
. "`SNR2`='0',"
. "`SNR3`='0',"
. "`VART`='0',"
. "`BART`='0',"
. "`KONU`='0',"
. "`RASA`='0',"
. "`ZAPR`='0',"
. "`ZAWI`='0',"
. "`MWSA`='0',"
. "`ZEIS`='$fecha 00:00:10',"
. "`ZEIE`='1970-01-01 00:00:00',"
. "`ZEIB`='0000-00-00 00:00:00',"
. "`TEXT`=''";
$result=$this->db->query($sql);  

}
}  


}

}

function verificacionTickets(){
echo 'inicio<br />';
$sql="SELECT bonu as bonu FROM `pe_boka` WHERE STYP=1 group by bonu";
$query=$this->db->query($sql);

foreach($query->result() as $v){
$bonu=$v->bonu;
$sql="SELECT BONU as bonu, sum(bt20)+sum(bt30) as importe2,zeis as fecha FROM `pe_boka` WHERE STYP=2 AND bonu='$bonu' ORDER BY zeis";
$query2=$this->db->query($sql);
$resultado2=$query2->result();

$sql="SELECT BONU as bonu, bt20 as importe1,zeis as fecha,rasa as rasa FROM `pe_boka` WHERE STYP=1 AND bonu='$bonu' ORDER BY zeis";
$query1=$this->db->query($sql);
$resultado1=$query1->result();

$sql="SELECT BONU as bonu, sum(bt20) as importe6,zeis as fecha FROM `pe_boka` WHERE STYP=6 AND bonu='$bonu' ORDER BY zeis";
$query6=$this->db->query($sql);
$resultado6=$query6->result();

$sql="SELECT BONU as bonu, sum(bt20) as importe6b,zeis as fecha FROM `pe_bokaStyp6` WHERE STYP=6 AND bonu='$bonu' ORDER BY zeis";
$query6b=$this->db->query($sql);
$resultado6b=$query6b->result();

$sql="SELECT BONU as bonu, sum(bt10) as importe8,zeis as fecha FROM `pe_boka` WHERE STYP=8 and PAR1<20 AND bonu='$bonu' ORDER BY zeis";
$query8=$this->db->query($sql);
$resultado8=$query8->result();

$sql="SELECT BONU as bonu, sum(bt10) as importe8b,zeis as fecha FROM `pe_boka` WHERE STYP=8 and PAR1=20 AND bonu='$bonu' ORDER BY zeis";
$query8b=$this->db->query($sql);
$resultado8b=$query8b->result();


$sql="SELECT BONU as bonu, sum(bt10) as importe8c,zeis as fecha FROM `pe_bokaStyp6` WHERE STYP=8 and PAR1<20 AND bonu='$bonu' ORDER BY zeis";
$query8c=$this->db->query($sql);
$resultado8c=$query8c->result();

$sql="SELECT BONU as bonu, sum(bt10) as importe8d,zeis as fecha FROM `pe_bokaStyp6` WHERE STYP=8 and PAR1=20 AND bonu='$bonu' ORDER BY zeis";
$query8d=$this->db->query($sql);
$resultado8d=$query8d->result();

//var_dump($resultado);
foreach($resultado1 as $k2=>$v2){
$importe6=$resultado6[$k2]->importe6+$resultado6b[$k2]->importe6b;
$iguales=$resultado1[$k2]->importe1==($resultado2[$k2]->importe2);
$importe8=$resultado8[$k2]->importe8-$resultado8b[$k2]->importe8b+$resultado8c[$k2]->importe8c-$resultado8d[$k2]->importe8d;

$iguales6=$resultado1[$k2]->importe1==$importe6;
$iguales8=$resultado1[$k2]->importe1==$importe8;

if(!$iguales8){
echo 'a'.$iguales.' b'.$iguales6.'  '.$resultado1[$k2]->fecha.'  ';
echo $resultado1[$k2]->bonu.' = '.$resultado1[$k2]->rasa.'  importe1='.$resultado1[$k2]->importe1.'    importe2='.$resultado2[$k2]->importe2.'   importe6='.$resultado6[$k2]->importe6.'   importe6b='.$resultado6b[$k2]->importe6b.'   importe6+importe6b='.$importe6.'   importe8='.$resultado8[$k2]->importe8.'   importe8b='.$resultado8b[$k2]->importe8b.'   importe8+importe8b='.$importe8.'<br />';
}

}




}


echo 'FIN <br />';
}

}

?>
