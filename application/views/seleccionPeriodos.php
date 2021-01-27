<style type="text/css">
    p,
    ul {
        padding: 10px;
        font-size: 15px;
        font-weight: normal;
        text-align: left;
        background: #f2f2f2;
    }

    .form-group,
    input,
    .box,
    .box1,
    .boxT,
    .boxF {
        /*background: #f2f2f2;*/

    }

    li {
        /*   padding-bottom: 5px; */
    }

    p.d {
        padding: 50px;
        font-size: 32px;
        font-weight: bold;
        text-align: center;
        background: #f2f2f2;
        height: 250px;
    }

    .box label,
    .box1 label {

        padding-bottom: 13px;
    }

    .boxS label {

        padding-bottom: 0px;
    }

    .box1 {
        padding-top: 10px;
        padding-bottom: 30px;
    }

    .boxS {
        padding-top: 0px;
        padding-bottom: 0px;
        padding-left: 10px;
    }

    ul {
        padding-bottom: 30px;
    }

    .boxT {
        padding-top: 6px;
        padding-left: 0px;
        padding-bottom: 8px;
    }

    .nombre {
        padding-left: 10px;
        text-align: left;

    }

    .nombreS {

        margin-bottom: 50px;
        padding-left: 10px;
        padding-bottom: 0px;
        text-align: left;
        color: red;

    }
    #myModal{
        color:red;
    }
