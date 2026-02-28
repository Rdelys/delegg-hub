<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSmtp extends Model
{
    protected $fillable = [
        'client_id',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'from_name',
        'is_active',
    ];

    protected $casts = [
        'password' => 'encrypted',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}