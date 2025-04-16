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
        <div class="flex justify-between h-16 items-center">
            <!-- Left side: Logo and links -->
            <div class="flex items-center space-x-4">
                <a href="#" class="text-xl font-bold text-indigo-600">My UdD Portal</a>
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden">
                <button id="menu-toggle" class="text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Right side: User info (hidden on mobile) -->
            <div class="hidden lg:flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600">My Grades</a>
                <a href="{{ route('subject.load') }}" class="text-gray-700 hover:text-indigo-600">Subject Load Schedule</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800 hover:underline hover:cursor-pointer">Logout</button>
                </form>
            </div>
        </div>

        <!-- Mobile menu (hidden by default) -->
        <div id="mobile-menu" class="hidden lg:hidden mt-2 space-y-2">
            <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-indigo-600">My Grades</a>
            <a href="{{ route('subject.load') }}" class="block text-gray-700 hover:text-indigo-600">Subject Load Schedule</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-600 hover:text-red-800 hover:underline">Logout</button>
            </form>
        </div>
    </div>
</nav>
@endauth
<main class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6">
        <p class="text-sm"><span class="font-semibold mr-2">Name:</span>{{ Auth::user()->userProfile->full_name }}</p>
        <p class="text-sm"><span class="font-semibold mr-2">Username:</span>{{ Auth::user()->USER_ID }}</p>
        <p class="text-sm"><span class="font-semibold mr-2">ID Number:</span>{{ Auth::user()->userProfile->ID_NUMBER }}</p>
    </div>
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
<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>

</body>
</html>