<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('users', compact('users'));
    }

    public function chat($receiverId)
    {
        $receiver = User::find($receiverId);

        $messages = Message::where(function ($query) use ($receiverId){
            $query->where('sender_id', Auth::id())->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)->where('receiver_id', Auth::id());
        })->get();

        return view('chat', compact('receiver', 'messages'));
    }

    public function sendMessage(Request $request, $receiverId)
    {
        // save message to DB
        $message = Message::create([
            'sender_id'     => Auth::id(),
            'receiver_id'   => $receiverId,
            'message'       => $request['message']
        ]);
        
        // Fire the message event
        broadcast(new MessageSent($message))->toOthers();
        
        return response()->json(['status' => 'Message sent!']);
    }

    public function typing()
    {
        // Fire the typing event
        broadcast(new UserTyping(Auth::id()))->toOthers();
        return response()->json(['status' => 'typing broadcasted!']);
    }

    public function setOnline()
    {
        Cache::put('user-is-online-' . Auth::id(), true, now()->addMinutes(5));
        return response()->json(['status' => 'Online']);
    }

    public function setOffline()
    {
        Cache::forget('user-is-online-' . Auth::id());
        return response()->json(['status' => 'Offline']);
    }

}
