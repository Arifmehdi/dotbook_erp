<?php

namespace Modules\CRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Http\Response\ApiResponse;
use Modules\CRM\Http\Requests\BusinessLeads\BusinessLeadStoreRequest;
use Modules\CRM\Http\Requests\BusinessLeads\BusinessLeadUpdateRequest;
use Modules\CRM\Interfaces\BusinessLeadServiceInterface;
use Modules\CRM\Transformers\BusinessLeadResource;

class BusinessLeadController extends Controller
{
    private $businessLeadService;

    public function __construct(BusinessLeadServiceInterface $businessLeadService)
    {
        $this->businessLeadService = $businessLeadService;
    }

    public function index()
    {
        $businessLeads = BusinessLeadResource::collection($this->businessLeadService->all());

        return $businessLeads;
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
    public function store(BusinessLeadStoreRequest $request)
    {
        $businessLeadToInsert = $this->businessLeadService->store($request->validated());
        $createdBusinessLead = BusinessLeadResource::make($businessLeadToInsert);

        return ApiResponse::created($createdBusinessLead, 'Business leads created successfully!');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = BusinessLeadResource::make($this->businessLeadService->find($id));

        return $data;
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
    public function update(BusinessLeadUpdateRequest $request, $id)
    {
        $data = $this->businessLeadService->update($request->validated(), $id);
        $data = BusinessLeadResource::make($data);

        return ApiResponse::updated($data, 'Business leads updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $data = $this->businessLeadService->destroy($id);
        $data = BusinessLeadResource::make($data);

        return ApiResponse::deleted($data, 'Business leads deleted successfully!');
    }
}
