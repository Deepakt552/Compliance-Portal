<x-admin-layout>
    <div class="space-y-6 w-full">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#0e1e3a]">Manage Documents Registry</h1>
                <p class="text-xs text-gray-400 mt-1">Review and manage overall submitted verification files, status updates, and comments.</p>
            </div>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 p-6">
            <form action="{{ route('documents.search') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by first name or last name of members"
                           class="pl-9 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                </div>
                <div class="flex items-center space-x-2 w-full sm:w-auto">
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-sm font-bold rounded-xl transition shadow-sm">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('documents.index') }}"
                           class="py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition text-center flex items-center justify-center">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Documents Data Listing -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            @if ($documents->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3 block"></i>
                    No documents currently registered.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">Document Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Associated Household</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">File Attachment</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-150">
                            @foreach ($documents as $document)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <!-- Document Name & Number -->
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800 leading-snug">
                                        <div class="flex items-start">
                                            <span class="h-6 w-6 rounded bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500 mr-2 flex-shrink-0">
                                                {{ $document->document_number }}
                                            </span>
                                            <span class="pt-0.5">
                                                {{ App\Models\Document::$documentNames[$document->document_number - 1] ?? 'Document Checklist #' . $document->document_number }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Family Head & Member Relationship -->
                                    <td class="px-6 py-4 text-xs text-gray-700">
                                        <div class="font-bold text-gray-900">
                                            @if($document->familyMember)
                                                {{ $document->familyMember->firstName }} {{ $document->familyMember->lastName }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                        <div class="text-gray-400 mt-0.5">Primary HoH: {{ $document->user->name ?? 'N/A' }}</div>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 inline-flex items-center rounded-full text-[10px] font-bold border uppercase
                                            @if($document->status === 'verified') bg-green-50 text-green-700 border-green-200
                                            @elseif($document->status === 'rejected') bg-red-50 text-red-700 border-red-200
                                            @else bg-yellow-50 text-yellow-700 border-yellow-200 @endif">
                                            <i class="fas @if($document->status === 'verified') fa-check-circle @elseif($document->status === 'rejected') fa-times-circle @else fa-hourglass-half @endif mr-1"></i>
                                            {{ $document->status === 'verified' ? 'Approved' : ($document->status === 'rejected' ? 'Rejected' : 'Pending') }}
                                        </span>
                                        
                                        @if($document->status === 'rejected' && $document->comments)
                                            <p class="text-[10px] text-red-600 font-semibold mt-1 max-w-xs">
                                                Feedback: "{{ $document->comments }}"
                                            </p>
                                        @endif
                                    </td>

                                    <!-- File Link -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($document->document_name)
                                            <a href="{{ route('view.pdf', ['fileName' => $document->document_name]) }}" 
                                               target="_blank" 
                                               class="inline-flex items-center px-3 py-1.5 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                <i class="fas fa-eye mr-1.5"></i> View File
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">No Attachment</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                        <a href="{{ route('documents.edit', $document->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                                            <i class="fas fa-edit mr-1"></i> Edit Status
                                        </a>

                                        <form action="{{ route('documents.destroy', $document->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this document permanently?')"
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
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>