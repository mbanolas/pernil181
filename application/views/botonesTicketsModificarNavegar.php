<?php 
//var_dump($ticket);
//var_dump($ticket2);
?>
<input type="hidden" id="base_url" value="<?php echo base_url() ?>">
<input type="hidden" id="ajax" value="conversion/mostrarTicketModificarAjax">
<input type="hidden" id="posicion" value="<?php echo $posicion ?>">
<input type="hidden" id="ultimo" value="<?php echo $totalNumTickets ?>">
<!-- Boton Otro periodo -->
<?php echo form_open('tickets/seleccionarTicketsModificar', array('role' => 'form')); ?>
<button style="display: inline;" type="submit" class="btn btn-primary btn-mini " >
    <span class="" aria-hidden="true"></span> Seleccionar otro periodo
</button>
<?php echo \form_close(); ?> 

<button style="display: inline;" type="button" class="btn btn-default btn-mini " id="primeroM">
    <span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span> Ticket Primero
</button>

<button style="display: inline;" type="button" class="btn btn-default btn-mini" id="anteriorM">
    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>  Ticket anterior
</button>

<button style="display: inline;" type="button" class="btn btn-default btn-mini" id="siguiente_TicketM">
    <span class="glyphicon glyphicon-forward" aria-hidden="true"></span> Ticket siguiente
</button>

<button style="display: inline;" type="button" class="btn btn-default btn-mini" id="ultimo_TicketM" >
    <span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>  Ticket último
</button>

<?php echo form_dropdown('tickets', $ticketsPeriodo, 0,
            array('style'=>"display: inline;", 'class' =>  'btn btn-default btn-mini dropdown-toggle botonNavegador', 'id' => 'ticketsNavegarM')); ?>



<script>
    
$(document).ready(function () {
    var posicion = $("#posicion").val();
    var ultimo = $("#ultimo").val();
    
    //alert(posicion)
    establecerBotonesM();
    
    var formmodifiedM = 0;
   
   window.onbeforeunload = confirmExit;
    function confirmExit() {
        if (formmodifiedM == 1) 
        {
            return "No ha guardado los cambios.";
        }
    }
    
    function navegarM(posicion){
       
        var base_url = $("#base_url").val();
        
         
        var ajax=$("#ajax").val();
        
        $("#posicion").val(posicion);
       // alert(ajax);
       // alert(posicion);
       // alert(base_url+"index.php/"+ajax)
        $.ajax({
            type: "POST",
            //url: "<?php echo base_url() ?>"+"index.php/mostrar/mostrarTicketCambiarAjax",
            url: base_url+"index.php/"+ajax,
            data: {numTicket:posicion},
            success: function(datos){
               // alert(datos);
               datos=datos.substr(2);
               $("#ticket").html(datos);
               $("#ticketsNavegarM").children("option").removeAttr("selected");
               $("#ticketsNavegarM").children("option[value="+posicion+"]").attr("selected","selected");
               establecerBotonesM();
        },
            error: function(){
                 alert('Error proceso navegarM. Informar ');
                $("#esperar").html("Error en el proceso");
            }
        });
    }
    
    $("#ticketsNavegarM").change(function(){
        if (typeof $("#formmodifiedM").val() != 'undefined') formmodifiedM=$("#formmodifiedM").val();
        if(formmodifiedM==1){
            if (!confirm("Se han modificado los datos, ¿desea abandonar el ticket sin guardar los cambios?"))
                return;
        }
        posicion=$(this).val();
        navegarM(posicion);
    })
    $("#primeroM").click(function(){
       
        if (typeof $("#formmodifiedM").val() != 'undefined') formmodifiedM=$("#formmodifiedM").val();
        if(formmodifiedM==1){
            if (!confirm("Se han modificado los datos, ¿desea abandonar el ticket sin guardar los cambios?"))
                return;
        }
        posicion=1;
        navegarM(posicion);
    })
    $("#anteriorM").click(function(){
        
        if (typeof $("#formmodifiedM").val() != 'undefined') formmodifiedM=$("#formmodifiedM").val();
        if(formmodifiedM==1){
            if (!confirm("Se han modificado los datos, ¿desea abandonar el ticket sin guardar los cambios?"))
                return;
        }
        posicion=$("#posicion").val();
        if(Number(posicion)>1) {posicion--;};
        navegarM(posicion);
        })
    $("#siguiente_TicketM").click(function () {
        
        if (typeof $("#formmodifiedM").val() != 'undefined') formmodifiedM=$("#formmodifiedM").val();
        if(formmodifiedM==1){
            if (!confirm("Se han modificado los datos, ¿desea abandonar el ticket sin guardar los cambios?"))
                return;
        }
        posicion = $("#posicion").val();
        ultimo = $("#ultimo").val();
        if (Number(posicion) < Number(ultimo)) {
            posicion++;
        }
        navegarM(posicion);
    })
    $("#ultimo_TicketM").click(function () {
        
        if (typeof $("#formmodifiedM").val() != 'undefined') formmodifiedM=$("#formmodifiedM").val();
        if(formmodifiedM==1){
            if (!confirm("Se han modificado los datos, ¿desea abandonar el ticket sin guardar los cambios?"))
                return;
        }
        posicion = $("#ultimo").val();
        navegarM(posicion);
    })

        function establecerBotonesM(){
        $('#ticketsNavegarM').val($('#posicion').val())
        var posicionNueva=Number($("#posicion").val());
        var ultimo=Number($("#ultimo").val());
        $(".botonNavegador").css("font-weight","bold");
        if(posicionNueva==1){
                $("#primeroM").addClass("disabled","disabled").css("color","#7B7B7B").css("font-weight","normal") ;
                $("#siguiente_TicketM").removeClass("disabled").css("font-weight","bold").css("color","black");
                $("#anteriorM").addClass("disabled","disabled").css("color","#7B7B7B").css("font-weight","normal"); 
                $("#ultimo_TicketM").removeClass("disabled").css("font-weight","bold").css("color","black");
                return false;
            } 
            if(posicionNueva==ultimo){
                $("#primeroM").removeClass("disabled").css("font-weight","bold").css("color","black");
                $("#siguiente_TicketM").addClass("disabled","disabled").css("color","#7B7B7B").css("font-weight","normal"); 
                $("#anteriorM").removeClass("disabled").css("font-weight","bold").css("color","black");
                $("#ultimo_TicketM").addClass("disabled","disabled").css("color","#7B7B7B").css("font-weight","normal"); 

            return false;
            }
            if(posicionNueva>1 || posicionNueva<ultimo){
                $("#primeroM").removeClass("disabled").css("font-weight","bold").css("color","black");
                $("#siguiente_TicketM").removeClass("disabled").css("font-weight","bold").css("color","black");
                $("#anteriorM").removeClass("disabled").css("font-weight","bold").css("color","black");
                $("#ultimo_TicketM").removeClass("disabled").css("font-weight","bold").css("color","black");
                return false;
            } 
        }
        })
</script>







