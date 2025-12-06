<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterWholesale extends Model
{
    use HasFactory;
    protected $table='master_wholesales';
    protected $fiilable=[
        'store_id',
        'wholesale'
        

    ]
}
