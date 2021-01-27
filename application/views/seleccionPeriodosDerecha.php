  <div class="col-md-5"  id='cuadroResultados' style="background-color: #f2f2f2; display:none" >
            <div class="row  " style='padding-top:12px'>
                <div class="col-md-4  " >
                    <?php echo form_label('Número tickets: ', 'numTickets', array('class' => 'control-label ')); ?>
                </div> 
                <div class="col-md-6  " >
                    <?php echo form_label('', 'numTickets', array('class' => 'control-label ','id'=>'numTickets')); ?> 
                </div> 
            </div>
            <div class="row  ">
                <div class="col-md-12  " >
                    <?php echo form_label('Seleccionar por fecha ticket: ', 'numTickets', array('class' => 'control-label ')); ?>
                </div> 
            </div>
            <div class="row  ">
                <div class="col-md-12 " style="padding-bottom: 68px" >
                    <?php echo form_dropdown('tickets', $this->session->ticketsPeriodo, $this->session->tickets, array('data-toggle' => "dropdown", 'class' => 'form-control btn btn-default dropdown-toggle ', 'id' => 'tickets')); ?>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-4  " >
       <!--           <?php echo anchor('conversion/mostrarTicketModificar', 'Mostrar Tickets para modificarlos', array('class' => "btn btn-default")); ?>
            -->   
               <button class="btn btn-default" id="mostrarTickets" ><?php echo $nombreBoton ?></button>
                </div> 
                <div class="col-md-6  " >
                    
                </div> 
            </div>
        </div>