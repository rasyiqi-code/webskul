<script type="text/javascript" src="<?=base_url('assets/plugins/Chart.js')?>"></script>
<script type="text/javascript">
$( document ).ready( function() {
	var element_id = document.getElementById('canvas');
	new Chart(element_id, {
		type: 'bar',
		data: {
			labels: <?=$labels;?>,
			datasets: [{
				label: 'Jumlah Suara',
				data: <?=$data;?>,
				borderWidth: 2,
				backgroundColor: 'rgba(37, 99, 235, 0.25)',
				borderColor: 'rgba(37, 99, 235, 1)',
				hoverBackgroundColor: 'rgba(6, 182, 212, 0.4)',
				hoverBorderColor: 'rgba(6, 182, 212, 1)'
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						stepSize: 1
					},
					gridLines: {
						color: '#f1f5f9'
					}
				}],
				xAxes: [{
					gridLines: {
						display: false
					}
				}]
			}
		}
	});
});
</script>
<div class="col-lg-8 col-md-7 col-sm-12">
	<div class="polling-header mb-4">
		<span class="section-tagline">Opini Publik</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider"></div>
	</div>

	<div class="polling-chart-card bg-white rounded-custom shadow-sm-custom border p-4 p-md-5 mb-4">
		<h5 class="font-weight-bold text-dark mb-4 text-center">Grafik Perolehan Suara Jajak Pendapat</h5>
		<canvas id="canvas" height="180"></canvas>
	</div>
</div>
<?php echo $this->include('themes/1/sidebar')?>
