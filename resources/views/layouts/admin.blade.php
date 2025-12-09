<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- NAVBAR ADMIN --}}
    <nav class="bg-white shadow border-b">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>

            <div class="flex gap-6">

                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'font-semibold text-blue-600' : 'text-gray-700' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.verification') }}"
                   class="{{ request()->routeIs('admin.verification*') ? 'font-semibold text-blue-600' : 'text-gray-700' }}">
                    Verifikasi Toko
                </a>

                <a href="{{ route('admin.users') }}"
                   class="{{ request()->routeIs('admin.users*') ? 'font-semibold text-blue-600' : 'text-gray-700' }}">
                    Manajemen Users
                </a>

                {{-- LOGOUT --}}
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="text-red-600">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>

            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="max-w-7xl mx-auto p-6">
        @yield('content')
    </main>

</body>
</html>