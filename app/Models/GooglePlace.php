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
    'rating',
    'reviews_count',
    'website_scraped',
    'website_scraped_at',
];

protected $casts = [
    'rating' => 'float',
    'reviews_count' => 'integer',
    'website_scraped' => 'boolean',
];


}
