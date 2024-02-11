<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetHistory extends Model
{
    protected $fillable = [
        'user_id', 'auc_id', 'amount',
    ];
}
