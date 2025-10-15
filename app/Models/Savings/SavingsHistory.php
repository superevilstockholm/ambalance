<?php

namespace App\Models\Savings;

use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\User;
use App\Models\Savings\Savings;

class SavingsHistory extends Model
{
    protected $table = 'savings_history';

    protected $fillable = [
        'savings_id',
        'user_id',
        'amount',
        'type', // in / out
        'description',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savings()
    {
        return $this->belongsTo(Savings::class);
    }
}
