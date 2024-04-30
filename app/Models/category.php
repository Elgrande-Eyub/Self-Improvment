<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class category extends Model
{
    use HasFactory,SoftDeletes;

    public function Goals(): HasMany
    {
        return $this->hasMany(Goals::class);
    }

    public function Resources(): HasMany
    {
        return $this->hasMany(Resources::class);
    }
}
