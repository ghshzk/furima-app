<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab','recommend');
        $user = Auth::user();

        /* お気に入り商品のみ表示 */
        if ($tab == 'mylist') {
            if($user) {
                $items = $user->likedItems()->with('orders')->get();
            } else {
                $items = collect();
            }
        } else {
            /* 出品商品を除外 */
            $query = Item::query();

            if ($user) {
                $query->where('user_id', '!=', $user->id);
            }

            $items = $query->with('orders')->get();
        }

        $items->each(function ($item) {
            $item->sold = $item->orders()->exists();
        });

        return view('top',compact('tab', 'items'));
    }

    public function show($id)
    {
        $item = Item::with(['comments.user', 'likedByUsers'])->findOrFail($id);
        $categories = Category::find($id);
        $commentCount = $item->comments->count();
        $likeCount = $item->likedByUsers->count();

        return view('exhibition', compact('item','categories' ,'commentCount','likeCount'));
    }

    public function create()
    {
        $user = Auth::user();
        $categories = Category::all();

        return view('sell',compact('user', 'categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();

        if($request->hasFile('image_path')){
            $path = $request->file('image_path')->store('public/items');
            $filename = str_replace('public/', '', $path);
        }else{
            $filename = null;
        }

        Item::create([
            'image_path' => $filename,
        ]);

        return redirect('/?tab=mylist');
    }

}
