<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Estimate extends Model
{
    use HasFactory;
    protected $table = 'estimates';
    protected $fillable = [
        'title',
        'adjustment',
        // 追加: store_id と role
        'store_id',
        'role',

    ];

    // 見積登録は多くの子（見積行）を持つ (1対多)
    public function items()
    {
    return $this->hasMany(EstimateItem::class, 'estimate_no', 'id');
    }
}
