<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab','recommend');
        $items = Item::all();
        return view('top',compact('tab', 'items'));
    }
}
