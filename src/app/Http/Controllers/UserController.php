<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
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
                Storage::delete('public/profile/' . $user->image_path);
            }
            /*プロフィール画像を取得 storage/app/public/profileディレクトリに保存*/
            $imagePath = $request->file('image_path')->store('public/profile');
            $imageName = basename($imagePath);
            $user->image_path = $imageName;
        }
        $user->fill($request->only([
            'name', 'postcode', 'address', 'building'
        ]));
        
        $user->save();

        return redirect('/?tab=mylist');
    }
}


