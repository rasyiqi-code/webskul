<script type="text/javascript" src="<?=base_url('assets/plugins/Chart.js')?>"></script>
<script type="text/javascript">
   $(document).ready(function() {
      generate_chart();
   });

   function generate_chart() {
      var ctx = $('#build_chart');
      var buildChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: <?=$labels?>,
              datasets: [{
                  label: '',
                  data: <?=$data?>,
                  borderWidth: 2,
                  backgroundColor: 'rgba(75, 192, 192, 0.2)',
                  borderColor: 'rgba(75, 192, 192, 1)'
              }]
          },
          options: {
            title: {
               display: true,
               text: '<?=$title?>'
            },
            responsive: true,
            scales: {
               yAxes: [{
                   ticks: {
                       beginAtZero:true
                   }
               }]
          }
         }
      });
   }
</script>
<section class="content-header">
   <div class="header-icon">
      <i class="fa fa-sign-out"></i>
   </div>
   <div class="header-title">
      <p class="table-header"><?=isset($title) ? $title : ''?></p>
      <?=isset($sub_title) ? '<small>'.$sub_title.'</small>' : ''?>
   </div>
</section>
 <section class="content">
 	<div class="box">
		<div class="box-body">
			<canvas id="build_chart"></canvas>
		</div>
	</div>
 </section>
