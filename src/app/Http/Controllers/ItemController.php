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
        $items = Item::all();
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

    public function like($id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($id);

        if ($user->likedItems()->wherePivot('item_id', $item->id)->exists()){
            $user->likedItems()->detach($id);
        } else {
            $user->likedItems()->attach([$item->id]);
        }
        return redirect()->back();
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
