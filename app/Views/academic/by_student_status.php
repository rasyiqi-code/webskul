<script type="text/javascript" src="<?=base_url('assets/plugins/Chart.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		generate_chart();
	});

	function generate_chart() {
		var values = {
			academic_year_id: $('#academic_year_id').val()
		}
		$.post(_BASE_URL + 'academic/by_student_status/generate_chart', values, function( response ) {
			var ctx = $('#build_chart');
			var buildChart = new Chart(ctx, {
			    type: 'bar',
			    data: {
			        labels: response.labels,
			        datasets: [{
			            label: '',
			            data: response.data,
			            borderWidth: 2,
			            backgroundColor: 'rgba(75, 192, 192, 0.2)',
			            borderColor: 'rgba(75, 192, 192, 1)'
			        }]
			    },
			    options: {
					title: {
					   display: true,
					   text:response.title + ' ' + $('#academic_year_id option:selected').text()
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
			<form class="form-inline">
				<div class="form-group">
					<select class="form-control" id="academic_year_id">
						<?php foreach($academic_year_dropdown as $key => $value) { ?>
							<option value="<?=$key?>" <?=$key==__session('current_academic_year_id') ? 'selected':''?>><?=$value?></option>
						<?php } ?>
					</select>
				</div>
				<button type="submit" class="btn btn-default" onclick="generate_chart(); return false;"><i class="fa fa-search"></i> TAMPILKAN</button>
			</form>
			<canvas id="build_chart"></canvas>
		</div>
	</div>
 </section>
