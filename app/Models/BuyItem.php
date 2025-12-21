<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyItem extends Model
{
    use HasFactory;
    
    // 一括保存を許可するカラムを指定
    protected $guarded = ['id' ];
    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id', 'id');
    }

}
