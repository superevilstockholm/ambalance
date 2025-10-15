<?php

namespace App\Models\Savings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Models
use App\Models\User;
use App\Models\Savings\Savings;

class SavingsHistory extends Model
{
    use HasFactory;

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

    protected static function booted()
    {
        static::created(function ($history) {
            $savings = $history->savings;

            if ($history->type === 'in') {
                $savings->amount += $history->amount;
            } elseif ($history->type === 'out') {
                $savings->amount -= $history->amount;
            }

            $savings->save();
        });
    }
}
