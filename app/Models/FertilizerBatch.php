<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FertilizerBatch extends Model
{
    use HasFactory;

    protected $primaryKey = 'batch_id';

    protected $fillable = [
        'batch_code',
        'user_id',
        'formulation_id',
        'status',
        'used_ingredients',
        'start_date',
        'estimated_ready_date',
        'notes',
    ];

    protected $casts = [
        'used_ingredients' => 'array',
        'start_date' => 'date',
        'estimated_ready_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function formulation(): BelongsTo
    {
        return $this->belongsTo(ApprovedFormulation::class, 'formulation_id', 'formula_id');
    }

    public function isReady(): bool
    {
        return $this->status === 'ready' || ($this->status === 'aging' && now()->startOfDay()->gte($this->estimated_ready_date));
    }
}
