<style type="text/css">
    .caja,.col-md-4{
        border:1px solid white;
    }
    label.caja{
        
    }
    .form-horizontal .control-label{
        padding-top: 4px;
        padding-bottom:  10px;
    }
    #error{
        color:red;
        font-weight: bold;
    }
    .moneda{
        text-align: right;
    }
    
    #notas{
       
    }
    th,label{
        text-align: center;
    }
    #datos{
        display:none
    }
    #iva1,#iva2,#iva3{
        text-align: right;
        padding-right: 10px;
    }
    #base1,#base2,#base3{
        text-align: right;
        padding-right: 10px;
    }
    #importe1,#importe2,#importe3,#importeTotal,#baseTotal{
        text-align: right;
        padding-right: 10px;
    }
    
    #metalico,#tarjeta,#aCuenta,#transferencia,#cheque,#importeTotalFormaPago{
        text-align: right;
        padding-right: 10px;
    }
    #importeTotal,#importeTotalFormaPago{
        font-weight: bold;
    }
    th.izda{
        text-align: left;
    }
</style>
<?php
echo '<h3>Entrada datos "Tira" - Generación ticket</h3><hr>';

?>

<div class="container">
    <div class="row">
    <div class="col-lg-6">
        <fieldset>
            

            <div class="row">
                <div class="form-group caja">
                    <?php echo form_label('Fecha cierre: ', '', array('class' => 'col-lg-5 control-label caja',)); ?>
                    <div class="col-lg-4">
                        <?php echo form_input(array('type' => 'date', 'name' => 'fecha', 'id' => 'fecha', 'class' => 'form-control input-sm', 'value' => date('Y-m-d'))) ?>
                        <?php echo form_error('fecha'); ?>
                    </div>
                    <div class='col-lg-2' style="padding-left:0px">
                        <button class='btn btn-default ' id="ir" style="padding:2px 8px 5px 8px">Ir</button>
                    </div>
                    
                </div>
            </div>
            <br /><br />
            <div class="row" id="datos" >
                <table>
                    <tr>
                        <th>Tipo IVA %</th>
                        <th>Base</th>
                        <th>Importe</th>
                    </tr>
                    <tr>
                        <td><input type="text" id="iva1" disabled="disabled"></td>
                        <td><input type="text" id="base1"></td>
                        <td><input type="text" id="importe1" ></td>
                    </tr>
                    <tr>
                        <td><input type="text" id="iva2" disabled="disabled"></td>
                        <td><input type="text" id="base2"></td>
                        <td><input type="text" id="importe2" ></td>
                    </tr>
                    <tr>
                        <td><input type="text" id="iva3" disabled="disabled"></td>
                        <td><input type="text" id="base3"></td>
                        <td><input type="text" id="importe3" ></td>
                    </tr>
                    <tr>
                        <th>Totales</th>
                        <td><input type="text" id="baseTotal" disabled="disabled"></td>
                        <td><input type="text" id="importeTotal" disabled="disabled"></td>
                    </tr>
                    
                    <tr>
                        <th class="izda" style="font-size: 18px;">Formas de Pago</th>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="izda">Metálico</th>
                        <td></td>
                        <td><input type="text" id="metalico" ></td>
                    </tr>
                    <tr>
                        <th class="izda">Tarjeta crédito</th>
                        <td></td>
                        <td><input type="text" id="tarjeta" ></td>
                    </tr>
                    <tr>
                        <th class="izda">A cuenta</th>
                        <td></td>
                        <td><input type="text" id="aCuenta" ></td>
                    </tr>
                    <tr>
                        <th class="izda">Vale</th>
                        <td></td>
                        <td><input type="text" id="vale" ></td>
                    </tr>
                    <tr>
                        <th class="izda">Transferencia</th>
                        <td></td>
                        <td><input type="text" id="transferencia" ></td>
                    </tr>
                    <tr>
                        <th class="izda">Cheque</th>
                        <td></td>
                        <td><input type="text" id="cheque" ></td>
                    </tr>
                    <tr>
                        <th class="izda">Total Forma Pago</th>
                        <td></td>
                        <td><input type="text" id="importeTotalFormaPago" disabled="disabled"></td>
                    </tr>
                    
                    
                </table>
                <br><br>
                <table>
                    <tr>
                        
                        <td >
                            <button class="btn btn-success" id="calcularTotal">Calcular Total</button>
                        </td>
                        <td> </td>
                        <td style="padding-left: 255px">
                            <button class="btn btn-success" id="crearTicket">Crear Ticket</button>
                        </td>
                    </tr>
                </table>
                <br><br>
               
                
            </div>
           
            
            
            
            
            
            
            
        </fieldset>
    </div>
    
            
    </div>
