<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    
    // 一括保存を許可するカラムを指定
    protected $guarded = ['id' ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function buyItems()
    {
        return $this->hasMany(BuyItem::class, 'deal_id', 'id');
    }
   
}
