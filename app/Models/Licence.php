<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Licence extends Model
{
    protected $fillable = [
        'client_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /* ===== RELATION ===== */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /* ===== STATUT AUTO ===== */
    public function refreshStatus(): void
    {
        if ($this->end_date && $this->end_date->isPast()) {
            $this->status = 'expire';
        } else {
            $this->status = 'actif';
        }

        $this->save();
    }
}
