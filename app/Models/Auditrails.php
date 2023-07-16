<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditrails extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_user',
        'user_id',
        'activity'
    ];
}
