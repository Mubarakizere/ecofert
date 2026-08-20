<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovedFormulation extends Model
{
    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'formula_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'target_waste_type',
        'preparation_steps',
        'application_guidance',
    ];
}
