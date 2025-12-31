<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'theme',
        'organizer_id',
        'status',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_user')->withPivot('role', 'payment_status')->withTimestamps();
    }

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->wherePivot('role', 'reviewer')->withTimestamps();
    }
}