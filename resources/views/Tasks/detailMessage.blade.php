<!-- resources/views/messages/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
</head>
<body>
    <h1>Messages</h1>
    <ul>
        @foreach ($messages as $message)
            <li>
                <p>User: {{ $message['userName'] }}</p>
                <p>Message Date: {{ $message['messageDate'] }}</p>
                <p>Message Time: {{ $message['messagetime'] }}</p>
                @if ($message['messageType'] == 'image')
                    <img src="{{ route('show.image', ['messageId' => $message['messageId']]) }}" alt="Image">
                    <a href="{{ route('download.image', ['messageId' => $message['messageId']]) }}">Download Image</a>
                @endif
            </li>
        @endforeach
    </ul>
</body>
</html>