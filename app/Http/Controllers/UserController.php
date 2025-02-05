<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show($login)
    {
        $user = User::where('login', $login)->firstOrFail();
        // Получаем посты авторизованного пользователя
        $posts = Post::where('user_id', $user->id)->latest()->paginate(10);
        if (request()->wantsJson()) {
            return $posts;
        }
        // Возвращаем представление с постами
        return view('user.page', compact('posts', 'user'));
    }

    public function settings($login){
        return view('user.settings');
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
}
