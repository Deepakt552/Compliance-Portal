<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'AR') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@300;400;500;600;700;850&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Fira Sans', sans-serif;
            background-color: #f8fafc;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #0e1e3a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ef3b45;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation Sidebar & Topbar Include -->
        @include('admin.layouts.navigation')

        <!-- Page Content Offset Area -->
        <div class="md:pl-64 pt-16 min-h-screen flex flex-col transition-all duration-300">
            @if (isset($header))
                <header class="bg-white border-b border-gray-100 py-4 px-6">
                    <div class="max-w-7xl mx-auto">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Main Content Canvas -->
            <main class="flex-1 p-4 md:p-8">
                {{ $slot }}
            </main>

            <!-- Page Footer -->
            <footer class="bg-white border-t border-gray-150 py-4 text-center text-xs text-gray-500 font-semibold mt-auto">
                Triumph Management &bull; {{ date('Y-m-d H:i:s T') }}
            </footer>
        </div>
    </div>
</body>
</html>