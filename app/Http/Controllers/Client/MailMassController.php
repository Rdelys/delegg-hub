<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ClientSmtp;
use App\Models\ClientMailLog;
use App\Services\ClientMailService;
use App\Mail\ClientDynamicMail;

class MailMassController extends Controller
{
    public function index()
    {
        return view('client.mails.plus');
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'emails'  => 'required',
            'message' => 'required',
        ]);

        $clientId = session('client.id');

        $smtp = ClientSmtp::where('client_id', $clientId)
            ->where('is_active', 1)
            ->first();

        if (!$smtp) {
            return back()->with('error', 'SMTP non configuré.');
        }

        try {

            // ✅ On configure le mailer dynamique
            ClientMailService::configure($smtp);

            $emails = explode(',', $request->emails);

            foreach ($emails as $email) {

                $email = trim($email);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                Mail::mailer('client')
                    ->to($email)
                    ->send(new ClientDynamicMail(
                        $request->subject,
                        $request->message
                    ));

                // Log success
                ClientMailLog::create([
                    'client_id' => $clientId,
                    'to'        => $email,
                    'subject'   => $request->subject,
                    'body'      => $request->message,
                    'status'    => 'success'
                ]);
            }

            return back()->with('success', 'Emails envoyés avec succès.');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}