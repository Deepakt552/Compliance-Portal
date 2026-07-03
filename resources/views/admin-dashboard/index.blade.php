<x-admin-layout>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-6 w-full">
        <!-- Welcome Command Center Header -->
        <div class="rounded-2xl bg-gradient-to-r from-[#0e1e3a] via-[#172f58] to-[#0e1e3a] text-white p-6 md:p-8 shadow-sm border border-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 h-64 w-64 rounded-full bg-[#ef3b45]/10 blur-3xl pointer-events-none"></div>
            <div>
                <span class="text-[10px] uppercase tracking-widest text-[#ef3b45] font-extrabold">Management Control Room</span>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-1">Compliance Overview</h1>
                <p class="text-slate-300 text-xs mt-1">Monitor verification queues, properties listing records, and documents audit status.</p>
            </div>
            <div class="bg-white/10 px-4 py-2.5 rounded-xl border border-white/10 text-xs font-semibold text-gray-200">
                <i class="far fa-clock mr-1.5 text-[#ef3b45]"></i> Live Monitoring
            </div>
        </div>

        <!-- 1. Stats and Analytics Summary Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Properties Card -->
            <div class="bg-white rounded-2xl border border-gray-200/80 border-l-4 border-l-blue-500 p-5 flex items-center space-x-4 transition-all duration-200 hover:shadow-md hover:border-r hover:border-r-blue-500/10">
                <div class="p-3.5 rounded-xl bg-blue-50 text-blue-700 border border-blue-100">
                    <i class="fas fa-building text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider">Total Properties</p>
                    <p class="text-2xl font-extrabold text-gray-800 mt-0.5">{{ $metrics['total_properties'] }}</p>
                </div>
            </div>

            <!-- Primary Users Card -->
            <div class="bg-white rounded-2xl border border-gray-200/80 border-l-4 border-l-indigo-500 p-5 flex items-center space-x-4 transition-all duration-200 hover:shadow-md hover:border-r hover:border-r-indigo-500/10">
                <div class="p-3.5 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-100">
                    <i class="fas fa-user-tie text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider">Primary Users</p>
                    <p class="text-2xl font-extrabold text-gray-800 mt-0.5">{{ $metrics['total_users'] }}</p>
                </div>
            </div>

            <!-- Total Members Card -->
            <div class="bg-white rounded-2xl border border-gray-200/80 border-l-4 border-l-purple-500 p-5 flex items-center space-x-4 transition-all duration-200 hover:shadow-md hover:border-r hover:border-r-purple-500/10">
                <div class="p-3.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-100">
                    <i class="fas fa-users text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider">Household Members</p>
                    <p class="text-2xl font-extrabold text-gray-800 mt-0.5">{{ $metrics['total_households'] }}</p>
                </div>
            </div>

            <!-- Total Uploads Card -->
            <div class="bg-white rounded-2xl border border-gray-200/80 border-l-4 border-l-[#ef3b45] p-5 flex items-center space-x-4 transition-all duration-200 hover:shadow-md hover:border-r hover:border-r-[#ef3b45]/10">
                <div class="p-3.5 rounded-xl bg-red-50 text-[#ef3b45] border border-red-100">
                    <i class="fas fa-file-invoice text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider">Total Uploads</p>
                    <p class="text-2xl font-extrabold text-gray-800 mt-0.5">{{ $metrics['total_documents'] }}</p>
                </div>
            </div>
        </div>

        <!-- 2. Compliance breakdown progress and visual analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Compliance Progress Widget with Doughnut Chart -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 lg:col-span-2 flex flex-col justify-between">
                <div class="border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-xs font-bold text-[#0e1e3a] uppercase tracking-wider">Document Verification Breakdown</h3>
                </div>
                
                @php
                    $totalDocs = max(1, $metrics['total_documents']);
                    $approvedPct = round(($metrics['approved_documents'] / $totalDocs) * 100);
                    $pendingPct = round(($metrics['pending_documents'] / $totalDocs) * 100);
                    $rejectedPct = round(($metrics['rejected_documents'] / $totalDocs) * 100);
                @endphp

                <div class="flex flex-col sm:flex-row items-center gap-8 py-2">
                    <!-- Doughnut Canvas with Centered Text Indicator -->
                    <div class="relative w-36 h-36 flex-shrink-0">
                        <canvas id="complianceChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none select-none">
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Total</span>
                            <span class="text-xl font-extrabold text-[#0e1e3a] leading-none mt-0.5">{{ $metrics['total_documents'] }}</span>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Files</span>
                        </div>
                    </div>

                    <!-- Legend & Detailed Stats Grid -->
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3 w-full">
                        <!-- Approved Card -->
                        <div class="p-4 bg-green-50/40 border border-green-100 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-green-700 uppercase tracking-wider">Approved</span>
                                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                            </div>
                            <p class="text-2xl font-extrabold text-green-900">{{ $metrics['approved_documents'] }}</p>
                            <!-- Mini progress bar -->
                            <div class="w-full bg-green-100 h-1 rounded-full overflow-hidden">
                                <div class="bg-green-500 h-1" style="width: {{ $approvedPct }}%"></div>
                            </div>
                            <span class="text-[9px] text-green-600 font-bold block">{{ $approvedPct }}% of total</span>
                        </div>

                        <!-- Pending Card -->
                        <div class="p-4 bg-yellow-50/40 border border-yellow-100 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-yellow-700 uppercase tracking-wider">Pending</span>
                                <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
                            </div>
                            <p class="text-2xl font-extrabold text-yellow-900">{{ $metrics['pending_documents'] }}</p>
                            <!-- Mini progress bar -->
                            <div class="w-full bg-yellow-100 h-1 rounded-full overflow-hidden">
                                <div class="bg-yellow-500 h-1" style="width: {{ $pendingPct }}%"></div>
                            </div>
                            <span class="text-[9px] text-yellow-600 font-bold block">{{ $pendingPct }}% of total</span>
                        </div>

                        <!-- Rejected Card -->
                        <div class="p-4 bg-red-50/40 border border-red-100 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-red-700 uppercase tracking-wider">Rejected</span>
                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            </div>
                            <p class="text-2xl font-extrabold text-red-900">{{ $metrics['rejected_documents'] }}</p>
                            <!-- Mini progress bar -->
                            <div class="w-full bg-red-100 h-1 rounded-full overflow-hidden">
                                <div class="bg-red-500 h-1" style="width: {{ $rejectedPct }}%"></div>
                            </div>
                            <span class="text-[9px] text-red-600 font-bold block">{{ $rejectedPct }}% of total</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Import Links -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-6 flex flex-col justify-between">
                <div class="border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-xs font-bold text-[#0e1e3a] uppercase tracking-wider">Excel Batch Importer</h3>
                </div>
                <div class="grid grid-cols-1 gap-2.5">
                    <a href="{{ route('import-form') }}" class="flex items-center justify-between p-3.5 rounded-xl border border-gray-150 hover:border-[#ef3b45] hover:bg-red-50/5 text-xs font-bold text-gray-700 transition-all duration-200">
                        <span><i class="fas fa-file-excel text-green-600 mr-2"></i> Import User Profiles</span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                    <a href="{{ route('household.import.form') }}" class="flex items-center justify-between p-3.5 rounded-xl border border-gray-150 hover:border-[#ef3b45] hover:bg-red-50/5 text-xs font-bold text-gray-700 transition-all duration-200">
                        <span><i class="fas fa-file-excel text-green-600 mr-2"></i> Import Family Members</span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                    <a href="{{ route('property.import.form') }}" class="flex items-center justify-between p-3.5 rounded-xl border border-gray-150 hover:border-[#ef3b45] hover:bg-red-50/5 text-xs font-bold text-gray-700 transition-all duration-200">
                        <span><i class="fas fa-file-csv text-blue-600 mr-2"></i> Import Properties</span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 3. Filters Form Card -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6">
            <div class="border-b border-gray-100 pb-3 mb-4 flex items-center">
                <h3 class="text-xs font-bold text-[#0e1e3a] uppercase tracking-wider"><i class="fas fa-filter mr-1.5 text-gray-400"></i>Filter Household List</h3>
            </div>
            
            <form method="get" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                        @foreach($unitNos as $unitNo)
                            <option value="{{ $unitNo }}" @if(request('unitNo') == $unitNo) selected @endif>
                                {{ $unitNo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Form Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit"
                            class="flex-1 justify-center py-2.5 px-4 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-xs font-bold rounded-xl transition duration-200 shadow-sm flex items-center">
                        <i class="fas fa-search mr-1.5 text-xs"></i> Filter Results
                    </button>
                    @if(request('code') || request('unitNo'))
                        <a href="{{ route('admin.dashboard') }}"
                           class="py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition duration-200 text-center flex items-center justify-center">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 4. Data Listing (Household / Users) -->
        <div class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-sm">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-150 flex items-center justify-between">
                <h3 class="text-xs font-bold text-[#0e1e3a] uppercase tracking-wider">Household Members Registry</h3>
                <span class="text-[10px] bg-slate-200 text-slate-800 font-extrabold px-2.5 py-1 rounded-full">{{ $users->total() }} Records</span>
            </div>

            @if($users->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-3 block"></i>
                    No household records found matching the active filters.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Family Member</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Property & Unit</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Compliance Status</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-150">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                                    <!-- Avatar & Name -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-full bg-blue-50 text-[#0e1e3a] flex items-center justify-center font-bold text-xs uppercase border border-blue-100">
                                                {{ strtoupper(substr($user->firstName, 0, 1) . substr($user->lastName, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $user->firstName }} {{ $user->lastName }}</div>
                                                <div class="text-[10px] text-gray-400 font-semibold mt-0.5">HoH Representative ID: {{ $user->userId ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Property Code & Unit -->
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        <div class="text-sm font-bold text-gray-800">{{ $propertyNames[$user->Code] ?? 'Code: ' . $user->Code }}</div>
                                        <div class="text-gray-400 mt-0.5">Unit Number: <span class="font-bold text-[#0e1e3a]">{{ $user->UnitNo }}</span></div>
                                    </td>

                                    <!-- Direct Document Verification Counts -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $memberDocs = \App\Models\Document::where('family_member_id', $user->id)->get();
                                            $totalCount = $memberDocs->count();
                                            $approvedCount = $memberDocs->where('status', 'verified')->count();
                                            $pendingCount = $memberDocs->where('status', 'pending')->count();
                                            $rejectedCount = $memberDocs->where('status', 'rejected')->count();
                                        @endphp
                                        @if($totalCount > 0)
                                            <div class="flex items-center space-x-2">
                                                <div class="flex items-center space-x-1.5">
                                                    @if($approvedCount > 0)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-green-50 text-green-700 border border-green-200" title="Approved Documents">
                                                            <i class="fas fa-check-circle mr-1 text-[8px]"></i> {{ $approvedCount }} Approved
                                                        </span>
                                                    @endif
                                                    @if($pendingCount > 0)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-250 animate-pulse" title="Pending Action">
                                                            <i class="fas fa-hourglass-half mr-1 text-[8px]"></i> {{ $pendingCount }} Pending
                                                        </span>
                                                    @endif
                                                    @if($rejectedCount > 0)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-red-50 text-red-700 border border-red-200" title="Rejected Documents">
                                                            <i class="fas fa-times-circle mr-1 text-[8px]"></i> {{ $rejectedCount }} Rejected
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic font-medium"><i class="fas fa-exclamation-circle mr-1"></i>No uploads yet</span>
                                        @endif
                                    </td>

                                    <!-- Action Links -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('admin.showUserDocuments', ['family_member_id' => $user->id]) }}" 
                                           class="inline-flex items-center px-3.5 py-1.5 border border-[#ef3b45] text-[10px] font-extrabold text-[#ef3b45] hover:bg-[#ef3b45] hover:text-white rounded-xl transition duration-150 uppercase tracking-wider">
                                            <i class="fas fa-folder-open mr-1.5"></i> Verify Documents
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Custom Styled Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-150">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Script to dynamically cascade property unit numbers selection -->
    <script>
    function updateUnitNumbers() {
        var selectedCode = document.getElementById('code').value;
        var unitNoSelect = document.getElementById('unitNo');
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

    // Initialize chart and units dropdown dynamically on page load based on current selections
    window.onload = function() {
        updateUnitNumbers();

        // Render Chart.js Doughnut Chart
        const ctx = document.getElementById('complianceChart').getContext('2d');
        const approved = parseInt("{{ $metrics['approved_documents'] }}") || 0;
        const pending = parseInt("{{ $metrics['pending_documents'] }}") || 0;
        const rejected = parseInt("{{ $metrics['rejected_documents'] }}") || 0;

        // Fallback placeholder if no documents uploaded
        const total = approved + pending + rejected;
        const chartData = total > 0 ? [approved, pending, rejected] : [0, 0, 1];
        const chartColors = total > 0 ? ['#10B981', '#F59E0B', '#EF4444'] : ['#E2E8F0'];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Rejected'],
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '76%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: total > 0
                    }
                }
            }
        });
    }
    </script>
</x-admin-layout>