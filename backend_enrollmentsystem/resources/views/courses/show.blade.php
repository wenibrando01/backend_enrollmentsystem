@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="javascript:history.back()" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-8">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xl font-bold">{{ strtoupper($course->course_code) }}</div>
                    </div>

                    <div class="flex-1">
                        <h1 class="text-2xl font-semibold text-gray-900">{{ $course->course_code }} — {{ $course->course_name }}</h1>
                        @if(!empty($course->description))
                            <p class="mt-2 text-sm text-gray-600">{{ $course->description }}</p>
                        @endif

                        <div class="mt-3 flex flex-wrap gap-3 text-sm">
                            <div class="px-3 py-1 rounded-md bg-gray-100 text-gray-700">Capacity: <span class="font-medium">{{ $course->capacity ?? '—' }}</span></div>
                            <div class="px-3 py-1 rounded-md bg-gray-100 text-gray-700">Credits: <span class="font-medium">{{ $course->credits ?? '—' }}</span></div>
                            <div class="px-3 py-1 rounded-md bg-gray-100 text-gray-700">Enrolled: <span class="font-medium">{{ $course->students->count() }}</span></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="border-t bg-gray-50 px-6 py-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Enrolled Students</h2>

                <div class="space-y-3">
                    @forelse($course->students as $student)
                        <div class="flex items-center justify-between bg-white border rounded-md px-4 py-3">
                            <div>
                                <div class="font-semibold text-gray-800">{{ $student->student_number }} — {{ $student->first_name }} {{ $student->last_name }}</div>
                                <div class="text-sm text-gray-500">{{ $student->email ?? 'No email' }}</div>
                            </div>
                            <div class="text-sm text-gray-500">View profile</div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-600">No students enrolled.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
