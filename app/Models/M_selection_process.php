<?php

namespace App\Models;

use CodeIgniter\Model;

class M_selection_process extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function getProspectiveStudents(int $admissionYearId = 0, int $admissionTypeId = 0, int $majorId = 0, string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $helper = new M_helper();
        $admissionYear = $helper->getAdmissionYear($admissionYearId);
        $fields = ['x1.id', 'x1.registration_number', 'x1.full_name'];
        if (__session('major_count') > 0) {
            $fields[] = "COALESCE(x2.major_name, '') AS first_choice";
            $fields[] = "COALESCE(x3.major_name, '') AS second_choice";
        }
        $builder = $this->db->table('users x1');
        $builder->select(implode(', ', $fields));
        if (__session('major_count') > 0) {
            $builder->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
            $builder->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
        }
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.is_prospective_student', 'true');
        $builder->where('x1.re_registration', 'true');
        $builder->where('x1.selection_result IS NULL');
        $builder->where('x1.admission_type_id', $admissionTypeId);
        $builder->where('LEFT(x1.registration_number, 4) = ', $admissionYear);
        if (__session('major_count') > 0) {
            $builder->where('x1.first_choice_id', $majorId);
        }
        $minBirthDate = __session('admission')['min_birth_date'] ?? null;
        $maxBirthDate = __session('admission')['max_birth_date'] ?? null;
        if ($minBirthDate && _isValidDate($minBirthDate) && $maxBirthDate && _isValidDate($maxBirthDate)) {
            $birthDates = array_date($minBirthDate, $maxBirthDate);
            if (count($birthDates) > 0) $builder->whereIn('x1.birth_date', $birthDates);
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function selectionProcess(int $admissionYearId = 0, int $admissionTypeId = 0, $selectionResult = '', array $studentIds = [])
    {
        $helper = new M_helper();
        $admissionYear = $helper->getAdmissionYear($admissionYearId);
        $admissionQuota = 0;
        $query = $this->db->table('admission_quotas')
            ->select('quota')
            ->where('academic_year_id', _toInteger($admissionYearId))
            ->where('admission_type_id', _toInteger($admissionTypeId))
            ->where('major_id', _toInteger($selectionResult))
            ->get()
            ->getRow();
        if ($query) $admissionQuota = _toInteger($query->quota);

        if ($selectionResult !== 'unapproved') {
            $approved = $this->db->table('users')
                ->where('LEFT(registration_number, 4)=', $admissionYear)
                ->where('admission_type_id', $admissionTypeId)
                ->where('is_prospective_student', 'true')
                ->groupStart()
                ->where('selection_result', $selectionResult)
                ->orWhere('selection_result', 'approved')
                ->groupEnd()
                ->countAllResults();
            if (($admissionQuota - $approved) < count($studentIds)) {
                return [
                    'status' => 'warning',
                    'message' => 'Kuota Penerimaan tidak mencukupi. Silahkan periksa kembali pengaturan Kuota Penerimaan.'
                ];
            }
        }

        $builder = $this->db->table('users');
        $builder->set('selection_result', $selectionResult);
        $builder->set('updated_by', __session('user_id'));
        if ($selectionResult !== 'unapproved') {
            $studentStatusId = get_option_id('student_status', 'aktif');
            $builder->set('is_student', 'true');
            $builder->set('student_status_id', _toInteger($studentStatusId));
        } else {
            $builder->set('is_student', 'false');
            $builder->set('student_status_id', null);
        }
        if (_toInteger($selectionResult) > 0) {
            $builder->set('major_id', _toInteger($selectionResult));
        }
        $builder->whereIn('id', $studentIds);
        $query = $builder->update();
        return [
            'status' => $query ? 'success' : 'error',
            'message' => $query ? 'Proses seleksi sudah tersimpan' : 'Proses seleksi tidak tersimpan'
        ];
    }
}
