<?php

namespace Modules\HRM\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Interface\LeaveApplicationRepositoryInterface;

class LeaveApplicationRepository implements LeaveApplicationRepositoryInterface
{
    public function getMonthLeaves(string $month, int $year): iterable
    {
        // toDO:: year month may be or date range;
        // $year = $request->year;
        // $month = $request->month;
        $dateRange = DateTimeUtils::getMonthDatesAsArray($year, $month);
        $leaves = DB::connection('hrm')->table('leave_applications')
            ->whereIn('from_date', $dateRange)
            ->select('employee_id', 'leave_type_id', 'from_date', 'to_date')
            ->get();

        $newLeaves = $leaves->map(function ($leave) {
            $arr = $leave->date_array = DateTimeUtils::dateRange($leave->from_date, $leave->to_date);
            $leave->to_date = count($arr);

            return $leave;
        });

        return $newLeaves;
    }

    public function getEmployeeLeaves(string $user_id, string $month, int $year): iterable
    {
        // $year = $request->year;
        // $month = $request->month;
        // $user_id = $request->employee_id;
        // toDO:: year month may be or date range;
        $dateRange = DateTimeUtils::getMonthDatesAsArray($year, $month);
        $leaves = DB::connection('hrm')->table('leave_applications')
            ->leftJoin('leave_types', 'leave_applications.leave_type_id', 'leave_types.id')
            ->where('leave_applications.employee_id', $user_id)
            ->whereIn('leave_applications.from_date', $dateRange)
            ->select('leave_types.name', 'leave_applications.from_date', 'leave_applications.to_date')
            ->get();

        $data = $leaves->reduce(function ($carry, $leave) {
            $dates = DateTimeUtils::dateRange($leave->from_date, $leave->to_date);
            $dates = array_map(fn ($date) => ["$date" => $leave->name], $dates);

            return array_merge($carry, $dates);
        }, []);

        $typeWiseLeaveDates = array_merge(...\array_values($data));
        $datesArray = array_keys($typeWiseLeaveDates);
        $datesArrayUnique = array_unique($datesArray);

        return [
            'employee_id' => $user_id,
            'year' => $year,
            'month' => $month,
            'total_leave' => count($datesArrayUnique),
            'type_wise' => $typeWiseLeaveDates,
            'dates_array' => $datesArrayUnique,
        ];
    }

    public function getEmployeesLeaves(array $user_ids, string $month, int $year): iterable
    {
        // toDO:: year month may be or date range;
        // $year = $request->year;
        // $month = $request->month;
        $dateRange = DateTimeUtils::getMonthDatesAsArray($year, $month);
        $leaves = DB::connection('hrm')->table('leave_applications')
            ->leftJoin('leave_types', 'leave_applications.leave_type_id', 'leave_types.id')
            ->whereIn('employee_id', $user_ids)
            ->whereIn('from_date', $dateRange)
            // ->where('year', $year)
            // ->where('month', $month)
            ->select('leave_applications.employee_id', 'leave_applications.leave_type_id', 'leave_applications.from_date', 'leave_applications.to_date', 'leave_types.name')
            ->get();

        $newLeaves = $leaves->map(function ($leave) {
            $arr = $leave->date_array = DateTimeUtils::dateRange($leave->from_date, $leave->to_date);
            $leave->type_wise = array_reduce($arr, fn ($carry, $item) => array_merge($carry, ["$item" => $leave->name]), []);
            $leave->total_leave = count($arr);

            return $leave;
        });

        return $newLeaves;
    }

    // ============================ Back soon  ============================

    public function getUniqueLeaves(string $userId, iterable $leaves): array
    {
        $datesCollection = $leaves->where('employee_id', $userId)->pluck('date_array')->toArray();
        $datesCombined = array_merge(...array_values($datesCollection));
        $datesArray = array_unique($datesCombined);
        sort($datesArray);

        $typeWiseCollection = $leaves->where('employee_id', $userId)->pluck('type_wise')->toArray();
        $typeWise = array_merge(...array_values($typeWiseCollection));
        ksort($typeWise);

        $total = count($datesArray);

        return [
            'employee_id' => $userId,
            'total' => $total,
            'dates_array' => $datesArray,
            'type_wise' => $typeWise,
        ];
    }
}
