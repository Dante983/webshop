<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div id="notification" class="fixed top-4 right-4 z-50 transform transition-transform duration-300 translate-x-full opacity-0">
            <div class="bg-green-500 text-white px-4 py-3 rounded shadow-lg flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span id="notification-message"></span>
            </div>
        </div>
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        @stack('scripts')
        <script>
            function showNotification(message, isError = false) {
                const notification = document.getElementById('notification');
                const notificationMessage = document.getElementById('notification-message');
                const notificationBg = notification.firstElementChild;
                
                // Set the message
                notificationMessage.textContent = message;
                
                // Set the color based on message type
                if (isError) {
                    notificationBg.classList.remove('bg-green-500');
                    notificationBg.classList.add('bg-red-500');
                } else {
                    notificationBg.classList.remove('bg-red-500');
                    notificationBg.classList.add('bg-green-500');
                }
                
                // Show the notification
                notification.classList.remove('translate-x-full', 'opacity-0');
                
                // Hide after 3 seconds
                setTimeout(() => {
                    notification.classList.add('translate-x-full', 'opacity-0');
                }, 3000);
            }
            
            // Check for flash messages on page load
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    showNotification("{{ session('success') }}");
                @endif
                
                @if(session('error'))
                    showNotification("{{ session('error') }}", true);
                @endif
            });
        </script>
    </body>
</html>
