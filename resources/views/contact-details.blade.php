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

            @if(session('error'))
                <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-sm font-semibold text-red-800 flex items-center space-x-3 shadow-sm">
                    <i class="fas fa-exclamation-circle text-lg text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-150">
                    <h1 class="text-lg font-bold text-[#0e1e3a]">Contact Details</h1>
                    <p class="text-xs text-gray-400 mt-1">Keep your primary contact methods up to date for certification alerts.</p>
                </div>

                <form method="POST" action="{{ route('contact.details.update') }}" class="p-6 space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('email')
                            <p class="text-[#ef3b45] text-xs mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number Input -->
                    <div>
                        <label for="PhoneNumber" class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Phone Number</label>
                        <input type="text" name="PhoneNumber" id="PhoneNumber" value="{{ old('PhoneNumber', auth()->user()->PhoneNumber) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('PhoneNumber')
                            <p class="text-[#ef3b45] text-xs mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full justify-center px-6 py-3 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-sm font-bold rounded-xl transition shadow-sm flex items-center">
                            <i class="fas fa-save mr-2"></i> Update Contact Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>