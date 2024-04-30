<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressLogs extends Model
{
    use HasFactory,SoftDeletes;

    public function Goals(): BelongsTo
    {
        return $this->BelongsTo(Goals::class);
    }
}
