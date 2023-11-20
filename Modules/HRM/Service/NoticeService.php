<?php

namespace Modules\HRM\Service;

use App\Interface\FileUploaderServiceInterface;
use Modules\HRM\Entities\Notice;
use Modules\HRM\Interface\NoticeServiceInterface;

class NoticeService implements NoticeServiceInterface
{
    public function __construct(
        private FileUploaderServiceInterface $uploader,
    ) {
    }

    public function all()
    {
        abort_if(! auth()->user()->can('hrm_notice_index'), 403, 'Access Forbidden');
        $items = Notice::orderBy('id', 'desc')->get();

        return $items;
    }

    public function allNotice($request)
    {
        abort_if(! auth()->user()->can('hrm_notice_index'), 403, 'Access Forbidden');
        $query = Notice::orderBy('id', 'desc');
        if ($request->status_id) {
            $query->where('is_active', $request->status_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('created_at', [$form_date, $to_date]); // Final
        }

        return $query->get();
    }

    public function store(array $notice)
    {

        abort_if(! auth()->user()->can('hrm_notice_create'), 403, 'Access Forbidden');
        if (isset($notice['attachment'])) {
            $notice['attachment'] = $this->uploader->upload($notice['attachment'], 'uploads/notice/');
        }
        $item = Notice::create($notice);

        return $item;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_types_view'), 403, 'Access Forbidden');
        $leaveType = Notice::find($id);

        return $leaveType;
    }

    public function update(array $notice, int $id)
    {
        abort_if(! auth()->user()->can('hrm_notice_update'), 403, 'Access Forbidden');
        $item = Notice::find($id);
        // show image & delete exist photo
        if (isset($notice['attachment'])) {

            if (isset($item['attachment']) && ! empty($item['attachment']) && file_exists('uploads/notice/'.$notice['old_photo'])) {
                unlink(public_path('uploads/notice/'.$notice['old_photo']));
            }
            $notice['attachment'] = $this->uploader->upload($notice['attachment'], 'uploads/notice/');

            $updatedItem = $item->update($notice);

            return $updatedItem;
        }
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_notice_delete'), 403, 'Access Forbidden');
        $item = Notice::find($id);
        $item->delete($item);

        return $item;
    }

    //Get Trashed Itemlist
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_notice_index'), 403, 'Access Forbidden');
        $item = Notice::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_notice_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Notice::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_notice_delete'), 403, 'Access Forbidden');
        $item = Notice::onlyTrashed()->find($id);

        if (isset($item['attachment']) && ! empty($item['attachment']) && file_exists('uploads/notice/'.$item['attachment'])) {

            unlink(public_path('uploads/notice/'.$item['attachment']));
        }

        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_notice_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Notice::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_notice_delete'), 403, 'Access Forbidden');
        $item = Notice::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_notice_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Notice::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_notice_index'), 403, 'Access Forbidden');
        $count = Notice::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_notice_index'), 403, 'Access Forbidden');
        $count = Notice::onlyTrashed()->count();

        return $count;
    }
}
