<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\JobCardServiceInterface;

class JobCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function __construct(private JobCardServiceInterface $jobCardService)
    {
    }

    public function jobCardPrint(Request $request)
    {
        $data = $this->jobCardService->jobCardPrint($request);

        return $data;

        return view('hrm::index');
    }

    public function jobCardSummaryPrint(Request $request)
    {
        $jobCardSummary = $this->jobCardSummaryService->jobSummaryPrint($request);

        return $jobCardSummary;
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
