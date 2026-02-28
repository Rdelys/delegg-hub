<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientImap;
use App\Services\ClientImapService;

class ClientImapController extends Controller
{
   public function index()
{
    $clientId = session('client.id');
    $imap = ClientImap::where('client_id', $clientId)->first();

    $messages = collect();
    $stats = [
        'unread' => 0,
        'total' => 0,
        'attachments' => 0,
        'week' => 0,
    ];

    if ($imap && $imap->last_test_success) {
        try {

            $client = ClientImapService::connect($imap);
            $folder = $client->getFolder($imap->folder);

            $messages = $folder->messages()
                ->all()
                ->limit(20)
                ->setFetchOrder("desc")
                ->get();

            $stats['total'] = $folder->messages()->count();
            $stats['unread'] = $folder->messages()->unseen()->count();

            $stats['attachments'] = $messages
                ->filter(fn($m) => $m->getAttachments()->count() > 0)
                ->count();

            $stats['week'] = $messages
                ->filter(function ($m) {
                    if (!$m->getDate()) return false;

                    $date = \Carbon\Carbon::parse(
                        $m->getDate()->toDateTime()
                    );

                    return $date->greaterThan(now()->subWeek());
                })
                ->count();

            $imap->update([
                'last_sync_at' => now()
            ]);

        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    return view('client.mails.recus', compact('imap','messages','stats'));
}
    public function save(Request $request)
    {
        $clientId = session('client.id');

        ClientImap::updateOrCreate(
            ['client_id'=>$clientId],
            [
                'host'=>$request->host,
                'port'=>$request->port,
                'encryption'=>$request->encryption,
                'username'=>$request->username,
                'password'=>encrypt($request->password),
                'folder'=>$request->folder,

                'last_test_success'=>null,
                'last_tested_at'=>null,
            ]
        );

        return back()->with('success','IMAP enregistré');
    }

    public function test()
    {
        $clientId = session('client.id');
        $imap = ClientImap::where('client_id',$clientId)->first();

        try {

            ClientImapService::connect($imap);

            $imap->update([
                'last_test_success'=>true,
                'last_tested_at'=>now(),
            ]);

            return back()->with('success','Connexion IMAP réussie');

        } catch (\Exception $e) {

            $imap->update([
                'last_test_success'=>false,
                'last_tested_at'=>now(),
            ]);

            return back()->with('error','Erreur IMAP : '.$e->getMessage());
        }
    }

    public function sync()
{
    $clientId = session('client.id');
    $imap = ClientImap::where('client_id', $clientId)->first();

    if (!$imap || !$imap->last_test_success) {
        return back()->with('error', 'IMAP non configuré ou non testé.');
    }

    try {

        $client = ClientImapService::connect($imap);
        $folder = $client->getFolder($imap->folder);

        // Juste pour forcer la récupération
        $folder->messages()->limit(1)->get();

        $imap->update([
            'last_sync_at' => now()
        ]);

        return back()->with('success', 'Synchronisation réussie.');

    } catch (\Exception $e) {

        return back()->with('error', 'Erreur synchronisation : '.$e->getMessage());
    }
}

}