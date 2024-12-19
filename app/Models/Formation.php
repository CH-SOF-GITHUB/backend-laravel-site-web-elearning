<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'duration', 'price', 'category_id', 'photo', 'videoId'];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Définir la relation un-à-plusieurs (un cours peut avoir plusieurs commentaires)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relationship with Inscription
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
