@extends('layouts.app')

@section('content')
    @include('admin.nav')

    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="javascript:history.back()" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-6">
                <h1 class="text-xl font-semibold text-gray-900 mb-6">Edit Course</h1>

                <form method="POST" action="{{ route('courses.update', $course) }}">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        <div>
                            <label for="course_code" class="block text-sm font-medium text-gray-700">Course Code</label>
                            <input type="text" name="course_code" id="course_code" value="{{ old('course_code', $course->course_code) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('course_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="course_name" class="block text-sm font-medium text-gray-700">Course Name</label>
                            <input type="text" name="course_name" id="course_name" value="{{ old('course_name', $course->course_name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('course_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $course->capacity) }}" min="0" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <x-primary-button type="submit">Update Course</x-primary-button>
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
