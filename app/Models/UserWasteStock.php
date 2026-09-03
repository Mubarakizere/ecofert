<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWasteStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'waste_type',
        'quantity',
        'unit',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
