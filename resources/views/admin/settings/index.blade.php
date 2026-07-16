<x-admin-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header Panel -->
        <div class="rounded-2xl bg-gradient-to-r from-[#0e1e3a] via-[#172f58] to-[#0e1e3a] text-white p-6 md:p-8 shadow-sm border border-gray-900 flex justify-between items-center relative overflow-hidden">
            <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 h-48 w-48 rounded-full bg-[#ef3b45]/10 blur-3xl pointer-events-none"></div>
            <div>
                <span class="text-[10px] uppercase tracking-widest text-[#ef3b45] font-extrabold">System Configuration</span>
                <h1 class="text-2xl font-extrabold tracking-tight mt-1">Notification Settings</h1>
                <p class="text-slate-300 text-xs mt-1">Configure who receives email alerts when a document is uploaded.</p>
            </div>
            <div class="p-3 bg-white/10 rounded-xl border border-white/10 text-white">
                <i class="fas fa-cog text-xl animate-spin-slow"></i>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 text-sm font-semibold rounded-xl flex items-center shadow-sm">
                <i class="fas fa-check-circle mr-2.5 text-green-500 text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Settings Card -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 md:p-8 shadow-sm">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
                @csrf

                <div>
                    <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bell mr-2 text-[#ef3b45]"></i> Document Upload Notifications
                    </h3>
                    <p class="text-gray-500 text-xs mb-6">Select who should be notified when household members submit a new document.</p>

                    <div class="space-y-4">
                        <!-- Option 1: Subscribed Admins -->
                        <label class="flex items-start p-4 rounded-xl border border-gray-200 hover:bg-slate-50/50 cursor-pointer transition">
                            <input type="radio" name="document_notification_type" value="subscribed_admins" 
                                   class="mt-1 text-[#ef3b45] focus:ring-[#ef3b45] border-gray-300"
                                   {{ $type === 'subscribed_admins' ? 'checked' : '' }}
                                   onchange="toggleSections()">
                            <div class="ml-4">
                                <span class="block text-sm font-bold text-gray-800">All Subscribed Administrators</span>
                                <span class="block text-xs text-gray-400 mt-1">Send notifications to all administrators who have enabled notifications in their profiles.</span>
                            </div>
                        </label>

                        <!-- Option 2: Specific Admin -->
                        <div class="rounded-xl border border-gray-200 hover:bg-slate-50/50 transition p-4">
                            <label class="flex items-start cursor-pointer">
                                <input type="radio" name="document_notification_type" value="specific_admin" 
                                       class="mt-1 text-[#ef3b45] focus:ring-[#ef3b45] border-gray-300"
                                       {{ $type === 'specific_admin' ? 'checked' : '' }}
                                       onchange="toggleSections()">
                                <div class="ml-4">
                                    <span class="block text-sm font-bold text-gray-800">Specific Admin User</span>
                                    <span class="block text-xs text-gray-400 mt-1">Route all notifications to a single selected administrator account.</span>
                                </div>
                            </label>

                            <!-- Specific Admin Dropdown Section -->
                            <div id="admin-select-wrapper" class="mt-4 ml-8 {{ $type === 'specific_admin' ? '' : 'hidden' }}">
                                <label for="document_notification_admin_id" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase">Select Admin</label>
                                <select name="document_notification_admin_id" id="document_notification_admin_id" 
                                        class="w-full max-w-md rounded-xl border-gray-200 text-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a]/10">
                                    <option value="">-- Choose Admin --</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ $adminId == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }} ({{ $admin->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('document_notification_admin_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Option 3: Custom Email -->
                        <div class="rounded-xl border border-gray-200 hover:bg-slate-50/50 transition p-4">
                            <label class="flex items-start cursor-pointer">
                                <input type="radio" name="document_notification_type" value="custom_email" 
                                       class="mt-1 text-[#ef3b45] focus:ring-[#ef3b45] border-gray-300"
                                       {{ $type === 'custom_email' ? 'checked' : '' }}
                                       onchange="toggleSections()">
                                <div class="ml-4">
                                    <span class="block text-sm font-bold text-gray-800">Custom Email Address</span>
                                    <span class="block text-xs text-gray-400 mt-1">Route all notifications to a custom email recipient not bound to any admin account.</span>
                                </div>
                            </label>

                            <!-- Custom Email Input Section -->
                            <div id="custom-email-wrapper" class="mt-4 ml-8 {{ $type === 'custom_email' ? '' : 'hidden' }}">
                                <label for="document_notification_custom_email" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase">Recipient Email</label>
                                <input type="email" name="document_notification_custom_email" id="document_notification_custom_email" 
                                       value="{{ old('document_notification_custom_email', $customEmail) }}"
                                       placeholder="e.g. notifications@triumphres.com"
                                       class="w-full max-w-md rounded-xl border-gray-200 text-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a]/10">
                                @error('document_notification_custom_email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-[#ef3b45] hover:bg-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition duration-200 shadow-sm flex items-center focus:outline-none focus:ring-2 focus:ring-[#ef3b45] focus:ring-offset-2">
                        <i class="fas fa-save mr-2 text-sm"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toggle logic script -->
    <script>
        function toggleSections() {
            const selectedType = document.querySelector('input[name="document_notification_type"]:checked').value;
            const adminWrapper = document.getElementById('admin-select-wrapper');
            const emailWrapper = document.getElementById('custom-email-wrapper');

            if (selectedType === 'specific_admin') {
                adminWrapper.classList.remove('hidden');
                emailWrapper.classList.add('hidden');
            } else if (selectedType === 'custom_email') {
                adminWrapper.classList.add('hidden');
                emailWrapper.classList.remove('hidden');
            } else {
                adminWrapper.classList.add('hidden');
                emailWrapper.classList.add('hidden');
            }
        }
    </script>
</x-admin-layout>