</style>
<hr>
<div class="container">

    <div class="row">
        <div class="col-md-2">
            <div class="row">

            </div>
        </div>
        <div class="col-md-8 ">
            <div class="row">

            </div>
        </div>
    </div>
    <div class="row" style="background-color: #f2f2f2">

        <div class="col-md-2">
            <div class="row">
                <ul style="list-style:none;">
                    <li>
                        <?php echo form_radio('periodo', 'hoy', $this->session->periodo == 'hoy' ? true : false, 'id="hoy"') ?>
                        <?php echo form_label(' Hoy ', 'hoy', array('class' => 'control-label ', 'for' => 'hoy')); ?>

                    </li>
                    <li>
                        <?php echo form_radio('periodo', 'ayer', $this->session->periodo == 'ayer' ? true : false, 'id="ayer"') ?>
                        <?php echo form_label(' Ayer ', 'ayer', array('class' => 'control-label ', 'for' => 'ayer')); ?>
                    </li>
                    <li>
                        <?php echo form_radio('periodo', 'Semana actual', $this->session->periodo == 'Semana actual' ? true : false, 'id="semanaActual"'); ?>
                        <?php echo form_label(' Semana actual ', 'semanaActual', array('class' => 'control-label ', 'for' => 'semanaActual')); ?>

                    </li>
                    <li>
                        <?php echo form_radio('periodo', 'Semana_anterior', $this->session->periodo == 'Semana_anterior' ? true : false, 'id="semanaAnterior"') ?>
                        <?php echo form_label(' Semana anterior ', 'semanaAnterior', array('class' => 'control-label ', 'for' => 'semanaAnterior')); ?>

                    </li>
                    <li>
                        <?php echo form_radio('periodo', 'Mes actual', $this->session->periodo == 'Mes actual' ? true : false, 'id="mesActual"') ?>
                        <?php echo form_label(' Mes actual ', 'mesActual', array('class' => 'control-label_', 'for' => 'mesActual')); ?>

                    </li>
                    <li>
                        <?php echo form_radio('periodo', 'Mes anterior', $this->session->periodo == 'Mes anterior' ? true : false, 'id="mesAnterior"') ?>
                        <?php echo form_label(' Mes anterior ', 'mesAnterior', array('class' => 'control-label ', 'for' => 'mesAnterior')); ?>

                    </li>
                    <li>
                        <?php echo form_radio('periodo', 'Año actual', $this->session->periodo == 'Año actual' ? true : false, 'id="añoActual"') ?>
                        <?php echo form_label(' Año Actual ', 'añoActual', array('class' => 'control-label ', 'for' => 'añoActual')); ?>

                    </li>
                    <li>
                        <?php echo form_radio('periodo', 'Año anterior', $this->session->periodo == 'Año anterior' ? true : false, 'id="añoAnterior"') ?>
                        <?php echo form_label(' Año anterior ', 'añoAnterior', array('class' => 'control-label ', 'for' => 'añoAnterior')); ?>

                    </li>

                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row ">
                <div class="col-md-4 box1 ">
                    <?php echo form_label('Un día: ', 'dia', array('class' => 'control-label ', 'for' => 'dia')); ?>
                </div>

                <?php if (strtolower($this->session->username) == 'pernilall') { ?>
                    <div class="col-md-6 box1 ">
                        <?php echo form_input(array('class' => 'form-control', 'name' => 'dia', 'id' => 'dia', 'value' => $this->session->dia, 'type' => 'date',)) ?>
                    </div>
                <?php } else { ?>
                    <div class="col-md-6 box1 ">
                        <?php echo form_input(array('class' => 'form-control', 'min' => strval(date('Y') - 1) . '-01-01', 'max' => strval(date('Y-m-d')), 'name' =>  'dia', 'id' => 'dia', 'value' => $this->session->dia, 'type' => 'date',)) ?>
                    </div>
                <?php } ?>

                <!-- <div class="col-md-6 box1 ">
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'dia', 'id' => 'dia', 'value' => $this->session->dia, 'type' => 'date',)) ?>
                </div> -->


            </div>
            <div class="row">
                <div class="col-md-4 box ">
                    <?php echo form_label('Desde fecha: ', 'inicio', array('class' => 'control-label ')); ?>
                </div>

                <?php if (strtolower($this->session->username) == 'pernilall') { ?>
                    <div class="col-md-6 box1 ">
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'inicio', 'id' => 'inicio', 'value' => $this->session->inicio, 'type' => 'date',)) ?>
                    </div>
                <?php } else { ?>
                    <div class="col-md-6 box1 ">
                    <?php echo form_input(array('class' => 'form-control', 'min' => strval(date('Y') - 1) . '-01-01', 'max' => strval(date('Y-m-d')), 'name' => 'inicio', 'id' => 'inicio', 'value' => $this->session->inicio, 'type' => 'date',)) ?>

                        <!-- <?php echo form_input(array('class' => 'form-control', 'min' => strval(date('Y') - 1) . '-01-01', 'max' => strval(date('Y-m-d')), 'name' =>  'dia', 'id' => 'dia', 'value' => $this->session->dia, 'type' => 'date',)) ?> -->
                    </div>
                <?php } ?>    

                <!-- <div class="col-md-6 box ">
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'inicio', 'id' => 'inicio', 'value' => $this->session->inicio, 'type' => 'date',)) ?>
                </div> -->

            </div>
            <div class="row">
                <div class="col-md-4 box ">
                    <?php echo form_label('Hasta fecha: ', 'final', array('class' => 'control-label ')); ?>
                </div>

                <?php if (strtolower($this->session->username) == 'pernilall') { ?>
                    <div class="col-md-6 box1 ">
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'final', 'id' => 'final', 'value' => $this->session->final, 'type' => 'date',)) ?>
                    </div>
                <?php } else { ?>
                    <div class="col-md-6 box1 ">
                    <?php echo form_input(array('class' => 'form-control', 'min' => strval(date('Y') - 1) . '-01-01', 'max' => strval(date('Y-m-d')),'name' => 'final', 'id' => 'final', 'value' => $this->session->final, 'type' => 'date',)) ?>

                        <!-- <?php echo form_input(array('class' => 'form-control', 'min' => strval(date('Y') - 1) . '-01-01', 'max' => strval(date('Y-m-d')), 'name' =>  'dia', 'id' => 'dia', 'value' => $this->session->dia, 'type' => 'date',)) ?> -->
                    </div>
                <?php } ?>




                <!-- <div class="col-md-6 box ">
                    <?php echo form_input(array('class' => 'form-control', 'name' => 'final', 'id' => 'final', 'value' => $this->session->final, 'type' => 'date',)) ?>
                </div> -->
            </div>



            <div class="row">
                <?php if (!isset($buscar)) {
                    $buscar = "buscarTickets";
                    $buscarTexto = "Buscar tickets";
                }
                ?>
                <div class="col-md-4 boxT ">
                    <button class="btn btn-default" id="<?php echo $buscar ?>"><?php echo $buscarTexto ?></button>
                </div>

                <div class="col-md-2 box " style="padding-top: 8px; padding-bottom: 24px;">
                    <img class="img-responsive ajax-loader2" style="visibility:hidden" src="<?php echo base_url('images/ajax-loader.gif') ?>">
                </div>

                <div class="col-md-6 boxF ">

                </div>
            </div>

        </div>


        <?php if (isset($seleccionPeriodosDerecha)) echo $seleccionPeriodosDerecha ?>


    </div>
</div>
<hr>
<div class="container">
    <?php if (isset($seleccionPeriodosAbajo)) echo $seleccionPeriodosAbajo ?>
</div>




</div>