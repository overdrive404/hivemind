<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function chatList()
    {
        $userId = auth()->id();

        // Получаем список пользователей, с которыми у текущего пользователя есть переписка
        $chats = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
            });

        return view('messages.list', compact('chats'));
    }
    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->input('receiver_id'),
            'content' => $request->input('content'),
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}
