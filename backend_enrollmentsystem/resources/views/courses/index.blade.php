@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div>
                <a href="javascript:history.back()" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">Courses</h1>
                <p class="mt-1 text-sm text-gray-500">Manage course offerings and enrollments.</p>
            </div>

            <div class="mt-4 sm:mt-0 sm:flex sm:items-center sm:space-x-3">
                @if(auth()->check() && auth()->user()->is_admin)
                    <a href="{{ route('courses.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700">Create Course</a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($courses as $course)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('courses.show', $course->id) }}" class="text-sm font-medium text-indigo-600 hover:underline">{{ $course->course_code }} — {{ $course->course_name }}</a>
                                <p class="mt-1 text-sm text-gray-500">Capacity: {{ $course->capacity ?? '—' }} • Credits: {{ $course->credits ?? '—' }}</p>
                            </div>

                            <div class="ms-4 flex-shrink-0 flex items-center space-x-2">
                                @if(isset($enrolledCourseIds) && in_array($course->id, $enrolledCourseIds))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Enrolled</span>
                                @endif
                                <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">View</a>
                                @if(auth()->check() && auth()->user()->is_admin)
                                    <a href="{{ route('courses.edit', $course) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                                    <form method="POST" action="{{ route('courses.destroy', $course) }}" class="inline" onsubmit="return confirm('Delete this course?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md text-sm text-white hover:bg-red-700">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-6 text-center text-sm text-gray-500">No courses found.</li>
                @endforelse
            </ul>
        </div>

        <div class="mt-4">
            @if(method_exists($courses, 'withQueryString') && method_exists($courses, 'links'))
                {{ $courses->withQueryString()->links() }}
            @endif
        </div>
    </div>
@endsection
