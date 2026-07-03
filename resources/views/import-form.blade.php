<x-admin-layout>
    <!-- SheetJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <div class="max-w-3xl mx-auto px-4 py-8 space-y-6">
        <!-- Back navigation link -->
        <div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm font-semibold text-[#0e1e3a] hover:text-[#ef3b45] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users List
            </a>
        </div>

        <!-- Alert notifications -->
        <div id="alert-box" class="hidden"></div>

        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            <!-- Header banner -->
            <div class="bg-[#0e1e3a] px-6 py-5 border-b border-gray-900 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-base font-bold">Import User Accounts (Live Tracker)</h2>
                    <p class="text-xs text-blue-200 mt-1">Upload Excel/CSV and watch records process in real-time.</p>
                </div>
                <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-gray-300">
                    <i class="fas fa-microchip text-xl"></i>
                </div>
            </div>
            
            <div class="p-6 md:p-8 space-y-6">
                <!-- Step 1: Drag & Drop Zone -->
                <div id="upload-step" class="space-y-4">
                    <div class="relative border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center transition hover:border-[#ef3b45] hover:bg-red-50/5 group cursor-pointer">
                        <input type="file" id="file" accept=".xlsx, .xls, .csv" required
                               class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">
                        <div class="space-y-3">
                            <div class="h-12 w-12 rounded-full bg-red-50 text-[#ef3b45] flex items-center justify-center mx-auto transition-transform group-hover:scale-105 duration-250">
                                <i class="fas fa-cloud-upload-alt text-xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-700">
                                <span class="text-[#ef3b45] hover:underline">Select Excel or CSV file</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-400">
                                XLSX, XLS, CSV (Max 10MB)
                            </p>
                        </div>
                    </div>

                    <!-- File Name Indicator -->
                    <div id="file-name" class="mt-3 hidden bg-gray-50 border border-gray-150 rounded-xl p-3 flex items-center justify-between text-xs text-gray-700 font-semibold">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-file-excel text-green-600 text-sm"></i>
                            <span id="file-name-text"></span>
                        </div>
                        <span id="row-count-badge" class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-full border border-blue-100">0 rows found</span>
                    </div>

                    <!-- Action Trigger -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-100">
                        <button type="button" id="start-btn" disabled
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gray-300 text-gray-500 font-bold rounded-xl transition shadow-sm cursor-not-allowed">
                            <i class="fas fa-play mr-2"></i>  Import user
                        </button>
                        
                        <a href="{{ asset('User Credentials March.xlsx') }}" download
                           class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 border border-gray-200 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition">
                            <i class="fas fa-download mr-2 text-gray-400"></i> Download Template File
                        </a>
                    </div>
                </div>

                <!-- Step 2: Live Processing Tracker -->
                <div id="progress-step" class="hidden space-y-5">
                    <!-- Progress Metrics Header -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-3 text-center">
                            <span class="block text-[10px] text-gray-400 font-bold uppercase mb-0.5">Total Rows</span>
                            <span id="total-rows-metric" class="text-lg font-extrabold text-gray-800">0</span>
                        </div>
                        <div class="bg-green-50 border border-green-100 rounded-xl p-3 text-center">
                            <span class="block text-[10px] text-green-600 font-bold uppercase mb-0.5">Success</span>
                            <span id="success-rows-metric" class="text-lg font-extrabold text-green-800">0</span>
                        </div>
                        <div class="bg-red-50 border border-red-100 rounded-xl p-3 text-center">
                            <span class="block text-[10px] text-red-600 font-bold uppercase mb-0.5">Failed</span>
                            <span id="failed-rows-metric" class="text-lg font-extrabold text-red-800">0</span>
                        </div>
                    </div>

                    <!-- Progress Bar Component -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-semibold text-gray-500">
                            <span id="progress-status-text">Ready to start...</span>
                            <span id="progress-percent" class="font-bold text-[#0e1e3a]">0%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3.5 overflow-hidden border border-gray-200/50">
                            <div id="progress-bar-fill" class="bg-[#ef3b45] h-3.5 transition-all duration-200" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Realtime logs console -->
                    <div class="space-y-2">
                        <span class="block text-xs font-bold text-[#0e1e3a] uppercase tracking-wide">Live Import Log Feed</span>
                        <div id="log-console" class="h-60 overflow-y-auto border border-gray-200 rounded-xl bg-gray-900 text-green-400 font-mono text-[10px] p-4 space-y-1.5">
                            <p class="text-gray-400">// Log console initialized. Ready for import...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guidelines Card -->
        <div class="bg-blue-50 border border-blue-150 rounded-2xl p-6 space-y-3">
            <h3 class="text-sm font-bold text-blue-800 flex items-center">
                <i class="fas fa-info-circle mr-2 text-base"></i> Spreadsheet Upload Guidelines
            </h3>
            <ul class="space-y-2 text-xs text-blue-700 font-medium">
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-500 flex-shrink-0"></i>
                    <span>Verify spreadsheet headers match the template sheet (First Name, Last Name, Email, Code, Unit Number, User ID, Password).</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-500 flex-shrink-0"></i>
                    <span>Duplicate Email addresses or User IDs will log errors and be skipped, allowing other valid entries to continue.</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Live script controller -->
    <script>
        let parsedRows = [];
        const fileInput = document.getElementById('file');
        const fileNameDiv = document.getElementById('file-name');
        const fileNameText = document.getElementById('file-name-text');
        const rowCountBadge = document.getElementById('row-count-badge');
        const startBtn = document.getElementById('start-btn');

        // File selection handling
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length === 0) return;

            const file = e.target.files[0];
            fileNameText.innerText = file.name;
            fileNameDiv.classList.remove('hidden');

            // Parse file with SheetJS
            const reader = new FileReader();
            reader.onload = function(evt) {
                try {
                    const data = evt.target.result;
                    const workbook = XLSX.read(data, { type: 'binary' });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    
                    // Convert sheets data to json
                    parsedRows = XLSX.utils.sheet_to_json(worksheet, { defval: "" });

                    if (parsedRows.length > 0) {
                        rowCountBadge.innerText = `${parsedRows.length} rows found`;
                        startBtn.disabled = false;
                        startBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                        startBtn.classList.add('bg-[#ef3b45]', 'hover:bg-[#d12e37]', 'text-white');
                    } else {
                        rowCountBadge.innerText = `Empty sheet`;
                        alert('Spreadsheet has no rows.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error reading file. Please upload a valid Excel or CSV.');
                }
            };
            reader.readAsBinaryString(file);
        });

        // Progressive loop runner
        startBtn.addEventListener('click', async function() {
            if (parsedRows.length === 0) return;

            // Shift states to step 2 view
            document.getElementById('upload-step').classList.add('hidden');
            document.getElementById('progress-step').classList.remove('hidden');

            const totalRows = parsedRows.length;
            document.getElementById('total-rows-metric').innerText = totalRows;

            const logConsole = document.getElementById('log-console');
            const fill = document.getElementById('progress-bar-fill');
            const percent = document.getElementById('progress-percent');
            const successMetric = document.getElementById('success-rows-metric');
            const failedMetric = document.getElementById('failed-rows-metric');
            const statusText = document.getElementById('progress-status-text');

            logConsole.innerHTML = `<p class="text-gray-400">// Starting progressive user import of ${totalRows} entries...</p>`;

            let successCount = 0;
            let failedCount = 0;

            for (let i = 0; i < totalRows; i++) {
                const row = parsedRows[i];
                const displayName = `${row.FirstName || row.firstname || ''} ${row.LastName || row.lastname || ''}`.trim() || `Row ${i + 1}`;
                
                statusText.innerText = `Importing: ${displayName}...`;

                try {
                    const response = await fetch('{{ route("import.row") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ row: row })
                    });

                    const resData = await response.json();

                    if (response.ok && resData.success) {
                        successCount++;
                        successMetric.innerText = successCount;
                        logConsole.innerHTML += `<p class="text-green-400"><i class="fas fa-check-circle mr-1"></i> [SUCCESS] [${i+1}/${totalRows}] ${resData.message}</p>`;
                    } else {
                        failedCount++;
                        failedMetric.innerText = failedCount;
                        const errorMsg = resData.error || 'Unknown server error.';
                        logConsole.innerHTML += `<p class="text-red-400"><i class="fas fa-times-circle mr-1"></i> [FAILED] [${i+1}/${totalRows}] ${displayName}: ${errorMsg}</p>`;
                    }
                } catch (err) {
                    failedCount++;
                    failedMetric.innerText = failedCount;
                    logConsole.innerHTML += `<p class="text-red-400"><i class="fas fa-exclamation-triangle mr-1"></i> [ERROR] [${i+1}/${totalRows}] Network or server failure importing ${displayName}.</p>`;
                }

                // Update progress components
                const processed = i + 1;
                const percentage = Math.round((processed / totalRows) * 100);
                fill.style.width = `${percentage}%`;
                percent.innerText = `${percentage}%`;

                // Scroll console to bottom
                logConsole.scrollTop = logConsole.scrollHeight;
            }

            // Summary report
            statusText.innerText = `Import complete!`;
            logConsole.innerHTML += `<p class="text-[#ef3b45] font-bold mt-3"><i class="fas fa-flag-checkered mr-1"></i> // Import completed: ${successCount} successfully imported, ${failedCount} failures.</p>`;
            
            // Show alert box
            const alertBox = document.getElementById('alert-box');
            alertBox.className = `rounded-2xl p-4 border text-sm font-semibold flex items-center space-x-3 shadow-sm ${failedCount > 0 ? 'bg-yellow-50 text-yellow-800 border-yellow-200' : 'bg-green-50 text-green-800 border-green-200'}`;
            alertBox.innerHTML = `
                <i class="fas ${failedCount > 0 ? 'fa-exclamation-triangle text-yellow-500' : 'fa-check-circle text-green-500'} text-lg"></i>
                <span>Import completed! ${successCount} accounts registered successfully. ${failedCount > 0 ? failedCount + ' entries failed (see log for details).' : ''}</span>
            `;
            alertBox.classList.remove('hidden');
        });
    </script>
</x-admin-layout>
