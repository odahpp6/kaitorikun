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
        return $this->hasMany(Deal::class);
    }
}