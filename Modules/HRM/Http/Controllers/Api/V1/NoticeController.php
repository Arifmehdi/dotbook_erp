<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Notice;
use Modules\HRM\Http\Requests\Notice\CreateNoticeRequest;
use Modules\HRM\Http\Requests\Notice\UpdateNoticeRequest;
use Modules\HRM\Interface\NoticeServiceInterface;
use Modules\HRM\Transformers\NoticeResource;

class NoticeController extends Controller
{
    protected $noticeService;

    public function __construct(NoticeServiceInterface $noticeService)
    {
        $this->noticeService = $noticeService;
    }

    public function index(Request $request)
    {
        $notice = NoticeResource::collection($this->noticeService->all());

        return $notice;
    }

    public function store(CreateNoticeRequest $request)
    {
        $notice = $this->noticeService->store($request->validated());
        if ($notice) {
            return response()->json('Notice created successfully!');
        }
    }

    public function show($id)
    {
        $notice = NoticeResource::make($this->noticeService->find($id));

        return $notice;
    }

    // public function edit($id)
    // {
    //     $notice = Notice::find($id);
    //     return $notice;
    // }

    public function update(UpdateNoticeRequest $request, $id)
    {
        $notice = $this->noticeService->update($request->validated(), $id);
        if ($notice) {
            return response()->json('Notice updated successfully!');
        }
    }

    public function destroy($id)
    {
        $notice = $this->noticeService->trash($id);

        return response()->json('Notice deleted successfully!');
    }

    /**
     * Permanent Delete the shift Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $notice = NoticeResource::collection($this->noticeService->getTrashedItem());

        return $notice;
    }

    /**
     * Permanent Delete the shift Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $notice = $this->noticeService->permanentDelete($id);

        return response()->json(['message' => 'Notice is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $notice = $this->noticeService->restore($id);

        return response()->json(['message' => 'Notice restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->notice_id)) {
            if ($request->action_type == 'move_to_trash') {
                $shift = $this->noticeService->bulkTrash($request->notice_id);

                return response()->json(['message' => 'Notice are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $shift = $this->noticeService->bulkRestore($request->notice_id);

                return response()->json(['message' => 'Notice are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $shift = $this->noticeService->bulkPermanentDelete($request->notice_id);

                return response()->json(['message' => 'Notice are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
