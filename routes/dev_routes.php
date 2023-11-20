<?php

use App\Models\Sale;
use Illuminate\Support\Facades\Route;

Route::get('apex-chart', function () {
    return view('dev_routes.apex-chart', []);
});

Route::get('el', function () {
    $fromDate = Carbon::parse('2022-11-17');
    $toDate = Carbon::parse('2023-02-05');

    $employees = Employee::whereBetween('joining_date', [$fromDate, $toDate])
        ->select('id', 'name', 'joining_date')
        ->get();
    $groupedEmployees = $employees->groupBy(function ($employee) {
        $joinDate = Carbon::parse($employee->joining_date);

        return $joinDate->format('Y');
    })->map(function ($yearGroup) {
        return $yearGroup->groupBy(function ($employee) {
            $joinDate = Carbon::parse($employee->joining_date);

            return $joinDate->format('M');
        })
            ->map(function ($monthGroup) {
                return [
                    'count' => $monthGroup->count(),
                    'employees' => $monthGroup,
                ];
            });
        // ->map(function ($monthGroup) use ($employees) {
        //     return $monthGroup->count();
        // });
    });
});
