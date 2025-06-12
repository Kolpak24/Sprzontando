<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'rating_from_user_id',
        'rating_to_user_id',
        'stars',
        'comment',
    ];

    /**
     * Relacja: ocena należy do jednej oferty.
     */
    public function offer()
    {
        return $this->belongsTo(Oferty::class, 'offer_id');
    }

    /**
     * Użytkownik, który wystawił ocenę.
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'rating_from_user_id');
    }

    /**
     * Użytkownik, który otrzymał ocenę.
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'rating_to_user_id');
    }
    public function ratingFromUser()
    {
        return $this->belongsTo(User::class, 'rating_from_user_id');
    }

}
