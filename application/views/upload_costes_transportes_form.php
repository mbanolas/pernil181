<h4>Archivos Datos Costes Transportes <?php if (isset($adicionales) && $adicionales == 1) echo 'ADICIONALES' ?></h4>

<h4 style="color:red"><?php echo $data['orig_name'] ?><br> <span id="error"><?php echo $error; ?></span></h4>
<br>

<?php echo form_open_multipart('upload/do_upload_costes_transportes'); ?>

<div class="container">
  <input class="btn btn-success btn-mini " type="file" name="userfile" style="width: 540px" />

  <br /><br />
  <!--
<input class="btn btn-primary btn-mini " id="descargar" type="submit" value="Descargar Costes Transportes" />
-->
  <div class="row">
    <div class="form-group col-xs-3">
      <button type="submit" id="descargar" class="btn btn-default">Descargar Costes Transportes <img id="esperar" class="hide" src="<?php echo base_url() ?>images/ajax-loader-2.gif" width="20" height="20" alt="Loading"></button>
    </div>
  </div>

  <!--
<div id="esperar" class="hide">
<br /><br /><h4>Esperar</h4>
<img class="img-responsive ajax-loader"   src="<?php echo base_url('images/ajax-loader-w.gif') ?>">
</div>
-->

</div>
</form>


<script>
  $(document).ready(function() {


    $('#descargar').click(function(e) {
     
      $('#esperar').removeClass('hide')
    })

    var error = $('#error').html()
    if (error == "<p>No seleccionaste un archivo para subir.</p>") {
      $('#error').addClass('hide')
    } else {
      $('#error').removeClass('hide')
    }

    if (error != "<p>No seleccionaste un archivo para subir.</p>") {
      $('.modal-title').html('Información importante')
      $('.modal-body>p').html(error)
      $("#myModal").css('color', 'red')
      $("#myModal").modal()
    }

  })
</script>