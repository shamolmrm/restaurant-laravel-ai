<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Restaurant Management System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background: linear-gradient(135deg, #0A2647 0%, #1a1a2e 50%, #8B0000 100%) !important;
            }
            .login-card {
                border-top: 4px solid #8B0000;
            }
            .app-name {
                color: #8B0000;
                font-family: Georgia, serif;
                font-weight: 700;
                font-size: 1.1rem;
                letter-spacing: 0.5px;
                margin-top: 8px;
            }
            .app-sub {
                color: #666;
                font-size: 0.78rem;
                letter-spacing: 1px;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="text-center">
                <a href="/" class="flex flex-col items-center">
                    <x-application-logo class="w-20 h-20" />
                    <span class="app-name">{{ config('app.name', 'Restaurant Management System') }}</span>
                    <span class="app-sub">Management System</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg login-card">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-white text-opacity-60" style="color: rgba(255,255,255,0.5);">
                &copy; {{ date('Y') }} Restaurant Management System
            </p>
        </div>
    </body>
</html>
