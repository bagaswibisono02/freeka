<!DOCTYPE html>
<html>

<head>
    <title>Chat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tambahkan Pusher (dibutuhkan meskipun pakai Reverb karena Laravel Echo tergantung Pusher interface) -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <!-- Tambahkan Laravel Echo (global UMD build) -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
</head>

<body>
    <h2>Chat Realtime</h2>

    <ul id="chat-box">
        @foreach ($messages as $msg)
            <li><strong>{{ $msg->user }}</strong>: {{ $msg->message }}</li>
        @endforeach
    </ul>

    <form method="POST" action="/send">
        @csrf
        <input type="text" name="user" placeholder="Nama Anda" required>
        <input type="text" name="message" placeholder="Ketik pesan..." required>
        <button type="submit">Kirim</button>
    </form>

    <script>
        const chatBox = document.getElementById('chat-box');

        const echo = new Echo({
            broadcaster: 'reverb',
            host: window.location.hostname + ':6001',
        });

        echo.channel('chat')
            .listen('MessageSent', (e) => {
                console.log('Pesan masuk:', e.message);

                const li = document.createElement('li');
                li.innerHTML = `<strong>${e.message.user}</strong>: ${e.message.message}`;
                chatBox.appendChild(li);
            });
    </script>
</body>

</html>
