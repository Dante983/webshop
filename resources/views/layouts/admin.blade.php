<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="flex">
            <!-- Sidebar -->
            <div class="w-64 bg-gray-800 text-white min-h-screen p-4">
                <div class="text-xl font-bold mb-8">Admin Panel</div>
                <nav class="mt-6">
                    <div class="px-4 py-2 text-xs text-gray-400 uppercase">
                        Navigation
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : '' }}">
                        Products
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }}">
                        Categories
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">
                        Orders
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
                        Users
                    </a>
                    <div class="px-4 py-2 mt-4 text-xs text-gray-400 uppercase">
                        Account
                    </div>
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm hover:bg-gray-700">
                        Back to Shop
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="block px-4 py-2 text-sm hover:bg-gray-700">
                        @csrf
                        <button type="submit" class="w-full text-left">Logout</button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Top Navigation -->
                <nav class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex">
                                <div class="flex-shrink-0 flex items-center">
                                    <span class="text-lg font-semibold">{{ config('app.name', 'Laravel') }} Admin</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="ml-3 relative">
                                    <div class="text-sm text-gray-500">
                                        Welcome, {{ Auth::user()->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="py-4">
                    @if (session('success'))
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>
</html> 