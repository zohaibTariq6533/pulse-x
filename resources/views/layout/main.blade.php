<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title>{{ config('app.name') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#2C5364">
        <meta name="description" content="Fitness and nutrition tracking app">
        
        <!-- Apple iOS PWA Meta Tags -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Pulse-X">
        <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
        
        <!-- Microsoft Tiles -->
        <meta name="msapplication-TileColor" content="#2C5364">
        <meta name="msapplication-TileImage" content="{{ asset('icons/icon-192x192.png') }}">
        
        <!-- Additional PWA Meta Tags -->
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="application-name" content="Pulse-X">
        
        <!-- Manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen">
    @yield('content')

    {{-- Bottom Navigation --}}
    @include('partials.bottom-nav')

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
    </body>
</html>
