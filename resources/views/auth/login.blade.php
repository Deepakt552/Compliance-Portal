<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'AR') }}</title>

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
            <h1 class="text-[#0e1e3a] text-2xl font-extrabold tracking-widest uppercase">Compliance Portal</h1>
            <p class="text-gray-400 text-xs mt-1.5 font-medium">Please log in to manage your household documents</p>
        </div>

        <div class="login-card rounded-2xl shadow-xl p-8 border border-gray-200">
            <h2 class="text-xl font-bold text-[#0e1e3a] mb-6 text-center">User Log In</h2>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-lg border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- UserId Input -->
                <div>
                    <label for="UserId" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">User ID</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none z-10">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </span>
                        <input id="UserId" type="text" name="UserId" value="{{ old('UserId') }}" required autofocus autocomplete="username"
                            placeholder="Enter your User ID"
                            class="pl-10 appearance-none rounded-xl relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus-brand text-sm transition-all duration-250" />
                    </div>
                    @if($errors->has('UserId'))
                        <p class="text-[#ef3b45] text-xs mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('UserId') }}</p>
                    @endif
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none z-10">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="Enter your password"
                            class="pl-10 appearance-none rounded-xl relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus-brand text-sm transition-all duration-250" />
                    </div>
                    @if($errors->has('password'))
                        <p class="text-[#ef3b45] text-xs mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-xs font-semibold">
                    <label class="flex items-center cursor-pointer select-none text-gray-600">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-[#0e1e3a] focus:ring-[#0e1e3a] h-4 w-4 mr-2" />
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-[#0e1e3a] hover:text-[#ef3b45] hover:underline transition-colors duration-200"
                            href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full btn-primary text-white font-bold py-3 px-4 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e1e3a] shadow-sm">
                        <i class="fas fa-sign-in-alt mr-2"></i>Log In
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