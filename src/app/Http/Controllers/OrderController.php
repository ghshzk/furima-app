<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

class OrderController extends Controller
{
    public function index($itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);
        $order = new Order([
            'postcode' => session('order_postcode', $user->postcode),
            'address' => session('order_address', $user->address),
            'building' => session('order_building', $user->building),
        ]);
        $payment_method = session('payment_method', '未選択');

        return view('purchase',compact('user','item', 'order','payment_method'));
    }

    /* 住所変更ページ表示 */
    public function edit($itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);
        $order = new Order([
            'postcode' => session('order_postcode', $user->postcode),
            'address' => session('order_address', $user->address),
            'building' => session('order_building', $user->building),
        ]);

        return view('address', compact('user', 'item','order'));
    }

    /* 住所の更新 */
    public function update(AddressRequest $request, $itemId)
    {
        session([
            'order_postcode' => $request->postcode,
            'order_address' => $request->address,
            'order_building' => $request->building,
        ]);

        return redirect('/purchase/{item_id}');
    }

    public function updatePayment(PurchaseRequest $request, $itemId)
    {
        session(['payment_method' => $request->payment_method]);

        return redirect('purchase',['item_id' => $itemId]);
    }
}
