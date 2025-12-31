<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'abstract',
        'keywords',
        'type',
        'file_path',
        'status',
        'event_id',
        'author_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'submission_session')->withPivot('order')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
