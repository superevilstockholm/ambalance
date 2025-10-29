<?php

namespace App\Models\Savings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Models
use App\Models\User;
use App\Models\Savings\Savings;
use App\Models\Settings\Notification;
use App\Models\Settings\NotificationUser;

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

            if ($savings->user->role === 'student') {
                $typeText = $history->type === 'in' ? 'Setoran Tabungan' : 'Penarikan Tabungan';
                $message = $history->type === 'in'
                    ? 'Setoran tabungan sebesar Rp ' . number_format($history->amount, 0, ',', '.') . ' telah berhasil masuk ke akun kamu.'
                    : 'Penarikan tabungan sebesar Rp ' . number_format($history->amount, 0, ',', '.') . ' telah berhasil diproses.';

                $notification = Notification::create([
                    'savings_history_id' => $history->id,
                    'title' => $typeText,
                    'body' => $message,
                ]);

                NotificationUser::create([
                    'notification_id' => $notification->id,
                    'user_id' => $savings->user_id,
                    'is_read' => false,
                ]);
            }
        });
    }
}
