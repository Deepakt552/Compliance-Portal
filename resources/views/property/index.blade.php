<x-admin-layout>
    <div class="space-y-6 w-full">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#0e1e3a]">Manage Properties</h1>
                <p class="text-xs text-gray-400 mt-1">Add, update, and manage property listings, codes, and unit counts.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('properties.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <i class="fas fa-plus mr-1.5"></i> Add Property
                </a>
                <a href="{{ route('property.import.form') }}"
                   class="inline-flex items-center px-4 py-2 bg-[#ef3b45] hover:bg-[#d12e37] text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <i class="fas fa-file-import mr-1.5"></i> Import Properties
                </a>
            </div>
        </div>

        <!-- Filters & Search Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-6">
            <form action="{{ route('properties.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by code, property name, or city"
                           class="pl-9 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5"
                           style="padding-left: 2.25rem;">
                </div>
                <div class="flex items-center space-x-2 w-full sm:w-auto">
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-sm font-bold rounded-xl transition shadow-sm">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('properties.index') }}"
                           class="py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition text-center flex items-center justify-center">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Properties Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            @if ($properties->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-building text-4xl text-gray-300 mb-3 block"></i>
                    No properties found matching your search.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Property Code</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Property Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Address Location</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Units</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-150">
                            @foreach ($properties as $property)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <!-- Property Code -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-[#0e1e3a]">
                                        {{ $property->Code }}
                                    </td>

                                    <!-- Property Name -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                        {{ $property->Property }}
                                    </td>

                                    <!-- Address -->
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                        <div class="font-medium">{{ $property->Address }}</div>
                                        <div class="text-gray-400 mt-0.5">{{ $property->City }}, {{ $property->State }} {{ $property->Zip }}</div>
                                    </td>

                                    <!-- Units -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-bold">
                                        {{ $property->units }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                        <a href="{{ route('properties.edit', $property->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>

                                        <form action="{{ route('properties.destroy', $property->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this property?')"
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

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-150">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
