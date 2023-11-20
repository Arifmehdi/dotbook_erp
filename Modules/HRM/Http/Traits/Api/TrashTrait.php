<?php

namespace Modules\HRM\Http\Traits\Api;

use Illuminate\Support\Str;

trait TrashTrait
{
    protected $serviceName;

    protected $modelName;

    protected $resource;

    /**
     * Permanent Delete the designation Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        //$items = $this->resource->collection($this->$serviceName->getTrashedItem());
        $items = $this->$serviceName->getTrashedItem();

        return $items;
    }

    /**
     * Permanent Delete the designation Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $item = $this->$serviceName->permanentDelete($id);

        return response()->json(['message' => $modelName.' is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $item = $this->$serviceName->restore($id);

        return response()->json(['message' => $modelName.' restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->designation_id)) {
            if ($request->action_type == 'move_to_trash') {
                $item = $this->$serviceName->bulkTrash($request->designation_id);

                return response()->json(['message' => Str::plural($modelName).' are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $item = $this->$serviceName->bulkRestore($request->designation_id);

                return response()->json(['message' => Str::plural($modelName).' are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $item = $this->$serviceName->bulkPermanentDelete($request->designation_id);

                return response()->json(['message' => Str::plural($modelName).' are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
