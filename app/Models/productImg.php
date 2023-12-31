<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productImg extends Model
{
    use HasFactory;
    protected $table = 'product_img';
    protected $fillable = [
        'filename',
        'path',
        'type_file',
        'product_id'
    ];
}
