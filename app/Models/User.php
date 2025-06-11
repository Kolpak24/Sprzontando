<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id',
    ];

    protected $dates = ['banned_until'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'banned_until' => 'datetime',
    ];
}

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    public function oferta()

{
    return $this->hasMany(Oferty::class, 'user_id');
}

public function receivedRatings()
{
    return $this->hasMany(Rating::class, 'rating_to_user_id');
}
public function completedOffers()
{
    return $this->hasMany(Oferty::class, 'chosen_user_id')
                ->where('status', 'zakonczona');
}

public function ratings()
    {
        return $this->hasMany(Rating::class, 'rating_to_user_id');
    }

public function isCurrentlyBanned(): bool
{
    return $this->role === 'banned' && $this->banned_until && now()->lessThan($this->banned_until);
}

}
