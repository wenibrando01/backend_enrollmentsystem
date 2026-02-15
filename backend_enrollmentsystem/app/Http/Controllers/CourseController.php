<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_code' => 'required|string|unique:courses,course_code',
            'course_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
        ]);

        Course::create($data);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    public function index()
    {
        $courses = Course::all();
        $enrolledCourseIds = [];
        if (Auth::check() && ! (Auth::user()->is_admin ?? false)) {
            $student = Student::where('email', Auth::user()->email)->first();
            if ($student) {
                $enrolledCourseIds = $student->courses->pluck('id')->toArray();
            }
        }
        return view('courses.index', compact('courses', 'enrolledCourseIds'));
    }

    public function show(Course $course)
    {
        $course->load('students');
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'course_code' => 'required|string|unique:courses,course_code,'.$course->id,
            'course_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
        ]);

        $course->update($data);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->students()->detach();
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
