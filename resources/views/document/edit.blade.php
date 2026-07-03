<x-admin-layout>
    <div class="space-y-6 max-w-3xl mx-auto">
        <!-- Back navigation link -->
        <div>
            <a href="{{ route('documents.index') }}" class="inline-flex items-center text-sm font-semibold text-[#0e1e3a] hover:text-[#ef3b45] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Documents Registry
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-150">
                <h1 class="text-lg font-bold text-[#0e1e3a]">Edit Document Status</h1>
                <p class="text-xs text-gray-400 mt-1">Update verification status, files, or audit comments.</p>
            </div>

            <form action="{{ route('documents.update', $document->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Document Name (Read-only reference) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Document Name Reference</label>
                    @php
                        $documentName = \App\Models\Document::getDocumentName($document->document_number);
                    @endphp
                    <!-- Hidden field to preserve state if required by request binding -->
                    <input type="hidden" name="document_name" value="{{ $document->document_name }}">
                    <input type="text" value="{{ $documentName }}" readonly
                           class="block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 text-sm py-2.5 font-medium">
                </div>

                <!-- Status Selector -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Verification Status</label>
                    <select id="status" name="status"
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">
                        <option value="pending" {{ $document->status === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="verified" {{ $document->status === 'verified' ? 'selected' : '' }}>Approved / Verified</option>
                        <option value="rejected" {{ $document->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Rejection Comments -->
                <div>
                    <label for="comments" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Audit Comments (Required for rejected status)</label>
                    <textarea name="comments" id="comments" rows="4" placeholder="Describe the reason for rejection or special instructions..."
                              class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5">{{ $document->comments }}</textarea>
                </div>

                <!-- File Path -->
                <div>
                    <label for="file_path" class="block text-xs font-semibold text-gray-500 uppercase mb-2">Stored File Reference Path</label>
                    <input type="text" name="file_path" id="file_path" value="{{ $document->file_path }}" required
                           class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#0e1e3a] focus:ring focus:ring-[#0e1e3a] focus:ring-opacity-20 text-sm py-2.5 font-mono text-xs">
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-gray-100 flex justify-end space-x-2">
                    <a href="{{ route('documents.index') }}"
                       class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition text-center flex items-center justify-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-[#0e1e3a] hover:bg-[#1a3461] text-white text-sm font-bold rounded-xl transition shadow-sm flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>