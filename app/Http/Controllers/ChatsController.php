<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Message;
use Illuminate\Support\Facades\Auth;
use phpseclib\Crypt\RSA;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
        $user = Auth::user();
        $messagem = Message::where('messages.created_at', '>=', $user->last_login_at)->with('user')
            ->get();
        return $messagem;
    }

    /**
     * Persist message to database
     *
     * @param Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $mes = $request->all();
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $mes['mes']
        ]);
        broadcast(new MessageSent($user, $message))->toOthers();
        return ['status' => 'Message Sent!'];
    }

    public function sendkey(Request $request)
    {
        $key = $request->all();
        $rsa = new RSA();
        $rsa->setHash('sha1');
        $rsa->loadKey($key['public_key']);
        $ket = base64_encode($rsa->encrypt('12345'));
        return $ket;

    }
}
