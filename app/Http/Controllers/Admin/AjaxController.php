<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ChatSenderRole;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Review;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'chat_id' => 'required|integer|exists:chats,id',
            'message' => 'required|string',
        ]);

        $chat = Chat::findOrFail($validated['chat_id']);

        $this->authorize('sendMessage', $chat);

        $message = $chat->messages()->create([
            'message' => $validated['message'],
            'sender_role' => $request->user()->role === 'admin' ? ChatSenderRole::ADMIN->value : ChatSenderRole::STUDENT->value,
            'sent_at' => now(),
        ]);

        broadcast(new MessageSent($message));

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function storeFeedback(Request $request)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        Review::updateOrCreate(
            ['student_id' => $request->user()->student->id],
            ['rating' => $validated['rating']]
        );

        return response()->json([
            'rating' => $validated['rating'],
        ]);
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'review' => ['nullable', 'string', 'max:500'],
        ]);

        Review::updateOrCreate(
            ['student_id' => $request->user()->student->id],
            ['review' => $validated['review'] ?? null]
        );

        return response()->json([
            'review' => $validated['review'] ?? '',
        ]);
    }
}
