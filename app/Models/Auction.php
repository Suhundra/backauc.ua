<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $fillable = [
        'user_id', 'title', 'start_price', 'last_bet', 'status', 'description', 'images', 'auc_end_time', 'auc_winner',
    ];
}
