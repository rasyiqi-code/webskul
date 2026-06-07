<?php

namespace App\Controllers\Teacher;

use App\Controllers\AdminController;

class Meeting_attendences extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'meeting_attendences';
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $employment_type = session('employment_type');
            if ($employment_type !== null && strpos(strtolower($employment_type), 'guru') === false) {
                return redirect()->to(base_url('dashboard'));
            }
            $meeting_attendences = json_decode($this->request->getPost('meeting_attendences'), true);
            $query = model('App\Models\M_meeting_attendences')->update($meeting_attendences);
            $this->vars['status'] = $query > 0 ? 'success' : 'error';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function get_meeting_attendences()
    {
        if ($this->request->isAJAX()) {
            $this->vars['meeting_attendences'] = [];
            $course_class_id = _toInteger($this->request->getPost('course_class_id'));
            $date = $this->request->getPost('date');
            if (_isNaturalNumber($course_class_id) && _isValidDate($date)) {
                $class_meeting_id = model('App\Models\M_class_meetings')->class_meeting_id($course_class_id, $date);
                $query = model('App\Models\M_helper')->rowObject('id', $course_class_id, 'course_classes');
                model('App\Models\M_meeting_attendences')->insert($class_meeting_id, $query->academic_year_id, $query->class_group_id);
                if ($class_meeting_id && $class_meeting_id > 0 && ctype_digit((string)$class_meeting_id)) {
                    $this->vars['status'] = 'success';
                    $this->vars['meeting_attendences'] = model('App\Models\M_meeting_attendences')->get_meeting_attendences($class_meeting_id);
                }
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
