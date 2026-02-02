<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'company',
        'password',
        'status',
        'role',
    ];


    protected $hidden = [
        'password',
    ];

    public function licences()
    {
        return $this->hasMany(Licence::class);
    }

}
