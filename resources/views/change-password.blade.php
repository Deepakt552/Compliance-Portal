<x-app-layout>
    <div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 flex items-center justify-center">
        <div class="w-full max-w-md space-y-6">
            <!-- Back navigation link -->
            <div>
                <a href="{{ route('user.dashboard') }}" class="inline-flex items-center text-sm font-semibold text-[#0e1e3a] hover:text-[#ef3b45] transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>

            <!-- Notification Alerts -->
            @if(session('success'))
                <div class="rounded-2xl bg-green-50 p-4 border border-green-200 text-sm font-semibold text-green-800 flex items-center space-x-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg text-green-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-150">
                    <h1 class="text-lg font-bold text-[#0e1e3a]">Change Password</h1>
                    <p class="text-xs text-gray-400 mt-1">Ensure your account security by updating your login credentials.</p>
                </div>

                <form method="POST" action="{{ route('change.password.update') }}" class="p-6 space-y-5">
                    @csrf

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required placeholder="••••••••"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('current_password')
                            <p class="text-[#ef3b45] text-xs mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">New Password</label>
                        <input type="password" name="new_password" id="new_password" required placeholder="••••••••"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('new_password')
                            <p class="text-[#ef3b45] text-xs mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required placeholder="••••••••"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full justify-center px-6 py-3 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-sm font-bold rounded-xl transition shadow-sm flex items-center">
                            <i class="fas fa-key mr-2"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>