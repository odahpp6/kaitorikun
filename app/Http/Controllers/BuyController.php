<?php

namespace App\Http\Controllers;
use App\Models\BuyItem;
use App\Models\Customer;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ★追加：トランザクション用
use Illuminate\Http\RedirectResponse; // ★追加：戻り値の型用
use Illuminate\Support\Facades\Auth; // ★Authファサードの追加

class BuyController extends Controller
{
public function purchase(){
    return view('purchase.register');

    }






}
