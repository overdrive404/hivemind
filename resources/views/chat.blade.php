@extends('layouts.layout')

@section('content')

    <div class="container mt-4">
        <div class="row">
            @include('layouts.sidebar')
            <div class="col-md-9">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">Чат</div>
                                <div class="card-body">
                                    <div id="chat-box" style="height: 400px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px;">
                                        @foreach($messages as $message)
                                            <div class="mb-2">
                                                <strong>{{ $message->sender->name }}:</strong> {{ $message->content }}
                                            </div>
                                        @endforeach
                                    </div>

                                    <form id="chat-form">
                                        @csrf
                                        <input type="hidden" id="receiver_id" value="{{ $receiver->id }}">
                                        <div class="input-group mt-3">
                                            <input type="text" id="message" class="form-control" placeholder="Введите сообщение..." required>
                                            <button type="submit" class="btn btn-primary">Отправить</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Подключаем Pusher
        Pusher.logToConsole = true;
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            encrypted: true
        });

        var receiverId = document.getElementById("receiver_id").value;
        var channel = pusher.subscribe("private-chat." + receiverId);

        channel.bind("MessageSent", function (data) {
            let chatBox = document.getElementById("chat-box");
            let newMessage = `<div class="mb-2"><strong>${data.message.sender.name}:</strong> ${data.message.content}</div>`;
            chatBox.innerHTML += newMessage;
            chatBox.scrollTop = chatBox.scrollHeight; // Автоскролл вниз
        });

        document.getElementById("chat-form").addEventListener("submit", function (e) {
            e.preventDefault();

            let message = document.getElementById("message").value;
            fetch("{{ route('messages.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    content: message
                })
            }).then(response => response.json())
                .then(data => {
                    document.getElementById("message").value = ""; // Очистить поле
                });
        });
    </script>
    <script>
        console.log("Echo object:", window.Echo);
        let userId = {{ auth()->user()->id }};

        window.Echo.private(`chat.${userId}`)
            .listen('MessageSent', (e) => {
                console.log('Новое сообщение:', e.message);
            });

    </script>
@endsection
