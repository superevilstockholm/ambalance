<?php

namespace App\Models\Savings;

use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\User;
use App\Models\Savings\SavingsHistory;

class Savings extends Model
{
    protected $table = 'savings';

    protected $fillable = [
        'user_id',
        'amount',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(SavingsHistory::class, 'savings_id');
    }
}
