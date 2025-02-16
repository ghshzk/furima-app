<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('mypage');
    }

    /* 編集する情報の表示 */
    public function edit()
    {
        $user = User::find(1);/*Auth::user();*/
        return view('editing', compact('user'));
    }

    /* 情報の更新 */
    public function update(Request $request)
    {
        $user = User::find(1);
        return view('editing', compact('user'));
    }
}


