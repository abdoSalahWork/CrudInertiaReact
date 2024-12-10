<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class StudentController extends Controller
{

    public function index(Student $model)
    {
        return Inertia::render('StudentsDashboard', [
            'studentsData' => $model->all(),
            'count' => $model->count(),
        ]);
    }
    public function store(Request $request, Student $model)
    {
        $model->create($request->validate([
            'first_name' => 'required|max:255|min:2',
            'last_name' => 'required|max:255|min:2',
            'department' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:students,email',
        ]));

        return back()->with('message', 'Student added successfully');
    }

    public function update(Request $request, Student $model, $student_id)
    {
        Log::info('Update request received for student ID: ' . $student_id);
        Log::info('Update request data: ', $request->all());


        $validatedData = $request->validate( // Validate the incoming request data
            [
                'first_name' => 'required|max:255|min:2',
                'last_name' => 'required|max:255|min:2',
                'department' => 'required|max:255|min:2',
                'email' => 'required|email|max:255',
            ],
            [
                'email.unique' => 'The email has already been taken.', // Custom error message for unique rule
            ]
        );

        $student = $model->findOrFail($student_id);

        $student->update($validatedData);

        return back()->with('message', 'Student updated successfully');
    }

    public function destroy(Student $model, $student_id)
    {
        $student = $model->findOrFail($student_id);

        $student->delete();

        return back()->with('message', 'Student deleted successfully');
    }
}