<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // 一括保存を許可するカラムを指定
    protected $guarded = ['id' ];
    
    /**
     * リレーション：この顧客に紐づく全ての取引を取得
     */
    public function deals()
    {
        return $this->hasMany(Deal::class, 'customer_id', 'id');
    }
    public function buyItems()
    {
        return $this->hasManyThrough(
        BuyItem::class,    // 1. 最終目的地（欲しいモデル）
        Deal::class,       // 2. 経由地（中間に挟まるモデル）
        'customer_id',     // 3. 中間テーブル（Deal）にある親を指すID
        'deal_id',         // 4. 目的地テーブル（BuyItem）にある中間を指すID
        'id',              // 5. 親テーブル（Customer）のローカルキー
        'id'               // 6. 中間テーブル（Deal）のローカルキー
        );
    }

}
