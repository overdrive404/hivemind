<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        // Получаем посты авторизованного пользователя
        $posts = Post::where('user_id', Auth::id())->latest()->paginate(5);
        if (request()->wantsJson()) {
            return $posts;
        }
        $user = auth()->user();
        // Возвращаем представление с постами
        return view('userpage.user', compact('posts', 'user'));
    }

    public function settings(){

        return view('userpage.settings');

    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        // Валидация
        $request->validate([
            'name' => 'required|string',
            'login' => [
                'required',
                'string',
                'max:255',
                'unique:users,login,' . $user->id,
                'regex:/^[a-zA-Z0-9_]+$/',
            ],
            'status'=> 'required|string',
            'avatar' => 'nullable|image|max:2048',
            'header' => 'nullable|image|max:4096',
        ]);

        // Обновление имени
        $user->name = $request->name;
        $user->status = $request->status;

        // Обновление логина
        $user->login = $request->login;

        // Обновление аватарки
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // Обновление шапки
        if ($request->hasFile('header')) {
            if ($user->header) {
                Storage::delete($user->header);
            }
            $user->header = $request->file('header')->store('headers', 'public');
        }

        $user->save();

        return redirect()->back()->with('success', 'Настройки обновлены!');
    }

    public function store(Request $request)
    {

        // Валидация данных
        $request->validate([
            'text' => 'required|string',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|max:2048', // Ограничение на изображения
        ]);

        // Создание поста
        $post = new Post();
        $post->text = $request->input('text');
        $post->user_id = Auth::id();
        $post->save();

        // Сохранение изображений, если они есть
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts_images', 'public');
                PostImage::create([
                    'post_id' => $post->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('home')->with('success', 'Пост опубликован!');
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post->text = $request->text;
        $post->save();

        // Удаление старых изображений
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imagePath) {
                $relativePath = str_replace(asset('storage/'), '', $imagePath);
                Storage::delete('public/' . $relativePath);
                $post->images()->where('path', $relativePath)->delete();
            }
        }

        // Добавление новых изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                $post->images()->create(['path' => $path]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function loadMore(Request $request)
    {
        $posts = Post::where('user_id', Auth::id())->latest()->paginate(5); // Количество постов за один раз
        if ($request->ajax()) {
            return response()->json($posts);
        }
        return view('userpage.user', compact('posts'));
    }
}
