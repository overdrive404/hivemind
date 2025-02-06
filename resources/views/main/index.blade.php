
@extends('layouts.layout')
@section('content')
    <div class="container mt-4">
        <div class="row">

            @include('layouts.sidebar')


            <!-- Основной контент -->
            <div class="col-md-9">
                @foreach($posts as $post)
                    <div class="card mb-3">
                        <div class="card-body">

                            @if( isset($post->user))
                           <a href="{{route('user.show', $post->user->login)}}">
                               <img src="{{asset('storage/' . $post->user->avatar)}}"  class="rounded-circle border" style="width: 50px; height: 50px; top: -50px; left: 20px;" alt="Аватар">
                            <h5>{{ $post->user->name }}</h5>
                           </a>
                            @endif

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
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        {{ $posts->links('pagination::bootstrap-4') }}
    </div>
@endsection


