
<!-- Сайдбар -->
<div class="col-md-3">
    <!-- Меню -->
    <div class="sidebar collapse d-md-block" id="sidebarNav">
        <h5>Меню</h5>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="{{ route('user.show', ['login' => auth()->user()->login]) }}" class="nav-link text-light">Моя страница</a></li>
            <li class="nav-item"><a href="{{route('friends.show')}}" class="nav-link text-light">Друзья</a></li>
            <li class="nav-item"><a href="{{route('friends.requests')}}" class="nav-link text-light">Заявки в друзья </a></li>
            <li class="nav-item"><a href="{{route('messages.show')}}" class="nav-link text-light">Сообщения</a></li>
            <li class="nav-item"><a href="{{route('home')}}" class="nav-link text-light">Лента</a></li>
            <li class="nav-item"><a href="{{route('settings', auth()->user()->login)}}" class="nav-link text-light">Настройки</a></li>
        </ul>
    </div>


</div>
