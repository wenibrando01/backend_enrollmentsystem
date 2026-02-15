<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Course;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $courses = Course::orderBy('course_code')->get();
    $student = null;
    if (Auth::check() && ! (Auth::user()->is_admin ?? false)) {
        $user = Auth::user();
        $student = Student::firstOrCreate(
            ['email' => $user->email],
            [
                'student_number' => Student::generateNextNumber(),
                'first_name' => explode(' ', $user->name)[0] ?? $user->name,
                'last_name' => collect(explode(' ', $user->name))->slice(1)->join(' ') ?: '',
            ]
        );
    }
    return view('dashboard', compact('courses', 'student'));
})->middleware(['auth', 'verified', 'not.admin'])->name('dashboard');

Route::middleware(['auth', 'not.admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin auth routes (separate admin login/register)
Route::get('/admin/login', [AdminAuthenticatedSessionController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthenticatedSessionController::class, 'store'])->name('admin.login.store');
Route::post('/admin/logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
// Admin dashboard (admin-only)
Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin'])->name('admin.dashboard');

// admin registration removed

// chooser pages for combined links
Route::get('/choose-login', function (Request $request) {
    if ($request->has('intended')) {
        session(['url.intended' => $request->intended]);
    }
    return view('auth.choose-login');
})->name('choose.login');

Route::get('/choose-register', function () {
    return view('auth.choose-register');
})->name('choose.register');

// Academic portal routes - students can view own record, admin can manage all
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::patch('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::patch('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
});

Route::get('/students', [StudentController::class, 'index'])->middleware(['auth', 'admin'])->name('students.index');
Route::get('/students/{student}', [StudentController::class, 'show'])->middleware('auth')->name('students.show');
Route::post('/students/{student}/enroll', [StudentController::class, 'enroll'])->middleware(['auth', 'admin'])->name('students.enroll');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

Route::post('/dashboard/enroll', function (Request $request) {
    $data = $request->validate([
        'course_ids' => 'required|array',
        'course_ids.*' => 'integer|exists:courses,id',
    ]);

    $user = Auth::user();
    if (! $user) {
        return redirect()->route('login');
    }

    $student = Student::firstOrCreate(
        ['email' => $user->email],
        [
            'student_number' => Student::generateNextNumber(),
            'first_name' => explode(' ', $user->name)[0] ?? $user->name,
            'last_name' => collect(explode(' ', $user->name))->slice(1)->join(' ') ?: '',
        ]
    );

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
        return back()->with('error', 'No courses enrolled. You may already be enrolled in the selected courses.');
    }

    return redirect()->route('courses.index')->with('success', $enrolled === 1 ? 'Enrollment successful.' : "Successfully enrolled in {$enrolled} courses.");
})->middleware(['auth', 'not.admin'])->name('dashboard.enroll');
