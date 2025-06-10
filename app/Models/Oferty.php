<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Oferty extends Model
{
    use HasFactory;

    protected $table = 'oferty';

    protected $casts = [
        'applicants' => 'array',  // już miałeś
    ];

    protected $fillable = [
        'user_id',
        'tytul',
        'opis',
        'lokalizacja',
        'cena',
        'rodzaj',
        'obraz',
        'chosen_user_id',  // dopiszemy to, żeby móc masowo przypisywać
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

}
