<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Approved</title>
</head>

<body>
    <p>Dear {{ $document->user->name }},</p>
    <p>Your document " @php
        $words = str_word_count(App\Models\Document::$documentNames[$document->document_number -
        1], 1);
        $chunks = array_chunk($words, 10);
        @endphp

        @foreach ($chunks as $chunk)
        {{ implode(' ', $chunk) }}<br>
        @endforeach" has been Approved.</p>
    <p>Thank you for submitting your document.</p>
    <p>Regards,</p>
    <p>Triumph Residential</p>
</body>

</html>