<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderMessage;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); //現在ログインしているユーザーの情報を変数に代入

        $unreadCount = OrderMessage::unreadFor($user->id)->count();

        $tab = $request->query('tab','sell');

        $items = null;
        $orders = null;

        if ($tab === 'buy'){
            $items = Item::boughtBy($user->id)->get();

        } elseif ($tab === 'transaction') {
            $orders = Order::inTransaction($user->id)
                ->withCount([
                    'messages as unread_messages_count' => function ($query) use ($user) {
                        $query->unreadFor($user->id);
                    }
                ])
                ->get()
                ->sortByDesc(function ($order) {
                    return $order->latestMessage ? $order->latestMessage->created_at : $order->updated_at;
                });
        } else {
            $items = Item::where('user_id', $user->id)->get();
        }

        return view('mypage',compact('tab','user','items', 'orders', 'unreadCount'));
    }

    /* 編集する情報の表示 */
    public function edit()
    {
        $user = Auth::user();
        return view('editing', compact('user'));
    }

    /* 情報の更新 */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        if($request->hasFile('image_path')){
            if($user->image_path){
                Storage::delete($user->image_path);
            }
            /*プロフィール画像を取得 storage/app/public/profileディレクトリに保存*/
            $imagePath = $request->file('image_path')->store('public/profile');
            $user->image_path = $imagePath;
        }
        $user->fill($request->only([
            'name', 'postcode', 'address', 'building'
        ]));

        $user->save();

        return redirect()->route('mypage');
    }
}