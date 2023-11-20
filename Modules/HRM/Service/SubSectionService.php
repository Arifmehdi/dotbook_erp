<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\SubSection;
use Modules\HRM\Interface\SubSectionServiceInterface;

class SubSectionService implements SubSectionServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_index'), 403, 'Access Forbidden');
        $subSection = SubSection::with('section')->orderBy('id', 'desc')->select('id', 'name', 'section_id')->get();

        return $subSection;
    }

    public function store(array $attributes)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_create'), 403, 'Access Forbidden');
        $subSection = SubSection::create($attributes);

        return $subSection;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_view'), 403, 'Access Forbidden');
        $subSection = SubSection::findOrFail($id);

        return $subSection;
    }

    public function getSubSectionBySection(int $id)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_view'), 403, 'Access Forbidden');
        $item = SubSection::where('section_id', $id)->get();

        return $item;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_update'), 403, 'Access Forbidden');
        $subSection = SubSection::find($id);
        $updatedSubSection = $subSection->update($attributes);

        return $updatedSubSection;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_index'), 403, 'Access Forbidden');
        $item = SubSection::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_delete'), 403, 'Access Forbidden');
        $item = SubSection::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = SubSection::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_delete'), 403, 'Access Forbidden');
        $item = SubSection::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = SubSection::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_delete'), 403, 'Access Forbidden');
        $item = SubSection::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = SubSection::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_index'), 403, 'Access Forbidden');
        $count = SubSection::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_index'), 403, 'Access Forbidden');
        $count = SubSection::onlyTrashed()->count();

        return $count;
    }

    public function getSubSectionDoPluck($request)
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_view'), 403, 'Access Forbidden');
        $id = $request->sectionId;
        $item = SubSection::where('section_id', $id)->select('id', 'name', 'section_id')->get();

        return $item;
    }

    public function subsectionSelectedAndSortListWithId()
    {
        abort_if(! auth()->user()->can('hrm_sub_sections_view'), 403, 'Access Forbidden');
        $subsection = SubSection::orderBy('name', 'asc')->where('deleted_at', null)->get(['id', 'name', 'section_id']);

        return $subsection;
    }
}
