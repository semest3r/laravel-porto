<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'uuid',
        'name_category',
        'code_category',
        'group_category_id'
    ];

    public function groupCategory(): BelongsTo
    {
        return $this->belongsTo(GroupCategory::class, 'group_category_id', 'id');
    }
}
