<x-admin-layout>
    <div class="space-y-6 max-w-6xl mx-auto">
        <!-- Back Button -->
        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-semibold text-[#0e1e3a] hover:text-[#ef3b45] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

        <!-- Head Family Representative Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Head of Household</p>
                <h1 class="text-2xl font-bold text-[#0e1e3a]">{{ $user->name ?? 'N/A' }}</h1>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-red-50 text-[#ef3b45] text-xs font-bold rounded-full border border-red-100">
                    Unit {{ $householdData->UnitNo }}
                </span>
                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100">
                    {{ $propertyNames[$householdData->Code] ?? 'Code: ' . $householdData->Code }}
                </span>
            </div>
        </div>

        <!-- Family Member Profile Details Section (Always Visible) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-150">
                <h2 class="text-base font-bold text-[#0e1e3a]">Family Member Profile</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Full Name</span>
                        <span class="text-sm font-bold text-gray-900">{{ $householdData->firstName }} {{ $householdData->lastName }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Relationship</span>
                        <span class="inline-block px-2.5 py-0.5 text-xs font-bold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase">{{ $householdData->Relation }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Date of Birth</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $householdData->dob ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Age Classification</span>
                        @php
                            $age = $householdData->dob ? \Carbon\Carbon::parse($householdData->dob)->age : $householdData->Age;
                            $status = $age < 18 ? 'Minor' : 'Adult';
                        @endphp
                        <span class="text-sm font-semibold text-gray-800">
                            {{ $age ?? 'N/A' }} years old ({{ $status }})
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Checklist / Verifications -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-150 flex items-center justify-between">
                <h3 class="text-base font-bold text-[#0e1e3a]">Submitted Documents Verification</h3>
                <span class="text-xs bg-gray-200 text-gray-700 font-semibold px-2.5 py-1 rounded-full">
                    {{ $documents->count() }} Documents Uploaded
                </span>
            </div>

            @if($documents->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-file-excel text-4xl text-gray-300 mb-3 block"></i>
                    No documents have been uploaded by this family member yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">Document Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status & Operations</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-150">
                            @foreach($documents as $document)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <!-- Document Name & Number -->
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                        <div class="flex items-start">
                                            <span class="h-6 w-6 rounded bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 mr-2 flex-shrink-0">
                                                {{ $document->document_number }}
                                            </span>
                                            <span class="leading-tight pt-0.5">
                                                {{ App\Models\Document::$documentNames[$document->document_number - 1] ?? 'Document #' . $document->document_number }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Status / Validation Forms -->
                                    <td class="px-6 py-4">
                                        @if($document->status === 'pending')
                                            <div class="flex flex-wrap items-center gap-3">
                                                <!-- Approve form -->
                                                <form method="post" action="{{ route('admin.approveDocument', ['documentId' => $document->id]) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl transition shadow-sm flex items-center">
                                                        <i class="fas fa-check-circle mr-1.5"></i> Approve
                                                    </button>
                                                </form>

                                                <!-- Reject form -->
                                                <form method="post" action="{{ route('admin.rejectDocument', ['documentId' => $document->id]) }}" class="inline-flex items-center gap-1.5">
                                                    @csrf
                                                    <div class="relative">
                                                        <input type="text" name="comments" placeholder="Reason for rejection" required
                                                               class="border border-gray-300 rounded-xl px-3 py-1.5 text-xs w-48 focus:border-[#ef3b45] focus:ring focus:ring-[#ef3b45] focus:ring-opacity-20"
                                                               list="comments-options-{{ $document->id }}">
                                                        <datalist id="comments-options-{{ $document->id }}">
                                                            <option value="Document is blurry or illegible.">
                                                            <option value="Expired date or out-of-date document.">
                                                            <option value="Incorrect document type uploaded.">
                                                            <option value="Document does not match family member.">
                                                            <option value="Missing pages or signatures.">
                                                        </datalist>
                                                    </div>
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold text-xs px-3.5 py-2 rounded-xl transition shadow-sm flex items-center">
                                                        <i class="fas fa-times-circle mr-1.5"></i> Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="flex flex-col space-y-1">
                                                <div>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border uppercase
                                                        {{ $document->status === 'verified' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                                        <i class="fas {{ $document->status === 'verified' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                                        {{ $document->status === 'verified' ? 'Approved' : 'Rejected' }}
                                                    </span>
                                                </div>
                                                @if($document->status === 'rejected' && $document->comments)
                                                    <p class="text-xs text-red-600 font-semibold leading-relaxed">
                                                        <span class="text-gray-400 font-normal">Feedback:</span> "{{ $document->comments }}"
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- PDF / View Document -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($document->document_name)
                                            <a href="{{ route('view.pdf', ['fileName' => $document->document_name]) }}" 
                                               target="_blank" 
                                               class="inline-flex items-center px-3.5 py-1.5 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                <i class="fas fa-eye mr-1"></i> View Document
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 font-medium">No File Attachment</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>