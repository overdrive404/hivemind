@extends('layouts.layout')

@section('content')
    <div class="container">
        <h3>Чат</h3>
        <div id="chat-box" style="height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;">
            @foreach ($messages as $message)
                <div class="mb-2">
                    <strong>{{ $message->sender_id == auth()->id() ? 'Вы' : 'Друг' }}:</strong> {{ $message->content }}
                </div>
            @endforeach
        </div>

        <form id="message-form">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $receiverId }}">
            <div class="input-group mt-3">
                <input type="text" id="message-content" name="content" class="form-control" placeholder="Введите сообщение..." required>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script>
        document.getElementById('message-form').addEventListener('submit', function(e) {
            e.preventDefault();
            let content = document.getElementById('message-content').value;

            axios.post("{{ route('chat.send') }}", {
                receiver_id: "{{ $receiverId }}",
                content: content
            }).then(response => {
                document.getElementById('message-content').value = '';
            }).catch(error => {
                console.error(error);
            });
        });

        let chatBox = document.getElementById('chat-box');
        let pusher = new Pusher("your-pusher-key", { cluster: "eu" });
        let channel = pusher.subscribe("chat-channel");

        channel.bind("message.sent", function(data) {
            let newMessage = document.createElement("div");
            newMessage.classList.add("mb-2");
            newMessage.innerHTML = `<strong>${data.message.sender_id == "{{ auth()->id() }}" ? 'Вы' : 'Друг'}:</strong> ${data.message.content}`;
            chatBox.appendChild(newMessage);
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>
@endpush
