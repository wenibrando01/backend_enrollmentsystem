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
                <h1 class="text-xl font-semibold text-gray-900 mb-6">Edit Student</h1>

                <form method="POST" action="{{ route('students.update', $student) }}">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        <div>
                            <label for="student_number" class="block text-sm font-medium text-gray-700">Student Number</label>
                            <input type="text" name="student_number" id="student_number" value="{{ old('student_number', $student->student_number) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('student_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $student->first_name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $student->last_name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <x-primary-button type="submit">Update Student</x-primary-button>
                        <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
