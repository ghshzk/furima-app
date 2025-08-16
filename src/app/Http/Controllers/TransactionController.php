<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMessage;
use App\Models\OrderReview;
use App\Http\Requests\OrderMessageRequest;
use App\Http\Requests\ReviewRequest;
use App\Mail\TransactionCompletedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    public function show(Request $request, $orderId)
    {
        $user = Auth::user();

        $transaction = Order::with(['item', 'seller', 'buyer', 'messages.sender'])
            ->where(function ($query) use ($user) {
                $query->where('seller_id', $user->id)
                    ->orWhere('buyer_id', $user->id);
            })
            ->findOrFail($orderId);

        $transaction->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $otherUser = $transaction->seller_id === $user->id ? $transaction->buyer : $transaction->seller;

        $otherTransactions = Order::with('item')
            ->where('status', 'trading')
            ->where(function ($query) use ($user) {
                $query->where('seller_id', $user->id)
                    ->orWhere('buyer_id', $user->id);
        })
        ->where('id', '!=' , $orderId)
        ->get()
        ->sortByDesc(function ($order) {
            return $order->latestMessage ? $order->latestMessage->created_at : $order->updated_at;
        });

        $orderMessages = $transaction->messages->sortBy('created_at');

        $showReviewModal = $transaction->needsReviewBy($user);

        return view('transaction_chat', compact('transaction', 'otherUser', 'otherTransactions', 'orderMessages', 'showReviewModal'));
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

    public function updateMessage(Request $request, $messageId)
    {
        $orderMessage = OrderMessage::findOrFail($messageId);

        if ($orderMessage->sender_id !== auth()->id()) {
            abort(403);
        }

        $orderMessage->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('transaction.show', ['order_id' => $orderMessage->order_id]);
    }

    public function deleteMessage(Request $request, $messageId)
    {
        $orderMessage = OrderMessage::findOrFail($messageId);

        if ($orderMessage->sender_id !== auth()->id()) {
            abort(403);
        }

        if($orderMessage->image_path){
                Storage::delete($orderMessage->image_path);
            }

        $orderMessage->delete();

        return redirect()->route('transaction.show', ['order_id' => $orderMessage->order_id]);
    }

    public function completeByBuyer($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (auth()->id() !== $order->buyer_id) {
            abort(403);
        }

        if ($order->status !== 'trading') {
            return redirect()->route('transaction.show', ['order_id' => $order->id]);
        }

        $order->status = 'pending_complete';
        $order->buyer_rated = false;
        $order->save();

        Mail::to($order->seller->email)->send(
            new TransactionCompletedMail($order->item, $order->buyer)
        );

        return redirect()->route('transaction.show',['order_id' => $order->id]);
    }

    public function review(ReviewRequest $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $userId = auth()->id();

        OrderReview::create([
            'order_id' => $order->id,
            'reviewer_id' => $userId,
            'reviewee_id' => $order->buyer_id === $userId ? $order->seller_id : $order->buyer_id,
            'rating' => $request->rating,
        ]);

        if ($order->buyer_id === $userId) {
            $order->buyer_rated = true;
        } elseif ($order->seller_id === $userId) {
            $order->seller_rated = true;
        }

        if ($order->buyer_rated && $order->seller_rated){
            $order->status = 'completed';
            $order->completed_at = now();
        }

        $order->save();

        return redirect()->route('top');
    }
}
