<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Academic_years extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'academic_years';
    }

    public function index()
    {
        $data['title'] = 'Tahun Pelajaran';
        $data['academic'] = $data['academic_references'] = $data['academic_years'] = true;
        $data['content'] = 'academic/academic_years';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_academic_years = model('App\Models\M_academic_years');
            $query = $m_academic_years->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_academic_years->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = $query->getResult();
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if ($this->validation()) {
                $dataset = $this->dataset();
                if (_isNaturalNumber($id)) {
                    $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
                    if ($this->vars['status'] == 'success' && filter_var($dataset['current_semester'], FILTER_VALIDATE_BOOLEAN)) {
                        model('App\Models\M_academic_years')->deactivate($id, 'current_semester');
                    }
                    if ($this->vars['status'] == 'success' && filter_var($dataset['admission_semester'], FILTER_VALIDATE_BOOLEAN)) {
                        model('App\Models\M_academic_years')->deactivate($id, 'admission_semester');
                    }
                } else {
                    if (filter_var($dataset['current_semester'], FILTER_VALIDATE_BOOLEAN)) {
                        model('App\Models\M_academic_years')->deactivate(0, 'current_semester');
                    }
                    if (filter_var($dataset['admission_semester'], FILTER_VALIDATE_BOOLEAN)) {
                        model('App\Models\M_academic_years')->deactivate(0, 'admission_semester');
                    }
                    $this->vars['status'] = model('App\Models\M_helper')->insertData($this->table, $dataset) ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'created' : 'not_created';
                }
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = $this->validation->getErrors();
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function dataset()
    {
        return [
            'academic_year' => $this->request->getPost('academic_year'),
            'semester' => $this->request->getPost('semester'),
            'current_semester' => $this->request->getPost('current_semester'),
            'admission_semester' => $this->request->getPost('admission_semester')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'academic_year' => ['label' => 'Tahun Pelajaran', 'rules' => 'trim|required|min_length[9]|max_length[9]|callback_format_check'],
            'semester' => ['label' => 'Semester', 'rules' => 'trim|required'],
            'current_semester' => ['label' => 'Semester Aktif', 'rules' => 'trim|required|in_list[true,false]'],
            'admission_semester' => ['label' => 'Semester PPDB', 'rules' => 'trim|required|in_list[true,false]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function format_check($str)
    {
        $year = explode('-', substr($str, 0, 9));
        if (FALSE === strpos($str, '-')) {
            $this->validation->setError('format_check', 'Tahun awal dan tahun akhir harus dipisah dengan tanda strip (-)');
            return false;
        } else if (strlen($str) !== 9) {
            $this->validation->setError('format_check', 'Format tahun pelajaran harus 9 digit. Contoh : 2017-2018');
            return false;
        } else if ((int)$year[0] === 0 || (int)$year[1] === 0) {
            $this->validation->setError('format_check', 'Format tahun pelajaran harus diisi angka. Contoh : 2017-2018');
            return false;
        } else if (($year[1] - $year[0]) != 1) {
            $this->validation->setError('format_check', 'Tahun awal dan tahun akhir harus selisih satu !');
            return false;
        } else if (strlen($year[0]) != 4 && strlen($year[1] != 4)) {
            $this->validation->setError('format_check', 'Tahun harus 4 karakter !');
            return false;
        }
        return true;
    }
}
