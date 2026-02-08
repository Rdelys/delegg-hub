<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedContact extends Model
{
// app/Models/ScrapedContact.php
protected $fillable = ['name', 'email', 'source_url'];
}
