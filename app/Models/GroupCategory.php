<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCategory extends Model
{
    use HasFactory;
    protected $table = 'group_categories';
    protected $fillable = [
        'uuid',
        'name_group_category',
        'code_group_category'
    ];
}
