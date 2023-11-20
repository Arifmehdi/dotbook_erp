<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Promotion\CreatePromotionRequest;
use Modules\HRM\Http\Requests\Promotion\UpdatePromotionRequest;
use Modules\HRM\Interface\PromotionServiceInterface;
use Modules\HRM\Transformers\PromotionResource;

class PromotionController extends Controller
{
    private $promoteService;

    public function __construct(PromotionServiceInterface $promoteService)
    {
        $this->promoteService = $promoteService;
    }

    public function index(Request $request)
    {
        $employees = $this->promoteService->all();
        $trashedCount = $this->promoteService->getRowCount();
        $employee = PromotionResource::collection($employees);

        return response()->json([
            'employee_type' => 'promotion',
            'total_promotion' => $trashedCount,
            'employees' => $employee,
        ]);
    }

    public function trashIndex()
    {
        $employees = $this->promoteService->getTrashedItem();
        $trashedCount = $this->promoteService->getTrashedCount();
        $employee = PromotionResource::collection($employees);

        return response()->json([
            'employee_type' => 'promotion',
            'total_promotion' => $trashedCount,
            'employees' => $employee,
        ]);
    }

    public function store(CreatePromotionRequest $request)
    {
        $this->promoteService->store($request->validated());

        return response()->json('Promotion created successfully');
    }

    public function update(UpdatePromotionRequest $request, $id)
    {
        $this->promoteService->update($request->validated(), $id);

        return response()->json('Promotion updated successfully');
    }

    public function destroy($id)
    {
        $paymentType = $this->promoteService->trash($id);

        return response()->json('Promotion Type deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->promotion_id)) {
            if ($request->action_type == 'move_to_trash') {
                $promotion = $this->promoteService->bulkTrash($request->promotion_id);

                return response()->json('Promotion are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $promotion = $this->promoteService->bulkRestore($request->promotion_id);

                return response()->json('Promotion are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $promotion = $this->promoteService->bulkPermanentDelete($request->promotion_id);

                return response()->json('Promotion are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function permanentDelete($id)
    {
        $promotion = $this->promoteService->permanentDelete($id);

        return response()->json('promotion is permanently deleted successfully');
    }

    public function restore($id)
    {
        $promotion = $this->promoteService->restore($id);

        return response()->json('Promotion restored successfully');
    }
}
