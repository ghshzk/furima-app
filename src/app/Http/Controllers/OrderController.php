<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Stripe\Stripe;
//use Stripe\Checkout\Session as StripeSession;

class OrderController extends Controller
{
    protected $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

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

        switch ($request->payment_method) {
            case 'コンビニ支払い':
                $paymentMethodTypes = ['konbini'];
                break;
            case 'カード支払い':
                $paymentMethodTypes = ['card'];
                break;
        }

        try {
            $checkoutSession = $this->stripe->createCheckoutSession([
                'payment_method_types' => $paymentMethodTypes,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&item_id=' . $item->id,
                'cancel_url' => route('checkout.cancel') . '?item_id=' . $item->id,
                'metadata' => [
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'payment_method' => $request->payment_method,
                    'shipping_address' => $request->shipping_address,
                ],
            ]);
            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            \Log::error('Stripe Checkout Error: ' . $e->getMessage());
            return back()->withErrors(['error' => '決済ページの作成に失敗しました']);
        }
    }

    public function checkoutSuccess(Request $request)
    {
        \Log::info('【テスト用】retrieveCheckoutSession呼ばれたよ');

        $sessionId = $request->query('session_id');
        $itemId = $request->query('item_id');

        if (!$sessionId) {
            return redirect()->route('purchase.show', ['item_id' => $itemId])
                ->withErrors(['error' => '決済情報が確認できませんでした']);
        }

        try {
            \Log::info('【確認】retrieveCheckoutSessionを呼んだよ');
            $session = $this->stripe->retrieveCheckoutSession($sessionId);

            \Log::info('【確認】Session retrieved:', (array)$session);

            $item = Item::findOrFail($itemId);
            $paymentMethodMap = [
                'コンビニ支払い' => 1,
                'カード支払い' => 2,
            ];

            \Log::info('payment_method:', [$session->metadata->payment_method]);

            Order::create([
                'user_id' => $session->metadata->user_id,
                'item_id' => $item->id,
                'price' => $item->price,
                'payment_method' => $paymentMethodMap[$session->metadata->payment_method] ?? 0,
                'shipping_address' => $session->metadata->shipping_address,
            ]);

            session()->forget(['payment_method', 'shipping_address']);

            $paymentMethodMapForDisplay = [
                'card' => 'カード支払い',
                'konbini' => 'コンビニ支払い',
            ];

            $paymentTypeCode = $session->payment_method_types[0] ?? 'unknown';
            $displayPaymentMethod = $paymentMethodMapForDisplay[$paymentTypeCode] ?? '不明な支払い方法';
            return view('checkout_success', compact('session', 'displayPaymentMethod'));

        } catch (\Exception $e) {
            \Log::error('Stripe Session Retrieve Error: ' . $e->getMessage());

            return redirect()->route('purchase.show', ['item_id' => $itemId])
            ->withErrors(['error' => '決済情報の取得に失敗しました']);
        }
    }

    public function checkoutCancel(Request $request)
    {
        $itemId = $request->query('item_id');
        session()->forget(['payment_method', 'shipping_address']);
        return redirect()->route('purchase.show',  ['item_id' => $itemId])
            ->with('cancel_message', '決済をキャンセルしました');
    }
}
