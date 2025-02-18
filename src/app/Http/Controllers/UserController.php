<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Requests\ProfileRequest;

class UserController extends Controller
{
    public function index()
    {
        return view('mypage');
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


