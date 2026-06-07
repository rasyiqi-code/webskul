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
      <div class="col-md-2">
         <div class="box box-primary">
            <div class="box-body box-profile">
               <img class="profile-user-img img-responsive" <?=(isset($photo) && $photo) ? ('src="'.$photo.'"') : '' ?> alt="User profile picture">
               <h3 class="profile-username text-center"><?=$student->full_name?></h3>
               <p class="text-muted text-center"><?=$student->street_address?></p>
            </div>
         </div>
      </div>
      <div class="col-md-10">
         <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
               <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">REGISTRASI PESERTA DIDIK</a></li>
               <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">DATA PRIBADI</a></li>
               <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">DATA AYAH</a></li>
               <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">DATA IBU</a></li>
               <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">DATA WALI</a></li>
               <li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="false">DATA PERIODIK</a></li>
               <?php if ($scholarships && $scholarships->getNumRows() > 0) { ?>
                  <li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false">BEASISWA</a></li>
               <?php } ?>
               <?php if ($achievements && $achievements->getNumRows() > 0) { ?>
                  <li class=""><a href="#tab_8" data-toggle="tab" aria-expanded="false">PRESTASI</a></li>
               <?php } ?>
               <li class=""><a href="#tab_9" data-toggle="tab" aria-expanded="false">DOKUMEN</a></li>
            </ul>
            <div class="tab-content">
               <div class="tab-pane active" id="tab_1">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <?php if (service('uri')->getSegment(1) == 'admission') { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->registration_number?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tanggal Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=date('d M Y', strtotime($student->created_at))?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Jenis Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->is_transfer?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Jalur Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->admission_type?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Gelombang Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->phase_name?></p>
                              </div>
                           </div>

                           <?php if (__session('major_count') > 0) { ?>
                              <div class="form-group">
                                 <label class="col-sm-4 control-label">Pilihan Ke-1</label>
                                 <div class="col-sm-8">
                                    <p class="form-control-static"><?=$student->first_choice?></p>
                                 </div>
                              </div>

                              <div class="form-group">
                                 <label class="col-sm-4 control-label">Pilihan Ke-2</label>
                                 <div class="col-sm-8">
                                    <p class="form-control-static"><?=$student->second_choice?></p>
                                 </div>
                              </div>
                           <?php } ?>

                        <?php } ?>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Induk Siswa</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nis?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Induk Siswa Nasional / NISN</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nisn?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Sekolah Asal</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->prev_school_name?></p>
                           </div>
                        </div>
                        
                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Peserta UN SMP/MTs</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->prev_exam_number?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">No. SKHUN SMP/MTs Sebelumnya</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->skhun?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">No. Seri Ijazah SMP/MTs</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->prev_diploma_number?></p>
                           </div>
                        </div>

                        <?php if (service('uri')->getSegment(2) == 'alumni') { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tanggal Masuk</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=_isValidDate($student->start_date) ? indo_date($student->start_date) : ''?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tanggal Keluar</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=_isValidDate($student->end_date) ? indo_date($student->end_date) : ''?></p>
                              </div>
                           </div>
                        <?php } ?>
                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_2">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Lengkap</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->full_name?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jenis Kelamin</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->gender?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">NIK/ No. KITAS (Untuk WNA)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nik?></p>
                           </div>
                        </div>
                        
                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Keluarga</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->family_card_number?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tempat Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->birth_place?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tanggal Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->birth_date?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Registasi Akta Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->birth_certificate_number?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Agama dan Kepercayaan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->religion?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kewarganegaraan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->citizenship?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Negara</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->country?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Berkebutuhan Khusus</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->special_need?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Alamat Jalan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->street_address?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">RT</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->rt?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">RW</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->rw?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Dusun</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->sub_village?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Desa/Kelurahan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->village?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kecamatan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->sub_district?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kabupaten/Kota</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->district?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kode Pos</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->postal_code?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Lintang</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->latitude?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Bujur</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->longitude?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tempat Tinggal</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->residence?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Moda Transportasi</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->transportation?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Anak Keberapa</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->child_number?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan (diperuntukan untuk warga belajar)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->student_employment?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Apakah Punya KIP</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->have_kip == 'true' ? 'Ya' : 'Tidak'?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Apakah Peserta Didik Tersebut Tetap Akan Menerima KIP</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->receive_kip == 'true' ? 'Ya' : 'Tidak'?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Alasan Menolak PIP</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=isset($student->reject_pip) && in_array($student->reject_pip, array_keys(reject_pip())) ? reject_pip($student->reject_pip) : ''?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Handphone</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mobile_phone?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Telepon</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->phone?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Email Pribadi</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->email?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jenis Kesejahteraan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=isset($student->welfare_type) && in_array($student->welfare_type, array_keys(welfare_types())) ? welfare_types($student->welfare_type) : ''?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Kesejahteraan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->welfare_number?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama di Kartu Kesejahteraan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->welfare_name?></p>
                           </div>
                        </div>

                        

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_3">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_name?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">NIK Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_nik?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tempat Lahir Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_birth_place?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tanggal Lahir Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_birth_date?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pendidikan Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_education?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_employment?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Penghasilan Bulanan Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_monthly_income?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kebutuhan Khusus Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_special_need?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_4">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_name?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">NIK Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_nik?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tempat Lahir Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_birth_place?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tanggal Lahir Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_birth_date?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pendidikan Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_education?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_employment?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Penghasilan Bulanan Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_monthly_income?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kebutuhan Khusus Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_special_need?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_5">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_name?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">NIK Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_nik?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tempat Lahir Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_birth_place?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tanggal Lahir Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_birth_date?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pendidikan Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_education?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_employment?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Penghasilan Bulanan Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_monthly_income?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_6">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tinggi Badan (Cm)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->height?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Berat Badan (Kg)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->weight?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Lingkar Kepala (Cm)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->head_circumference?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jarak Tempat Tinggal ke Sekolah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mileage?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Waktu Tempuh ke Sekolah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->traveling_time?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jumlah Saudara Kandung</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->sibling_number?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <?php if ($scholarships && $scholarships->getNumRows() > 0) { ?>
                  <div class="tab-pane" id="tab_7">
                     <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed">
                           <thead>
                              <tr>
                                 <th>NAMA BEASISWA</th>
                                 <th>JENIS BEASISWA</th>
                                 <th>TAHUN MULAI</th>
                                 <th>TAHUN SELESAI</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($scholarships->getResult() as $row) { ?>
                                 <tr>
                                    <td><?=$row->scholarship_description?></td>
                                    <td><?=scholarship_types($row->scholarship_type)?></td>
                                    <td><?=$row->scholarship_start_year?></td>
                                    <td><?=$row->scholarship_end_year?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               <?php } ?>
               <?php if ($achievements && $achievements->getNumRows() > 0) { ?>
                  <div class="tab-pane" id="tab_8">
                     <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed">
                           <thead>
                              <tr>
                                 <th width="10px">NO</th>
                                 <th>NAMA PRESTASI</th>
                                 <th>JENIS PRESTASI</th>
                                 <th>TINGKAT</th>
                                 <th>TAHUN</th>
                                 <th>PENYELENGGARA</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $no = 1; foreach($achievements->getResult() as $row) { ?>
                                 <tr>
                                    <td align="center"><?=$no?></td>
                                    <td><?=$row->achievement_description?></td>
                                    <td><?=achievement_types($row->achievement_type)?></td>
                                    <td><?=achievement_levels($row->achievement_level)?></td>
                                    <td><?=$row->achievement_year?></td>
                                    <td><?=$row->achievement_organizer?></td>
                                 </tr>
                              <?php $no++; } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               <?php } ?>
               <div class="tab-pane" id="tab_9">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <div class="form-group">
                           <label class="col-sm-4 control-label">Photo</label>
                           <div class="col-sm-8">
                              <?php if ( ! empty($student->photo)) { ?>
                                 <a target="_blank" href="<?=base_url('media_library/users/photo/' . $student->photo)?>" class="btn btn-success"><i class="fa fa-download"></i> DOWNLOAD PHOTO</a>
                              <?php } ?>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kartu Keluarga</label>
                           <div class="col-sm-8">
                              <?php if ( ! empty($student->family_card)) { ?>
                                 <a target="_blank" href="<?=base_url('media_library/users/family_card/' . $student->family_card)?>" class="btn btn-success"><i class="fa fa-download"></i> DOWNLOAD KARTU KELUARGA</a>
                              <?php } ?>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label">Akta Lahir</label>
                           <div class="col-sm-8">
                              <?php if ( ! empty($student->birth_certificate)) { ?>
                                 <a target="_blank" href="<?=base_url('media_library/users/birth_certificate/' . $student->birth_certificate)?>" class="btn btn-success"><i class="fa fa-download"></i> DOWNLOAD AKTA LAHIR</a>
                              <?php } ?>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label">KTP Ayah</label>
                           <div class="col-sm-8">
                              <?php if ( ! empty($student->father_identity_card)) { ?>
                                 <a target="_blank" href="<?=base_url('media_library/users/father_identity_card/' . $student->father_identity_card)?>" class="btn btn-success"><i class="fa fa-download"></i> DOWNLOAD KTP AYAH</a>
                              <?php } ?>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label">KTP Ibu</label>
                           <div class="col-sm-8">
                              <?php if ( ! empty($student->mother_identity_card)) { ?>
                                 <a target="_blank" href="<?=base_url('media_library/users/mother_identity_card/' . $student->mother_identity_card)?>" class="btn btn-success"><i class="fa fa-download"></i> DOWNLOAD KTP IBU</a>
                              <?php } ?>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label">KTP Wali</label>
                           <div class="col-sm-8">
                              <?php if ( ! empty($student->guardian_identity_card)) { ?>
                                 <a target="_blank" href="<?=base_url('media_library/users/guardian_identity_card/' . $student->guardian_identity_card)?>" class="btn btn-success"><i class="fa fa-download"></i> DOWNLOAD KTP WALI</a>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
