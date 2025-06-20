<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferty extends Model
{
    use HasFactory;

    protected $table = 'oferty';

    protected $casts = [
    'applicants' => 'array',
    ];

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
}
