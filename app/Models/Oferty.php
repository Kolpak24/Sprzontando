<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferty extends Model
{
    use HasFactory;

    protected $table = 'oferty';

    protected $fillable = [
        'user_id',
        'tytul',
        'opis',
        'lokalizacja',
        'cena',
        'rodzaj',
        'obraz',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
<<<<<<< Updated upstream
=======

    /**
     * RELACJA do wybranego wykonawcy (użytkownika).
     */
    public function chosenApplicant()
    {
        return $this->belongsTo(User::class, 'chosen_user_id');
    }
    public function rating()
    {
        return $this->hasOne(Rating::class, 'offer_id');
    }
>>>>>>> Stashed changes
}
