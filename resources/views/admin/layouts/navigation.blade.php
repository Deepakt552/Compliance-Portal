<!-- Sidebar and Topbar Container -->
<div x-data="{ sidebarOpen: false, notificationOpen: false, profileOpen: false }">
    <!-- 1. Mobile Sidebar Drawer Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 md:hidden"
         @click="sidebarOpen = false"
         style="display: none;">
    </div>

    <!-- 2. Mobile Sidebar Drawer Panel -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0e1e3a] text-white flex flex-col md:hidden shadow-2xl border-r border-gray-800"
         style="display: none;">
        
        <!-- Mobile Drawer Header -->
        <div class="h-16 flex items-center justify-between px-6 bg-[#071329] border-b border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="text-white font-extrabold tracking-wider text-base uppercase hover:text-[#ef3b45] transition-colors duration-200">
                Compliance Portal
            </a>
            <button @click="sidebarOpen = false" class="text-white hover:text-[#ef3b45] focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Mobile Drawer Navigation Items -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'icon' => 'fa-chart-pie', 'label' => 'Dashboard'],
                    ['route' => 'users.index', 'icon' => 'fa-users', 'label' => 'Manage Users'],
                    ['route' => 'household.index', 'icon' => 'fa-home', 'label' => 'Manage Household'],
                    ['route' => 'documents.index', 'icon' => 'fa-file-alt', 'label' => 'Manage Documents'],
                    ['route' => 'properties.index', 'icon' => 'fa-building', 'label' => 'Manage Properties'],
                    ['route' => 'admins.index', 'icon' => 'fa-user-shield', 'label' => 'Manage Admins'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || (request()->path() == str_replace(url('/'), '', route($item['route'])));
                @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 
                          {{ $isActive ? 'bg-[#ef3b45] text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-5 hover:text-white' }}">
                    <i class="fas {{ $item['icon'] }} mr-3 text-base w-5"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Mobile Drawer Footer (Logout) -->
        <div class="p-4 bg-[#071329] border-t border-gray-800">
            <div class="flex items-center space-x-3 mb-4">
                <div class="h-9 w-9 rounded-full bg-[#ef3b45] flex items-center justify-center font-bold text-white text-sm">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ Auth::guard('admin')->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::guard('admin')->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-red-600 bg-opacity-20 hover:bg-opacity-100 hover:bg-[#ef3b45] text-white text-sm font-bold rounded-xl transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- 3. Desktop Permanent Left Sidebar -->
    <aside class="hidden md:flex flex-col w-64 bg-[#0e1e3a] text-white fixed inset-y-0 left-0 z-30 border-r border-gray-900">
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 bg-[#071329] border-b border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="text-white font-extrabold tracking-wider text-base uppercase hover:text-[#ef3b45] transition-colors duration-200">
                Compliance Portal
            </a>
        </div>

        <!-- Sidebar Navigation Items -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || (request()->path() == str_replace(url('/'), '', route($item['route'])));
                @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group
                          {{ $isActive ? 'bg-[#ef3b45] text-white shadow-lg shadow-red-600/10' : 'text-gray-300 hover:bg-white hover:bg-opacity-5 hover:text-white' }}">
                    <i class="fas {{ $item['icon'] }} mr-3 text-base w-5 transition-transform duration-200 group-hover:scale-110 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Sidebar Admin Info / Logout -->
        <div class="p-4 bg-[#071329] border-t border-gray-800">
            <div class="flex items-center space-x-3 mb-4">
                <div class="h-9 w-9 rounded-full bg-[#ef3b45] flex items-center justify-center font-bold text-white text-sm">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ Auth::guard('admin')->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::guard('admin')->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-red-600 bg-opacity-20 hover:bg-opacity-100 hover:bg-[#ef3b45] text-white text-sm font-bold rounded-xl transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                </button>
            </form>
        </div>
    </aside>

    <!-- 4. Top Header Navbar (Desktop md:pl-64 wrapper logic) -->
    <header class="h-16 bg-white border-b border-gray-200 fixed top-0 right-0 left-0 md:left-64 z-20 flex items-center justify-between px-6 shadow-sm">
        <!-- Left Side: Hamburger & Title -->
        <div class="flex items-center space-x-4">
            <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="hidden sm:block text-lg font-bold text-[#0e1e3a]">
                @if(request()->routeIs('admin.dashboard'))
                    Dashboard Analytics
                @elseif(request()->routeIs('users.*'))
                    User Management
                @elseif(request()->routeIs('household.*'))
                    Household Management
                @elseif(request()->routeIs('documents.*'))
                    Document Compliance
                @elseif(request()->routeIs('properties.*'))
                    Property Management
                @elseif(request()->routeIs('admins.*'))
                    Admin Management
                @else
                    Admin Console
                @endif
            </h1>
        </div>

        <!-- Right Side: Notifications & Profile Dropdowns -->
        <div class="flex items-center space-x-4">
            <!-- Notifications Bell Dropdown -->
            <div class="relative">
                <button @click="notificationOpen = !notificationOpen" 
                        @click.away="notificationOpen = false" 
                        class="p-2 text-gray-500 hover:text-[#ef3b45] focus:outline-none transition-colors relative">
                    <i class="fas fa-bell text-lg"></i>
                    @if($userNotificationCount > 0)
                        <span class="absolute top-1.5 right-1.5 h-4 w-4 bg-[#ef3b45] text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                            {{ $userNotificationCount }}
                        </span>
                    @endif
                </button>

                <!-- Notifications Dropdown Content -->
                <div x-show="notificationOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-3 bg-white border border-gray-150 rounded-2xl shadow-xl z-50 overflow-hidden"
                     style="display: none; width: min(320px, calc(100vw - 1.5rem));">
                    
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <span class="text-sm font-bold text-[#0e1e3a]">Notifications</span>
                        <div class="flex items-center space-x-2">
                            @if($userNotificationCount > 0)
                                <form action="{{ route('admin.notifications.clearAll') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-gray-500 hover:text-[#ef3b45] font-bold underline focus:outline-none">
                                        Clear All
                                    </button>
                                </form>
                            @endif
                            <span class="text-xs bg-red-100 text-[#ef3b45] px-2 py-0.5 rounded-full font-semibold">{{ $userNotificationCount }} New</span>
                        </div>
                    </div>

                    <div class="max-h-72 overflow-y-auto divide-y divide-gray-100">
                        @php
                            $limitedNotifications = $userNotifications->reverse()->take(10);
                        @endphp
                        @forelse ($limitedNotifications as $notification)
                            @if ($notification->role == 'user')
                                <a href="{{ route('admin.showUserDocuments', ['family_member_id' => $notification->family_member_id]) }}" 
                                   class="block p-4 hover:bg-gray-50 transition duration-150">
                                    <p class="text-xs font-semibold text-gray-800 leading-snug">{{ $notification->message }}</p>
                                    <span class="text-[10px] text-gray-400 mt-1 block">
                                        <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </a>
                            @endif
                        @empty
                            <div class="p-6 text-center text-sm text-gray-500">
                                <i class="far fa-bell-slash text-2xl text-gray-300 mb-2 block"></i>
                                No notifications found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button @click="profileOpen = !profileOpen" 
                        @click.away="profileOpen = false" 
                        class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-gray-100 focus:outline-none transition">
                    <div class="h-8 w-8 rounded-full bg-[#0e1e3a] text-white flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 2)) }}
                    </div>
                    <span class="hidden md:block text-sm font-semibold text-gray-700">{{ Auth::guard('admin')->user()->name }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                </button>

                <!-- Profile Menu -->
                <div x-show="profileOpen" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white border border-gray-150 rounded-xl shadow-xl z-50 py-1"
                     style="display: none;">
                    
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-xs text-gray-400">Signed in as</p>
                        <p class="text-sm font-bold text-[#0e1e3a] truncate">{{ Auth::guard('admin')->user()->name }}</p>
                    </div>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 font-semibold flex items-center transition">
                            <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
</div>