<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\SalaryAdjustmentServiceInterface;
use Modules\HRM\Transformers\SalaryAdjustmentResource;

class SalaryAdjustmentReportController extends Controller
{
    private $salaryAdjustmentService;

    public function __construct(SalaryAdjustmentServiceInterface $salaryAdjustmentService)
    {
        $this->salaryAdjustmentService = $salaryAdjustmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function filter(Request $request)
    {
        $salaryAdjustmentReportFilter = SalaryAdjustmentResource::collection($this->salaryAdjustmentService->employeeFilter($request));

        return $salaryAdjustmentReportFilter;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('hrm::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('hrm::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
