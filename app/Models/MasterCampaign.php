<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCampaign extends Model
{
    use HasFactory;
    protected $table = 'master_campaigns';
    protected $fillable = [
        'store_id',
        'campaign',
        'distribution_date',
        'remarks',
    ];



}
