

<br />
<!-- Seleccion fórmula -->
<div class="box box-primary col-lg-12 " id="seleccionar">  
        <div class="container">
            <h4>Fórmulas </h4>
            <div class="row">
                <div class="box-body col-lg-3"> 
                    Filtrar fórmulas: <input type="text" id="buscarFormulas" class="form-control_ input-sm" value="" >
                </div> 
                <div class="box-body col-lg-1 reset"> 
                    <a href="#" id="resetFormula">Resetear</a>
                </div>
            </div>
        </div>
        <div class="container">
              <div class="row">  
                <input type="hidden" value="0" id="codigoFormula" >
                <div class="box-body_ col-lg-4"> 
                    <?php echo form_dropdown('formula', $optionsFormulas, '', array('id' => 'formula', 'class' => 'form-control input-sm')); ?>
                </div>
                    <button type="button" class="btn btn-info_ btn-sm" id="addFormula" >Nueva Formula </button>
                    <button type="button" class="btn btn-info btn-sm" id="verFormula"  >Ver </button>
                    <button type="button" class="btn btn-primary btn-sm" id="updateFormula" >Modificar </button>
                    <button type="button" class="btn btn-danger btn-sm" id="deleteFormula" >Eliminar </button>              </div> 
               <hr>
            </div>
</div>

<!-- Nueva formule -->
<div class="box box-primary col-lg-12 hide" id="nuevo">   
        <div class="container">
            <h4>Nueva Formula </h4>
            <div class="row">
                <div class="box-body col-lg-4"> 
                    <strong>Denominación: </strong><input type="text" id="descripcionFormula" class="form-control input-sm" value="" >
                </div> 
                
            </div>
            <div class="row">
                <div class="box-body col-lg-3"> 
                    <strong>Producto final obtenido</strong>
                </div> 
            </div>
            <div class="row">
                <div class="box-body col-lg-3"> 
                    Filtrar productos: <input type="text" id="buscarProductosFinales" class="form-control_ input-sm" value="" >
                </div> 
                <div class="box-body col-lg-1 reset"> 
                    <a href="#" class="" id="resetProductosFinales">Resetear</a>
                </div>
                <div class="box-body col-lg-1 " style="margin-top: 10px;"> 
                    <strong>Cantidad: </strong>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">  
              <input type="hidden" value="0" id="codigoFormula" >
              <div class="box-body_ col-lg-4"> 
                  <?php $optionsProductos[0]="Seleccionar producto final" ?>
                  <?php echo form_dropdown('productoFinal', $optionsProductos, '', array('id' => 'productoFinal', 'class' => 'form-control input-sm')); ?>
              </div>
              <div class="box-body col-lg-1"> 
                    <input type="text" id="cantidadFormula" class="form-control input-sm" value="" >
                </div> 
            </div> 
             <hr>
        </div>
        <div class="container">
            <h4>Composición</h4>
            
            <div class="row">
                <div class="box-body col-lg-2 titulo">Código</div>
                <div class="box-body col-lg-4 titulo">Nombre</div>
                <div class="box-body col-lg-1 titulo">Cantidad</div>
            </div>
            <div id="componentes">
            
            </div>
            <br />
            <button type="button" class="btn btn-info btn-sm" id="registrarFormula" >Registrar Formula</button>

            <hr>
            <div class="row">
                <div class="box-body col-lg-3"> 
                    <strong>Componenetes</strong>
                </div> 
            </div>
             <div class="row">
                <div class="box-body col-lg-3"> 
                    Filtrar comp: <input type="text" id="buscarComponentes" class="form-control_ input-sm" value="" >
                </div> 
                <div class="box-body col-lg-1 reset"> 
                    <a href="#" class="" id="resetComponentes">Resetear</a>
                </div>
                <div class="box-body col-lg-3" style="margin-top: 10px;"> 
                    <strong>Cantidad:</strong>
                </div> 
            </div>
             <div class="container">
              <div class="row">  
                <input type="hidden" value="0" id="codigoFormula" >
                <div class="box-body_ col-lg-4"> 
                    <?php $optionsProductos[0]="Seleccionar componente" ?>
                    <?php echo form_dropdown('componente', $optionsProductos, '', array('id' => 'componente', 'class' => 'form-control input-sm')); ?>
                </div>
                <div class="box-body_ col-lg-1"> 
                    <input type="text" id="cantidadComponente" class="form-control input-sm" value="" >    
                </div>
                <div class="reset">
                    <a href="#" class="" id="anadir">Añadir</a>                    
                </div>
                    
            </div>
        </div>
        <hr>
