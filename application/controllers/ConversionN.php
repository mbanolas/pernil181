<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class ConversionN extends CI_Controller {
	
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','numeros'));
                $this->load->model('tickets_');	
                $this->load->model('caja_');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                $this->load->model('conversionn_model');
                ini_set('memory_limit', '-1');
                    
        }
        
        function makeConversiones(){
            $resultados=$this->conversionn_model->makeConversiones();
            echo json_encode(array('resultados'=>$resultados,
                    )
                    );
        }
        
        function restaurarConversiones(){
            $inicio=$_POST['inicio'];
            $final=$_POST['final'];
            $resultados=$this->conversionn_model->restaurarConversiones($inicio,$final);
            echo json_encode(array('resultados'=>$resultados,
                    )
                    );
        }
        
        function guardarConversiones(){
            $resultados=$this->conversionn_model->guardarConversiones();
            echo json_encode(array('resultados'=>$resultados,
                    )
                    );
        }
        
        
        function registrarConversiones(){
            $resultados=$this->conversionn_model->registrarConversiones();
            echo json_encode(array('resultados'=>$resultados,
                    )
                    );
        }
        
        function crearConversiones(){
            $inicio=$_POST['inicio'];
            $final=$_POST['final'];
            $resultados=$this->conversionn_model->crearConversiones($inicio,$final);
            
            echo json_encode(array('resultados'=>$resultados,));
        }
        
        function verSalidaConversionN(){
            var_dump($_POST);
        }
        
        function conversionPeriodo() {
            $this->load->model('conversion_model');
            $dato = array();
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);

            $dato['conversiones'] = $this->conversion_model->getConversionesTodas();
            $this->load->view('listaConversiones', $dato);
            
            //obtener información conversiones propuestas
            $dato['cambios'] = $this->conversion_model->getConversiones();

            $dato['seleccionPeriodosAbajo'] = $this->load->view('seleccionResumenConversionPeriodoN', $dato, true);
            $dato['seleccionPeriodosN'] = $this->load->view('seleccionPeriodosN', $dato, true);
            
            $this->load->view('resumenTicketsPeriodoN', $dato);
            
            $this->load->view('templates/footer.html', $dato);
            $this->load->view('myModal', $dato);
        }

    function getResumenTickets(){
            $inicio=$_POST['inicio'];
            $final=$_POST['final'];
            echo json_encode($_POST);
             return;
            //preparar pe_boka2
            //copia TODAS las lineas de pe_boka a pe_boka2 de las fechas 
            $this->conversionn_model->prepararBoka2($inicio,$final);
            
            $datosConversion=$this->conversionn_model->getDatosConversion($inicio, $final);
            
            echo json_encode($datosConversion);
             return;
            
            
            
            //num tickets convertibles (pago metálico, sin cliente, sin devoluciones)
            $numModificables=$this->conversionn_model->getNumTicketsModificablesN($inicio, $final);
             if($numModificables==0) {
                 echo json_encode($numModificables);
                return;
             }
            
             
            //importe bruto tickets convertibles (pago metálico, sin cliente, sin devoluciones)
   ///        $brutoModificables=$this->conversionn_model->getFormatedImporteBrutoModificables($inicio, $final);
            //importe iva tickets convertibles (pago metálico, sin cliente, sin devoluciones)
  ///          $ivaModificables=$this->conversionn_model->getFormatedIvaModificables($inicio, $final);
            
           
            //num tickets todos
  ///          $numTodos=$this->conversionn_model->getNumTicketsTodos($inicio, $final);
            //importe bruto tickets convertibles (pago metálico, sin cliente, sin devoluciones)
  ///          $brutoTodos=$this->conversionn_model->getFormatedImporteBrutoTodos($inicio, $final);
            //importe iva tickets convertibles (pago metálico, sin cliente, sin devoluciones)
  ///          $ivaTodos=$this->conversionn_model->getFormatedIvaTodos($inicio, $final);
            
  ///          $numPorCiento=  $this->conversionn_model->getFormatedNumPorCiento($inicio, $final);
  ///          $brutoPorCiento=  $this->conversionn_model->getFormatedBrutoPorCiento($inicio, $final);
  ///          $ivaPorCiento=  $this->conversionn_model->getFormatedIvaPorCiento($inicio, $final);
            
            //obtener las conversiones definidas
            $cambios=$this->conversionn_model->getConversiones();
            
            $codigosIniciales=$cambios['codigosIniciales'];
            $codigosFinales=$cambios['codigosFinales'];
            $pesos=$cambios['pesos'];
            
            
            $tickets=$this->conversionn_model->getTicketsConvertir($inicio,$final,$codigosIniciales);
            $ticketsFinales=$this->conversionn_model->getTicketsConvertirFinal($pesos,$codigosFinales);
            
            $lineas=$this->conversionn_model->getLineasConvertir($inicio,$final,$ticketsFinales);
            /*
            $datos=array('numTodos'=>$numTodos,'brutoTodos'=>$brutoTodos,'ivaTodos'=>$ivaTodos,
                            'numModificables'=>$numModificables,'brutoModificables'=>$brutoModificables,'ivaModificables'=>$ivaModificables,
                            'numPorCiento'=>$numPorCiento,'brutoPorCiento'=>$brutoPorCiento,'ivaPorCiento'=>$ivaPorCiento,
                   );
            $tablaResumen=$this->tablaResumen($datos);
            
            $datosTabla=$this->conversionn_model->getDatosTabla();
            
            $tabla=$this->tabla2($datosTabla);
            */
            $resultado=$this->tablaLineas($lineas);
            
   ///         $totalIVADiferencia=$resultado['ivaDiferenciaTotal'];
  ///          $totalPvpDiferencia=$resultado['pvpDiferenciaTotal'];
            
   ///         $tablaLineas=$resultado['tablaLineas'];
  ///          $totalIVADiferencia=$resultado['ivaDiferenciaTotal'];
            
            $dato=array();
            $this->load->view('templates/header.html',$dato);
            $this->load->view('templates/top.php');
            $this->load->view('propuestaConversion',$resultado);
            //echo $resultado['tablaLineas'];
            
            
          /*
            echo json_encode(array('num'=>$numModificables, 'bruto'=>$brutoModificables, 'iva'=> $ivaModificables,
                'totalIVADiferencia'=>$totalIVADiferencia,
                'totalPvpDiferencia'=>$totalPvpDiferencia,
                'tabla' =>$tabla, 
                'tablaResumen' =>$tablaResumen,
                'tablaLineas' =>$tablaLineas,
                    )
                    );
            */
           
            
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
                            <th class='tconversion'>Diferencia IVA</th>
                        </tr>
                        </thead>
                        ";
            $tablaLineasCuerpo="<tbody>";
                
                $numLineas=0;
                $pvpTotal=0;
                $ivaTotal=0;
                $pvpFinalTotal=0;
                $ivaFinalTotal=0;
                $pvpDiferenciaTotal=0;
                $ivaDiferenciaTotal=0;
            
                  foreach($lineas as $k=>$v){  
                      if($v['pvpFinal']>0){
                       $ticket=$v['ticket'];
                       $productoInicial=$v['productoInicial'];
                       $pvp=  number_format($v['pvp']/100,2,".","");
                       $iva=number_format($v['iva']/100,2,".","");
                       $productoFinal=$v['productoFinal'];
                       
                       $pvpFinal=number_format($v['pvpFinal']/100,2,".","");
                       $ivaFinal=number_format($v['ivaFinal']/100,2,".","");
                       
                       //$pvpDiferencia=number_format(($v['pvp']-$v['pvpFinal'])/100,2,".",",");
                       $pvpDiferencia=number_format(($v['pvp']-$v['pvpFinal'])/100,2,".","");
                      //$ivaDiferencia=number_format(($v['iva']-$v['ivaFinal'])/100,2,".",",");     
                       $ivaDiferencia=number_format(($v['iva']-$v['ivaFinal'])/100,2,".","");     
                       
                       $strike="";$strikefin="";$noConertible="";
                       if($pvpDiferencia<0) {$strike="<strike>";$strikefin="</strike>";
                            $noConertible="<td class='tconversion'>No convertible - dieferencia negativa</td>";
                       }
                       if($pvpDiferencia>0){
                           $numLineas+=1;
                           $pvpTotal+=$pvp;
                           $ivaTotal+=$iva;
                           $pvpFinalTotal+=$pvpFinal;
                           $ivaFinalTotal+=$ivaFinal;
                           $pvpDiferenciaTotal+=$pvpDiferencia;
                           $ivaDiferenciaTotal+=$ivaDiferencia;
                       }
                       
                        $tablaLineasCuerpo.="<tr>
                            <td class='tconversion'>$strike $ticket $strikefin</td>
                            <td class='tconversion'>$strike $productoInicial $strikefin</td>
                            <td class='tconversion'>$strike $pvp $strikefin</td>
                            <td class='tconversion'>$strike $iva $strikefin</td>
                            <td class='tconversion'></td>
                            <td class='tconversion'>$strike $productoFinal $strikefin</td>
                            <td class='tconversion'>$strike $pvpFinal $strikefin</td>
                            <td class='tconversion'>$strike $ivaFinal $strikefin</td>
                            <td class='tconversion'></td>
                            <td class='tconversion'>$strike $pvpDiferencia $strikefin</td>
                            <td class='tconversion'>$strike $ivaDiferencia $strikefin</td>
                            $noConertible    
                        </tr>";
                      }
                  };
            $tablaLineasCuerpo.="</tbody>";
            
            number_format(($v['iva']-$v['ivaFinal'])/100,2,".",""); 
            $pvpTotal=number_format($pvpTotal,2,".",""); 
            $ivaTotal=number_format($ivaTotal,2,".",""); 
            $pvpFinalTotal=number_format($pvpFinalTotal,2,".",""); 
            $ivaFinalTotal=number_format($ivaFinalTotal,2,".",""); 
            $pvpDiferenciaTotal=number_format($pvpDiferenciaTotal,2,".",""); 
            $ivaDiferenciaTotal=number_format($ivaDiferenciaTotal,2,".",""); 
            
            $tablaLineasPie="
                        <tfoot>
                        <tr>
                            <th class='tconversion'>$numLineas</th>
                            <th class='tconversion'></th>
                            <th class='tconversion'>$pvpTotal</th>
                            <th class='tconversion'>$ivaTotal</th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'>$pvpFinalTotal</th>
                            <th class='tconversion'>$ivaFinalTotal</th>
                            <th class='tconversion'></th>
                            <th class='tconversion'>$pvpDiferenciaTotal</th>
                            <th class='tconversion'>$ivaDiferenciaTotal</th>
                        </tr>
                        </tfoot>
                        ";
            $tablaLineas=$tablaLineasEncabezado.$tablaLineasCuerpo.$tablaLineasPie;
            return array('pvpDiferenciaTotal'=>$pvpDiferenciaTotal,'tablaLineas'=>$tablaLineas,'ivaDiferenciaTotal'=>$ivaDiferenciaTotal);
        }
        
        function tabla($cambios,$tickets,$ticketsFinales,$codigosFinales){
            $tablaEncabezado='<table>
                <thead>
                    <tr>
                    <th class="tconversion">Conversión</th>
                    <th class="tconversion">Lineas</th>
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
            $totalPVP=0;
            $totalIVA=0;
            $totalPVPFinal=0;
            $totalIVAFinal=0;
            $totalPVPDiferencia=0;
            $totalIVADiferencia=0;
            
            foreach($cambios['conversiones'] as $k=>$v){
                    $lineas=$tickets['lineas'][$k];
                    $pvp=  $tickets['pvp'][$k];
                    $iva=$tickets['iva'][$k];
                    $codigoFinal=$codigosFinales[$k];
                    $pvpFinal=$ticketsFinales['precios'][$k]*$lineas;
                    $factorIvaFinal=$ticketsFinales['porcentajesIvas'][$k]/(10000+$ticketsFinales['porcentajesIvas'][$k]);
                    $ivaFinal=$ticketsFinales['precios'][$k]*$lineas*$factorIvaFinal; 
                    $pvpDiferencia=$pvp-$pvpFinal;
                    $ivaDiferencia=$iva-$ivaFinal;
                    
                    
                    if ($cambios['activa'][$k]==1){ 
                       if($pvpDiferencia>0){ 
                    $totalLineas+=$lineas;
                    $totalPVP+=$pvp;
                    $totalIVA+=$iva;
                    $totalPVPFinal+=$pvpFinal;
                    $totalIVAFinal+=$ivaFinal;
                    $totalPVPDiferencia+=$pvpDiferencia;
                    $totalIVADiferencia+=$ivaDiferencia;
                       }
                    
                    }
                    $pvp=number_format($pvp/100,2);
                    $iva=number_format($iva/100,2);
                    $pvpFinal=number_format($pvpFinal/100,2);
                    $ivaFinal=number_format($ivaFinal/100,2);
                    $pvpDiferencia=number_format($pvpDiferencia/100,2);
                    $ivaDiferencia=number_format($ivaDiferencia/100,2);
                    
                    
                    
                    
                    if ($cambios['activa'][$k]==1 ){
                        $strike="";$strikefin="";
                        if($pvpDiferencia<0) {$strike="<strike>";$strikefin="</strike>";}
                    $tablaCuerpo.= "<tr>"
                            . "<td class='tconversion'>$strike $v $strikefin</td>"
                            . "<td class='tconversion'>$strike $lineas $strikefin</td>"
                            . "<td class='tconversion'>$strike $pvp $strikefin</td>"
                            . "<td class='tconversion'>$strike $iva $strikefin</td>"
                            . "<td class='tconversion'></td>"
                            . "<td class='tconversion'>$strike $pvpFinal $strikefin</td>"
                            . "<td class='tconversion'>$strike $ivaFinal $strikefin</td>"
                           . "<td class='tconversion'></td>"
                            . "<td class='tconversion'>$strike $pvpDiferencia $strikefin</td>"
                            . "<td class='tconversion'>$strike $ivaDiferencia $strikefin</td>"
                            . "</tr>";
                    }
                    }     
                   // $totalLineas=number_format($totalLineas,2);
                    
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
                              $tabla=$tablaEncabezado.$tablaCuerpo.$tablaPie;
                              return array('tabla'=>$tabla,'totalIVADiferencia'=>$totalIVADiferencia);
        }
        
        function tabla2($datosTabla){
            $tablaEncabezado='<table>
                <thead>
                    <tr>
                    <th class="tconversion">Conversión</th>
                    <th class="tconversion">Lineas</th>
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
            $totalPVP=0;
            $totalIVA=0;
            $totalPVPFinal=0;
            $totalIVAFinal=0;
            $totalPVPDiferencia=0;
            $totalIVADiferencia=0;
            
            foreach($datosTabla['conversiones'] as $k=>$v){
                
                    /*
                    $lineas=$tickets['lineas'][$k];
                    $pvp=  $tickets['pvp'][$k];
                    $iva=$tickets['iva'][$k];
                    $codigoFinal=$codigosFinales[$k];
                    $pvpFinal=$ticketsFinales['precios'][$k]*$lineas;
                    $factorIvaFinal=$ticketsFinales['porcentajesIvas'][$k]/(10000+$ticketsFinales['porcentajesIvas'][$k]);
                    $ivaFinal=$ticketsFinales['precios'][$k]*$lineas*$factorIvaFinal; 
                    $pvpDiferencia=$pvp-$pvpFinal;
                    $ivaDiferencia=$iva-$ivaFinal;
                    
                    
                    if ($cambios['activa'][$k]==1){ 
                       if($pvpDiferencia>0){ 
                    $totalLineas+=$lineas;
                    $totalPVP+=$pvp;
                    $totalIVA+=$iva;
                    $totalPVPFinal+=$pvpFinal;
                    $totalIVAFinal+=$ivaFinal;
                    $totalPVPDiferencia+=$pvpDiferencia;
                    $totalIVADiferencia+=$ivaDiferencia;
                       }
                    
                    }
                    $pvp=number_format($pvp/100,2);
                    $iva=number_format($iva/100,2);
                    $pvpFinal=number_format($pvpFinal/100,2);
                    $ivaFinal=number_format($ivaFinal/100,2);
                    $pvpDiferencia=number_format($pvpDiferencia/100,2);
                    $ivaDiferencia=number_format($ivaDiferencia/100,2);
                    
                    
                    
                    
                    if ($cambios['activa'][$k]==1 ){
                        $strike="";$strikefin="";
                        if($pvpDiferencia<0) {$strike="<strike>";$strikefin="</strike>";}
                     */  
                    $strike="";
                    $strikefin="";
                    $lineas=$datosTabla['lineas'][$v];
                    $pvp=number_format($datosTabla['pvps'][$v]/100,2);
                    $iva=number_format($datosTabla['ivas'][$v]/100,2);
                    $pvpFinal=number_format($datosTabla['pvpFinales'][$v]/100,2);
                    $ivaFinal=number_format($datosTabla['ivaFinales'][$v]/100,2);
                    $pvpDiferencia=$datosTabla['pvps'][$v]-$datosTabla['pvpFinales'][$v];
                    $pvpDiferencia=number_format($pvpDiferencia/100,2);
                    $ivaDiferencia=$datosTabla['ivas'][$v]-$datosTabla['ivaFinales'][$v];
                    $ivaDiferencia=number_format($ivaDiferencia/100,2);
                    $tablaCuerpo.= "<tr>"
                            . "<td class='tconversion'>$strike $v $strikefin</td>"
                            . "<td class='tconversion'>$strike $lineas $strikefin</td>"
                            . "<td class='tconversion'>$strike $pvp $strikefin</td>"
                            . "<td class='tconversion'>$strike $iva $strikefin</td>"
                            . "<td class='tconversion'></td>"
                            . "<td class='tconversion'>$strike  $pvpFinal $strikefin</td>"
                            . "<td class='tconversion'>$strike  $ivaFinal $strikefin</td>"
                           . "<td class='tconversion'></td>"
                            . "<td class='tconversion'>$strike  $pvpDiferencia $strikefin</td>"
                            . "<td class='tconversion'>$strike  $ivaDiferencia $strikefin</td>"
                            . "</tr>";
                    }
                        
                   // $totalLineas=number_format($totalLineas,2);
                  /*  
                    $totalPVP=number_format($totalPVP/100,2);
                    $totalIVA=number_format($totalIVA/100,2);
                    $totalPVPFinal=number_format($totalPVPFinal/100,2);
                    $totalIVAFinal=number_format($totalIVAFinal/100,2);
                    $totalPVPDiferencia=number_format($totalPVPDiferencia/100,2);
                    $totalIVADiferencia=number_format($totalIVADiferencia/100,2);
                 */
                    
               $tablaPie= "</tbody>
                           <tfoot>
                           <tr>
                    <th class='tconversion'></th>
                    <th class='tconversion'>$totalLineas</th>
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
                $tablaPie= "</tbody>
                           <tfoot>
                           
                           </tfoot>
                            </table>";
               
                              $tabla=$tablaEncabezado.$tablaCuerpo.$tablaPie;
                              return $tabla;
        }
        
        function getTickets(){
         
         $tickets=array();
         $tickets=$this->conversionn_model->getTickets($_POST['inicio'], $_POST['final']);
         
         echo json_encode($tickets);
        }
        
        function tablaResumenEncabezado(){
            
        }
        
        function mostrarTicketModificar($ticket){
                
                //$dato['autor']='Miguel Angel Bañolas';
                //$dato['ticket']=$ticket;
                $ticketsPeriodo=$this->conversionn_model->getTicketsPeriodo();
                $datosTickets=$this->conversionn_model->getDatosTicket($ticket);
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
                $ticketsPeriodo=$this->conversionn_model->getTicketsPeriodo();
                $datosTickets=$this->conversionn_model->getDatosTicket($ticket);
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
            $this->conversionn_model->getDatosTicket($ticket);
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