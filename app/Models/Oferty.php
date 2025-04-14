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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
