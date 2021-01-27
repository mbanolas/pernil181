<?php
echo '<h3>Estudios Mercado Productos - Entrada de datos</h3><hr>';

foreach($codigos_productos_em as $k=>$v){
   $codigos_productos[$k]=$v;
}
$codigos_productos[0]="Producto sin código existente";
ksort($codigos_productos);
$options = $codigos_productos;

foreach($codigos_productos_em_nombre as $k=>$v){
   $codigos_productos_nombre[$k]=$v;
}

$codigos_productos_nombre[0]="Producto sin código existente";
//sort($codigos_productos_nombre);
$options_nombre = $codigos_productos_nombre;



?>

<div class="container">
    <div class="col-lg-8">
        <fieldset>
            <?php echo form_open('','0',array('class' => 'codigo')); ?>
            <div class="row">
                <div class="form-group">
                    <?php echo form_label('Código producto: ', '', array('class' => 'col-lg-3 control-label caja',)); ?>
                    <div >
                        <?php echo form_dropdown('codigo_producto', $options,'0',array('id'=>'codigo_producto'));?>

                        <?php echo form_error('fecha'); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <?php echo form_label('', '', array('class' => 'col-lg-3 control-label caja',)); ?>
                    <div >
                        <?php echo form_dropdown('codigo_producto_nombre', $options_nombre,'',array('id'=>'codigo_producto_nombre'));?>

                        <?php echo form_error('fecha'); ?>
                    </div>
                </div>
                
                
            </div>
           <div class="row">
                    <div class="col-xs-offset-3 col-lg-8">
                        <button type="submit"  class="btn btn-success submit" id="entrarDatos"  >Entrar datos</button>
                    </div>
           </div>   
                <?php echo form_close(); ?>
            
        </fieldset>
    </div>
