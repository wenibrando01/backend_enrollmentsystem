<a href="{{ auth()->check() ? (auth()->user()->is_admin ? route('admin.dashboard') : route('dashboard')) : url('/') }}">
    <img src="{{ asset('images/198a29b43bf4729dfd940e418e0d7250-removebg-preview.png') }}" alt="{{ config('app.name') }}" {{ $attributes }}>
</a>
