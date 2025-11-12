<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('courses')->where('role', 'student');

        if ($name = $request->query('name')) {
            $query->where('name', 'like', "%{$name}%");
        }
        if ($email = $request->query('email')) {
            $query->where('email', 'like', "%{$email}%");
        }
        if ($courseId = $request->query('course_id')) {
            $query->whereHas('courses', fn ($q) => $q->where('courses.id', $courseId));
        }
        if ($courseName = $request->query('course_name')) {
            $query->whereHas('courses', fn ($q) => $q->where('courses.name', 'like', "%{$courseName}%"));
        }

        $students = $query->orderBy('name')->paginate(20);

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6',
        ]);

        $student = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => 'student',
        ]);

        return response()->json($student, 201);
    }

    public function show(int $id)
    {
        $student = User::with('courses')->where('role', 'student')->findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, int $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $student->id,
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);

        $student->fill(collect($data)->except('password')->all());
        if (!empty($data['password'])) {
            $student->password = $data['password'];
        }
        $student->save();

        return response()->json($student);
    }

    public function destroy(int $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

