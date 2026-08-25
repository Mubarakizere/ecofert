<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthMeasurement extends Model
{
    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'measurement_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'experiment_id',
        'week_number',
        'plant_height_cm',
        'soil_pH',
        'leaf_vitality',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'week_number' => 'integer',
            'plant_height_cm' => 'float',
            'soil_pH' => 'float',
        ];
    }

    /**
     * Get the experiment this measurement belongs to.
     */
    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class, 'experiment_id', 'experiment_id');
    }
}
