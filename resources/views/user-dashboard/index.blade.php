<x-app-layout>
    <div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-6xl mx-auto space-y-6">
            
            <!-- Welcome Brand Banner -->
            <div class="rounded-2xl bg-[#0e1e3a] text-white p-6 md:p-8 shadow-sm border border-gray-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#ef3b45] font-bold">Member Portal</span>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-1">Welcome, {{ $user->name }}!</h1>
                    <p class="text-blue-200 text-xs mt-1">Manage and upload compliance documents for your household.</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-[#ef3b45] flex items-center justify-center font-bold text-white text-sm">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </div>

            <!-- Household Information Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Property Card -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-150 flex items-center space-x-4">
                    <div class="p-3 rounded-xl bg-red-50 text-[#ef3b45]">
                        <i class="fas fa-building text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Property Name</p>
                        <p class="text-sm font-bold text-gray-800">{{ $user->property->Property ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Unit Card -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-150 flex items-center space-x-4">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                        <i class="fas fa-door-open text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Unit Number</p>
                        <p class="text-sm font-bold text-gray-800">Unit {{ $user->UnitNo }}</p>
                    </div>
                </div>

                <!-- Family Size Card -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-150 flex items-center space-x-4">
                    <div class="p-3 rounded-xl bg-green-50 text-green-600">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Family Members</p>
                        <p class="text-sm font-bold text-gray-800">{{ count($familyMembers) }} Registered</p>
                    </div>
                </div>
            </div>

            <!-- Main Upload Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-3 sm:p-5">
                <div class="border-b border-gray-150 pb-3 mb-4">
                    <h2 class="text-sm font-bold text-[#0e1e3a]"><i class="fas fa-file-upload mr-2 text-gray-400"></i>Upload Family Members Documents</h2>
                    <p class="text-xs text-gray-400 mt-1">Select a family member below to view and upload their required compliance documents.</p>
                </div>

                <div class="space-y-4">
                    @forelse($familyMembers as $familyMember)
                        @php
                            $memberDocs = $documentsByFamilyMember[$familyMember->id]['documents'] ?? collect();
                            $uploadedCount = $memberDocs->where('document_name', '!=', '')->count();
                            $progressPercent = round(($uploadedCount / 30) * 100);
                        @endphp
                        
                        <div x-data="{ isOpen: false, activeTab: 'all', searchQuery: '' }" 
                             class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm transition hover:border-[#0e1e3a]/30">
                            
                            <!-- Toggle Button / Header -->
                            <button @click="isOpen = !isOpen"
                                    class="w-full px-5 py-4 text-left bg-gray-50/50 hover:bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition focus:outline-none select-none">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($familyMember->firstName, 0, 1) . substr($familyMember->lastName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-800">{{ $familyMember->firstName }} {{ $familyMember->lastName }}</span>
                                        <span class="ml-2 text-[10px] bg-indigo-50 text-indigo-700 font-bold px-2 py-0.5 rounded-full border border-indigo-100 uppercase">{{ $familyMember->Relation }}</span>
                                    </div>
                                </div>

                                <!-- Progress Gauge on Header -->
                                <div class="flex items-center space-x-4 self-start sm:self-auto w-full sm:w-auto">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">Documents:</span>
                                        <span class="text-xs font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded-lg border border-gray-200">
                                            {{ $uploadedCount }} / 30
                                        </span>
                                    </div>
                                    <div class="w-20 bg-gray-200 h-1.5 rounded-full overflow-hidden hidden xs:block">
                                        <div class="h-1.5 rounded-full transition-all duration-300 {{ $uploadedCount == 30 ? 'bg-green-500' : ($uploadedCount > 0 ? 'bg-yellow-500' : 'bg-gray-300') }}" 
                                             style="width: {{ ($uploadedCount / 30) * 100 }}%"></div>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-250 ml-auto sm:ml-0" :class="{'rotate-180': isOpen}"></i>
                                </div>
                            </button>

                            <!-- Member Accordion Content -->
                            <div x-show="isOpen" class="px-3 py-4 border-t border-gray-150 space-y-5" style="display: none;">
                                <!-- Member Metadata Grid -->
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    <div>
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase">Date of Birth</span>
                                        <span class="text-xs font-semibold text-gray-800">{{ $familyMember->dob ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase">Age</span>
                                        @php
                                            $age = $familyMember->dob ? \Carbon\Carbon::parse($familyMember->dob)->age : 'N/A';
                                            $classification = ($age !== 'N/A' && $age < 18) ? 'Minor' : 'Adult';
                                        @endphp
                                        <span class="text-xs font-semibold text-gray-800">{{ $age }} yrs ({{ $classification }})</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase">Property Code</span>
                                        <span class="text-xs font-semibold text-gray-800">{{ $familyMember->Code }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase">Unit Number</span>
                                        <span class="text-xs font-semibold text-gray-800">Unit {{ $familyMember->UnitNo }}</span>
                                    </div>
                                </div>

                                <!-- Filter Controls -->
                                <div class="flex flex-col gap-2">
                                    <!-- Status Tabs -->
                                    <div class="flex items-center space-x-1 bg-gray-100 p-1 rounded-xl self-start flex-wrap">
                                        <button @click="activeTab = 'all'"
                                                :class="{'bg-white text-gray-850 shadow-sm': activeTab === 'all', 'text-gray-500 hover:text-gray-850': activeTab !== 'all'}"
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition">
                                            All (30)
                                        </button>
                                        <button @click="activeTab = 'uploaded'"
                                                :class="{'bg-white text-green-700 shadow-sm': activeTab === 'uploaded', 'text-gray-500 hover:text-gray-850': activeTab !== 'uploaded'}"
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition">
                                            <i class="fas fa-check text-[8px] mr-0.5"></i>Done ({{ $uploadedCount }})
                                        </button>
                                        <button @click="activeTab = 'pending'"
                                                :class="{'bg-white text-red-700 shadow-sm': activeTab === 'pending', 'text-gray-500 hover:text-gray-850': activeTab !== 'pending'}"
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition">
                                            <i class="fas fa-clock text-[8px] mr-0.5"></i>Pending ({{ 30 - $uploadedCount }})
                                        </button>
                                    </div>

                                    <!-- Search Requirements Box -->
                                    <div class="relative w-full">
                                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400">
                                            <i class="fas fa-search text-[10px]"></i>
                                        </span>
                                        <input type="text" x-model="searchQuery" placeholder="Search requirements..."
                                               class="pl-8 pr-3 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-[11px] py-1.5">
                                    </div>
                                </div>

                                <!-- Documents Checklist List -->
                                @if(isset($documentsByFamilyMember[$familyMember->id]))
                                    <div class="rounded-xl border border-gray-200 overflow-hidden divide-y divide-gray-100">

                                        {{-- Header row (desktop only) --}}
                                        <div class="hidden sm:flex items-center bg-gray-50 px-3 py-2 gap-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                            <span class="w-7 text-center flex-shrink-0">#</span>
                                            <span class="flex-1">Document Name</span>
                                            <span class="w-52 flex-shrink-0">File</span>
                                            <span class="w-20 flex-shrink-0 text-center">Action</span>
                                        </div>

                                        @foreach(range(1, 30) as $documentNumber)
                                            @php
                                                $document = $documentsByFamilyMember[$familyMember->id]['documents']
                                                            ->where('document_number', $documentNumber)->first();
                                                $hasDoc = $document && $document->document_name;
                                                $docName = $documentNames[$documentNumber - 1] ?? 'Document Checklist Item ' . $documentNumber;
                                            @endphp

                                            <div x-show="(activeTab === 'all' || (activeTab === 'uploaded' && {{ $hasDoc ? 'true' : 'false' }}) || (activeTab === 'pending' && {{ $hasDoc ? 'false' : 'true' }})) && ('{{ strtolower($docName) }}'.includes(searchQuery.toLowerCase()))"
                                                 class="transition {{ $hasDoc ? 'bg-green-50/30 hover:bg-green-50/50' : 'bg-white hover:bg-gray-50/60' }}">

                                                @if($hasDoc)
                                                    {{-- ── UPLOADED STATE ──────────────────────────────────── --}}
                                                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3 px-3 py-2.5">
                                                        {{-- Number --}}
                                                        <span class="flex-shrink-0 h-6 w-6 sm:h-7 sm:w-7 rounded-full bg-green-100 text-green-700 text-[10px] font-extrabold flex items-center justify-center border border-green-200">
                                                            {{ $documentNumber }}
                                                        </span>
                                                        {{-- Doc Name --}}
                                                        <span class="flex-1 min-w-0 text-xs sm:text-sm font-semibold text-gray-800 leading-snug">
                                                            {{ $docName }}
                                                        </span>
                                                        {{-- Status + View --}}
                                                        <div class="flex items-center gap-2 flex-shrink-0 ml-auto sm:ml-0">
                                                            <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2 py-1 rounded-lg text-[10px] font-bold">
                                                                <i class="fas fa-check-circle text-[9px]"></i> Uploaded
                                                            </span>
                                                            <a href="{{ route('view.pdf', ['fileName' => $document->document_name]) }}"
                                                               target="_blank"
                                                               class="inline-flex items-center gap-1 bg-[#0e1e3a] hover:bg-[#1a3461] text-white px-2.5 py-1 rounded-lg text-[10px] font-bold transition shadow-sm">
                                                                <i class="fas fa-eye text-[9px]"></i>
                                                                <span class="hidden sm:inline">View</span>
                                                                <span class="sm:hidden">View</span>
                                                            </a>
                                                        </div>
                                                    </div>

                                                @else
                                                    {{-- ── UPLOAD FORM ─────────────────────────────────────── --}}
                                                    <div class="px-3 py-2.5">

                                                        {{-- Mobile: doc name on top --}}
                                                        <div class="flex items-center gap-2 mb-2 sm:hidden">
                                                            <span class="flex-shrink-0 h-6 w-6 rounded-full bg-gray-100 text-gray-500 text-[10px] font-extrabold flex items-center justify-center border border-gray-200">
                                                                {{ $documentNumber }}
                                                            </span>
                                                            <span class="text-xs font-semibold text-gray-800 leading-snug">{{ $docName }}</span>
                                                        </div>

                                                        {{-- Desktop + Mobile controls row --}}
                                                        <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3">

                                                            {{-- Desktop: number badge --}}
                                                            <span class="hidden sm:flex flex-shrink-0 h-7 w-7 rounded-full bg-gray-100 text-gray-400 text-[10px] font-extrabold items-center justify-center border border-gray-200">
                                                                {{ $documentNumber }}
                                                            </span>

                                                            {{-- Desktop: doc name --}}
                                                            <span class="hidden sm:block flex-1 min-w-0 text-sm font-semibold text-gray-800 leading-snug">
                                                                {{ $docName }}
                                                            </span>

                                                            {{-- Upload form (visible on both) --}}
                                                            <div class="flex-1 sm:flex-none sm:w-52 min-w-0 w-full">
                                                                <form class="upload-form flex items-center gap-2" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="family_member_id" value="{{ $familyMember->id }}">
                                                                    <input type="hidden" name="document_number" value="{{ $documentNumber }}">

                                                                    {{-- File picker --}}
                                                                    <div class="upload-dropzone relative flex-1 flex items-center border border-dashed border-gray-300 bg-gray-50 rounded-lg px-2.5 py-1.5 hover:border-[#ef3b45] hover:bg-red-50/20 transition group cursor-pointer overflow-hidden">
                                                                        <i class="fas fa-file-pdf text-gray-300 group-hover:text-[#ef3b45] text-sm mr-1.5 flex-shrink-0 transition-colors"></i>
                                                                        <div class="flex-1 min-w-0">
                                                                            <p class="upload-placeholder text-[10px] font-semibold text-gray-400 group-hover:text-[#ef3b45] transition-colors truncate">Choose PDF</p>
                                                                            <p class="upload-selected-name text-[10px] font-bold text-[#ef3b45] hidden truncate"></p>
                                                                        </div>
                                                                        <input type="file" name="document" required accept="application/pdf"
                                                                               class="upload-file-input absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                                                    </div>

                                                                    {{-- Upload button --}}
                                                                    <button type="submit"
                                                                            class="upload-btn flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-[#ef3b45] hover:bg-[#d12e37] active:bg-[#b22530] text-white text-[10px] font-bold rounded-lg transition shadow-sm whitespace-nowrap">
                                                                        <i class="fas fa-cloud-upload-alt"></i>
                                                                        <span class="upload-btn-text">Upload</span>
                                                                    </button>
                                                                </form>
                                                            </div>

                                                            {{-- Post-upload success indicator --}}
                                                            <div class="uploadStatusDiv hidden flex items-center gap-1.5 mt-1 sm:mt-0">
                                                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2 py-1 rounded-lg text-[10px] font-bold">
                                                                    <i class="fas fa-check-circle text-[9px]"></i> Uploaded
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endif

                                            </div>
                                        @endforeach

                                    </div>
                                @else
                                    <p class="text-xs text-gray-500 italic p-4 text-center">No document records initialized for this member.</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-users-slash text-3xl text-gray-300 mb-2 block"></i>
                            No family members registered for this account.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification (Success/Error Alerts) -->
    <div class="alert-toast fixed bottom-6 right-6 z-50 w-full max-w-sm" style="display: none;">
        <input type="checkbox" class="hidden" id="toast-close">
        <label for="toast-close" class="close cursor-pointer flex items-center justify-between w-full p-4 bg-green-600 text-white rounded-2xl shadow-xl border border-green-500 transition duration-300 hover:bg-green-700">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span class="toast-message text-sm font-semibold"></span>
            </div>
            <i class="fas fa-times text-sm opacity-70 hover:opacity-100"></i>
        </label>
    </div>

    <script>
    // ─── Filename preview inside dropzone card ──────────────────────────────────
    document.querySelectorAll('.upload-file-input').forEach(function(input) {
        const dropzone  = input.closest('.upload-dropzone');
        const form      = input.closest('.upload-form');
        if (!dropzone || !form) return;

        const placeholder = dropzone.querySelector('.upload-placeholder');
        const selected    = dropzone.querySelector('.upload-selected-name');

        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                if (placeholder) placeholder.classList.add('hidden');
                if (selected)    { selected.textContent = fileName; selected.classList.remove('hidden'); }
                dropzone.classList.add('border-[#ef3b45]', 'bg-red-50');
                dropzone.classList.remove('border-gray-300', 'bg-gray-50');
            } else {
                if (placeholder) placeholder.classList.remove('hidden');
                if (selected)    { selected.textContent = ''; selected.classList.add('hidden'); }
                dropzone.classList.remove('border-[#ef3b45]', 'bg-red-50');
                dropzone.classList.add('border-gray-300', 'bg-gray-50');
            }
        });
    });

    // ─── Upload form submit handler ─────────────────────────────────────────────
    document.querySelectorAll('.upload-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData  = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const btnText   = submitBtn.querySelector('.upload-btn-text');
            const btnIcon   = submitBtn.querySelector('i');

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            if (btnIcon)  btnIcon.className = 'fas fa-spinner fa-spin';
            if (btnText)  btnText.textContent = 'Uploading...';

            try {
                const response = await fetch('{{ route("upload.document") }}', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    const responseData = await response.json();
                    handleSuccess(responseData.message, form);
                } else {
                    handleError(submitBtn, btnText, btnIcon);
                }
            } catch (error) {
                console.error('Error occurred during document upload:', error);
                handleError(submitBtn, btnText, btnIcon);
            }
        });
    });

    function handleSuccess(message, form) {
        // Hide the form wrapper and show the uploaded status indicator
        const wrapper   = form.closest('.w-full');
        const statusDiv = wrapper ? wrapper.querySelector('.uploadStatusDiv') : form.nextElementSibling;
        form.style.display = 'none';
        if (statusDiv) statusDiv.classList.remove('hidden');
        showSuccessToast(message);
    }

    function handleError(submitBtn, btnText, btnIcon) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        if (btnIcon)  btnIcon.className = 'fas fa-cloud-upload-alt';
        if (btnText)  btnText.textContent = 'Upload';
        alert('An error occurred during document upload. Please try again.');
    }

    function showSuccessToast(message) {
        const toast = document.querySelector('.alert-toast');
        toast.querySelector('.toast-message').innerText = message;
        $(toast).fadeIn(300);
        setTimeout(hideToastWithAnimation, 6000);
    }

    function hideToastWithAnimation() {
        var toast = document.querySelector('.alert-toast');
        if (toast) $(toast).fadeOut(300);
    }
    </script>
</x-app-layout>