</div>
</div>

<!-- Editar formula -->
<div class="box box-primary col-lg-12 hide" id="editar">  
        <div class="container">
            <h4>Editar fórmula </h4>
            <div class="row">
                <div class="box-body col-lg-4"> 
                    <strong>Denominación: </strong><input type="text" id="descripcionFormulaEdit" class="form-control input-sm " value=""  >
                </div> 
            </div>   
           <div class="row">
                <div class="box-body col-lg-3"> 
                    <strong>Producto final obtenido</strong>
                </div> 
           </div>
            <div class="row">
                <div class="box-body col-lg-3"> 
                    Filtrar productos: <input type="text" id="buscarProductosFinalesEdit" class="form-control_ input-sm" value="" >
                </div> 
                <div class="box-body col-lg-1 reset"> 
                    <a href="#" class="" id="resetProductosFinalesEdit">Resetear</a>
                </div>
                <div class="box-body col-lg-1"> 
                    <strong>Cantidad: </strong>
                </div> 
            </div>
        </div>
        <div class="container">
            <div class="row">  
              <input type="hidden" value="0" id="codigoFormula" >
              <div class="box-body_ col-lg-4"> 
                  <?php $optionsProductos[0]="Seleccionar producto final" ?>
                  <?php echo form_dropdown('productoFinalEdit', $optionsProductos, '', array('id' => 'productoFinalEdit', 'class' => 'form-control input-sm')); ?>
              </div>
              <div class="box-body col-lg-1"> 
                    <input type="text" id="cantidadFormulaEdit" class="form-control input-sm" value="" >
                </div> 
            </div> 
            
             <hr>
        </div>
        <div class="container">
            <h4>Composición</h4>
            
            <div class="row">
                <div class="box-body col-lg-2 titulo">Código</div>
                <div class="box-body col-lg-4 titulo">Nombre</div>
                <div class="box-body col-lg-1 titulo">Cantidad</div>
            </div>
            <div id="componentesEdit">
            
            </div>
            <br />
            <button type="button" class="btn btn-info btn-sm" id="registrarModificacionFormula" >Registrar Modificación Fórmula</button>

            <hr>
            <div class="row">
                <div class="box-body col-lg-3"> 
                    Filtrar comp: <input type="text" id="buscarComponentesEdit" class="form-control_ input-sm" value="" >
                </div> 
                <div class="box-body col-lg-1 reset"> 
                    <a href="#" class="" id="resetComponentesEdit">Resetear</a>
                </div>
                <div class="box-body col-lg-3" style="margin-top: 10px;"> 
                    <strong>Cantidad:</strong>
                </div> 
            </div>
           <div class="container">
              <div class="row">  
                <input type="hidden" value="0" id="codigoFormula" >
                <div class="box-body_ col-lg-4"> 
                    <?php $optionsProductos[0]="Seleccionar componente" ?>
                    <?php echo form_dropdown('componenteEdit', $optionsProductos, '', array('id' => 'componenteEdit', 'class' => 'form-control input-sm')); ?>
                </div>
                <div class="box-body_ col-lg-1"> 
                    <input type="text" id="cantidadComponenteEdit" class="form-control input-sm" value="" >    
                </div>
                <div class="reset">
                    <a href="#" class="" id="anadirEdit">Añadir</a>                    
                </div>
                    
            </div>
        </div>
        <hr>
</div>
</div>

