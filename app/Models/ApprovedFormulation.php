<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovedFormulation extends Model
{
    use HasFactory;

    protected $primaryKey = 'formula_id';

    protected $fillable = [
        'title',
        'target_waste_type',
        'required_ingredients',
        'yield_quantity',
        'yield_unit',
        'fermentation_days',
        'npk_ratio',
        'primary_nutrients',
        'preparation_steps',
        'application_guidance',
    ];

    protected $casts = [
        'required_ingredients' => 'array',
        'yield_quantity' => 'decimal:2',
        'fermentation_days' => 'integer',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(FertilizerBatch::class, 'formulation_id', 'formula_id');
    }
}
