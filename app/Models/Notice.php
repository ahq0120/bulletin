<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['title','author','published_at','due_date','content'];

    protected $casts = [
        'published_at' => 'date',
        'due_date'     => 'date',
    ];

}
