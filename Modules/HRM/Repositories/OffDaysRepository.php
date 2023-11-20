<?php

// namespace App\Repositories;

namespace Modules\HRM\Repositories;

use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Entities\Holiday;
use Modules\HRM\Interface\OffDaysRepositoryInterface;

class OffDaysRepository implements OffDaysRepositoryInterface
{
    public function getMonthlyOffDays(string $month, int $year): iterable
    {
        $date = new \DateTime("01 $month $year");
        $from = $date->format('Y-m-01');
        $to = $date->format('Y-m-t');

        $collection = Holiday::query()
            ->where('is_buyer_mode', config('app.is_buyer_mode'))
            ->where('from', '>=', $from)
            ->where('to', '<=', $to)
            ->get();
        $holidays = $collection->reduce(function ($carry, $holiday) {
            $dateArr = DateTimeUtils::dateRange($holiday->from, $holiday->to);
            $types = array_fill(0, count($dateArr), $holiday->holiday_name);
            $typeWiseArr = array_combine($dateArr, $types);

            return array_merge($carry, $typeWiseArr);
        }, []);
        ksort($holidays);

        return [
            'total' => count($holidays),
            'type_wise' => $holidays,
            'dates_array' => array_keys($holidays),
        ];
    }

    public function getYearlyOffDays(int $year): iterable
    {
        $date = new \DateTime("01 December $year");
        $from = $date->format('Y-01-01');
        $to = $date->format('Y-12-t');

        $collection = Holiday::query()
            ->where('is_buyer_mode', config('app.is_buyer_mode'))
            ->where('from', '>=', $from)
            ->where('to', '<=', $to)
            ->get();

        $holidays = $collection->reduce(function ($carry, $holiday) {
            $dateArr = DateTimeUtils::dateRange($holiday->from, $holiday->to);
            $types = array_fill(0, count($dateArr), $holiday->holiday_name);
            $typeWiseArr = array_combine($dateArr, $types);

            return array_merge($carry, $typeWiseArr);
        }, []);
        ksort($holidays);

        return [
            'total' => count($holidays),
            'type_wise' => $holidays,
            'dates_array' => array_keys($holidays),
        ];
    }
}
