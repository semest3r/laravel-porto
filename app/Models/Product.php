<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
  use HasFactory;
  protected $fillable = [
    'uuid',
    'name_product',
    'code_product',
    'created_by',
    'category_id',
  ];

  public function productImg(): HasMany
  {
    return $this->hasMany(productImg::class, 'product_id', 'id');
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class, 'category_id', 'id');
  }
}
