<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comment(CommentRequest $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'content' => $request->content,
        ]);

        return redirect()->route('item.show',['item_id' => $item->id]);
    }
}
