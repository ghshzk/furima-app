<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show(Request $request, $itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);
        $shippingAddress = session('shipping_address', "{$user->postcode}\n{$user->address}\n{$user->building}");
        $payment_method = session('payment_method', '未選択');

        return view('purchase',compact('user', 'item', 'shippingAddress', 'payment_method'));
    }

    /*支払い方法の選択反映 */
    public function updatePayment(Request $request, $itemId)
    {
        session(['payment_method' => $request->payment_method]);
        session()->save();
        return redirect()->route('purchase.show', ['item_id' => $itemId]);
    }

    /* 住所変更ページ表示 */
    public function edit($itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);

        return view('address', compact('user', 'item'));
    }

    /* 住所の更新 */
    public function update(AddressRequest $request, $itemId)
    {
        $shippingAddress = $request->postcode . "\n" . $request->address . "\n" . $request->building;
        session(['shipping_address' => $shippingAddress]);
        session()->save();

        return redirect()->route('purchase.show', ['item_id' => $itemId]);
    }


    public function order(PurchaseRequest $request, $itemId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $item = Item::findOrFail($itemId);
        $paymentMethod = [
            'コンビニ払い' => 1,
            'カード支払い' => 2,
        ];

        $order = new Order();
        $order->user_id = $user->id; // ログインユーザーのID
        $order->item_id = $itemId;
        $order->price = $item->price;
        $order->payment_method = $paymentMethod[$request->payment_method] ?? null;
        $order->shipping_address = $request->shipping_address;
        $order->save();

        // 支払い方法、住所のセッションを削除
        session()->forget(['payment_method','shipping_address']);

        return redirect('/');
    }
}
