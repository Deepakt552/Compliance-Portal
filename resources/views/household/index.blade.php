<x-admin-layout>
    <div class="space-y-6 w-full">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#0e1e3a]">Manage Household Data</h1>
                <p class="text-xs text-gray-400 mt-1">Register and oversee household members, relations, and age profiles.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" id="bulk-delete-btn"
                        class="hidden inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <i class="fas fa-trash-alt mr-1.5"></i> Delete Selected (<span id="selected-count">0</span>)
                </button>
                <a href="{{ route('household.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <i class="fas fa-plus mr-1.5"></i> Add Household
                </a>
                <a href="{{ route('household.import.form') }}"
                   class="inline-flex items-center px-4 py-2 bg-[#ef3b45] hover:bg-[#d12e37] text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <i class="fas fa-file-import mr-1.5"></i> Import Household
                </a>
            </div>
        </div>

        <!-- Filters & Search Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-6">
            <form action="{{ route('household.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Text Search -->
                    <div>
                        <label for="search" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Search Query</label>
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name, code, or unit..."
                                   class="pl-9 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-xs py-2.5"
                                   style="padding-left: 2.25rem;">
                        </div>
                    </div>

                    <!-- Property Filter -->
                    <div>
                        <label for="code" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Property Name</label>
                        <select name="code" id="code" onchange="updateUnitNumbers()"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-xs py-2.5">
                            <option value="">All Properties</option>
                            @foreach($codes as $code)
                                @if(isset($propertyNames[$code]))
                                    <option value="{{ $code }}" @if(request('code') == $code) selected @endif>
                                        {{ $propertyNames[$code] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- UnitNo Filter -->
                    <div>
                        <label for="unitNo" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Unit Number</label>
                        <select name="unitNo" id="unitNo"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-xs py-2.5">
                            <option value="">All Units</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 pt-2">
                    <span class="text-[11px] text-gray-400 font-medium">
                        @if($householdData->total() > 0)
                            Showing {{ $householdData->firstItem() }}-{{ $householdData->lastItem() }} of {{ $householdData->total() }} records
                        @endif
                    </span>
                    <div class="flex items-center space-x-2">
                        <button type="submit"
                                class="px-6 py-2.5 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center">
                            <i class="fas fa-filter mr-1.5 text-xs"></i> Apply Filters
                        </button>
                        @if(request('search') || request('code') || request('unitNo'))
                            <a href="{{ route('household.index') }}"
                               class="py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition text-center flex items-center justify-center">
                                <i class="fas fa-undo mr-1.5"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Household Members Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            @if ($householdData->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-home text-4xl text-gray-300 mb-3 block"></i>
                    No household members registered.
                </div>
            @else
                <form id="bulk-delete-household-form" action="{{ route('household.bulkDestroy') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">
                                        <input type="checkbox" id="select-all-household" class="rounded border-gray-300 text-[#0e1e3a] focus:ring-[#0e1e3a] cursor-pointer">
                                    </th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Family Member Name</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Representative ID</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Property & Unit</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Age Class</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-150">
                                @foreach ($householdData as $data)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <!-- Checkbox -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="ids[]" value="{{ $data->id }}" form="bulk-delete-household-form" class="household-checkbox rounded border-gray-300 text-[#0e1e3a] focus:ring-[#0e1e3a] cursor-pointer">
                                        </td>

                                        <!-- Name and Icon -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-9 w-9 rounded-full bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-xs uppercase">
                                                    {{ strtoupper(substr($data->firstName ?? 'M', 0, 1) . substr($data->lastName ?? 'B', 0, 1)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-bold text-gray-900">{{ $data->firstName }} {{ $data->lastName }}</div>
                                                    <span class="text-[9px] bg-indigo-50 text-indigo-700 font-bold px-1.5 py-0.5 rounded border border-indigo-100 uppercase tracking-wide inline-block mt-0.5">{{ $data->Relation }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Representative User ID -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-semibold">
                                            {{ $data->userId }}
                                        </td>

                                        <!-- Property Code & Unit -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <div class="font-bold text-gray-800">Unit {{ $data->UnitNo }}</div>
                                            <div class="text-xs text-gray-400">Code: {{ $data->Code }}</div>
                                        </td>

                                        <!-- Age Group -->
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-600">
                                            @php
                                                $ageVal = $data->dob ? \Carbon\Carbon::parse($data->dob)->age : $data->Age;
                                                $ageStatus = $ageVal < 18 ? 'Minor' : 'Adult';
                                            @endphp
                                            <span>{{ $ageVal }} yrs ({{ $ageStatus }})</span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                            <a href="{{ route('household.edit', $data->id) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>

                                            <form action="{{ route('household.destroy', $data->id) }}" method="POST" class="inline delete-household-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
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
                        {{ $householdData->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

<script>
function updateUnitNumbers() {
    var codeEl = document.getElementById('code');
    var unitNoSelect = document.getElementById('unitNo');
    if (!codeEl || !unitNoSelect) return;

    var selectedCode = codeEl.value;
    var unitNumbers = @json($unitNumbers); 

    // Clear existing options
    unitNoSelect.innerHTML = '';

    // Add 'All' option
    var allOption = document.createElement('option');
    allOption.value = '';
    allOption.text = 'All Units';
    unitNoSelect.add(allOption);

    // Add options based on the selected code
    if (selectedCode && unitNumbers[selectedCode]) {
        unitNumbers[selectedCode].forEach(function(unitNo) {
            var option = document.createElement('option');
            option.value = unitNo;
            option.text = unitNo;
            if ("{{ request('unitNo') }}" == unitNo) {
                option.selected = true;
            }
            unitNoSelect.add(option);
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Initial call to load active selection on page load once DOM is ready
    updateUnitNumbers();

    const selectAll = document.getElementById('select-all-household');
    const checkboxes = document.querySelectorAll('.household-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

    // Hook bulk delete button to layout modal
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function () {
            const count = document.querySelectorAll('.household-checkbox:checked').length;
            window.confirmDeleteModal.setForm(document.getElementById('bulk-delete-household-form'));
            window.confirmDeleteModal.show(`Are you sure you want to delete the ${count} selected household records?`);
        });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (!this.checked && selectAll) {
                selectAll.checked = false;
            }
            toggleBulkButton();
        });
    });

    function toggleBulkButton() {
        const checkedCount = document.querySelectorAll('.household-checkbox:checked').length;
        const selectedCountSpan = document.getElementById('selected-count');
        if (selectedCountSpan) {
            selectedCountSpan.textContent = checkedCount;
        }
        if (bulkDeleteBtn) {
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('hidden');
            } else {
                bulkDeleteBtn.classList.add('hidden');
            }
        }
    }
});
</script>