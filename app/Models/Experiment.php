<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'fertilizer_type',
    ];

    /**
     * Get the growth measurements for this experiment.
     */
    public function growthMeasurements(): HasMany
    {
        return $this->hasMany(GrowthMeasurement::class, 'experiment_id', 'experiment_id');
    }
}
