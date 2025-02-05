<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function show(){
        $user = Auth::user();
        return view('friends.list', compact('user'));
    }


    // Отправить заявку в друзья
    public function sendRequest($friend_id)
    {
        $user = Auth::user();

        // Проверка, не отправлена ли уже заявка
        if (Friend::where('user_id', $user->id)->where('friend_id', $friend_id)->exists()) {
            return back()->with('error', 'Заявка уже отправлена.');
        }

        Friend::create([
            'user_id' => $user->id,
            'friend_id' => $friend_id,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Заявка отправлена!');
    }

    // Принять заявку
    public function acceptRequest($senderId)
    {
        $user = Auth::user(); // Текущий пользователь (кто принимает заявку)
        $friendRequest = Friend::where('user_id', $senderId)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        // Обновляем статус на "accepted"
        $friendRequest->update(['status' => 'accepted']);

        // Создаём запись в обратную сторону (чтобы оба были друзьями)
        Friend::create([
            'user_id' => $user->id,
            'friend_id' => $senderId,
            'status' => 'accepted',
        ]);

        return redirect()->route('friends.requests')->with('success', 'Вы приняли заявку в друзья.');
    }


    // Отклонить заявку
    public function declineRequest($friend_id)
    {
        Friend::where('user_id', $friend_id)
            ->where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->delete();

        return back()->with('success', 'Заявка отклонена.');
    }

    // Удалить из друзей
    public function removeFriend($friend_id)
    {
        Friend::where(function ($query) use ($friend_id) {
            $query->where('user_id', Auth::id())->where('friend_id', $friend_id);
        })->orWhere(function ($query) use ($friend_id) {
            $query->where('user_id', $friend_id)->where('friend_id', Auth::id());
        })->delete();

        return back()->with('success', 'Друг удалён.');
    }

    public function friendRequests(){
        $user = Auth::user();

        // Получаем заявки, где текущий пользователь — получатель
        $requests = $user->receivedFriendRequests()->with('sender')->get();

        return view('friends.requests', compact('requests'));
    }
}
