<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBlastEmail extends Model
{
    use HasFactory;
    protected $table = 'log_blast_table';
    protected $fillable = [
        'uuid',
        'email',
        'status'
    ];
}
