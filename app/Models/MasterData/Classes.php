<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Models
use App\Models\MasterData\Teacher;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'class_name',
        'description',
        'teacher_id',
    ];

    public $timestamps = true;

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
