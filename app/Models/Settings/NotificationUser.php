<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\User;
use App\Models\Settings\Notification;

class NotificationUser extends Model
{
    protected $table = 'notification_users';

    protected $fillable = [
        'user_id',
        'notification_id',
        'is_read',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
