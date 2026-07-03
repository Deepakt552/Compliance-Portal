<x-admin-layout>
    <div class="max-w-3xl mx-auto px-4 py-8 space-y-6">
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
            <!-- Header banner -->
            <div class="bg-[#0e1e3a] px-6 py-5 border-b border-gray-900 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-base font-bold">Import Property Listings</h2>
                    <p class="text-xs text-blue-200 mt-1">Upload Excel spreadsheet of code-indexed property list data.</p>
                </div>
                <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-gray-300">
                    <i class="fas fa-file-excel text-xl"></i>
                </div>
            </div>
            
            <form action="{{ route('property.import') }}" method="post" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                @csrf
                
                <!-- Dropzone Area -->
                <div>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center transition hover:border-[#ef3b45] hover:bg-red-50/5 group cursor-pointer">
                        <input type="file" name="file" id="file" accept=".xlsx, .xls" required
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
                
                <!-- Action Buttons -->
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-sm font-bold rounded-xl transition shadow-sm">
                        <i class="fas fa-file-import mr-2"></i> Import Properties
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script to update filename -->
    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileNameElement = document.getElementById('file-name');
            const fileNameTextElement = document.getElementById('file-name-text');
            
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                fileNameTextElement.textContent = fileName;
                fileNameElement.classList.remove('hidden');
            } else {
                fileNameElement.classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>