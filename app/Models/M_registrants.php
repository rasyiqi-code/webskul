<?php

namespace App\Models;

use CodeIgniter\Model;

class M_registrants extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $admissionYear;

    public function __construct()
    {
        parent::__construct();
        $year = __session('admission_year');
        $this->admissionYear = ($year !== null && $year > 0) ? $year : (int)date('Y');
    }

    public function saveRegistrationForm(array $dataset, array $subjectScores)
    {
        $this->db->transBegin();
        $this->db->table($this->table)->insert($dataset);
        $studentId = $this->db->insertID();
        $this->db->table($this->table)
            ->where($this->primaryKey, $studentId)
            ->update(['created_by' => $studentId]);
        if (count($subjectScores) > 0) {
            foreach ($subjectScores as $subjectSettingDetailId => $score) {
                $score = str_replace(' ', '', trim($score));
                $score = str_replace(',', '.', $score);
                $score = floatval($score);
                $score = number_format($score, 2, '.', '');
                if ($score > 100) $score = 0.00;
                $this->db->table('admission_subject_scores')->insert([
                    'subject_setting_detail_id' => _toInteger($subjectSettingDetailId),
                    'score' => $score,
                    'student_id' => _toInteger($studentId),
                    'created_by' => _toInteger($studentId)
                ]);
            }
        }
        return $this->db->transStatus();
    }

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $fields = [
            'x1.id',
            'IF(x1.is_transfer = "true","Pindahan", "Baru") AS is_transfer',
            'x1.registration_number',
            'x1.re_registration',
            'LEFT(x1.created_at, 10) AS created_at',
            'x1.full_name',
            'x1.birth_date',
            'x1.gender',
            'x1.photo',
            'x4.option_name AS admission_type',
            'x5.phase_name',
            'x1.is_deleted'
        ];
        if (__session('major_count') > 0) {
            $fields[] = "COALESCE(x2.major_name, '-') AS first_choice";
            $fields[] = "COALESCE(x3.major_name, '-') AS second_choice";
        }
        $builder = $this->db->table($this->table . ' x1');
        $builder->select(implode(', ', $fields));
        if (__session('major_count') > 0) {
            $builder->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
            $builder->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
        }
        $builder->join('options x4', 'x1.admission_type_id = x4.id', 'LEFT');
        $builder->join('admission_phases x5', 'x1.admission_phase_id = x5.id', 'LEFT');
        $builder->where('x1.is_prospective_student', 'true');
        $builder->where('LEFT(x1.registration_number, 4) = ', $this->admissionYear);
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.full_name', $keyword);
            $builder->orLike('x1.registration_number', $keyword);
            $builder->orLike('x1.re_registration', $keyword);
            $builder->orLike('x1.created_at', $keyword);
            if (__session('major_count') > 0) {
                $builder->orLike('x2.major_name', $keyword);
                $builder->orLike('x3.major_name', $keyword);
            }
            $builder->orLike('x1.gender', $keyword);
            $builder->orLike('x1.birth_place', $keyword);
            $builder->orLike('x1.birth_date', $keyword);
            $builder->orLike('x1.street_address', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function registrationNumber()
    {
        $admissionYear = $this->admissionYear;
        $query = $this->db->query("
            SELECT MAX(RIGHT(registration_number, 6)) AS max_number
            FROM users
            WHERE is_prospective_student='true'
            AND LEFT(registration_number, 4) = ?
        ", [$admissionYear]);
        $registrationNumber = "000001";
        if ($query->getNumRows() === 1) {
            $data = $query->getRow();
            $number = ((int)$data->max_number) + 1;
            $registrationNumber = sprintf("%06s", $number);
        }
        return $admissionYear . $registrationNumber;
    }

    public function selectionResult(string $registrationNumber, string $birthDate)
    {
        $row = $this->db->table($this->table)
            ->select('full_name, selection_result')
            ->where('registration_number', $registrationNumber)
            ->where('birth_date', $birthDate)
            ->get()
            ->getRow();
        if ($row) {
            if (is_null($row->selection_result)) {
                return ['status' => 'info', 'message' => 'Proses seleksi belum selesai.'];
            }
            if (__session('major_count') > 0) {
                if ($row->selection_result === 'unapproved') {
                    return ['status' => 'info', 'message' => 'Mohon Maaf ' . $row->full_name . '<br>Anda Tidak Lolos Seleksi Penerimaan Peserta Didik Baru ' . __session('school_profile')['school_name'] . ' Tahun ' . $this->admissionYear];
                }
                $majors = $this->db->table('majors')->where('id', $row->selection_result)->get()->getRow();
                return ['status' => 'success', 'message' => 'Selamat ' . $row->full_name . '!<br>Anda diterima di ' . ($majors ? $majors->major_name : '') . ' ' . __session('school_profile')['school_name'] . ' Tahun ' . $this->admissionYear];
            }
            if ($row->selection_result === 'unapproved') {
                return ['status' => 'info', 'message' => 'Mohon Maaf ' . $row->full_name . '<br>Anda Tidak Lolos Seleksi Penerimaan Peserta Didik Baru ' . __session('school_profile')['school_name'] . ' Tahun ' . $this->admissionYear];
            }
            return ['status' => 'success', 'message' => 'Selamat ' . $row->full_name . '!<br>Anda Lolos Seleksi Penerimaan Peserta Didik Baru ' . __session('school_profile')['school_name'] . ' Tahun ' . $this->admissionYear];
        }
        return ['status' => 'warning', 'message' => 'Data dengan tanggal lahir ' . $birthDate . ' dan nomor pendaftaran ' . $registrationNumber . ' tidak ditemukan.'];
    }

    public function findRegistrant(string $registrationNumber, string $birthDate)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.*
            , IF(x1.is_transfer='true', 'Pindahan', 'Baru') AS is_transfer
            , IF(x1.gender = 'M', 'Laki-laki', 'Perempuan') AS gender
            , x2.major_name AS first_choice
            , x3.major_name AS second_choice
            , x4.option_name AS religion
            , x5.option_name AS special_needs
            , x6.option_name AS admission_type
            , x7.phase_name
        ");
        $builder->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
        $builder->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
        $builder->join('options x4', 'x1.religion_id = x4.id', 'LEFT');
        $builder->join('options x5', 'x1.special_need_id = x5.id', 'LEFT');
        $builder->join('options x6', 'x1.admission_type_id = x6.id', 'LEFT');
        $builder->join('admission_phases x7', 'x1.admission_phase_id = x7.id', 'LEFT');
        $builder->where('x1.registration_number', $registrationNumber);
        $builder->where('x1.birth_date', $birthDate);
        return $builder->get()->getRowArray();
    }

    public function admissionReports()
    {
        $mStudents = new M_students();
        $query = $mStudents->studentQuery();
        $query .= " AND x1.is_prospective_student='true' ORDER BY x1.registration_number, x1.full_name ASC";
        return $this->db->query($query);
    }
}
