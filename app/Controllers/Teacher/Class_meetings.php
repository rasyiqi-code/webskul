<?php

namespace App\Controllers\Teacher;

use App\Controllers\AdminController;

class Class_meetings extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'class_meetings';
    }

    public function index()
    {
        $employment_type = session('employment_type');
        if ($employment_type !== null && strpos(strtolower($employment_type), 'guru') === false) {
            return redirect()->to(base_url('dashboard'));
        }
        $id = _toInteger(service('uri')->getSegment(4));
        $query = model('App\Models\M_helper')->rowObject('id', $id, 'course_classes');
        if (is_object($query) &&
            session('current_academic_year_id') !== null &&
            _toInteger($query->academic_year_id) === _toInteger(session('current_academic_year_id')) &&
            session('user_id') !== null &&
            _toInteger(session('user_id')) === _toInteger($query->employee_id)
        ) {
            $data['title'] = 'Masuk Kelas';
            $data['academic_schedules'] = true;
            $data['query'] = model('App\Models\M_course_classes')->get_course_classes_by_id($id);
            $data['content'] = 'teacher/class_meetings';
            return view('backend/index', $data);
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function is_exists()
    {
        if ($this->request->isAJAX()) {
            $this->vars['status'] = 'error';
            $this->vars['message'] = 'ID bukan tipe angka dan/atau tanggal bukan format yang benar';
            $course_class_id = _toInteger($this->request->getPost('course_class_id'));
            $date = $this->request->getPost('date');
            if (_isNaturalNumber($course_class_id) && _isValidDate($date)) {
                $this->vars['is_exists'] = model('App\Models\M_class_meetings')->is_exists($course_class_id, $date);
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function insert()
    {
        if ($this->request->isAJAX()) {
            $this->vars['status'] = 'error';
            $this->vars['message'] = 'ID bukan tipe angka dan/atau tanggal bukan format yang benar';
            $course_class_id = _toInteger($this->request->getPost('course_class_id'));
            $date = $this->request->getPost('date');
            if (_isNaturalNumber($course_class_id) && _isValidDate($date)) {
                $this->vars['status'] = model('App\Models\M_class_meetings')->insert($course_class_id, $date) ? 'success' : 'error';
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $this->vars['status'] = 'error';
            $this->vars['message'] = 'ID bukan tipe angka dan/atau tanggal bukan format yang benar';
            $course_class_id = _toInteger($this->request->getPost('course_class_id'));
            $date = $this->request->getPost('date');
            if (_isNaturalNumber($course_class_id) && _isValidDate($date)) {
                $query = model('App\Models\M_class_meetings')->update($this->dataset(), $course_class_id, $date);
                $this->vars['status'] = $query ? 'success' : 'error';
                $this->vars['method'] = 'update';
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function dataset()
    {
        return [
            'start_time' => $this->request->getPost('start_time') ? $this->request->getPost('start_time') : date('H:i:s'),
            'end_time' => $this->request->getPost('end_time') ? $this->request->getPost('end_time') : date('H:i:s'),
            'discussion' => $this->request->getPost('discussion') ? $this->request->getPost('discussion') : null,
        ];
    }

    public function get_class_meetings()
    {
        if ($this->request->isAJAX()) {
            $this->vars['status'] = 'error';
            $this->vars['message'] = 'ID bukan tipe angka dan/atau tanggal bukan format yang benar';
            $course_class_id = _toInteger($this->request->getPost('course_class_id'));
            $date = $this->request->getPost('date');
            if (_isNaturalNumber($course_class_id) && _isValidDate($date)) {
                $class_meeting = model('App\Models\M_class_meetings')->get_class_meetings($course_class_id, $date);
                $this->vars['date'] = $class_meeting->date;
                $this->vars['start_time'] = $class_meeting->start_time;
                $this->vars['end_time'] = $class_meeting->end_time;
                $this->vars['discussion'] = $class_meeting->discussion;
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function print_meeting_attendance()
    {
        $course_class_id = service('uri')->getSegment(4);
        $date = service('uri')->getSegment(5);
        if (_isNaturalNumber($course_class_id) && _isValidDate($date)) {
            $class_meeting = model('App\Models\M_class_meetings')->get_class_meetings($course_class_id, $date);
            $params['date'] = indo_date($class_meeting->date);
            $params['time'] = $class_meeting->start_time . ' - ' . $class_meeting->end_time;
            $params['discussion'] = $class_meeting->discussion;
            $course_class = model('App\Models\M_course_classes')->get_course_classes_by_id($course_class_id);
            $params['academic_year'] = $course_class->academic_year;
            $params['semester'] = $course_class->semester;
            $params['subject_name'] = $course_class->subject_name;
            $params['class_group'] = $course_class->class_group;
            $params['full_name'] = $course_class->full_name;
            $class_meeting_id = model('App\Models\M_class_meetings')->class_meeting_id($course_class_id, $date);
            $params['students'] = model('App\Models\M_meeting_attendences')->get_meeting_attendences($class_meeting_id);
            $file_name = 'laporan-data-kehadiran-siswa-';
            $file_name .= $course_class->academic_year . '-';
            $file_name .= strtolower($course_class->semester) . '-';
            $file_name .= strtolower(str_replace(' ', '-', $course_class->subject_name)) . '-';
            $file_name .= strtolower(str_replace(' ', '', $course_class->class_group)) . '-';
            $file_name .= $class_meeting->date;
            $file_name .= '-' . time() . '.pdf';
            $params['file_name'] = $file_name;
            $meeting_attendances = service('meeting_attendances');
            $meeting_attendances->pdf($params);
        }
    }
}
