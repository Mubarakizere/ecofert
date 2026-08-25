<?php

namespace App\Services;

use App\Models\ApprovedFormulation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Get the best fertilizer formulation recommendation from an AI API (e.g., Gemini).
     * If the API key is not set or the API fails, it falls back to a rule-based match.
     *
     * @param string $wasteType
     * @param float|null $quantity
     * @param string|null $unit
     * @return ApprovedFormulation|null
     */
    public function getRecommendation(string $wasteType, ?float $quantity, ?string $unit): ?ApprovedFormulation
    {
        $formulations = ApprovedFormulation::all();
        if ($formulations->isEmpty()) {
            return null;
        }

        $apiKey = env('GEMINI_API_KEY');
        
        if (empty($apiKey)) {
            // Fallback to basic logic if no API key is set
            Log::info('No GEMINI_API_KEY found, falling back to rule-based recommendation.');
            return $this->fallbackRecommendation($wasteType);
        }

        try {
            $prompt = $this->buildPrompt($wasteType, $quantity, $unit, $formulations);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                $result = json_decode($text, true);
                if (isset($result['formulation_id'])) {
                    return ApprovedFormulation::find($result['formulation_id']);
                }
            } else {
                Log::error('AI API Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('AI Service Exception: ' . $e->getMessage());
        }

        // Fallback if AI fails or returns invalid response
        return $this->fallbackRecommendation($wasteType);
    }

    private function buildPrompt(string $wasteType, ?float $quantity, ?string $unit, $formulations): string
    {
        $qtyStr = $quantity ? "{$quantity} {$unit}" : "unknown quantity";
        
        $options = $formulations->map(function($f) {
            return [
                'id' => $f->formula_id,
                'target_waste' => $f->target_waste_type,
                'instructions' => $f->preparation_steps . ' - ' . $f->application_guidance,
            ];
        })->toJson();

        return "You are an expert agricultural AI assistant for EcoFert.
A household has just logged the following food waste:
- Waste Type: {$wasteType}
- Quantity: {$qtyStr}

Here is the list of available approved organic fertilizer formulations in JSON format:
{$options}

Your task: Analyze the waste logged and pick the BEST formulation_id for it based on the target_waste. 
Respond ONLY with a valid JSON object containing the chosen 'formulation_id' (integer), and a brief 'reason' (string) for your choice. Do not wrap it in markdown block. Example:
{\"formulation_id\": 1, \"reason\": \"Because banana peels are rich in potassium...\"}";
    }

    private function fallbackRecommendation(string $wasteType): ?ApprovedFormulation
    {
        return ApprovedFormulation::where('target_waste_type', $wasteType)->first();
    }
}
