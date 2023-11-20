<?php

namespace Modules\Core\Utils;

use DateTime;
use Illuminate\Support\Carbon;

class DateTimeUtils
{
    public static function dateRangeBetween($date1, $date2): array
    {
        if (strtotime($date1) > strtotime($date2)) {
            $start = new \DateTime($date2);
            $end = new \DateTime($date1);
            $end = $end->modify('+1 day');
        } else {
            $start = new \DateTime($date1);
            $end = new \DateTime($date2);
            $end = $end->modify('+1 day');
        }

        $interval = new \DateInterval('P1D');

        $period = new \DatePeriod($start, $interval, $end);

        $arr = [];
        foreach ($period as $date) {
            array_push($arr, $date->format('Y-m-d'));
        }

        return $arr;
    }

    public static function getMonthDatesAsArray(int $year, int|string $month, ?string $format = 'Y-m-d'): array
    {
        $month = \is_numeric($month) ? $month : self::monthNumberFromName($month);
        $firstDate = Carbon::parse("$year-$month-01");
        $totalDate = $firstDate->format('t');
        $lastDate = Carbon::parse("$year-$month-$totalDate");
        $arr = self::dateRange($firstDate->format('Y-m-d'), $lastDate->format('Y-m-d'), $format);

        return $arr;
    }

    public static function monthNumberFromName(string $monthName): int
    {
        if (\is_numeric($monthName)) {
            return $monthName;
        }
        $monthName = trim($monthName);

        $monthNameVsMonthNumberArr = [
            'January' => '1',
            'February' => '2',
            'March' => '3',
            'April' => '4',
            'May' => '5',
            'June' => '6',
            'July' => '7',
            'August' => '8',
            'September' => '9',
            'October' => '10',
            'November' => '11',
            'December' => '12',
        ];
        if (isset($monthNameVsMonthNumberArr[$monthName])) {
            return $monthNameVsMonthNumberArr[$monthName];
        }
        throw Exception("Month number can't be calculated. Wrong month name provided.");
    }

    public static function dateRange($date1, $date2, $format = 'Y-m-d'): array
    {
        if (strtotime($date1) > strtotime($date2)) {
            $start = new \DateTime($date2);
            $end = new \DateTime($date1);
            $end = $end->modify('+1 day');
        } else {
            $start = new \DateTime($date1);
            $end = new \DateTime($date2);
            $end = $end->modify('+1 day');
        }

        $interval = new \DateInterval('P1D');

        $period = new \DatePeriod($start, $interval, $end);

        $arr = [];
        foreach ($period as $date) {
            array_push($arr, $date->format($format));
        }

        return $arr;
    }

    public static function diffInDays(DateTime|string $d1, DateTime|string $d2): int
    {
        $d1 = new DateTime($d1);
        $d2 = new DateTime($d2);

        return $d1->diff($d2)->format('%a');
    }

    public static function diffInDaysWithOrder(DateTime|string $d1, DateTime|string $d2): int
    {
        $d1 = new DateTime($d1);
        $d2 = new DateTime($d2);

        return $d1->diff($d2)->format('%r%a');
    }

    public static function diffInDaysInclusive(DateTime|string $d1, DateTime|string $d2): int
    {
        $d1 = new DateTime($d1);
        $d2 = new DateTime($d2);
        $d2 = $d2->modify('+1day');

        return $d1->diff($d2)->format('%a');
    }

    public static function diffInDaysWithOrderInclusive(DateTime|string $d1, DateTime|string $d2): int
    {
        $d1 = new DateTime($d1);
        $d2 = new DateTime($d2);

        return $d1->diff($d2)->format('%r%a');
    }

    public static function years_array(int $start = 2018): array
    {
        $start = config('core.company_start_year') ?? $start;
        $end = date('Y') + 3;
        $years = range($start, $end);

        return $years;
    }

    public static function months_array()
    {
        return [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
    }

    public static function minutesToHourMinutes($minutes, $format = '%02d:%02d')
    {
        if ($minutes < 1) {
            return;
        }
        $hours = floor($minutes / 60);
        $minutes = ($minutes % 60);

        return sprintf($format, $hours, $minutes);
    }
}
