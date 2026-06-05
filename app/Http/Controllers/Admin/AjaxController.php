<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function getOrders(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $query = Order::query()
            ->with('student.user')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->select(
                'orders.id',
                'orders.ordered_at',
                'orders.student_id',
                DB::raw('SUM(order_items.price) as total')
            )
            ->whereMonth('orders.ordered_at', $month)
            ->whereYear('orders.ordered_at', $year)
            ->groupBy('orders.id', 'orders.ordered_at', 'orders.student_id');

        if (auth()->user()->student !== null) {
            $query->where('orders.student_id', auth()->user()->student->id);
        }

        $orders = $query
            ->orderByDesc('orders.ordered_at')
            ->get();

        $mappedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'date' => DateHelper::format($order->ordered_at),
                'total' => $order->total ?? 0,
                'student' => $order->student->user->name . ' ' . $order->student->user->surname,
            ];
        });

        return response()->json([
            'html' => view('admin.partials.order-rows', [
                'orders' => $mappedOrders,
            ])->render(),
            'total' => $mappedOrders->sum('total'),
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'chat_id' => 'required|integer|exists:chats,id',
            'message' => 'required|string',
        ]);

        $message = ChatMessage::create([
            'chat_id' => $validated['chat_id'],
            'message' => $validated['message'],
            'sender_role' => $request->user()->role === 'admin' ? 1 : 0,
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
