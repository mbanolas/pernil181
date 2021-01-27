<?php echo $botones ?>
<?php         $ticket2=$ticket; ?>
<hr>
    <h3>Ticket núm <?php echo $ticket['numero'].' '.$ticket['fecha'] ?> </h3>
    <input type="hidden" id="ticketCorregir" value="<?php echo $ticket['numero'] ?>">
    <input type="hidden" id="fechaCorregir" value="<?php echo $ticket['fecha'] ?>">
    <input type="hidden" id="base_url" value="<?php echo base_url() ?>">

    <?php $alternativa=true ?>
 <?php echo form_open('tickets/seleccionaTicketCorregir',array('role'=>'form', 'class'=> 'fechas form-horizontal')) ; ?> 
<input type="hidden" name="ticket" value='<?php echo $ticket['numero'] ?>'>
<div class="row">
    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
                <tr><th colspan="3" class="col-md-12 izquierda"><?php echo $ticket['modo'] ?> - ACTUAL -</th></tr>
                <?php if($ticket['cliente']!=="") { ?>
                <tr><td colspan="3" class="col-md-12 izquierda ">Núm Cliente: <?php echo $ticket['cliente'] ?></td></tr>
                <tr><td colspan="3" class="col-md-12 izquierda "><?php echo $ticket['nombreCliente'] ?></td></tr>
                <tr><td colspan="3" class="col-md-12 izquierda" style=" border-bottom:1px solid black "><?php echo $ticket['empresa'] ?></td></tr>
                
                <?php } ?>
                <tr >
                    <td class="col-md-4 izquierda">Spdto <?php echo $ticket['subDepartamento'] ?></td>
                    <td class="col-md-4 centro">Caja <?php echo $ticket['numCaja'] ?></td>
                    <td class="col-md-4">#<?php echo $ticket['referencia'] ?></td>
                    
                </tr>
                <tr>
                    <td class="col-md-4 izquierda"><?php echo $ticket['fecha'] ?></td>
                    <td class="col-md-4 centro"><?php echo $ticket['numero'] ?>/<?php echo $ticket['numCaja'] ?> </td>
                    <td class="col-md-4">Depe <?php echo $ticket['dependiente'] ?></td>
                    
                </tr>
            </thead>
        </table>    
    </div>
    <!-- Alternativa -->
    <?php if($alternativa) { ?>
    <!--
    <input type="hidden" id="diferenciaPeriodoImportes" name="diferenciaPeriodoImportes" value='<?php echo $this->session->diferenciaPeriodoImportes ?>'>
    <input type="hidden" id="diferenciaPeriodoIvas" name="diferenciaPeriodoIvas" value='<?php echo $this->session->diferenciaPeriodoIvas ?>'>
   -->
    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
                <tr id="tituloAlteracion">
                    <th colspan="3" class="col-md-12 izquierda"><?php echo $ticket2['modo'] ?><span style="color:red"></span><span id="correccion" ></span>
                    </th>
                </tr>
                <?php if($ticket2['cliente']) { ?>
                <tr><td colspan="3" class="col-md-12 izquierda ">Núm Cliente: <?php echo $ticket2['cliente'] ?></td></tr>
                <tr><td colspan="3" class="col-md-12 izquierda "><?php echo $ticket2['nombreCliente'] ?></td></tr>
                <tr><td colspan="3" class="col-md-12 izquierda" style=" border-bottom:1px solid black "><?php echo $ticket2['empresa'] ?></td></tr>
                
                <?php } ?>
                <tr >
                    <td class="col-md-4 izquierda">Spdto <?php echo $ticket2['subDepartamento'] ?></td>
                    <td class="col-md-4 centro">Caja <?php echo $ticket2['numCaja'] ?></td>
                    <td class="col-md-4">#<?php echo $ticket2['referencia'] ?></td>
                    
                </tr>
                <tr>
                    <td class="col-md-4 izquierda"><?php echo $ticket2['fecha'] ?></td>
                    <td class="col-md-4 centro"><?php echo $ticket2['numero'] ?>/<?php echo $ticket2['numCaja'] ?> </td>
                    <td class="col-md-4"><span>Depe <?php echo $ticket2['dependiente'] ?></span>
                       <input type="hidden" id="diferenciaSessionImporte" name="diferenciaSessionImporte" value="<?php echo $this->session->diferenciaPeriodoImportes ?>">
                    </td>
                    <!-- guardamos las variables diferencias Periodo -->
                    

                </tr>
            </thead>
        </table>    
    </div>
    <?php } ?>
