<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - {{ config('app.name', 'AR') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #ffffff;
        }
        .login-card {
            background-color: #ffffff;
        }
        .btn-primary {
            background-color: #0e1e3a;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #ef3b45;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 59, 69, 0.2);
        }
        .focus-brand:focus {
            border-color: #ef3b45;
            box-shadow: 0 0 0 3px rgba(239, 59, 69, 0.15);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Branding Header -->
        <div class="text-center mb-8">
            <h1 class="text-[#0e1e3a] text-2xl font-extrabold tracking-widest uppercase">Admin Portal</h1>
            <p class="text-gray-400 text-xs mt-1.5 font-medium">Authorized Administrative Access Only</p>
        </div>

        <div class="login-card rounded-2xl shadow-xl p-8 border border-gray-200">
            <h2 class="text-xl font-bold text-[#0e1e3a] mb-6 text-center font-bold">Admin Log In</h2>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-lg border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                            placeholder="Enter administrative email"
                            class="pl-10 appearance-none rounded-xl relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus-brand text-sm transition-all duration-250" />
                    </div>
                    @if($errors->has('email'))
                        <p class="text-[#ef3b45] text-xs mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="Enter password"
                            class="pl-10 appearance-none rounded-xl relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus-brand text-sm transition-all duration-250" />
                    </div>
                    @if($errors->has('password'))
                        <p class="text-[#ef3b45] text-xs mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full btn-primary text-white font-bold py-3 px-4 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e1e3a] shadow-sm">
                        <i class="fas fa-shield-alt mr-2"></i>Log In
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Info -->
        <p class="text-center text-gray-400 text-xs mt-8 font-medium">
            &copy; {{ date('Y') }} Triumph Management. All rights reserved.
        </p>
    </div>
</body>
</html>