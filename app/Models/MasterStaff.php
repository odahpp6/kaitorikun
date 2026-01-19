<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterStaff extends Model
{
    use HasFactory;
    protected $table = 'master_staff';
    protected $fillable = [
        'store_id',
        'staff_name',
        'position',
        'remarks',
    ];
}
