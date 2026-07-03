<x-admin-layout>
    <div class="space-y-6 max-w-4xl mx-auto">
        <!-- Back navigation link -->
        <div>
            <a href="{{ route('admins.index') }}" class="inline-flex items-center text-sm font-semibold text-[#0e1e3a] hover:text-[#ef3b45] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Administrators List
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-sm">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-150">
                <h1 class="text-sm font-bold text-[#0e1e3a] uppercase tracking-wider">Add New Administrator</h1>
                <p class="text-xs text-gray-400 mt-1">Configure credentials for a new console administrator.</p>
            </div>

            <!-- Error List -->
            @if($errors->any())
                <div class="p-6 bg-red-50 border-b border-red-150 space-y-2">
                    <span class="text-xs font-bold text-red-800 uppercase block">Validation Errors:</span>
                    <ul class="list-disc pl-4 text-xs text-red-700 font-semibold space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admins.store') }}" method="POST" class="p-6 md:p-8 space-y-6" autocomplete="off">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Display Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="off"
                               placeholder="e.g. Deepak Tiwari"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-xs py-2.5">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="off"
                               placeholder="e.g. itdev@navkarservices.com"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-xs py-2.5">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" id="password" required autocomplete="new-password"
                               placeholder="Minimum 6 characters"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-xs py-2.5">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-gray-100 flex justify-end space-x-2">
                    <a href="{{ route('admins.index') }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition text-center flex items-center justify-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-[#0e1e3a] hover:bg-[#162f59] text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center">
                        <i class="fas fa-plus mr-1.5"></i> Create Administrator
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
