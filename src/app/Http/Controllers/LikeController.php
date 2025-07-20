<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like($id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($id);

        //自分の出品した商品ならいいねの処理しない
        if ($item->user_id === $user->id) {
            return redirect()->back();
        }

        if ($user->likedItems()->where('item_id',$id)->exists()){
            $user->likedItems()->detach($id); //既にいいねがつけられている場合は解除する
        } else {
            $user->likedItems()->attach($id); //まだいいねがついていない場合は追加する
        }
        return redirect()->back(); //アクションを行う前のページへリダイレクト
    }
}
