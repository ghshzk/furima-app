<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //$user = Auth::user(); //現在ログインしているユーザーの情報を変数に代入

        $tab = $request->query('tab','sell');

        if ($tab === 'buy'){
            $items = Item::whereIn('id',Order::where('user_id', $user->id)->pluck('item_id'))->get();
        } else {
            //$items = Item::where('user_id', $user->id)->get();
        }

        return view('mypage.index',compact('tab','items'));
    }
}
