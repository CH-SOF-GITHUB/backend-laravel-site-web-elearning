<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    // fillable fields
    protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'email',
        'formation_id',
        'price',
        'language_id',
        'promo_code',
        'comment',
        // Pas besoin de l'inclure car 'draft' est défini par défaut
    ];

     // Relation avec le modèle User
     public function user()
     {
         return $this->belongsTo(User::class);
     }
 
     // Relation avec le modèle Formation
     public function formation()
     {
         return $this->belongsTo(Formation::class);
     }
 
     // Relation avec le modèle LanguageMedium
     public function language()
     {
         return $this->belongsTo(LanguageMedium::class, 'language_id');
     }
 
     // Méthode pour valider l'inscription après paiement
     public function validateEnrollment()
     {
         $this->status = 'validated';
         $this->save();
     }
}
