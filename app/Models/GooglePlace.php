<?php
// app/Models/GooglePlace.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GooglePlace extends Model
{
    protected $fillable = [
        'client_id',
            'nom_scrapping', // ✅ AJOUT

        'name',
        'category',
        'address',
        'phone',
        'website',
        'email',
        'facebook',
        'instagram',
        'linkedin',
        'source_url',
        'rating',
        'reviews_count',
        'website_scraped',
        'website_scraped_at',
        'contact_scraped_at',
            'exported_to_lead',
    'exported_at'
    ];

    protected $casts = [
        'rating' => 'float',
        'reviews_count' => 'integer',
        'website_scraped' => 'boolean',
        'website_scraped_at' => 'datetime',
        'contact_scraped_at' => 'datetime',
            'exported_to_lead' => 'boolean',
    'exported_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}