<?php

namespace App\Models;

use App\priority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use HasFactory,SoftDeletes;

    protected $casts = [
        'priority' =>  priority::class,
    ];
}
