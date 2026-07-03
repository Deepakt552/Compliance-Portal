<div class="container">
    <h2>Family Member Details</h2>

    <h3>{{ $familyMember->name }}</h3>

    <ul>
        @foreach($documents as $document)
        <li>
            <strong>Document Name:</strong> {{ $document->document_name }}
            <span class="badge badge-secondary">{{ ucfirst($document->status) }}</span>
            @if($document->status === 'pending')
            <!-- Add your approval/rejection form here -->
            <form method="post" action="{{ route('admin.approveDocument', ['documentId' => $document->id]) }}">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">Approve</button>
            </form>

            <form method="post" action="{{ route('admin.rejectDocument', ['documentId' => $document->id]) }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
            </form>
            @endif
        </li>
        @endforeach
    </ul>
</div>