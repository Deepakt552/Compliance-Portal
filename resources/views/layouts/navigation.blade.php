<nav x-data="{ open: false, notificationOpen: false, profileOpen: false }"
     class="bg-[#0e1e3a] text-white border-b border-gray-800 shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side: Logo & Main Links -->
            <div class="flex items-center space-x-8">
                <!-- Logo / Text Branding -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="text-white font-extrabold tracking-wider text-base uppercase hover:text-[#ef3b45] transition-colors duration-200">
                        Compliance Portal
                    </a>
                </div>

                @auth
                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:space-x-4">
                    <!-- Dashboard Link -->
                    <a href="{{ route('user.dashboard') }}" 
                       class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-xl transition duration-200 
                              {{ request()->routeIs('user.dashboard') ? 'bg-[#ef3b45] text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-5 hover:text-white' }}">
                        <i class="fas fa-home mr-2 text-xs"></i> Dashboard
                    </a>

                    <!-- Status Link -->
                    <a href="{{ route('document.status') }}" 
                       class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-xl transition duration-200 
                              {{ request()->routeIs('document.status') ? 'bg-[#ef3b45] text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-5 hover:text-white' }}">
                        <i class="fas fa-file-invoice mr-2 text-xs"></i> Document Status
                    </a>
                </div>
                @endauth
            </div>

            <!-- Right Side: Notifications & User Settings (Authenticated Users Only) -->
            @auth
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                
                <!-- Notification Bell Dropdown -->
                <div class="relative">
                    <button id="notificationButton" @click="notificationOpen = !notificationOpen" @click.away="notificationOpen = false" 
                            class="p-2 rounded-xl text-gray-300 hover:text-[#ef3b45] hover:bg-white hover:bg-opacity-5 transition focus:outline-none relative">
                        <i class="fas fa-bell text-base"></i>
                        @if($notificationCount > 0)
                            <span class="absolute top-1 right-1 h-4 w-4 bg-[#ef3b45] text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                {{ $notificationCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown Panels -->
                    <div x-show="notificationOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-72 bg-white text-gray-900 border border-gray-150 rounded-2xl shadow-xl z-50 overflow-hidden" style="max-width: min(288px, calc(100vw - 2rem));"
                         style="display: none;">
                        
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                            <span class="text-xs font-bold text-[#0e1e3a] uppercase">Notifications</span>
                            <div class="flex items-center space-x-2">
                                @if($notificationCount > 0)
                                    <form action="{{ route('notifications.clearAll') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-gray-500 hover:text-[#ef3b45] font-bold underline focus:outline-none">
                                            Clear All
                                        </button>
                                    </form>
                                @endif
                                <span class="text-[10px] bg-red-50 text-[#ef3b45] font-bold px-2 py-0.5 rounded-full border border-red-100">{{ $notificationCount }} Alert</span>
                            </div>
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                            @php
                                $limitedNotifications = $notifications->reverse()->take(10);
                            @endphp
                            @forelse ($limitedNotifications as $notification)
                                @if ($notification->role == 'Admin')
                                    @php
                                        $documentName = \App\Models\Document::getDocumentName($notification->message);
                                        $isApproved = $notification->status == 'approved';
                                    @endphp
                                    <div class="p-3 hover:bg-gray-50 transition">
                                        <p class="text-xs font-semibold text-gray-800 leading-snug">
                                            {{ $documentName }} has been 
                                            <span class="{{ $isApproved ? 'text-green-600' : 'text-red-500' }} font-bold">
                                                {{ $notification->status }}
                                            </span>
                                        </p>
                                        <span class="text-[9px] text-gray-400 mt-1 block">
                                            <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @endif
                            @empty
                                <div class="p-4 text-center text-xs text-gray-500">
                                    <i class="far fa-bell-slash text-lg text-gray-300 mb-1.5 block"></i>
                                    No notifications found.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Dropdown Menu -->
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" 
                            class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-white hover:bg-opacity-5 focus:outline-none transition">
                        <div class="h-8 w-8 rounded-full bg-[#ef3b45] text-white flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-200">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white text-gray-900 border border-gray-150 rounded-xl shadow-xl z-50 py-1"
                         style="display: none;">
                        
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs text-gray-400">Signed in as</p>
                            <p class="text-xs font-bold text-[#0e1e3a] truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center transition">
                            <i class="fas fa-user-cog mr-2 text-gray-400"></i> Edit Profile
                        </a>
                        
                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 hover:text-red-700 flex items-center transition">
                                <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Right Controls: Bell + Hamburger -->
            <div class="flex items-center space-x-1 sm:hidden">

                <!-- Mobile Bell Icon -->
                <div class="relative" x-data="{ mobileNotifOpen: false }">
                    <button @click="mobileNotifOpen = !mobileNotifOpen" @click.away="mobileNotifOpen = false"
                            class="p-2 rounded-xl text-gray-300 hover:text-[#ef3b45] hover:bg-white hover:bg-opacity-5 transition focus:outline-none relative">
                        <i class="fas fa-bell text-base"></i>
                        @if($notificationCount > 0)
                            <span class="absolute top-1 right-1 h-4 w-4 bg-[#ef3b45] text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                {{ $notificationCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Mobile Notification Dropdown -->
                    <div x-show="mobileNotifOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-3 bg-white text-gray-900 border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden"
                         style="display:none; width: calc(100vw - 2rem); max-width: 320px;">

                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                            <span class="text-xs font-bold text-[#0e1e3a] uppercase">Notifications</span>
                            <span class="text-[10px] bg-red-50 text-[#ef3b45] font-bold px-2 py-0.5 rounded-full border border-red-100">{{ $notificationCount }} Alert</span>
                        </div>
                        <div class="max-h-56 overflow-y-auto divide-y divide-gray-100">
                            @php $limitedMobile = $notifications->reverse()->take(8); @endphp
                            @forelse ($limitedMobile as $notification)
                                @if ($notification->role == 'Admin')
                                    @php
                                        $documentNameM = \App\Models\Document::getDocumentName($notification->message);
                                        $isApprovedM   = $notification->status == 'approved';
                                    @endphp
                                    <div class="p-3 hover:bg-gray-50 transition">
                                        <p class="text-xs font-semibold text-gray-800 leading-snug">
                                            {{ $documentNameM }} has been
                                            <span class="{{ $isApprovedM ? 'text-green-600' : 'text-red-500' }} font-bold">
                                                {{ $notification->status }}
                                            </span>
                                        </p>
                                        <span class="text-[9px] text-gray-400 mt-1 block">
                                            <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @endif
                            @empty
                                <div class="p-4 text-center text-xs text-gray-500">
                                    <i class="far fa-bell-slash text-lg text-gray-300 mb-1.5 block"></i>
                                    No notifications found.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Hamburger Button -->
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-xl text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-5 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @endauth
        </div>
    </div>

    <!-- Responsive Navigation Mobile Drawer Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden bg-[#071329] border-t border-gray-800">
        @auth
        <div class="pt-2 pb-3 space-y-1 px-4">
            <!-- Mobile Links -->
            <a href="{{ route('user.dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold text-gray-300 hover:bg-white hover:bg-opacity-5 hover:text-white transition">
                <i class="fas fa-home mr-3"></i> Dashboard
            </a>
            <a href="{{ route('document.status') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold text-gray-300 hover:bg-white hover:bg-opacity-5 hover:text-white transition">
                <i class="fas fa-file-invoice mr-3"></i> Document Status
            </a>
        </div>

        <!-- Mobile User Profile Operations -->
        <div class="pt-4 pb-4 border-t border-gray-800 px-4 bg-[#050d1b]">
            <div class="flex items-center space-x-3 px-4 mb-4">
                <div class="h-9 w-9 bg-[#ef3b45] text-white flex items-center justify-center font-bold text-xs rounded-full shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="text-sm font-bold text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1.5">
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-300 hover:bg-white hover:bg-opacity-5 hover:text-white transition">
                    <i class="fas fa-user-cog mr-3 text-gray-400"></i> Edit Profile
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-red-400 hover:bg-red-950/20 hover:text-red-300 transition">
                        <i class="fas fa-sign-out-alt mr-3"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>