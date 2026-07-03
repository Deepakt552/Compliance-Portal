<x-admin-layout>
    <div class="space-y-6 w-full">
        <!-- Success & Error Alert Messages -->
        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl text-xs font-bold flex items-center shadow-sm">
                <i class="fas fa-check-circle mr-2 text-base"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-xs font-bold flex items-center shadow-sm">
                <i class="fas fa-exclamation-circle mr-2 text-base"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold text-[#0e1e3a] uppercase tracking-wider">Manage Administrators</h1>
                <p class="text-xs text-gray-400 mt-1">Add, update, or revoke access credentials for console administrators.</p>
            </div>
            <a href="{{ route('admins.create') }}"
               class="self-start md:self-auto inline-flex items-center px-4 py-2.5 bg-[#ef3b45] hover:bg-[#d12e37] text-white text-xs font-bold rounded-xl transition shadow-sm">
                <i class="fas fa-plus mr-1.5"></i> Add Administrator
            </a>
        </div>

        <!-- Search Form Card -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6">
            <form method="GET" action="{{ route('admins.search') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email address..."
                           class="pl-9 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#ef3b45] focus:ring focus:ring-[#ef3b45] focus:ring-opacity-20 text-sm py-2.5"
                           style="padding-left: 2.25rem;">
                </div>
                <div class="flex space-x-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-[#0e1e3a] hover:bg-[#162f59] text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center justify-center min-w-[100px]">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admins.index') }}"
                           class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition flex items-center justify-center">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Data List Table -->
        <div class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-sm">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-150 flex items-center justify-between">
                <h3 class="text-xs font-bold text-[#0e1e3a] uppercase tracking-wider">Registered Administrators</h3>
                <span class="text-[10px] bg-slate-200 text-slate-800 font-extrabold px-2.5 py-1 rounded-full">{{ $admins->total() }} Records</span>
            </div>

            @if($admins->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-user-shield text-4xl text-gray-300 mb-3 block"></i>
                    No administrator records found.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Admin Name</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Email Address</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Upload Alerts</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Date Registered</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-150">
                            @foreach($admins as $admin)
                                <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                                    <!-- Avatar & Name -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-full bg-red-50 text-[#ef3b45] flex items-center justify-center font-bold text-xs uppercase border border-red-100">
                                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $admin->name }}</div>
                                                @if(Auth::guard('admin')->id() === $admin->id)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold bg-[#0e1e3a]/10 text-[#0e1e3a] uppercase mt-0.5">Current Account</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Email Address -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                        {{ $admin->email }}
                                    </td>

                                    <!-- Upload Alerts -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($admin->receive_upload_notifications)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                                <i class="fas fa-bell mr-1"></i> Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-gray-50 text-gray-400 border border-gray-150">
                                                <i class="fas fa-bell-slash mr-1"></i> Muted
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Date Registered -->
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-400 font-medium">
                                        {{ $admin->created_at ? $admin->created_at->format('M d, Y H:i T') : 'N/A' }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                        <a href="{{ route('admins.edit', $admin->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 border border-gray-200 hover:border-[#0e1e3a] text-[#0e1e3a] text-[10px] font-bold rounded-xl transition uppercase tracking-wider">
                                            Edit
                                        </a>

                                        @if(Auth::guard('admin')->id() !== $admin->id)
                                            <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" class="inline-block"
                                                  onsubmit="return confirm('Are you sure you want to delete this administrator account? This action is irreversible.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent bg-red-50 hover:bg-[#ef3b45] text-[#ef3b45] hover:text-white text-[10px] font-bold rounded-xl transition uppercase tracking-wider">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-block px-3 py-1.5 bg-gray-50 text-gray-300 text-[10px] font-bold rounded-xl cursor-not-allowed uppercase tracking-wider" title="You cannot delete yourself">
                                                Delete
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-150">
                    {{ $admins->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
