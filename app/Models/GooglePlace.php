<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GooglePlace extends Model
{
    protected $fillable = [
    'client_id',
    'name',
    'category',
    'address',
    'phone',
    'website',
    'email',
    'website_scraped',
    'website_scraped_at',
];

protected $casts = [
    'website_scraped' => 'boolean',
];

}
