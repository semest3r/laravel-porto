<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
      'uuid',
      'name_product',
      'code_product',
      'created_by',
      'category_id'  
    ];
}
