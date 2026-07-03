<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Uploaded</title>
</head>

<body>

    <p>Dear Admin,</p>

    <p>We would like to inform you that {{ $document->user->name }} has uploaded a new document.</p>

    <p>Your attention is required regarding this document. Here are the details:</p>

    <p>Document Name:
        @php
        $words = str_word_count(App\Models\Document::$documentNames[$document->document_number - 1], 1);
        $chunks = array_chunk($words, 10);
        @endphp

        @foreach ($chunks as $chunk)
        {{ implode(' ', $chunk) }}<br>
        @endforeach
    </p>

    <p>Status: {{ $document->status }}</p>

    <p>Please review the document and take appropriate action.</p>

    <p>Regards,</p>
    <p>Triumph Residential</p>

</body>

</html>