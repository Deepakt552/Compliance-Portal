<x-admin-layout>
    <div class="space-y-6 w-full">
        <!-- Header Panel -->
        <div class="rounded-2xl bg-gradient-to-r from-[#0e1e3a] via-[#172f58] to-[#0e1e3a] text-white p-6 md:p-8 shadow-sm border border-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 h-48 w-48 rounded-full bg-[#ef3b45]/10 blur-3xl pointer-events-none"></div>
            <div>
                <span class="text-[10px] uppercase tracking-widest text-[#ef3b45] font-extrabold">Audit Trails</span>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-1">Email Logs</h1>
                <p class="text-slate-300 text-xs mt-1">Track and inspect all emails sent out by the compliance portal.</p>
            </div>
            <div class="bg-white/10 px-4 py-2.5 rounded-xl border border-white/10 text-xs font-semibold text-gray-200">
                <i class="fas fa-history mr-1.5 text-[#ef3b45]"></i> Outbox Archive
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.email-logs.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 text-sm">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search by recipient or subject line..." 
                           class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border-gray-200 bg-slate-50/50 focus:bg-white focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a]/10">
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-initial px-5 py-2.5 bg-[#0e1e3a] hover:bg-[#172f58] text-white text-xs font-bold uppercase tracking-wider rounded-xl transition duration-200 focus:outline-none">
                        Search
                    </button>
                    @if($search)
                        <a href="{{ route('admin.email-logs.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-250 text-gray-700 text-xs font-bold uppercase tracking-wider rounded-xl transition duration-200 text-center flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Data Table Card -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-gray-500 font-extrabold uppercase text-[10px] tracking-wider border-b border-gray-150">
                            <th class="px-6 py-4">Date / Time</th>
                            <th class="px-6 py-4">Recipient</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-gray-900 block">{{ $log->created_at->format('M d, Y') }}</span>
                                    <span class="text-xs text-gray-400">{{ $log->created_at->format('h:i A') }} ({{ $log->created_at->diffForHumans() }})</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    <span class="break-all">{{ $log->recipient }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-700">{{ $log->subject }}</span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if ($log->status === 'sent')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-150">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                            Sent
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-150">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                            Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.email-logs.show', $log->id) }}" 
                                       class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold bg-gray-100 text-[#0e1e3a] hover:bg-[#0e1e3a] hover:text-white rounded-xl transition duration-200">
                                        <i class="far fa-eye mr-1.5"></i> Inspect
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-500">
                                    <i class="far fa-envelope-open text-4xl text-gray-300 mb-3.5 block"></i>
                                    <span class="font-semibold block text-base text-gray-400">No Email Logs Found</span>
                                    <span class="text-xs text-gray-400">Sent emails will appear here automatically when triggered by the system.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if ($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-slate-50/50">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
