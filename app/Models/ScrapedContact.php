<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedContact extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'email',
        'source_url',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
