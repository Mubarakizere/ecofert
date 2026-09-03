<?php

namespace App\Http\Controllers\Household;

use App\Http\Controllers\Controller;
use App\Models\AiChatMessage;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIAssistantController extends Controller
{
    /**
     * Display chat view or return JSON for AJAX requests.
     */
    public function chatHistory(Request $request)
    {
        $messages = Auth::user()->aiChatMessages()->orderBy('created_at', 'asc')->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($messages);
        }

        return view('household.chat.index', compact('messages'));
    }

    /**
     * Send a message to the AI Assistant.
     */
    public function sendMessage(Request $request, AIService $aiService)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Save user message
        AiChatMessage::create([
            'user_id' => Auth::id(),
            'role'    => 'user',
            'content' => $validated['message']
        ]);

        // Get AI Response
        $response = $aiService->answerQuestion(Auth::user(), $validated['message']);

        // Save AI message
        $aiMessage = AiChatMessage::create([
            'user_id' => Auth::id(),
            'role'    => 'model',
            'content' => $response
        ]);

        return response()->json([
            'message' => $aiMessage
        ]);
    }
}
