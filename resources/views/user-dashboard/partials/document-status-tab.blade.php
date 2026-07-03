<div class="mt-2">
    <div class="overflow-x-auto rounded-xl border border-gray-150">
        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-gray-500 font-bold uppercase tracking-wider">Family Member</th>
                    <th class="px-6 py-3 text-gray-500 font-bold uppercase tracking-wider w-1/3">Document Name</th>
                    <th class="px-6 py-3 text-gray-500 font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-gray-500 font-bold uppercase tracking-wider">Operations</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-150">
                @php $hasDocuments = false; @endphp
                @foreach($familyMembers as $familyMember)
                    @php
                        $documents = $documentsByFamilyMember[$familyMember->id]['documents']->where('status', $status);
                    @endphp
                    @if($documents->isNotEmpty())
                        @php $hasDocuments = true; @endphp
                        @foreach($documents as $document)
                            <tr class="hover:bg-gray-50/50 transition">
                                <!-- Family Member -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="h-7 w-7 rounded-full bg-gray-100 flex items-center justify-center font-bold text-[#0e1e3a] text-[10px]">
                                            {{ strtoupper(substr($familyMember->firstName, 0, 1) . substr($familyMember->lastName, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 leading-none">{{ $familyMember->firstName }} {{ $familyMember->lastName }}</p>
                                            <span class="text-[9px] bg-indigo-50 text-indigo-700 font-bold px-1.5 py-0.5 rounded border border-indigo-100 uppercase tracking-wide mt-1 inline-block">{{ $familyMember->Relation }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Document Name -->
                                <td class="px-6 py-4 text-gray-700 font-medium leading-relaxed">
                                    {{ App\Models\Document::$documentNames[$document->document_number - 1] ?? 'Document #' . $document->document_number }}
                                </td>

                                <!-- Status Badge -->
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 inline-flex items-center rounded-full text-[10px] font-bold border uppercase
                                        @if($document->status === 'verified') bg-green-50 text-green-700 border-green-200
                                        @elseif($document->status === 'rejected') bg-red-50 text-red-700 border-red-200
                                        @else bg-yellow-50 text-yellow-700 border-yellow-200 @endif">
                                        <i class="fas @if($document->status === 'verified') fa-check-circle @elseif($document->status === 'rejected') fa-times-circle @else fa-hourglass-half @endif mr-1"></i>
                                        {{ $document->status === 'verified' ? 'Approved' : ($document->status === 'rejected' ? 'Rejected' : 'Pending Verification') }}
                                    </span>

                                    <!-- Rejected Comments -->
                                    @if($document->status === 'rejected' && $document->comments)
                                        <div class="mt-2 p-2 bg-red-50 border border-red-100 rounded-xl text-[10px] text-red-800 leading-snug">
                                            <strong class="font-bold text-red-900 block mb-0.5">Feedback:</strong>
                                            "{{ $document->comments }}"
                                        </div>
                                    @endif
                                </td>

                                <!-- Operations (ReUpload & View) -->
                                <td class="px-6 py-4 text-right whitespace-nowrap space-y-2 sm:space-y-0 sm:space-x-2">
                                    @if($document->status === 'rejected')
                                        <!-- Re-upload Form -->
                                        <form method="post" action="{{ route('reupload.document') }}" enctype="multipart/form-data" class="inline-flex items-center space-x-1">
                                            @csrf
                                            <input type="file" name="document" required accept="application/pdf"
                                                   class="block w-40 text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-xl file:border-0 file:text-[10px] file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 transition cursor-pointer" />
                                            <input type="hidden" name="family_member_id" value="{{ $document->family_member_id }}">
                                            <input type="hidden" name="document_number" value="{{ $document->document_number }}">
                                            <input type="hidden" name="document_id" value="{{ $document->id }}">
                                            <button type="submit" class="bg-[#ef3b45] hover:bg-[#d12e37] text-white font-bold px-2.5 py-1 rounded-xl transition text-[10px] flex items-center shadow-sm">
                                                <i class="fas fa-redo mr-1"></i> Re-Upload
                                            </button>
                                        </form>
                                    @endif

                                    @if($document->document_name)
                                        <a href="{{ route('view.pdf', ['fileName' => $document->document_name]) }}" 
                                           target="_blank" 
                                           class="inline-flex items-center px-2.5 py-1 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-[10px] font-bold rounded-xl transition shadow-sm">
                                            <i class="fas fa-eye mr-1"></i> View File
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach

                @if(!$hasDocuments)
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                            <i class="fas fa-folder-open text-3xl text-gray-300 mb-2 block"></i>
                            No documents in this category.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>