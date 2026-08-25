<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experiment extends Model
{
    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'experiment_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'fertilizer_type',
        'plant_species',
        'start_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
        ];
    }

    /**
     * Get the user who owns this experiment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the growth measurements for this experiment.
     */
    public function growthMeasurements(): HasMany
    {
        return $this->hasMany(GrowthMeasurement::class, 'experiment_id', 'experiment_id');
    }
}
