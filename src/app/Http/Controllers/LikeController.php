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

        if ($user->likedItems()->where('item_id',$id)->exists()){
            $user->likedItems()->detach($id);
        } else {
            $user->likedItems()->attach($id);
        }
        return redirect()->back();
    }
}
