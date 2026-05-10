<?php

namespace App\Models;

// Changed to Authenticatable so you can use it for Logins properly
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin_table';
    protected $primaryKey = 'admin_id';

    // Disable timestamps if your admin_table doesn't have created_at/updated_at
    public $timestamps = true; 

    protected $fillable = [
        'username', 
        'password', 
        'first_name', 
        'middle_name', 
        'last_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}