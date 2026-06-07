<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Exam_card extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function pdf()
    {
        $id = service('uri')->getSegment(4);
        if (!_isNaturalNumber($id)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $query = model('App\Models\M_helper')->rowObject('id', $id, 'users');
        if (!is_object($query)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $res = model('App\Models\M_registrants')->find_registrant($query->registration_number, $query->birth_date);
        $schedules = model('App\Models\M_admission_exam_schedule_details')->get_student_exam_schedules($id);
        if (!is_object($schedules)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $file_name = 'kartu-peserta-ujian-penerimaan-peserta-didik-baru-tahun-' . substr($query->registration_number, 0, 4);
        $file_name .= '-' . $query->registration_number . '.pdf';
        $exam_cards = service('exam_cards');
        $exam_cards->pdf($res, $schedules, $file_name);
    }
}
