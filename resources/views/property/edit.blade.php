<x-admin-layout>
    <div class="space-y-6 max-w-4xl mx-auto">
        <!-- Back navigation link -->
        <div>
            <a href="{{ route('properties.index') }}" class="inline-flex items-center text-sm font-semibold text-[#0e1e3a] hover:text-[#ef3b45] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Properties List
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-150">
                <h1 class="text-lg font-bold text-[#0e1e3a]">Edit Property Details</h1>
                <p class="text-xs text-gray-400 mt-1">Modify information for property: {{ $property->Property }}.</p>
            </div>

            <form action="{{ route('properties.update', $property->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Property Code -->
                    <div>
                        <label for="Code" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Property Code (Unique)</label>
                        <input type="text" name="Code" id="Code" value="{{ old('Code', $property->Code) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('Code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Property Name -->
                    <div>
                        <label for="Property" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Property Name</label>
                        <input type="text" name="Property" id="Property" value="{{ old('Property', $property->Property) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('Property')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="Address" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Street Address</label>
                        <input type="text" name="Address" id="Address" value="{{ old('Address', $property->Address) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('Address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label for="City" class="block text-xs font-semibold text-gray-500 uppercase mb-2">City</label>
                        <input type="text" name="City" id="City" value="{{ old('City', $property->City) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('City')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- State -->
                    <div>
                        <label for="State" class="block text-xs font-semibold text-gray-500 uppercase mb-2">State</label>
                        <input type="text" name="State" id="State" value="{{ old('State', $property->State) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('State')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Zip Code -->
                    <div>
                        <label for="Zip" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Zip Code</label>
                        <input type="text" name="Zip" id="Zip" value="{{ old('Zip', $property->Zip) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('Zip')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Units -->
                    <div>
                        <label for="units" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Total Units</label>
                        <input type="number" name="units" id="units" value="{{ old('units', $property->units) }}" required min="1"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        @error('units')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-gray-100 flex justify-end space-x-2">
                    <a href="{{ route('properties.index') }}"
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
