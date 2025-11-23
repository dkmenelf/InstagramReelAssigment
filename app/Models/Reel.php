<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class Reel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'reels';

    protected $fillable = [
        'user_id',
        'reels_url',
        'foundbyme_id',
        'ai_description',
        'status',
        'analized_at',
        'check_count'
    ];
}
