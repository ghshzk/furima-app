<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMessage;
use App\Http\Requests\OrderMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function show(Request $request, $orderId)
    {
        $user = Auth::user();

        $transaction = Order::with(['item', 'seller', 'buyer'])
            ->where(function ($query) use ($user) {
                $query->where('seller_id', $user->id)
                    ->orWhere('buyer_id', $user->id);
            })
            ->findOrFail($orderId);

        $otherUser = $transaction->seller_id === $user->id ? $transaction->buyer : $transaction->seller;

        $otherTransactions = Order::with('item')
            ->where('status', 'trading')
            ->where(function ($query) use ($user) {
                $query->where('seller_id', $user->id)
                    ->orWhere('buyer_id', $user->id);
        })
        ->where('id', '!=' , $orderId)
        ->get();

        $orderMessages = OrderMessage::with('sender')
            ->where('order_id', $orderId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('transaction_chat', compact('transaction', 'otherUser', 'otherTransactions', 'orderMessages'));
    }

    public function sendMessage(OrderMessageRequest $request, $orderId)
    {
        $order = Order::where(function ($query) {
            $query->where('seller_id', Auth::id())
                ->orWhere('buyer_id', Auth::id());
        })
        ->findOrFail($orderId);

        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('public/messages');
        } else {
            $imagePath = null;
        }

        OrderMessage::create([
            'order_id' => $order->id,
            'sender_id' => Auth::id(),
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('transaction.show',['order_id' => $order->id]);
    }
}
