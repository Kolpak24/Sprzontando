<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Oferty;
use App\Models\User;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'oferta_id',
        'zglaszajacy_id',
        'zglaszany_id',
        'powody',
        'opis',
    ];

    public function oferta()
    {
        return $this->belongsTo(Oferty::class, 'oferta_id');
    }

    public function zglaszajacy()
    {
        return $this->belongsTo(User::class, 'zglaszajacy_id');
    }

    public function zglaszany()
    {
        return $this->belongsTo(User::class, 'zglaszany_id');
    }
}