</div>
<hr>
<div class="container" style="display:none" id="informacion">
    
    
    
        <div class="col-lg-12 " style="display:inline">
            <div class="row">
            <?php echo form_label('Código producto: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_label('', '', array('class' => 'col-lg-2 control-label codigo_producto','id'=>'codigo_grabar')); ?>
           </div>
            <div class="row" >
            <?php echo form_label('Nombre Web: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_label(' ', '', array('class' => 'col-lg-10 control-label nombre_web nocodigo','id'=>'nombre_grabar')); ?>
            <?php echo form_input('nombreWeb', '', 'class="col-lg-10 control-label " style="display:none" id="nombreWeb"'); ?>
            <?php echo form_label('Iva (%)', '', array('class' => 'col-lg-2 control-label   ','id'=>'iva_label','style'=>'display:none')); ?>
            <?php echo form_input('iva', '', 'class="col-lg-1 control-label " style="display:none" id="iva_input"'); ?>
    
            </div>
            
            <div class="row nocodigo">
            <?php echo form_label('PVP €/unidad: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_label(' ', '', array('class' => 'col-lg-2 control-label tarifa_venta_unidad',)); ?>
            <?php echo form_label('PVP €/Kg: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_label(' ', '', array('class' => 'col-lg-2 control-label tarifa_venta_peso',)); ?> 
            <?php echo form_label('Peso Kg: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_label(' ', '', array('class' => 'col-lg-2 control-label peso_real',)); ?>   
           </div>
            <div class="row nocodigo">
            <?php echo form_label('PVP Producto: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_label(' ', '', array('class' => 'col-lg-2 control-label pvp',)); ?>
            <?php echo form_label('Iva %: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_label(' ', '', array('class' => 'col-lg-2 control-label iva','id'=>'iva_grabar')); ?>    
            <?php echo form_label('Precio venta neto: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_label(' ', '', array('class' => 'col-lg-2 control-label ventaNeto',)); ?> 
            </div>
            <div class="row nocodigo">
                <?php echo form_label('Precio compra: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
                <?php echo form_label(' ', '', array('class' => 'col-lg-2 control-label precioCompra',)); ?>  
                <?php echo form_label('Beneficio %: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
                <?php echo form_label(' ', '', array('class' => 'col-lg-2 control-label beneficio',)); ?>  
           </div>
    </div>
                   


<h4>Datos mercado</h4>
<hr>
    <div class="row">
        <?php echo form_label('Fecha 1: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
        <input type="date" name="fecha1" id="fecha1" class="col-lg-2 control-label" >
    </div>
    <div class="row">
        <?php echo form_label('URL web 1: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
        <?php echo form_input('web1', '', 'class=col-lg-10 control-label id="web1"'); ?>
    </div>
    <div class="row">
        <?php echo form_label('PVP €/unidad: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_input('pvp1u', '', 'class="col-lg-1 control-label pvpu" id="pvp1u"'); ?>
            <?php echo form_label('PVP €/Kg: ', '', array('class' => 'col-lg-1 control-label ',)); ?>
            <?php echo form_input('pvp1p', '', 'class="col-lg-1 control-label pvpp" id="pvp1p"'); ?> 
            <?php echo form_label('Peso Kg: ', '', array('class' => 'col-lg-1 control-label ',)); ?>
            <?php echo form_input('peso1', '', 'class="col-lg-1 control-label pvpp" id="peso1"'); ?> 
        
            <?php echo form_label('Precio compra (€) (beneficio 30 %): ', '', array('class' => 'col-lg-3 control-label ',)); ?>
            <?php echo form_label('', '', array('class' => 'col-lg-1 control-label ','id'=>'precioCompra30_1')); ?> 
    </div>

<hr>
 <div class="row">
        <?php echo form_label('Fecha 2: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
        <input type="date" name="fecha2" id="fecha2" class="col-lg-2 control-label" >
    </div>
    <div class="row">
        <?php echo form_label('URL web 2: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
        <?php echo form_input('web2', '', 'class=col-lg-10 control-label id="web2"'); ?>
    </div>
    <div class="row">
        <?php echo form_label('PVP €/unidad: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_input('pvp2u', '', 'class="col-lg-1 control-label pvpu" id="pvp2u"'); ?>
            <?php echo form_label('PVP €/Kg: ', '', array('class' => 'col-lg-1 control-label ',)); ?>
            <?php echo form_input('pvp2p', '', 'class="col-lg-1 control-label pvpp" id="pvp2p"'); ?> 
            <?php echo form_label('Peso Kg: ', '', array('class' => 'col-lg-1 control-label ',)); ?>
            <?php echo form_input('peso2', '', 'class="col-lg-1 control-label pvpp" id="peso2"'); ?> 
        
            <?php echo form_label('Precio compra (€) (beneficio 30 %): ', '', array('class' => 'col-lg-3 control-label ',)); ?>
            <?php echo form_label('', '', array('class' => 'col-lg-1 control-label ','id'=>'precioCompra30_2')); ?> 
    </div>
<hr>
    <div class="row">
        <?php echo form_label('Fecha 3: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
        <input type="date" name="fecha3" id="fecha3" class="col-lg-2 control-label" >
    </div>
    <div class="row">
        <?php echo form_label('URL web 3: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
        <?php echo form_input('web3', '', 'class=col-lg-10 control-label id="web3"'); ?>
    </div>
    <div class="row">
        <?php echo form_label('PVP €/unidad: ', '', array('class' => 'col-lg-2 control-label ',)); ?>
            <?php echo form_input('pvp3u', '', 'class="col-lg-1 control-label pvpu" id="pvp3u"'); ?>
            <?php echo form_label('PVP €/Kg: ', '', array('class' => 'col-lg-1 control-label ',)); ?>
            <?php echo form_input('pvp3p', '', 'class="col-lg-1 control-label pvpp" id="pvp3p"'); ?> 
            <?php echo form_label('Peso Kg: ', '', array('class' => 'col-lg-1 control-label ',)); ?>
            <?php echo form_input('peso3', '', 'class="col-lg-1 control-label pvpp" id="peso3"'); ?> 
        
            <?php echo form_label('Precio compra (€) (beneficio 30 %): ', '', array('class' => 'col-lg-3 control-label ',)); ?>
            <?php echo form_label('', '', array('class' => 'col-lg-1 control-label ','id'=>'precioCompra30_3')); ?> 
    </div>

<div class="row">
                    <div class="col-xs-offset-3 col-lg-8">
                        <button type="submit"  class="btn btn-success submit" id="registrarDatos"  >Registrar datos</button>
                    </div>
           </div>  
</div>
<script>
$(document).ready(function(){
    
    
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 
    today = dd+'/'+mm+'/'+yyyy;
    today=yyyy+'-'+mm+'-'+dd
    
    var valor_iva=0
    
    $('#codigo_producto').change(function(){
        $('#informacion').css('display','none')
    })
    
    var pvp1u=0
    var pvp2u=0
    var pvp3u=0
    var pvp1p=0
    var pvp2p=0
    var pvp3p=0
    var peso1=0
    var peso2=0
    var peso3=0
    
    $('#peso1').blur(function(e){
        peso1=numX1000($(this).val())
        if(pvp1p && peso1){
        $('#pvp1u').val(numeral(pvp1p/100*peso1/1000).format('0.00'))
        var precioCompra=70*pvp1p/100*peso1/1000/(Number(100)+Number(valor_iva))
        $('#precioCompra30_1').html(numeral(precioCompra).format('0.00'))
        }
    })
    $('#pvp1u').blur(function(e){
        pvp1u=numX100($(this).val())
        if(pvp1u){
        $('#pvp1p').val("")
        var precioCompra=70*pvp1u/100/(Number(100)+Number(valor_iva))
        $('#precioCompra30_1').html(numeral(precioCompra).format('0.00'))
        }
    })
    $('#pvp1p').blur(function(e){
        pvp1p=numX100($(this).val())
        if(pvp1p && peso1){
        $('#pvp1u').val(numeral(pvp1p/100*peso1/1000).format('0.00'))
        var precioCompra=70*pvp1p/100*peso1/1000/(Number(100)+Number(valor_iva))
        $('#precioCompra30_1').html(numeral(precioCompra).format('0.00'))
        }
    })
    
    $('#peso2').blur(function(e){
        peso2=numX1000($(this).val())
        if(pvp2p && peso2){
        $('#pvp2u').val(numeral(pvp2p/100*peso2/1000).format('0.00'))
        var precioCompra=70*pvp2p/100*peso2/1000/(Number(100)+Number(valor_iva))
        $('#precioCompra30_2').html(numeral(precioCompra).format('0.00'))
        }
    })
    $('#pvp2u').blur(function(e){
        pvp2u=numX100($(this).val())
        if(pvp2u){
        $('#pvp2p').val("")
        var precioCompra=70*pvp2u/100/(Number(100)+Number(valor_iva))
        $('#precioCompra30_2').html(numeral(precioCompra).format('0.00'))
        }
    })
    $('#pvp2p').blur(function(e){
        pvp2p=numX100($(this).val())
        if(pvp2p && peso2){
        $('#pvp2u').val(numeral(pvp2p/100*peso2/1000).format('0.00'))
        var precioCompra=70*pvp2p/100*peso2/1000/(Number(100)+Number(valor_iva))
        $('#precioCompra30_2').html(numeral(precioCompra).format('0.00'))
        }
    })
    
    $('#peso3').blur(function(e){
        peso3=numX1000($(this).val())
        if(pvp3p && peso3){
        $('#pvp3u').val(numeral(pvp3p/100*peso1/1000).format('0.00'))
        var precioCompra=70*pvp3p/100*peso3/1000/(Number(100)+Number(valor_iva))
        $('#precioCompra30_3').html(numeral(precioCompra).format('0.00'))
        }
    })
    $('#pvp3u').blur(function(e){
        pvp3u=numX100($(this).val())
        if(pvp3u){
        $('#pvp3p').val("")
        var precioCompra=70*pvp3u/100/(Number(100)+Number(valor_iva))
        $('#precioCompra30_3').html(numeral(precioCompra).format('0.00'))
        }
    })
    $('#pvp3p').blur(function(e){
        pvp3p=numX100($(this).val())
        if(pvp3p && peso3){
        $('#pvp3u').val(numeral(pvp3p/100*peso3/1000).format('0.00'))
        var precioCompra=70*pvp3p/100*peso3/1000/(Number(100)+Number(valor_iva))
        $('#precioCompra30_3').html(numeral(precioCompra).format('0.00'))
        }
    })
    
  
    
    $('#entrarDatos').click(function(e){
        valor_iva=0
        e.preventDefault()
        
        $('#pvp1u').val("")
        $('#pvp2u').val("")
        $('#pvp3u').val("")
        $('#pvp1p').val("")
        $('#pvp2p').val("")
        $('#pvp3p').val("")
        $('#web1').val("")
        $('#web2').val("")
        $('#web3').val("")
        $('#peso1').val("")
        $('#peso2').val("")
        $('#peso3').val("")
        $('#precioCompra30_1').html("")
        $('#precioCompra30_2').html("")
        $('#precioCompra30_3').html("")
        
        
        //alert($('#codigo_producto').val())
        var codigo_producto=$('#codigo_producto').val();
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/estudios/getDatosProducto", 
            data: {codigo_producto: codigo_producto},
            success: function(datos){
                
               
                var datosProductos=$.parseJSON(datos);
                
                if(datosProductos==0) {
                    //alert('No código')
                    valor_iva=0;
                    var codigo_producto
                    $.ajax({
                        
                        url: "<?php echo base_url() ?>"+"index.php/estudios/getSiguienteCodigoEM", 
                        
                        success: function(datos){
                            //alert(datos)
                            var siguienteCodigoEM=$.parseJSON(datos);
                            //alert(siguienteCodigoEM)
                            codigo_producto=siguienteCodigoEM
                            
                            $('#nombreWeb').css('display','inline')
                            $('#iva_label').css('display','inline')
                            $('#iva_input').css('display','inline')
                            $('.nocodigo').css('display','none')
                            $('.codigo_producto').html(codigo_producto)
                            $('.pvpu').removeAttr('disabled')
                            $('.pvpp').removeAttr('disabled')
                            $('#informacion').css('display','inline')
                            $('#fecha1').val(today)
                            $('#fecha2').val(today)
                            $('#fecha3').val(today)
                            
                            
                        },
                        error: function(){
                            alert("Error en el proceso. Informar");
                        }
                    });
                }
                else{
                
                $('#nombreWeb').css('display','none') 
                $('#iva_label').css('display','none')
                $('#iva_input').css('display','none')
                $('.nocodigo').css('display','inline')
                
                var valores=datosProductos['datos'];
                
                var iva=datosProductos['iva']
                
                var valoresMercado=datosProductos['datosMercado'];
                //alert(valoresMercado)
                if(valoresMercado!=null){
                    //lee datos de mercado ya existentes
                    if(valoresMercado['tarifa_venta_unidad1']!=0) $('#pvp1u').val(numeral(valoresMercado['tarifa_venta_unidad1']/100).format('0.00'))
                    if(valoresMercado['tarifa_venta_peso1']!=0)$('#pvp1p').val(numeral(valoresMercado['tarifa_venta_peso1']/100).format('0.00'))
                    if(valoresMercado['tarifa_venta_unidad2']!=0)$('#pvp2u').val(numeral(valoresMercado['tarifa_venta_unidad2']/100).format('0.00'))
                    if(valoresMercado['tarifa_venta_peso2']!=0)$('#pvp2p').val(numeral(valoresMercado['tarifa_venta_peso2']/100).format('0.00'))
                    if(valoresMercado['tarifa_venta_unidad3']!=0)$('#pvp3u').val(numeral(valoresMercado['tarifa_venta_unidad3']/100).format('0.00'))
                    if(valoresMercado['tarifa_venta_peso3']!=0)$('#pvp3p').val(numeral(valoresMercado['tarifa_venta_peso3']/100).format('0.00'))
                
                    $('#fecha1').val(valoresMercado['fecha_1'])
                    $('#fecha2').val(valoresMercado['fecha_2'])
                    $('#fecha3').val(valoresMercado['fecha_3'])
                    
                    $('#peso1').val(numeral(valoresMercado['peso1']/1000).format('0.000'))
                    $('#peso2').val(numeral(valoresMercado['peso2']/1000).format('0.000'))
                    $('#peso3').val(numeral(valoresMercado['peso3']/1000).format('0.000'))
                    
                    if(valoresMercado['fecha_1']==null) $('#fecha1').val(today)
                    if(valoresMercado['fecha_2']==null) $('#fecha2').val(today)
                    if(valoresMercado['fecha_3']==null) $('#fecha3').val(today)
                    
                    $('#web1').val(valoresMercado['url_web1'])
                    $('#web2').val(valoresMercado['url_web2'])
                    $('#web3').val(valoresMercado['url_web3'])
                    $('.codigo_producto').html(valoresMercado['codigo_producto'])
                }
                if(valores==null){
                    $('#nombreWeb').css('display','inline')
                    $('#iva_label').css('display','inline')
                    $('#iva_input').css('display','inline')
                    $('.nocodigo').css('display','none')
                    $('.codigo_producto').html(codigo_producto)
                    $('.pvpu').removeAttr('disabled')
                    $('.pvpp').removeAttr('disabled')
                }
                
                
                if(valores!=null){
                    $('.codigo_producto').html(valores['codigo_producto'])
                    $('.nombre_web').html(valores['nombre_web'])
                    
                    var tarifa_venta_unidad=numeral(valores['tarifa_venta_unidad']/100).format('0.00')
                    
                    var tarifa_venta_peso=numeral(valores['tarifa_venta_peso']/100).format('0.00')
                    var precio_ultimo_unidad=numeral(valores['precio_ultimo_unidad']/1000000).format('0.00')
                    var precio_ultimo_peso=numeral(valores['precio_ultimo_peso']/1000000).format('0.00')
                    var peso_real=numeral(valores['peso_real']/1000).format('0.000')
                    
                }
                if(iva!=null)
                    valor_iva=iva['valor_iva']
                
                $('#iva_grabar').html(valor_iva)
                
                
                var pvp=tarifa_venta_unidad==0?tarifa_venta_peso*peso_real:tarifa_venta_unidad;
                
                pvp=numeral(pvp).format('0.00')
                
                var precioCompra=precio_ultimo_unidad==0?precio_ultimo_peso*peso_real:precio_ultimo_unidad;
                precioCompra=numeral(precioCompra).format('0.00')
                
                var ventaNeto=pvp/(1+valor_iva/100)
                
                var beneficio=ventaNeto!=0?numeral((ventaNeto-precioCompra)/ventaNeto*100).format('0.00'):'----'
                
                if (tarifa_venta_unidad==0) {tarifa_venta_unidad="----"; $('.pvpp').removeAttr('disabled'); $('.pvpu').attr('disabled','disabled');}
                if (tarifa_venta_peso==0) {tarifa_venta_peso="----"; $('.pvpu').removeAttr('disabled'); $('.pvpp').attr('disabled','disabled');}
                if (peso_real==0) peso_real="----"
                if (pvp==0) pvp="----"
                
                $('.tarifa_venta_unidad').html(tarifa_venta_unidad)
                $('.tarifa_venta_peso').html(tarifa_venta_peso)
                $('.peso_real').html(peso_real)
                $('.pvp').html(pvp)
                $('#iva_grabar').html(valor_iva)
                $('.ventaNeto').html(numeral(ventaNeto).format('0.00'))
                $('.precioCompra').html(precioCompra)
                $('.beneficio').html(beneficio)
                
                $('#informacion').css('display','inline')
            }
                
                
            },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
    });
    
    $('#iva_input').change(function(){
        //alert('hola')
        valor_iva=$(this).val()
        
        if($('#pvp1u').val()) {
            var precioCompra1=70*$('#pvp1u').val()/(Number(100)+Number(valor_iva))
            $('#precioCompra30_1').html(numeral(precioCompra1).format('0.00'))
        }
        if($('#pvp1p').val() && $('#peso1').val()) {
            var precioCompra1=70*$('#pvp1p').val()*$('#peso1').val()/(Number(100)+Number(valor_iva))
            $('#precioCompra30_1').html(numeral(precioCompra1).format('0.00'))
        }
        
        if($('#pvp2u').val()) {
            var precioCompra2=70*$('#pvp2u').val()/(Number(100)+Number(valor_iva))
            $('#precioCompra30_2').html(numeral(precioCompra2).format('0.00'))
        }
        if($('#pvp2p').val() && $('#peso2').val()) {
            var precioCompra2=70*$('#pvp2p').val()*$('#peso2').val()/(Number(100)+Number(valor_iva))
            $('#precioCompra30_2').html(numeral(precioCompra2).format('0.00'))
        }
        
        if($('#pvp3u').val()) {
            var precioCompra3=70*$('#pvp3u').val()/(Number(100)+Number(valor_iva))
            $('#precioCompra30_3').html(numeral(precioCompra3).format('0.00'))
        }
        if($('#pvp3p').val() && $('#peso3').val()) {
            var precioCompra3=70*$('#pvp3p').val()*$('#peso3').val()/(Number(100)+Number(valor_iva))
            $('#precioCompra30_3').html(numeral(precioCompra3).format('0.00'))
        }
        
    })
    
    
    
    
    
    $('#registrarDatos').click(function(e){
        e.preventDefault()
        
        var codigo_producto=$('#codigo_grabar').html()
        var nombre=""
        var iva=""
        if(codigo_producto.substr(0,2)=="EM"){  
            nombre=$('#nombreWeb').val();
            iva=$('#iva_input').val()
        } else {
            nombre=$('#nombre_grabar').html();
            iva=$('#iva_grabar').html()
        }
        
        if(!iva){
            alert('No se ha introducido el iva del producto')
            return false;
        }
        
        var web1=$('#web1').val()
        var web2=$('#web2').val()
        var web3=$('#web3').val()
        
        var fecha1=$('#fecha1').val()
        var fecha2=$('#fecha2').val()
        var fecha3=$('#fecha3').val()
        var precioCompra30_1=numX1000000($('#precioCompra30_1').html())
        precioCompra30_1=numeral(precioCompra30_1).format('0')
        var precioCompra30_2=numX1000000($('#precioCompra30_2').html())
        precioCompra30_2=numeral(precioCompra30_2).format('0')
        var precioCompra30_3=numX1000000($('#precioCompra30_3').html())
        precioCompra30_3=numeral(precioCompra30_3).format('0')
        

        var pvp1=pvp1u==0?pvp1p*peso1/1000:pvp1u
        
        var pvp2=pvp2u==0?pvp2p*peso2/1000:pvp2u
        
        var pvp3=pvp3u==0?pvp3p*peso3/1000:pvp3u
        /*
        var peso1=$('#peso1').val()
        var peso2=$('#peso2').val()
        var peso3=$('#peso3').val()
        */
       
        
        //alert('pvp1 '+pvp1)
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/estudios/grabarDatosEstudiosMercado", 
            data: {codigo_producto: codigo_producto,
                    nombre:nombre,
                    pvp1u:pvp1u,
                    pvp2u:pvp2u,
                    pvp3u:pvp3u,
                    pvp1p:pvp1p,
                    pvp2p:pvp2p,
                    pvp3p:pvp3p,
                    web1:web1,
                    web2:web2,
                    web3:web3,
                    peso1:peso1,
                    peso2:peso2,
                    peso3:peso3,
                    fecha1:fecha1,
                    fecha2:fecha2,
                    fecha3:fecha3,
                    precio_compra1:precioCompra30_1,
                    precio_compra2:precioCompra30_2,
                    precio_compra3:precioCompra30_3,
                    iva:iva,
                    pvp1:pvp1,
                    pvp2:pvp2,
                    pvp3:pvp3,
                    
                },
                        
            success: function(datos){
                //alert(datos)
                //alert($.parseJSON(datos))
                alert('Los datos se han grabado.')
            },
            error: function(){
                alert("Error en el proceso. Informar");
            }
        });
        
        
        
    })
    
    function numX100(num){
        //alert(num)
        var numero=num.replace(",",".")
        return numero*100
    }
    function numX1000(num){
        //alert(num)
        var numero=num.replace(",",".")
        return numero*1000
    }
    function numX1000000(num){
        //alert(num)
        var numero=num.replace(",",".")
        return numero*1000000
    }
    
    });
</script>    