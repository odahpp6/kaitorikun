<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Deal;
use App\Models\MasterWholesale;

class Sale extends Model
{
    use HasFactory;
    protected $table = 'sale';
    // 一括保存を許可するカラムを指定
    protected $guarded = ['id' ];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function wholesaleInfo()
    {
        return $this->belongsTo(MasterWholesale::class, 'wholesale');
    }
}
