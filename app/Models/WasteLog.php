<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteLog extends Model
{
    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'log_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'waste_type',
        'quantity',
        'unit',
        'date_recorded',
        'transaction_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'float',
            'date_recorded' => 'date',
        ];
    }

    /**
     * Get the user who created this waste log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
