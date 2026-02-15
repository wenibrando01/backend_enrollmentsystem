@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div>
                <a href="{{ auth()->user()->is_admin ? route('students.index') : route('dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h1>
                <p class="mt-1 text-sm text-gray-500">Student Number: {{ $student->student_number }} · {{ $student->email }}</p>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-4 rounded-md bg-red-50 p-3 text-red-800">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-6 sm:px-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Enroll in a course</h3>

                @if(auth()->user()->is_admin && $student->email === auth()->user()->email)
                    <div class="rounded-md bg-amber-50 p-4 text-amber-800 text-sm">Admins cannot enroll themselves in courses.</div>
                @else
                <p class="text-sm text-gray-600 mb-4">Select courses to add to your list, then click Enroll to permanently enroll.</p>

                <form method="post" action="{{ auth()->user()->is_admin ? route('students.enroll', $student) : route('dashboard.enroll') }}" id="enroll-form">
                    @csrf
                    <ul class="divide-y divide-gray-200 mb-6">
                        @foreach($allCourses as $course)
                            @php $isEnrolled = $student->courses->contains('id', $course->id); @endphp
                            <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <label for="course-{{ $course->id }}" class="flex-1 min-w-0 {{ !$isEnrolled ? 'cursor-pointer' : '' }}">
                                    <div class="text-sm font-medium text-gray-800">{{ $course->course_code }} — {{ $course->course_name }}</div>
                                </label>
                                @if($isEnrolled)
                                    <div class="ms-4 flex-shrink-0">
                                        <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">Enrolled</span>
                                    </div>
                                @else
                                    <div class="ms-4 flex-shrink-0 flex items-center space-x-2">
                                        <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" id="course-{{ $course->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 m-0">
                                        <span class="text-sm text-gray-500 whitespace-nowrap">Inlist</span>
                                    </div>
                                @endif
                            </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="px-4 sm:px-6 pb-4">
                        <x-primary-button type="button" id="enroll-btn" disabled>Enroll</x-primary-button>
                    </div>
                </form>

                <!-- Enroll confirmation modal -->
                <div id="enroll-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="modal-backdrop"></div>
                        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Enroll course</h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-6">Are you sure you want to enroll in the selected course(s)?</p>
                            <div class="flex justify-end gap-2">
                                <button type="button" id="modal-cancel" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                                <button type="button" id="modal-confirm" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">Enroll now</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.querySelectorAll('input[name="course_ids[]"]').forEach(cb => {
                        cb.addEventListener('change', () => {
                            document.getElementById('enroll-btn').disabled = !document.querySelector('input[name="course_ids[]"]:checked');
                        });
                    });
                    if (document.querySelector('input[name="course_ids[]"]:checked')) {
                        document.getElementById('enroll-btn').disabled = false;
                    }

                    const enrollForm = document.getElementById('enroll-form');
                    const enrollBtn = document.getElementById('enroll-btn');
                    const modal = document.getElementById('enroll-modal');
                    const modalBackdrop = document.getElementById('modal-backdrop');
                    const modalCancel = document.getElementById('modal-cancel');
                    const modalConfirm = document.getElementById('modal-confirm');

                    enrollBtn.addEventListener('click', () => {
                        if (!document.querySelector('input[name="course_ids[]"]:checked')) return;
                        modal.classList.remove('hidden');
                    });

                    function closeModal() {
                        modal.classList.add('hidden');
                    }

                    modalCancel.addEventListener('click', closeModal);
                    modalBackdrop.addEventListener('click', closeModal);

                    modalConfirm.addEventListener('click', () => {
                        closeModal();
                        enrollForm.submit();
                    });
                </script>
                @endif
            </div>
        </div>
    </div>
@endsection
