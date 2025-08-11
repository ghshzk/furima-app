<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function chat(Request $request)
    {
        return view('transaction_chat');
    }
}
