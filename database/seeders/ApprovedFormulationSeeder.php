<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApprovedFormulation;

class ApprovedFormulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApprovedFormulation::updateOrCreate(
            ['target_waste_type' => 'Banana Peels'],
            [
                'preparation_steps' => 'Chop peels finely. Mix with 1 part soil and 1 part water.',
                'application_guidance' => 'Let sit for 7 days before application.',
            ]
        );

        ApprovedFormulation::updateOrCreate(
            ['target_waste_type' => 'Eggshells'],
            [
                'preparation_steps' => 'Wash and dry shells completely. Crush into a fine powder.',
                'application_guidance' => 'Sprinkle directly on soil or mix with compost.',
            ]
        );

        ApprovedFormulation::updateOrCreate(
            ['target_waste_type' => 'Coffee Grounds'],
            [
                'preparation_steps' => 'Mix used coffee grounds with equal parts brown matter (dry leaves or paper) to balance acidity.',
                'application_guidance' => 'Apply thinly.',
            ]
        );
    }
}
