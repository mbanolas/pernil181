<h2> Preparar documentos pedidos Suiza </h2>
<br>
<?php echo form_open('prestashop/documentos'); ?>
<div class="form-group">
  <label for="sel1">Seleccionar pedido de Suiza:</label>
  <select class="form-control" id="selecccionarPedido" name="pedido">
  <?php echo "<option value='0'>Seleccionar un pedido</option>";  
      foreach($pedidosSuiza as $k=>$v){ 
          $id=$v->id;
          $nombre=$v->firtsname.' '.$v->lastname;
          $fecha=fechaEuropea($v->fecha);
          echo "<option value='$id'>".$id.' - '.$fecha.' - '.$nombre."</option>";  
      } ?>
  </select>
</div>

<div class="form-group">
  <label for="usr">Número factura:</label>
  <input type="text" class="form-control" id="factura" name="factura">
</div>

<div class="form-group">
  <label for="usr">Fecha:</label>
  <input type="text" class="form-control" id="fecha" name="fecha" value='<?php echo date("d/m/Y") ?> '>
</div>

<div class="form-group">
  <label for="usr">Peso (Kg):</label>
  <input type="text" class="form-control" id="peso" name="peso">
</div>

<button type="submit" class="btn btn-default" name="documento_a">Preparar Factura</button>
<button type="submit" class="btn btn-default" name="documento_b">Preparar Carta Uso y destino</button>
<button type="submit" class="btn btn-default" name="documento_c">Preparar Carta traslado y destino</button>
</form>

<script>
    $(document).ready(function() {
        $('button[type="submit"]').click(function(e){
            if($('#selecccionarPedido').val()==0){
                $('.modal-title').html('Información');
                $('.modal-body >p').html('Se debe seleccionar un pedido')
                $("#myModal").modal() 
                e.preventDefault()
            }
            if($('input#factura').val()==0){
                $('.modal-title').html('Información');
                $('.modal-body >p').html('Se debe indicar número factura')
                $("#myModal").modal() 
                e.preventDefault()
            }
            if($('input#fecha').val()==0){
                $('.modal-title').html('Información');
                $('.modal-body >p').html('Se debe indicar fecha para datar los documentos')
                $("#myModal").modal() 
                e.preventDefault()
            }
            if($('input#peso').val()==0){
                $('.modal-title').html('Información');
                $('.modal-body >p').html('Se debe indicar peso pedido enviado')
                $("#myModal").modal() 
                e.preventDefault()
            }

        })

    })
</script>