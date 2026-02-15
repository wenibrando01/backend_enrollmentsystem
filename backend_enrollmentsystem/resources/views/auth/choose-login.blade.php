<x-guest-layout>
    <div class="max-w-md mx-auto mt-12 p-6 bg-white shadow rounded">
        <h2 class="text-xl font-semibold mb-4">Choose Login Type</h2>
        <div class="space-y-3">
            <a href="{{ route('login') }}" class="block text-center px-4 py-2 bg-indigo-600 text-white rounded">Student Login</a>
            <a href="{{ route('admin.login') }}" class="block text-center px-4 py-2 border border-gray-300 rounded">Admin Login</a>
            <a href="{{ url('/') }}" class="mt-4 block text-center text-sm text-gray-600">Cancel</a>
        </div>
    </div>
</x-guest-layout>
