
@extends('layouts.layout')
@section('content')
    <div class="container mt-4">
        <div class="row">
            @include('layouts.sidebar')

            <!-- Основной контент -->
            <div class="col-md-9">

                <h3>Друзья ({{ $user->friends->count() }})</h3>
                <ul>
                    @forelse($user->friends as $friend)
                        <li><a href="{{ route('user.show', $friend->login) }}">{{ $friend->name}}</a></li>
                    @empty
                        <p>Нет друзей</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection


