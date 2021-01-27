<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Prestashop extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'numeros'));

        // $this->load->model('tickets_');	
        // $this->load->model('caja_');
        // $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

    }

    function documentosSuiza()
    {
       
        $dato = array();
        $dato['pedidosSuiza']=$this->db->query("SELECT o.id,c.firstname,c.lastname,o.fecha,o.total_pedido FROM pe_orders_prestashop o
                                                LEFT JOIN pe_clientes_jamonarium c ON c.id=o.customer_id
                                                WHERE delivery_country='Suiza' ORDER BY pedido DESC")->result();
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('documentosSuiza', $dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
    }

    function calculoBase($total,$iva){
        $iva=$iva/100;
        return $total/(100+$iva);
    }

    function prepararFactura($datos){
            $total=(($datos['total']/100)-($datos['descuento']/100)); //.toFixed(2);
            $totalBase=(($datos['total_base']/100)); //.toFixed(2);
            $totalIva=(($datos['total_iva']/100)); //.toFixed(2);
              
             $fecha=$datos['fecha'].substr(8,2)+'/'+$datos['fecha'].substr(5,2)+'/'+$datos['fecha'].substr(0,4);
             $cabecera="<div class='factura' ><table class='tcab'>";
             $cabecera+="<tr><th class='izda w2'>Pedido</th><td class='izda'>"+$datos['pedido']+"</td></tr>";
             $cabecera+="<tr><th class='izda w2'>Tipo cliente</th><td class='izda'>"+$datos['tipoCliente']+"</td></tr>" ;
             $cabecera+="<tr><th class='izda w2'>Cliente</th><td class='izda'>"+$datos['num_cliente']+" "+$datos['firstname']+" "+$datos['lastname']+" "+$datos['country']+"</td></tr>" ;

             $cabecera+="<tr><th class='izda w2'>Fecha</th><td izda='dcha w1'>"+$fecha+"</td></tr>" ;
             $cabecera+="</table><p>";
             
             $productos="<table class='tlineas'>";
             if($datos['tipoCliente']!=9)
                $productos+="<tr class='cab' ><th>Referencia</th><th>Descripción</th><th>V</th><th>Base price</th><th>Qty</th><th>Total</th><th>VAT Type</th><th>VAT Amount</th><th>Total</th></tr>";
             else 
                $productos+="<tr class='cab' ><th>Referencia</th><th>Descripción</th><th>V</th><th>Base price</th><th>Qty</th><th>Total</th></tr>";
             $infoIvas=[];
           
             $precioIvas=[];
             $totalPrecio=0;
             $anulado=false;
             
             for($i=0;$i<count($datos['lineas']);$i++){
                if($datos['lineas'][$i]['valid']==-1) 
                        $anulado=true ;
                if($datos['lineas'][$i]['esPack']==1)
                        $productos+="<tr style='color:blue' class='linea'>";
                    else
                        $productos+="<tr class='linea'>"    ;
                $productos+="<td>"+$datos['lineas'][$i]['codigo_producto']."</td>" ;
                
                $nombre=$datos['lineas'][$i]['nombre'];
                if($datos['lineas'][$i]['esPack']==1)
                        $nombre=$nombre." (".$datos['lineas'][$i]['cantidad']." Packs)";
                if($datos['lineas'][$i]['esComponentePack']==1) 
                        $nombre="---- ".$nombre    ;
                
                $productos+="<td>".$nombre+"</td><td>".$datos['lineas'][$i]['valid']."</td>" ;
                //$base=($datos['lineas'][i]['importe']/100).toFixed(2)-($datos['lineas'][i]['iva']/100).toFixed(2);
                $precio=$datos['lineas'][$i]['precio']*$datos['lineas'][$i]['cantidad']/100;
                //alert($datos['lineas'][i]['precio'])
                //alert($datos['lineas'][i]['tipoIva'])
                $basePrecio=$this->calculoBase($datos['lineas'][$i]['precio'],$datos['lineas'][$i]['tipoIva']);
                $basePrecio=number_format($basePrecio*100)/100;
                $baseTotal=$basePrecio*$datos['lineas'][$i]['cantidad'];
                
                $tipoIva=$datos['lineas'][$i]['tipoIva']/100;
                $infoIvas[$tipoIva]=$tipoIva;
                
                $varAmount=$precio-$baseTotal;
                $varAmount=number_format($varAmount*100,2)/100;
                
                if(is_null($precioIvas[$tipoIva])) $precioIvas[$tipoIva]=0;
                $precioIvas[$tipoIva]+=$precio;
                if($datos['lineas'][$i]['esPack']==0){
                    $productos+="<td class='dcha'>".$basePrecio." €</td>";
                    $productos+="<td class='dcha'>".$datos['lineas'][$i]['cantidad']."</td>";
                    if($datos['tipoCliente']!=9){
                        $productos+="<td class='dcha'>".number_format($baseTotal,2)."</td>";
                        $productos+="<td class='dcha'>".number_format($tipoIva,2)."%</td>";
                        $productos+="<td class='dcha'>".number_format($varAmount,2)." €</td>";
                     }
                    $productos+="<td class='dcha'>".number_format($precio,2)." €</td>";
                    
                    $totalPrecio+=$precio;
                }
                else 
                    if($datos['tipoCliente']!=9){
                        $productos+="<td></td><td></td><td></td><td></td><td></td><td></td>";
                    }
                    else
                        $productos+="<td></td><td></td><td></td>";

                $productos+="</tr>";
             }
             $descuento=floatval($datos['descuento'])/1;
             $transporte=floatval($datos['transporte'])/10;
             
            // alert(descuento)
            // alert(transporte)
             /*
             if(descuento>transporte){
                descuento=(descuento-transporte)
                transporte=0
                }
              */  
             if($descuento==$transporte){
                $descuento=0;
                $transporte=0;
                }   
             if($descuento!=0){
                $descuento=-$descuento/100;
                if($datos['tipoCliente']!=9)
                        $productos+="<tr class='linea'><td></td><td class='izda descuento'>Descuento</td><td></td><td class='dcha'>1</td><td></td><td></td><td></td><td></td><td class='dcha'>".number_format($descuento,2)." €</td></tr>";
                 else
                     $productos+="<tr class='linea'><td></td><td class='izda descuento'>Descuento</td><td></td><td ></td><td class='dcha'>1</td><td class='dcha'>".number_format($descuento,2)." €</td></tr>";
                }
            $productos+="</table>";
             
             $factor=1+$descuento/$totalPrecio;
             $ivas="<table class='tivas table-responsive'>";
             if($datos['tipoCliente']==9)
                $ivas+="<tr class='ivas'><th class='dcha'>Detalle</th><th width='8'></th><th class='dcha'>Total</th><tr>";
             else
                $ivas+="<tr class='ivas'><th class='dcha'>IVA</th><th class='dcha'>% IVA</th><th class='dcha'>Total sin IVA</th><th class='dcha'>Total IVA</th><th class='dcha'>Total IVA incluido</th><tr>";

            $totalSinIva=0;
            $totalAmount=0;
            $totalIvaIncluido=0;
            // infoIvas.forEach(function(value,index){
                foreach($infoIvas as $index=>$value){
                 //if (value==0) return
                 //if (precioIvas[index]==0) return
                 $base=$precioIvas[$index]*$factor/(1+$index/100);
                 $amount=$precioIvas[$index]*$factor-$base;
                 $ivas+="<tr ><td class='dcha'>Productos</td>";
                 if(is_null($value)) $value=0;
                 if(is_null($base)) $base=0;
                 if(is_null($amount)) $amount=0;
                 
                 if($datos['tipoCliente']!=9){
                    $ivas+="<td class='dcha'>".number_format($value,3)."</td>";
                    $ivas+="<td class='dcha'>".number_format($base,2)." €</td>";
                    $ivas+="<td class='dcha'>".number_format($amount,2)." €</td>";
                 }
                 else{
                    $ivas+="<td class='dcha'></td>" ;
                 }
                 
                 if(is_null($factor)) $factor=0;
                 $ivas+="<td class='dcha'>".number_format($precioIvas[$index]*$factor,2)." €</td></tr>";
                 $totalSinIva+=$base;
                 $totalAmount+=$amount;
                 $totalIvaIncluido+=$precioIvas[$index]*$factor;
             }
             if($transporte!=0){
                 $transporte=$transporte/100;
                 $index=21;
                 if($datos['tipoCliente']==9) $index=0; //es un cliente intercomunitario que no paga iva.
                 $base=$transporte/(1+$index/100);
                 $amount=$transporte-$base;
                 $ivas+="<tr ><td class='dcha'>Transportista</td>";
                 if($datos['tipoCliente']!=9){
                    $ivas+="<td class='dcha'>".number_format($index,3)."</td>";
                    $ivas+="<td class='dcha'>".number_format($base,2)." €</td>";
                    $ivas+="<td class='dcha'>".number_format($amount,2)." €</td>";
                }
                else{
                    $ivas+="<td class='dcha'></td>" ;
                 }
                 $ivas+="<td class='dcha'>".number_format($transporte,2)." €</td></tr>";
                 $totalSinIva+=$base;
                 $totalAmount+=$amount;
                 $totalIvaIncluido+=$transporte;
                 
                 }
             if($datos['tipoCliente']!=9){    
                $ivas+="<tr style='height:5px;border-bottom: 1px solid lightgrey;'><td></td><td></td><td></td><td></td><td></td></tr>";
                $ivas+="<tr style=''><td class='dcha'>Total</td><td></td><td class='dcha'>"+number_format($totalSinIva,2)." €</td><td class='dcha'>".number_format($totalAmount,2)." €</td><td class='dcha'>".number_format($totalIvaIncluido,2)." €</td></tr>";
             }  
             else{
                $ivas+="<tr style='height:5px;border-bottom: 1px solid lightgrey;'><td></td><td></td><td></td></tr>";
                $ivas+="<tr style=''><td class='dcha'>Total</td><td></td><td class='dcha'>".number_format($totalIvaIncluido,2)." €</td></tr>";
             }
             $ivas+="</table>";
             

             $totalFinal="<table class='totalFinal'><tr><td >Total: ".number_format($totalIvaIncluido,2)." €</td></tr></div>";
             return $cabecera.$productos.$ivas.$totalFinal;
    }

    function documentos(){
        $this->load->library('excel');
        $this->load->library('exceldrawing');

        if(isset($_POST['documento_a'])){
            // echo 'Preparando documento A';
            $pedido=$_POST['pedido'];
            $dato['peso']=$_POST['peso'];
            $sql="SELECT o.id,c.firstname,c.lastname,o.fecha,o.total_pedido,o.delivery_address_line_1,o.delivery_address_line_2, o.delivery_postcode,o.delivery_city FROM pe_orders_prestashop o
            LEFT JOIN pe_clientes_jamonarium c ON c.id=o.customer_id
            WHERE delivery_country='Suiza' && o.id='$pedido'";
            mensaje($sql);
            $dato['pedido']=$this->db->query($sql)->row(); 
            
            $this->load->model('compras_model');
            $dato['datos']=$this->compras_model->getPedidoPrestashop($pedido);
            // var_dump($dato['datos']);


            $this->load->view('prepararDocumentoA',$dato);
        }
        if(isset($_POST['documento_b'])){
            $pedido=$_POST['pedido'];
            $sql="SELECT o.id,c.firstname,c.lastname,o.fecha,o.total_pedido,o.delivery_address_line_1,o.delivery_address_line_2, o.delivery_postcode,o.delivery_city FROM pe_orders_prestashop o
            LEFT JOIN pe_clientes_jamonarium c ON c.id=o.customer_id
            WHERE delivery_country='Suiza' && o.id='$pedido'";
            mensaje($sql);
            $dato['pedido']=$this->db->query($sql)->row();

            

            $this->load->view('prepararDocumentoB',$dato);
        }
        if(isset($_POST['documento_c'])){
            $pedido=$_POST['pedido'];
            $peso=$_POST['peso'];
            $sql="SELECT o.id,c.firstname,c.lastname,o.fecha,o.total_pedido,o.delivery_address_line_1,o.delivery_address_line_2, o.delivery_postcode,o.delivery_city FROM pe_orders_prestashop o
            LEFT JOIN pe_clientes_jamonarium c ON c.id=o.customer_id
            WHERE delivery_country='Suiza' && o.id='$pedido'";
            mensaje($sql);
            $dato['pedido']=$this->db->query($sql)->row();
            $this->load->view('prepararDocumentoC',$dato);

        }
        $dato = array();
        
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('templates/footer.html');
        $this->load->view('myModal.php');
    }
}
