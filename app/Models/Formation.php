<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'duration', 'price', 'category_id', 'photo'];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
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
