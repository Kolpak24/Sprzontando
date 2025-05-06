<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['oferta_id', 'powody', 'opis'];

    public function oferta()
    {
        return $this->belongsTo(Oferty::class, 'oferta_id');
    }
}
