
@extends('layouts.layout')
@section('content')
    <div class="container mt-4">
        <div class="row">

            @include('layouts.sidebar')


            <!-- Основной контент -->
            <div class="col-md-9">
                <h2>Сообщения</h2>
                <div class="list-group">
                    @foreach($chats as $userId => $messages)
                        @php
                            $chatUser = $messages->first()->sender_id == auth()->id() ? $messages->first()->receiver : $messages->first()->sender;
                        @endphp
                        <a href="{{ route('chat', $chatUser->id) }}" class="list-group-item list-group-item-action">
                            <strong>{{ $chatUser->name }}</strong>
                            <br>
                            <small>Последнее сообщение: {{ $messages->last()->content }}</small>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
