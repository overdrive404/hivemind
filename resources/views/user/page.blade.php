
@extends('layouts.layout')
@section('content')
<div class="container mt-4">
    <div class="row">

 @include('layouts.sidebar')
        <!-- Основной контент -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-0">
                    <img src="{{asset('storage/' . $user->header)}}" class="w-100" style="height: 200px; object-fit: cover;" alt="Обложка профиля">
                </div>
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <img src="{{asset('storage/' . $user->avatar)}}"  class="rounded-circle border position-absolute" style="width: 100px; height: 100px; top: -50px; left: 20px;" alt="Аватар">
                        <div class="ms-5 ps-5">
                            <h3 class="mb-0">{{$user->name}}</h3>
                            <p class="text-muted">{{'@' . $user->login}}</p>
                        </div>
                    </div>
                    <p>{{$user->status}}</p>

                    @if(auth()->id() !== $user->id)
                        <a href="{{ url('/chat/' . $user->id) }}" class="btn btn-primary">Написать</a>
                    @else  <a class="btn btn-primary btn-sm" href="{{route('settings', $user->login)}}"> Редактировать профиль </a>

                    @endif
                </div> @include('layouts.friendsbar')
            </div>
            @if(auth()->id() === $user->id)
            <div class="card mt-3">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">Текст поста</label>
                            <textarea class="form-control" id="content" name="text" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Прикрепить изображения (до 10)</label>
                            <input class="form-control" type="file" id="images" name="images[]" accept="image/*" multiple>
                            <div id="preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                        </div>

                        <button type="submit" class="btn btn-primary">Опубликовать</button>
                    </form>
                </div>
            </div>
            @endif
                    @foreach($posts as $post)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>{{ $post->user->name }}</h6>
                                <p class="post-text">{{ $post->text }}</p>
                                @if($post->images->count() > 0)
                                    <div class="post-images-container">
                                        @foreach($post->images as $image)
                                            @if(Storage::disk('public')->exists($image->path))
                                                <a href="{{ asset('storage/' . $image->path) }}" data-lightbox="post">
                                                    <img src="{{ asset('storage/' . $image->path) }}" class="post-image">
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                <p><small class="text-muted">{{ $post->created_at->diffForHumans() }}</small></p>
                                @if(auth()->id() === $user->id)
                                <button class="btn btn-sm btn-warning edit-post-btn" data-post-id="{{ $post->id }}">Редактировать</button>
                                <form action="{{route('destroy', $post->id)}}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border-0 bg-transparent">
                                        <i class="fas fa-trash text-danger"> </i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
            <!-- Пагинация -->
            <div class="d-flex justify-content-center">
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection


