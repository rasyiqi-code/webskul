<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model Helper
 * Menyediakan operasi CRUD generik yang dapat dipakai oleh semua controller.
 * Method insert() CI4 Model sengaja tidak di-override; gunakan insertData() untuk
 * menghindari konflik dengan signature parent::insert().
 */
class M_helper extends Model
{
    protected $table      = '';
    protected $primaryKey = 'id';

    /**
     * Insert data baru dengan pencatatan created_by.
     * Menggunakan nama insertData() (bukan insert()) agar tidak konflik
     * dengan method parent CodeIgniter\Model::insert().
     */
    public function insertData(string $table, array $dataset): bool
    {
        $dataset['created_by'] = session('user_id');
        $db = db_connect();
        $db->transBegin();
        $db->table($table)->insert($dataset);

        return $db->transStatus();
    }

    /**
     * Update data berdasarkan ID dengan pencatatan updated_by.
     */
    public function updateData(int $id, string $table, array $dataset): bool
    {
        $dataset['updated_by'] = session('user_id');
        $db = db_connect();
        $db->transBegin();
        $db->table($table)->where('id', $id)->update($dataset);

        return $db->transStatus();
    }

    /**
     * Insert atau Update berdasarkan ID (jika ID valid = update, jika tidak = insert).
     */
    public function upsert(int $id, string $table, array $dataset): bool
    {
        $dataset[(_isNaturalNumber($id) ? 'updated_by' : 'created_by')] = session('user_id');
        $db = db_connect();
        $db->transBegin();

        if (_isNaturalNumber($id)) {
            $db->table($table)->where('id', $id)->update($dataset);
        } else {
            $db->table($table)->insert($dataset);
        }

        return $db->transStatus();
    }

    /**
     * Hapus permanen berdasarkan key-value.
     */
    public function deletePermanently(string $key, array $value, string $table): bool
    {
        $db = db_connect();
        $db->transBegin();
        $db->table($table)->whereIn($key, $value)->delete();

        return $db->transStatus();
    }

    /**
     * Soft delete — set is_deleted ke 'true'.
     */
    public function softDelete(array $ids, string $table): bool
    {
        $db = db_connect();
        $db->transBegin();
        $db->table($table)->whereIn('id', $ids)->update([
            'is_deleted' => 'true',
            'deleted_by' => session('user_id'),
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        return $db->transStatus();
    }

    /**
     * Truncate (kosongkan) tabel.
     */
    public function truncateTable(string $table): bool
    {
        $db = db_connect();
        $db->transBegin();
        $db->table($table)->truncate();

        return $db->transStatus();
    }

    /**
     * Restore data — set is_deleted ke 'false'.
     */
    public function restore(array $ids, string $table): bool
    {
        $db = db_connect();
        $db->transBegin();
        $db->table($table)->whereIn('id', $ids)->update([
            'is_deleted'  => 'false',
            'restored_by' => session('user_id'),
            'restored_at' => date('Y-m-d H:i:s'),
        ]);

        return $db->transStatus();
    }

    /**
     * Cek apakah nilai ada di tabel.
     */
    public function isExists(string $key, string $value, string $table): bool
    {
        $db    = db_connect();
        $count = $db->table($table)->where($key, $value)->countAllResults();

        return $count > 0;
    }

    /**
     * Ambil satu baris data sebagai object.
     */
    public function rowObject(string $key, $value, string $table): ?object
    {
        $db = db_connect();

        return $db->table($table)->where($key, $value)->get()->getRow();
    }

    /**
     * Hapus sesi yang sudah expired dan catatan login attempts lama.
     */
    public function clearExpiredSession(): void
    {
        $db = db_connect();
        $db->query("DELETE FROM `_sessions` WHERE DATE_FORMAT(FROM_UNIXTIME(timestamp), '%Y-%m-%d') < CURRENT_DATE");
        $db->query("DELETE FROM `login_attempts` WHERE DATE_FORMAT(created_at, '%Y-%m-%d') < CURRENT_DATE");
    }

    /**
     * Dapatkan tahun akademik berdasarkan ID.
     */
    public function getAdmissionYear(int $admission_year_id): string
    {
        $admission_year = date('Y');
        $db             = db_connect();
        $query          = $db->table('academic_years')
            ->select('academic_year')
            ->where('id', $admission_year_id)
            ->get()
            ->getRow();

        if ($query) {
            $admission_year = substr($query->academic_year, 0, 4);
        }

        return $admission_year;
    }
}