</div>
       
<br />
<br />

<script>
// control menú navegación    
$(document).ready(function(){
  $('.menu').removeClass('btn-primary');
  $('.menu').addClass('btn-default');
  $('#menuTickets').addClass('btn-primary');
  $('#menuEntradaManual').addClass('btn-primary');  
})
</script>

<script>
$(document).ready(function(){
    
    $('#calcularTotal').click(function(){
        var iva1=parseFloat($('#iva1').val())
        var iva2=parseFloat($('#iva2').val())
        var iva3=parseFloat($('#iva3').val())
        
        var base1=parseFloat($('#base1').val())
        var base2=parseFloat($('#base2').val())
        var base3=parseFloat($('#base3').val())
        
        if(isNaN(base1)) base1=0
        if(isNaN(base2)) base2=0
        if(isNaN(base3)) base3=0
        
        var importe1=parseFloat($('#importe1').val())
        var importe2=parseFloat($('#importe2').val())
        var importe3=parseFloat($('#importe3').val())
        
        var metalico=parseFloat($('#metalico').val())
        var tarjeta=parseFloat($('#tarjeta').val())
        var aCuenta=parseFloat($('#aCuenta').val())
        var transferencia=parseFloat($('#transferencia').val())
        var cheque=parseFloat($('#cheque').val())
        var vale=parseFloat($('#vale').val())
        
        if(isNaN(importe1)) importe1=0
        if(isNaN(importe2)) importe2=0
        if(isNaN(importe3)) importe3=0
        
        if(isNaN(metalico)) metalico=0
        if(isNaN(tarjeta)) tarjeta=0
        if(isNaN(aCuenta)) aCuenta=0
        if(isNaN(transferencia)) transferencia=0
        if(isNaN(cheque)) cheque=0
        if(isNaN(vale)) vale=0
        
        
        
        var baseTotal=formateoNumero(base1+base2+base3)
        var importeTotal=formateoNumero(importe1+importe2+importe3)
        var importeTotalFormaPago=formateoNumero(metalico+tarjeta+aCuenta+transferencia+cheque+vale)
        
        
        $('#base1').val(formateoNumero(base1))
        $('#base2').val(formateoNumero(base2))
        $('#base3').val(formateoNumero(base3))
        
        $('#importe1').val(formateoNumero(importe1))
        $('#importe2').val(formateoNumero(importe2))
        $('#importe3').val(formateoNumero(importe3))
       
        $('#metalico').val(formateoNumero(metalico))
        $('#tarjeta').val(formateoNumero(tarjeta))
        $('#aCuenta').val(formateoNumero(aCuenta))
        $('#transferencia').val(formateoNumero(transferencia))
        $('#cheque').val(formateoNumero(cheque))  
        $('#vale').val(formateoNumero(vale))  
        
        $('#baseTotal').val(baseTotal)
        $('#importeTotal').val(importeTotal)
        $('#importeTotalFormaPago').val(importeTotalFormaPago)
        
        if($('#importeTotalFormaPago').val()!=$('#importeTotal').val()){
            alert("NO coinciden los Importes Totales y el Total Forma Pagos.\nRevíselos")
        }
        
    })
    
    $('#crearTicket').click(function(){
        
        if($('#importeTotalFormaPago').val()!=$('#importeTotal').val()){
            alert("NO coinciden los Importes Totales y el Total Forma Pagos.\nRevíselos")
            return false
        }
        
        var importeTotal=$('#importeTotal').val()
        
        var baseTotal=$('#baseTotal').val()
        if(importeTotal=="" || baseTotal==""){
            alert('Los valores Totales NO son válidos.\nPrimero indroduzcalos y Pulsar Calcular Total.\nNo se ha podido crear el Ticket')
            return false;
        }
        var fecha=$('#fecha').val();
        var iva1=parseFloat($('#iva1').val())
        var iva2=parseFloat($('#iva2').val())
        var iva3=parseFloat($('#iva3').val())
        
        var base1=parseFloat($('#base1').val())
        var base2=parseFloat($('#base2').val())
        var base3=parseFloat($('#base3').val())
        
        if(isNaN(base1)) base1=0
        if(isNaN(base2)) base2=0
        if(isNaN(base3)) base3=0
        
        var importe1=parseFloat($('#importe1').val())
        var importe2=parseFloat($('#importe2').val())
        var importe3=parseFloat($('#importe3').val())
        
        if(isNaN(importe1)) importe1=0
        if(isNaN(importe2)) importe2=0
        if(isNaN(importe3)) importe3=0
        
        var metalico=parseFloat($('#metalico').val())
        var tarjeta=parseFloat($('#tarjeta').val())
        var aCuenta=parseFloat($('#aCuenta').val())
        var transferencia=parseFloat($('#transferencia').val())
        var cheque=parseFloat($('#cheque').val())
        var vale=parseFloat($('#vale').val())
        
        if(isNaN(metalico)) metalico=0
        if(isNaN(tarjeta)) tarjeta=0
        if(isNaN(aCuenta)) aCuenta=0
        if(isNaN(transferencia)) transferencia=0
        if(isNaN(cheque)) cheque=0
        if(isNaN(vale)) vale=0
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/crearTicket", 
            data: {fecha:fecha,
                    iva1:iva1,
                    iva2:iva2,
                    iva3:iva3,
                    
                    base1:base1,
                    base2:base2,
                    base3:base3,
                    
                    importe1:importe1,
                    importe2:importe2,
                    importe3:importe3,
                    
                    importeTotal:importeTotal,
                    baseTotal:baseTotal,
                    
                    metalico:metalico,
                    tarjeta:tarjeta,
                    aCuenta:aCuenta,
                    transferencia:transferencia,
                    cheque:cheque,
                    vale:vale,
                },
            success: function(datos){
                alert("Tira introducida en el sistema")
            }
        })
        
        
        
        
    })
    
    $('#ir').click(function(){
        fecha=$('#fecha').val();
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/tickets/existenTickets", 
            data: {fecha:fecha },
            success: function(datos){
                if (datos!=0)
                    alert("Ya existen datos con fecha "+ fecha+".\n No se puede introducir los datos de ninguna 'Tira'"); 
                else{
                   $('#datos').css('display','inline') 
                   
                   $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>"+"index.php/tickets/verTiposIvas", 
                    data: {fecha:fecha }, 
                    success: function(datos){
                        var d=$.parseJSON(datos);
                        //alert(d[0])
                        $('#iva1').attr('value',formateoNumero(d[0]))
                        $('#iva2').attr('value',formateoNumero(d[1]))
                        $('#iva3').attr('value',formateoNumero(d[2]))
                    }
                   }) 
                    
                    
                    
                }
                // var d=$.parseJSON(datos);
            }
        })
        })
        
        
        
        function formateoNumero(numero){
            numero=parseFloat(numero);
            if(isNaN(numero)) numero=0
            numero=numero.toFixed(2)
            return numero
        }
    
    
    
})
</script>