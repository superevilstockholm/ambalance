<?php

if (!function_exists('studentSidebarItems')) {
    function studentSidebarItems(): array
    {
        return [
            'main' => [
                ['icon' => 'ti ti-dashboard', 'label' => 'Dashboard', 'route' => 'student.dashboard'],
            ],
            'tabungan' => [
                ['icon' => 'ti ti-chart-bar', 'label' => 'Statistik', 'route' => 'student.statistics'],
                ['icon' => 'ti ti-history', 'label' => 'Histori', 'route' => 'student.savings-history'],
            ]
        ];
    }
}
