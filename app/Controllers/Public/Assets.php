<?php

namespace App\Controllers\Public;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Controller Assets
 * Menangani request berkas aset statis (CSS, JS, Gambar) dari folder tema privat
 * agar aset dan template terkumpul di satu tempat tanpa menggunakan symlink.
 */
class Assets extends Controller
{
    /**
     * Melayani berkas aset dari folder tema secara dinamis.
     *
     * @param string $themeFolder Nama folder tema (misal: 1, 2, dll)
     * @param string $filename Nama berkas aset (termasuk subdirektori)
     * @return ResponseInterface
     */
    public function theme(string $themeFolder, string $filename): ResponseInterface
    {
        // Bersihkan nama berkas untuk menghindari directory traversal (akses ../)
        $cleanFilename = str_replace(['../', '..\\'], '', $filename);
        
        // Path absolut ke berkas di folder privat
        $filePath = APPPATH . 'Views/themes/' . $themeFolder . '/assets/' . $cleanFilename;

        // Validasi apakah berkas ada
        if (!is_file($filePath)) {
            return $this->response->setStatusCode(404)->setBody('Aset tidak ditemukan.');
        }

        // Dapatkan ekstensi file dan tentukan Content-Type header
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $contentType = $this->getMimeType($extension);

        $fileContent = file_get_contents($filePath);

        // Kirim berkas dengan cache control agar performa tetap optimal
        return $this->response
            ->setHeader('Content-Type', $contentType)
            ->setHeader('Cache-Control', 'public, max-age=31536000') // Cache 1 tahun
            ->setBody($fileContent);
    }

    /**
     * Mencocokkan ekstensi file dengan mime-type yang sesuai.
     *
     * @param string $extension Ekstensi berkas
     * @return string Mime-type
     */
    private function getMimeType(string $extension): string
    {
        $mimeTypes = [
            'css'   => 'text/css',
            'js'    => 'application/javascript',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'gif'   => 'image/gif',
            'svg'   => 'image/svg+xml',
            'ico'   => 'image/x-icon',
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
            'otf'   => 'font/otf',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
