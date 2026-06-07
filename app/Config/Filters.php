<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => \App\Filters\AuthFilter::class,
        'privilege'     => \App\Filters\PrivilegeFilter::class,
        'maintenance'   => \App\Filters\MaintenanceFilter::class,
    ];

    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    public array $globals = [
        'before' => [],
        'after' => [],
    ];

    public array $methods = [];

    /**
     * Filter berdasarkan pattern URI.
     * Berlaku untuk semua URL yang cocok dengan pattern (termasuk auto-routed URL),
     * sehingga seluruh area admin tetap terlindungi tanpa harus menulis route eksplisit
     * untuk setiap method.
     */
    public array $filters = [
        'auth' => [
            'before' => [
                'dashboard',
                'dashboard/*',
                'profile',
                'profile/*',
                'change_password',
                'change_password/*',
                'employee_profile',
                'employee_profile/*',
                'student_profile',
                'student_profile/*',
                'student_presence',
                'student_presence/*',
                'scholarships',
                'scholarships/*',
                'achievements',
                'achievements/*',
                'posts',
                'posts/*',
                'blog/*',
                'academic/*',
                'admission/*',
                'appearance/*',
                'employees/*',
                'maintenance/*',
                'media/*',
                'plugins/*',
                'reference/*',
                'settings/*',
                'teacher/*',
                'users/*',
            ],
        ],
        'privilege' => [
            'before' => [
                'dashboard',
                'dashboard/*',
                'profile',
                'profile/*',
                'change_password',
                'change_password/*',
                'employee_profile',
                'employee_profile/*',
                'student_profile',
                'student_profile/*',
                'student_presence',
                'student_presence/*',
                'scholarships',
                'scholarships/*',
                'achievements',
                'achievements/*',
                'posts',
                'posts/*',
                'blog/*',
                'academic/*',
                'admission/*',
                'appearance/*',
                'employees/*',
                'maintenance/*',
                'media/*',
                'plugins/*',
                'reference/*',
                'settings/*',
                'teacher/*',
                'users/*',
            ],
        ],
    ];
}
