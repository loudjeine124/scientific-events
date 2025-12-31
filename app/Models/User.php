<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // app/Models/User.php
    protected $fillable = [
        'name', 'email', 'password', 'role' 
    ];

    
    public function isAuthor()
    {
        return $this->role === 'author';
    }
    
    public function isReviewer() 
    {
        return $this->role === 'reviewer';
    }
    
    public function isOrganizer()
    {
        return $this->role === 'organizer';
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
    