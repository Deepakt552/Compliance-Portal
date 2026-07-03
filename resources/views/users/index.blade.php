<x-admin-layout>
    <div class="space-y-6 w-full">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#0e1e3a]">Manage Users</h1>
                <p class="text-xs text-gray-400 mt-1">Create, update, and manage primary user portal accounts.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('users.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <i class="fas fa-user-plus mr-1.5"></i> Create User
                </a>
                <a href="{{ route('import-form') }}"
                   class="inline-flex items-center px-4 py-2 bg-[#ef3b45] hover:bg-[#d12e37] text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <i class="fas fa-file-import mr-1.5"></i> Import Users
                </a>
            </div>
        </div>

        <!-- Filters & Search Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-6">
            <form action="{{ route('users.search') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or code"
                           class="pl-9 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5"
                           style="padding-left: 2.25rem;">
                </div>
                <div class="flex items-center space-x-2 w-full sm:w-auto">
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-sm font-bold rounded-xl transition shadow-sm">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}"
                           class="py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition text-center flex items-center justify-center">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Registry Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            @if ($users->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-users-slash text-4xl text-gray-300 mb-3 block"></i>
                    No users found matching your search.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">User Account Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email Address</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Property Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-150">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <!-- Avatar & Name -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs uppercase">
                                                {{ strtoupper(substr($user->FirstName ?? 'U', 0, 1) . substr($user->LastName ?? 'S', 0, 1)) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-bold text-gray-900">{{ $user->FirstName }} {{ $user->LastName }}</div>
                                                <div class="text-[10px] text-gray-400 font-medium">User ID: {{ $user->UserId ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Email -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                        {{ $user->email }}
                                    </td>

                                    <!-- Vacant Toggle -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <label class="inline-flex items-center cursor-pointer select-none">
                                            <input type="checkbox" class="rounded border-gray-300 text-[#0e1e3a] focus:ring-[#0e1e3a] h-4.5 w-4.5 transition cursor-pointer"
                                                   {{ $user->Vacant ? 'checked' : '' }}
                                                   onchange="updateVacantStatus({{ $user->id }}, this)">
                                            <span class="ml-2 text-xs font-semibold text-gray-700">Vacant Status</span>
                                        </label>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this user?')"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-[#ef3b45] text-[#ef3b45] hover:text-white text-xs font-bold rounded-xl transition border border-red-100 hover:border-transparent">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination links -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-150">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

<script>
function updateVacantStatus(userId, checkbox) {
    const vacant = checkbox.checked ? 1 : 0;

    if (confirm('Are you sure you want to update the vacant status?')) {
        fetch(`/users/${userId}/update-vacant-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    vacant
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error('There was an error updating the vacant status:', error);
            });
    } else {
        // If user cancels, revert checkbox state
        checkbox.checked = !checkbox.checked;
    }
}
</script>