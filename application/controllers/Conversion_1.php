<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class Conversion extends CI_Controller {
	
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros'));
                $this->load->model('tickets_');	
                $this->load->model('caja_');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                $this->load->model('conversion_model');
                    
        }
        
        function makeConversiones(){
            $resultados=$this->conversion_model->makeConversiones();
            echo json_encode(array('resultados'=>$resultados,
                    )
                    );
        }
        
        function registrarConversiones(){
            $resultados=$this->conversion_model->registrarConversiones();
            echo json_encode(array('resultados'=>$resultados,
                    )
                    );
        }
        
        
        
        
        function conversionPeriodo(){
            $dato=array();
            $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
                //obtener información conversiones propuestas
                 $dato['cambios']=$this->conversion_model->getConversiones();
                 
                 
                // var_dump($dato['cambios']['codigosIniciales']);
                // $dato['lineasCambios']=$this->conversion_model->getLineasConversiones();
                //$dato['seleccionPeriodosDerecha']=$this->load->view('seleccionResumenConversionPeriodo',$dato,true);
                 
                 
                $dato['seleccionPeriodosAbajo']=$this->load->view('seleccionResumenConversionPeriodo',$dato,true);
               
                $dato['seleccionPeriodos']=$this->load->view('seleccionPeriodos',$dato,true);
		$this->load->view('resumenTicketsPeriodo',$dato );
                $this->load->view('templates/footer.html',$dato);
                 
        }
        
        function conversionPeriodo_2(){
            $dato=array();
            $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
                
                //obtener información conversiones propuestas
                $dato['conversiones']=$this->conversion_model->getConversionesTodas();
                
                
                //$this->load->view('seleccionResumenConversionPeriodo_2',$dato);
                 
               
                $dato['seleccionPeriodos']=$this->load->view('seleccionPeriodosConversiones',$dato,true);
                $this->load->view('resumenTicketsPeriodo2',$dato );
                $dato['seleccionPeriodosAbajo']=$this->load->view('seleccionResumenConversionPeriodo',$dato);

                $this->load->view('templates/footer.html',$dato);
                 
        }
        
        function getResumenTickets(){
            $inicio=$_POST['inicio'];
            $final=$_POST['final'];
              
            //num tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $numModificables=$this->conversion_model->getNumTicketsModificables($inicio, $final);
            //importe bruto tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $brutoModificables=$this->conversion_model->getFormatedImporteBrutoModificables($inicio, $final);
            //importe iva tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $ivaModificables=$this->conversion_model->getFormatedIvaModificables($inicio, $final);
            
 /*          
            //num tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $numTodos=$this->conversion_model->getNumTicketsTodos($inicio, $final);
            //importe bruto tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $brutoTodos=$this->conversion_model->getFormatedImporteBrutoTodos($inicio, $final);
            //importe iva tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $ivaTodos=$this->conversion_model->getFormatedIvaTodos($inicio, $final);
            
            $numPorCiento=  $this->conversion_model->getFormatedNumPorCiento($inicio, $final);
            $brutoPorCiento=  $this->conversion_model->getFormatedBrutoPorCiento($inicio, $final);
            $ivaPorCiento=  $this->conversion_model->getFormatedIvaPorCiento($inicio, $final);
            
            
            $cambios=$this->conversion_model->getConversiones();
            $codigosIniciales=$cambios['codigosIniciales'];
            $codigosFinales=$cambios['codigosFinales'];
            $pesos=$cambios['pesos'];
            
            $tickets=$this->conversion_model->getTicketsConvertir($inicio,$final,$codigosIniciales);
            $ticketsFinales=$this->conversion_model->getTicketsConvertirFinal($pesos,$codigosFinales);
            
            $lineas=$this->conversion_model->getLineasConvertir($inicio,$final,$ticketsFinales);
            
            $datos=array('numTodos'=>$numTodos,'brutoTodos'=>$brutoTodos,'ivaTodos'=>$ivaTodos,
                            'numModificables'=>$numModificables,'brutoModificables'=>$brutoModificables,'ivaModificables'=>$ivaModificables,
                            'numPorCiento'=>$numPorCiento,'brutoPorCiento'=>$brutoPorCiento,'ivaPorCiento'=>$ivaPorCiento,
                   );
            $tablaResumen=$this->tablaResumen($datos);
            
            $resultado=$this->tabla($cambios,$tickets,$ticketsFinales,$codigosFinales);
            $tabla=$resultado['tabla'];
            $totalIVADiferencia=$resultado['totalIVADiferencia'];
            
            $tablaLineas=$this->tablaLineas($lineas);
  */           
            echo json_encode(array('num'=>$numModificables, 'bruto'=>$brutoModificables, 'iva'=> $ivaModificables,
               // 'totalIVADiferencia'=>$totalIVADiferencia,
               // 'tabla' =>$tabla, 
               // 'tablaResumen' =>$tablaResumen,
               // 'tablaLineas' =>$tablaLineas,
                    )
                    );
            
        }
        
        function getResumenTickets2(){
            $inicio=$_POST['inicio'];
            $final=$_POST['final'];
              
            //num tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $numModificables=$this->conversion_model->getNumTicketsModificables($inicio, $final);
            //importe bruto tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $brutoModificables=$this->conversion_model->getFormatedImporteBrutoModificables($inicio, $final);
            //importe iva tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $ivaModificables=$this->conversion_model->getFormatedIvaModificables($inicio, $final);
            
           
            //num tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $numTodos=$this->conversion_model->getNumTicketsTodos($inicio, $final);
            //importe bruto tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $brutoTodos=$this->conversion_model->getFormatedImporteBrutoTodos($inicio, $final);
            //importe iva tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $ivaTodos=$this->conversion_model->getFormatedIvaTodos($inicio, $final);
            
            $numPorCiento=  $this->conversion_model->getFormatedNumPorCiento($inicio, $final);
            $brutoPorCiento=  $this->conversion_model->getFormatedBrutoPorCiento($inicio, $final);
            $ivaPorCiento=  $this->conversion_model->getFormatedIvaPorCiento($inicio, $final);
            
            
            $cambios=$this->conversion_model->getConversiones();
            $codigosIniciales=$cambios['codigosIniciales'];
            $codigosFinales=$cambios['codigosFinales'];
            $pesos=$cambios['pesos'];
            
            $tickets=$this->conversion_model->getTicketsConvertir($inicio,$final,$codigosIniciales);
            $ticketsFinales=$this->conversion_model->getTicketsConvertirFinal($pesos,$codigosFinales);
            
            $lineas=$this->conversion_model->getLineasConvertir($inicio,$final,$ticketsFinales);
            
            $datos=array('numTodos'=>$numTodos,'brutoTodos'=>$brutoTodos,'ivaTodos'=>$ivaTodos,
                            'numModificables'=>$numModificables,'brutoModificables'=>$brutoModificables,'ivaModificables'=>$ivaModificables,
                            'numPorCiento'=>$numPorCiento,'brutoPorCiento'=>$brutoPorCiento,'ivaPorCiento'=>$ivaPorCiento,
                   );
            $tablaResumen=$this->tablaResumen($datos);
            
            $resultado=$this->tabla($cambios,$tickets,$ticketsFinales,$codigosFinales);
            $tabla=$resultado['tabla'];
            $totalIVADiferencia=$resultado['totalIVADiferencia'];
            
            $tablaLineas=$this->tablaLineas($lineas);
            
            
            echo json_encode(array('num'=>$numModificables, 'bruto'=>$brutoModificables, 'iva'=> $ivaModificables,
                'totalIVADiferencia'=>$totalIVADiferencia,
                'tabla' =>$tabla, 
                'tablaResumen' =>$tablaResumen,
                'tablaLineas' =>$tablaLineas,
                    )
                    );
            
            
            
        }
        
        function tablaResumen($datos){
            $numTodos=$datos['numTodos'];
            $brutoTodos=$datos['brutoTodos'];
            $ivaTodos=$datos['ivaTodos'];
            
             $numModificables=$datos['numModificables'];
              $brutoModificables=$datos['brutoModificables'];
               $ivaModificables=$datos['ivaModificables'];
           
               $numPorCiento=$datos['numPorCiento'];
              $brutoPorCiento=$datos['brutoPorCiento'];
               $ivaPorCiento=$datos['ivaPorCiento'];
            
            $tablaResumenEncabezado='<table>
                <thead>
                    <tr>
                    <th class="tconversion"></th>
                    <th class="tconversion">Tickeks</th>
                    <th class="tconversion">PVP </th>
                    <th class="tconversion">IVA </th>
                    
                    </tr>
                </thead>
                <tbody>';
            
            $tablaResumenCuerpo= '
                <tr>
                    <th class="tconversion">Todos </th>
                    <th class="tconversion">'.$numTodos.'</th>
                    <th class="tconversion">'.$brutoTodos.'</th>
                    <th class="tconversion">'.$ivaTodos.'</th>
                    </tr>
                    <tr>
                    <th class="tconversion">Modificables</th>
                    <th class="tconversion">'.$numModificables.'</th>
                    <th class="tconversion">'.$brutoModificables.'</th>
                    <th class="tconversion">'.$ivaModificables.'</th>
                    </tr>
                    <tr>
                    <td class="tconversion">%</td>
                    <td class="tconversion">'.$numPorCiento.'</td>
                    <td class="tconversion">'.$brutoPorCiento.'</td>
                    <td class="tconversion">'.$ivaPorCiento.'</td>
                    </tr>';
            
            $tablaResumenPie= '</tbody>
                           <tfoot>
                           <tr>
                    <th class="tconversion"></th>
                    <th class="tconversion">'.''.'</th>
                    <th class="tconversion">'.''.'</th>
                    <th class="tconversion">'.''.'</th>
                    </tr>
                           </tfoot>
                            </table>';
            $tablaResumen=$tablaResumenEncabezado.$tablaResumenCuerpo.$tablaResumenPie;
            return $tablaResumen;
        }
        
        function tablaLineas($lineas){
             $tablaLineasEncabezado="<table>
                        <thead>
                        <tr>
                            <th class='tconversion'>Ticket</th>
                            <th class='tconversion'>Producto inicial</th>
                            <th class='tconversion'>PVP inicial</th>
                            <th class='tconversion'>IVA inicial</th>
                            <th class='tconversion'><span style='color:#f2f2f2'>---</span></th>
                            <th class='tconversion'>Producto final</th>
                            <th class='tconversion'>PVP final</th>
                            <th class='tconversion'>IVA final</th>
                            <th class='tconversion'><span style='color:#f2f2f2'>---</span></th>
                            <th class='tconversion'>Diferencia PVP</th>
                            <th class='tconversion'>Diferencia IVA_</th>
                        </tr>
                        </thead>
                        ";
            $tablaLineasCuerpo="<tbody>";
                  foreach($lineas as $k=>$v){  
                      if($v['pvpFinal']>0){
                       $ticket=$v['ticket'];
                       $productoInicial=$v['productoInicial'];
                       $pvp=  number_format($v['pvp']/100,2,".",",");
                       $iva=number_format($v['iva']/100,2,".",",");
                       $productoFinal=$v['productoFinal'];
                       
                       $pvpFinal=number_format($v['pvpFinal']/100,2,".",",");
                       $ivaFinal=number_format($v['ivaFinal']/100,2,".",",");
                       
                       $pvpDiferencia=number_format(($v['pvp']-$v['pvpFinal'])/100,2,".",",");
                       $ivaDiferencia=number_format(($v['iva']-$v['ivaFinal'])/100,2,".",",");       
                       
                       
                        $tablaLineasCuerpo.="<tr>
                            <td class='tconversion'>$ticket</td>
                            <td class='tconversion'>$productoInicial</td>
                            <td class='tconversion'>$pvp</td>
                            <td class='tconversion'>$iva</td>
                            <td class='tconversion'></td>
                            <td class='tconversion'>$productoFinal</td>
                            <td class='tconversion'>$pvpFinal</td>
                            <td class='tconversion'>$ivaFinal</td>
                            <td class='tconversion'></td>
                            <td class='tconversion'>$pvpDiferencia</td>
                            <td class='tconversion'>$ivaDiferencia</td>
                                
                        </tr>";
                      }
                  };
            $tablaLineasCuerpo.="</tbody>";
            $tablaLineasPie="
                        <tfoot>
                        <tr>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                        </tr>
                        </tfoot>
                        ";
            $tablaLineas=$tablaLineasEncabezado.$tablaLineasCuerpo.$tablaLineasPie;
            return $tablaLineas;
        }
        
        function tabla($cambios,$tickets,$ticketsFinales,$codigosFinales){
            //encabezado
            if(true){
            $tablaEncabezado='<table>
                <thead>
                    <tr>
                    <th class="tconversion">Conversión</th>
                    <th class="tconversion">Lineas</th>
                    <th class="tconversion">Part</th>
                    <th class="tconversion">PVP inicial</th>
                    <th class="tconversion">IVA inicial</th>
                    <th class="tconversion"><span style="color:#f2f2f2">---</span></th>
                    <th class="tconversion">PVP final</th>
                    <th class="tconversion">IVA final</th>
                    <th class="tconversion"><span style="color:#f2f2f2">---</span></th>
                    <th class="tconversion">PVP diferencia</th>
                    <th class="tconversion">IVA diferencia tabla</th>
                    </tr>
                </thead>
                <tbody>';
            
            $tablaCuerpo="";
            $totalLineas=0;
            $totalPartidas=0;
            $totalPVP=0;
            $totalIVA=0;
            $totalPVPFinal=0;
            $totalIVAFinal=0;
            $totalPVPDiferencia=0;
            $totalIVADiferencia=0;
            }
            //cuerpo
            
            foreach($cambios['conversiones'] as $k=>$v){
                    $lineas=$tickets['lineas'][$k];
                    $partidas=$tickets['partidas'][$k]?$tickets['partidas'][$k]:"0";
                    $pvp=  $tickets['pvp'][$k];
                    $iva=$tickets['iva'][$k];
                    $codigoFinal=$codigosFinales[$k];
                    $pvpFinal=$ticketsFinales['precios'][$k]*$partidas; //$lineas;
                    $factorIvaFinal=(10000+$ticketsFinales['porcentajesIvas'][$k]);
                    $ivaFinal= number_format($ticketsFinales['precios'][$k]/$factorIvaFinal*1000,0)*$partidas;//$lineas;
                    $pvpDiferencia=$pvp-$pvpFinal;
                    $ivaDiferencia=$iva-$ivaFinal;
                    
                    if ($cambios['activa'][$k]==1 ){ 
                        
                    if($ivaDiferencia>=0)   { 
                    $totalLineas+=$lineas;
                    $totalPartidas+=$partidas;
                    $totalPVP+=$pvp;
                    $totalIVA+=$iva;
                    $totalPVPFinal+=$pvpFinal;
                    $totalIVAFinal+=$ivaFinal;
                    $totalPVPDiferencia+=$pvpDiferencia;
                    $totalIVADiferencia+=$ivaDiferencia;
                    }
                    
                    $pvp=number_format($pvp/100,2);
                    $iva=number_format($iva/100,2);
                    $pvpFinal=number_format($pvpFinal/100,2);
                    $ivaFinal=number_format($ivaFinal/100,2);
                    $pvpDiferencia=number_format($pvpDiferencia/100,2);
                    $ivaDiferencia=number_format($ivaDiferencia/100,2);
                    
                    
                    
                    
                  //  if ($cambios['activa'][$k]==1 && $ivaDiferencia>0){ 
                    $strike="";
                    $finStrike="";
                    if ($ivaDiferencia<0) {$strike='<strike>'; $finStrike='</strike>';}
                    $tablaCuerpo.= " <tr >"
                            . "<td class='tconversion'>$strike$v$finStrike</td>"
                            . "<td class='tconversion'>$strike$lineas$finStrike</td>"
                            . "<td class='tconversion'>$strike$partidas$finStrike</td>"
                            . "<td class='tconversion'>$strike$pvp$finStrike</td>"
                            . "<td class='tconversion'>$strike$iva$finStrike</td>"
                            . "<td class='tconversion'></td>"
                            . "<td class='tconversion'>$strike$pvpFinal$finStrike</td>"
                            . "<td class='tconversion'>$strike$ivaFinal$finStrike</td>"
                           . "<td class='tconversion'></td>"
                            . "<td class='tconversion'>$strike$pvpDiferencia$finStrike</td>"
                            . "<td class='tconversion'>$strike $ivaDiferencia $finStrike</td>"
                            . "</tr>";
                    
                         
                   // $totalLineas=number_format($totalLineas,2);
                    
                    }  
            }
            
            //pie
            if(true ){
               $totalPVP=number_format($totalPVP/100,2);
                $totalIVA=number_format($totalIVA/100,2);
                $totalPVPFinal=number_format($totalPVPFinal/100,2);
                $totalIVAFinal=number_format($totalIVAFinal/100,2);
                $totalPVPDiferencia=number_format($totalPVPDiferencia/100,2);
                $totalIVADiferencia=number_format($totalIVADiferencia/100,2); 
                
               $tablaPie= "</tbody>
                           <tfoot>
                           <tr>
                    <th class='tconversion'></th>
                    <th class='tconversion'>$totalLineas</th>
                    <th class='tconversion'>$totalPartidas</th>
                    <th class='tconversion'>$totalPVP</th>
                    <th class='tconversion'>$totalIVA</th>
                    <th class='tconversion'></th>    
                    <th class='tconversion'>$totalPVPFinal</th>
                    <th class='tconversion'>$totalIVAFinal</th>
                    <th class='tconversion'></th>    
                    <th class='tconversion'>$totalPVPDiferencia</th>
                    <th class='tconversion'>$totalIVADiferencia</th>
                    </tr>
                           </tfoot>
                            </table>";
            }
            
           $tabla=$tablaEncabezado.$tablaCuerpo.$tablaPie;
           return array('tabla'=>$tabla,'totalIVADiferencia'=>$totalIVADiferencia);
        }
        
        function getTickets(){
         
         $tickets=array();
         $tickets=$this->conversion_model->getTickets($_POST['inicio'], $_POST['final']);
         
         echo json_encode($tickets);
        }
        
        function tablaResumenEncabezado(){
            
        }
        
        function mostrarTicketModificar($ticket){
                
                //$dato['autor']='Miguel Angel Bañolas';
                //$dato['ticket']=$ticket;
                $ticketsPeriodo=$this->conversion_model->getTicketsPeriodo();
                $datosTickets=$this->conversion_model->getDatosTicket($ticket);
                $datos['ticket']=$datosTickets['ticket'];
                $datos['ticket2']=$datosTickets['ticket2'];
                $datos['ticketsPeriodo']=$ticketsPeriodo;
                $datos['primero']=1;
                $datos['posicion']=$ticket;
                $datos['ultimo']=sizeof($ticketsPeriodo);
                $datos['totalNumTickets']=sizeof($ticketsPeriodo);
                //$datos['contenido']=$this->load->view('mostrarTicketConversion',$datos );
               // $dato['botones']=$this->load->view('botonesTicketsModificarNavegar',$dato,true );
                $this->load->view('templates/header.html');
                $this->load->view('templates/top.php');
                $this->load->view('botonesTicketsModificarNavegar',$datos );
		$this->load->view('mostrarTicketConversion',$datos );
                $this->load->view('templates/footer.html');
        }
        
        function mostrarTicketModificarAjax(){
                $ticket=$_POST['numTicket'];
                //$dato['autor']='Miguel Angel Bañolas';
                //$dato['ticket']=$ticket;
                $ticketsPeriodo=$this->conversion_model->getTicketsPeriodo();
                $datosTickets=$this->conversion_model->getDatosTicket($ticket);
                $datos['ticket']=$datosTickets['ticket'];
                $datos['ticket2']=$datosTickets['ticket2'];
                $datos['ticketsPeriodo']=$ticketsPeriodo;
                $datos['primero']=1;
                $datos['posicion']=$ticket;
                $datos['ultimo']=sizeof($ticketsPeriodo);
                $datos['totalNumTickets']=sizeof($ticketsPeriodo);
                //$datos['contenido']=$this->load->view('mostrarTicketConversion',$datos );
               // $dato['botones']=$this->load->view('botonesTicketsModificarNavegar',$dato,true );
                
		$salida=$this->load->view('mostrarTicketConversionAjax',$datos );
                
                
                echo json_encode(utf8_encode($salida));
        }
        
        
        
        function getDatosTicket($ticket){
            $this->conversion_model->getDatosTicket($ticket);
        }
        
        
         function mostrarTicketModificar_()
        {
            if (isset($_POST['periodo']))
                 $this->session->set_userdata('periodo',$_POST['periodo']);
             if (isset($_POST['inicio']))
                 $this->session->set_userdata('inicio',$_POST['inicio']);
             if (isset($_POST['final']))
                 $this->session->set_userdata('final',$_POST['final']);
             if (isset($_POST['tickets']))
                 $this->session->set_userdata('tickets',$_POST['tickets']);
             if (isset($_POST['ticketsPeriodo'])){
                 $this->session->set_userdata('ticketsPeriodo',$_POST['ticketsPeriodo']);
             }
            
            
                $dato['autor']='Miguel Angel Bañolas';
                $dato=array('error' => ' ' );
               // $numTicket=$this->session->ticketsPeriodo[$this->session->tickets];//$_POST['tickets'];
                $numTicket=$this->session->tickets;
                // se busca en $this->session->ticketsPeriodo
                foreach($this->session->ticketsPeriodo as $k=>$v)
                    if (strpos($v,$this->session->tickets)===0){
                        $posicion=$k;
                        break;
                    }
                $dato['numTicket']=$numTicket;
                $dato['posicion']=$posicion;
                
                
                
                $dato['ticket']=$this->tickets_->getTicketPorNumero($dato['numTicket'],"pe_boka");
                $dato['ticket2']=$this->tickets_->getTicketPorNumero($dato['numTicket'],"pe_boka2");
               
               // var_dump($dato['ticket']);
               // var_dump($dato['ticket2']);
                
                $dato['botones']=$this->load->view('botonesTicketsModificar',$dato,true );;
                $this->load->view('templates/header.html',$dato);
                $this->load->view('templates/top.php',$dato);
		$this->load->view('mostrarTicketModificar',$dato );
                $this->load->view('templates/footer.html',$dato);
        }   
        
        
}