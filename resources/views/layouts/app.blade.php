<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ 'My UdD Portal' }}</title>
    <!-- Styles -->
    @vite('resources/css/app.css')
</head>
<body class="d-flex flex-column h-100">
@auth
<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-4">
                <a href="#" class="text-xl font-bold text-indigo-600">My UdD Portal</a>
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600">My Grades</a>
                <a href="{{ route('subject.load') }}" class="text-gray-700 hover:text-indigo-600">Subject Load Schedule</a>
            </div>
            <div class="flex items-center">
                <span class="text-gray-700 font-medium">
                    {{ Auth::user()->userProfile->full_name }} - ({{ Auth::user()->USER_ID }})
                </span>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800 hover:underline hover:cursor-pointer">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>
@endauth
<main>
    @yield('content')
</main>
<!-- Scripts -->
@vite('resources/js/app.js')
<script>
    document.getElementById('sy_from').addEventListener('change', function () {
        const from = parseInt(this.value);
        const toField = document.getElementById('sy_to');
        toField.value = !isNaN(from) ? from + 1 : '';
    });

    document.getElementById('resetFilters').addEventListener('click', function () {
        window.location.href = '{{ url()->current() }}';
    });
</script>
</body>
</html>