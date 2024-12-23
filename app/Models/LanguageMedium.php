<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageMedium extends Model
{
    use HasFactory;

    // Explicitly specify the table name if needed
    protected $table = 'language_mediums';

    // Add fillable attributes
    protected $fillable = ['language_name', 'code'];

    // Relation avec Enrollment
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'language_id');
    }
}
