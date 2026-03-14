<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class SmsController extends Controller
{
    public function send(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'message' => 'required'
    ]);

    $sid = env('TWILIO_SID');
    $token = env('TWILIO_TOKEN');

    $twilio = new Client($sid, $token);

    $twilio->messages->create(
        $request->phone,
        [
            'from' => 'DELEGG',
            'body' => $request->message
        ]
    );

    return back()->with('success', 'SMS envoyé avec succès');
}
}