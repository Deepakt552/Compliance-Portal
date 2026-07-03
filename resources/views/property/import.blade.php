<x-admin-layout>
    <!-- SheetJS Excel Parser Library -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
        <!-- Back Navigation Link -->
        <div>
            <a href="{{ route('properties.index') }}" class="inline-flex items-center text-sm font-semibold text-[#0e1e3a] hover:text-[#ef3b45] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Properties List
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-sm">
            <!-- Header banner -->
            <div class="bg-[#0e1e3a] px-6 py-5 border-b border-gray-900 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider">Import Property Listings</h2>
                    <p class="text-xs text-blue-200 mt-1">Upload code-indexed property list data via Excel spreadsheets.</p>
                </div>
                <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-gray-300">
                    <i class="fas fa-building text-xl"></i>
                </div>
            </div>
            
            <div class="p-6 md:p-8 space-y-6">
                <!-- Dropzone Area -->
                <div>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center transition hover:border-[#ef3b45] hover:bg-red-50/5 group cursor-pointer">
                        <input type="file" id="file" accept=".xlsx, .xls" required
                               class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">
                        <div class="space-y-3">
                            <div class="h-12 w-12 rounded-full bg-red-50 text-[#ef3b45] flex items-center justify-center mx-auto transition-transform group-hover:scale-105 duration-250">
                                <i class="fas fa-cloud-upload-alt text-xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-700">
                                <span class="text-[#ef3b45] hover:underline">Click to upload spreadsheet</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-400">
                                Supported formats: XLSX, XLS (Max 10MB)
                            </p>
                        </div>
                    </div>

                    <!-- File Name Indicator -->
                    <div id="file-name" class="mt-3 hidden bg-gray-50 border border-gray-150 rounded-xl p-3 flex items-center justify-between text-xs text-gray-700 font-semibold animate-fade-in">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-file-csv text-blue-600 text-sm"></i>
                            <span id="file-name-text"></span>
                        </div>
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                </div>

                <!-- Live Progressive Tracker Panel (Hidden initially) -->
                <div id="progress-panel" class="hidden space-y-6 pt-4 border-t border-gray-100">
                    <!-- Progress Stats Counter -->
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="p-4 bg-slate-50 border border-gray-100 rounded-2xl">
                            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Rows</span>
                            <span id="total-rows-metric" class="text-lg font-extrabold text-gray-800">0</span>
                        </div>
                        <div class="p-4 bg-green-50/30 border border-green-100 rounded-2xl">
                            <span class="block text-[10px] text-green-600 font-bold uppercase tracking-wider">Imported</span>
                            <span id="imported-rows-metric" class="text-lg font-extrabold text-green-800">0</span>
                        </div>
                        <div class="p-4 bg-red-50/30 border border-red-100 rounded-2xl">
                            <span class="block text-[10px] text-red-600 font-bold uppercase tracking-wider">Failed</span>
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
                
                <!-- Action Buttons -->
                <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <button type="button" id="start-btn" disabled
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gray-300 text-gray-500 font-bold rounded-xl transition shadow-sm cursor-not-allowed">
                        <i class="fas fa-play mr-2"></i> Start Properties Import
                    </button>
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
                    <span>Required Columns: Code, Property, Address, City, Zip, State, units.</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Script to parse and progressively import properties -->
    <script>
        let parsedRows = [];
        const fileInput = document.getElementById('file');
        const fileNameElement = document.getElementById('file-name');
        const fileNameText = document.getElementById('file-name-text');
        const startBtn = document.getElementById('start-btn');
        const progressPanel = document.getElementById('progress-panel');
        const logConsole = document.getElementById('log-console');

        // Parse Excel Spreadsheet via SheetJS on file select
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length === 0) {
                fileNameElement.classList.add('hidden');
                startBtn.disabled = true;
                startBtn.className = "w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gray-300 text-gray-500 font-bold rounded-xl transition shadow-sm cursor-not-allowed";
                return;
            }

            const file = e.target.files[0];
            fileNameText.textContent = file.name;
            fileNameElement.classList.remove('hidden');

            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const json = XLSX.utils.sheet_to_json(worksheet);

                    if (json.length === 0) {
                        alert('The uploaded spreadsheet contains no data rows.');
                        return;
                    }

                    parsedRows = json;
                    document.getElementById('total-rows-metric').innerText = parsedRows.length;
                    
                    // Activate Start button
                    startBtn.disabled = false;
                    startBtn.className = "w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-[#ef3b45] hover:bg-[#d12e37] text-white font-bold rounded-xl transition shadow-sm cursor-pointer";
                    
                    logConsole.innerHTML = `<p class="text-gray-400">// File loaded: ${file.name}</p>`;
                    logConsole.innerHTML += `<p class="text-gray-400">// Rows parsed successfully: ${parsedRows.length}</p>`;
                    logConsole.innerHTML += `<p class="text-yellow-400">// Ready to seed properties list. Click 'Start Properties Import' below.</p>`;
                } catch (error) {
                    console.error(error);
                    alert('Error parsing Excel file. Please verify formatting.');
                }
            };
            reader.readAsArrayBuffer(file);
        });

        // Run sequential AJAX row-by-row posts on click
        startBtn.addEventListener('click', async function() {
            // Disable buttons and input
            startBtn.disabled = true;
            startBtn.className = "w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gray-300 text-gray-500 font-bold rounded-xl transition shadow-sm cursor-not-allowed";
            fileInput.disabled = true;
            progressPanel.classList.remove('hidden');

            let importedCount = 0;
            let failedCount = 0;
            const total = parsedRows.length;

            const metricImported = document.getElementById('imported-rows-metric');
            const metricFailed = document.getElementById('failed-rows-metric');
            const progressFill = document.getElementById('progress-bar-fill');
            const progressPercent = document.getElementById('progress-percent');
            const progressStatus = document.getElementById('progress-status-text');

            logConsole.innerHTML += `<p class="text-blue-400">// Beginning progressive data import pipeline...</p>`;

            for (let i = 0; i < total; i++) {
                const row = parsedRows[i];
                const rowNumber = i + 1;
                
                progressStatus.innerText = `Importing properties list: ${rowNumber} of ${total}...`;

                try {
                    const response = await fetch('{{ route("property.import.row") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ row: row })
                    });

                    const resData = await response.json();

                    if (response.ok && resData.success) {
                        importedCount++;
                        metricImported.innerText = importedCount;
                        logConsole.innerHTML += `<p class="text-green-400">Row ${rowNumber} [SUCCESS]: ${resData.message}</p>`;
                    } else {
                        failedCount++;
                        metricFailed.innerText = failedCount;
                        const errorMsg = resData.error || 'Validation mismatch.';
                        logConsole.innerHTML += `<p class="text-red-400">Row ${rowNumber} [FAILED]: ${errorMsg}</p>`;
                    }
                } catch (err) {
                    failedCount++;
                    metricFailed.innerText = failedCount;
                    logConsole.innerHTML += `<p class="text-red-400">Row ${rowNumber} [ERROR]: Connection reset or server failure.</p>`;
                }

                // Update Progress percentage indicators
                const percent = Math.round((rowNumber / total) * 100);
                progressFill.style.width = `${percent}%`;
                progressPercent.innerText = `${percent}%`;
                logConsole.scrollTop = logConsole.scrollHeight; // Auto scroll log window
            }

            progressStatus.innerText = "Data seeding finished.";
            logConsole.innerHTML += `<p class="text-yellow-400">// Import operation complete. ${importedCount} succeeded, ${failedCount} failed.</p>`;
            logConsole.scrollTop = logConsole.scrollHeight;
        });
    </script>
</x-admin-layout>