<div class="mt-2 space-y-4">
    @php $hasDocuments = false; @endphp

    @foreach($familyMembers as $familyMember)
        @php
            $documents = $documentsByFamilyMember[$familyMember->id]['documents']->where('status', $status);
        @endphp

        @if($documents->isNotEmpty())
            @php $hasDocuments = true; @endphp

            {{-- Family Member Group Label --}}
            <div class="flex items-center space-x-2.5 pt-2 pb-1 px-1">
                <div class="h-8 w-8 rounded-full bg-[#0e1e3a] text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                    {{ strtoupper(substr($familyMember->firstName, 0, 1) . substr($familyMember->lastName, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-[#0e1e3a] text-sm leading-none">{{ $familyMember->firstName }} {{ $familyMember->lastName }}</p>
                    <span class="text-[9px] bg-indigo-50 text-indigo-700 font-bold px-1.5 py-0.5 rounded border border-indigo-100 uppercase tracking-wide mt-0.5 inline-block">{{ $familyMember->Relation }}</span>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 overflow-hidden divide-y divide-gray-100">
                @foreach($documents as $document)
                    @php
                        $docName = App\Models\Document::$documentNames[$document->document_number - 1] ?? 'Document #' . $document->document_number;
                    @endphp

                    <div class="p-4 @if($document->status === 'rejected') bg-red-50/30 @elseif($document->status === 'verified') bg-green-50/20 @else bg-yellow-50/20 @endif">
                        {{-- Top Row: Doc name + status badge --}}
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-800 leading-snug">{{ $docName }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Document #{{ $document->document_number }}</p>
                            </div>
                            <span class="px-2.5 py-1 inline-flex items-center rounded-full text-[10px] font-bold border uppercase flex-shrink-0
                                @if($document->status === 'verified') bg-green-50 text-green-700 border-green-200
                                @elseif($document->status === 'rejected') bg-red-50 text-red-700 border-red-200
                                @else bg-yellow-50 text-yellow-700 border-yellow-200 @endif">
                                <i class="fas @if($document->status === 'verified') fa-check-circle @elseif($document->status === 'rejected') fa-times-circle @else fa-hourglass-half @endif mr-1"></i>
                                {{ $document->status === 'verified' ? 'Approved' : ($document->status === 'rejected' ? 'Rejected' : 'Pending Verification') }}
                            </span>
                        </div>

                        {{-- Rejection Feedback Box --}}
                        @if($document->status === 'rejected' && $document->comments)
                            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-xl text-[11px] text-red-800 leading-relaxed">
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <strong class="font-bold text-red-900 block mb-0.5">Admin Feedback:</strong>
                                        {{ $document->comments }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Actions Row --}}
                        <div class="flex flex-wrap items-end gap-3">
                            {{-- Re-upload form (only for rejected) --}}
                            @if($document->status === 'rejected')
                                <form method="post" action="{{ route('reupload.document') }}" enctype="multipart/form-data"
                                      class="flex-1 min-w-0">
                                    @csrf
                                    <input type="hidden" name="family_member_id" value="{{ $document->family_member_id }}">
                                    <input type="hidden" name="document_number" value="{{ $document->document_number }}">
                                    <input type="hidden" name="document_id" value="{{ $document->id }}">

                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                        <i class="fas fa-paperclip mr-1"></i> Select Replacement File
                                    </label>

                                    {{-- Styled file input card --}}
                                    <div class="relative flex items-center border-2 border-dashed border-red-300 bg-white rounded-xl px-4 py-3 hover:border-[#ef3b45] transition group cursor-pointer">
                                        <i class="fas fa-file-upload text-[#ef3b45] text-lg mr-3 group-hover:scale-110 transition-transform"></i>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] font-semibold text-gray-600">Click to choose a file</p>
                                            <p class="text-[9px] text-gray-400 mt-0.5">Max 5MB (PDF, Image, Excel, CSV)</p>
                                        </div>
                                        <input type="file" name="document" required accept="image/*,application/pdf,.csv,.xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                        <span class="reupload-filename text-[10px] text-[#ef3b45] font-bold ml-2 truncate max-w-[120px] hidden"></span>
                                    </div>

                                    <button type="submit"
                                            class="mt-2 w-full sm:w-auto flex items-center justify-center space-x-2 bg-[#ef3b45] hover:bg-[#d12e37] active:bg-[#b22530] text-white font-bold px-4 py-2 rounded-xl transition text-xs shadow-sm">
                                        <i class="fas fa-redo"></i>
                                        <span>Submit Re-Upload</span>
                                    </button>
                                </form>
                            @endif

                            {{-- View File button --}}
                            @if($document->document_name)
                                <div class="flex-shrink-0 self-end">
                                    <a href="{{ route('view.pdf', ['fileName' => $document->document_name]) }}"
                                       target="_blank"
                                       class="inline-flex items-center space-x-2 px-4 py-2 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-xs font-bold rounded-xl transition shadow-sm">
                                        <i class="fas fa-eye"></i>
                                        <span>View Current File</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach

    @if(!$hasDocuments)
        <div class="py-12 text-center text-gray-500">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                <i class="fas fa-folder-open text-2xl text-gray-300"></i>
            </div>
            <p class="text-sm font-semibold text-gray-400">No documents in this category.</p>
            <p class="text-xs text-gray-300 mt-1">Documents will appear here once processed.</p>
        </div>
    @endif
</div>

<script>
// Show chosen filename in the styled file picker
document.querySelectorAll('.reupload-filename').forEach(function(span) {
    const container = span.closest('.relative');
    if (!container) return;
    const fileInput = container.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                span.textContent = this.files[0].name;
                span.classList.remove('hidden');
                container.classList.add('border-[#ef3b45]', 'bg-red-50/30');
            } else {
                span.textContent = '';
                span.classList.add('hidden');
            }
        });
    }
});
</script>