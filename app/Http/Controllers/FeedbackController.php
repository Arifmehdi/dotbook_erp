<?php

namespace App\Http\Controllers;

use App\Models\Feedback\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('feedback.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        $feedback = new Feedback();
        $feedback->name = $request->name;
        $feedback->email = $request->email;
        $feedback->message = $request->message;
        $feedback->rating = $request->rating;
        $feedback->save();

        return response()->json('Feedback created successfully');
    }

    public function star_add(Request $request)
    {
        // code...
    }
}
