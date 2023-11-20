<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\JobCardSummaryServiceInterface;

class JobCardSummaryController extends Controller
{
    public function __construct(private JobCardSummaryServiceInterface $jobCardSummaryService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function jobCardSummaryPrint(Request $request)
    {
        [
            'month' => $month,
            'year' => $year,
            'employees' => $employees,
            'section_name' => $section_name,
            'attendances_dates' => $attendances_dates,
            'overtime_sum' => $overtime_sum,
        ] = $this->jobCardSummaryService->jobSummaryPrint($request);

        return view('hrm::job_card.summery_jobcard_print', compact(
            'month',
            'year',
            'employees',
            'section_name',
            'attendances_dates',
            'overtime_sum',
        ));
        // return $jobCardSummary;
    }
}
