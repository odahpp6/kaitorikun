<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateItem extends Model
{
    use HasFactory;
   // 修正: テーブル名を複数形・小文字にする
    protected $table = 'estimate_items';
    // ★★★★ この行を追加する ★★★★
    public $timestamps = false;
   // 変更後
    protected $fillable = [
    'estimate_no', // ★追加
    'text',
    'num1',
    'num2',
    'remarks'
    ];
  

    // 見積行は親（見積登録）に属する (多対1)
    public function estimate()
    {
    return $this->belongsTo(Estimate::class, 'estimate_no', 'id');
    }

}
