<?php
class Ventas_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        function getAnalisisVentas($desde,$hasta){
            $sql="SELECT "
                    . " sum(precio_compra) as precio_compre,"
                    . " sum(tarifa_venta) as precio_compre,";
        }

        public function getImportes($BT10,$BT12,$BT20,$BT30,$POS1,$GEW1,$BT13){
                $precioUnitario=0;
                $totalSinDescuento=0;
                $descuento=($BT12-$BT13)?$BT30:0;
                $descuento=($GEW1 AND $BT13)?0:$BT30;
                if($GEW1==0 AND ($BT20*($BT10-$BT12)==($BT30*$BT10))){
                        $precioUnitario =$BT12;
                        $totalSinDescuento=$BT20-$BT30;
                return array('precioUnitario'=>$precioUnitario, 'totalSinDescuento'=>$totalSinDescuento,'descuento'=>$descuento);
                }
                if($GEW1==0 AND ($BT20*($BT10-$BT12)!=($BT30*$BT10))){
                        $precioUnitario =$BT10;
                        $totalSinDescuento=$BT20;
                return array('precioUnitario'=>$precioUnitario, 'totalSinDescuento'=>$totalSinDescuento,'descuento'=>$descuento);
                }
                if($GEW1!=0 AND (round($BT10*$GEW1/1000,0)-round($BT12*$GEW1/1000,0))==$BT30){
                        $precioUnitario =$BT12;
                        $totalSinDescuento=$BT20-$BT30;
                return array('precioUnitario'=>$precioUnitario, 'totalSinDescuento'=>$totalSinDescuento,'descuento'=>$descuento);
                }
                if($GEW1!=0 AND (round($BT10*$GEW1/1000,0)-round($BT12*$GEW1/1000,0))!=$BT30){
                        $precioUnitario =$BT10;
                        $totalSinDescuento=$BT20;
                return array('precioUnitario'=>$precioUnitario, 'totalSinDescuento'=>$totalSinDescuento,'descuento'=>$descuento);
                }
        }
        /* viene de la preparación del ticket
        if($row->GEW1==0){
                //productos vendidos por unidades
                //preciosUnitarios o preciosModificados?
                if($row->BT20*($row->BT10-$row->BT12)==$row->BT30*$row->BT10){
                    $preciosUnitarios[]=formato2decimales($row->BT12/100);
                    $precios[]=formato2decimales(($row->BT20-$row->BT30)/100);
                }
            else{
                $preciosUnitarios[]=formato2decimales($row->BT10/100);
                $precios[]=formato2decimales($row->BT20/100);
            }
            }else{
                //productos vendidos a peso
                if(round($row->BT10*$row->GEW1/1000,0)-round($row->BT12*$row->GEW1/1000,0)==$row->BT30){
                    $preciosUnitarios[]=formato2decimales($row->BT12/100);
                    $precios[]=formato2decimales(($row->BT20-$row->BT30)/100);

                }else{
                    $preciosUnitarios[]=formato2decimales($row->BT10/100);
                    $precios[]=formato2decimales($row->BT20/100);
                }
               
            }
            */ 
}
