<?php

namespace App\Services;

class DayStatsService
{
    public function getKcalRangeLabel($kcal)
    {
        $ranges = [
            'success' => [0, 1300],
            'info' => [1301, 1500],
            'warning' => [1501, 2000],
            'danger' => [2001, null],
        ];

        foreach ($ranges as $label => $range) {
            if (($range[1] === null || $kcal <= $range[1]) && $kcal >= $range[0]) {
                return $label;
            }
        }

        return 'secondary';
    }

    public function getProteinRangeLabel($protein)
    {
        $ranges = [
            'danger' => [0, 60],
            'warning' => [61, 89],
            'success' => [90, null],
        ];

        foreach ($ranges as $label => $range) {
            if (($range[1] === null || $protein <= $range[1]) && $protein >= $range[0]) {
                return $label;
            }
        }

        return 'secondary';
    }
}