<!-- Ver formula -->
<div class="box box-primary col-lg-12 hide" id="ver">  
        <div class="container">
            <h4>Ver fórmula </h4>
            <div class="row">
                <div class="box-body col-lg-4"> 
                    Denominación:
                </div> 
                <div class="box-body col-lg-1"> 
                    Cantidad:
                </div> 
            </div>
            
            <div class="row">
                <div class="box-body col-lg-4"> 
                    <label id="verDescripcion">Nombre producto</label>
                </div> 
                <div class="box-body col-lg-1"> 
                    <label id="verCantidad">12.45</label>
                </div> 
            </div>
            
           <br />
        </div>
        <div class="container" >
            Composición:
            
            <div class="row">
                <div class="box-body col-lg-2 titulo">Código</div>
                <div class="box-body col-lg-4 titulo">Nombre</div>
                <div class="box-body col-lg-1 titulo">Cantidad</div>
            </div>
            <div id="composicion">
                
            </div>
            <br />

            <hr>
            
        
</div>
</div>


<style>
    select.input-sm{
        height: 25px;
    }
    .input-sm {
    height: 25px;
    padding: 0px 10px;
    }
    .reset{
       padding-left: 28px;
       padding-top:  4px;
    }
    input.input-sm{
        border: 1px solid #cccccc;
    }
    hr{
        margin-top:5px;
    }
    .titulo{
        font-weight: bold;
    }
    .componentesCantidadVer,cantidadComponente{
        text-align: right;
    }
    
    
</style>

<script>
        
