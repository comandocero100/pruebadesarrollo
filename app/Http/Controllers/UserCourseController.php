<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class UserCourseController extends Controller
{
    // Listar cursos asignados a un usuario
    public function index(int $id)
    {
        $user = User::with('courses')->findOrFail($id);
        return response()->json($user->courses);
    }
    // Asignar curso a usuario
    public function attach(Request $request, int $id)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);
        $user = User::findOrFail($id);
        $user->courses()->syncWithoutDetaching([$data['course_id']]);
        return response()->json(['message' => 'Assigned']);
    }
    // Eliminar curso asignado a usuario
    public function detach(int $id, int $courseId)
    {
        $user = User::findOrFail($id);
        $course = Course::findOrFail($courseId);
        $user->courses()->detach($course->id);
        return response()->json(['message' => 'Unassigned']);
    }
}

