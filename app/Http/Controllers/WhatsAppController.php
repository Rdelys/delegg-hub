<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsAppController extends Controller
{
    public function send(Request $request)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $from = env('TWILIO_WHATSAPP_FROM');

        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            "whatsapp:".$request->phone,
            [
                "from" => $from,
                "body" => $request->message
            ]
        );

        return back()->with('success','Message WhatsApp envoyé');
    }
}