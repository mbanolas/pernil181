<br /> <br />

<?php echo validation_errors(); ?>

<?php echo form_open('verifyLogin'); ?>
    <div class="container">
    <div class="row ">
    <div class="form-group  col-xs-offset-2 col-xs-7 col-sm-offset-2 col-sm-6 col-lg-offset-4 col-lg-3 col-md-offset-3 col-md-6 ">
        <label for="usuario">Usuario: </label>
        <input autofocus type="text" name="username" class="form-control" id="usuario" placeholder="Introducir usuario" value="<?php echo set_value('username'); ?>">
        <label for="password">Contraseña: </label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Introducir contraseña" value="<?php echo set_value('password'); ?>" >
        <span id="error" style="color:red"><?php echo $error ?></span>
        <input type="submit" class="btn btn-primary" value="Entrar">  
    </div>
    </div>
    </div>
    <div class="col-xs-12 col-md-4"></div>
    
    <div class="row col-centered">
        <div class="col-xs-12 col-md-4 ">
        <h6 style="margin-top: 100px">Utilizar, preferentemente, Chrome</h6>
        <h6 >Y pantalla completa con Windows F11 - Mac Ctr+Shift+f</h6>
        </div>
    </div>
    
<?php echo \form_close(); ?>


<style>
    .col-centered{
        margin: 0 auto;
        float: none;
    }
</style>
