<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BaseModel kompatibilitas CI3 untuk seluruh model aplikasi.
 *
 * Menambahkan:
 *  - get_where() / get_rows() : alias CI3 yang sering dipanggil di controller.
 *  - __call() override untuk menerjemahkan pemanggilan snake_case ke method
 *    builder/query yang tersedia, sehingga controller hasil porting CI3
 *    dapat berjalan tanpa perubahan besar.
 */
class BaseModel extends Model
{
    /**
     * Pemetaan alias CI3 -> method CI4 di Model/Builder/DB.
     * Diisi ulang oleh subclass bila perlu.
     *
     * @var array<string,string>
     */
    protected $legacyAliases = [];

    public function __call(string $name, array $params)
    {
        if (array_key_exists($name, $this->legacyAliases)) {
            return $this->{$this->legacyAliases[$name]}(...$params);
        }

        // Auto-translate snake_case CI3 -> camelCase PHP method yang ada di subclass.
        // Contoh: row_array() akan mencari rowArray(), last_active() akan mencari lastActive(), dst.
        if (str_contains($name, '_') && !method_exists($this, $name)) {
            $camel = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
            if (method_exists($this, $camel)) {
                return $this->{$camel}(...$params);
            }
        }

        return parent::__call($name, $params);
    }

    /**
     * CI3-style get_where().
     * Pemanggil biasanya: $this->model->get_where($keyword, $otherField, 'rows', $limit, $offset)
     * atau:                  $this->model->get_where($keyword, $otherField) // count
     *
     * Method ini HARUS di-override di subclass dengan signature/domain yang spesifik
     * (lihat M_options::getWhere, M_pages::getWhere, dll). Di sini hanya menjadi
     * fallback agar pemanggilan tidak meledak jika subclass belum override.
     */
    public function get_where(...$params)
    {
        if (method_exists($this, 'getWhere')) {
            return $this->getWhere(...$params);
        }

        throw new \BadMethodCallException(
            static::class . '::get_where() tidak terdefinisi. Silakan override di subclass.'
        );
    }

    /**
     * CI3-style get_rows() yang mengembalikan array baris.
     * Default implementasi menggunakan findAll() agar aman.
     */
    public function get_rows(...$params)
    {
        if (method_exists($this, 'getRows')) {
            return $this->getRows(...$params);
        }

        if (isset($params[0]) && is_array($params[0])) {
            return $this->where($params[0])->findAll();
        }

        return $this->findAll();
    }
}
