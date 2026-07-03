<x-admin-layout>
    <div class="space-y-6 max-w-4xl mx-auto">
        <!-- Back navigation link -->
        <div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm font-semibold text-[#0e1e3a] hover:text-[#ef3b45] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users List
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-150">
                <h1 class="text-lg font-bold text-[#0e1e3a]">Create User Account</h1>
                <p class="text-xs text-gray-400 mt-1">Register a primary head-of-household user profile.</p>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Display Name</label>
                        <input type="text" name="name" id="name" required placeholder="John Doe"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Email Address</label>
                        <input type="email" name="email" id="email" required placeholder="john.doe@example.com"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Password</label>
                        <input type="password" name="password" id="password" required placeholder="••••••••"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- User ID -->
                    <div>
                        <label for="UserId" class="block text-xs font-semibold text-gray-500 uppercase mb-2">User ID (Unique identifier)</label>
                        <input type="text" name="UserId" id="UserId" required placeholder="USR1004"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Unit Number -->
                    <div>
                        <label for="UnitNo" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Unit Number</label>
                        <input type="text" name="UnitNo" id="UnitNo" required placeholder="104B"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="FirstName" class="block text-xs font-semibold text-gray-500 uppercase mb-2">First Name</label>
                        <input type="text" name="FirstName" id="FirstName" required placeholder="John"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="LastName" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Last Name</label>
                        <input type="text" name="LastName" id="LastName" required placeholder="Doe"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Age -->
                    <div>
                        <label for="Age" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Age</label>
                        <input type="number" name="Age" id="Age" required placeholder="32" min="1"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Family Size -->
                    <div>
                        <label for="FamilySize" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Family Size</label>
                        <input type="number" name="FamilySize" id="FamilySize" required placeholder="3" min="1"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Certification Date -->
                    <div>
                        <label for="CertificationDate" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Certification Date</label>
                        <input type="date" name="CertificationDate" id="CertificationDate" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Recertification Date -->
                    <div>
                        <label for="RecertificationDate" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Recertification Date</label>
                        <input type="date" name="RecertificationDate" id="RecertificationDate" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- ChangePwd -->
                    <div>
                        <label for="ChangePwd" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Change Password Status</label>
                        <input type="text" name="ChangePwd" id="ChangePwd" placeholder="0"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Contact Details -->
                    <div>
                        <label for="ContactDetails" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Contact Details Status</label>
                        <input type="text" name="ContactDetails" id="ContactDetails" placeholder="0"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="PhoneNumber" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Phone Number</label>
                        <input type="tel" name="PhoneNumber" id="PhoneNumber" placeholder="123-456-7890"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Property Code -->
                    <div>
                        <label for="Code" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Property Code</label>
                        <input type="text" name="Code" id="Code" required placeholder="PROP10"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-sm font-bold rounded-xl transition shadow-sm flex items-center">
                        <i class="fas fa-check mr-2"></i> Register User Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>