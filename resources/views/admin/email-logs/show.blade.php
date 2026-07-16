<x-admin-layout>
    <div class="space-y-6 w-full max-w-5xl mx-auto">
        <!-- Back and Navigation Header -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.email-logs.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-[#0e1e3a] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Email Logs
            </a>
            <span class="text-xs text-gray-400">Log ID: #{{ $log->id }}</span>
        </div>

        <!-- Detail Inspector Panel -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Metadata Card -->
            <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-6 self-start">
                <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-[#ef3b45]"></i> Delivery Details
                </h3>

                <div class="space-y-4 text-xs">
                    <div>
                        <span class="block text-gray-400 font-extrabold uppercase tracking-wider mb-1">Recipient</span>
                        <span class="block text-sm font-bold text-gray-800 break-all bg-slate-50 p-2.5 rounded-xl border border-slate-100">{{ $log->recipient }}</span>
                    </div>

                    <div>
                        <span class="block text-gray-400 font-extrabold uppercase tracking-wider mb-1">Subject</span>
                        <span class="block text-sm font-semibold text-gray-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">{{ $log->subject }}</span>
                    </div>

                    <div>
                        <span class="block text-gray-400 font-extrabold uppercase tracking-wider mb-1">Sent Date / Time</span>
                        <span class="block text-sm font-semibold text-gray-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            {{ $log->created_at->format('M d, Y h:i:s A') }}
                            <span class="block text-[10px] text-gray-400 font-normal mt-0.5">{{ $log->created_at->diffForHumans() }}</span>
                        </span>
                    </div>

                    <div>
                        <span class="block text-gray-400 font-extrabold uppercase tracking-wider mb-1">Delivery Status</span>
                        <div class="mt-1">
                            @if ($log->status === 'sent')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-150">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span>
                                    Sent Successfully
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-150">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                                    Failed
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Body Preview Canvas -->
            <div class="lg:col-span-2 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Email HTML Body Preview</h3>
                    <span class="text-xs text-gray-400 flex items-center">
                        <i class="fas fa-shield-alt mr-1.5 text-green-500"></i> Sandboxed Preview
                    </span>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-4">
                    <iframe srcdoc="{{ htmlspecialchars($log->body) }}" 
                            class="w-full min-h-[550px] border-0 rounded-xl bg-[#f8fafc]" 
                            sandbox="allow-same-origin">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
