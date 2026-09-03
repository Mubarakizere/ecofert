<?php

namespace App\Services;

use App\Models\ApprovedFormulation;
use App\Models\Experiment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private function getApiKey(): ?string
    {
        return env('GEMINI_API_KEY');
    }

    /**
     * Explain a rule-matched formulation in plain language, detailing NPK/nutrient chemistry.
     */
    public function explainFormulationMatch(ApprovedFormulation $formulation, array $ruleMatch): string
    {
        $apiKey = $this->getApiKey();
        if (empty($apiKey)) {
            return "Formulation Recommendation Summary: The system matched {$formulation->title} based on your available stock. This formula utilizes {$formulation->primary_nutrients} for plant growth.";
        }

        $ingredientsStr = json_encode($formulation->required_ingredients);
        $prompt = "You are an agricultural extension AI assistant for EcoFert.
Explain why the organic fertilizer formulation '{$formulation->title}' is recommended for a household.
Formulation Details:
- Target Waste: {$formulation->target_waste_type}
- Ingredients Required: {$ingredientsStr}
- NPK Ratio: {$formulation->npk_ratio}
- Primary Nutrients: {$formulation->primary_nutrients}
- Preparation Steps: {$formulation->preparation_steps}

User Stock Status: {$ruleMatch['status']} ({$ruleMatch['producible_batches']} batch(es) possible).

Write a clear, professional 2-3 paragraph explanation for a home gardener explaining:
1. The scientific benefit of this nutrient balance (Potassium, Calcium, Nitrogen).
2. Why their recorded food waste fits this recipe.
3. Practical tips for safe preparation.
Do not use emojis. Keep tone scientific and encouraging.";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['temperature' => 0.4]
                ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text') ?? "Explanation generated successfully.";
            }
        } catch (\Exception $e) {
            Log::error('AIService Exception in explainFormulationMatch: ' . $e->getMessage());
        }

        return "Formulation Chemistry: '{$formulation->title}' is rich in {$formulation->primary_nutrients} (NPK: {$formulation->npk_ratio}). Follow the validated extension preparation steps to maximize soil fertility.";
    }

    /**
     * Synthesize a plain-language executive summary of a 4-week plant experiment comparing Organic vs Commercial control.
     */
    public function summarizeGrowthExperiment(Experiment $experiment): string
    {
        $apiKey = $this->getApiKey();
        
        $measurements = $experiment->growthMeasurements()->orderBy('week_number', 'asc')->get();

        if ($measurements->isEmpty()) {
            return "No weekly growth measurements recorded yet for this experiment. Record weekly height, soil pH, and leaf vitality to generate an AI evaluation.";
        }

        $formattedData = $measurements->map(function ($m) {
            return "Week {$m->week_number}: Height={$m->plant_height_cm}cm, Soil pH={$m->soil_pH}, Leaf Vitality={$m->leaf_vitality}";
        })->implode("\n");

        if (empty($apiKey)) {
            $latest = $measurements->last();
            return "Four-Week Experiment Summary for {$experiment->plant_species} ({$experiment->fertilizer_type}): Over {$measurements->count()} recorded week(s), plant height reached {$latest->plant_height_cm}cm with soil pH {$latest->soil_pH} and {$latest->leaf_vitality} leaf vitality.";
        }

        $prompt = "You are an expert agricultural scientist analyzing a 4-week plant growth trial for the EcoFert project.
Plant Species: {$experiment->plant_species}
Fertilizer Group: {$experiment->fertilizer_type}
Recorded Growth Trail Data:
{$formattedData}

Write an executive scientific summary (3 paragraphs) evaluating:
1. Plant growth trajectory and height progression over the weeks.
2. Soil pH stability and leaf vitality trends.
3. Comparative performance conclusions regarding homemade organic fertilizer effectiveness.
Do not use any emojis. Maintain an academic, professional research report tone.";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['temperature' => 0.3]
                ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text') ?? "Trial summary synthesized successfully.";
            }
        } catch (\Exception $e) {
            Log::error('AIService Exception in summarizeGrowthExperiment: ' . $e->getMessage());
        }

        $latest = $measurements->last();
        return "Four-Week Experiment Summary: The {$experiment->plant_species} under {$experiment->fertilizer_type} treatment progressed to {$latest->plant_height_cm}cm by Week {$latest->week_number}, demonstrating steady development with soil pH of {$latest->soil_pH}.";
    }

    /**
     * Answer user questions while maintaining chat history context.
     */
    public function answerQuestion(User $user, string $newMessage): string
    {
        $apiKey = $this->getApiKey();
        if (empty($apiKey)) {
            return "AI Assistant is currently unavailable. Please check API key configuration.";
        }

        $history = $user->aiChatMessages()->orderBy('created_at', 'desc')->take(10)->get()->reverse();

        $contents = [];
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => "You are an expert agricultural extension assistant for EcoFert. Answer questions about organic fertilizer production, preparation, and plant monitoring based strictly on safe, recognized agricultural practices. Do not invent unverified scientific claims. Do not use emojis. Keep answers clear, structured, and concise."]]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => "Understood. I am ready to assist with EcoFert decision support."]]
        ];

        foreach ($history as $msg) {
            $contents[] = [
                'role' => $msg->role,
                'parts' => [['text' => $msg->content]]
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $newMessage]]
        ];

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$apiKey}", [
                    'contents' => $contents,
                    'generationConfig' => ['temperature' => 0.5]
                ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text') ?? "I could not generate a response at this time.";
            }
        } catch (\Exception $e) {
            Log::error('AI Service Exception: ' . $e->getMessage());
        }

        return "An unexpected error occurred while processing your query.";
    }
}
