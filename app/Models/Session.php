<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_time',
        'end_time',
        'room',
        'event_id',
        'chair_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function chair()
    {
        return $this->belongsTo(User::class, 'chair_id');
    }

    public function submissions()
    {
        return $this->belongsToMany(Submission::class, 'submission_session')->withPivot('order')->withTimestamps()->orderBy('order');
    }
}
