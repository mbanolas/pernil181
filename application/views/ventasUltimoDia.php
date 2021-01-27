<?php //var_dump($ventasUltimoDia) ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
         ['Ventas', 'Total', { role: 'style' }, { role: 'annotation' } ],
         ['<?php echo $ventasUltimoDia['ultimoDiaTickets']?>', <?php echo $ventasUltimoDia['totalTickets']?>, 'color: #DC524A', 'Tienda' ],
         ['<?php echo $ventasUltimoDia['ultimoDiaPrestaShop']?>', <?php echo $ventasUltimoDia['totalPrestaShop']?>, 'color: #289F61', 'Online' ],
         ['<?php echo $ventasUltimoDia['ultimoDiaVentaDirecta']?>',<?php echo $ventasUltimoDia['totalVentasDirectas']?>, 'color: #FCCC50', 'Directa' ]
      ]);

      var view = new google.visualization.DataView(data);
      

      var options = {
        title: "Ventas totales último día reportado",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  }
  </script>
<div id="columnchart_values" style="width: 900px; height: 300px;"></div>
