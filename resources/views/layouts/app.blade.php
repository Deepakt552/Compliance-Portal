<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AR') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Assets Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: "Montserrat", sans-serif;
        }
        body {
            background-color: #f9fafb;
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

        /* Toast Keyframe Animations */
        @keyframes slideIn {
            from {
                transform: translate(100%, 20px);
                opacity: 0;
            }
            to {
                transform: translate(0, 0);
                opacity: 1;
            }
        }
        .alert-toast {
            animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .hide-toast-animation {
            transform: translate(100%, 20px) !important;
            opacity: 0 !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation bar -->
        @include('layouts.navigation')

        <!-- Page Heading Header -->
        @if (isset($header))
            <header class="bg-white border-b border-gray-150 shadow-sm py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content Canvas -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Page Footer -->
        <footer class="bg-white border-t border-gray-150 py-4 text-center text-xs text-gray-500 font-semibold mt-auto">
            Triumph Management &bull; {{ date('Y-m-d H:i:s T') }}
        </footer>
    </div>

    <!-- Notification Toast System (Session Triggered) -->
    @if(session('success') || session('error'))
        <div class="alert-toast fixed bottom-6 right-6 z-50 w-full max-w-sm">
            <input type="checkbox" class="hidden" id="toast-closer">
            <label for="toast-closer" 
                   class="close cursor-pointer flex items-center justify-between w-full p-4 rounded-2xl shadow-2xl border transition duration-200 
                          {{ session('error') ? 'bg-red-600 text-white border-red-500 hover:bg-red-700' : 'bg-green-600 text-white border-green-500 hover:bg-green-700' }}">
                <div class="flex items-center space-x-3">
                    <i class="fas {{ session('error') ? 'fa-exclamation-circle' : 'fa-check-circle' }} text-lg"></i>
                    <span class="text-sm font-semibold">{{ session('success') ?? session('error') }}</span>
                </div>
                <i class="fas fa-times text-sm opacity-70 hover:opacity-100"></i>
            </label>
        </div>

        <script>
            // Function to close the toast automatically after 6 seconds
            function autoCloseToast() {
                var toast = document.querySelector('.alert-toast');
                if (toast) {
                    toast.classList.add('hide-toast-animation');
                    setTimeout(function() {
                        toast.style.display = 'none';
                    }, 300);
                }
            }
            setTimeout(autoCloseToast, 6000);
        </script>
    @endif
</body>
</html>