<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
       
        'instagram_token',
        'instagram_token_expires_at'
    ];


    protected $hidden = [
        'remember_token',
    ];

 
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}