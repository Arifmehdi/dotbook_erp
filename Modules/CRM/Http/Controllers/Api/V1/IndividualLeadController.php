<?php

namespace Modules\CRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\CRM\Http\Requests\IndividualLeads\IndividualLeadStoreRequest;
use Modules\CRM\Http\Requests\IndividualLeads\IndividualLeadUpdateRequest;
use Modules\CRM\Interfaces\IndividualLeadServiceInterface;
use Modules\CRM\Transformers\IndividualLeadResource;

class IndividualLeadController extends Controller
{
    private $individualLeadService;

    public function __construct(IndividualLeadServiceInterface $individualLeadService)
    {
        $this->individualLeadService = $individualLeadService;
    }

    public function index()
    {
        $individualLead = IndividualLeadResource::collection($this->individualLeadService->all());

        return $individualLead;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('crm::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(IndividualLeadStoreRequest $request)
    {
        $data = $this->individualLeadService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Individual leads created successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        $individual_leads = IndividualLeadResource::make($this->individualLeadService->find($id));

        return $individual_leads;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('crm::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(IndividualLeadUpdateRequest $request, $id)
    {
        $data = $this->individualLeadService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Individual leads updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $data = $this->individualLeadService->destroy($id);

        return response()->json(['data' => $data, 'message' => 'Individual leads deleted successfully!']);
    }
}
