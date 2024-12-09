<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_inscription',
        'status',
        'price',
        'formation_id',
        'user_id',
    ];

    // Relationship with Formation
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Modify to reference User model
    }
}