</div>


<div class="row">
<?php if ($ticket['piezas']) { ?>
    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
               <tr style="border:2px solid grey;">
                    <th class="col-md-3">Pza.</th>
                    <th class="col-md-3">I.V.A.</th>
                    <th class="col-md-3">€/Pza</th>
                    <th class="col-md-3">€</th>
                </tr> 
            </thead>
            <tbody>
                    <?php foreach ($ticket['unidades_pesos'] as $k => $v) { ?>
                         <?php if ($v =="1" || $v=="3" ){ ?> 
                            <tr>
                                <th colspan="4" class="col-md-12 izquierda"><?php echo $ticket['productos'][$k] ?></th>
                            </tr>
                              <tr>
                                <td class="col-md-3"><?php echo $ticket['unidades'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['tiposIva'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['preciosUnitarios'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['precios'][$k] ?></td>
                            </tr>
                            <?php if ($ticket['descuentos'][$k] !=0) { ?>  
                             <tr>
                                 <td colspan="3" class="col-md-3 izquierda">Su ventaja</td>
                                <td class="col-md-3 "><?php echo $ticket['descuentos'][$k] ?></td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
        </table>    
    </div>
<?php } ?>  
    <!-- Alternativa -->
    <?php if($alternativa) { ?>
<?php if ($ticket['piezas']) { ?>
    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
               <tr style="border:2px solid grey;">
                    <th class="col-md-3">Pza.</th>
                    <th class="col-md-3">I.V.A.</th>
                    <th class="col-md-3">€/Pza</th>
                    <th class="col-md-3">€</th>
                </tr> 
            </thead>
            <tbody>
                    <?php foreach ($ticket2['unidades_pesos'] as $k => $v) { ?>
                         <?php if ($v =="1" || $v=="3" ){ ?> 
                
                            <tr>
                                <th colspan="4" class="col-md-12 izquierda"><?php echo $ticket2['productos'][$k] ?></th>
                            </tr>
                              <tr>
                                <td class="col-md-3 " >
                                    <input class="cantidad derecha" type="text" name="cantidadUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['unidades'][$k] ?>">
                                    <input type="hidden" class="cantidadPart" name="cantidad[<?php echo $k ?>]" value="<?php echo $ticket2['unidades'][$k] ?>">
                                </td>
                                <td class="col-md-3 iva" ><?php echo $ticket2['tiposIva'][$k] ?>
                                   <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['tiposIva'][$k] ?>">
                                </td>
                                <td class="col-md-3 ">
                                    <input class="precio derecha" type="text" name="precioUnitarioUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['preciosUnitarios'][$k] ?>">
                                    <input type="hidden" name="precio[<?php echo $k ?>]" value="<?php echo $ticket2['preciosUnitarios'][$k] ?>">
                                </td>
                                <td class="col-md-3 "  >
                                    <span class="importe"><?php echo $ticket2['precios'][$k] ?></span>
                                   <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['codigosIva'][$k] ?>">
                                    <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['tiposIva'][$k] ?>">
                                    <input type="hidden" name="importe[<?php echo $k ?>]" value="<?php echo $ticket2['precios'][$k] ?>">
                                    <input type="hidden" class="boka" name="preciosBoka[<?php echo $k ?>]" value="<?php echo $ticket['precios'][$k] ?>">
                                    <input type="hidden" class="boka2" name="preciosBoka2[<?php echo $k ?>]" value="<?php echo $ticket2['precios'][$k] ?>">
                                </td>
                            </tr>
                            <?php if ($ticket2['descuentos'][$k] !=0) { ?>  
                             <tr>
                                 <td colspan="3" class="col-md-3 izquierda">Su ventaja</td>
                                 <td class="col-md-3 ">
                                     <span class="descuento importe"><?php echo $ticket['descuentos'][$k] ?></span>
                                    <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['codigosIva'][$k] ?>">
                                    <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['tiposIva'][$k] ?>">
                                    <input type="hidden" name="importe[<?php echo $k ?>]" value="<?php echo $ticket2['descuentos'][$k] ?>">
                                    <input type="hidden" class="boka" name="preciosBoka[<?php echo $k ?>]" value="<?php echo $ticket['descuentos'][$k] ?>">
                                    <input type="hidden" class="boka2" name="preciosBoka2[<?php echo $k ?>]" value="<?php echo $ticket2['descuentos'][$k] ?>">
                                 </td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
        </table>    
    </div>
<?php } ?>  
    <?php } ?>
</div>

<!-- añadir al final productos las entragas negativas cod 999998 -->    
<div class="row">
    <div class="col-md-6">
        <table class="table ticket" >
            <tbody>
                    <?php foreach ($ticket['unidades_pesos'] as $k => $v) { ?>
                        <?php if ( $v=='2') { ?>  
                            <tr>
                                <th colspan="4" class="col-md-12 izquierda"><?php echo ucfirst(strtolower($ticket['productos'][$k])) ?></th>
                            </tr>
                            <tr>
                                <td class="col-md-3"><?php echo $ticket['tiposIva'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['precios'][$k] ?></td>
                            </tr>
                            
                        <?php } ?>
                    <?php } ?>
                </tbody>
        </table>    
    </div>
      
<!-- Alternativa -->
<?php if($alternativa) { ?>
     <div class="col-md-6">
        <table class="table ticket" >
            <tbody>
                    <?php foreach ($ticket['unidades_pesos'] as $k => $v) { ?>
                        <?php if ( $v=='2') { ?>  
                            <tr>
                                <th colspan="4" class="col-md-12 izquierda"><?php echo ucfirst(strtolower($ticket['productos'][$k])) ?></th>
                            </tr>
                            <tr>
                                <td class="col-md-3"><?php echo $ticket['tiposIva'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['precios'][$k] ?></td>
                            </tr>
                            
                        <?php } ?>
                    <?php } ?>
                </tbody>
        </table>    
    </div>
<?php } ?>
 </div>    
<!-- fin añadir al final productos las entragas negativas cod 999998 -->  

 <div class="row">
<?php if ($ticket['pesados']) { ?>

    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
                <tr style="border:2px solid grey;">
                    <th class="col-md-3">kg</th>
                    <th class="col-md-3">I.V.A.</th>
                    <th class="col-md-3">€/kg</th>
                    <th class="col-md-3">€</th>
                </tr> 
            </thead>
            <tbody>
                    <?php foreach ($ticket['unidades_pesos'] as $k => $v) { ?>
                         <?php if ($v =="0" || $v=="4" ){ ?> 
                            <tr>
                                <th colspan="4" class="col-md-12 izquierda"><?php echo $ticket['productos'][$k] ?></th>
                            </tr>
                            <tr>
                                <td class="col-md-3"><?php echo $ticket['pesos'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['tiposIva'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['preciosUnitarios'][$k] ?></td>
                                <td class="col-md-3"><?php echo $ticket['precios'][$k] ?></td>
                            </tr>

                        <?php } ?>
                    <?php } ?>
                </tbody>
        </table>    
    </div>
     <?php } ?>  
<!-- Alternativa -->
<?php if($alternativa) { ?>
    <?php if ($ticket2['pesados']) { ?>

    <div class="col-md-6">
        <table class="table ticket" >
            <thead>
                <tr style="border:2px solid grey;">
                    <th class="col-md-3">kg</th>
                    <th class="col-md-3">I.V.A.</th>
                    <th class="col-md-3">€/kg</th>
                    <th class="col-md-3">€</th>
                </tr> 
            </thead>
          <tbody>
                   <?php foreach ($ticket2['unidades_pesos'] as $k => $v) { ?>
                         <?php if ($v =="0" || $v=="4" ){ ?> 
                
                            <tr>
                                <th colspan="4" class="col-md-12 izquierda"><?php echo $ticket2['productos'][$k] ?></th>
                            </tr>
                              <tr>
                                <td class="col-md-3 " >
                                    <input class="peso derecha" type="text" name="cantidadPeso[<?php echo $k ?>]" value="<?php echo $ticket2['pesos'][$k] ?>">
                                    <input type="hidden" class="pesosPart" name="peso[<?php echo $k ?>]" value="<?php echo $ticket2['pesos'][$k] ?>">
                                </td>
                                    <td class="col-md-3 iva" ><?php echo $ticket2['tiposIva'][$k] ?>
                                   <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['tiposIva'][$k] ?>">
                                </td>
                                <td class="col-md-3 ">
                                    <input class="precio derecha" type="text" name="precioUnitarioUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['preciosUnitarios'][$k] ?>">
                                   <input type="hidden" name="precio[<?php echo $k ?>]" value="<?php echo $ticket2['preciosUnitarios'][$k] ?>">
                                </td>
                                <td class="col-md-3 "  >
                                    <span class="importe"><?php echo $ticket2['precios'][$k] ?></span>
                                   <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['codigosIva'][$k] ?>">
                                    <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['tiposIva'][$k] ?>">
                                    <input type="hidden" name="importe[<?php echo $k ?>]" value="<?php echo $ticket2['precios'][$k] ?>">
                                    <input type="hidden" class="boka" name="preciosBoka[<?php echo $k ?>]" value="<?php echo $ticket['precios'][$k] ?>">
                                    <input type="hidden" class="boka2" name="preciosBoka2[<?php echo $k ?>]" value="<?php echo $ticket2['precios'][$k] ?>">
                                </td>                              </tr>
                            <?php if ($ticket2['descuentos'][$k] !=0) { ?>  
                             <tr>
                                 <td colspan="3" class="col-md-3 izquierda">Su ventaja</td>
                                  <td class="col-md-3 ">
                                     <span class="descuento importe"><?php echo $ticket['descuentos'][$k] ?></span>
                                    <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['codigosIva'][$k] ?>">
                                    <input type="hidden" name="tipoIvaUnidad[<?php echo $k ?>]" value="<?php echo $ticket2['tiposIva'][$k] ?>">
                                    <input type="hidden" name="importe[<?php echo $k ?>]" value="<?php echo $ticket2['descuentos'][$k] ?>">
                                    <input type="hidden" class="boka" name="preciosBoka[<?php echo $k ?>]" value="<?php echo $ticket['descuentos'][$k] ?>">
                                    <input type="hidden" class="boka2" name="preciosBoka2[<?php echo $k ?>]" value="<?php echo $ticket2['descuentos'][$k] ?>">
                                 </td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
        </table>    
    </div>
     <?php } ?>  
<?php } ?>
 </div>


 <div class="row">
    <div class="col-md-6">
        <table class="table ticket" >
            
            <tbody>
                <tr>
                    <th  class="col-md-3 izquierda ticketTotal" >
                        <?php echo $ticket['numPartidasTicket'] ?> Part
                    </th>
                    <th class="col-md-3 centro ticketTotal">
                        Suma
                    </th>
                    <th class="col-md-3 centro ticketTotal">
                        €
                    </th>
                    <th class="col-md-3 destacado" id="importeTotalOriginal"   >
                        <?php echo $ticket['totalTicket'] ?>
                    </th>
                </tr>

                <?php ksort($ticket['formaPago']);foreach($ticket['formaPago'] as $k => $v) { ?>
                <tr>
                    <td colspan="2" class="col-md-3 izquierda">
                        <?php echo $ticket['formaPago'][$k] ?>
                    </td>
                    
                    <td class="col-md-3 centro">
                        <?php echo $ticket['importeFormaPago'][$k]!=""?"€":"" ?>
                    </td>
                    <td class="col-md-3">
                        <?php echo $ticket['importeFormaPago'][$k] ?>
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <td colspan="4" class="col-md-3 izquierda">
                        En la suma se incluye
                    </td>
                  
                </tr>
                <tr>
                    <td class="col-md-3 izquierda">
                    </td>
                    <td class="col-md-3">
                        I.V.A.
                    </td>
                    <td class="col-md-3">
                        Neto
                    </td>
                    <td class="col-md-3">
                        Bruto
                    </td>
                </tr>
                
                <?php 
                    $sumaIvas=0;
                    foreach ($ticket['tipoIvasSum'] as $k => $v) { 
                        $sumaIvas+=numero($ticket['ivas'][$k]);
                    ?>
                
                    <tr>
                        <td colspan="4" class="col-md-3 izquierda"><?php echo $v ?>% <?php echo $ticket['textos'][$k] ?></td>
                    </tr>
                    <tr>
                        <td class="col-md-3"></td>
                        <td class="col-md-3 ivaOriginal"><?php echo $ticket['ivas'][$k] ?></td>
                        <td class="col-md-3 "><?php echo $ticket['netos'][$k] ?></td>
                        <td class="col-md-3 "><?php echo $ticket['brutos'][$k] ?></td>
                    </tr>
                <?php } ?>
                    <tr>
                        <td><input type="hidden" id="totalIvasOriginalBoka" value="<?php echo $sumaIvas ?>"></td>

                    </tr>
                 <?php if ($ticket['sumaIvas']>0){ ?>   
                    <tr>
                        <td class="col-md-3">Suma</td>
                        <td style="border-top:1px solid black" class="col-md-3" id="sumaIvasOriginal"><?php echo $ticket['sumaIvas'] ?></td>
                        <td class="col-md-3"></td>
                        <td class="col-md-3">
                            
                        </td>
                    </tr>
                 <?php } ?>

                <tr>
                    <td colspan="4" class="col-md-3 izquierda"><?php echo $ticket['fechaCierre'] ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="col-md-3 izquierda">ATES PER <?php echo $ticket['nombreDependiente'] ?></td>
                </tr>    
                    
                    
                    
            </tbody>
        </table>    
    </div>
    <!-- Alternativa -->
   <?php if($alternativa) { ?> 
    <div class="col-md-6">
        <table class="table ticket" >
            
            <tbody>
                <tr>
                    <th  class="col-md-3 izquierda ticketTotal" id="part">
                        <span> <?php echo $ticket2['numPartidasTicket'] ?> Part</span>
                        <input type="hidden" id="numPartidasTicket" name="numPartidasTicket" value="<?php echo $ticket2['numPartidasTicket'] ?>">
                    </th>

                    <th class="col-md-3 centro ticketTotal">
                        Suma
                    </th>
                    <th class="col-md-3 centro ticketTotal">
                        €
                    </th>
                    <th class="col-md-3 destacado"  >
                    <span id="importeTotal"><?php echo $ticket2['totalTicket'] ?></span>
                        <input type="hidden" id="importeTotalBoka" class="boka" name="totalTicketBoka" value="<?php echo $ticket['totalTicket'] ?>">
                        <input type="hidden" id="importeTotalBoka2" class="boka2" name="totalTicketBoka2" value="<?php echo $ticket2['totalTicket'] ?>">
                        <input type="hidden"  class="boka2" name="importeTotal" value="<?php echo $ticket2['totalTicket'] ?>">
                    </th>
                </tr>

                <?php ksort($ticket2['formaPago']);foreach($ticket2['formaPago'] as $k => $v) { ?>
                <tr>
                    <td colspan="2" class="col-md-3 izquierda">
                        <span>    <?php echo $ticket2['formaPago'][$k] ?></span>
                        <input type="hidden"  class="boka" name="formaPago[<?php echo $k ?>]" value="<?php echo $ticket['formaPago'][$k] ?>">
                        <input type="hidden"  class="boka2" name="formaPago[<?php echo $k ?>]" value="<?php echo $ticket2['formaPago'][$k] ?>">
                    </td>
                    
                    <td class="col-md-3 centro">
                        <?php echo $ticket2['importeFormaPago'][$k]!=""?"€":"" ?>
                    </td>
                    <td class="col-md-3">
                        <span <?php if($k==1) echo 'class="importeFormaPago"' ?>><?php echo $ticket2['importeFormaPago'][$k] ?></span>
                        <input type="hidden"  <?php if($k==1) echo 'class="importeFormaPago" name="importeFormaPago"' ?> value="<?php echo $ticket2['importeFormaPago'][$k] ?>">
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <td colspan="4" class="col-md-3 izquierda">
                        En la suma se incluye
                    </td>
                  
                </tr>
                <tr>
                    <td class="col-md-3 izquierda">
                    </td>
                    <td class="col-md-3">
                        I.V.A.
                    </td>
                    <td class="col-md-3">
                        Neto
                    </td>
                    <td class="col-md-3">
                        Bruto
                    </td>
                </tr>
                
                <?php 
                    $sumaIvas=0;
                    foreach ($ticket2['tipoIvasSum'] as $k => $v) { 
                        $sumaIvas+=numero($ticket2['ivas'][$k]);
                    ?>
                    <tr>
                        <td colspan="4" class="col-md-3 izquierda"><?php echo $v ?>% <?php echo $ticket2['textos'][$k] ?></td>
                    </tr>
                    <tr>
                        
                        <?php $codigo=$ticket2['codigosIvaSum'][$k] ?>
                        <td class="col-md-3"></td>
                        <td class="col-md-3 iva<?php echo $codigo; ?>"><span><?php echo $ticket2['ivas'][$k] ?></span>
                        <input type="hidden" name="iva[<?php echo $k ?>]" value="<?php echo numero($ticket2['ivas'][$k]) ?>">
                        </td>
                        
                        <td class="col-md-3 neto<?php echo $codigo; ?>"><span><?php echo $ticket2['netos'][$k] ?></span>
                        <input type="hidden" name="neto[<?php echo $k ?>]" value="<?php echo numero($ticket2['netos'][$k]) ?>">
                        </td>
                        
                        <td class="col-md-3 bruto<?php echo $codigo; ?>"><span><?php echo $ticket2['brutos'][$k] ?></span>
                        <input type="hidden" name="bruto[<?php echo $k ?>]" value="<?php echo numero($ticket2['brutos'][$k]) ?>">
                        </td>

                    </tr>
                    
                <?php }  ?>
                    <tr>
                        <td>
                       <!-- <input type="hidden" id="totalIvasOriginalBoka2" value="<?php echo $sumaIvas ?>"> -->
                        <input type="hidden" id="ivaTotalBoka" class="boka" name="totalIvaTicketBoka" value="<?php echo array_sum(arrayNumero($ticket['ivas'])) ?>">
                        <input type="hidden" id="ivaTotalBoka2" class="boka2" name="totalIvaTicketBoka2" value="<?php echo array_sum(arrayNumero($ticket2['ivas'])) ?>">
                        <input type="hidden"  class="boka2" name="ivaTotal" value="<?php echo $ticket2['sumaIvas'] ?>">
                   </td>
                    </tr>
                    
                 <?php if ($ticket2['sumaIvas']>0){ ?>   
                    <tr>
                        <td class="col-md-3">Suma</td>
                        <td style="border-top:1px solid black" class="col-md-3 " id="sumaIvas"><?php echo $ticket2['sumaIvas'] ?></td>
                        <td class="col-md-3"></td>
                        <td class="col-md-3"></td>
                    </tr>
                    
                 <?php } ?>
                    

                <tr>
                    <td colspan="4" class="col-md-3 izquierda"><?php echo $ticket2['fechaCierre'] ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="col-md-3 izquierda">ATES PER <?php echo $ticket2['nombreDependiente'] ?></td>
                </tr>    
                    
                    
                    
            </tbody>
        </table>    
    </div>   
   <?php } ?> 
</div>


<div class="row">
    <div class="col-md-6">
      
    </div>
    <!-- Alternativa -->
   <?php if($alternativa) { ?> 
    <div class="col-md-6">
        <table class="table ticket" >
            
            <tbody>
                <tr class="">
                    <th   colspan="2" class="col-md-3 izquierda ">Diferencia Importe Ticket</th>
                    <th  class="col-md-3" id="diferenciaImportes"><?php echo formato2decimales(numero($ticket2['totalTicket'])-numero($ticket['totalTicket'])) ?></th>

                </tr >

                <tr class="" >
                    <th   colspan="2" class="col-md-3 izquierda ">Diferencia IVA Ticket</th>
                    <th  class="col-md-3 " id="diferenciaIvas"><?php echo formato2decimales(array_sum(arrayNumero($ticket2['ivas']))-array_sum(arrayNumero($ticket['ivas']))) ?></th>
                </tr>
               <!--
                <tr class="destacado">
                    <th  colspan="2" class="col-md-3 izquierda ">Diferencia Importe Periodo</th>
                    <th  class="col-md-3" >
                        <span id="diferenciaImportesPeriodo"><?php echo formato2decimales($this->session->diferenciaPeriodoImportes/100) ?></span>
                        <input type="hidden" id="diferenciaImportesPeriodoSession" name="diferenciaImportesPeriodo" value="<?php echo formato2decimales($this->session->diferenciaPeriodoImportes/100) ?>">
                        <input type="hidden" id="diferenciaImportesPeriodoSessionOriginal"  value="<?php echo formato2decimales($this->session->diferenciaPeriodoImportes/100) ?>">
                    </th>
                     
                </tr >

                <tr class="destacado">
                    <th  colspan="2" class="col-md-3 izquierda ">Diferencia IVA Periodo</th>
                    <th  class="col-md-3" >
                        <span id="diferenciaIvasPeriodo"><?php echo formato2decimales($this->session->diferenciaPeriodoIvas/100) ?></span>
                        <input type="hidden" id="diferenciaIvasPeriodoSession" name="diferenciaIvasPeriodo" value="<?php echo formato2decimales($this->session->diferenciaPeriodoIvas/100) ?>">
                        <input type="hidden" id="diferenciaIvasPeriodoSessionOriginal"  value="<?php echo formato2decimales($this->session->diferenciaPeriodoIvas/100) ?>">
                    </th>
                     
                </tr >
                
                -->
                
                <!--
                
                <tr class="destacado">
                    <th   colspan="2" class="col-md-3 izquierda ">Diferencia IVA Periodo</th>
                    <th  class="col-md-3 " id="diferenciaIvasPeriodo"><?php echo formato2decimales($this->session->diferenciaPeriodoIvas/100) ?></th>
                </tr>
                -->
                
                <tr>
                    
                    <th    class="col-md-3 " style="padding-top:10px">
                        <!--
                        <button style="display: inline;" type="submit" class="btn btn-primary btn-mini" id="recuperarTicketOriginal">
                                <span class="" aria-hidden="true"></span> Recuperar Ticket Original
                        </button>
                        -->
                    </th>
                   
                    <th   colspan="2" class="col-md-3 " style="padding-top:10px" >
                        <!--
                        <button style="display: inline;" type="submit" class="btn btn-danger btn-mini" id="guardarCorrecciones">
                                <span class="" aria-hidden="true"></span> Registrar correcciones de este ticket
                        </button>
                        -->
                    </th>  
                   
                </tr>

            </tbody>
        </table>    
    </div>   
   <?php } ?> 
</div>

        <?php echo \form_close(); ?>

<?php //echo $botones ?>    



<br /><br />
<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTickets').addClass('btn-primary');
  $('#menuCopiaCorregirTicket').addClass('btn-primary');  
})
</script>
<!--
<script>
$(document).ready(function(){
    
    
    var formmodified = 0;
    $('form *').change(function(){
        formmodified = 1;
    });
    window.onbeforeunload = confirmExit;
    function confirmExit() {
        if (formmodified == 1) 
        {
            return "No ha guardado los cambios.";
        }
    }
    
    
    
    $('#recuperarTicketOriginal').click(function(e){
        
        formmodified = 0;
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/recuperarTicket", 
            data: $('form.fechas').serialize(),
            success: function(datos){
                 //recarga de nuevo la página después de haber eliminado el ticket del Boka2, es decir recuperado el del Boka
                 window.location="<?php echo base_url() ?>"+"index.php/tickets/mostrarTicketModificar";
             },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        
        //alert("Hola pendiene de implementar guardar modificaciones");
    })
    
    
   
})
</script>
-->

