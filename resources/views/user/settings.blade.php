
@extends('layouts.layout')
@section('content')
    <div class="container mt-4">
        <div class="row">

    @include('layouts.sidebar')
            <div class="col-md-9">
                <h2>Настройки профиля</h2>

                <form action="{{route('settings.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Поле для имени -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Поле для логина -->
                    <div class="mb-3">
                        <label for="login" class="form-label">Логин</label>
                        <input id="login" type="text" class="form-control @error('login') is-invalid @enderror"
                               name="login" value="{{ old('login', auth()->user()->login) }}" required>
                        @error('login')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Поле для статуса -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Статус</label>
                        <input id="status" type="text" class="form-control @error('status') is-invalid @enderror"
                               name="status" value="{{ old('status', auth()->user()->status) }}" required>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Аватарка -->
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Аватарка</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" onchange="previewImage(event, 'avatarPreview')">
                        <div class="mt-2">
                            <img id="avatarPreview" src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://via.placeholder.com/100' }}"
                                 alt="Предпросмотр аватарки"  class="rounded-circle border" style="width: 100px; height: 100px; top: -50px; left: 20px;">
                        </div>
                        @error('avatar')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Шапка профиля -->
                    <div class="mb-3">
                        <label for="header" class="form-label">Изображение шапки</label>
                        <input type="file" class="form-control" id="header" name="header" accept="image/*" onchange="previewImage(event, 'headerPreview')">
                        <div class="mt-2">
                            <img id="headerPreview" src="{{ auth()->user()->header ? asset('storage/' . auth()->user()->header) : 'https://via.placeholder.com/600x200' }}"
                                 alt="Предпросмотр шапки" class="w-100" style="height: 200px; object-fit: cover;">
                        </div>
                        @error('header')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>


        </div>
    </div>

    <script>
        function previewImage(event, id) {
            const reader = new FileReader();
            reader.onload = function(){
                const img = document.getElementById(id);
                img.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
