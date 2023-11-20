<?php

namespace App\Http\Controllers;

use App\Models\ShortcutBookmark;
use Illuminate\Http\Request;

class ShortcutBookmarkController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shortcut_name' => 'required|string|unique:shortcut_bookmarks,name',
            'shortcut_url' => 'required|string',
        ]);
        $result = ShortcutBookmark::create([
            'name' => $request->shortcut_name,
            'url' => $request->shortcut_url,
        ]);
        if ($result) {
            return \response()->json([
                'message' => 'Shortcut created!',
                'data' => $result,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShortcutBookmark $shortcutBookmark)
    {
        return view('dashboard.ajax_view.shortcut-edit', \compact('shortcutBookmark'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShortcutBookmark $shortcutBookmark)
    {
        $request->validate([
            'shortcut_name' => 'required|string|unique:shortcut_bookmarks,name,' . $request->id,
            'shortcut_url' => 'required|string',
        ]);
        $result = $shortcutBookmark->update([
            'name' => $request->shortcut_name,
            'url' => $request->shortcut_url,
        ]);

        return \response()->json([
            'message' => 'Shortcut created!',
            'data' => $shortcutBookmark,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortcutBookmark $shortcutBookmark)
    {
        $result = $shortcutBookmark->delete();
        if ($result) {
            return \response()->json('Shortcut removed!');
        }
    }
}
