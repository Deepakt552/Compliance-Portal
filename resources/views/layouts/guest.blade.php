<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans  bg-white">
    <div class="min-h-screen flex  sm:justify-center items-center sm:pt-0">
        <div>
            <!-- <a href="/">
                <img src="{{ asset('Logo.png') }}" alt="Triumph"
                    class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" ">
                    </a> -->

        </div>

        <div class=" w-full sm:max-w-md  px-6 py-4 bg-white">
            {{ $slot }}
        </div>
    </div>
</body>

</html>