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
            ['title' => 'Potassium & Mineral Liquid Brew'],
            [
                'target_waste_type' => 'Banana Peels',
                'required_ingredients' => [
                    ['waste_type' => 'Banana Peels', 'quantity' => 1.0, 'unit' => 'kg'],
                    ['waste_type' => 'Eggshells', 'quantity' => 0.5, 'unit' => 'kg'],
                ],
                'yield_quantity' => 2.50,
                'yield_unit' => 'L',
                'fermentation_days' => 7,
                'npk_ratio' => '0-1-5',
                'primary_nutrients' => 'Potassium (K), Calcium (Ca)',
                'preparation_steps' => 'Chop banana peels finely and crush dried eggshells into a powder. Combine ingredients in 2.5 liters of clean water within a covered bucket. Stir daily for 7 days, then strain out solids.',
                'application_guidance' => 'Dilute 1 part liquid brew with 2 parts water. Apply directly to soil around plant roots once per week during flowering or fruiting stage.',
            ]
        );

        ApprovedFormulation::updateOrCreate(
            ['title' => 'Balanced NPK Soil Amendment'],
            [
                'target_waste_type' => 'Coffee Grounds',
                'required_ingredients' => [
                    ['waste_type' => 'Coffee Grounds', 'quantity' => 1.0, 'unit' => 'kg'],
                    ['waste_type' => 'Banana Peels', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['waste_type' => 'Eggshells', 'quantity' => 0.5, 'unit' => 'kg'],
                ],
                'yield_quantity' => 2.00,
                'yield_unit' => 'kg',
                'fermentation_days' => 14,
                'npk_ratio' => '2-1-3',
                'primary_nutrients' => 'Nitrogen (N), Potassium (K), Calcium (Ca)',
                'preparation_steps' => 'Mix dried spent coffee grounds with finely chopped banana peels and crushed eggshells. Layer with 1 part garden soil or dry compost. Keep moist and turn every 3 days for 14 days.',
                'application_guidance' => 'Incorporate 100g into topsoil around the base of vegetable plants bi-weekly.',
            ]
        );

        ApprovedFormulation::updateOrCreate(
            ['title' => 'Calcium Soil Acid Neutralizer'],
            [
                'target_waste_type' => 'Eggshells',
                'required_ingredients' => [
                    ['waste_type' => 'Eggshells', 'quantity' => 0.5, 'unit' => 'kg'],
                ],
                'yield_quantity' => 0.50,
                'yield_unit' => 'kg',
                'fermentation_days' => 2,
                'npk_ratio' => '0-0-0 (Ca 90%)',
                'primary_nutrients' => 'Calcium Carbonate (CaCO3)',
                'preparation_steps' => 'Boil eggshells for 10 minutes to sterilize. Dry completely under sun or low heat, then grind into a microscopic powder using a pestle or blender.',
                'application_guidance' => 'Sprinkle 2 tablespoons per plant into soil to prevent blossom end rot and regulate soil acidity.',
            ]
        );
    }
}
