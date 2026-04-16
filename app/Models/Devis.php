<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $fillable = [
        'client_id',
        'tiime_id',
        'label',
        'date_emission',
        'date_validite',
        'note',
        'address',
        'city',
        'postal_code',
        'country',
        'tva_exoneration',
        'lines',
        'total_ht',
        'total_tva',
        'total_ttc'
    ];

    protected $casts = [
        'lines' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(\App\Models\ClientInvoice::class);
    }
}