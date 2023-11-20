<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\Award\CreateAwardRequest;
use Modules\HRM\Http\Requests\Award\UpdateAwardRequest;
use Modules\HRM\Interface\AwardServiceInterface;
use Modules\HRM\Transformers\AwardResource;

class AwardController extends Controller
{
    private $awardService;

    public function __construct(AwardServiceInterface $awardService)
    {
        $this->awardService = $awardService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $awards = AwardResource::collection($this->awardService->all());

        return $awards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateAwardRequest $request)
    {
        $data = $this->awardService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Award Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $award = AwardResource::make($this->awardService->find($id));

        return $award;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateAwardRequest $request, $id)
    {
        $data = $this->awardService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Award Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $department = $this->awardService->trash($id);

        return response()->json(['message' => 'Award Deleted successfully']);
    }

    /**
     * Permanent Delete the holiday Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $award = AwardResource::collection($this->awardService->getTrashedItem());

        return $award;
    }

    /**
     * Permanent Delete the holiday Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $award = $this->awardService->permanentDelete($id);

        return response()->json(['message' => 'Award is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $holiday = $this->awardService->restore($id);

        return response()->json(['message' => 'Award restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->award_id)) {
            if ($request->action_type == 'move_to_trash') {
                $holiday = $this->awardService->bulkTrash($request->award_id);

                return response()->json(['message' => 'Awards are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $holiday = $this->awardService->bulkRestore($request->award_id);

                return response()->json(['message' => 'Awards are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $holiday = $this->awardService->bulkPermanentDelete($request->award_id);

                return response()->json(['message' => 'Awards are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
