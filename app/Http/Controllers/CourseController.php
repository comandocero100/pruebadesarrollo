<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();
        if ($name = $request->query('name')) {
            $query->where('name', 'like', "%{$name}%");
        }
        $courses = $query->orderBy('name')->paginate(20);
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:courses,name',
            'intensity' => 'required|integer|min:0',
        ]);
        $course = Course::create($data);
        return response()->json($course, 201);
    }

    public function show(int $id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
    }

    public function update(Request $request, int $id)
    {
        $course = Course::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:courses,name,' . $course->id,
            'intensity' => 'sometimes|required|integer|min:0',
        ]);
        $course->update($data);
        return response()->json($course);
    }

    public function destroy(int $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

