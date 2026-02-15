<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function create()
    {
        $nextNumber = Student::generateNextNumber();
        return view('students.create', compact('nextNumber'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_number' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/', 'unique:students,student_number'],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
        ], [
            'student_number.size' => 'Student number must be exactly 6 digits.',
            'student_number.regex' => 'Student number must be 6 digits only (e.g., 000001).',
        ]);

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function index(Request $request)
    {
        // only admin can view full students list
        if (! auth()->check() || ! auth()->user()->is_admin) {
            abort(403);
        }

        $query = Student::query();

        if ($search = trim($request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('student_number')->get();

        return view('students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $student->load('courses');

        // Admins can view any student; students can view only their own record
        if (auth()->check() && auth()->user()->is_admin) {
            $allCourses = Course::all();
            return view('students.show', compact('student', 'allCourses'));
        }

        // if not admin, ensure the authenticated user's email matches the student email
        if (! auth()->check() || auth()->user()->email !== $student->email) {
            abort(403);
        }

        $allCourses = Course::all();
        return view('students.show', compact('student', 'allCourses'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'student_number' => 'required|string|size:6|regex:/^\d{6}$/|unique:students,student_number,'.$student->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,'.$student->id,
        ]);

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->courses()->detach();
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function enroll(Request $request, Student $student)
    {
        if (auth()->user()->is_admin && $student->email === auth()->user()->email) {
            return back()->with('error', 'Admins cannot enroll themselves in courses.');
        }

        $data = $request->validate([
            'course_ids' => 'required|array',
            'course_ids.*' => 'integer|exists:courses,id',
        ]);

        $enrolled = 0;
        $errors = [];
        foreach ($data['course_ids'] as $courseId) {
            $course = Course::findOrFail($courseId);
            if ($student->courses()->where('course_id', $course->id)->exists()) {
                continue;
            }
            $enrolledCount = $course->students()->count();
            if ($course->capacity && $enrolledCount >= $course->capacity) {
                $errors[] = "{$course->course_code} has reached capacity.";
                continue;
            }
            $student->courses()->attach($course->id);
            $enrolled++;
        }

        if (! empty($errors)) {
            return back()->with('error', implode(' ', $errors));
        }
        if ($enrolled === 0) {
            return back()->with('error', 'No courses enrolled. Student may already be enrolled in the selected courses.');
        }

        return back()->with('success', $enrolled === 1 ? 'Enrollment successful.' : "Successfully enrolled student in {$enrolled} courses.");
    }
}
