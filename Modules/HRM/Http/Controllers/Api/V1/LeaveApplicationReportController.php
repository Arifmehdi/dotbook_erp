<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\LeaveApplicationReportServiceInterface;
use Modules\HRM\Transformers\LeaveApplicationReportFilterResource;

class LeaveApplicationReportController extends Controller
{
    private $LeaveApplicationReportService;

    public function __construct(LeaveApplicationReportServiceInterface $LeaveApplicationReportService)
    {
        $this->LeaveApplicationReportService = $LeaveApplicationReportService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function filter(Request $request)
    {
        $leaveApplicationEmployeeFilter = LeaveApplicationReportFilterResource::collection($this->LeaveApplicationReportService->leaveApplicationFilter($request));

        return $leaveApplicationEmployeeFilter;
    }

    public function index()
    {
        return view('hrm::index');
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
