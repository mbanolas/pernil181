<!-- Modal -->
<div class="modal fade" id="myModalFactura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>

                    </button>
                     <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">...</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
    
       
    })   
</script>  

<style >
.modal {
}
.vertical-alignment-helper {
    display:table;
    height: 100%;
    width: 95%;
    margin:auto;
}
.vertical-align-center {
    /* To center vertically */
    display: table-cell;
    vertical-align: middle;
}
.modal-content {
    /* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
    /*width:inherit; */
    height:inherit;
    /* To center horizontally */
    margin: 0 auto;
}
</style>