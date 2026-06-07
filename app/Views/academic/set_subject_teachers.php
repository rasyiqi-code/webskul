<style type="text/css">
	input[type="checkbox"] {
		width: 20px;
		height: 20px;
	}
</style>
<script type="text/javascript">
	$(document).ready(function() {
		$(".select2").select2({ width: '100%' });
	});

	// Employees
	DS.Employees = _H.StrToObject('<?=$teachers?>');

	/**
	* Get Subjects From Course Classes
	*/
	function getSubjects() {
		_H.Loading( true );
		var values = {
			academic_year_id: $('#academic_year_id').val(),
			semester: $('#semester').val(),
			class_group_id: $('#class_group_id').val()
		}
		$.post(_BASE_URL + 'academic/subject_teachers/get_subjects', values, function( response ) {
			var res = _H.StrToObject( response );
			var subjects = res.subjects;
			var str = '';
			if (subjects.length) {
				$('.save-subject-teachers').removeAttr('disabled');
				str += '<thead class="header">';
				str += '<tr>';
				str += '<th width="30px">NO</th>';
				str += '<th>MATA PELAJARAN</th>';
				str += '<th>GURU PENGAJAR</th>';
				str += '</tr>';
				str += '</thead>';
				str += '<tbody>';
				var no = 1;
				for (var z in subjects) {
					var subject = subjects[ z ];
					str += '<tr>';
					str += '<td>' + no + '.</td>';
					str += '<td>' + subject.subject_name + '</td>';
					str += '<td>';
					str += '<select class="form-control select2" id="cc_' + subject.id + '">';
					for (var y in DS.Employees) {
						var selected = '';
						if (y == subject.employee_id) {
							selected = 'selected="selected"';
						}
						str += '<option value="'+ y +'" ' + selected + '>' + DS.Employees[ y ] + '</option>';
					}
					str += '</select>';
					str += '</td>';
					str += '</tr>';
					no++;
				}
				str += '</tbody>';
				$('.subject-teachers').empty().html(str);
				$(".select2").select2({ width: '100%' });
			} else {
				$('.subject-teachers').empty();
				$('.save-subject-teachers').attr('disabled', 'disabled');
			}
			_H.Loading( false );
		});
	}

	// Save Subject Teachers
	function saveSubjectTeachers() {
		_H.Loading( true );
		$('.save-subject-teachers').attr('disabled', 'disabled');
		var rows = $('.subject-teachers').find(':input');
		var course_classes = [];
		rows.each(function() {
			course_classes.push({
				id:this.id.split('_')[ 1 ],
				employee_id:$(this).val()
			});
		});

		if (course_classes.length) {
			var values = {
				course_classes: JSON.stringify(course_classes)
			};
			$.post(_BASE_URL + 'academic/subject_teachers/save', values, function( response ) {
				var res = _H.StrToObject( response );
				_H.Notify(res.status, res.message);
				_H.Loading( false );
				$('.save-subject-teachers').removeAttr('disabled');
				getSubjects();
			});
		} else {
			_H.Notify('info', 'Tidak ada data yang tersimpan');
		}
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
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="callout">
         	<h4>Petunjuk Singkat</h4>
      		<ul>
      			<li>Pilih <strong>Tahun Pelajaran</strong>, <strong>Semester</strong>, dan <strong>Kelas</strong> yang akan diatur Guru Mata Pelajarannya, kemudian klik tombol <strong>"CARI DATA"</strong></li>
      			<li>Setelah data tampil, pada kolom <strong>"GURU PENGAJAR"</strong> silahkan dipilih Guru Pengajar sesuai Mata Pelajaran di kolom <strong>"MATA PELAJARAN"</strong></li>
      			<li>Jika sudah diatur semua, terakhir klik tombol <strong>"SIMPAN PERUBAHAN"</strong></li>
      		</ul>
      	</div>
			<div class="box">
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="academic_year_id" class="col-sm-3 control-label">Tahun Pelajaran</label>
							<div class="col-sm-9">
								<?=form_dropdown('academic_year_id', $academic_year_dropdown, __session('current_academic_year_id'), 'class="form-control select2" id="academic_year_id"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="semester" class="col-sm-3 control-label">Semester</label>
							<div class="col-sm-9">
								<?=form_dropdown('semester', ['odd' => 'Ganjil', 'even' => 'Genap'], '', 'class="form-control select2" id="semester"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="class_group_id" class="col-sm-3 control-label">Kelas</label>
							<div class="col-sm-9">
								<?=form_dropdown('class_group_id', $class_group_dropdown, '', 'class="form-control select2" id="class_group_id"');?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<div class="btn-group">
									<button type="button" onclick="getSubjects(); return false;" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> CARI DATA</button>
									<button disabled="disabled" type="button" onclick="saveSubjectTeachers(); return false;" class="btn btn-sm btn-success save-subject-teachers"><i class="fa fa-save"></i> SIMPAN PERUBAHAN</button>
								</div>
							</div>
						</div>
					</form>
					<div class="table-responsive">
						<table class="table table-striped table-condensed subject-teachers"></table>
					</div>
				</div>
			</div>
		</div>
	 </div>
 </section>
