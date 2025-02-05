
@extends('layouts.layout')
@section('content')
    <div class="container mt-4">
        <div class="row">

            @include('layouts.sidebar')


            <!-- Основной контент -->

            <div class="col-md-9">
                <div class="container">
                    <h2>Заявки в друзья</h2>

                    @if($requests->isEmpty())
                        <p>У вас нет новых заявок.</p>
                    @else
                        <ul class="list-group">
                            @foreach($requests as $request)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ route('user.show', ['login' => $request->sender->login]) }}">
                                        {{ $request->sender->name }} ({{ $request->sender->login }})
                                    </a>
                                    <div>
                                        <form action="{{ route('friends.accept', $request->sender->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Принять</button>
                                        </form>
                                        <form action="{{ route('friends.decline', $request->sender->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Отклонить</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection


