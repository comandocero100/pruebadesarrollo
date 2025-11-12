<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeController extends Controller
{
    public function myCourses(Request $request)
    {
        $user = $request->user();
        $user->load('courses');
        return response()->json($user->courses);
    }
}

