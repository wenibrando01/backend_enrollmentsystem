@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div>
                <a href="javascript:history.back()" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">Students</h1>
                <p class="mt-1 text-sm text-gray-500">Manage student records and enrollments.</p>
            </div>

            <div class="mt-4 sm:mt-0 sm:flex sm:items-center sm:space-x-3">
                <form method="GET" action="{{ route('students.index') }}" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by ID, name, or email..." class="block w-64 rounded-md border-gray-300 shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500" />
                    <button type="submit" class="sr-only">Search</button>
                </form>

            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($students as $student)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('students.show', $student->id) }}" class="text-sm font-medium text-indigo-600 hover:underline">{{ $student->student_number }} — {{ $student->first_name }} {{ $student->last_name }}</a>
                                <p class="mt-1 text-sm text-gray-500">{{ $student->email ?? 'No email' }}</p>
                            </div>

                            <div class="ms-4 flex-shrink-0 flex items-center space-x-2">
                                <a href="{{ route('students.show', $student) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">View</a>
                                @if(auth()->check() && auth()->user()->is_admin)
                                    <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                                    <form method="POST" action="{{ route('students.destroy', $student) }}" class="inline" onsubmit="return confirm('Delete this student?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md text-sm text-white hover:bg-red-700">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-6 text-center text-sm text-gray-500">No students found.</li>
                @endforelse
            </ul>
        </div>

        <div class="mt-4">
            @if(method_exists($students, 'withQueryString') && method_exists($students, 'links'))
                {{ $students->withQueryString()->links() }}
            @endif
        </div>
    </div>
@endsection
