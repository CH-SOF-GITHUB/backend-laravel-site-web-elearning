<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'photoUrl', 'fileUrl', 'formation_id'];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
