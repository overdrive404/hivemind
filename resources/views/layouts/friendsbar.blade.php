<!-- Сайдбар -->
<div class="col-md-3">
    <!-- Меню -->
    <div class="sidebar collapse d-md-block" id="sidebarNav">
        @if(auth()->id() !== $user->id)
            @if(auth()->user()->friends->contains($user))
                <!-- Уже друзья -->
                <form action="{{ route('friends.remove', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Удалить из друзей</button>
                </form>
            @elseif(auth()->user()->sentFriendRequests->contains('friend_id', $user->id))
                <!-- Запрос отправлен -->
                <button class="btn btn-secondary" disabled>Запрос отправлен</button>
            @elseif(auth()->user()->receivedFriendRequests->contains('user_id', $user->id))
                <!-- Запрос получен -->
                <form action="{{ route('friends.accept', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Принять запрос</button>
                </form>
                <form action="{{ route('friends.decline', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning">Отклонить</button>
                </form>
            @else
                <!-- Отправить запрос -->
                <form action="{{ route('friends.request', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Добавить в друзья</button>
                </form>
            @endif
        @endif

    </div>
</div>
