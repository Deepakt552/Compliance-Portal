<x-admin-layout>
    <div class="space-y-6 max-w-4xl mx-auto">
        <!-- Back navigation link -->
        <div>
            <a href="{{ route('household.index') }}" class="inline-flex items-center text-sm font-semibold text-[#0e1e3a] hover:text-[#ef3b45] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Household Registry
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-150">
                <h1 class="text-lg font-bold text-[#0e1e3a]">Edit Household Member</h1>
                <p class="text-xs text-gray-400 mt-1">Modify details for family member: {{ $household->firstName }} {{ $household->lastName }}.</p>
            </div>

            <form action="{{ route('household.update', $household->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Unit Number -->
                    <div>
                        <label for="UnitNo" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Unit Number</label>
                        <input type="text" name="UnitNo" id="UnitNo" value="{{ $household->UnitNo }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- User ID (Foreign reference code) -->
                    <div>
                        <label for="userId" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Representative User ID</label>
                        <input type="text" name="userId" id="userId" value="{{ $household->userId }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="firstName" class="block text-xs font-semibold text-gray-500 uppercase mb-2">First Name</label>
                        <input type="text" name="firstName" id="firstName" value="{{ $household->firstName }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="lastName" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Last Name</label>
                        <input type="text" name="lastName" id="lastName" value="{{ $household->lastName }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Adult or Minor -->
                    <div>
                        <label for="AdultOrMinor" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Age Group Classification</label>
                        <select name="AdultOrMinor" id="AdultOrMinor"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                            <option value="Adult" {{ $household->AdultOrMinor === 'Adult' ? 'selected' : '' }}>Adult</option>
                            <option value="Minor" {{ $household->AdultOrMinor === 'Minor' ? 'selected' : '' }}>Minor</option>
                        </select>
                    </div>

                    <!-- Relation -->
                    <div>
                        <label for="Relation" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Relationship to Primary User</label>
                        <input type="text" name="Relation" id="Relation" value="{{ $household->Relation }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Student Status -->
                    <div>
                        <label for="Student" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Student Status</label>
                        <input type="text" name="Student" id="Student" value="{{ $household->Student }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Age -->
                    <div>
                        <label for="Age" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Age</label>
                        <input type="number" name="Age" id="Age" value="{{ $household->Age }}" required min="1"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Family Size -->
                    <div>
                        <label for="FamilySize" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Family Size</label>
                        <input type="number" name="FamilySize" id="FamilySize" value="{{ $household->FamilySize }}" required min="1"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Certification Date -->
                    <div>
                        <label for="CertificationDate" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Certification Date</label>
                        <input type="text" name="CertificationDate" id="CertificationDate" value="{{ $household->CertificationDate }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Recertification Date -->
                    <div>
                        <label for="RecertificationDate" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Recertification Date</label>
                        <input type="text" name="RecertificationDate" id="RecertificationDate" value="{{ $household->RecertificationDate }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>

                    <!-- Property Code -->
                    <div>
                        <label for="Code" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Property Code Reference</label>
                        <input type="text" name="Code" id="Code" value="{{ $household->Code }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-gray-100 flex justify-end space-x-2">
                    <a href="{{ route('household.index') }}"
                       class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition text-center flex items-center justify-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-sm font-bold rounded-xl transition shadow-sm flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>