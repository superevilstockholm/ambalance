<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Savings\SavingsHistory;
use App\Models\Settings\NotificationUser;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'body',
        'savings_history_id',
    ];

    public $timestamps = true;

    public function savings_history()
    {
        return $this->belongsTo(SavingsHistory::class);
    }

    public function receivers()
    {
        return $this->hasMany(NotificationUser::class, 'notification_id');
    }
}
