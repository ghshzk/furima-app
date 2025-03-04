<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab','recommend');
        $items = Item::all();
        return view('top',compact('tab', 'items'));
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
