<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'view_at' => 'datetime',
    ];
}
