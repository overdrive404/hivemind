
@extends('layouts.layout')
@section('content')
    <div class="container mt-4">
        <div class="row">
            @include('layouts.sidebar')

            <!-- Основной контент -->
            <div class="col-md-9">
             @include('layouts.friendsbar')
            </div>
        </div>
    </div>
@endsection


