<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'author',
        'text',
        'formation_id'
    ];

    // Relation inverse : un commentaire appartient à une formation
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