$(document).ready(function () {
    
    //control cambios antes de abandonar la página
    var cambios=false
    
    filtroProductos("",'productoFinal')
    filtroProductos("",'productoFinalEdit')
    filtroProductos("",'componente')
    filtroProductos("",'componenteEdit')
    
    $('#descripcionFormula, #productoFinal, #cantidadFormula,#componente,#cantidadComponente').change(function(){
        cambios=true
    })
    $('#descripcionFormulaFinal, #productoFinalFinal, #cantidadFormulaFinal,#componenteFinal,#cantidadComponenteFinal').change(function(){
        cambios=true
    })
    $('.eliminar').click
    
    window.onbeforeunload=confirmExit
     
     function confirmExit() {
        if (cambios ) 
        {
            return 'Ha introducido datos que no se han guardado.'
           
        }
    }
    
    
    $('#addFormula').click(function(){
        $('#nuevo').removeClass('hide');
        $('#editar').addClass('hide')
        $('#ver').addClass('hide')
        $('#seleccionar').addClass('hide')
        $('#componentes').empty()
        
        
        
        
        
    })
    
    
    $('#updateFormula').click(function(){
        if($('#formula').val()==0) return false;
        $('#editar').removeClass('hide');
        $('#nuevo').addClass('hide')
        $('#ver').addClass('hide')
        
        var formula=$('#formula').val()
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/stocks/getFormula",
            data: {formula:formula},
            success: function(datos){
                //alert(datos)
                var datos=$.parseJSON(datos)
                $('#descripcionFormulaEdit').val(datos['descripcion'])
               //alert(datos['productoFinal'])
                var elemento=0
                $('select#productoFinalEdit option').each(function(){
                   // console.log($(this).html())
                    if($(this).html().indexOf(datos['productoFinal']) > -1){
                    elemento= $(this).val()
                    return false
                    }  
                })
                $('#productoFinalEdit').val(elemento)
                
                $('#cantidadFormulaEdit').val((datos['cantidad']/1000).toFixed(3))
                for(var i=0;i<datos['lineas'].length;i++){
                var componente='<div class="row"><div class="box-body col-lg-2">'
                componente+=datos['lineas'][i]['componentesCodigo_producto']
                componente+='</div><div class="box-body col-lg-4">'
                componente+=datos['lineas'][i]['componentesNombre']
                componente+='</div><div class="box-body col-lg-1 componentesCantidadVer">'
                componente+=(datos['lineas'][i]['componentesCantidad']/1000).toFixed(3)
                componente+='</div>'
                componente+="<a href='' class='eliminar'>Eliminar</a></div>"
                componente+='</div>'
                $('#componentesEdit').append(componente)
            }
            },
            error: function(){
                alert("Error en el proceso lectura fórmula. Informar");
            },
        })
        
        
        
    })
   
    $('#verFormula').click(function(){
        if($('#formula').val()==0) return false;
        $('#ver').removeClass('hide');
        $('#nuevo').addClass('hide')
        $('#editar').addClass('hide')
        $('#composicion').empty()
        var formula=$('#formula').val()
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/stocks/getFormula",
            data: {formula:formula},
            success: function(datos){
                var datos=$.parseJSON(datos)
                $('#verDescripcion').html(datos['descripcion']+' ('+datos['productoFinal']+')')
                
                $('#verCantidad').html((datos['cantidad']/1000).toFixed(3))
                for(var i=0;i<datos['lineas'].length;i++){
                var componente='<div class="row"><div class="box-body col-lg-2">'
                componente+=datos['lineas'][i]['componentesCodigo_producto']
                componente+='</div><div class="box-body col-lg-4">'
                componente+=datos['lineas'][i]['componentesNombre']
                componente+='</div><div class="box-body col-lg-1 componentesCantidadVer">'
                componente+=(datos['lineas'][i]['componentesCantidad']/1000).toFixed(3)
                componente+='</div></div>'
                $('#composicion').append(componente)
            }
            },
            error: function(){
                alert("Error en el proceso lectura fórmula. Informar");
            },
        })
       
       })
        
    
    
    
    
    
    
    $('#verFormula,#updateFormula,#deleteFormula').addClass('disabled')
    
    $('#formula').change(function(){
        $('#ver').addClass('hide');
        $('#nuevo').addClass('hide')
        $('#editar').addClass('hide')
        if($(this).val()>0) {
            $('#verFormula,#updateFormula,#deleteFormula').removeClass('disabled')
        }
        else {
            $('#verFormula,#updateFormula,#deleteFormula').addClass('disabled')
        }
    })
    
    //filtrado formulas 
    $('#buscarFormulas').blur(function(){
    var filtro=$(this).val()
    filtroFormulas(filtro)
    })
    
    $('#resetFormula').click(function(){
        $('#buscarFormulas').val("")
        filtroFormulas("")
    })
    
    function filtroFormulas(filtro){
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/stocks/getFormulasFiltro", 
        data:{filtro:filtro},
        success:function(datos){
            alert(datos)
            var datos=$.parseJSON(datos);
             //alert(datos['nombres'])
             $('select#formula option').remove();
             
             $.each(datos, function(index, value){
                 var option='<option value="'+index+'">'+value+'</option>'
                 $('#formula').append(option)
             })
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
    
    //filtrado productosFinales 
    $('#buscarProductosFinales').blur(function(){
    var filtro=$(this).val()
    filtroProductos(filtro,'productoFinal')
    })
    
    $('#resetProductosFinales').click(function(){
        $('#buscarProductosFinales').val("")
        filtroProductos("",'productoFinal')
    })
    
    //filtrado productosFinales Edit 
    $('#buscarProductosFinalesEdit').blur(function(){
    var filtro=$(this).val()
    filtroProductos(filtro,'productoFinalEdit')
    })
    
    $('#resetProductosFinaleEdits').click(function(){
        $('#buscarProductosFinales').val("")
        filtroProductos("",'productoFinalEdit')
    })
    
    function filtroProductos(filtro,id){
         $.ajax({
        type:'POST',
        url: "<?php echo base_url() ?>"+"index.php/stocks/getProductosFiltro", 
        data:{filtro:filtro,id:id},
        success:function(datos){
            
            var datos=$.parseJSON(datos);
             
             $('select#'+id+' option').remove();
            
             $.each(datos, function(index, value){
                 var option='<option value="'+index+'">'+value+'</option>'
                 $('#'+id).append(option)
             })
        },
        error: function(){
                alert("Error en el proceso. Informar");
         }
    })
    }
    
    //filtrado Componentes 
    $('#buscarComponentes').blur(function(){
    var filtro=$(this).val()
    filtroProductos(filtro,'componente')
    })
    
    $('#resetComponentes').click(function(){
        $('#buscarComponentes').val("")
        filtroProductos("",'componente')
    })
    
    //filtrado Componentes 
    $('#buscarComponentesEdit').blur(function(){
    var filtro=$(this).val()
    filtroProductos(filtro,'componenteEdit')
    })
    
    $('#resetComponentesEdit').click(function(){
        $('#buscarComponentesEdit').val("")
        filtroProductos("",'componenteEdit')
    })
    
    
    //verifica cantidad y devuelve valor con 3 decimales
    function cantidadNumber(cantidad){
        cantidad=cantidad.replace(",",".")
        cantidad*=1
        if(isNaN(cantidad)) cantidad=0
        return cantidad.toFixed(3)
    }
    
    $('#anadir').click(function(){
        var componente=$('#componente').val()
        var cantidad=cantidadNumber($('#cantidadComponente').val())

        if(componente==0 || cantidad==0){
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("No ha seleccionado el componente y/o la cantidad o no es válida.\n")
            $("#myModal").modal()  
            return false
        }
            
        var nombreComponente=$('option[value="'+componente+'"]').html()
        var codigo_producto = nombreComponente.substr(nombreComponente.length-14,13);
        var nombre = nombreComponente.substr(0,nombreComponente.length-15);
        
        var anadir="<div class='row'><input type='hidden' class='componente' value='"+componente+"'>"
        anadir+="<div class='box-body col-lg-2'>"+codigo_producto+"</div>"
        anadir+="<div class='box-body col-lg-4'>"+nombre+"</div>"
        anadir+="<div class='box-body col-lg-1 cantidadComponente componentesCantidadVer' >"+cantidad+"</div>"
        anadir+="<a href='' class='eliminar'>Eliminar</a></div>"
        
        $('#componentes').append(anadir)
        $('#cantidadComponente').val("")
        $('#componente').val(0)
        $('#cantidadComponente').css('background-color','white')
        $('#componente').css('background-color','white')
        
    })
    
    $('#anadirEdit').click(function(){
        var componente=$('#componenteEdit').val()
        var cantidad=cantidadNumber($('#cantidadComponenteEdit').val())

        if(componente==0 || cantidad==0){
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("No ha seleccionado el componente y/o la cantidad o no es válida.\n")
            $("#myModal").modal()  
            return false
        }
            
        var nombreComponente=$('option[value="'+componente+'"]').html()
        var codigo_producto = nombreComponente.substr(nombreComponente.length-14,13);
        var nombre = nombreComponente.substr(0,nombreComponente.length-15);
        
        var anadir="<div class='row'><input type='hidden' class='componenteEdit' value='"+componente+"'>"
        anadir+="<div class='box-body col-lg-2'>"+codigo_producto+"</div>"
        anadir+="<div class='box-body col-lg-4'>"+nombre+"</div>"
        anadir+="<div class='box-body col-lg-1 cantidadComponenteEdit componentesCantidadVer' >"+cantidad+"</div>"
        anadir+="<a href='' class='eliminar'>Eliminar</a></div>"
        
        $('#componentesEdit').append(anadir)
        $('#cantidadComponenteEdit').val("")
        $('#componenteEdit').val(0)
        $('#cantidadComponenteEdit').css('background-color','white')
        $('#componenteEdit').css('background-color','white')
        
    })
    
    
    $('body').delegate('.eliminar','click',function(e)  
        {  
            e.preventDefault()
            $(this).parent().remove(); 
        }); 
        
      
        
    $('#cantidadFormula').change(function(){
        
        var cantidad=cantidadNumber($(this).val())
        if(cantidad==0){
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("La cantidad producida no es válida")
            $("#myModal").modal()  
            return false
        }
        $('#cantidadFormula').attr('value',cantidad)
    })   
    
    $('#registrarFormula').click(function(){
        var errores=false
        //verificando condiciones registro
        var descripcion=$('#descripcionFormula').val()
        var cantidadFormula=$('#cantidadFormula').val()
        var productoFinal=$('#productoFinal').val()
        var numComponentes=$('#componentes').children().length
        if(!descripcion){
            $('#descripcionFormula').css('background-color','yellow')
            errores=true
        }
        if(!cantidadFormula){
            $('#cantidadFormula').css('background-color','yellow')
            errores=true
        }if(productoFinal==0){
            $('#productoFinal').css('background-color','yellow')
            errores=true
        }
        if(numComponentes==0){
            $('#componente').css('background-color','yellow')
            $('#cantidadComponente').css('background-color','yellow')
            errores=true
        }
        if(errores){
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("Completar los campos indicados")
            $("#myModal").modal()  
            return false
        }
        
        var componentes=[]
        $('.componente').each(function(i,e)  {
            componentes[i]=$(this).val()-0
        })
        var cantidadesComponentes=[]
        $('.cantidadComponente').each(function(i,e)  {
            cantidadesComponentes[i]=$(this).html()
        })
        
       var lineas={}
       for (var i = 0; i < componentes.length; ++i){
            lineas[i]={
                componente:componentes[i],
                cantidadComponente:cantidadesComponentes[i]
             }
        }  
        
        //alert(lineas[0]['componente'])
        
       var formula={}
       formula={
           descripcion:descripcion,
           cantidadFormula:cantidadFormula,
           productoFinal:productoFinal,
           lineas:lineas
       }
       $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/stocks/grabarFormula",
            data: formula,
            success: function(datos){
               //alert(datos) 
               var datos=$.parseJSON(datos);
               cambios=false
               $('#myModal').on('hidden.bs.modal', function () {
                   window.location.href = "<?php echo base_url() ?>" + "index.php/stocks/formulas";
                })    
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Formula registrada correctamente.')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
               
               
            },
            error: function(){
            
            }
        }) 
        
    })
    
     $('#registrarModificacionFormula').click(function(){
        var errores=false
        //verificando condiciones registro
        var descripcion=$('#descripcionFormulaEdit').val()
        var cantidadFormula=$('#cantidadFormulaEdit').val()
        var productoFinal=$('#productoFinalEdit').val()
        var numComponentes=$('#componentesEdit').children().length
        var formulaId=$('#formula').val()

        if(!descripcion){
            $('#descripcionFormulaEdit').css('background-color','yellow')
            errores=true
        }
        if(!cantidadFormula){
            $('#cantidadFormulaEdit').css('background-color','yellow')
            errores=true
        }if(productoFinal==0){
            $('#productoFinalEdit').css('background-color','yellow')
            errores=true
        }
        if(numComponentes==0){
            $('#componenteEdit').css('background-color','yellow')
            $('#cantidadComponenteEdit').css('background-color','yellow')
            errores=true
        }
        if(errores){
            $('.modal-title').html('Información ')
            $('.modal-body>p').html("Completar los campos indicados")
            $("#myModal").modal()  
            return false
        }
        
        var componentes=[]
        $('.componenteEdit').each(function(i,e)  {
            componentes[i]=$(this).val()-0
        })
        var cantidadesComponentes=[]
        $('.cantidadComponenteEdit').each(function(i,e)  {
            cantidadesComponentes[i]=$(this).html()
        })
        
       var lineas={}
       for (var i = 0; i < componentes.length; ++i){
            lineas[i]={
                componente:componentes[i],
                cantidadComponente:cantidadesComponentes[i]
             }
        }  
        
        
        //alert(lineas[0]['componente'])
        
       var formula={}
       formula={
           formulaId:formulaId,
           descripcion:descripcion,
           cantidadFormula:cantidadFormula,
           productoFinal:productoFinal,
           lineas:lineas
       }
       $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>"+"index.php/stocks/grabarModificacionFormula",
            data: formula,
            success: function(datos){
               //alert(datos) 
               var datos=$.parseJSON(datos);
               cambios=false
               $('#myModal').on('hidden.bs.modal', function () {
                   window.location.href = "<?php echo base_url() ?>" + "index.php/stocks/formulas";
                })    
                $('.modal-title').html('Información')
                $('.modal-body>p').html('Formula registrada correctamente.')
                $("#myModal").modal({backdrop:"static",keyboard:"false"}) 
               
               
            },
            error: function(){
            
            }
        }) 
        
    })
        
    
})
</script>