@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full text-center">
            <div class="mx-auto mb-8 w-32 h-32">
                <x-application-logo class="w-full h-full text-indigo-600" />
            </div>

            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Skwelahan sa Unahan</h1>
          

            <div class="flex items-center justify-center gap-4">
                @auth
                    <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">View Students</a>
                @else
                    <a href="{{ route('choose.login', ['intended' => route('students.index')]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">View Students</a>
                @endauth

                @auth
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">View Courses</a>
                @else
                    <a href="{{ route('choose.login', ['intended' => route('courses.index')]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">View Courses</a>
                @endauth
            </div>

          
        </div>
    </div>
@endsection
