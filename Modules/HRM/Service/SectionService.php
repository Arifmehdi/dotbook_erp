<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\HrmDepartment;
use Modules\HRM\Entities\Section;
use Modules\HRM\Interface\SectionServiceInterface;

class SectionService implements SectionServiceInterface
{
    public function all()
    {
        abort_if(!auth()->user()->can('hrm_sections_index'), 403, 'Access Forbidden');
        $section = Section::with('hrmDepartment')->orderBy('id', 'desc')->get();

        return $section;
    }

    public function store(array $attributes)
    {
        abort_if(!auth()->user()->can('hrm_sections_create'), 403, 'Access Forbidden');
        $section = Section::create($attributes);

        return $section;
    }

    public function find(int $id)
    {
        abort_if(!auth()->user()->can('hrm_sections_view'), 403, 'Access Forbidden');
        $section = Section::findOrFail($id);

        return $section;
    }

    public function getSectionByHrmDepartment(int $id)
    {
        abort_if(!auth()->user()->can('hrm_sections_view'), 403, 'Access Forbidden');
        $section = Section::where('hrm_department_id', $id)->get();

        return $section;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(!auth()->user()->can('hrm_sections_update'), 403, 'Access Forbidden');
        $section = Section::find($id);
        $updatedSection = $section->update($attributes);

        return $updatedSection;
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        abort_if(!auth()->user()->can('hrm_sections_index'), 403, 'Access Forbidden');
        $item = Section::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(!auth()->user()->can('hrm_sections_delete'), 403, 'Access Forbidden');
        $item = Section::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_sections_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Section::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(!auth()->user()->can('hrm_sections_delete'), 403, 'Access Forbidden');
        $item = Section::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_sections_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Section::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(!auth()->user()->can('hrm_sections_delete'), 403, 'Access Forbidden');
        $item = Section::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(!auth()->user()->can('hrm_sections_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Section::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(!auth()->user()->can('hrm_sections_index'), 403, 'Access Forbidden');
        $count = Section::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(!auth()->user()->can('hrm_sections_index'), 403, 'Access Forbidden');
        $count = Section::onlyTrashed()->count();

        return $count;
    }

    public function sectionWithHrmDepartmentAndSelection()
    {
        abort_if(!auth()->user()->can('hrm_sections_index'), 403, 'Access Forbidden');
        $sections = HrmDepartment::with('sections')->get();

        return $sections;
    }

    public function sectionSelectedAndSortListWithId()
    {
        abort_if(!auth()->user()->can('hrm_sections_index'), 403, 'Access Forbidden');
        $section = Section::orderBy('name', 'asc')->where('deleted_at', null)->get(['id', 'name', 'hrm_department_id']);

        return $section;
    }
}
